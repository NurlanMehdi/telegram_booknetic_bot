<?php

include_once __DIR__ . "/../Helper/TelegramHelper.php";
include_once __DIR__ . "../BookneticController.php";
include_once __DIR__ . "/../vendor/simplehtmldom_1_9_1/simple_html_dom.php";
//include_once __DIR__ . "/../config/database.php" ;
include_once __DIR__ . "/../models/Service.php";
include_once __DIR__ . "/../models/Messages.php";

class TelegramController
{
    private $apiEndpoint;
    private $bookneticController;
    private $messagesModel;

    public function __construct()
    {
        $config = require_once __DIR__ . "/../config/config.php";
        $this->apiEndpoint = $config["apiEndpoint"];
        $this->bookneticController = new BookneticController();
        $this->messagesModel = new Messages();
    }

    public function handleIncomingMessages()
    {
        $response = file_get_contents(
            $this->apiEndpoint . "getUpdates?offset=-1"
        );
        $updates = json_decode($response, true);
    

        if (isset($updates["result"])) {
            foreach ($updates["result"] as $update) {
                if(isset($update["message"]))
                {
                    $message = $update["message"];
                    $chatId = $message["chat"]["id"];
                    $messageText = $message["text"];
                    $messageId = $message["message_id"];
                    $updateId = $update["update_id"];
                    $username = isset($message["chat"]["username"])
                        ? $message["chat"]["username"]
                        : "";
    
                    $checkMessage = $this->messagesModel->getMessageById(
                        $message["message_id"]
                    );
    
                    if (!$checkMessage) {
                        $this->messagesModel->addMessage(
                            $chatId,
                            $messageId,
                            $updateId,
                            $messageText,
                            $username
                        );
                    }
    
                    $this->processMessage($chatId, $username, $messageText);

                }elseif(isset($update['callback_query']['data']) && $update['callback_query']['data']== 'reservation'){
                    $chatId = $update['callback_query']['message']['chat']['id'];
                    $getMessageData = $this->messagesModel->getMessageData($chatId);
                    $userInfo = explode(",", $getMessageData['info']['message']);

                    $contents = [
                        [
                            "location" => -1,
                            "staff" => -1,
                            "service_category" => "",
                            "service" => $getMessageData['service']['message'],
                            "service_extras" => [],
                            "date" => $getMessageData['date']['message'],
                            "time" => $getMessageData['time']['message'],
                            "brought_people_count" => 0,
                            "recurring_start_date" => "",
                            "recurring_end_date" => "",
                            "recurring_times" => "{}",
                            "appointments" => "[]",
                            "customer_data" => [
                                "first_name"=>"{$userInfo[0]}",
                                "last_name"=>"{$userInfo[1]}",
                                "email"=>"{$userInfo[2]}",
                                "phone"=>{$userInfo[3]}
                            ],
                        ],
                    ];
                    var_dump($data);
                }
             

            }
        }
    }

