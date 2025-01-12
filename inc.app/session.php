<?php
use MagicObject\Session\PicoSession;

require_once __DIR__."/app.php";
$sessions = new PicoSession($appConfig->getSession());

$sessionConfig = $appConfig->getSession();
$maxlifetime = $sessionConfig->getMaxLifeTime();
$cookiePath = $sessionConfig->getCookiePath();
$cookieDomain = $sessionConfig->getCookieDomain();
$cookieSecure = $sessionConfig->isCookieSecure();
$cookieHttponly = $sessionConfig->isCookieHttpOnly();

$sessions->setSessionCookieParams($maxlifetime, $cookiePath, $cookieDomain, $cookieSecure, $cookieHttponly);

$sessions->startSession();