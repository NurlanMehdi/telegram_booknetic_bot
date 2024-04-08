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
        $this->apiEndpoint = $config["apiEndpoint"];
    }

    public function handleIncomingMessages()
    {
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
            $html = str_get_html(htmlentities($data['html']));

            $service_list_div = $html->find("div[class=bkntc_service_list]", 0);

            if ($service_list_div) {
                $service_cards = $service_list_div->find(
                    "div.booknetic_service_card"
                );

                $services = '';
                foreach ($service_cards as $card) {
                    $title = $card->find("span.booknetic_service_title_span", 0)->plaintext;
                    $id = $card->getAttribute("data-id");
                    $price = $card->find("div.booknetic_service_card_price", 0)->plaintext;

                    $services .= "[<b>ID</b>:$id] $title - $price \n";

                }
            }

            // Clean up memory
            $html->clear();
            unset($html);

            $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Zəhmət olmasa maraqlandığınız servisin ID-sini qeyd edin.\n{$services}",$this->apiEndpoint);
            //$telegramHelper->sendMessage($chatId, $services,$this->apiEndpoint);
        }
    }
}
