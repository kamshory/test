<?php

require_once dirname(__DIR__) . "/inc.app/app.php";
require_once dirname(__DIR__) . "/inc.app/session.php";

$sessions->destroy();
header("Location: ./");