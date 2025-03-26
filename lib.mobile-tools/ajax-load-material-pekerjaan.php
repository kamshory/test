<?php

use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\MaterialPekerjaan;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputGet = new InputGet();
$jenisPekerjaanId = $inputGet->getId();

$specs = PicoSpecification::getInstance()
	->addAnd(PicoPredicate::getInstance()->equals('jenisPekerjaanId', $jenisPekerjaanId))
	->addAnd(PicoPredicate::getInstance()->equals('material.aktif', true))
;

$sorts = PicoSortable::getInstance()
	->add(['material.nama', PicoSort::ORDER_TYPE_ASC])
;
$materialPekerjaan = new MaterialPekerjaan(null, $database);

$json = [];

try
{
	$pageData = $materialPekerjaan->findAll($specs, null, $sorts);
	foreach($pageData->getResult() as $data)
	{
		if($data->hasValueMaterial())
		{
			$material = $data->getMaterial();
			$nama = $material->getNama();
			if($material->hasValueSatuan())
			{
				$nama .= " [".$material->getSatuan()."]";
			}
			$json[] = [
				'v'=>$data->getMaterialId(), 
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
