<?php

use MagicObject\Request\InputGet;
use MagicObject\SecretObject;

require_once __DIR__ . "/inc.app/app.php";
require_once __DIR__ . "/inc.app/session.php";

$inputGet = new InputGet();
$waiting = intval(base64_decode($inputGet->getWaiting()));

$loginRule = $appConfig->getLoginRule();
if($loginRule == null)
{
    $loginRule = new SecretObject();
    $loginRule->setInvalidLoginTimeRange(30);
    $loginRule->setInvalidLoginMaximumCount(5);
}

$message = sprintf("Anda harus menunggu %d menit untuk bisa login kembali. Jangan melakukan percobaan login sebelum waktu tunggu habis.", $loginRule->getInvalidLoginTimeRange() - ceil($waiting/60));

echo $message;