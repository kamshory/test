<?php

use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\User;
use Sipro\Entity\Data\UserMin;

require_once __DIR__ . "/inc.app/app.php";

$database->setCallbackDebugQuery(function($sql){
    echo $sql;
});
$specs = PicoSpecification::alwaysTrue();

$user = new UserMin(null, $database);
try
{
$pageData = $user->findAll($specs);
}
catch(Exception $e)
{
    // Do nothing
}