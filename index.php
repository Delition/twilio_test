<?php

require_once (__DIR__ . '/vendor/autoload.php');

$mainController = new Inc\MainController();
$step = empty($_GET['verify']) ? '' : $_GET['verify'];

switch ($step) {
    case 1:
        $userId = $mainController->generateUserId();
        if ($mainController->getErrors())
            $mainController->actionFormAddUsers();
        else
            $mainController->actionFormTokens($userId);
        break;
    case 2:
        $mainController->verifyUser();
        if ($mainController->getErrors())
            $mainController->actionFormTokens();
        else
            $mainController->successPage();
        break;
    default:
        $mainController->actionFormAddUsers();
}



