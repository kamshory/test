<?php

use MagicObject\Util\File\FileUtil;

$theme = "sb-admin-2";
$theme = "core-ui";
$pathFooter = dirname(__DIR__)."/lib.themes/$theme/inc.backend/footer-supervisor.php";
$themePath = "lib.themes/$theme/";
$pathFooter = FileUtil::fixFilePath($pathFooter);
if(file_exists($pathFooter))
{
    require_once $pathFooter;
}
