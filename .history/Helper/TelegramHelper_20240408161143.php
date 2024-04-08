<?php

class TelegramHelper
{
    public function sendMessage($chatId, $message, $apiEndpoint, $button = '')
    {
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
    
        return $result;
    }
    
}
