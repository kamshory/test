<?php

use MagicApp\Config\ConfigEncrypter;

require_once __DIR__ . "/inc.app/app.php";

$input = __DIR__ . "/inc.cfg/application.yml";
$output = __DIR__ . "/inc.cfg/application-draft.yml";

$encrypter = new ConfigEncrypter(function(){
   return "68e656b251e67e8358bef8483ab0d51c"."6619f3e7a1a9f0e75838d41ff368f728";
});

$encrypter->encryptConfig($input, $output);