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

    private function sendRequest($url, $params)
    {
        $query = http_build_query($params);
        $url = "{$url}?{$query}";

        $options = [
            'http' => [
                'method' => 'GET',
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        // Handle response or error checking here if needed
        // For example:
        // if ($result === false) {
        //     throw new Exception("Error sending request to Telegram API");
        // }

        return $result;
    }
}
