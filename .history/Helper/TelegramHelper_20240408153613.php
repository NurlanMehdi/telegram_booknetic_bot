<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message,$apiEndpoint,$button)
    {
        $url = $apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message).'&parse_mode=html&inline_keyboard:[[{"text":"Rezervasyon Yap","callback_data":"reservation"}]]';
        var_dump($url);
        $result = file_get_contents($url);

        return $result;
    }
}
