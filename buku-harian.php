<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicObject\Exceptions\NoRecordFoundException;
use MagicObject\MagicObject;
use MagicObject\SetterGetter;
use Sipro\Entity\Data\AcuanPengawasan;
use Sipro\Entity\Data\AcuanPengawasanPekerjaan;
use Sipro\Entity\Data\AcuanPengawasanProyek;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Entity\Data\BillOfQuantityProyek;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\BukuHarianMin;
use Sipro\Entity\Data\JenisPekerjaan;
use Sipro\Entity\Data\KelasTower;
use Sipro\Entity\Data\LokasiProyek;
use Sipro\Entity\Data\ManPowerProyek;
use Sipro\Entity\Data\MaterialProyek;
use Sipro\Entity\Data\Pekerjaan;
use Sipro\Entity\Data\PeralatanProyek;
use Sipro\Entity\Data\Permasalahan;
use Sipro\Entity\Data\PermasalahanMin;
use Sipro\Entity\Data\ProgresProyek;
use Sipro\Entity\Data\Proyek;
use Sipro\Entity\Data\RekomendasiPekerjaan;
use Sipro\Entity\Data\SupervisorProyek;
use Sipro\Entity\Data\TipePondasi;
use Sipro\Util\BoqUtil;
use Sipro\Util\CalendarUtil;
use Sipro\Util\CommonUtil;
use Sipro\Util\DateUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "buku-harian", "Buku Harian");

$inputGet = new InputGet();
$inputPost = new InputPost();

function arrayCount($arr)
{
	if(isset($arr) && is_array($arr))
	{
		return count($arr);
	}
	return 0;
}

function savePermasalahanRekomendasi($database, $currentAction, $proyekId, $bukuHarianId, $permasalahanIds, $rekomendasiIds)
{
	$max = max(arrayCount($permasalahanIds), arrayCount($rekomendasiIds));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$rekomendasiPekerjaan = new RekomendasiPekerjaan(null, $database);
		$permasalahanId = isset($permasalahanIds[$i]) ? $permasalahanIds[$i] : null;
		$rekomendasiId = isset($rekomendasiIds[$i]) ? $rekomendasiIds[$i] : null;
		try
		{
			$rekomendasiPekerjaan->findOneByProyekIdAndBukuHarianIdAndPermasalahanIdAndRekomendasiId($proyekId, $bukuHarianId, $permasalahanId, $rekomendasiId);
			$ids[] = (int) $rekomendasiPekerjaan->getRekomendasiPekerjaanId();
		}
		catch(Exception $e)
		{
			// Not found
			// Insert
			$rekomendasiPekerjaan = new RekomendasiPekerjaan(null, $database);
			$rekomendasiPekerjaan->setProyekId($proyekId);
			$rekomendasiPekerjaan->setBukuHarianId($bukuHarianId);
			$rekomendasiPekerjaan->setSupervisorId($currentAction->getSupervisorId());
			$rekomendasiPekerjaan->setPermasalahanId($permasalahanId);
			$rekomendasiPekerjaan->setRekomendasiId($rekomendasiId);
			$rekomendasiPekerjaan->setAktif(true);
			$rekomendasiPekerjaan->setWaktuBuat($currentAction->getTime());
			$rekomendasiPekerjaan->setIpBuat($currentAction->getIp());
			$rekomendasiPekerjaan->setWaktuUbah($currentAction->getTime());
			$rekomendasiPekerjaan->setIpUbah($currentAction->getIp());
			$rekomendasiPekerjaan->insert();
			$ids[] = (int) $rekomendasiPekerjaan->getRekomendasiPekerjaanId();
		}
	}

	// Clean up
	$rekomendasiPekerjaan = new RekomendasiPekerjaan(null, $database);
	$rekomendasiPekerjaan->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->rekomendasiPekerjaanId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}

function saveBoq($database, $currentAction, $proyekId, $bukuHarianId, $bodIds, $jumlahBoqs)
{
	$max = max(arrayCount($bodIds), arrayCount($jumlahBoqs));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
		$billOfQuantityId = isset($bodIds[$i]) ? $bodIds[$i] : null;
		$volumeProyek = isset($jumlahBoqs[$i]) ? $jumlahBoqs[$i] : null;
		try
		{
			$billOfQuantityProyek->findOneByProyekIdAndBukuHarianIdAndBillOfQuantityId($proyekId, $bukuHarianId, $billOfQuantityId);
			$ids[] = (int) $billOfQuantityProyek->getBillOfQuantityProyekId();
		}
		catch(Exception $e)
		{
			// Not found
			// Insert

			try
			{
				$billOfQuantity = new BillOfQuantity(null, $database);
				$billOfQuantity->findOneByBillOfQuantityId($billOfQuantityId);

				$volume = $billOfQuantity->getVolume();
				$persen = $volume == 0 ? 0 : (100*$volumeProyek/$volume);

				$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
				$billOfQuantityProyek->setProyekId($proyekId);
				$billOfQuantityProyek->setBukuHarianId($bukuHarianId);
				$billOfQuantityProyek->setSupervisorBuatId($currentAction->getSupervisorId());
				$billOfQuantityProyek->setSupervisorUbahId($currentAction->getSupervisorId());
				$billOfQuantityProyek->setBillOfQuantityId($billOfQuantityId);
				$billOfQuantityProyek->setVolumeProyek($volumeProyek);
				$billOfQuantityProyek->setVolume($volume);
				$billOfQuantityProyek->setPersen($persen);
				$billOfQuantityProyek->setAktif(true);
				$billOfQuantityProyek->setWaktuBuat($currentAction->getTime());
				$billOfQuantityProyek->setIpBuat($currentAction->getIp());
				$billOfQuantityProyek->setWaktuUbah($currentAction->getTime());
				$billOfQuantityProyek->setIpUbah($currentAction->getIp());
				$billOfQuantityProyek->insert();

				$billOfQuantity->setVolume($volumeProyek);
				$billOfQuantity->update();

				$ids[] = (int) $billOfQuantityProyek->getBillOfQuantityProyekId();
			}
			catch(Exception $e2)
			{
				// Do nothing
			}
		}
	}

	// Clean up
	$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
	$billOfQuantityProyek->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->billOfQuantityProyekId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}

function saveAcuanPengawasan($database, $currentAction, $proyekId, $bukuHarianId, $acuanPengawasanIds)
{
	$max = arrayCount($acuanPengawasanIds);
	for($i = 0; $i < $max; $i++)
	{
		try
		{
			$acuanPengawasanId = $acuanPengawasanIds[$i];
			$acuanPengawasanProyek = new AcuanPengawasanProyek(null, $database);
			$acuanPengawasanProyek->setProyekId($proyekId);
			$acuanPengawasanProyek->setBukuHarianId($bukuHarianId);
			$acuanPengawasanProyek->setAcuanPengawasanId($acuanPengawasanId);
			$acuanPengawasanProyek->setSupervisorId($currentAction->getSupervisorId());
			$acuanPengawasanProyek->setAktif(true);
			$acuanPengawasanProyek->setWaktuBuat($currentAction->getTime());
			$acuanPengawasanProyek->setIpBuat($currentAction->getIp());
			$acuanPengawasanProyek->setWaktuUbah($currentAction->getTime());
			$acuanPengawasanProyek->setIpUbah($currentAction->getIp());
			$acuanPengawasanProyek->insert();

			$ids[] = (int) $acuanPengawasanProyek->getAcuanPengawasanProyekId();
		}
		catch(Exception $e)
		{
			// Do nothing
		}
	}
	// Clean up
	$acuanPengawasanProyek = new AcuanPengawasanProyek(null, $database);
	$acuanPengawasanProyek->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->acuanPengawasanProyekId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}

function saveManPower($database, $currentAction, $proyekId, $bukuHarianId, $manPowerIds, $jumlahManPowers)
{
	$max = max(arrayCount($manPowerIds), arrayCount($jumlahManPowers));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$manPowerProyek = new ManPowerProyek(null, $database);
		$manPowerId = isset($manPowerIds[$i]) ? $manPowerIds[$i] : null;
		$jumlahManPower = isset($jumlahManPowers[$i]) ? $jumlahManPowers[$i] : null;
		try
		{
			$manPowerProyek->findOneByProyekIdAndBukuHarianIdAndManPowerId($proyekId, $bukuHarianId, $manPowerId);
			$ids[] = (int) $manPowerProyek->getManPowerProyekId();
		}
		catch(Exception $e)
		{
			// Not found
			// Insert
			$manPowerProyek = new ManPowerProyek(null, $database);
			$manPowerProyek->setProyekId($proyekId);
			$manPowerProyek->setBukuHarianId($bukuHarianId);
			$manPowerProyek->setSupervisorId($currentAction->getSupervisorId());
			$manPowerProyek->setManPowerId($manPowerId);
			$manPowerProyek->setJumlahPekerja($jumlahManPower);
			$manPowerProyek->setAktif(true);
			$manPowerProyek->setWaktuBuat($currentAction->getTime());
			$manPowerProyek->setIpBuat($currentAction->getIp());
			$manPowerProyek->setWaktuUbah($currentAction->getTime());
			$manPowerProyek->setIpUbah($currentAction->getIp());
			$manPowerProyek->insert();
			$ids[] = (int) $manPowerProyek->getManPowerProyekId();
		}
	}

	// Clean up
	$manPowerProyek = new ManPowerProyek(null, $database);
	$manPowerProyek->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->manPowerProyekId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}

