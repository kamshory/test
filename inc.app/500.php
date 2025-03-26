<?php

require_once __DIR__."/default.php";

$theme = "sb-admin-2";
$theme = "core-ui";
$pathForbidden = dirname(__DIR__)."/lib.themes/$theme/inc.backend/500.php";
$themePath = "lib.themes/$theme/";
if(file_exists($pathForbidden))
{
    require_once $pathForbidden;
}
