<?php
// Telegram bot token-ini qeyd edin
$botToken = '6485313469:AAEcK7SI1sYd9YTYicntztDlIxOtKN6D720';

include 'config.php';
$apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/getMe";
$update = json_decode(file_get_contents('php://input'), true);

// Telegram-dan gələn istəyi işləyin
var_dump($update);
if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = $update['message']['text'];

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