<?php
require __DIR__ . "/inc/bootstrap.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $urilength = count($uri) - 1;
    if ((isset($uri[$urilength-1]) && $uri[$urilength-1] != 'payment')) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    require PROJECT_ROOT_PATH . "/controller/PaymentController.php";

    $objFeedController = new PaymentController();
    $strMethodName = $uri[3] . 'Action';
    $result = $objFeedController->{$strMethodName}();
    
    $sender = new BaseController();
    $sender->sendOutput(
        $result[0],
        $result[1],
    );
