<?php

use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\PeralatanPekerjaan;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputGet = new InputGet();
$jenisPekerjaanId = $inputGet->getId();

$specs = PicoSpecification::getInstance()
	->addAnd(PicoPredicate::getInstance()->equals('jenisPekerjaanId', $jenisPekerjaanId))
	->addAnd(PicoPredicate::getInstance()->equals('peralatan.aktif', true))
	;

$sorts = PicoSortable::getInstance()
	->add(['peralatan.nama', PicoSort::ORDER_TYPE_ASC])
	;

$peralatanPekerjaan = new PeralatanPekerjaan(null, $database);

$json = [];

try
{
	$pageData = $peralatanPekerjaan->findAll($specs, null, $sorts);
	foreach($pageData->getResult() as $data)
	{
		if($data->hasValuePeralatan())
		{
			$peralatan = $data->getPeralatan();
			$nama = $peralatan->getNama();
			if($peralatan->hasValueSatuan())
			{
				$nama .= " [".$peralatan->getSatuan()."]";
			}
			$json[] = [
				'v'=>$data->getPeralatanId(), 
				'l'=>$nama
			];
		}
	}
}
catch(Exception $e)
{
	// do nothing
}
header('Content-type: application/json');
echo json_encode($json);

