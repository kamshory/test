<?php

use Sipro\Util\CalendarUtil;

require_once dirname(__DIR__) . "/inc.app/app.php";

$dari = '2024-05-22';
$hingga = '2024-06-22';

$hariKerja = CalendarUtil::getWorkingDays($database, $dari, $hingga);
$detil = implode(",", $hariKerja);

echo $detil;
