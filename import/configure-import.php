<?php

use MagicObject\SecretObject;
use MagicObject\Util\Database\PicoDatabaseUtilMySql;

require_once dirname(__DIR__) . "/inc.lib/vendor/autoload.php";

$configFile = dirname(__DIR__).'/inc.cfg/import-data.yml';

$config = new SecretObject();
$config->loadYamlFile($configFile, true, true, true);
$util = new PicoDatabaseUtilMySql();
$util->autoConfigureImportData($config);
file_put_contents($configFile, $config->dumpYaml(0, 2));

