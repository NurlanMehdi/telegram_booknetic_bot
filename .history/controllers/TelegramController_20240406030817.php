<?php

include_once __DIR__ . '/../Helper/TelegramHelper.php';
include_once __DIR__ . '../BookneticController.php';
include_once __DIR__ . '/../vendor/simplehtmldom_1_5/simple_html_dom.php';

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
            echo '<pre>';

            $html = str_get_html($data['html']);
           
            $rows = $html->find('booknetic_service_card demo booknetic_fade'); // Find all rows in the table
            
            //Loop through each row
            foreach ($rows as $row) {
                //Loop through each child (cell) of the row
                foreach ($row->children() as $cell) {
                    echo $cell->plaintext; // Display the contents of each cell - this is the value you want to extract
                }
            }


          //  $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?",$this->apiEndpoint);
        }
    }
}