function savePeralatan($database, $currentAction, $proyekId, $bukuHarianId, $peralatanIds, $jumlahPeralatans)
{
	$max = max(arrayCount($peralatanIds), arrayCount($jumlahPeralatans));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$peralatanProyek = new PeralatanProyek(null, $database);
		$peralatanId = isset($peralatanIds[$i]) ? $peralatanIds[$i] : null;
		$jumlah = isset($jumlahPeralatans[$i]) ? $jumlahPeralatans[$i] : null;
		try
		{
			$peralatanProyek->findOneByProyekIdAndBukuHarianIdAndPeralatanId($proyekId, $bukuHarianId, $peralatanId);
			$ids[] = (int) $peralatanProyek->getPeralatanProyekId();
		}
		catch(Exception $e)
		{
			// Not found
			// Insert
			$peralatanProyek = new PeralatanProyek(null, $database);
			$peralatanProyek->setProyekId($proyekId);
			$peralatanProyek->setBukuHarianId($bukuHarianId);
			$peralatanProyek->setSupervisorId($currentAction->getSupervisorId());
			$peralatanProyek->setPeralatanId($peralatanId);
			$peralatanProyek->setJumlah($jumlah);
			$peralatanProyek->setAktif(true);
			$peralatanProyek->setWaktuBuat($currentAction->getTime());
			$peralatanProyek->setIpBuat($currentAction->getIp());
			$peralatanProyek->setWaktuUbah($currentAction->getTime());
			$peralatanProyek->setIpUbah($currentAction->getIp());
			$peralatanProyek->insert();
			$ids[] = (int) $peralatanProyek->getPeralatanProyekId();
		}
	}

	// Clean up
	$peralatanProyek = new PeralatanProyek(null, $database);
	$peralatanProyek->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->peralatanProyekId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}

function saveMaterial($database, $currentAction, $proyekId, $bukuHarianId, $materialIds, $jumlahMaterials)
{
	$max = max(arrayCount($materialIds), arrayCount($materialIds));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$materialProyek = new MaterialProyek(null, $database);
		$materialId = isset($materialIds[$i]) ? $materialIds[$i] : null;
		$jumlah = isset($jumlahMaterials[$i]) ? $jumlahMaterials[$i] : null;
		try
		{
			$materialProyek->findOneByProyekIdAndBukuHarianIdAndMaterialId($proyekId, $bukuHarianId, $materialId);
			$ids[] = (int) $materialProyek->getMaterialProyekId();
		}
		catch(Exception $e)
		{
			// Not found
			// Insert
			$materialProyek = new MaterialProyek(null, $database);
			$materialProyek->setProyekId($proyekId);
			$materialProyek->setBukuHarianId($bukuHarianId);
			$materialProyek->setSupervisorId($currentAction->getSupervisorId());
			$materialProyek->setMaterialId($materialId);
			$materialProyek->setJumlah($jumlah);
			$materialProyek->setAktif(true);
			$materialProyek->setWaktuBuat($currentAction->getTime());
			$materialProyek->setIpBuat($currentAction->getIp());
			$materialProyek->setWaktuUbah($currentAction->getTime());
			$materialProyek->setIpUbah($currentAction->getIp());
			$materialProyek->insert();
			$ids[] = (int) $materialProyek->getMaterialProyekId();
		}
	}

	// Clean up
	$materialProyek = new MaterialProyek(null, $database);
	$materialProyek->where(
		PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->materialProyekId, $ids))
			->addAnd([Field::of()->proyekId, $proyekId])
			->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
	)
		->delete();
}
if($inputPost->getUserAction() == UserAction::CREATE)
{
    $proyekId = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);

    $bukuHarian = new BukuHarian(null, $database);
	$bukuHarian->setSupervisorId($currentLoggedInSupervisor->getSupervisorId());
	$bukuHarian->setProyekId($proyekId);
	$bukuHarian->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true));
	$bukuHarian->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuHarian->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
	$bukuHarian->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$bukuHarian->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$bukuHarian->setAltitude($inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$bukuHarian->setAktif(true);
	$bukuHarian->setWaktuBuat($currentAction->getTime());
	$bukuHarian->setIpBuat($currentAction->getIp());
	$bukuHarian->setWaktuUbah($currentAction->getTime());
	$bukuHarian->setIpUbah($currentAction->getIp());

	$bukuHarian->insert();

	$bukuHarianId = $bukuHarian->getBukuHarianId();

	savePermasalahanRekomendasi($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPermasalahanId(), $inputPost->getRekomendasiId());
	saveBoq($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getBoqId(), $inputPost->getJumlahBoq());
	saveAcuanPengawasan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getAcuanPengawasanId());
	saveManPower($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getManPowerId(), $inputPost->getJumlahManPower());
	savePeralatan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPeralatanId(), $inputPost->getJumlahPeralatan());
	saveMaterial($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getMaterialId(), $inputPost->getJumlahMaterial());
	
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarianId);
	
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$bukuHarian = new BukuHarian(null, $database);
	$bukuHarian->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuHarian->setPermasalahan($inputPost->getPermasalahan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuHarian->setRekomendasi($inputPost->getRekomendasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuHarian->update();
	$newId = $bukuHarian->getBukuHarianId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_harian_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$bukuHarian = new BukuHarian(null, $database);
			try
			{
				$bukuHarian->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuHarianId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
				)
				->setAktif(true)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::DEACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$bukuHarian = new BukuHarian(null, $database);
			try
			{
				$bukuHarian->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuHarianId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
				)
				->setAktif(false)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::DELETE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			try
			{
				$bukuHarian = new BukuHarian(null, $database);
				$bukuHarian->deleteOneByBukuHarianId($rowId);
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
			}
		}
	}
	$currentModule->redirectToItself();
}

