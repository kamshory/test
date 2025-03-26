<?php

use MagicObject\Util\File\FileUtil;

$theme = "sb-admin-2";
$theme = "core-ui";
$pathResetPassword = dirname(__DIR__)."/lib.themes/$theme/inc.backend/email-confirmation.php";
$themePath = "lib.themes/$theme/";
$pathResetPassword = FileUtil::fixFilePath($pathResetPassword);
if(file_exists($pathResetPassword))
{
    require_once $pathResetPassword;
}
