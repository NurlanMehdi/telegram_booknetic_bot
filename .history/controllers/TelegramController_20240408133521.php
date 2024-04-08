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

    public function __construct()
    {
        $config = require_once __DIR__ . "/../config/config.php";
        $this->apiEndpoint = $config["apiEndpoint"];
        $this->bookneticController = new BookneticController();
    }

    public function handleIncomingMessages()
    {
        $response = file_get_contents(
            $this->apiEndpoint . "getUpdates?offset=-1"
        );
        $updates = json_decode($response, true);

        if (isset($updates["result"])) {
            foreach ($updates["result"] as $update) {
                $message = $update["message"];
                $chatId = $message["chat"]["id"];
                $messageText = $message["text"];
                $messageId = $message['message_id'];
                $updateId = $update['update_id'];
                $username = isset($message["chat"]["username"])
                    ? $message["chat"]["username"]
                    : "";
                    

                $messages = new Messages();
                $checkMessage = $messages->getMessageById($message['message_id']);

                if(!$checkMessage){
                    $messages->addMessage($chatId, $messageId, $updateId, $messageText, $username);
                }

                $this->processMessage($chatId, $username, $messageText);
            }
        }
    }

    private function processMessage($chatId, $username, $messageText)
    {
        $telegramHelper = new TelegramHelper();
        $services = new Services();

        if ($messageText == "/start") {
            $data = $this->bookneticController->getDataService('bkntc_get_data_service',3);

            // Create a DOM object
            $html = str_get_html(html_entity_decode($data['html']));

            $service_list_div = $html->find("div[class=bkntc_service_list]", 0);

            if ($service_list_div) {
                $service_cards = $service_list_div->find(
                    "div.booknetic_service_card"
                );

                $servicesInfo = '';
                foreach ($service_cards as $card) {
                   
                    $title = $card->find("span.booknetic_service_title_span", 0)->plaintext;
                    $service_id = $card->getAttribute("data-id");
                    $price = $card->find("div.booknetic_service_card_price", 0)->plaintext;
                    $checkService = $services->getServiceById($service_id);
                    if(!$checkService){
                        $services->addService($service_id, $title, $price);
                    }
                    

                    $servicesInfo .= "[<b>ID</b>:$service_id] $title - $price \n";

                }
            }

            // Clean up memory
            $html->clear();
            unset($html);

            $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Zəhmət olmasa maraqlandığınız servisin ID-sini qeyd edin.\n{$servicesInfo}",$this->apiEndpoint);
            //$telegramHelper->sendMessage($chatId, $services,$this->apiEndpoint);
        }elseif($services->getServiceById($messageText))
        {

               $telegramHelper->sendMessage($chatId, "Zəhmət olmasa tarix qeyd edin. (Nümunə: 10-04-2024)",$this->apiEndpoint);
        }else{
            $dateFormat = "d-m-Y"; 
            $dateTime = DateTime::createFromFormat($dateFormat, $messageText);
            if($dateTime)
            {
                $contents = [
                    [
                        'location' => -1,
                        'staff' => -1,
                        'service_category' => '',
                        'service' => 11,
                        'service_extras' => [],
                        'date' => '',
                        'time' => '',
                        'brought_people_count' => 0,
                        'recurring_start_date' => '',
                        'recurring_end_date' => '',
                        'recurring_times' => '{}',
                        'appointments' => '[]',
                        'customer_data' => []
                    ]
                    ];
                $data = $this->bookneticController->getDataService('bkntc_get_data_date_time',3,'cart',$contents);
                $dateTime->setTimezone(new DateTimeZone('UTC'));

                
                if(!empty($data['data']['dates'][$dateTime->format('Y-m-d')])){

                    var_dump($data['data']['dates'][$dateTime->format('Y-m-d')]);
                }else{
                    $aviableDates = [];

                    foreach($data['data']['dates'] as $time =>  $val)
                    {
                        if(!empty($val)){
                            $aviableDates[$time] = $val;
                        }
                    }
                    echo '<pre>';
                  
                    $aviableDatesMessage = '';
                    foreach($aviableDates as $key => $date)
                    {
                        $aviableDatesMessage .= "[<b>ID</b>:$key] . $date['start_time_format'] - $date['end_time_format'] \n";
                       
                    }
                    var_dump($aviableDatesMessage);
                }
            }
        }
    }
}