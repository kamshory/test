<?php
$theme = "sb-admin-2";
$theme = "core-ui";
$pathRegistration = dirname(__DIR__)."/lib.themes/$theme/inc.backend/registration.php";
$themePath = "lib.themes/$theme/";
if(file_exists($pathRegistration))
{
    require_once dirname(__DIR__)."/registration-form.php";
}
