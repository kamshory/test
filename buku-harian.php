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
use MagicObject\MagicObject;
use MagicObject\SetterGetter;
use Sipro\Entity\Data\AcuanPengawasan;
use Sipro\Entity\Data\AcuanPengawasanProyek;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Entity\Data\BillOfQuantityProyek;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\BukuHarianMin;
use Sipro\Entity\Data\LokasiPekerjaan;
use Sipro\Entity\Data\LokasiProyek;
use Sipro\Entity\Data\ManPower;
use Sipro\Entity\Data\ManPowerProyek;
use Sipro\Entity\Data\Material;
use Sipro\Entity\Data\MaterialProyek;
use Sipro\Entity\Data\Peralatan;
use Sipro\Entity\Data\PeralatanProyek;
use Sipro\Entity\Data\Permasalahan;
use Sipro\Entity\Data\PermasalahanMin;
use Sipro\Entity\Data\Proyek;
use Sipro\Entity\Data\RekomendasiPekerjaan;
use Sipro\Util\BoqUtil;
use Sipro\Util\CalendarUtil;
use Sipro\Util\DateUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "buku-harian", $appLanguage->getBukuHarian());
$inputGet = new InputGet();
$inputPost = new InputPost();
$dataFilter = null;
$filterProyek = null;
if(
	$currentLoggedInSupervisor->getTipePengguna() == 'supervisor'
	&&
	(
	($currentLoggedInSupervisor->getUmkId() != null && $currentLoggedInSupervisor->getUmkId() != 0) 
	|| 
	($currentLoggedInSupervisor->getTskId() != null && $currentLoggedInSupervisor->getTskId() != 0)
	)
)
{
	$dataFilter = PicoSpecification::getInstance();
	$filterProyek = PicoSpecification::getInstance();
	if($currentLoggedInSupervisor->getUmkId() != null && $currentLoggedInSupervisor->getUmkId() != 0)
	{
		$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $currentLoggedInSupervisor->getUmkId()));
		$filterProyek->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $currentLoggedInSupervisor->getUmkId()));
	}
	if($currentLoggedInSupervisor->getTskId() != null && $currentLoggedInSupervisor->getTskId() != 0)
	{
		$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $currentLoggedInSupervisor->getTskId()));
		$filterProyek->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $currentLoggedInSupervisor->getTskId()));
	}
}
else
{
	// Add impossible condition to prevent data leak
	$dataFilter = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, 0))
		;
	$filterProyek = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, 0))
		;
}

/**
 * Counts the number of elements in an array.
 *
 * @param array $arr The array whose elements are to be counted.
 * @return int Returns the number of elements in the array, or 0 if the input is not a valid array.
 */
function arrayCount($arr)
{
	if(isset($arr) && is_array($arr))
	{
		return count($arr);
	}
	return 0;
}

/**
 * Saves the permasalahan and rekomendasi data to the database.
 * 
 * This function processes the provided permasalahan and rekomendasi IDs, inserting them into the database if they do not already exist.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $permasalahanIds Array of permasalahan IDs to be saved.
 * @param int[] $rekomendasiIds Array of rekomendasi IDs to be saved.
 * @return void
 */
function savePermasalahanRekomendasi($database, $currentAction, $proyekId, $bukuHarianId, $permasalahanIds)
{
	$max = arrayCount($permasalahanIds);
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$rekomendasiPekerjaan = new RekomendasiPekerjaan(null, $database);
		$permasalahanId = isset($permasalahanIds[$i]) ? $permasalahanIds[$i] : null;
		try
		{
			$rekomendasiPekerjaan->findOneByProyekIdAndBukuHarianIdAndPermasalahanId($proyekId, $bukuHarianId, $permasalahanId);
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
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		// Clean all
		$rekomendasiPekerjaan = new RekomendasiPekerjaan(null, $database);
		$rekomendasiPekerjaan->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}

/**
 * Saves the Bill of Quantity (BOQ) data to the database.
 * 
 * This function processes the provided Bill of Quantity IDs and volumes, updating or inserting records in the database.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $boqIds Array of BOQ IDs to be saved.
 * @param float[] $jumlahBoqs Array of BOQ volumes to be saved.
 * @return void
 */
function saveBoq($database, $currentAction, $proyekId, $bukuHarianId, $boqIds, $jumlahBoqs)
{
	$max = max(arrayCount($boqIds), arrayCount($jumlahBoqs));
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
		$billOfQuantityId = isset($boqIds[$i]) ? $boqIds[$i] : null;
		$volumeProyek = isset($jumlahBoqs[$i]) ? $jumlahBoqs[$i] : null;
		try
		{
			$billOfQuantityProyek->findOneByProyekIdAndBukuHarianIdAndBillOfQuantityId($proyekId, $bukuHarianId, $billOfQuantityId);
			$ids[] = (int) $billOfQuantityProyek->getBillOfQuantityProyekId();

			$billOfQuantity = new BillOfQuantity(null, $database);
			$billOfQuantity->findOneByBillOfQuantityId($billOfQuantityId);
			$volume = $billOfQuantity->getVolume();
			$persen = $volume == 0 ? 0 : (100*$volumeProyek/$volume);
			$billOfQuantityProyek->setVolumeProyek($volumeProyek);
			$billOfQuantityProyek->setVolume($volume);
			$billOfQuantityProyek->setPersen($persen);
			$billOfQuantityProyek->setWaktuUbah($currentAction->getTime());
			$billOfQuantityProyek->setIpUbah($currentAction->getIp());
			$billOfQuantityProyek->update();
			
			$billOfQuantity->setVolumeProyek($volumeProyek);
			$billOfQuantity->update();
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

				$billOfQuantity->setVolumeProyek($volumeProyek);
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
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
		$billOfQuantityProyek->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}

/**
 * Saves the acuan pengawasan (supervision references) data to the database.
 * 
 * This function processes the provided acuan pengawasan IDs and inserts them into the database.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $acuanPengawasanIds Array of acuan pengawasan IDs to be saved.
 * @return void
 */
function saveAcuanPengawasan($database, $currentAction, $proyekId, $bukuHarianId, $acuanPengawasanIds)
{
	$max = arrayCount($acuanPengawasanIds);
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$acuanPengawasanId = $acuanPengawasanIds[$i];
		$acuanPengawasanProyek = new AcuanPengawasanProyek(null, $database);
		try
		{
			$acuanPengawasanProyek->findOneByProyekIdAndBukuHarianIdAndAcuanPengawasanId($proyekId, $bukuHarianId, $acuanPengawasanId);
			$ids[] = (int) $acuanPengawasanProyek->getAcuanPengawasanProyekId();
		}
		catch(Exception $e)
		{
			// Do nothing

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
		}
	}
	// Clean up
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		$acuanPengawasanProyek = new AcuanPengawasanProyek(null, $database);
		$acuanPengawasanProyek->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}


/**
 *
 * @param PicoDatabase $database The database connection instance.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $lokasiPekerjaanIds Array of acuan pengawasan IDs to be saved.
 * @param float $latitude
 * @param float $longitude
 * @param float $altitude
 * @return void
 */
function saveLokasiPekerjaan($database, $proyekId, $bukuHarianId, $lokasiPekerjaanIds, $latitude, $longitude, $altitude) // NOSONAR
{
	$max = arrayCount($lokasiPekerjaanIds);
	$ids = array();
	for($i = 0; $i < $max; $i++)
	{
		$lokasiProyekId = $lokasiPekerjaanIds[$i];
		$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
		try
		{
			$lokasiPekerjaan->findOneByProyekIdAndBukuHarianIdAndLokasiProyekId($proyekId, $bukuHarianId, $lokasiProyekId);
			$ids[] = (int) $lokasiPekerjaan->getLokasiProyekId();
		}
		catch(Exception $e)
		{
			// Do nothing
			echo "INSERT";
			$lokasiPekerjaan->setProyekId($proyekId);
			$lokasiPekerjaan->setBukuHarianId($bukuHarianId);
			$lokasiPekerjaan->setLokasiProyekId($lokasiProyekId);
			$lokasiPekerjaan->setLatitude($latitude);
			$lokasiPekerjaan->setLongitude($longitude);
			$lokasiPekerjaan->setAltitude($altitude);
			$lokasiPekerjaan->setAktif(true);
			$lokasiPekerjaan->insert();
		}
	}
	// Clean up
	if($max > 0)
	{
		if(!empty($ids))
		{
			$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
			$lokasiPekerjaan->where(
				PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->lokasiProyekId, $ids))
					->addAnd([Field::of()->proyekId, $proyekId])
					->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
			)
				->delete();
		}
	}
	else
	{
		$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
		$lokasiPekerjaan->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
		)
			->delete();
	}
}

