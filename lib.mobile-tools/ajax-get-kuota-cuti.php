<?php

use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\KuotaCuti;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$tahun = date('Y');
$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

$specs = PicoSpecification::getInstance()
	->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $supervisorId))
	->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals('tahun', $tahun))
	;
$sorts = PicoSortable::getInstance()->add(['tahun', PicoSort::ORDER_TYPE_ASC]);

$kuotaCuti = new KuotaCuti(null, $database);
$json = [];
try
{
	$pageData = $kuotaCuti->findAll($specs, null, $sorts);
	foreach($pageData->getResult() as $data)
	{
		$tahun = $data->getTahun();
		if(!isset($json[$tahun]))
		{
			$json[$tahun] = [];
		}
		$json[$tahun][] = [
			'jenis_cuti' => $data->hasValueJenisCuti() ? $data->getJenisCuti()->getNama() : "",
			'kuota' => $data->getKuota()
		];
	}
}
catch(Exception $e)
{
	// do nothing
}
header('Content-type: application/json');
echo json_encode($json);
