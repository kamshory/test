<?php

use MagicObject\SecretObject;
use MagicObject\Util\Database\PicoDatabaseUtilMySql;

require_once dirname(__DIR__) . "/inc.lib/vendor/autoload.php";

$configFile = dirname(__DIR__).'/inc.cfg/import-data2.yml';
$dbFile = dirname(__DIR__).'/inc.cfg/import-data.sql';

$config = new SecretObject();
$config->loadYamlFile($configFile, true, true, true);

$fp = fopen($dbFile, 'w');
fclose($fp);

/*
PicoDatabaseUtilMySql::showCreateTables($config, function($sql, $target){
    $fp = fopen(dirname(__DIR__).'/inc.cfg/import-data.sql', 'a');
    fwrite($fp, "-- CREATE TABLE $target\r\n\r\n");
    fwrite($fp, $sql.";\r\n\r\n");
    fclose($fp);
});
*/
PicoDatabaseUtilMySql::importData($config, function($sql, $databaseSource, $databaseTarget, $source, $target){
    $fp = fopen(dirname(__DIR__).'/inc.cfg/import-data.sql', 'a');
    fwrite($fp, $sql.";\r\n\r\n");
    fclose($fp);
    //$databaseTarget->query($sql);
});