/**
 * Saves the manpower data to the database.
 * 
 * This function processes the provided manpower IDs and quantities, inserting them into the database.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $manPowerIds Array of manpower IDs to be saved.
 * @param int[] $jumlahManPowers Array of manpower quantities to be saved.
 * @return void
 */
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
			$manPowerProyek->setJumlahPekerja($jumlahManPower);
			$manPowerProyek->setWaktuUbah($currentAction->getTime());
			$manPowerProyek->setIpUbah($currentAction->getIp());
			$manPowerProyek->update();
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
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		$manPowerProyek = new ManPowerProyek(null, $database);
		$manPowerProyek->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}

/**
 * Saves the equipment data to the database.
 * 
 * This function processes the provided equipment IDs and quantities, inserting them into the database.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $peralatanIds Array of equipment IDs to be saved.
 * @param float[] $jumlahPeralatans Array of equipment quantities to be saved.
 * @param string $tanggal Date when data inserted.
 * @return void
 */
function savePeralatan($database, $currentAction, $proyekId, $bukuHarianId, $peralatanIds, $jumlahPeralatans, $tanggal)
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
			$peralatanProyek->setJumlah($jumlah);
			$peralatanProyek->setWaktuUbah($currentAction->getTime());
			$peralatanProyek->setIpUbah($currentAction->getIp());
			$peralatanProyek->update();
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
			$peralatanProyek->setTanggal($tanggal);
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
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		$peralatanProyek = new PeralatanProyek(null, $database);
		$peralatanProyek->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}

/**
 * Saves the material data to the database.
 * 
 * This function processes the provided material IDs and quantities, inserting them into the database.
 *
 * @param PicoDatabase $database The database connection instance.
 * @param MagicObject $currentAction The current action object containing supervisor and time information.
 * @param int $proyekId The ID of the project.
 * @param int $bukuHarianId The ID of the Buku Harian (daily report).
 * @param int[] $materialIds Array of material IDs to be saved.
 * @param float[] $jumlahMaterials Array of material quantities to be saved.
 * @param string $tanggal Date when data inserted.
 * @return void
 */
