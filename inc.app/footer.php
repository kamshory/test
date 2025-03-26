<?php
$theme = "sb-admin-2";
$theme = "core-ui";
$pathFooter = dirname(__DIR__)."/lib.themes/$theme/inc.backend/footer.php";
$themePath = "lib.themes/$theme/";
if(file_exists($pathFooter))
{
    require_once $pathFooter;
}
