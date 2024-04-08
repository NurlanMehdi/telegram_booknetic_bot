<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message,$apiEndpoint)
    {
        $url = $apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message);
        $result = file_get_contents($url);

        return $result;
    }
}