function saveMaterial($database, $currentAction, $proyekId, $bukuHarianId, $materialIds, $jumlahMaterials, $tanggal)
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
			$materialProyek->setJumlah($jumlah);
			$materialProyek->setWaktuUbah($currentAction->getTime());
			$materialProyek->setIpUbah($currentAction->getIp());
			$materialProyek->update();

			$material = new Material(null, $database);
			try
			{
				$material->findOneByMaterialId($materialId);
				$onsite = $material->getOnsite();
				$stdClass = $materialProyek->getTotalTerpasang($materialId);
				$terpasang = $stdClass->terpasang;
				$material->setTerpasang($terpasang);
				$material->setBelumTerpasang($onsite - $terpasang);
				$material->update();
			}
			catch(Exception $e)
			{
				// Do nothing
			}
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
			$materialProyek->setTanggal($tanggal);
			$materialProyek->setAktif(true);
			$materialProyek->setWaktuBuat($currentAction->getTime());
			$materialProyek->setIpBuat($currentAction->getIp());
			$materialProyek->setWaktuUbah($currentAction->getTime());
			$materialProyek->setIpUbah($currentAction->getIp());
			$materialProyek->insert();

			$material = new Material(null, $database);
			try
			{
				$material->findOneByMaterialId($materialId);
				$onsite = $material->getOnsite();
				$stdClass = $materialProyek->getTotalTerpasang($materialId);
				$terpasang = $stdClass->terpasang;
				$material->setTerpasang($terpasang);
				$material->setBelumTerpasang($onsite - $terpasang);
				$material->update();
			}
			catch(Exception $e)
			{
				// Do nothing
			}

			$ids[] = (int) $materialProyek->getMaterialProyekId();
		}
	}

	// Clean up
	if($max > 0)
	{
		if(!empty($ids))
		{
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
	}
	else
	{
		$materialProyek = new MaterialProyek(null, $database);
		$materialProyek->where(
			PicoSpecification::getInstance()
				->addAnd([Field::of()->proyekId, $proyekId])
				->addAnd([Field::of()->bukuHarianId, $bukuHarianId])
				->addAnd([Field::of()->supervisorId, $currentAction->getSupervisorId()])
		)
			->delete();
	}
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
    $proyekId = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
	$tanggal = $inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
    
	$proyek = new Proyek(null, $database);
	$proyek->findOneByProyekId($proyekId);
	
	$bukuHarian = new BukuHarian(null, $database);

	$bukuHarian->setUmkId($proyek->getUmkId());
	$bukuHarian->setTskId($proyek->getTskId());

	$bukuHarian->setSupervisorId($currentLoggedInSupervisor->getSupervisorId());
	$bukuHarian->setProyekId($proyekId);
	$bukuHarian->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true));
	$bukuHarian->setTanggal($tanggal);
	$bukuHarian->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_DEFAULT, false, false, true));

	$latitude = $inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$longitude = $inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$altitude = $inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$bukuHarian->setLatitude($latitude);
	$bukuHarian->setLongitude($longitude);
	$bukuHarian->setAltitude($altitude);
	
	$bukuHarian->setAktif(true);
	$bukuHarian->setWaktuBuat($currentAction->getTime());
	$bukuHarian->setIpBuat($currentAction->getIp());
	$bukuHarian->setWaktuUbah($currentAction->getTime());
	$bukuHarian->setIpUbah($currentAction->getIp());

	$bukuHarian->insert();

	$bukuHarianId = $bukuHarian->getBukuHarianId();
	
	saveLokasiPekerjaan($database, $proyekId, $bukuHarianId, $inputPost->getLokasiProyekId(), $latitude, $longitude, $altitude);

	savePermasalahanRekomendasi($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPermasalahanId());
	saveBoq($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getBoqId(), $inputPost->getJumlahBoq());
	saveAcuanPengawasan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getAcuanPengawasanId());
	saveManPower($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getManPowerId(), $inputPost->getJumlahManPower());
	savePeralatan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPeralatanId(), $inputPost->getJumlahPeralatan(), $tanggal);
	saveMaterial($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getMaterialId(), $inputPost->getJumlahMaterial(), $tanggal);
	
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarianId);
	
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuHarianId, $inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true)));

	
	$bukuHarian = new BukuHarian(null, $database);
	try
	{
		$bukuHarian->findOne($specification);
		$tanggal = $bukuHarian->getTanggal();
		$bukuHarian->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true));
		$bukuHarian->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
		$bukuHarian->setWaktuUbah($currentAction->getTime());
		$bukuHarian->setIpUbah($currentAction->getIp());		
		$bukuHarian->update();
		$proyekId = $bukuHarian->getProyekId();
		$bukuHarianId = $bukuHarian->getBukuHarianId();

		$latitude = $bukuHarian->getLatitude();
		$longitude = $bukuHarian->getLongitude();
		$altitude = $bukuHarian->getAltitude();

		saveLokasiPekerjaan($database, $proyekId, $bukuHarianId, $inputPost->getLokasiProyekId(), $latitude, $longitude, $altitude);
		savePermasalahanRekomendasi($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPermasalahanId());
		saveBoq($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getBoqId(), $inputPost->getJumlahBoq());
		saveAcuanPengawasan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getAcuanPengawasanId());
		saveManPower($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getManPowerId(), $inputPost->getJumlahManPower());
		savePeralatan($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getPeralatanId(), $inputPost->getJumlahPeralatan(), $tanggal);
		saveMaterial($database, $currentAction, $proyekId, $bukuHarianId, $inputPost->getMaterialId(), $inputPost->getJumlahMaterial(), $tanggal);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarianId);
	}
	catch(Exception $e)
	{
		error_log($e->getMessage());
		$currentModule->redirectToItself();
	}
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
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<style>
	.note-hint-popover {
		position: absolute;
	}
</style>
<script type="text/javascript">
var dataCuaca = [];
var bukuHarianId = 0;
var proyekId = <?php echo $proyekId;?>;
</script>
<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian-editor.css">
<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js"></script>
<script type="text/javascript" src="lib.assets/mobile-script/buku-harian-editor.js"></script>
<script>
	var elements = [];
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
		alert(errorMessage);
	}

