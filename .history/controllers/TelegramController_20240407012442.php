<?php

include_once __DIR__ . '/../Helper/TelegramHelper.php';
include_once __DIR__ . '../BookneticController.php';
include_once __DIR__ . '/../vendor/simplehtmldom_1_9_1/simple_html_dom.php';

class TelegramController
{
    private $apiEndpoint;

    public function __construct()
    {
        $config = require_once __DIR__ . '/../config/config.php';
        $this->apiEndpoint = $config['apiEndpoint'];
    }

    public function handleIncomingMessages()
    {
        // Telegram'dan mesajları alıyoruz
        $response = file_get_contents($this->apiEndpoint . 'getUpdates?limit=1');
        $updates = json_decode($response, true);

        if (isset($updates['result'])) {
            foreach ($updates['result'] as $update) {
                $message = $update['message'];
                $chatId = $message['chat']['id'];
                $messageText = $message['text'];
                $username = isset($message['chat']['username']) ? $message['chat']['username'] : '';

                $this->processMessage($chatId, $username, $messageText);
            }
        }
    }

    private function processMessage($chatId, $username, $messageText)
    {
        $telegramHelper = new TelegramHelper();

        if ($messageText == '/start') {
            $bookneticController = new BookneticController();
            $tenantId = 3; 
            $data = $bookneticController->getDataService($tenantId);
      
// Create a DOM object
$html = str_get_html($data['html']);


// Find the div container with class 'bkntc_service_list'
$service_list_div = $html->find('div', 0);
var_dump($service_list_div);
if ($service_list_div) {
    // Find all div elements with class 'booknetic_service_card' within 'bkntc_service_list'
    $service_cards = $service_list_div->find('div.booknetic_service_card');

    // Loop through each service card
    foreach ($service_cards as $card) {
        $title = $card->find('span.booknetic_service_title_span', 0)->plaintext;
        $image_url = $card->find('img.booknetic_card_service_image', 0)->src;
        $price = $card->find('div.booknetic_service_card_price', 0)->plaintext;

        // Output the extracted data
        echo "Title: $title\n";
        echo "Image URL: $image_url\n";
        echo "Price: $price\n\n";
    }
}

// Clean up memory
$html->clear();
unset($html);
           



          //  $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?",$this->apiEndpoint);
        }
    }
}
