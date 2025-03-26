<?php

use MagicObject\Request\InputGet;
use MagicObject\Request\InputServer;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\Admin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputServer = new InputServer();
$langId = $inputGet->getLanguageId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

$user = new Admin(null, $database);

try
{
    $user->setAdminId($currentUser->getAdminId())->setLangId($langId)->update();
}
catch(Exception $e)
{
    // do nothing
}

if(isset($_SERVER['HTTP_REFERER']) && basename($_SERVER['HTTP_REFERER']) != basename($_SERVER['PHP_SELF']))
{
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
else
{
    header("Location: index.php");
}