</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		
		<form name="createform" id="createform" action="" method="post" data-proyek-id="<?php echo $proyekId;?>">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
						<select name="proyek_id" class="form-control" required="required" onchange="window.location='?user_action=create&proyek_id='+this.value+'&tanggal=<?php echo $inputGet->getTanggal();?>'">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php 
							$proyekFinder = new Proyek(null, $database);
							$specs1 = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
								
							if($filterProyek)
							{
								$specs1->addAnd($filterProyek);
							}
							$sorts1 = PicoSortable::getInstance()
								->add(new PicoSort('proyek.proyekId', PicoSort::ORDER_TYPE_DESC))
							;
							try
							{
								$pageData1 = $proyekFinder->findAll($specs1, null, $sorts1, true);
								$rows = $pageData1->getResult();
								foreach($rows as $proyek)
								{
									?>
									<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $proyekId ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
									<?php
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
						<td><textarea spellcheck="false" name="kegiatan" id="kegiatan" class="form-control" style="height:256px"></textarea></td>
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
						<button type="button" class="btn btn-primary" id="tambah-man-power">Tambah</button>
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#man-power-modal">Atur</button>
						<span id="total-man-power"></span>
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

	<style>
		#man-power-modal[data-mode="list"] .save-man-power, #man-power-modal[data-mode="list"] .cancel-man-power, #man-power-modal[data-mode="list"]
		{
			display:none;
		}
		#man-power-modal[data-mode="edit"] .add-man-power
		{
			display:none;
		}
	</style>
	<script>
		
	</script>

	<div class="modal modal-lg fade" data-mode="list" id="man-power-modal" tabindex="-1" aria-labelledby="manPowerLabel" aria-hidden="true" data-proyek-id="<?php echo $proyekId;?>">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="manPowerLabel"><?php echo $appLanguage->getManPower();?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<form action="" method="post" class="data-form-list">
					
				</form>

				<form action="" method="post" class="data-form-edit">
					<div class="input-area">
						<div class="label">Nama Tim</div>
						<input type="text" class="form-control" name="nama">
					</div>
					<div class="input-area">
						<div class="label">Pekerjaan</div>
						<input type="text" class="form-control" name="pekerjaan">
					</div>
					<div class="input-area">
						<div class="label">Jumlah Pekerja</div>
						<input type="number" step="1" min="1" class="form-control" name="jumlah_pekerja">
						<input type="hidden" name="proyek_id">
						<input type="hidden" name="man_power_id">
					</div>
				</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success save-man-power"><?php echo $appLanguage->getButtonSave();?></button>
                <button type="button" class="btn btn-primary cancel-man-power"><?php echo $appLanguage->getButtonCancel();?></button>
                <button type="button" class="btn btn-primary add-man-power"><?php echo $appLanguage->getButtonAdd();?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $appLanguage->getButtonClose();?></button>
            </div>
        </div>
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
				$tv = $bukuHarian->get('c_'.$tt);
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
		<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
		<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap5.bundle.min.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
		<style>
			.note-hint-popover {
				position: absolute;
			}
		</style>
		<script type="text/javascript">
		var dataCuaca = [];
		var bukuHarianId = <?php echo $bukuHarian->getBukuHarianId();?>;
		</script>
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian-editor.css">
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js"></script>
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian-editor.js"></script>
		<script>
			var elements = [];
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
		<script type="text/javascript">
		var dataCuaca = <?php echo json_encode($data_cuaca);?>;
		var bukuHarianId = <?php echo $bukuHarian->getBukuHarianId();?>;
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
						<td><?php echo $bukuHarian->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>

					<tr>
					<td>Lokasi Pekerjaan</td>
					<td>
					<?php
					$lokasiProyekObj = $bukuHarian->getLokasiProyekList($bukuHarian->getBukuHarianId());
					$lokasiPekerjaanIds = $bukuHarian->getSerialOfScalar($lokasiProyekObj);
					?>
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
						Field::of()->lokasiProyekId, Field::of()->nama, $lokasiPekerjaanIds)
						; ?>
					</select>
					</td>
					</tr>

					<tr>
						<td><?php echo $appEntityLanguage->getKegiatan();?></td>
						<td><textarea spellcheck="false" name="kegiatan" id="kegiatan" class="form-control" style="height:256px"><?php echo $bukuHarian->getKegiatan();?></textarea></td>
					</tr>

					<tr>
						<td><?php echo $appEntityLanguage->getPermasalahanDanRekomendasi();?></td>
						<td>
							<table class="tabel-control-two-side">
							<tbody>
								
							<?php 
								$specificationRekomendasiPekerjaan = PicoSpecification::getInstance()
								->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
								$appEntityLanguagePermasalahan = new AppEntityLanguage(new RekomendasiPekerjaan(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
								$dataLoader = new RekomendasiPekerjaan(null, $database);
								try
								{
									$permasalahanFinder = new Permasalahan(null, $database);
									$dt1 = $permasalahanFinder->findByProyekIdAndAktifAndDitutup($bukuHarian->getProyekId(), true, false);
									$pageData = $dataLoader->findAll($specificationRekomendasiPekerjaan, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
									if($pageData->getTotalResult() > 0)
									{		
										$dataIndex = 0;
										while($rekomendasiPerekraan = $pageData->fetch())
										{
											
											$permasalahan = $rekomendasiPerekraan->issetPermasalahan() ? $rekomendasiPerekraan->getPermasalahan() : new Permasalahan();
										?>
										<tr data-value="<?php echo $rekomendasiPerekraan->getPermasalahanId();?>">
											<td>
											<select class="form-control" data-name="permasalahan_id" name="permasalahan_id[<?php echo $dataIndex;?>]" data-value="<?php echo $rekomendasiPerekraan->getPermasalahanId();?>">
												<?php
												foreach($dt1->getResult() as $permasalahan)
												{
													?>
													<option value="<?php echo $permasalahan->getPermasalahanId();?>"<?php echo $permasalahan->getPermasalahanId() == $rekomendasiPerekraan->getPermasalahanId() ? ' selected':'';?>>
														<?php echo $permasalahan->getPermasalahan();?> : <?php echo $permasalahan->getRekomendasi();?> : <?php echo $permasalahan->getTindakLanjut();?></option>
													<?php
												}
												?>
											</select>
											</td>
											<td>
												<button type="button" class="btn btn-danger remove-issue">×</button>
											</td>
										</tr>
										<?php
										$dataIndex++;
										}

									}
								}
								catch(Exception $e)
								{
									// Do nothing
								}

								?>
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
					<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
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
							Field::of()->billOfQuantityId, Field::of()->nama, $bukuHarian->getBillOfQuantityId(), [Field::of()->proyekId, Field::of()->parentId])
							; ?>
							?>
						</select>
					
					</td>
					</tr>

					<tr>
						<td></td>
						<td>
							<table class="tabel-control" id="tabel-boq" cellpadding="0" cellspacing="0" border="0" class="form-control">
								<tbody>
								<?php 
								$parentId = $bukuHarian->getBillOfQuantityId();
								$specificationBoq = PicoSpecification::getInstance()
								->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
								$appEntityLanguageBoq = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
								$dataLoader = new BillOfQuantityProyek(null, $database);
								try
								{
									$pageData = $dataLoader->findAll($specificationBoq, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
									if($pageData->getTotalResult() > 0)
									{
										$dataIndex = 0;
										while($billOfQuantityProyek = $pageData->fetch())
										{
											
											$boq = $billOfQuantityProyek->issetBillOfQuantity() ? $billOfQuantityProyek->getBillOfQuantity() : new MagicObject();
										?>
										<tr data-value="<?php echo $billOfQuantityProyek->getBillOfQuantityId();?>">
											<td>
											<select class="resource-bill-of-quantity form-control" name="boq_id[<?php echo $dataIndex;?>]" data-value="<?php echo $billOfQuantityProyek->getBillOfQuantityId();?>">
										
											<?php
											$boq = new BillOfQuantity(null, $database);
											try
											{
												$specs = PicoSpecification::getInstance()
													->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $proyekId))
													->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
												;
												$pageData = $boq->findAll($specs);
												$boqUtil = new BoqUtil($pageData->getResult(), $parentId, true);
												echo $boqUtil->selectOption($billOfQuantityProyek->getBillOfQuantityId(), true);
											}
											catch(Exception $e)
											{
												echo $e->getMessage();
												// do nothing
											}
											?>
											<td>
											<input type="number" step="any" min="<?php echo $billOfQuantityProyek->getVolumeProyek();?>" value="<?php echo $billOfQuantityProyek->getVolumeProyek();?>" class="form-control volume" required="required" max="<?php echo $boq->getVolume();?>" name="jumlah_boq[<?php echo $dataIndex;?>]">
											</td>
											<td>
											<button type="button" class="btn btn-danger remove-boq">×</button>
											</td>
											</tr>
										</select>
										
										<?php 
										$dataIndex++;
										}
									}
								}
								catch(Exception $e)
								{
									// Do nothing
								}
								
								?>

								</tbody>
							</table>
							<div class="form-control-add">
							<input type="button" value="Tambah" id="tambah-boq" class="btn btn-primary">
							</div>
						</td>
					</tr>

					<tr>
						<td>
						<?php echo $appEntityLanguage->getAcuanPengawasanProyek();?>
						</td>
						<td>
						<div class="acuan-pengawasan-container">
							<?php 
							$specificationAcuanPengawasanProyek = PicoSpecification::getInstance()
							//->addAnd([Field::of()->proyekId, $bukuHarian->getProyekId()])
							->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()])
							;
							$appEntityLanguageAcuanPengawasanProyek = new AppEntityLanguage(new AcuanPengawasanProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
							$appEntityLanguageAcuanPengawasan = new AppEntityLanguage(new AcuanPengawasan(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
							$dataLoader = new AcuanPengawasanProyek(null, $database);
							try
							{
								$pageData = $dataLoader->findAll($specificationAcuanPengawasanProyek, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
								if($pageData->getTotalResult() > 0)
								{
									$dataIndex = 0;
									while($acuanPengawasanProyek = $pageData->fetch())
									{
										
										$acuanPengawasan = $acuanPengawasanProyek->issetAcuanPengawasan() ? $acuanPengawasanProyek->getAcuanPengawasan() : new AcuanPengawasan();
										?>
										<div>
											<label><input type="checkbox" name="acuan_pengawasan_id[]" value="<?php echo $acuanPengawasan->getAcuanPengawasanId();?>"<?php echo $acuanPengawasan->getAcuanPengawasanId() == $acuanPengawasanProyek->getAcuanPengawasanId() ? ' checked':'';?>> <?php echo $acuanPengawasan->getNama();?></label>
										</div>
										
										<?php 
									$dataIndex++;
									}
									
								}
							}
							catch(Exception $e)
							{
								// Do nothing
							}
							
							?>
							</div>
						</td>
					</tr>

					<tr>
						<td>
						<?php echo $appEntityLanguage->getManOfPower();?>
						</td>
						<td>

						<table class="tabel-control" id="tabel-man-power" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody>
						
						<?php 
						
						$specificationManPower = PicoSpecification::getInstance()
							->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
						$appEntityLanguageManPowerProyek = new AppEntityLanguage(new ManPowerProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
						$dataLoader = new ManPowerProyek(null, $database);
						try
						{
							$manPowerFinder = new ManPower(null, $database);

							$dt1 = $manPowerFinder->findByProyekIdAndAktif($proyekId, true);

							$pageData = $dataLoader->findAll($specificationManPower, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
							if($pageData->getTotalResult() > 0)
							{		
								$dataIndex = 0;
								while($manPowerProyek = $pageData->fetch())
								{
									
								?>
									<tr data-value="<?php echo $manPowerProyek->getManPowerId();?>">
									<td>
									<select class="resource-man-power form-control" required="required" name="man_power_id[<?php echo $dataIndex;?>]" data-value="<?php echo $manPowerProyek->getManPowerId();?>">
										<?php
											foreach($dt1->getResult() as $manPower)
											{
												$nama = $manPower->getNama();
												if($manPower->issetPekerjaan())
												{
													$nama .= " [".$manPower->getPekerjaan()."]";
												}
												$jumlahPekerja = $manPower->getJumlahPekerja();        
												$nama .= " [".$jumlahPekerja." orang]";
												?>
												<option value="<?php echo $manPower->getManPowerId();?>"<?php echo $manPower->getManPowerId() == $manPowerProyek->getManPowerId() ? ' selected' : '';?>><?php echo $nama;?></option>
												<?php
											}
										?>
									</select>
									</td>
									<td>
									<input type="number" step="any" min="1" value="<?php echo $manPowerProyek->getJumlahPekerja();?>" class="form-control" required="required" name="jumlah_man_power[<?php echo $dataIndex;?>]">
									</td>
									<td>
									<button type="button" class="btn btn-danger remove-man-power">×</button>
									</td>
									</tr>
								<?php 
								$dataIndex++;
								}
							}
						}
						catch(Exception $e)
						{
							// Do nothing
						}
						
						?>
						</tbody>
						</table>
						<div class="form-control-add">
						<button type="button" class="btn btn-primary" id="tambah-man-power">Tambah</button>
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#man-power-modal">Atur</button>
						<span id="total-man-power"></span>
						</div>
						</td>
					</tr>

					<tr>
						<td><?php echo $appEntityLanguage->getMaterial();?></td>
						<td>

						<table class="tabel-control" id="tabel-material" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody>
							
							<?php 


							


							$specificationMaterial = PicoSpecification::getInstance()
							->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
							$appEntityLanguageMaterialProyek = new AppEntityLanguage(new MaterialProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
							$dataLoader = new MaterialProyek(null, $database);
							try
							{

								$pageData = $dataLoader->findAll($specificationMaterial, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
								if($pageData->getTotalResult() > 0)
								{		
									$dataIndex = 0;
									$resTerpasang = array();
									$maximal = array();
									$resMaterailProyek = array();
									while($materialProyek = $pageData->fetch())
									{
										$resMaterailProyek[] = $materialProyek;
										$resTerpasang[$materialProyek->getMaterialId()] = $materialProyek->getJumlah();
									}


									$specs4 = PicoSpecification::getInstance()->add(['aktif', true])->add('proyekId', $proyekId);
									$sorts4 = PicoSortable::getInstance()->add(['nama', PicoSort::ORDER_TYPE_ASC]);
									$resourse_material[] = '<option value="">'.$appLanguage->getLabelOptionSelectOne().'</option>';
									try
									{
										$material = new Material(null, $database);
										$pageData4 = $material->findAll($specs4, null, $sorts4);
		
										$categories = array();
										$uncategories = array();
		
										foreach($pageData4->getResult() as $row)
										{
											$group = $row->getKategoriMaterial()->getNama();
											if(!empty($group))
											{
												if(!isset($categories[$group]))
												{
													$categories[$group] = array();
												}
												$categories[$group][] = $row;
											}
											else
											{
												$uncategories[] = $row;
											}
										}
		
										if(!empty($categories))
										{
											foreach($categories as $group=>$rows)
											{
												$resourse_material[] = '<optgroup label="'.$group.'">';
												foreach($rows as $row)
												{
													$belumTerpasang = $row->getBelumTerpasang();
													if(isset($resTerpasang[$row->getMaterialId()]))
													{
														$belumTerpasang += $resTerpasang[$row->getMaterialId()];
													}
													$maximal[$row->getMaterialId()] = $belumTerpasang;

													$onsite = rtrim(sprintf("%f", $row->getOnsite()), ".0");
													$terpasang = rtrim(sprintf("%f", $row->getTerpasang()), ".0");
													$textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $row->getNama(), $row->getSatuan(), $onsite, $terpasang);
													$resourse_material[$row->getMaterialId()] = "\t".'<option value="'.$row->getMaterialId().'" data-max="'.$belumTerpasang.'">'.$textNode.'</option>';
												}
												$resourse_material[] = '</optgroup>';
											}
										}
										foreach($uncategories as $row)
										{
											$belumTerpasang = $row->getBelumTerpasang();
											if(isset($resTerpasang[$row->getMaterialId()]))
											{
												$belumTerpasang += $resTerpasang[$row->getMaterialId()];
											}
											$maximal[$row->getMaterialId()] = $belumTerpasang;

											$onsite = rtrim(sprintf("%f", $row->getOnsite()), ".0");
											$terpasang = rtrim(sprintf("%f", $row->getTerpasang()), ".0");
											$textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $row->getNama(), $row->getSatuan(), $onsite, $terpasang);
											$resourse_material[$row->getMaterialId()] = '<option value="'.$row->getMaterialId().'" data-max="'.$belumTerpasang.'">'.$textNode.'</option>';
										}
										
									}
									catch(Exception $e)
									{
										// do nothing
									}



									
									foreach($resMaterailProyek as $idx=>$materialProyek)
									{
										?>
										<tr data-value="<?php echo $materialProyek->getMaterialId();?>">
										<td>
							
										<select class="resource-material form-control" required="required" name="material_id[<?php echo $dataIndex;?>]" data-value="<?php echo $materialProyek->getMaterialId();?>">
										<?php
											foreach($resourse_material as $key=>$value)
											{
												if($materialProyek->getMaterialId() == $key)
												{
													$value = str_replace(" value", ' selected="selected" value', $value);
												}
												echo $value."\r\n";
											}

											$max = isset($maximal[$materialProyek->getMaterialId()]) ? $maximal[$materialProyek->getMaterialId()] : $materialProyek->getJumlah();
										?>
									</select>
									</td>
									<td>
									<input type="number" step="any" min="0" value="<?php echo $materialProyek->getJumlah();?>" max="<?php echo $max;?>" class="form-control" required="required" name="jumlah_material[<?php echo $idx;?>]">
									</td>
									<td>
									<button type="button" class="btn btn-danger remove-material">×</button>
									</td>
									</tr>
									<?php
										$dataIndex++;
									
									}
								}
							}
							catch(Exception $e)
							{
								// Do nothing
							}
							?>
							
							</tbody>
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-material" class="btn btn-primary">
						</div>

						
						</td>
					</tr>

					<tr>
						<td><?php echo $appEntityLanguage->getPeralatan();?></td>
						<td>
						<table class="tabel-control" id="tabel-peralatan" cellpadding="0" cellspacing="0" border="0" class="form-control">
							<tbody>
							<?php 
							$specificationPeralatan = PicoSpecification::getInstance()
							->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
							$appEntityLanguagePeralatanProyek = new AppEntityLanguage(new PeralatanProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
							$dataLoader = new PeralatanProyek(null, $database);
							try{
								$peralatanFinder = new Peralatan(null, $database);
								$dt1 = $peralatanFinder->findByAktif(true);
								$pageData = $dataLoader->findAll($specificationPeralatan, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
								if($pageData->getTotalResult() > 0)
								{		
									$dataIndex = 0;
									while($peralatanProyek = $pageData->fetch())
									{
										?>
										<tr data-value="<?php echo $peralatanProyek->getPeralatanId();?>">
											<td>
												
												<select class="resource-peralatan form-control" required="required" name="peralatan_id[<?php echo $dataIndex;?>]" data-value="<?php echo $peralatanProyek->getPeralatanId();?>">
												<?php
													foreach($dt1->getResult() as $peralatan)
													{
														?>
														<option value="<?php echo $peralatan->getPeralatanId();?>"<?php echo $peralatan->getPeralatanId() == $peralatanProyek->getPeralatanId() ? ' selected' : '';?>><?php echo $peralatan->getNama();?> [<?php echo $peralatan->getSatuan();?>]</option>
														<?php
													}
												?>
											</select>
											</td>
											<td>
											<input type="number" step="any" min="0" value="1" class="form-control" required="required" name="jumlah_peralatan[<?php echo $dataIndex;?>]">
											</td>
											<td>
											<button type="button" class="btn btn-danger remove-peralatan">×</button>
											</td>
											</tr>
									<?php
										$dataIndex++;
									
									}
									
								}
							}
							catch(Exception $e)
							{
								// Do nothing
							}
							
							?>
							</tbody>
						</table>
						<div class="form-control-add">
						<input type="button" value="Tambah" id="tambah-peralatan" class="btn btn-primary">
						</div>
						</td>
					</tr>
					<tr>
						<td><?php echo $appLanguage->getLaporanCuaca();?></td>
						<td>
						<?php
							include "inc.app/dom-buku-harian.php";
						?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
						<button type="submit" value="update" name="user_action" class="btn btn-success"><?php echo $appLanguage->getButtonUpdate();?></button>
						<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
						<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
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

	<style>
		#man-power-modal[data-mode="list"] .save-man-power, #man-power-modal[data-mode="list"] .cancel-man-power, #man-power-modal[data-mode="list"]
		{
			display:none;
		}
		#man-power-modal[data-mode="edit"] .add-man-power
		{
			display:none;
		}
	</style>
	<script>
		
	</script>

	<div class="modal modal-lg fade" data-mode="list" id="man-power-modal" tabindex="-1" aria-labelledby="manPowerLabel" aria-hidden="true" data-proyek-id="<?php echo $proyekId;?>">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="manPowerLabel"><?php echo $appLanguage->getManPower();?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				
				<form action="" method="post" class="data-form-list">
					
				</form>

				<form action="" method="post" class="data-form-edit">
					<div class="input-area">
						<div class="label">Nama Tim</div>
						<input type="text" class="form-control" name="nama">
					</div>
					<div class="input-area">
						<div class="label">Pekerjaan</div>
						<input type="text" class="form-control" name="pekerjaan">
					</div>
					<div class="input-area">
						<div class="label">Jumlah Pekerja</div>
						<input type="number" step="1" min="1" class="form-control" name="jumlah_pekerja">
						<input type="hidden" name="proyek_id">
						<input type="hidden" name="man_power_id">
					</div>
				</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success save-man-power"><?php echo $appLanguage->getButtonSave();?></button>
                <button type="button" class="btn btn-primary cancel-man-power"><?php echo $appLanguage->getButtonCancel();?></button>
                <button type="button" class="btn btn-primary add-man-power"><?php echo $appLanguage->getButtonAdd();?></button>
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
				$tv = $bukuHarian->get('c_'.$tt);
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
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap5.bundle.min.js"></script>
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js"></script>
		<script type="text/javascript" src="lib.assets/mobile-script/upload-foto.js"></script>
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
		<script type="text/javascript">
		var dataCuaca = <?php echo json_encode($data_cuaca);?>;
		bukuHarianId = <?php echo $bukuHarian->getBukuHarianId();?>;
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
						<td><?php echo $bukuHarian->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
				</tbody>
			</table>

			<h4>Lokasi Pekerjaan</h4>
			<?php 
			$specificationLokasiPekerjaan = PicoSpecification::getInstance()
			->addAnd([Field::of()->bukuHarianId, $bukuHarian->getBukuHarianId()]);
			$appEntityLanguageLokasiPekerjaan = new AppEntityLanguage(new LokasiPekerjaan(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
			$dataLoader = new LokasiPekerjaan(null, $database);
			try{
			$pageData = $dataLoader->findAll($specificationLokasiPekerjaan, null, null, true, null, MagicObject::FIND_OPTION_NO_FETCH_DATA);
			if($pageData->getTotalResult() > 0)
			{		
				
			?>
			<table class="table">
				<thead>
					<tr>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageLokasiPekerjaan->getLokasi();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($lokasiPekerjaan = $pageData->fetch())
					{
						$dataIndex++;
						$lokasiProyek = $lokasiPekerjaan->issetLokasiProyek() ? $lokasiPekerjaan->getLokasiProyek() : new LokasiProyek();
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
						<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
						<td><?php echo $lokasiProyek->getNama();?></td>
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
						<td><?php echo $appEntityLanguagePermasalahan->getTindakLanjut();?></td>
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
						<td data-col-name="volume"><?php echo $permasalahan->getTindakLanjut();?></td>
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

			<div class="modal modal-lg fade" data-mode="list" id="galeri-proyek-modal" tabindex="-1" aria-labelledby="galeriProyekLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="galeriProyekLabel"><?php echo $appLanguage->getGaleriProyek();?></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $appLanguage->getButtonClose();?></button>
					</div>
				</div>
				</div>
			</div>

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
						<td class="data-controll data-number"><span class="fa fa-upload"></span></td>
						<td class="data-controll data-number"><span class="fa fa-image"></span></td>
						<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
						<td><?php echo $appEntityLanguageBoq->getBillOfQuantity();?></td>
						<td><?php echo $appEntityLanguageBoq->getVolume();?></td>
						<td><?php echo $appEntityLanguageBoq->getTercapai();?></td>
						<td width="15%"><?php echo $appEntityLanguageBoq->getPersen();?></td>
					</tr>
				</thead>
			
				<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
					<?php 
					$dataIndex = 0;
					while($billOfQuantityProyek = $pageData->fetch())
					{
						$dataIndex++;
						$boq = $billOfQuantityProyek->issetBillOfQuantity() ? $billOfQuantityProyek->getBillOfQuantity() : new MagicObject();
					?>

					<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" 
						data-proyek-id="<?php echo $billOfQuantityProyek->getProyekId();?>"
						data-buku-harian-id="<?php echo $billOfQuantityProyek->getBukuHarianId();?>"
						data-boq-proyek-id="<?php echo $billOfQuantityProyek->getBillOfQuantityProyekId();?>"
						data-boq-id="<?php echo $boq->getBillOfQuantityId();?>"
						>
						<td class="data-controll data-number"><a href="javascript:" class="upload-image-boq"><span class="fa fa-upload"></span></a></td>
						<td class="data-controll data-number"><a href="javascript:" class="show-image-boq" data-bs-toggle="modal" data-bs-target="#galeri-proyek-modal" data-proyek-id="<?php echo $billOfQuantityProyek->getProyekId();?>" data-buku-harian-id="<?php echo $billOfQuantityProyek->getBukuHarianId();?>" data-bill-of-quantity-id="<?php echo $boq->getBillOfQuantityId();?>"><span class="fa fa-image"></span></a></td>
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
						<td width="15%"><?php echo $appEntityLanguageManPowerProyek->getJumlah();?></td>
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
						<td width="15%"><?php echo $appEntityLanguageMaterialProyek->getJumlah();?></td>
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
						<td width="15%"><?php echo $appEntityLanguagePeralatanProyek->getJumlah();?></td>
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
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
				<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
			</div>

		</form>
	</div>
</div>
<style>
	#toast-container{
		position:fixed;
		right:0px;
		bottom:0px;
	}
</style>
<div id="toast-container" aria-live="polite" aria-atomic="true"></div>
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
				->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals('tanggal', $startDate))
				->addAnd(PicoPredicate::getInstance()->lessThan('tanggal', $endDate))
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
					->setDataKoordinator('')
					->setDataKtsk('')
					->setClass($class)
					;
			}
			try
			{
				$pageData = $bukuHarianFinder->findAll($specs);
				foreach($pageData->getResult() as $bukuHarian)
				{
					$tanggal = $bukuHarian->getTanggal();

					$apvKoordinator = strtolower($bukuHarian->getStatusAccKoordinator());
					$apvKtsk = strtolower($bukuHarian->getStatusAccKtsk());

					if(empty($apvKoordinator))
					{
						$apvKoordinator = 'do-nothing';
					}
					if(empty($apvKtsk))
					{
						$apvKtsk = 'do-nothing';
					}

					$buhar[$tanggal] = (new SetterGetter())
						->setBukuHarianId($bukuHarian->getBukuHarianId())
						->setTanggal($tanggal)
						->setDataKoordinator($apvKoordinator)
						->setDataKtsk($apvKtsk)
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


				/* 1 */
				button[data-koordinator="do-nothing"][data-ktsk="do-nothing"]{
					background-color: yellow;
				}
				/* 2 */
				button[data-koordinator="checked"][data-ktsk="do-nothing"]{
					background-color: #ff7800;
					color: #FFFFFF;
				}
				/* 3 */
				button[data-koordinator="rejected"][data-ktsk="do-nothing"]{
					background-color: red;
					color: #FFFFFF;
				}
				/* 4 */
				button[data-koordinator="do-nothing"][data-ktsk="approved"]{
					background-color: green;
					color: #FFFFFF;
				}
				/* 5 */
				button[data-koordinator="checked"][data-ktsk="approved"]{
					background-color: green;
					color: #FFFFFF;
				}
				/* 6 */
				button[data-koordinator="rejected"][data-ktsk="approved"]{
					background-color: red;
					color: #FFFFFF;
				}
				/* 7 */
				button[data-koordinator="do-nothing"][data-ktsk="rejected"]{
					background-color: red;
					color: #FFFFFF;
				}
				/* 8 */
				button[data-koordinator="checked"][data-ktsk="rejected"]{
					background-color: red;
					color: #FFFFFF;
				}
				/* 9 */
				button[data-koordinator="rejected"][data-ktsk="rejected"]{
					background-color: red;
					color: #FFFFFF;
				}

			</style>
			<div class="calendar">
				
				<div class="filter-section">
					<form action="" method="get" class="filter-form">
						<span class="filter-group" style="width: 100%; display: block;">
							
						<?php

						$proyekFinder = new Proyek(null, $database);
						$specs1 = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
								
						if($filterProyek)
						{
							$specs1->addAnd($filterProyek);
						}
						$sorts1 = PicoSortable::getInstance()
							->add(new PicoSort('proyek.proyekId', PicoSort::ORDER_TYPE_DESC))
						;
						
						?>
							<select name="proyek_id" class="form-control select-project" style="max-width:100%">
								<option value=""><?php echo $appLanguage->getSelectProject();?></option>
								<?php 
								
								try
								{
									$pageData1 = $proyekFinder->findAll($specs1, null, $sorts1, true);
									$rows = $pageData1->getResult();
									foreach($rows as $proyek)
									{
										?>
										<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $inputGet->getProyekId() ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
										<?php
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
										$dataKoordinator = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getDataKoordinator() : "";
										$dataKtsk = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getDataKtsk() : "";
										$class = $class.' '.$class2;
									?>
									<button 
									data-proyek-id="<?php echo $proyekId;?>" 
									data-tanggal="<?php echo $tanggal;?>" 
									data-buku-harian-id="<?php echo $bukuHarianId;?>" 
									data-koordinator="<?php echo $dataKoordinator;?>"
									data-ktsk="<?php echo $dataKtsk;?>"
									class="calendar-button buku-harian-button <?php echo $class;?>"
									><?php echo $col['day'];?></button>
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