    private function processMessage($chatId, $username, $messageText)
    {
        $telegramHelper = new TelegramHelper();
        $services = new Services();
        $dateFormat = "d-m-Y";
        $dateTime = DateTime::createFromFormat($dateFormat, $messageText);
        $time = strtotime($messageText);
        
        if ($messageText == "/start") {
            $data = $this->bookneticController->getDataService(
                "bkntc_get_data_service",
                3
            );

            // Create a DOM object
            $html = str_get_html(html_entity_decode($data["html"]));

            $service_list_div = $html->find("div[class=bkntc_service_list]", 0);

            if ($service_list_div) {
                $service_cards = $service_list_div->find(
                    "div.booknetic_service_card"
                );

                $servicesInfo = "";
                foreach ($service_cards as $card) {
                    $title = $card->find("span.booknetic_service_title_span", 0)
                        ->plaintext;
                    $service_id = $card->getAttribute("data-id");
                    $price = $card->find("div.booknetic_service_card_price", 0)
                        ->plaintext;
                    $checkService = $services->getServiceById($service_id);
                    if (!$checkService) {
                        $services->addService($service_id, $title, $price);
                    }

                    $servicesInfo .= "[<b>ID</b>:$service_id] $title - $price \n";
                }
            }

            // Clean up memory
            $html->clear();
            unset($html);

            $telegramHelper->sendMessage(
                $chatId,
                "Salam {$username}! Mən Booking Bot for Booknetic. Zəhmət olmasa maraqlandığınız servisin ID-sini qeyd edin.\n{$servicesInfo}",
                $this->apiEndpoint
            );
            $this->messagesModel->updateLatestMessageKey("start");
        } elseif ($services->getServiceById($messageText)) {
            $this->messagesModel->updateLatestMessageKey("service_id");
            $telegramHelper->sendMessage(
                $chatId,
                "Zəhmət olmasa tarix qeyd edin. (Nümunə: 10-04-2024)",
                $this->apiEndpoint
            );
        } elseif($dateTime) {
            $getMessageData = $this->messagesModel->getMessageData($chatId);

            $contents = [
                [
                    "location" => -1,
                    "staff" => -1,
                    "service_category" => "",
                    "service" => $getMessageData['service']['message'],
                    "service_extras" => [],
                    "date" => "",
                    "time" => "",
                    "brought_people_count" => 0,
                    "recurring_start_date" => "",
                    "recurring_end_date" => "",
                    "recurring_times" => "{}",
                    "appointments" => "[]",
                    "customer_data" => [],
                ],
            ];
            $data = $this->bookneticController->getDataService(
                "bkntc_get_data_date_time",
                3,
                "cart",
                $contents
            );
            $dateTime->setTimezone(new DateTimeZone("UTC"));

            if (
                !empty($data["data"]["dates"][$dateTime->format("Y-m-d")])
            ) {
                $date = $data["data"]["dates"][$dateTime->format("Y-m-d")];
                $timeRange = "";
                foreach ($date as $number => $val) {
                    $number++;
                    $timeRange .= "<b>{$number}</b> : {$val["start_time"]} - {$val["end_time"]} \n";
                }
                $telegramHelper->sendMessage(
                    $chatId,
                    "Sizə uyğun saat aralığının başlama saatını seçin. (Nümunə: 18:30 ) \n{$timeRange}",
                    $this->apiEndpoint
                );
            } else {
                $timeRange = "";

                foreach ($data["data"]["dates"] as $time => $val) {
                    if (!empty($val)) {
                        $timeFormat = new DateTime($time);

                        $timeRange .= "{$timeFormat->format("d-m-Y")} \n";
                    }
                }
                $telegramHelper->sendMessage(
                    $chatId,
                    "Seçdiyiniz günə uyğun nəticə tapılmadı. Zəhmət olmasa yeni tarix seçin. \n{$timeRange}",
                    $this->apiEndpoint
                );
            }
            $this->messagesModel->updateLatestMessageKey("date");
        } elseif($time !== false && date("H:i", $time) === $messageText){
           
            $telegramHelper->sendMessage(
                $chatId,
                "Ad,Soyad,Email və Mobil nömrə qeyd edin.(Nümunə : Nurlan,Gulaliyev,example@gmail.com,0515555555). \n",
                $this->apiEndpoint
            );

            $this->messagesModel->updateLatestMessageKey("time");

        }else{
            $userInfo = explode(",", $messageText);

            $getMessageData = $this->messagesModel->getMessageData($chatId);
            $serviceData = $services->getServiceById($getMessageData['service']['message']);
          
            $servicesInfo = "1: <b>{$serviceData['title']}</b> - ".$serviceData['price']."\n2: <b>Tarix</b> - {$getMessageData['date']['message']}\n3: <b>Saat</b> - {$getMessageData['time']['message']}\n4: <b>Ad</b> - {$userInfo[0]}\n5: <b>Soyad</b> - {$userInfo[1]}\n6: <b>Email</b> - {$userInfo[2]}\n7: <b>Mobil nömrə</b> - {$userInfo[3]}\n  ";

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'Rezerv et', 'callback_data' => 'reservation']
                    ]
                ]
            ];
            
            $replyMarkup = json_encode($keyboard);

            $telegramHelper->sendMessage(
            $chatId,
            $servicesInfo,
            $this->apiEndpoint,
            $replyMarkup
       );
        
            $this->messagesModel->updateLatestMessageKey("info");
        }
    }
}
