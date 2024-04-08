<?php

include 'config.php';
$webHookUrl =  "http://127.0.0.1/telegramBotForBooknetic/index.php";
$apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/setWebhook?url={$webHookUrl}";
$response = file_get_contents($apiUrl);
echo $response;