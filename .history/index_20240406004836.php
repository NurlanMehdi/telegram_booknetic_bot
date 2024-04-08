<?php

include_once __DIR__ . '/controllers/TelegramController.php';

$telegramController = new TelegramController();
$telegramController->handleIncomingMessages();

