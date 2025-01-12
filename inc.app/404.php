<?php

use MagicObject\Util\File\FileUtil;

require_once __DIR__."/default.php";

$theme = "sb-admin-2";
$theme = "core-ui";
$pathForbidden = dirname(__DIR__)."/lib.themes/$theme/inc.backend/404.php";
$themePath = "lib.themes/$theme/";
$pathForbidden = FileUtil::fixFilePath($pathForbidden);
if(file_exists($pathForbidden))
{
    require_once $pathForbidden;
}
