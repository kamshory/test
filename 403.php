<?php

use MagicApp\PicoModule;
use Sipro\AppIncludeImpl;

require_once __DIR__ . "/inc.app/auth.php";

$currentModule = new PicoModule($appConfig, $database, null, null, null);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);
require_once $appInclude->appForbiddenPage(__DIR__);
