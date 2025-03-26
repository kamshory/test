<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicApp\PicoModule;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "Home";
$currentModule = new PicoModule($appConfig, $database, null, "/", "index", $appLanguage->getHalamanDepan());

require_once __DIR__ . "/inc.app/header-supervisor.php";
?>

<a class="btn btn-primary" href="kehadiran.php"><?php echo $appLanguage->getShortcutKehadiran();?></a>
<a class="btn btn-primary" href="buku-harian.php"><?php echo $appLanguage->getShortcutBukuHarian();?></a>
<a class="btn btn-primary" href="proyek.php"><?php echo $appLanguage->getShortcutInformasiProyek();?></a>
<a class="btn btn-primary" href="perjalanan-dinas.php"><?php echo $appLanguage->getShortcutDinas();?></a>
<a class="btn btn-primary" href="cuti.php"><?php echo $appLanguage->getShortcutCuti();?></a>
<a class="btn btn-primary" href="hari-libur.php"><?php echo $appLanguage->getShortcutLibur();?></a>
<a class="btn btn-primary" href="./"><?php echo $appLanguage->getShortcutReferensi();?></a>
<a class="btn btn-primary" href="./"><?php echo $appLanguage->getShortcutJalurPintas();?></a>

<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
