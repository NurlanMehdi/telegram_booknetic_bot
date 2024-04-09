<?php

include_once __DIR__ . '/../controllers/TelegramController.php';
// Run the project using README.md

$telegramController = new TelegramController();
$telegramController->handleIncomingMessages();
header("Refresh: 1");