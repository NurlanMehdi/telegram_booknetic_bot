<?php

include 'config.php';
$response = file_get_contents($apiEndpoint . 'getUpdates');
$updates = json_decode($response, true);

// Telegram-dan gələn istəy

if (isset($updates['result'][0])) {
  
    $result = $updates['result'][0];
    $chatId = $result['message']['chat']['id'];
    $messageText = $result['message']['text'];
    $username = $result['message']['chat']['username'];

    // /start komandasına cavab verin
    if ($messageText == '/start') {
        sendMessage($chatId, "Salam {$username}! Mən BookingBot-um. Sizə necə kömək edə bilərəm?");
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