if(isset($_POST['save-work']))
{
	$now = $currentAction->getTime();
	
	$buku_harian_id = $inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$proyek_id = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$jenis_pekerjaan_id = $inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$pekerjaanId = $inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$tipe_pondasi_id = $inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kelas_tower_id = $inputPost->getKelasTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$lokasi_proyek_id = $inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kegiatan = $inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$latitude = $inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$longitude = $inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$altitude = $inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$jumlah_pekerja = $inputPost->getJumlahPekerjaan(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$acuan_pengawasan = $inputPost->getAcuanPengawasan();
	$acuanPengawasanPekerjaan = new AcuanPengawasanPekerjaan(null, $database);
	$acuanPengawasanPekerjaan->deleteByPekerjaanId($pekerjaanId);

	if(isset($acuan_pengawasan) && is_array($acuan_pengawasan))
	{
		foreach($acuan_pengawasan as $key=>$acuan_pengawasan_id)
		{
			$acuanPengawasanPekerjaan = new AcuanPengawasanPekerjaan(null, $database);
			$acuanPengawasanPekerjaan->setPekerjaanId($pekerjaanId);
			$acuanPengawasanPekerjaan->setAcuanPengawasan($acuan_pengawasan_id);
			$acuanPengawasanPekerjaan->setAktif(true);
			$acuanPengawasanPekerjaan->insert();
		}
	}

	$arr_peralatan_id = array();
	if(isset($_POST['peralatan_proyek_id']))
	{
		$peralatan_proyek_id = $_POST['peralatan_proyek_id'];
		if(isset($peralatan_proyek_id) && is_array($peralatan_proyek_id))
		{
			foreach($peralatan_proyek_id as $key=>$val)
			{
				if(stripos($val, 'rand_') !== false)
				{
					// insert
					$peralatan_id = $inputPost->get('peralatan_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$pemalatanProyek = new PeralatanProyek(null, $database);
					$pemalatanProyek->setPekerjaanId($pekerjaanId);
					$pemalatanProyek->setPeralatanId($peralatan_id);
					$pemalatanProyek->setJumlah($jumlah);
					$pemalatanProyek->setProyekId($proyek_id);
					$pemalatanProyek->setAktif(true);

					$pemalatanProyek->insert();

					$arr_peralatan_id[] = $pemalatanProyek->getPeralatanProyekId();
				}
				else
				{
					// update
					$peralatan_proyek_id = $val * 1;
					$peralatan_id = $inputPost->get('peralatan_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$pemalatanProyek = new PeralatanProyek(null, $database);
					$pemalatanProyek->setPeralatanProyekId($peralatan_proyek_id);
					$pemalatanProyek->setPekerjaanId($pekerjaanId);
					$pemalatanProyek->setPeralatanId($peralatan_id);
					$pemalatanProyek->setJumlah($jumlah);
					$pemalatanProyek->setProyekId($proyek_id);
					$pemalatanProyek->setAktif(true);

					$pemalatanProyek->update();
					
					$arr_peralatan_id[] = $pemalatanProyek->getPeralatanProyekId();
				}
			}
		}
	}
	// clean up peralatan

	$peralatanProyek = new PeralatanProyek(null, $database);
	try
	{
		$pageData = $peralatanProyek->findByPekerjaanIdAndProyekId($pekerjaanId, $proyek_id);

		foreach($pageData->getResult() as $data)
		{
			$peralatan_proyek_id = $data->getPeralatanProyekId();
			if(!in_array($data->getPeralatanProyekId(), $arr_peralatan_id))
			{
				$peralatanProyekRemover = new PeralatanProyek(null, $database);
				$peralatanProyekRemover->deleteByPeralatanProyekId($peralatan_proyek_id);
			}
		}
	}
	catch(Exception $e)
	{
		// do nothing
	}
	if(isset($_POST['material_proyek_id']))
	{
		$material_proyek_id = $_POST['material_proyek_id'];
		if(is_array($material_proyek_id))
		{
			foreach($material_proyek_id as $key=>$val)
			{
				if(stripos($val, 'rand_') !== false)
				{
					// insert
					$material_id = $inputPost->get('material_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$materialProyek = new MaterialProyek(null, $database);
					$materialProyek->setPekerjaanId($pekerjaanId);
					$materialProyek->setMaterialId($material_id);
					$materialProyek->setProyekId($proyek_id);
					$materialProyek->setJumlah($jumlah);
					$materialProyek->setAktif(true);

					$materialProyek->insert();

					$arr_material_id[] = $materialProyek->getMaterialProyekId();
				}
				else
				{
					// update
					$material_proyek_id = $val * 1;
					$material_id = $inputPost->get('material_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$materialProyek = new MaterialProyek(null, $database);
					$materialProyek->setMaterialProyekId($material_proyek_id);
					$materialProyek->setPekerjaanId($pekerjaanId);
					$materialProyek->setMaterialId($material_id);
					$materialProyek->setProyekId($proyek_id);
					$materialProyek->setJumlah($jumlah);
					$materialProyek->setAktif(true);
					
					$materialProyek->update();

					$arr_material_id[] = $materialProyek->getMaterialProyekId();
				}
			}
		}
	}
	// clean up material

	$materialProyek = new MaterialProyek(null, $database);
	try
	{
		$pageData = $materialProyek->findByPekerjaanIdAndProyekId($pekerjaanId, $proyek_id);

		foreach($pageData->getResult() as $data)
		{
			$material_proyek_id = $data->getMaterialProyekId();
			if(!in_array($data->getMaterialProyekId(), $arr_peralatan_id))
			{
				$materialProyekRemover = new PeralatanProyek(null, $database);
				$materialProyekRemover->deleteByMaterialProyekId($material_proyek_id);
			}
		}
	}
	catch(Exception $e)
	{
		// do nothing
	}

	$boqProyekId = $inputPost->getBoqProyekId();
	$supervisorId = $currentLoggedInSupervisor->getSupervisorId();
	
	if(isset($boqProyekId) && is_array($boqProyekId))
	{
		foreach($boqProyekId as $rand)
		{
			$boqId = $inputPost->get('boq_proyek_id_rand_'.$rand);
			$volumeProyek = $inputPost->get('volume_rand_'.$rand);

			$billOfQuantity = new BillOfQuantity(null, $database);
			$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
			
			try
			{
				$billOfQuantity->find($boqId);
				$volume = $billOfQuantity->getVolume();
				try
				{
					// BOQ Proyek ditemukan
					$billOfQuantityProyek->findOneByProyekIdAndBukuHarianIdAndBillOfQuantityIdAndSupervisorBuat($proyek_id, $buku_harian_id, $boqId, $supervisorId);
					$billOfQuantityProyek
						->setVolume($volume)
						->setVolumeProyek($volumeProyek)
						->setSupervisorUbah($supervisorId)
						->setWaktuUbah($now)
					;
					// update volume
					$billOfQuantityProyek->update();
				}
				catch(NoRecordFoundException $e)
				{
					// BOQ Proyek tidak ditemukan
					
					if($volumeProyek < $billOfQuantity->getVolumeProyek())
					{
						// ambil dari volume proyek
						$volumeProyek = $billOfQuantity->getVolumeProyek();
					}
					
					$billOfQuantityProyek
						->setProyekId($proyek_id)
						->setBukuHarianId($buku_harian_id)
						->setBillOfQuantityId($boqId)
						->setVolume($volume)
						->setVolumeProyek($volumeProyek)
						->setSupervisorBuat($supervisorId)
						->setSupervisorUbah($supervisorId)
						->setWaktuBuat($now)
						->setWaktuUbah($now)
						->setAktif(true)
					;
					$billOfQuantityProyek->insert();

					if($volume > 0)
					{
						$persen = 100 * $volumeProyek / $volume;
					}
					else
					{
						$persen = 0;
					}
					$billOfQuantity->setVolumeProyek($volumeProyek);
					$billOfQuantity->setPersen($persen);
					$billOfQuantity->setWaktuUbahVolumeProyek($now);
					
					// update volume dan persen
					$billOfQuantity->update();
				}
			}
			catch(Exception $e)
			{
				// do nothing
			}
		}
	}

	$pekerjaan = new Pekerjaan(null, $database);

	$pekerjaan->where(
		PicoSpecification::getInstance()
			->add(array('pekerjaanId', $pekerjaanId))
			->add(array('bukuHarianId', $buku_harian_id))
			->add(array('supervisorId', $supervisor_id))
	)
		->setJenisPekerjaanId($jenis_pekerjaan_id)
		->setTipePondasiId($tipe_pondasi_id)
		->setKelasTowerId($kelas_tower_id)
		->setLokasiProyekId($lokasi_proyek_id)
		->setKegiatan($kegiatan)
		->setJumlahPekerjaan($jumlah_pekerja)
		->setAcuanPengawasan($acuan_pengawasan)
		->setWaktuUbah($waktu_ubah)
		->setIpUbah($ip_ubah)
		->update()
	;
	
	header("Location: ".basename($_SERVER['PHP_SELF'])."?option=detail&buku_harian_id=$buku_harian_id");
	exit();
}

if(isset($_POST['add-work']))
{
	$waktuBuat = $currentAction->getTime();
	$waktuUbah = $waktuBuat;
	$ipBuat = $_SERVER['REMOTE_ADDR'];
	$ipUbah = $_SERVER['REMOTE_ADDR'];

	$bukuHarianId = $inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$proyekId = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$jenis_pekerjaan_id = $inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$tipe_pondasi_id = $inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kelas_tower_id = $inputPost->getKelasTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$lokasi_proyek_id = $inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kegiatan = $inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$acuan_pengawasan = $inputPost->getAcuanPengawasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$latitude = $inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$longitude = $inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$altitude = $inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$jumlah_pekerja = $inputPost->getJumlahPekerjaan(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	
	$arr_peralatan_id = array();
	
	$pekerjaan = new Pekerjaan(null, $database);
	$pekerjaan->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setJenisPekerjaanId($inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setTipePondasiId($inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setKelasTowerId($inputPost->getKelasTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setAtitude($inputPost->getAtitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setJumlahPekerja($inputPost->getJumlahPekerja(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setAcuanPengawasan($inputPost->getAcuanPengawasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pekerjaan->setAdminBuat($currentUser->getUserId());
	$pekerjaan->setWaktuBuat($currentAction->getTime());
	$pekerjaan->setIpBuat($currentAction->getIp());
	$pekerjaan->setAdminUbah($currentUser->getUserId());
	$pekerjaan->setWaktuUbah($currentAction->getTime());
	$pekerjaan->setIpUbah($currentAction->getIp());

	$pekerjaan->insert();

	$pekerjaanId = $pekerjaan->getPekerjaanId();

	$acuan_pengawasan = $inputPost->getAcuanPengawasan();
	$acuanPengawasanPekerjaan = new AcuanPengawasanPekerjaan(null, $database);
	$acuanPengawasanPekerjaan->deleteByPekerjaanId($pekerjaanId);

	if(isset($acuan_pengawasan) && is_array($acuan_pengawasan))
	{
		foreach($acuan_pengawasan as $key=>$acuan_pengawasan_id)
		{
			$acuanPengawasanPekerjaan = new AcuanPengawasanPekerjaan(null, $database);
			$acuanPengawasanPekerjaan->setPekerjaanId($pekerjaanId);
			$acuanPengawasanPekerjaan->setAcuanPengawasan($acuan_pengawasan_id);
			$acuanPengawasanPekerjaan->setAktif(true);
			$acuanPengawasanPekerjaan->insert();
		}
	}

	if(isset($_POST['peralatan_proyek_id']))
	{
		$peralatan_proyek_id = $_POST['peralatan_proyek_id'];
		if(is_array($peralatan_proyek_id))
		{
			foreach($peralatan_proyek_id as $key=>$val)
			{
				if(stripos($val, 'rand_') !== false)
				{
					// insert
					$peralatan_id = $inputPost->get('peralatan_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$pemalatanProyek = new PeralatanProyek(null, $database);
					$pemalatanProyek->setPekerjaanId($pekerjaanId);
					$pemalatanProyek->setPeralatanId($peralatan_id);
					$pemalatanProyek->setJumlah($jumlah);
					$pemalatanProyek->setProyekId($proyekId);
					$pemalatanProyek->setAktif(true);

					$pemalatanProyek->insert();

					$arr_peralatan_id[] = $pemalatanProyek->getPeralatanProyekId();
				}
			}
		}
	}
	if(isset($_POST['material_proyek_id']))
	{
		$material_proyek_id = $_POST['material_proyek_id'];
		if(is_array($material_proyek_id))
		{
			foreach($material_proyek_id as $key=>$val)
			{
				if(stripos($val, 'rand_') !== false)
				{
					// insert
					$materialId = $inputPost->get('material_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$jumlah = $inputPost->get('jumlah_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));

					$materialProyek = new MaterialProyek(null, $database);
					$materialProyek->setPekerjaanId($pekerjaanId);
					$materialProyek->setMaterialId($materialId);
					$materialProyek->setProyekId($proyekId);
					$materialProyek->setJumlah($jumlah);
					$materialProyek->setAktif(true);

					$materialProyek->insert();

					$arr_material_id[] = $materialProyek->getMaterialProyekId();
				}
			}
		}
	}
	
	header("Location: ".basename($_SERVER['PHP_SELF'])."?option=detail&buku_harian_id=$bukuHarianId");
	exit();
}


if(isset($_POST['add-boq']))
{
	$waktuBuat = $currentAction->getTime();
	$waktuUbah = $waktuBuat;
	$ipBuat = $_SERVER['REMOTE_ADDR'];
	$ipUbah = $_SERVER['REMOTE_ADDR'];

	$bukuHarianId = $inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$proyekId = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$jenis_pekerjaan_id = $inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$tipe_pondasi_id = $inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kelas_tower_id = $inputPost->getKelasTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$lokasi_proyek_id = $inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$kegiatan = $inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$acuan_pengawasan = $inputPost->getAcuanPengawasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$latitude = $inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$longitude = $inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$altitude = $inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$jumlah_pekerja = $inputPost->getJumlahPekerjaan(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	
	$arr_peralatan_id = array();
	
	if(isset($_POST['boq_proyek_id']))
	{
		$boq_proyek_id = $_POST['boq_proyek_id'];
		if(is_array($boq_proyek_id))
		{
			foreach($boq_proyek_id as $key=>$val)
			{
				if(stripos($val, 'rand_') !== false)
				{
					// insert
					$boqId = $inputPost->get('boq_proyek_id_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT));
					$volumeProyek = $inputPost->get('volume_'.$val, array(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT));

					$boq = new BillOfQuantity(null, $database);

					try
					{
						$boq->find($boqId);

						$boqMin = $boq->getVolumeProyek();
						$boqMax = $boq->getVolume();

						if($volumeProyek < $boqMin)
						{
							$volumeProyek = $boqMin;
						}
						if($volumeProyek > $boqMax)
						{
							$volumeProyek = $boqMax;
						}	
						
						$boqProyek = new BillOfQuantityProyek(null, $database);

						$boqProyek->setProyekId($proyekId);
						$boqProyek->setBukuHarianId($bukuHarianId);
						$boqProyek->setBillOfQuantityId($boqId);
						$boqProyek->setVolumeProyek($volumeProyek);
						$boqProyek->setVolume($boq->getVolume());
						$boqProyek->setAktif(true);
						$boqProyek->setIpBuat($ipBuat);
						$boqProyek->setIpUbah($ipUbah);
						$boqProyek->setWaktuBuat($waktuBuat);
						$boqProyek->setWaktuUbah($waktuUbah);
						$boqProyek->setSupervisorBuat($currentLoggedInSupervisor->getSupervisorId());
						$boqProyek->setSupervisorUbah($currentLoggedInSupervisor->getSupervisorId());

						$persen = $boqProyek->getVolume() > 0 ? 100 * $boqProyek->getVolumeProyek() / $boqProyek->getVolume() : 0;
						$boqProyek->setPersen($persen);
						
						$boqProyek->insert();
						
						// update BOQ
						$boq->setVolumeProyek($volumeProyek)->update();

						$arr_boq_id[] = $boqProyek->getBillOfQuantityProyekId();
					}
					catch(Exception $e)
					{
						// Do nothing
					}
				}
			}
		}
	}

	// update rata-rata
	$boqFinder = new BillOfQuantity(null, $database);
	try
	{
		$boqData = $boqFinder->findByProyekId($proyekId);
		$boqResult = $boqData->getResult();
		$persen = BoqUtil::getAveragePercent($boqResult);
		if($persen > 0)
		{
			$progresProyek = new ProgresProyek(null, $database);
			$progresProyek->setProyekId($proyekId);
			$progresProyek->setPersen($persen);
			$progresProyek->setAktif(true);
			$progresProyek->setIpBuat($ipBuat);
			$progresProyek->setIpUbah($ipUbah);
			$progresProyek->setWaktuBuat($waktuBuat);
			$progresProyek->setWaktuUbah($waktuUbah);
			$progresProyek->setSupervisorBuat($currentLoggedInSupervisor->getSupervisorId());
			$progresProyek->setSupervisorUbah($currentLoggedInSupervisor->getSupervisorId());
			$progresProyek->insert();

			$proyek = new Proyek(null, $database);
			$proyek->setProyekId($proyekId)->setPersen($persen)->update();
		}
	}
	catch(Exception $e)
	{
		// Do nothing
	}
	
	header("Location: ".basename($_SERVER['PHP_SELF'])."?option=detail&buku_harian_id=$bukuHarianId");
	exit();
}


if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";

$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<style>
	.note-hint-popover {
		position: absolute;
	}
</style>
<script>
	let elements = [];
	jQuery(function($) {
		let editors = [];
		var activeEditor = null;	
		$('textarea').each(function(index){
			$(this).attr('data-index', index);
			$(this).addClass('summernote-source');
			editors[index] = $(this).summernote({
				height: 200,
				hint: {
					words: [],
					match: /\b(\w{1,})$/,
					search: function (keyword, callback) {
						callback($.grep(this.words, function (item) {
							return item.indexOf(keyword) === 0;
						}));
					}
				},
				toolbar: [
					['style', ['style', 'bold', 'italic', 'underline']],
					['para', ['ul', 'ol', 'paragraph']],
					['font', ['fontname', 'fontsize', 'color', 'background']],
					['insert', ['picture', 'table']],
				],
				callbacks: {
					onImageUpload: function (files) {
					},
					onMediaDelete: function (target) {
					},
					onFocus: function() {
						let idx = $(this).attr('data-index');
						activeEditor = editors[idx];
					}
				}
			});
			elements[index] = $(this);
		});

		$('textarea.summernote-source').each(function(index) {
			$(this).next().closest('.note-editor').on('click', function(e) {
				activeEditor = editors[index];  
				if (activeEditor) {
					activeEditor.summernote('focus');
				}
			});
		});

		$(document).on('change', '.note-image-input.form-control-file.note-form-control.note-input', function(e) {
			var files = e.target.files;

			if (files.length > 0) {
				var file = files[0];
				if (file.type.startsWith('image/')) {
					let mdl = $(this).closest('.modal-dialog');
					let btn = mdl.find('.note-image-btn');
					btn[0].disabled = false;
				} else {
					alert("Please select an image file.");
				}
			}
		});

		$(document).on('click', '.note-image-btn', function() {
			let btn = $(this);
			if (activeEditor) {
				var fileInput = $(this).closest('.note-modal').find('.note-image-input.form-control-file.note-form-control.note-input')[0];
				var file = fileInput.files[0];
				if (file) {
					var reader = new FileReader();
					reader.onload = function(event) {
						var base64Image = event.target.result;
						activeEditor.summernote('insertImage', base64Image);
						fileInput.value = "";
						btn.closest('.modal').modal('hide');  // Close the modal
					};
					reader.readAsDataURL(file);
				}
			} else {
				console.log('No active editor found.');
			}
		});
	});

</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<script type="text/javascript">
		$(document).ready(function(e) {
			detectLocation();
		});
		function testform(frm)
		{
			var latitude = $('[name="latitude"]').val();
			var longitude = $('[name="longitude"]').val();
			var altitude = $('[name="altitude"]').val();
			
			if(latitude == '' || longitude == '' || latitude == '0' || longitude == '0')
			{
				detectLocation();
				return false;
			}
			else
			{
				return true;
			}
		}
		var options = {
		enableHighAccuracy: true,
		timeout: 5000,
		maximumAge: 0
		};
		function detectLocation()
		{
			navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
		}
		function onSuccess(position)
		{
			$('[name="latitude"]').val(position.coords.latitude);
			$('[name="longitude"]').val(position.coords.longitude);
			$('[name="altitude"]').val(position.coords.altitude);
		}
		function onError(error)
		{
			var errorMessage = "";
			switch(error.code) {
				case error.PERMISSION_DENIED:
					errorMessage = "Pengguna menolak permintaan geolokasi."
					break;
				case error.POSITION_UNAVAILABLE:
					errorMessage = "Geolokasi tidak tersedia."
					break;
				case error.TIMEOUT:
					errorMessage = "Tenggang waktu permintaan geolokasi telah habis."
					break;
				case error.UNKNOWN_ERROR:
					errorMessage = "Terjadi kesalahan yang tidak diketahui."
					break;
			}
			mui.alert(errorMessage, "Tutup");
		}
		
		</script>
		<script type="text/javascript">
		var dataCuaca = [];
		var bukuHarianID = 0;
		</script>

		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css?rand=<?php echo mt_rand(111111, 999999);?>"">
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian-editor.css?rand=<?php echo mt_rand(111111, 999999);?>"">
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js?<?php echo mt_rand(111111, 99999999);?>"></script>
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian-editor.js?rand=<?php echo mt_rand(111111, 999999);?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
		
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
						<select name="proyek_id" class="form-control" required="required" onchange="window.location='?user_action=create&proyek_id='+this.value+'&tanggal=<?php echo $inputGet->getTanggal();?>'">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php 
							$supervisorProyek = new SupervisorProyek(null, $database);
							$specs1 = PicoSpecification::getInstance()
								->add(['supervisorId', $currentLoggedInSupervisor->getSupervisorId()])
								->add(['supervisor.aktif', true])
								->add(['proyek.aktif', true])
							;
							$sorts1 = PicoSortable::getInstance()
								->add(['proyek.proyekId', PicoSort::ORDER_TYPE_DESC])
							;
							try
							{
								$pageData1 = $supervisorProyek->findAll($specs1, null, $sorts1, true);
								$rows = $pageData1->getResult();
								foreach($rows as $row)
								{
									$proyek = $row->getProyek();
									if($proyek != null)
									{
									?>
									<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $proyekId ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
									<?php
									}
								}
							}
							catch(Exception $e)
							{
								// do nothing
							}
							?>
						</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
						<?php
						$tanggal = $inputGet->getTanggal();
						if(empty($tanggal))
						{
							$tanggal = date('Y-m-d');
						}
						?>
						<input class="form-control" type="date" name="tanggal" id="tanggal" value="<?php echo $tanggal;?>">
						</td>
					</tr>
					
					<tr>
					<td>Lokasi Pekerjaan</td>
					<td>
					<select name="lokasi_proyek_id[]" id="lokasi_proyek_id" class="form-control" 
						data-placeholder="<?php echo $appLanguage->getLabelSelectLocation();?>"
						data-search-placeholder="<?php echo $appLanguage->getLabelSearch();?>" 
						data-label-selected="<?php echo $appLanguage->getLabelSelected();?>"
						data-label-select-all="<?php echo $appLanguage->getLabelSelectAll();?>" 
						multiple data-multi-select>
						<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyek(null, $database), 
						PicoSpecification::getInstance()
							->addAnd(new PicoPredicate(Field::of()->aktif, true))
							->addAnd(new PicoPredicate(Field::of()->draft, false))
							->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId))
							, 
						PicoSortable::getInstance()
							->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
							->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
						Field::of()->lokasiProyekId, Field::of()->nama)
						; ?>
					</select>
					</td>
					</tr>
					<tr>
						<td>Kegiatan</td>
						<td><textarea spellcheck="false" name="kegiatan" id="kegiatan" class="form-control"></textarea></td>
					</tr>
					<tr>
						<td>Permasalahan dan Rekomendasi</td>
						<td>
							<table class="tabel-control-two-side">
								<tbody>
									<tr>
										<td>
											<select class="form-control" data-name="permasalahan_id" name="permasalahan_id[0]">
												<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
												<?php echo AppFormBuilder::getInstance()->createSelectOption(new PermasalahanMin(null, $database), 
												PicoSpecification::getInstance()
													->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId))
													->addAnd(new PicoPredicate(Field::of()->aktif, true))
													->addAnd(new PicoPredicate(Field::of()->ditutup, false)), 
												PicoSortable::getInstance()
													->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
													->add(new PicoSort(Field::of()->permasalahan, PicoSort::ORDER_TYPE_ASC)), 
												Field::of()->permasalahanId, Field::of()->permasalahan)
												->setTextNodeFormat('"%s : %s : %s", permasalahan, rekomendasi, tindakLanjut')
												; ?>
												?>
											</select>
										</td>
										<td>
											<button type="button" class="btn btn-danger remove-issue">×</button>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2">
											<button type="button" class="btn btn-primary add-issue">
												Tambah
											</button>
											<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permasalahan-modal">
												Atur
											</button>
										</td>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
					<tr>
						<td>Bill of Quality</td>
					<td>
						<select class="form-control" name="bill_of_quantity_id" id="bill_of_quantity_id">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantity(null, $database), 
							PicoSpecification::getInstance()
								->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId))
								->addAnd(new PicoPredicate(Field::of()->level, 1))
								->addAnd(
									PicoSpecification::getInstance()
									->addOr(new PicoPredicate(Field::of()->parentId, null))
									->addOr(new PicoPredicate(Field::of()->parentId, 0))
								)
								->addAnd(new PicoPredicate(Field::of()->aktif, true)), 
							PicoSortable::getInstance()
								->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
								->add(new PicoSort(Field::of()->timeCreate, PicoSort::ORDER_TYPE_DESC)), 
							Field::of()->billOfQuantityId, Field::of()->nama, null, [Field::of()->proyekId, Field::of()->parentId])
							; ?>
							?>
						</select>
					</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table class="tabel-control" id="tabel-boq" cellpadding="0" cellspacing="0" border="0" class="form-control">
								<tbody></tbody>
							</table>
							<div class="form-control-add">
							<input type="button" value="Tambah" id="tambah-boq" class="btn btn-primary">
							</div>
						</td>
					</tr>

					<tr>
					<td>Acuan Pengawasan</td>
					<td>
						<div class="acuan-pengawasan-container">
						
						</div>
					</td>
					</tr>
					<tr>
					<td>Man Power</td>
					<td>
						<table class="tabel-control" id="tabel-man-power" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody></tbody>
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-man-power" class="btn btn-primary">
						<span id="total-man-power"></span>
						</div>
					</td>
					</tr>
					<tr>
					<td>Peralatan</td>
					<td>
						<table class="tabel-control" id="tabel-peralatan" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody></tbody>
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-peralatan" class="btn btn-primary">
						</div>

					</td>
					</tr>
					<tr>
					<td>Material</td>
					<td>
						<table class="tabel-control" id="tabel-material" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody></tbody>
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-material" class="btn btn-primary">
						</div>
					</td>
					</tr>

				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<input type="hidden" name="latitude" value="" />
							<input type="hidden" name="longitude" value="" />
							<input type="hidden" name="altitude" value="" />
							<button type="submit" class="btn btn-success" name="user_action" value="create"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
        
		<div style="display:none">
			<select class="resource-peralatan form-control">
			</select>
			<select class="resource-material form-control">
			</select>
			<select class="resource-bill-of-quantity form-control">
			</select>
			<select class="resource-man-power form-control">
			</select>		
		</div>
	</div>

    <div class="modal modal-lg fade" data-mode="list" id="permasalahan-modal" tabindex="-1" aria-labelledby="permasalahanLabel" aria-hidden="true" data-proyek-id="<?php echo $proyekId;?>">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="permasalahanLabel"><?php echo $appLanguage->getIssue();?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success save-issue"><?php echo $appLanguage->getButtonSave();?></button>
                <button type="button" class="btn btn-primary cancel-issue"><?php echo $appLanguage->getButtonCancel();?></button>
                <button type="button" class="btn btn-primary add-issue"><?php echo $appLanguage->getButtonAdd();?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $appLanguage->getButtonClose();?></button>
            </div>
        </div>
        </div>
    </div>

    <div class="modal modal-lg fade" data-mode="list" id="rekomendasi-modal" tabindex="-1" aria-labelledby="rekomendasiLabel" aria-hidden="true" data-proyek-id="<?php echo $proyekId;?>">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="rekomendasiLabel"><?php echo $appLanguage->getRecommendation();?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success save-recommendation"><?php echo $appLanguage->getButtonSave();?></button>
                <button type="button" class="btn btn-primary cancel-recommendation"><?php echo $appLanguage->getButtonCancel();?></button>
                <button type="button" class="btn btn-primary add-recommendation"><?php echo $appLanguage->getButtonAdd();?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $appLanguage->getButtonClose();?></button>
            </div>
        </div>
        </div>
    </div>


	<!-- Modal -->
	<div class="modal fade" id="add-location-modal" tabindex="-1" aria-labelledby="add-location-modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="add-location-modalLabel"><?php echo $appLanguage->getAddProjectLocation();?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<form name="formlokasi_proyek" id="formlokasi_proyek" action="" method="post" enctype="multipart/form-data" onsubmit="return tambahLokasi();">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="responsive responsive-two-cols">
					<tbody>
					<tr>
						<td>Nama Lokasi</td>
						<td><input type="text" class="form-control" name="nama" data-required="true" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Kode Lokasi</td>
						<td><input type="text" class="form-control" name="kode_lokasi" data-required="true" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Latitude</td>
						<td><input type="number" step="any" class="form-control" name="latitude" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Longitude</td>
						<td><input type="number" step="any" class="form-control" name="longitude" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Atitude</td>
						<td><input type="number" step="any" class="form-control" name="atitude" autocomplete="off"></td>
						</tr>
						<tr>
						<td></td>
						<td>					
						<input type="hidden" name="proyek_id" id="proyek_id" value="<?php echo $proyekId;?>"> 
						</td>
						</tr>
					</tbody>
				</table>
			</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" type="button" id="detect" onclick="detectLocation()">Deteksi</button> 
				<button type="button" class="btn btn-success" id="addLocation" onClick="$(this).attr('disabled', 'disabled'); $('#formlokasi_proyek').submit(); ">Simpan</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
			</div>
			</div>
		</div>
	</div>
</div>

<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$bukuHarian = new BukuHarian(null, $database);
	try{
		$bukuHarian->findOneByBukuHarianId($inputGet->getBukuHarianId());
		if($bukuHarian->hasValueBukuHarianId())
		{
			$proyekId = $bukuHarian->getProyekId();
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select name="proyek_id" class="form-control" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php 
								$supervisorProyek = new SupervisorProyek(null, $database);
								$specs1 = PicoSpecification::getInstance()
									->add(['supervisorId', $currentLoggedInSupervisor->getSupervisorId()])
									->add(['supervisor.aktif', true])
									->add(['proyek.aktif', true])
								;
								$sorts1 = PicoSortable::getInstance()
									->add(['proyek.proyekId', PicoSort::ORDER_TYPE_DESC])
								;
								try
								{
									$pageData1 = $supervisorProyek->findAll($specs1, null, $sorts1, true);
									$rows = $pageData1->getResult();
									foreach($rows as $row)
									{
										$proyek = $row->getProyek();
										if($proyek != null)
										{
										?>
										<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $proyekId ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
										<?php
										}
									}
								}
								catch(Exception $e)
								{
									// do nothing
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPermasalahan();?></td>
						<td>
							<textarea class="form-control" name="permasalahan" id="permasalahan" spellcheck="false"><?php echo $bukuHarian->getPermasalahan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getRekomendasi();?></td>
						<td>
							<textarea class="form-control" name="rekomendasi" id="rekomendasi" spellcheck="false"><?php echo $bukuHarian->getRekomendasi();?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-success" name="user_action" value="update"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php 
		}
		else
		{
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php
		}
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
	catch(Exception $e)
	{
require_once __DIR__ . "/inc.app/header-supervisor.php";
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
}
else if(($inputGet->getUserAction() == 'add-image') && $inputGet->getBukuHarianId() != 0)
{
	require_once __DIR__ . "/inc.app/header-supervisor.php";
	// Do somtething here when exception
	?>
	<style>
		#preview
		{
			display: flex;
    flex-wrap: wrap;
		}
		#preview img{
			height: 120px;
			margin: 2px;
		}
		#progress {
            width: 100%;
            background: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            display: none;
        }
        #progress-bar {
            height: 20px;
            background: #4caf50;
            width: 0;
        }
	</style>
	<form id="uploadForm" enctype="multipart/form-data">
		<input type="hidden" name="buku_harian_id" value="<?php echo $inputGet->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);?>">
        <input type="file" id="image" name="image[]" accept="image/*" multiple required>
        <div id="preview"></div>
        <div id="progress">
            <div id="progress-bar"></div>
        </div>
        <button type="submit">Upload</button>
    </form>

    <script>
        document.getElementById('image').addEventListener('change', function() {
            const files = this.files;
			for(let i in files)
			{
				if (files[i]) {
					let img = document.createElement('img');
					let file = files[i];
					const reader = new FileReader();
					reader.onload = function(e) {
						const preview = document.getElementById('preview');
						
						img.src = e.target.result;
						preview.appendChild(img);
						preview.style.display = 'block';
					}
					reader.readAsDataURL(file);
				}
			}
        });

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the default way

            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            
            xhr.open('POST', 'lib.mobile-tools/ajax-upload-image.php', true);

            // Update the progress bar
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    document.getElementById('progress').style.display = 'block';
                    document.getElementById('progress-bar').style.width = percentComplete + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('progress').style.display = 'none';
                    document.getElementById('progress-bar').style.width = '0%'; // Reset progress bar
                } else {
                }
            };

            xhr.send(formData);
        });
    </script>
	<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if(($inputGet->getUserAction() == 'add-work' || $inputGet->getUserAction() == 'add-boq') && $inputGet->getBukuHarianId() != 0)
{
	$bukuHarian = new BukuHarian(null, $database);
	try{
		$subqueryMap = array(
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		), 
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		)
		);
		$bukuHarian->findOneWithPrimaryKeyValue($inputGet->getBukuHarianId(), $subqueryMap);
		if($bukuHarian->hasValueBukuHarianId())
		{
			$proyekId = $bukuHarian->getProyekId();
			$x = array(1=>'cerah', 2=>'berawan', 3=>'hujan', 4=>'hujan-lebat');
			$data_cuaca = array();
			for($i = 0; $i<24; $i++)
			{
				$tt = sprintf("%02d", $i);
				$tv = $bukuHarian->get('c'.$tt);
				$data_cuaca[$tt] = isset($x[$tv]) ? $x[$tv] : null;
			}
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($bukuHarian->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $bukuHarian->getWaitingFor());?></div>
				<?php
		}
		?>
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian-editor.css">
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js"></script>
		<script type="text/javascript">
		var dataCuaca = <?php echo json_encode($data_cuaca);?>;
		var bukuHarianID = <?php echo $bukuHarian->getBukuHarianId();?>;
		</script>
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian-editor.js"></script>
	
		<div id="map-container"></div>
			<form name="formpekerjaan" method="post" enctype="multipart/form-data">
				<div id="accordion" class="accordion">
					<div class="card">
						<div class="card-header" id="headingOne">
						<h5 class="mb-0">
						<button type="button" class="btn collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" 
						style="width:100%; text-align: left; padding-left: 0; margin-top: -13px; margin-bottom:-7px;">
							Buku Harian
						</button>
						</h5>
						</div>

						<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
							<div class="card-body">
								<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td><?php echo $appEntityLanguage->getSupervisor();?></td>
											<td><?php echo $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getProyek();?></td>
											<td><?php echo $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek()->getNama() : "";?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getTanggal();?></td>
											<td><?php echo $bukuHarian->getTanggal();?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getPermasalahan();?></td>
											<td><?php echo $bukuHarian->getPermasalahan();?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getRekomendasi();?></td>
											<td><?php echo $bukuHarian->getRekomendasi();?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
											<td><?php echo $bukuHarian->getWaktuBuat();?></td>
										</tr>
										<tr>
											<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
											<td><?php echo $bukuHarian->getWaktuUbah();?></td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>

				<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php
				if($inputGet->getUserAction() == 'add-work')
				{
				?>
					<tr>
					<td>Pekerjaan</td>
					<td>
					<select name="jenis_pekerjaan_id" id="jenis_pekerjaan_id" class="form-control">
						<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
						<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaan(null, $database), 
						PicoSpecification::getInstance()
							->addAnd(new PicoPredicate(Field::of()->aktif, true))
							->addAnd(new PicoPredicate(Field::of()->draft, false))
							->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId))
							, 
						PicoSortable::getInstance()
							->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
							->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
						Field::of()->jenisPekerjaanId, Field::of()->nama, $inputGet->getJenisPekerjaanId(),
						array(
							Field::of()->tipePondasiId, 
							Field::of()->kelasTowerId, 
							Field::of()->lokasiProyekId, 
							Field::of()->kegiatan
							)
						)
						; ?>
					</select>
					</td>
					</tr>
					<tr style="display:none">
					<td>Lokasi Pekerjaan</td>
					<td>
					<select name="lokasi_proyek_id" id="lokasi_proyek_id" class="form-control">
						<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
						<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyek(null, $database), 
						PicoSpecification::getInstance()
							->addAnd(new PicoPredicate(Field::of()->aktif, true))
							->addAnd(new PicoPredicate(Field::of()->draft, false))
							->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId))
							, 
						PicoSortable::getInstance()
							->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
							->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
						Field::of()->lokasiProyekId, Field::of()->nama)
						; ?>
					</select>
					</td>
					</tr>
					<tr style="display:none">
					<td></td>
					<td><span class="form-control-add">
						<button type="button" id="tambah-lokasi" class="btn btn-primary" onclick="detectLocation(); $('#add-location-modal').modal('show');">Tambah</button>
					</span></td>
					</tr>
					<tr style="display:none">
					<td>Tipe Pondasi</td>
					<td><select class="form-control" name="kelas_tower_id" id="kelas_tower_id">
						<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
						<?php echo AppFormBuilder::getInstance()->createSelectOption(new TipePondasi(null, $database), 
						PicoSpecification::getInstance()
							->addAnd(new PicoPredicate(Field::of()->aktif, true))
							->addAnd(new PicoPredicate(Field::of()->draft, false)), 
						PicoSortable::getInstance()
							->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
							->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
						Field::of()->tipePondasiId, Field::of()->nama)
						; ?>
					</select>
					</td>
					</tr>
					<tr>
					<td>Kelas Tower</td>
					<td>
						<select class="form-control" name="tipe_pondasi_id" id="tipe_pondasi_id">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php echo AppFormBuilder::getInstance()->createSelectOption(new KelasTower(null, $database), 
							PicoSpecification::getInstance()
								->addAnd(new PicoPredicate(Field::of()->aktif, true))
								->addAnd(new PicoPredicate(Field::of()->draft, false)), 
							PicoSortable::getInstance()
								->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
								->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
							Field::of()->kelasTowerId, Field::of()->nama)
							; ?>
						</select>
					</td>
					</tr>
					<tr>
					<td>Kegiatan</td>
					<td><textarea spellcheck="false" name="kegiatan" id="kegiatan" class="form-control" required></textarea></td>
					</tr>
					<tr>
					<td>Acuan Pengawasan</td>
					<td><?php echo CommonUtil::getAcuanPengawasanPekerjaan($database, 'check');?></td>
					</tr>
					<tr>
					<td>Man Power</td>
					<td><input type="number" min="0" name="jumlah_pekerja" id="jumlah_pekerja" class="form-control"></td>
					</tr>
					<tr>
					<td>Peralatan</td>
					<td>
						<table class="tabel-control" id="tabel-peralatan" cellpadding="0" cellspacing="0" border="0" class="form-control">
						
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-peralatan" class="btn btn-primary">
						</div>

					</td>
					</tr>
					<tr>
					<td>Material</td>
					<td>
						<table class="tabel-control" id="tabel-material" cellpadding="0" cellspacing="0" border="0" class="form-control">
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-material" class="btn btn-primary">
						</div>
					</td>
					</tr>
					<?php
				}
				else if($inputGet->getUserAction() == 'add-boq')
				{
				?>

					<tr>
					<td>Bill of Quality</td>
					<td>
						<select class="form-control" name="bill_of_quantity_id" id="bill_of_quantity_id">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantity(null, $database), 
							PicoSpecification::getInstance()
								->addAnd(new PicoPredicate(Field::of()->proyekId, $bukuHarian->getProyekId()))
								->addAnd(new PicoPredicate(Field::of()->level, 1))
								->addAnd(
									PicoSpecification::getInstance()
									->addOr(new PicoPredicate(Field::of()->parentId, null))
									->addOr(new PicoPredicate(Field::of()->parentId, 0))
								)
								->addAnd(new PicoPredicate(Field::of()->aktif, true)), 
							PicoSortable::getInstance()
								->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
								->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
							Field::of()->billOfQuantityId, Field::of()->nama, $bukuHarian->getBillOfQuantityId(), array('proyekId', 'parentId'))
							; ?>
							?>
						</select>
					</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table class="tabel-control" id="tabel-boq" cellpadding="0" cellspacing="0" border="0" class="form-control">
						
							</table>
							<div class="form-control-add">
							<input type="button" value="Tambah" id="tambah-boq" class="btn btn-primary">
							</div>
						</td>
					</tr>
					<tr>
					<tr>
						<td></td>
						<td>
						<input type="hidden" name="latitude" id="latitude" value="">
						<input type="hidden" name="longitude" id="longitude" value="">
						<input type="hidden" name="altitude" id="altitude" value="">
						<input type="hidden" name="pekerjaan_id" value="<?php echo $pekerjaanId;?>">
						<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>">
						<input type="hidden" name="proyek_id" value="<?php echo $bukuHarian->getProyekId();?>">
						<div class="button-area">
							<input type="submit" name="<?php echo $inputGet->getUserAction(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);?>" value="Simpan"  class="btn btn-success">
							<input type="button" name="back" value="Batal" onclick="history.go(-1)" class="btn btn-primary">
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
						</div>

						</td>
					</tr>
					<?php
				}
					?>
				</table>

				<?php
					include "inc.app/dom-buku-harian.php";
				?>
				
				<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
							
			</form>
		</div>
	</div>

	<div style="display:none">
		<select class="resource-peralatan form-control">
		</select>
		<select class="resource-material form-control">
		</select>
		<select class="resource-bill-of-quantity form-control">
		</select>
		<script>
			jQuery(function(){
				$('.resource-peralatan').load('lib.mobile-tools/ajax-load-peralatan.php');
				$('.resource-material').load('lib.mobile-tools/ajax-load-material.php');
			});
		</script>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="add-location-modal" tabindex="-1" aria-labelledby="add-location-modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="add-location-modalLabel"><?php echo $appLanguage->getAddProjectLocation();?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<form name="formlokasi_proyek" id="formlokasi_proyek" action="" method="post" enctype="multipart/form-data" onsubmit="return tambahLokasi();">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="responsive responsive-two-cols">
					<tbody>
					<tr>
						<td>Nama Lokasi</td>
						<td><input type="text" class="form-control" name="nama" data-required="true" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Kode Lokasi</td>
						<td><input type="text" class="form-control" name="kode_lokasi" data-required="true" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Latitude</td>
						<td><input type="number" step="any" class="form-control" name="latitude" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Longitude</td>
						<td><input type="number" step="any" class="form-control" name="longitude" autocomplete="off" required="required"></td>
						</tr>
						<tr>
						<td>Atitude</td>
						<td><input type="number" step="any" class="form-control" name="atitude" autocomplete="off"></td>
						</tr>
						<tr>
						<td></td>
						<td>					
						<input type="hidden" name="proyek_id" id="proyek_id" value="<?php echo $proyekId;?>"> 
						</td>
						</tr>
					</tbody>
				</table>
			</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" type="button" id="detect" onclick="detectLocation()">Deteksi</button> 
				<button type="button" class="btn btn-success" id="addLocation" onClick="$(this).attr('disabled', 'disabled'); $('#formlokasi_proyek').submit(); ">Simpan</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
			</div>
			</div>
		</div>
	</div>
	

