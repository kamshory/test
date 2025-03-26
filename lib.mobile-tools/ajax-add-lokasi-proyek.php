<?php

use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\LokasiProyek;
use Sipro\Entity\Data\LokasiProyekMin;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputPost = new InputPost();

if($inputPost->getAction() == 'add')
{
	$lokasiProyek = new LokasiProyek(null, $database);
	$lokasiProyek->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lokasiProyek->setKodeLokasi($inputPost->getKodeLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lokasiProyek->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lokasiProyek->setSupervisorId($currentAction->getSupervisorId());
	$lokasiProyek->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiProyek->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiProyek->setAtitude($inputPost->getAtitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiProyek->setAktif(true);
	$lokasiProyek->setWaktuBuat($currentAction->getTime());
	$lokasiProyek->setIpBuat($currentAction->getIp());
	$lokasiProyek->setWaktuUbah($currentAction->getTime());
	$lokasiProyek->setIpUbah($currentAction->getIp());
	$lokasiProyek->insert();
	$newId = $lokasiProyek->getLokasiProyekId();

	$lokasiProyekAll = new LokasiProyekMin(null, $database);	
	$json = [];
	try
	{
		$res = $lokasiProyekAll->findByProyekIdAndAktif($lokasiProyek->getProyekId(), true);		
		foreach($res->getResult() as $data)
		{
			$json[] = [
				'v'=>$data->getLokasiProyekId(), 
				'l'=>$data->getNama()
			];
		}
	}
	catch(Exception $e)
	{
		// do nothing
	}
	header('Content-type: application/json');
	echo json_encode($json);
}
