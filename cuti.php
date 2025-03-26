<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\MagicObject;
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
use Sipro\Entity\Data\Cuti;
use Sipro\Entity\Data\TskMin;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\JenisCutiMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;
use Sipro\Entity\Data\CutiSupervisor;
use Sipro\Entity\Data\KuotaCuti;
use Sipro\Entity\Data\KuotaCutiMin;
use Sipro\Util\CutiUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, null, "/", "cuti", $appLanguage->getCuti());


$dataFilter = PicoSpecification::getInstance()
->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $currentLoggedInSupervisor->getSupervisorId()));

$nextYearThreshold = 12;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	// Mulai transaksi database
	$database->startTransaction();

	// Ambil ID supervisor yang sedang login
	$supervisorId = $currentLoggedInSupervisor->getSupervisorId();
	
	// Ambil tanggal yang dipilih dengan filter karakter khusus
	$selectedDate = $inputPost->getSelectedDate(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS);

	// Ambil jenis cuti pengawas dari konfigurasi aplikasi
	$jenisCutiId = $appConfig->getJenisCutiPengawas();

	// Ambil alasan cuti dengan filter karakter khusus
	$alasan = $inputPost->getAlasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

	$detil = array();
	
	// Pastikan tanggal yang dipilih ada dan berbentuk array
	if(isset($selectedDate) && is_array($selectedDate) && !empty($selectedDate))
	{
		sort($selectedDate); // Urutkan tanggal cuti

		$cuti = new Cuti(null, $database);
		
		// Set detail cuti
		$cuti->setTskId($currentLoggedInSupervisor->getTskId());
		$cuti->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$cuti->setSupervisorId($supervisorId);
		$cuti->setJenisCutiId($jenisCutiId);
		$cuti->setDibayar(true);
		$cuti->setCutiDari($selectedDate[0]); // Tanggal awal cuti
		$cuti->setCutiHingga(end($selectedDate)); // Tanggal akhir cuti
		$cuti->setHariCuti(count($selectedDate)); // Jumlah hari cuti
		$cuti->setAlasan($alasan);
		$cuti->setAktif(true);

		// Set data pembuatan
		$cuti->setAdminBuat($currentAction->getUserId());
		$cuti->setWaktuBuat($currentAction->getTime());
		$cuti->setIpBuat($currentAction->getIp());
		$cuti->setAdminUbah($currentAction->getUserId());
		$cuti->setWaktuUbah($currentAction->getTime());
		$cuti->setIpUbah($currentAction->getIp());
		try
		{
			// Masukkan cuti ke database
			$cuti->insert();
			$newId = $cuti->getCutiId();

			// Ambil kuota cuti supervisor berdasarkan tahun
			$specsKuotaCuti = CutiUtil::getKuotaCutiSoecification($supervisorId, $nextYearThreshold);
			$kuotaCuti = new KuotaCuti(null, $database);
			$kuotaCutiTahun = array();
			try
			{
				$pageData = $kuotaCuti->findAll($specsKuotaCuti);
				foreach($pageData->getResult() as $kuota)
				{
					$kuotaCutiTahun[$kuota->getTahun()] = $kuota->getSisa();
				}
			}
			catch(Exception $e)
			{
				// Jika gagal mengambil kuota, biarkan kosong
			}

			// Proses setiap tanggal cuti
			foreach($selectedDate as $tanggal)
			{
				$arr = explode('-', $tanggal); // Ambil tahun dari tanggal
				$tahun = $arr[0];
				if(!isset($kuotaCutiTahun[$tahun]))
				{
					$kuotaCutiTahun[$tahun] = 0;
				}
				if($kuotaCutiTahun[$tahun] > 0)
				{
					$cutiSupervisor = new CutiSupervisor(null, $database);
					$cutiSupervisor->setTahun($tahun);
					$cutiSupervisor->setTanggal($tanggal);
					$cutiSupervisor->setTskId($currentLoggedInSupervisor->getTskId());
					$cutiSupervisor->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
					$cutiSupervisor->setSupervisorId($supervisorId);
					$cutiSupervisor->setJenisCutiId($jenisCutiId);
					$cutiSupervisor->setCutiId($newId);
					$cutiSupervisor->setKeterangan($alasan);
					$cutiSupervisor->setStatusCuti('P'); // Status pengajuan cuti (Pending)
					$cutiSupervisor->setAktif(true);

					// Set informasi pembuatan
					$cutiSupervisor->setAdminBuat($currentAction->getUserId());
					$cutiSupervisor->setWaktuBuat($currentAction->getTime());
					$cutiSupervisor->setIpBuat($currentAction->getIp());
					$cutiSupervisor->setAdminUbah($currentAction->getUserId());
					$cutiSupervisor->setWaktuUbah($currentAction->getTime());
					$cutiSupervisor->setIpUbah($currentAction->getIp());
					$cutiSupervisor->insert();

					$kuotaCutiTahun[$tahun]--; // Kurangi kuota cuti

					$detil[] = $tanggal;
				}
			}

			// Perbarui sisa kuota cuti
			foreach($kuotaCutiTahun as $tahun=>$sisa)
			{
				$kuotaCuti = new KuotaCuti(null, $database);
				$kuotaCuti->findOneBySupervisorIdAndTahun($supervisorId, $tahun);
				try
				{
					$diambil = $kuotaCuti->getKuota() - $sisa;
					$kuotaCuti->setSisa($sisa)->setDiambil($diambil)->update();
				}
				catch(Exception $e)
				{
					 // Jika gagal memperbarui, abaikan
				}
			}

			$cuti->setDetilTanggalCuti(implode(", ", $detil));
			$cuti->update();

			$database->commit();
			$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_id, $newId);
		}
		catch(Exception $e)
		{
			$database->rollback();
			$currentModule->redirectToItself(); // Jika gagal, tetap di halaman yang sama	
		}
	}
}

