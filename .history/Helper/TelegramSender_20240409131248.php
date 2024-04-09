<?php

include_once __DIR__ . "/../models/Messages.php";

trait TelegramSender
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
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            $this->handleApiRequestFailure();
            return false;
        }

        $this->logMessage($chatId, $message);

        return $result;
    }

    private function handleApiRequestFailure()
    {
        error_log('Telegram API request failed: ' . error_get_last()['message']);
        // İsteğe göre, başka bir işlem yapılabilir: hata günlüğüne kaydetme, istisna fırlatma, vb.
    }

    private function logMessage($chatId, $message)
    {
        $messageModel = new Messages();
        $messageModel->addMessage($chatId, 0, 0, $message, 'bot');
    }
}

?>
