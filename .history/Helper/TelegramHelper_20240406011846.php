<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message)
    {
        $apiEndpoint = require_once __DIR__ . '/../config/config.php';
        $url = $apiEndpoint['apiEndpoint'] . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message);
        file_get_contents($url);
    }
}
