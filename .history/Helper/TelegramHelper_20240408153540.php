<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message,$apiEndpoint,$button)
    {
        $url = $apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message).'&parse_mode=html&'.$button;
        var_dump($url);
        $result = file_get_contents($url);

        return $result;
    }
}
