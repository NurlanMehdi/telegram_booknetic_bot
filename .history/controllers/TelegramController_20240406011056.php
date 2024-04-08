<?php

include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../Helper/TelegramHelper.php';

class TelegramController
{
    private $apiEndpoint;

    public function __construct()
    {
        $this->apiEndpoint = require_once __DIR__ . '/../config/config.php';
    }

    public function handleIncomingMessages()
    {
        var_dump($this->apiEndpoint[]);
        $updates = file_get_contents($this->apiEndpoint['apiEndpoint'] . 'getUpdates?limit=1');
var_dump($updates['result']);
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
            $telegramHelper->sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?");
        }
    }
}

