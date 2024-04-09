<?php

include_once __DIR__ . "/../models/Messages.php";

trait TelegramSender
{
    /**
     * Sends a message via the Telegram API.
     *
     * @param int|string $chatId The ID of the chat where the message will be sent.
     * @param string $message The message content to be sent.
     * @param string $apiEndpoint The base API endpoint of the Telegram Bot API.
     * @param array|string $button Optional. An array defining custom reply markup buttons or a string.
     * @return bool|string Returns the result of the API call or false on failure.
     */
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
        $result = @file_get_contents($url, false, $context); // Use @ to suppress warnings

        if ($result === false) {
            // Handle API request failure (e.g., log error, throw exception)
            error_log('Telegram API request failed: ' . error_get_last()['message']);
            return false;
        }

        // Assuming successful API call, log the message using the Messages class
        $this->logMessage($chatId, $message);

        return $result;
    }

    /**
     * Logs the sent message using the Messages class.
     *
     * @param int|string $chatId The ID of the chat where the message was sent.
     * @param string $message The content of the sent message.
     * @return void
     */
    private function logMessage($chatId, $message)
    {
        $messageModel = new Messages(); // Instantiate the Messages class
        $messageModel->addMessage($chatId, 0, 0, $message, 'bot'); // Perform message logging
    }
}

?>