else if($inputPost->getUserAction() == UserAction::DELETE)
{
	if($inputPost->countableCheckedRowId())
	{
		// Mulai transaksi database
		$database->startTransaction();
		$kuotaTahun = array();
		$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			try
			{
				// Inisialisasi objek yang diperlukan untuk menghapus cuti dan memperbarui kuota
				$cuti = new Cuti(null, $database);
				$kuotaCuti = new KuotaCuti(null, $database);

				// Menentukan spesifikasi cuti supervisor yang akan dihapus
				$specsCutiSupervisor = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiId, $rowId))
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->statusCuti, 'P'))
					->addAnd($dataFilter)
					;
				
				$cutiSupervisor = new CutiSupervisor(null, $database);

				// Menghitung jumlah cuti yang akan dihapus berdasarkan tahun
				try
				{
					$pageData = $cutiSupervisor->findAll($specsCutiSupervisor);
					foreach($pageData->getResult() as $cs)
					{
						if(!isset($kuotaTahun[$cs->getTahun()]))
						{
							$kuotaTahun[$cs->getTahun()] = 0;
						}
						$kuotaTahun[$cs->getTahun()]++;
					}
				}
				catch(Exception $e)
				{
					// Tangani pengecualian jika terjadi error saat menghitung cuti supervisor
					// Untuk saat ini tidak melakukan apa-apa
				}


				;
				$specificationCuti = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiId, $rowId))
					->addAnd($dataFilter)
				;

				// Menghapus cuti supervisor terkait
				$cutiSupervisor
					->where($specsCutiSupervisor)
					->delete();
				
				
				// Menghapus data cuti dari database
				$cuti
					->where($specificationCuti)
					->delete();

				// Memperbarui kuota cuti setelah penghapusan
				try
				{
					foreach($kuotaTahun as $tahun=>$dibebaskan)
					{
						$specificationKuotaCutiUpdate = PicoSpecification::getInstance()
						->addAnd([Field::of()->supervisorId, $supervisorId])
						->addAnd([Field::of()->tahun, $tahun])
						;
						$kuotaCuti = new KuotaCutiMin(null, $database);
						try
						{
							// Mencari kuota cuti berdasarkan tahun yang sesuai
							$kuotaCuti->findOne($specificationKuotaCutiUpdate);

							// Menambah kembali sisa kuota cuti yang telah dihapus
							$kuotaCuti->setSisa($kuotaCuti->getSisa() + $dibebaskan);
							$kuotaCuti->setDiambil($kuotaCuti->getDiambil() - $dibebaskan);

							$kuotaCuti->update();
						}
						catch(Exception $e)
						{
							// Tangani pengecualian jika terjadi error saat memperbarui kuota cuti
							// Untuk saat ini tidak melakukan apa-apa
						}
					}
				}
				catch(Exception $e)
				{
					// Tangani pengecualian jika terjadi error saat memperbarui kuota cuti
					// Untuk saat ini tidak melakukan apa-apa
				}
			}
			catch(Exception $e)
			{
				// Menangkap error jika terjadi kesalahan selama proses penghapusan
				
				error_log($e->getMessage());
			}
		}
	}
	$database->commit();
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/calendar.css">
<script>
    function selectDate(element)
    {
		let date = element.getAttribute('data-date');
		let year = date.split('-')[0]; // As string
		let quote = 0;
		let ok = true;
		let taken = 0;
		if(!element.classList.contains('date-selected'))
		{
			let selectedOnYear = document.querySelectorAll('.selected-date input[value^="'+year+'"]');

			if(selectedOnYear != null)
			{
				taken = selectedOnYear.length;
			}
		}

		let y = document.querySelector('[name="kuota_tahun_'+year+'"]');
		if(y != null)
		{
			quote = parseInt(y.value);
		}
		if(quote < (taken+1))
		{
			ok = false;
		}
		
		if(ok)
		{
			element.classList.toggle('date-selected');
			if(element.classList.contains('date-selected'))
			{
				let inputDate = document.createElement('input');
				inputDate.setAttribute('type', 'hidden');
				inputDate.setAttribute('name', 'selected_date[]');
				inputDate.setAttribute('value', date);
				document.querySelector('.selected-date').append(inputDate);
			}
			else
			{
				let inputDate = document.querySelector('.selected-date input[value="'+date+'"]');
				if(inputDate != null)
				{
					inputDate.parentNode.removeChild(inputDate);
				}
			}
		}

		
    }
    
    function prevDate(e)
    {
        let prevStartDate = e.target.getAttribute('data-date');
        let url = createUrl(prevStartDate, getSelectedDate());
        $('.calendar-area').load(url, function(){
			initCalendar()
		});
    }
    function nextDate(e)
    {
        let nextStartDate = e.target.getAttribute('data-date');
        let url = createUrl(nextStartDate, getSelectedDate());
        $('.calendar-area').load(url, function(){
			initCalendar()
		});
    }
    function getSelectedDate()
    {
        let selected = [];
        let container = document.querySelectorAll('#calendar .date-button.date-selected');
        if(container != null)
        {
            container.forEach((date) => {
                selected.push(date.getAttribute('data-date'));
            })
            
        }
        return selected;
    }
    function createUrl(startDate, selectedDate)
    {
        let selected = [];
        if(selectedDate != null && typeof selectedDate == 'object' && selectedDate.length > 0)
        {
            selectedDate.forEach((date)=>{
                selected.push(`&selected_date[]=${date}`);
            });
        } 
        return `lib.mobile-tools/ajax-calendar.php?start_date=${startDate}${selected.join('')}`;
    }
	function initCalendar()
	{
		document.querySelector('#prev-date').addEventListener('click', function(e){
			e.preventDefault();
			prevDate(e);
		});
		document.querySelector('#next-date').addEventListener('click', function(e){
			e.preventDefault();
			nextDate(e);
		});
	}
	jQuery(function(){
		initCalendar();
	});
