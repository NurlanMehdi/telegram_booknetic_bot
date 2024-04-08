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

// DOMDocument oluştur
$doc = new DOMDocument();
@$doc->loadHTML($html); // HTML içeriğini yükle (Hata ayıklama modunu kapatarak yüklemeyi deneyin)

// Servisleri içeren ana div'i seç
$serviceList = $doc->getElementsByTagName('div')->item(0); // bkntc_service_list div'i

// Servis kartlarını seç
$serviceCards = $serviceList->getElementsByTagName('div');

// Her bir servis kartını döngü ile işle
foreach ($serviceCards as $card) {
    if ($card->getAttribute('class') === 'booknetic_service_card') {
        // Servis başlığını ve fiyatını seç
        $titleElement = $card->getElementsByTagName('span')->item(0); // başlık span'i
        $priceElement = $card->getElementsByTagName('div')->item(2); // fiyat div'i

        // Başlık ve fiyat bilgilerini al
        $title = $titleElement->nodeValue; // servis başlığı
        $price = $priceElement->nodeValue; // servis fiyatı

        // İstenilen bilgileri kullanabilirsiniz
        echo "Servis Başlığı: $title, Fiyat: $price\n";
    }
}


          //  $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?",$this->apiEndpoint);
        }
    }
}
