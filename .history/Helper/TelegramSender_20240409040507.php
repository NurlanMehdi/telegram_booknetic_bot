<?php

include_once __DIR__ . "/../models/Messages.php";

class TelegramHelper
{
    public function sendMessage($chatId, $message, $apiEndpoint, $button = '')
    {
        $messageModel = new Messages();
        $url = $apiEndpoint . 'sendMessage';
    
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => $button 
        ];
    
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data)
            ]
        ];
    
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $messageModel->addMessage(
            $chatId,
            0,
            0,
            $message,
            'bot'
        );
    
        return $result;
    }
    
}
