<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\PicoModule;
use Sipro\Entity\Data\Proyek;
use Sipro\AppIncludeImpl;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, null, "/", "proyek", $appLanguage->getProyek());
$appInclude = new AppIncludeImpl($appConfig, $currentModule);


$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
