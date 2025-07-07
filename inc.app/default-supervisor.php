<?php

use Sipro\AppLanguageImpl;

require_once __DIR__."/app.php";

$appLanguage = new AppLanguageImpl(
    $appConfig,
    'id'
);