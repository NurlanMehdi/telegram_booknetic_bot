<?php

include_once __DIR__ . '/../Helper/TelegramHelper.php';
include_once __DIR__ . '../BookneticController.php';

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

            $html = $data['html']; 
            $doc = new DOMDocument();
@$doc->loadHTML($html); // HTML içeriğini yükle (Hata ayıklama modunu kapatarak yüklemeyi deneyin)

// XPath oluştur
$xpath = new DOMXPath($doc);

// Belirli bir class'a sahip tüm span öğelerini seç
$spanElements = $xpath->query('SPAN');
var_dump($spanElements);



          //  $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?",$this->apiEndpoint);
        }
    }
}
