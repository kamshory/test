<?php

use MagicObject\Database\PicoDatabase;
use MagicObject\SecretObject;

require_once dirname(__DIR__)."/inc.lib/vendor/autoload.php";

$appConfig = new SecretObject();


$languageDir = dirname(__DIR__)."/inc.lang";
$appConfig->setBaseLanguageDirectory($languageDir);

$appConfig->loadYamlFile(dirname(__DIR__)."/inc.cfg/application.yml", false, true, true);

// Ensure that data configuration is exists
$dataControlConfig = new SecretObject($appConfig->getData());

$entityInfo = $appConfig->getEntityInfo();
$entityApvInfo = $appConfig->getEntityApvInfo();


$database = new PicoDatabase($appConfig->getDatabase());
try
{
    $database->connect();
}
catch(Exception $e)
{
    error_log($e->getMessage());
}
