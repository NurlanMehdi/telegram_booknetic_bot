<?php

class TelegramHelper
{
    private $apiEndpoint;

    public function __construct()
    {
        $config = require_once __DIR__ . '/../config/config.php';
        $this->apiEndpoint = $config['apiEndpoint'];
    }

    public function sendMessage($chatId, $message)
    {
        $url = $this->apiEndpoint . 'sendMessage';
        $params = [
            'chat_id' => $chatId,
            'text' => $message
        ];

        $this->sendRequest($url, $params);
    }
}
