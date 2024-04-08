<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message,$apiEndpoint)
    {
        $url = $apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message);
        $result = file_get_contents($url);

        if ($result === false) {
            throw new Exception("Error sending message via Telegram API");
        }

        return $result;
    }
}
