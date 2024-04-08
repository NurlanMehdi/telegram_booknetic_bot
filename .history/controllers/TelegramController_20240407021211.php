<?php

include_once __DIR__ . "/../Helper/TelegramHelper.php";
include_once __DIR__ . "../BookneticController.php";
include_once __DIR__ . "/../vendor/simplehtmldom_1_9_1/simple_html_dom.php";

class TelegramController
{
    private $apiEndpoint;

    public function __construct()
    {
        $config = require_once __DIR__ . "/../config/config.php";
        $this->apiEndpoint = $config["apiEndpoint"]';
    }

    public function handleIncomingMessages()
    {
        // Telegram'dan mesajları alıyoruz
        $response = file_get_contents(
            $this->apiEndpoint . "getUpdates?limit=1"
        );
        $updates = json_decode($response, true);

        if (isset($updates["result"])) {
            foreach ($updates["result"] as $update) {
                $message = $update["message"];
                $chatId = $message["chat"]["id"];
                $messageText = $message["text"];
                $username = isset($message["chat"]["username"])
                    ? $message["chat"]["username"]
                    : "";

                $this->processMessage($chatId, $username, $messageText);
            }
        }
    }

    private function processMessage($chatId, $username, $messageText)
    {
        $telegramHelper = new TelegramHelper();

        if ($messageText == "/start") {
            $bookneticController = new BookneticController();
            $tenantId = 3;
            $data = $bookneticController->getDataService($tenantId);

            // Create a DOM object
            $html = str_get_html(
                '"<div class="bkntc_service_list"><div data-parent="1" class="booknetic_service_category booknetic_fade">Academic Subjects<span data-parent="1"></span></div> <div class="booknetic_service_card demo booknetic_fade" data-id="11" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/c36e412bd117363189f50d235d4c62a7.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Mathematics</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="40.0000"> $40.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="12" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/a1cd692866ac1e82251b958ce8068841.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Science</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="45.0000"> $45.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="13" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/8f895509ec3c5e22d1b1aedccb9be040.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Coding/Programming</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="60.0000"> $60.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div data-parent="0" class="booknetic_service_category booknetic_fade">Language Learning<span data-parent="0"></span></div> <div class="booknetic_service_card demo booknetic_fade" data-id="14" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/58d0cd6c5b0d4c6063c891092822b4f0.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Spanish class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="40.0000"> $40.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="15" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/972d143ae8656de6de3d2b922b5de438.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">French class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="45.0000"> $45.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="16" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/af6c12262251fbbe6a103e605ef3fe3f.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">English class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="35.0000"> $35.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> </div>"'
            );

            // Find the div container with class 'bkntc_service_list'
            $service_list_div = $html->find("div[class=bkntc_service_list]", 0);

            if ($service_list_div) {
                // Find all div elements with class 'booknetic_service_card' within 'bkntc_service_list'
                $service_cards = $service_list_div->find(
                    "div.booknetic_service_card"
                );

                $services = '';
                foreach ($service_cards as $card) {
                    $title = $card->find("span.booknetic_service_title_span", 0)
                        ->plaintext;
                    $id = $card->getAttribute("data-id");
                    $price = $card->find("div.booknetic_service_card_price", 0)
                        ->plaintext;

                        $services .= @"[<b>ID</b>:$id] $title - $price";
                      
                    // $services[] = [
                    //     "id" => $id,
                    //     "title" => $title,
                    //     "price" => $price,
                    // ];
                }
                // echo "<pre>";
                // var_dump($services);
            }

            // Clean up memory
            $html->clear();
            unset($html);

            $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Zəhmət olmasa maraqlandığınız servisi seçin.",$this->apiEndpoint);
            $telegramHelper->sendMessage($chatId, $services,$this->apiEndpoint.'&text=HTML_CODE&parse_mode=html');
        }
    }
}
