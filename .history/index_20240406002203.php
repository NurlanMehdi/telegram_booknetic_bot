<?php

include 'config.php';
$apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/";
$response = file_get_contents($apiUrl . 'getUpdates');
$updates = json_decode($response, true);

// Telegram-dan gələn istəy

if (isset($updates['result'][0])) {
  
    $result = $updates['result'][0];
    $chatId = $result['message']['chat']['id'];
    var_dump($result['message']);
    $messageText = $result['message']['text'];

    // /start komandasına cavab verin
    if ($messageText == '/start') {
        sendMessage($chatId, "Salam! Mən BookingBot-um. Sizə necə kömək edə bilərəm?");
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
