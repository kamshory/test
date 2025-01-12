<?php
use Sipro\Captcha\CaptchaGenerator;

require_once dirname(__DIR__) . "/inc.app/session.php";

try {
    $captcha = new CaptchaGenerator(
        __DIR__ . '/arial.ttf',
        __DIR__ . '/pwdimage.png',
        6
    );
    $sessions->captcha = $captcha->getPhrase();
    $captcha->render();
} catch (InvalidArgumentException $e) {
    error_log($e->getMessage());
}