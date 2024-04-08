<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message,$apiEndpoint,$encodedKeyboard = '')
    {
        $url = $apiEndpoint . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message).'&parse_mode=html&'.$encodedKeyboard;
        $result = file_get_contents($url);

        return $result;
    }
}
