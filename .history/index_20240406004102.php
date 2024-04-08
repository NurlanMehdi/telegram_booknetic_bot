<?php

include 'config.php';
$response = file_get_contents($apiEndpoint . 'getUpdates?limit=1');
$updates = json_decode($response, true);

// Telegram-dan gələn istəy
var_dump($updates['result']);
if (isset($updates['result'])) {
    foreach ($updates['result'] as $update) {
        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $messageText = $message['text'];
        $username = $message['chat']['username'];
    
        // /start komandasına cavab verin
        if ($messageText == '/start') {
            sendMessage($chatId, "Salam {$username}! Mən Booking Bot for Booknetic. Sizə necə kömək edə bilərəm?");
        }
    }
}


// Telegram API-dan mesaj göndərmək üçün funksiya
function sendMessage($chatId, $message) {
    global $apiEndpoint;
    $params = [
        'chat_id' => $chatId,
        'text' => $message
    ];
    $url = $apiEndpoint . 'sendMessage?' . http_build_query($params);
    file_get_contents($url);
}
