<?php

use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\KuotaCuti;
use Sipro\Util\CommonUtil;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputGet = new InputGet();

$dari = $inputGet->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
$hingga = $inputGet->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
$jenisCutiId = $inputGet->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
$tahun = date('Y', strtotime($dari));
// kuota

$kuotaCuti = new KuotaCuti(null, $database);

$kuota = 0;

try
{
	$kuotaCuti->findOneBySupervisorIdAndJenisCutiIdAndTahun($supervisorId, $jenisCutiId, $tahun);
	$kuota = $kuotaCuti->getKuota();
}
catch(Exception $e)
{
	// do nothing
}

if($dari != '' && $hingga != '')
{
	$dateDari = strtotime($dari);
	$dateHingga = strtotime($hingga);
	$hariKerja = 0;
	$hariLibur = 0;
	for($time = $dateDari; $time <= $dateHingga; $time += 86400)
	{
		$date = date('Y-m-d', $time);
		if(!CommonUtil::isHariLibur($database, $date))
		{
			$hariKerja++;
		}
		else
		{
			$hariLibur++;
		}
	}
	echo json_encode(array(
		'hari_libur' => intval($hariLibur), 
		'hari_kerja' => intval($hariKerja), 
		'kuota' => intval($kuota)
	));
}
