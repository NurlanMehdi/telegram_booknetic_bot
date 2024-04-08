<?php

class TelegramHelper
{
    private $apiEndpoint;

    public function __construct($apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
    }

    public function sendMessage($chatId, $message)
    {
        $url = $this->apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message);
        $result = file_get_contents($url);

        if ($result === false) {
            throw new Exception("Error sending message via Telegram API");
        }

        return $result;
    }
}