<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
		}
		else
		{
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php
		}
	}
	catch(Exception $e)
	{
require_once __DIR__ . "/inc.app/header-supervisor.php";
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
}
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$bukuHarian = new BukuHarian(null, $database);
	try{
		$subqueryMap = array(
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		), 
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		)
		);
		$bukuHarian->findOneWithPrimaryKeyValue($inputGet->getBukuHarianId(), $subqueryMap);
		if($bukuHarian->hasValueBukuHarianId())
		{
			$x = array(1=>'cerah', 2=>'berawan', 3=>'hujan', 4=>'hujan-lebat');
			$data_cuaca = array();
			for($i = 0; $i<24; $i++)
			{
				$tt = sprintf("%02d", $i);
				$tv = $bukuHarian->get('c'.$tt);
				$data_cuaca[$tt] = isset($x[$tv]) ? $x[$tv] : null;
			}
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($bukuHarian->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $bukuHarian->getWaitingFor());?></div>
				<?php
		}
		?>
		<style type="text/css">
		.item-pekerjaan{
			padding:0 0 8px 0;
		}
		.separator{
			margin-top:5px;
			margin-bottom:5px;
			border-bottom:1px solid #EEEEEE;
			height:1px;
			box-sizing:border-box;
		}
		.cuaca-container{
			padding:10px 0;
		}
		.detail-proyek{
			margin-bottom:10px;
		}
		</style>
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js">
		</script>
		<script type="text/javascript">
		var dataCuaca = <?php echo json_encode($data_cuaca);?>;
		var bukuHarianID = <?php echo $bukuHarian->getBukuHarianId();?>;
		$(document).ready(function(e) {
			initArea('area.cuaca-control');
			initMenu('.menu-cuaca-item a');
			renderCuaca('area.cuaca-control', dataCuaca);
		});
		</script>
		<h4>Buku Harian</h4>
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $bukuHarian->getTanggal();?></td>
					</tr>


					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $bukuHarian->getWaktuBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuHarian->getWaktuUbah();?></td>
					</tr>

				</tbody>
			</table>

			<h4>Kegiatan</h4>

			<?php echo $bukuHarian->getKegiatan();?>

			<h4>Permasalahan dan Rekomendasi</h4>
			<?php 
			$specificationRekomendasiPekerjaan = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguagePermasalahan = new AppEntityLanguage(new RekomendasiPekerjaan(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new RekomendasiPekerjaan(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationRekomendasiPekerjaan, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguagePermasalahan->getPermasalahan();?></td>
						<td><?php echo $appEntityLanguagePermasalahan->getRekomendasi();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($rekomendasiPerekraan = $pageData->fetch())
					{
						$dataIndex++;
						$permasalahan = $rekomendasiPerekraan->issetPermasalahan() ? $rekomendasiPerekraan->getPermasalahan() : new Permasalahan();
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $permasalahan->getPermasalahan();?></td>
						<td data-col-name="volume"><?php echo $permasalahan->getRekomendasi();?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>
			
			<h4>Bill Of Quantity</h4>
			<?php 
			$specificationBoq = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguageBoq = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new BillOfQuantityProyek(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationBoq, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageBoq->getBillOfQuantity();?></td>
						<td><?php echo $appEntityLanguageBoq->getVolume();?></td>
						<td><?php echo $appEntityLanguageBoq->getVolumeProyek();?></td>
						<td width="20%"><?php echo $appEntityLanguageBoq->getPersen();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($billOfQuantityProyek = $pageData->fetch())
					{
						$dataIndex++;
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $billOfQuantityProyek->issetBillOfQuantity() ? $billOfQuantityProyek->getBillOfQuantity()->getNama() : "";?></td>
						<td data-col-name="volume"><?php echo $billOfQuantityProyek->getVolume();?></td>
						<td data-col-name="volume_proyek"><?php echo $billOfQuantityProyek->getVolumeProyek();?></td>
						<td data-col-name="persen"><?php echo $billOfQuantityProyek->getPersen();?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>

			<h4>Acuan Pengawasan Proyek</h4>
			<?php 
			$specificationAcuanPengawasanProyek = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguageAcuanPengawasanProyek = new AppEntityLanguage(new AcuanPengawasanProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$appEntityLanguageAcuanPengawasan = new AppEntityLanguage(new AcuanPengawasan(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new AcuanPengawasanProyek(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationAcuanPengawasanProyek, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageAcuanPengawasanProyek->getAcuanPengawasan();?></td>
						<td><?php echo $appEntityLanguageAcuanPengawasan->getJenisHirarkiKontrak();?></td>
						<td><?php echo $appEntityLanguageAcuanPengawasan->getSatusAcuanPengawasan();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($acuanPengawasanProyek = $pageData->fetch())
					{
						$dataIndex++;

						$acuanPengawasan = $acuanPengawasanProyek->issetAcuanPengawasan() ? $acuanPengawasanProyek->getAcuanPengawasan() : new AcuanPengawasan();
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $acuanPengawasan->getNama();?></td>
						<td data-col-name="jenis_hirarki_kontrak"><?php echo $acuanPengawasan->issetJenisHirarkiKontrak() ? $acuanPengawasan->getJenisHirarkiKontrak()->getNama() : "";?></td>
						<td data-col-name="jenis_hirarki_kontrak"><?php echo $acuanPengawasan->issetStatusAcuanPengawasan() ? $acuanPengawasan->getStatusAcuanPengawasan()->getNama() : "";?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>


			<h4>Man Power</h4>
			<?php 
			$specificationManPower = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguageManPowerProyek = new AppEntityLanguage(new ManPowerProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new ManPowerProyek(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationManPower, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageManPowerProyek->getNama();?></td>
						<td width="20%"><?php echo $appEntityLanguageManPowerProyek->getJumlahPekerja();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($manPowerProyek = $pageData->fetch())
					{
						$dataIndex++;
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $manPowerProyek->issetManPower() ? $manPowerProyek->getManPower()->getNama() : "";?></td>
						<td data-col-name="volume_proyek"><?php echo $manPowerProyek->getJumlahPekerja();?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>

			<h4>Material</h4>
			<?php 
			$specificationMaterial = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguageMaterialProyek = new AppEntityLanguage(new MaterialProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new MaterialProyek(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationMaterial, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageMaterialProyek->getNama();?></td>
						<td width="20%"><?php echo $appEntityLanguageMaterialProyek->getJumlah();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($materialProyek = $pageData->fetch())
					{
						$dataIndex++;
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $materialProyek->issetMaterial() ? $materialProyek->getMaterial()->getNama() : "";?></td>
						<td data-col-name="volume_proyek"><?php echo $materialProyek->getJumlah();?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>

			<h4>Peralatan</h4>
			<?php 
			$specificationPeralatan = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguagePeralatanProyek = new AppEntityLanguage(new PeralatanProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new PeralatanProyek(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationPeralatan, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguagePeralatanProyek->getNama();?></td>
						<td width="20%"><?php echo $appEntityLanguagePeralatanProyek->getJumlah();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($peralatanProyek = $pageData->fetch())
					{
						$dataIndex++;
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td data-col-name="bill_of_quantity_id"><?php echo $peralatanProyek->issetPeralatan() ? $peralatanProyek->getPeralatan()->getNama() : "";?></td>
						<td data-col-name="volume_proyek"><?php echo $peralatanProyek->getJumlah();?></td>
					</tr>
					<?php 
					}
					?>

				</tbody>
			</table>
			<?php
			}
			}
			catch(Exception $e)
			{
				// Do nothing
			}
			
			?>

			<h4>Laporan Cuaca</h4>
				
			
			<?php
				include "inc.app/dom-buku-harian.php";
			?>
			<div class="button-area">
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl('add-work', Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getAddWork();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl('add-boq', Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getAddBillOfQuantity();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl('add-image', Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getButtonAddImage();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>


				<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
			</div>

			</table>
		</form>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
		}
		else
		{
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php
		}
	}
	catch(Exception $e)
	{
require_once __DIR__ . "/inc.app/header-supervisor.php";
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
}
else 
{
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
$proyekId = $inputGet->getProyekId();
$specMap = array(
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number")
);
$sortOrderMap = array(
	"supervisorId" => "supervisorId",
	"proyekId" => "proyekId",
	"tanggal" => "tanggal",
	"latitude" => "latitude",
	"longitude" => "longitude",
	"ktskId" => "ktskId",
	"accKtsk" => "accKtsk",
	"koordinatorId" => "koordinatorId",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);

// Additional filter here
$specification->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $currentLoggedInSupervisor->getSupervisorId()));
$specification->addAnd(PicoPredicate::getInstance()->equals('tanggal', date('Y-m-d')));

// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "waktuBuat", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new BukuHarian(null, $database);

$subqueryMap = array(
"supervisorId" => array(
	"columnName" => "supervisor_id",
	"entityName" => "SupervisorMin",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "supervisor",
	"propertyName" => "nama"
), 
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"ktskId" => array(
	"columnName" => "ktsk_id",
	"entityName" => "KtskMin",
	"tableName" => "ktsk",
	"primaryKey" => "ktsk_id",
	"objectName" => "ktsk",
	"propertyName" => "nama"
)
);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					// Pilih semua elemen dengan kelas 'buku-harian-button'
					const elements = document.querySelectorAll('.buku-harian-button');
					// Loop melalui setiap elemen dan tambahkan event listener
					elements.forEach(function(element) {
						element.addEventListener('click', function(e) {
							// Tambahkan kode yang ingin dijalankan saat elemen diklik di sini
							let elem = e.target;
							let proyek_id = elem.getAttribute('data-proyek-id');
							let buku_harian_id = elem.getAttribute('data-buku-harian-id');
							let tanggal = elem.getAttribute('data-tanggal');
							openUrl(proyek_id, tanggal, buku_harian_id);
						});
					});
					
					$('.select-project').on('change', function(){
						$(this).closest('form').submit();
					});
				});
				function openUrl(proyek_id, tanggal, buku_harian_id)
				{
					if(buku_harian_id == '')
					{
						window.location = 'buku-harian.php?user_action=create&proyek_id='+proyek_id+'&tanggal='+tanggal;
					}
					else
					{
						window.location = 'buku-harian.php?user_action=detail&buku_harian_id='+buku_harian_id;
					}
				}
			</script>
			<?php
			$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

			$inputGet = new InputGet();
			
			$periodeOri = date('Y-m');
			$periode = $inputGet->getPeriode();
			if(empty($periode))
			{
				$periode = date('Y-m');
			}
			$periode2 = strtotime($periode."-15");

			$sebelumnya = date('Y-m', $periode2 - (31 * 86400));
			$sesudahnya = date('Y-m', $periode2 + (31 * 86400));

			$periodeArr = explode('-', $periode);

			$calendar = new CalendarUtil(intval($periodeArr[0]), intval($periodeArr[1]), 0, true);
			
			$cal = $calendar->getCalendar();
			$calInline = $calendar->getCalendarInline();
			
			$startDate = $calendar->getStartDate();
			$endDate = $calendar->getEndDate();
			
			$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
			if(isset($proyekId) && !empty($proyekId))
			{
				$proyekId = intval($proyekId);
			}
			$specs = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $supervisorId))
			->addAnd(PicoPredicate::getInstance()->equals('proyekId', $proyekId))
			->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals('tanggal', $startDate.' 00:00:00'))
			->addAnd(PicoPredicate::getInstance()->lessThan('tanggal', $endDate.' 23:59:59'))
			;
			
			$bukuHarianFinder = new BukuHarianMin(null, $database);
			
			$buhar = array();
			$startTime = strtotime($startDate);
			$endTime = strtotime($endDate);
			
			$class = 'kosong';
			for($i = $startTime; $i<$endTime; $i+=86400)
			{
				$tanggal = date('Y-m-d', $i);
				$buhar[$tanggal] = (new SetterGetter())
					->setBukuHarianId(null)
					->setTanggal($tanggal)
					->setClass($class)
					;
			}
			try
			{
				$pageData = $bukuHarianFinder->findAll($specs);
				foreach($pageData->getResult() as $bukuHarian)
				{
					$tanggal = $bukuHarian->getTanggal();
					$class = $bukuHarian->getAccKoordinator() ? 'sudah-acc':'belum-acc';
					$buhar[$tanggal] = (new SetterGetter())
						->setBukuHarianId($bukuHarian->getBukuHarianId())
						->setTanggal($tanggal)
						->setClass($class)
						;
				}
			}
			catch(Exception $e)
			{
				// 
			}
			
			?>
			<style>
				.calendar{
					position: relative;
				}
				.jambi-wrapper .calendar .filter-section .filter-control .form-control
				{
					width: 100%;
					max-width: 100%;
					box-sizing: border-box;
				}
				.calendar .btn{
					padding: 2px 8px;
				}
			</style>
			<div class="calendar">
				
				<div class="filter-section">
					<form action="" method="get" class="filter-form">
						
						
						<span class="filter-control">
							<select name="proyek_id" class="form-control select-project">
								<option value=""><?php echo $appLanguage->getSelectProject();?></option>
								<?php 
								$supervisorProyek = new SupervisorProyek(null, $database);
								$specs1 = PicoSpecification::getInstance()
									->add(['supervisorId', $currentLoggedInSupervisor->getSupervisorId()])
									->add(['supervisor.aktif', true])
									->add(['proyek.aktif', true])
								;
								$sorts1 = PicoSortable::getInstance()
									->add(['proyek.proyekId', PicoSort::ORDER_TYPE_DESC])
								;
								try
								{
									$pageData1 = $supervisorProyek->findAll($specs1, null, $sorts1, true);
									$rows = $pageData1->getResult();
									foreach($rows as $row)
									{
										$proyek = $row->getProyek();
										if($proyek != null)
										{
										?>
										<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $inputGet->getProyekId() ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
										<?php
										}
									}
								}
								catch(Exception $e)
								{
									// do nothing
								}
								?>
							</select>
						</span>
					</form>
				</div>

				<table width="100%">
					<thead>
						<tr>
							<td style="text-align: left;">
							<button type="button" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>?proyek_id=<?php echo $proyekId;?>&periode=<?php echo $sebelumnya;?>'"><i class="fa-solid fa-chevron-left"></i></button>
							</td>
							<td colspan="5" style="font-size: 1rem; text-align: center;"><?php echo DateUtil::translateDate($appLanguage, date('F Y', $periode2));?></td>
							<td style="text-align: right;">
							<button type="button" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>?proyek_id=<?php echo $proyekId;?>&periode=<?php echo $sesudahnya;?>'"<?php echo $periode == $periodeOri ? ' disabled':'';?>><i class="fa-solid fa-chevron-right"></i></button>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Min</td>
							<td>Sen</td>
							<td>Sel</td>
							<td>Rab</td>
							<td>Kam</td>
							<td>Jum</td>
							<td>Sab</td>
						</tr>
						<?php
						foreach($cal as $row)
						{
							?>
							<tr>
							<?php
							foreach($row as $col)
							{
								?>
								<td width="14%">
									<?php
									if($col['print'])
									{
										$class = $col['class'];
										$tanggal = $col['date'];
										$class2 = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getClass() : "";
										$bukuHarianId = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getBukuHarianId() : "";
										$class = $class.' '.$class2;
									?>
									<button data-proyek-id="<?php echo $proyekId;?>" data-tanggal="<?php echo $tanggal;?>" data-buku-harian-id="<?php echo $bukuHarianId;?>" class="calendar-button buku-harian-button <?php echo $class;?>"><?php echo $col['day'];?></button>
									<?php
									}
									?>
								</td>
								<?php
							}
							?>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			
			<?php /*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){ ?>
		</div>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
/*ajaxSupport*/
}