</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<div class="form-with-calendar">
				<div class="form-label"><?php echo $appLanguage->getQuotaCuti();?></div>
				<div class="form-control-container">
					<?php
					$tahunSekarang = intval(date('Y'));
					$tahunMendatang = $tahunSekarang + 1;
					// Buat kuota cuti tahun sekarang
					CutiUtil::addIfNotExists($database, $currentLoggedInSupervisor->getSupervisorId(), $tahunSekarang, $appConfig->getKuotaCutiPengawas());
					// Buat kuota cuti tahun mendatang
					CutiUtil::addIfNotExists($database, $currentLoggedInSupervisor->getSupervisorId(), $tahunMendatang, $appConfig->getKuotaCutiPengawas());
					$specsKuotaCuti = CutiUtil::getKuotaCutiSoecification($supervisorId, $nextYearThreshold);
					$sorts = PicoSortable::getInstance()->addSortable(new PicoSort(Field::of()->tahun, PicoSort::ORDER_TYPE_ASC));
					$kuotaCuti = new KuotaCuti(null, $database);
					try
					{
						$pageData = $kuotaCuti->findAll($specsKuotaCuti, null, $sorts);
						foreach($pageData->getResult() as $kuota)
						{
							?>
							<div>
								<?php echo $appLanguage->getTahun();?> <?php echo $kuota->getTahun();?>: <?php echo $kuota->getSisa();?>
								<input type="hidden" name="kuota_tahun_<?php echo $kuota->getTahun();?>" value="<?php echo $kuota->getSisa();?>">
							</div>
							<?php
						}
					}
					catch(Exception $e)
					{
						// Do nothing
					}
					?>
				</div>
				<div class="form-label">
					<select class="form-control" name="proyek_id" id="proyek_id" required>
						<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
						<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
						PicoSpecification::getInstance()
							->addAnd(new PicoPredicate(Field::of()->aktif, true))
							->addAnd(new PicoPredicate(Field::of()->draft, true))
							->addAnd($dataFilter), 
						PicoSortable::getInstance()
							->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
						Field::of()->proyekId, Field::of()->nama)
						; ?>
					</select>
				</div>
				<div class="form-label"><?php echo $appLanguage->getPilihTanggalCuti();?></div>
				<div class="calendar-area">
					<?php
					require_once __DIR__ . "/lib.mobile-tools/ajax-calendar.php";
					?>
				</div>
				<div class="form-label"><?php echo $appLanguage->getTulisAlasanCuti();?></div>
				<div class="form-control-container">
					<textarea class="form-control" name="alasan" spellscheck="false"></textarea>
				</div>
				<div class="button-area">
					<button type="submit" class="btn btn-success" name="user_action" value="create"><?php echo $appLanguage->getButtonSave();?></button>
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>							
				</div>
			</div>
		</form>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->cutiId, $inputGet->getCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$cuti = new Cuti(null, $database);
	try{
		$subqueryMap = array(
		"tskId" => array(
			"columnName" => "tsk_id",
			"entityName" => "TskMin",
			"tableName" => "tsk",
			"primaryKey" => "tsk_id",
			"objectName" => "tsk",
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
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		), 
		"jenisCutiId" => array(
			"columnName" => "jenis_cuti_id",
			"entityName" => "JenisCutiMin",
			"tableName" => "jenis_cuti",
			"primaryKey" => "jenis_cuti_id",
			"objectName" => "jenis_cuti",
			"propertyName" => "nama"
		)
		);
		$cuti->findOne($specification, null, $subqueryMap);
		if($cuti->issetCutiId())
		{
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// Define map here
			$mapForStatusCuti = array(
				"P" => array("value" => "P", "label" => "Menunggu Persetujuan", "group" => "", "selected" => false),
				"A" => array("value" => "A", "label" => "Disetujui", "group" => "", "selected" => false),
				"R" => array("value" => "R", "label" => "Ditolak", "group" => "", "selected" => false),
				"C" => array("value" => "C", "label" => "Dibatalkan", "group" => "", "selected" => false)
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($cuti->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $cuti->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td><?php echo $cuti->issetTsk() ? $cuti->getTsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $cuti->issetProyek() ? $cuti->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $cuti->issetSupervisor() ? $cuti->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td><?php echo $cuti->issetJenisCuti() ? $cuti->getJenisCuti()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td><?php echo $cuti->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiDari();?></td>
						<td><?php echo $cuti->getCutiDari();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiHingga();?></td>
						<td><?php echo $cuti->getCutiHingga();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHariCuti();?></td>
						<td><?php echo $cuti->getHariCuti();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDetilTanggalCuti();?></td>
						<td><?php echo $cuti->getDetilTanggalCuti();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlasan();?></td>
						<td><?php echo $cuti->getAlasan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorPengganti();?></td>
						<td><?php echo $cuti->getSupervisorPengganti();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatusCuti();?></td>
						<td><?php echo isset($mapForStatusCuti) && isset($mapForStatusCuti[$cuti->getStatusCuti()]) && isset($mapForStatusCuti[$cuti->getStatusCuti()]["label"]) ? $mapForStatusCuti[$cuti->getStatusCuti()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $cuti->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $cuti->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuApprove();?></td>
						<td><?php echo $cuti->getWaktuApprove();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $cuti->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $cuti->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpApprove();?></td>
						<td><?php echo $cuti->getIpApprove();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $cuti->getAdminBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $cuti->getAdminUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminApprove();?></td>
						<td><?php echo $cuti->getAdminApprove();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $cuti->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->cuti_id, $cuti->getCutiId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="cuti_id" value="<?php echo $cuti->getCutiId();?>"/>
						</td>
					</tr>
				</tbody>
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
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
$mapForStatusCuti = array(
	"P" => array("value" => "P", "label" => "Menunggu Persetujuan", "group" => "", "selected" => false),
	"A" => array("value" => "A", "label" => "Disetujui", "group" => "", "selected" => false),
	"R" => array("value" => "R", "label" => "Ditolak", "group" => "", "selected" => false),
	"C" => array("value" => "C", "label" => "Dibatalkan", "group" => "", "selected" => false)
);
$specMap = array(
	"tskId" => PicoSpecification::filter("tskId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"jenisCutiId" => PicoSpecification::filter("jenisCutiId", "number"),
	"statusCuti" => PicoSpecification::filter("statusCuti", "fulltext")
);
$sortOrderMap = array(
	"tskId" => "tskId",
	"proyekId" => "proyekId",
	"supervisorId" => "supervisorId",
	"jenisCutiId" => "jenisCutiId",
	"cutiDari" => "cutiDari",
	"cutiHingga" => "cutiHingga",
	"hariCuti" => "hariCuti",
	"statusCuti" => "statusCuti",
	"waktuApprove" => "waktuApprove",
	"ipApprove" => "ipApprove",
	"adminApprove" => "adminApprove",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "cuti_id", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	),
	array(
		"sortBy" => "supervisorId", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new Cuti(null, $database);

$subqueryMap = array(
"tskId" => array(
	"columnName" => "tsk_id",
	"entityName" => "TskMin",
	"tableName" => "tsk",
	"primaryKey" => "tsk_id",
	"objectName" => "tsk",
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
"supervisorId" => array(
	"columnName" => "supervisor_id",
	"entityName" => "SupervisorMin",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "supervisor",
	"propertyName" => "nama"
), 
"jenisCutiId" => array(
	"columnName" => "jenis_cuti_id",
	"entityName" => "JenisCutiMin",
	"tableName" => "jenis_cuti",
	"primaryKey" => "jenis_cuti_id",
	"objectName" => "jenis_cuti",
	"propertyName" => "nama"
)
);

if($inputGet->getUserAction() == UserAction::EXPORT)
{
	$exporter = DocumentWriter::getXLSXDocumentWriter();
	$fileName = $currentModule->getModuleName()."-".date("Y-m-d-H-i-s").".xlsx";
	$sheetName = "Sheet 1";

	$headerFormat = new XLSXDataFormat($dataLoader, 3);
	$pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_COUNT_DATA | MagicObject::FIND_OPTION_NO_FETCH_DATA);
	$exporter->write($pageData, $fileName, $sheetName, array(
		$appLanguage->getNumero() => $headerFormat->asNumber(),
		$appEntityLanguage->getCutiId() => $headerFormat->getCutiId(),
		$appEntityLanguage->getTsk() => $headerFormat->asString(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getJenisCuti() => $headerFormat->asString(),
		$appEntityLanguage->getDibayar() => $headerFormat->asString(),
		$appEntityLanguage->getCutiDari() => $headerFormat->getCutiDari(),
		$appEntityLanguage->getCutiHingga() => $headerFormat->getCutiHingga(),
		$appEntityLanguage->getHariCuti() => $headerFormat->getHariCuti(),
		$appEntityLanguage->getAlasan() => $headerFormat->getAlasan(),
		$appEntityLanguage->getSupervisorPengganti() => $headerFormat->getSupervisorPengganti(),
		$appEntityLanguage->getStatusCuti() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getWaktuApprove() => $headerFormat->getWaktuApprove(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getIpApprove() => $headerFormat->getIpApprove(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->getAdminBuat(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->getAdminUbah(),
		$appEntityLanguage->getAdminApprove() => $headerFormat->getAdminApprove(),
		$appEntityLanguage->getAktif() => $headerFormat->getAktif()
	), 
	function($index, $row) use ($appLanguage, $mapForStatusCuti) {
		return array(
			sprintf("%d", $index + 1),
			$row->getCutiId(),
			$row->issetTsk() ? $row->getTsk()->getNama() : "",
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->issetJenisCuti() ? $row->getJenisCuti()->getNama() : "",
			$row->optionDibayar($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getCutiDari(),
			$row->getCutiHingga(),
			$row->getHariCuti(),
			$row->getAlasan(),
			$row->getSupervisorPengganti(),
			isset($mapForStatusCuti) && isset($mapForStatusCuti[$row->getStatusCuti()]) && isset($mapForStatusCuti[$row->getStatusCuti()]["label"]) ? $mapForStatusCuti[$row->getStatusCuti()]["label"] : "",
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getWaktuApprove(),
			$row->getIpBuat(),
			$row->getIpUbah(),
			$row->getIpApprove(),
			$row->getAdminBuat(),
			$row->getAdminUbah(),
			$row->getAdminApprove(),
			$row->optionAktif($appLanguage->getYes(), $appLanguage->getNo())
		);
	});
	exit();
}
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->tskId, $currentLoggedInSupervisor->getTskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisCuti();?></span>
					<span class="filter-control">
							<select class="form-control" name="jenis_cuti_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisCutiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisCutiId, Field::of()->nama, $inputGet->getJenisCutiId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getStatusCuti();?></span>
					<span class="filter-control">
							<select class="form-control" name="status_cuti" data-value="<?php echo $inputGet->getStatusCuti();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P" <?php echo AppFormBuilder::selected($inputGet->getStatusCuti(), 'P');?>>Menunggu Persetujuan</option>
								<option value="A" <?php echo AppFormBuilder::selected($inputGet->getStatusCuti(), 'A');?>>Disetujui</option>
								<option value="R" <?php echo AppFormBuilder::selected($inputGet->getStatusCuti(), 'R');?>>Ditolak</option>
								<option value="C" <?php echo AppFormBuilder::selected($inputGet->getStatusCuti(), 'C');?>>Dibatalkan</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedExport()){ ?>
		
				<span class="filter-group">
					<button type="submit" name="user_action" value="export" class="btn btn-success"><?php echo $appLanguage->getButtonExport();?></button>
				</span>
				<?php } ?>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
				</span>
				<?php } ?>
			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php try{
				$pageData = $dataLoader->findAll($specification, $pageable, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
				if($pageData->getTotalResult() > 0)
				{		
				    $pageControl = $pageData->getPageControl(Field::of()->page, $currentModule->getSelf())
				    ->setNavigation(
				        $dataControlConfig->getPrev(), $dataControlConfig->getNext(),
				        $dataControlConfig->getFirst(), $dataControlConfig->getLast()
				    )
				    ->setPageRange($dataControlConfig->getPageRange())
				    ;
			?>
			<div class="pagination pagination-top">
			    <div class="pagination-number">
			    <?php echo $pageControl; ?>
			    </div>
			</div>
			<form action="" method="post" class="data-form">
				<div class="data-wrapper">
					<table class="table table-row table-sort-by-column">
						<thead>
							<tr>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="cuti_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-cuti-id"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="jenis_cuti_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisCuti();?></a></td>
								<td data-col-name="cuti_dari" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiDari();?></a></td>
								<td data-col-name="cuti_hingga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiHingga();?></a></td>
								<td data-col-name="hari_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHariCuti();?></a></td>
								<td data-col-name="status_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatusCuti();?></a></td>
								<td data-col-name="waktu_approve" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuApprove();?></a></td>
								<td data-col-name="admin_approve" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAdminApprove();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($cuti = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $cuti->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="cuti_id">
									<input type="checkbox" class="checkbox check-slave checkbox-cuti-id" name="checked_row_id[]" value="<?php echo $cuti->getCutiId();?>" <?php echo $cuti->equalsStatusCuti('P') ? '' : ' disabled';?>/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->cuti_id, $cuti->getCutiId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $cuti->issetProyek() ? $cuti->getProyek()->getNama() : "";?></td>
								<td data-col-name="jenis_cuti_id"><?php echo $cuti->issetJenisCuti() ? $cuti->getJenisCuti()->getNama() : "";?></td>
								<td data-col-name="cuti_dari"><?php echo $cuti->getCutiDari();?></td>
								<td data-col-name="cuti_hingga"><?php echo $cuti->getCutiHingga();?></td>
								<td data-col-name="hari_cuti"><?php echo $cuti->getHariCuti();?></td>
								<td data-col-name="status_cuti"><?php echo isset($mapForStatusCuti) && isset($mapForStatusCuti[$cuti->getStatusCuti()]) && isset($mapForStatusCuti[$cuti->getStatusCuti()]["label"]) ? $mapForStatusCuti[$cuti->getStatusCuti()]["label"] : "";?></td>
								<td data-col-name="waktu_approve"><?php echo $cuti->getWaktuApprove();?></td>
								<td data-col-name="admin_approve"><?php echo $cuti->getAdminApprove();?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
						<?php if($userPermission->isAllowedUpdate()){ ?>
						<button type="submit" class="btn btn-success" name="user_action" value="activate"><?php echo $appLanguage->getButtonActivate();?></button>
						<button type="submit" class="btn btn-warning" name="user_action" value="deactivate"><?php echo $appLanguage->getButtonDeactivate();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedDelete()){ ?>
						<button type="submit" class="btn btn-danger" name="user_action" value="delete" data-onclik-message="<?php echo htmlspecialchars($appLanguage->getWarningDeleteConfirmation());?>"><?php echo $appLanguage->getButtonDelete();?></button>
						<?php } ?>
					</div>
				</div>
			</form>
			<div class="pagination pagination-bottom">
			    <div class="pagination-number">
			    <?php echo $pageControl; ?>
			    </div>
			</div>
			
			<?php 
			}
			else
			{
			    ?>
			    <div class="alert alert-info"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			    <?php
			}
			?>
			
			<?php
			}
			catch(Exception $e)
			{
			    ?>
			    <div class="alert alert-danger"><?php echo $appInclude->printException($e);?></div>
			    <?php
			} 
			?>
			<?php /*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){ ?>
		</div>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
/*ajaxSupport*/
}

