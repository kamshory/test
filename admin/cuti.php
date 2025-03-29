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
use MagicApp\AppUserPermission;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Cuti;
use Sipro\Entity\Data\TskMin;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\JenisCutiMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;
use Sipro\Entity\Data\CutiSupervisor;
use Sipro\Entity\Data\Kehadiran;
use Sipro\Entity\Data\KuotaCuti;
use Sipro\Entity\Data\KuotaCutiMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "cuti", $appLanguage->getCuti());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

$dataFilter = null;
if($currentUser->getTskId())
{
	$dataFilter = PicoSpecification::getInstance();
	$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $currentUser->getTskId()));
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$cuti = new Cuti(null, $database);
	$cuti->setTskId($inputPost->getTskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setJenisCutiId($inputPost->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setDibayar($inputPost->getDibayar(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cuti->setCutiDari($inputPost->getCutiDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setCutiHingga($inputPost->getCutiHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setHariCuti($inputPost->getHariCuti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setDetilTanggalCuti($inputPost->getDetilTanggalCuti(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setAlasan($inputPost->getAlasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setSupervisorPengganti($inputPost->getSupervisorPengganti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setStatusCuti($inputPost->getStatusCuti(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setWaktuApprove($inputPost->getWaktuApprove(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cuti->setAdminBuat($currentAction->getUserId());
	$cuti->setWaktuBuat($currentAction->getTime());
	$cuti->setIpBuat($currentAction->getIp());
	$cuti->setAdminUbah($currentAction->getUserId());
	$cuti->setWaktuUbah($currentAction->getTime());
	$cuti->setIpUbah($currentAction->getIp());
	try
	{
		$cuti->insert();
		$newId = $cuti->getCutiId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::APPROVE)
{
	if($inputPost->countableCheckedRowId())
	{
		// Mulai transaksi database
		$database->startTransaction();
		$kuotaTahun = array();

		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			try
			{
				// Inisialisasi objek yang diperlukan untuk menghapus cuti dan memperbarui kuota
				$cuti = new Cuti(null, $database);
				$cuti->findOneByCutiId($rowId);
				$kuotaCuti = new KuotaCuti(null, $database);

				// Menentukan spesifikasi cuti supervisor yang akan dihapus
				$specsCutiSupervisor = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiId, $rowId))
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->statusCuti, 'P'))
					->addAnd($dataFilter)
					;
				
				$cutiSupervisor = new CutiSupervisor(null, $database);

				$specificationCuti = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiId, $rowId))
					->addAnd($dataFilter)
				;

				// Setujui cuti supervisor terkait
				$cutiSupervisor
					->where($specsCutiSupervisor)
					->setStatusCuti('A') // Approved
					->update();
				
				
				// Setujui cuti
				$cuti
					->setStatusCuti('A') // Approved
					->update();

				// Buat record di tabel kehadiran

				$specsCutiSupervisor = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiId, $rowId))
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->statusCuti, 'A')) // Approve
					;
				$sorts = PicoSortable::getInstance()
				->addSortable(new PicoSort(Field::of()->tangga, PicoSort::ORDER_TYPE_ASC));
				try
				{
					$pageData = $cutiSupervisor->findAll($specsCutiSupervisor, null, $sorts);

					foreach($pageData->getResult() as $cs)
					{
						$kehadiran = new Kehadiran(null, $database);
						$kehadiran->setTipePengguna('supervisor');
						$kehadiran->setSupervisorId($cs->getSupervisorId());
						$kehadiran->setTanggal($cs->getTanggal());
						$kehadiran->setTipeKehadiran('C'); // Cuti
						$kehadiran->setAktif(true);
						$kehadiran->setAdminBuat($currentAction->getAdminId());
						$kehadiran->setWaktuBuat($currentAction->getTime());
						$kehadiran->setIpBuat($currentAction->getIp());
						$kehadiran->setAdminUbah($currentAction->getAdminId());
						$kehadiran->setWaktuUbah($currentAction->getTime());
						$kehadiran->setIpUbah($currentAction->getIp());
					}

				}
				catch(Exception $e)
				{
					// Do nothing
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
else if($inputPost->getUserAction() == UserAction::REJECT)
{
	if($inputPost->countableCheckedRowId())
	{
		// Mulai transaksi database
		$database->startTransaction();
		$kuotaTahun = array();

		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			try
			{
				// Inisialisasi objek yang diperlukan untuk menghapus cuti dan memperbarui kuota
				$cuti = new Cuti(null, $database);
				$cuti->findOneByCutiId($rowId);
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

				// Tolak cuti supervisor terkait
				$cutiSupervisor
					->where($specsCutiSupervisor)
					->setStatusCuti('R') // Rejected
					->update();
				
				
				// Tolak cuti
				$cuti
					->setStatusCuti('R') // Rejected
					->update();

				// Memperbarui kuota cuti setelah penghapusan
				try
				{
					foreach($kuotaTahun as $tahun=>$dibebaskan)
					{
						$specificationKuotaCutiUpdate = PicoSpecification::getInstance()
						->addAnd([Field::of()->supervisorId, $cuti->getSupervisorId()])
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
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td>
							<select class="form-control" name="tsk_id" id="tsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort("umk.nama", PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tskId, Field::of()->nama)
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td>
							<select class="form-control" name="jenis_cuti_id" id="jenis_cuti_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisCutiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisCutiId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dibayar" id="dibayar" value="1"/> <?php echo $appEntityLanguage->getDibayar();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiDari();?></td>
						<td>
							<input type="date" class="form-control" name="cuti_dari" id="cuti_dari" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiHingga();?></td>
						<td>
							<input type="date" class="form-control" name="cuti_hingga" id="cuti_hingga" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHariCuti();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="hari_cuti" id="hari_cuti" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDetilTanggalCuti();?></td>
						<td>
							<input type="text" class="form-control" name="detil_tanggal_cuti" id="detil_tanggal_cuti" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlasan();?></td>
						<td>
							<textarea class="form-control" name="alasan" id="alasan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorPengganti();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="supervisor_pengganti" id="supervisor_pengganti" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatusCuti();?></td>
						<td>
							<select class="form-control" name="status_cuti" id="status_cuti">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P">Menunggu Persetujuan</option>
								<option value="A">Disetujui</option>
								<option value="R">Ditolak</option>
								<option value="C">Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuApprove();?></td>
						<td>
							<input type="datetime-local" class="form-control" name="waktu_approve" id="waktu_approve" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1"/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-success" name="user_action" value="create"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
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
		), 
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "AdminMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
			"propertyName" => "nama"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "AdminMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama"
		), 
		"adminApprove" => array(
			"columnName" => "admin_approve",
			"entityName" => "Admin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "approver",
			"propertyName" => "nama"
		)
		);
		$cuti->findOne($specification, null, $subqueryMap);
		if($cuti->issetCutiId())
		{
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
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
						<td><?php echo $cuti->issetPembuat() ? $cuti->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $cuti->issetPengubah() ? $cuti->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminApprove();?></td>
						<td><?php echo $cuti->issetApprover() ? $cuti->getApprover()->getNama() : "";?></td>
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
require_once $appInclude->mainAppFooter(__DIR__);
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
require_once $appInclude->mainAppHeader(__DIR__);
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php 
require_once $appInclude->mainAppFooter(__DIR__);
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
	"detilTanggalCuti" => "detilTanggalCuti",
	"statusCuti" => "statusCuti",
	"waktuApprove" => "waktuApprove",
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
), 
"adminBuat" => array(
	"columnName" => "admin_buat",
	"entityName" => "AdminMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "pembuat",
	"propertyName" => "nama"
), 
"adminUbah" => array(
	"columnName" => "admin_ubah",
	"entityName" => "AdminMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "pengubah",
	"propertyName" => "nama"
), 
"adminApprove" => array(
	"columnName" => "admin_approve",
	"entityName" => "Admin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "approver",
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
		$appEntityLanguage->getDetilTanggalCuti() => $headerFormat->getDetilTanggalCuti(),
		$appEntityLanguage->getAlasan() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisorPengganti() => $headerFormat->getSupervisorPengganti(),
		$appEntityLanguage->getStatusCuti() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getWaktuApprove() => $headerFormat->getWaktuApprove(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getIpApprove() => $headerFormat->getIpApprove(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->asString(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->asString(),
		$appEntityLanguage->getAdminApprove() => $headerFormat->asString(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
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
			$row->getDetilTanggalCuti(),
			$row->getAlasan(),
			$row->getSupervisorPengganti(),
			isset($mapForStatusCuti) && isset($mapForStatusCuti[$row->getStatusCuti()]) && isset($mapForStatusCuti[$row->getStatusCuti()]["label"]) ? $mapForStatusCuti[$row->getStatusCuti()]["label"] : "",
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getWaktuApprove(),
			$row->getIpBuat(),
			$row->getIpUbah(),
			$row->getIpApprove(),
			$row->issetPembuat() ? $row->getPembuat()->getNama() : "",
			$row->issetPengubah() ? $row->getPengubah()->getNama() : "",
			$row->issetApprover() ? $row->getApprover()->getNama() : "",
			$row->optionAktif($appLanguage->getYes(), $appLanguage->getNo())
		);
	});
	exit();
}
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<?php
				if($currentUser->getTskId() == 0)
				{
				?>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTsk();?></span>
					<span class="filter-control">
							<select class="form-control" name="tsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tskId, Field::of()->nama, $inputGet->getTskId())
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
					</span>
				</span>
				<?php
				}
				?>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->tskId, $inputGet->getTskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisor();?></span>
					<span class="filter-control">
							<select class="form-control" name="supervisor_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->tskId, $inputGet->getTskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorId())
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
								<td data-col-name="tsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTsk();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="jenis_cuti_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisCuti();?></a></td>
								<td data-col-name="cuti_dari" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiDari();?></a></td>
								<td data-col-name="cuti_hingga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiHingga();?></a></td>
								<td data-col-name="hari_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHariCuti();?></a></td>
								<td data-col-name="detil_tanggal_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDetilTanggalCuti();?></a></td>
								<td data-col-name="status_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatusCuti();?></a></td>
								<td data-col-name="waktu_approve" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuApprove();?></a></td>
								<td data-col-name="admin_approve" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAdminApprove();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
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
									<input type="checkbox" class="checkbox check-slave checkbox-cuti-id" name="checked_row_id[]" value="<?php echo $cuti->getCutiId();?>"<?php echo $cuti->equalsStatusCuti('P') ? '' : ' disabled';?>/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->cuti_id, $cuti->getCutiId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="tsk_id"><?php echo $cuti->issetTsk() ? $cuti->getTsk()->getNama() : "";?></td>
								<td data-col-name="proyek_id"><?php echo $cuti->issetProyek() ? $cuti->getProyek()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $cuti->issetSupervisor() ? $cuti->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="jenis_cuti_id"><?php echo $cuti->issetJenisCuti() ? $cuti->getJenisCuti()->getNama() : "";?></td>
								<td data-col-name="cuti_dari"><?php echo $cuti->getCutiDari();?></td>
								<td data-col-name="cuti_hingga"><?php echo $cuti->getCutiHingga();?></td>
								<td data-col-name="hari_cuti"><?php echo $cuti->getHariCuti();?></td>
								<td data-col-name="detil_tanggal_cuti"><?php echo $cuti->getDetilTanggalCuti();?></td>
								<td data-col-name="status_cuti"><?php echo isset($mapForStatusCuti) && isset($mapForStatusCuti[$cuti->getStatusCuti()]) && isset($mapForStatusCuti[$cuti->getStatusCuti()]["label"]) ? $mapForStatusCuti[$cuti->getStatusCuti()]["label"] : "";?></td>
								<td data-col-name="waktu_approve"><?php echo $cuti->getWaktuApprove();?></td>
								<td data-col-name="admin_approve"><?php echo $cuti->issetApprover() ? $cuti->getApprover()->getNama() : "";?></td>
								<td data-col-name="aktif"><?php echo $cuti->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
						<?php if($currentUser->getTskId() != 0){ ?>
						<button type="submit" class="btn btn-success" name="user_action" value="approve"><?php echo $appLanguage->getButtonApprove();?></button>
						<button type="submit" class="btn btn-warning" name="user_action" value="reject"><?php echo $appLanguage->getButtonReject();?></button>
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
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}

