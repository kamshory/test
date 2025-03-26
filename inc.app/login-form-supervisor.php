<?php
$theme = "sb-admin-2";
$theme = "core-ui";
$pathLoginForm = dirname(__DIR__)."/lib.themes/$theme/inc.backend/login-form.php";
$themePath = "lib.themes/$theme/";
if(file_exists($pathLoginForm))
{
    require_once $pathLoginForm;
}
