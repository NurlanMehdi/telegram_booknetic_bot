<?php

include 'config.php';
$apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/setWebhookInfo";
$response = file_get_contents($apiUrl);
echo $response;