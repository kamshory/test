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
use Sipro\Entity\Data\PerjalananDinas;
use Sipro\Entity\Data\JenisPerjalananDinasMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\TskMin;
use Sipro\Util\CalendarUtil;

require_once __DIR__ . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, null, "/", "perjalanan-dinas", $appLanguage->getPerjalananDinas());

$dataFilter = PicoSpecification::getInstance()
	->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $currentUser->getSupervisorId()))
	;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$dari = $inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$hingga = $inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$hariKerja = CalendarUtil::getWorkingDays($database, $dari, $hingga);
	$detil = implode(", ", $hariKerja);

	$perjalananDinas = new PerjalananDinas(null, $database);
	$perjalananDinas->setJenisPerjalananDinasId($inputPost->getJenisPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setTskId($currentUser->getTskId());
	$perjalananDinas->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setSupervisorId($currentUser->getSupervisorId());
	$perjalananDinas->setAsal($inputPost->getAsal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setTujuan($inputPost->getTujuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAlatAngkutan($inputPost->getAlatAngkutan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeperluan($inputPost->getKeperluan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDari($inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setHingga($inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDetil($detil);
	$perjalananDinas->setStatusPerjalananDinas("P");
	$perjalananDinas->setAktif(true);
	$perjalananDinas->setAdminBuat($currentAction->getUserId());
	$perjalananDinas->setWaktuBuat($currentAction->getTime());
	$perjalananDinas->setIpBuat($currentAction->getIp());
	$perjalananDinas->setAdminUbah($currentAction->getUserId());
	$perjalananDinas->setWaktuUbah($currentAction->getTime());
	$perjalananDinas->setIpUbah($currentAction->getIp());
	try
	{
		$perjalananDinas->insert();
		$newId = $perjalananDinas->getPerjalananDinasId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$dari = $inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$hingga = $inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$hariKerja = CalendarUtil::getWorkingDays($database, $dari, $hingga);
	$detil = implode(", ", $hariKerja);

	$specification = PicoSpecification::getInstanceOf(Field::of()->perjalananDinasId, $inputPost->getPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd(PicoPredicate::getInstance()->equals(Field::of()->statusPerjalananDinas, 'P'));
	$specification->addAnd($dataFilter);
	$perjalananDinas = new PerjalananDinas(null, $database);
	$updater = $perjalananDinas->where($specification)
		->setJenisPerjalananDinasId($inputPost->getJenisPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setTskId($currentUser->getTskId())
		->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setAsal($inputPost->getAsal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTujuan($inputPost->getTujuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAlatAngkutan($inputPost->getAlatAngkutan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setKeperluan($inputPost->getKeperluan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setDari($inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setHingga($inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setDetil($detil)
		->setAktif(true)
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}

else if($inputPost->getUserAction() == UserAction::DELETE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			try
			{
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->perjalananDinasId, $rowId))
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->statusPerjalananDinas, 'P'))
					->addAnd($dataFilter)
					;
				$perjalananDinas = new PerjalananDinas(null, $database);
				$perjalananDinas->where($specification)
					->delete();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				error_log($e->getMessage());
			}
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td>
							<select class="form-control" name="jenis_perjalanan_dinas_id" id="jenis_perjalanan_dinas_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinasMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama)
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
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->tskId, $currentUser->getTskId()))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td>
							<input type="text" class="form-control" name="asal" id="asal" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td>
							<input type="text" class="form-control" name="tujuan" id="tujuan" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td>
							<textarea class="form-control" name="alat_angkutan" id="alat_angkutan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td>
							<textarea class="form-control" name="keperluan" id="keperluan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td>
							<input type="date" class="form-control" name="dari" id="dari" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td>
							<input type="date" class="form-control" name="hingga" id="hingga" value="" autocomplete="off"/>
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->perjalananDinasId, $inputGet->getPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd(['statusPerjalananDinas', 'P']);
	$specification->addAnd($dataFilter);
	$perjalananDinas = new PerjalananDinas(null, $database);
	try{
		$perjalananDinas->findOne($specification);
		if($perjalananDinas->issetPerjalananDinasId())
		{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td>
							<select class="form-control" name="jenis_perjalanan_dinas_id" id="jenis_perjalanan_dinas_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinasMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama, $perjalananDinas->getJenisPerjalananDinasId())
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
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $perjalananDinas->getProyekId()))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $perjalananDinas->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td>
							<input type="text" class="form-control" name="asal" id="asal" value="<?php echo $perjalananDinas->getAsal();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td>
							<input type="text" class="form-control" name="tujuan" id="tujuan" value="<?php echo $perjalananDinas->getTujuan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td>
							<textarea class="form-control" name="alat_angkutan" id="alat_angkutan" spellcheck="false"><?php echo $perjalananDinas->getAlatAngkutan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td>
							<textarea class="form-control" name="keperluan" id="keperluan" spellcheck="false"><?php echo $perjalananDinas->getKeperluan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"><?php echo $perjalananDinas->getKeterangan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td>
							<input type="date" class="form-control" name="dari" id="dari" value="<?php echo $perjalananDinas->getDari();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td>
							<input type="date" class="form-control" name="hingga" id="hingga" value="<?php echo $perjalananDinas->getHingga();?>" autocomplete="off"/>
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
							<input type="hidden" name="perjalanan_dinas_id" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->perjalananDinasId, $inputGet->getPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$perjalananDinas = new PerjalananDinas(null, $database);
	try{
		$subqueryMap = array(
		"jenisPerjalananDinasId" => array(
			"columnName" => "jenis_perjalanan_dinas_id",
			"entityName" => "JenisPerjalananDinasMin",
			"tableName" => "jenis_perjalanan_dinas",
			"primaryKey" => "jenis_perjalanan_dinas_id",
			"objectName" => "jenis_perjalanan_dinas",
			"propertyName" => "nama"
		), 
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
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "UserMin",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pembuat",
			"propertyName" => "first_name"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "UserMin",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pengubah",
			"propertyName" => "first_name"
		)
		);
		$perjalananDinas->findOne($specification, null, $subqueryMap);
		if($perjalananDinas->issetPerjalananDinasId())
		{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// Define map here
			$mapForStatusPerjalananDinas = array(
				"P" => array("value" => "P", "label" => "Pending", "group" => "", "selected" => false),
				"A" => array("value" => "A", "label" => "Disetujui", "group" => "", "selected" => false),
				"R" => array("value" => "R", "label" => "Ditolak", "group" => "", "selected" => false),
				"C" => array("value" => "C", "label" => "Dibatalkan", "group" => "", "selected" => false)
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($perjalananDinas->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $perjalananDinas->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td><?php echo $perjalananDinas->issetJenisPerjalananDinas() ? $perjalananDinas->getJenisPerjalananDinas()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td><?php echo $perjalananDinas->issetTsk() ? $perjalananDinas->getTsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $perjalananDinas->issetProyek() ? $perjalananDinas->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $perjalananDinas->issetSupervisor() ? $perjalananDinas->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSppd();?></td>
						<td><?php echo $perjalananDinas->getNomorSppd();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td><?php echo $perjalananDinas->getAsal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td><?php echo $perjalananDinas->getTujuan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td><?php echo $perjalananDinas->getKodeLokasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td><?php echo $perjalananDinas->getAlatAngkutan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td><?php echo $perjalananDinas->getKeperluan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td><?php echo $perjalananDinas->getKeterangan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDetil();?></td>
						<td><?php echo $perjalananDinas->getDetil();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td><?php echo $perjalananDinas->getDari();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td><?php echo $perjalananDinas->getHingga();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtasBebanBiaya();?></td>
						<td><?php echo $perjalananDinas->getAtasBebanBiaya();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatusPerjalananDinas();?></td>
						<td><?php echo isset($mapForStatusPerjalananDinas) && isset($mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]) && isset($mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]["label"]) ? $mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td><?php echo $perjalananDinas->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuDibayar();?></td>
						<td><?php echo $perjalananDinas->dateFormatWaktuDibayar('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $perjalananDinas->issetPembuat() ? $perjalananDinas->getPembuat()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $perjalananDinas->issetPengubah() ? $perjalananDinas->getPengubah()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $perjalananDinas->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $perjalananDinas->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $perjalananDinas->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $perjalananDinas->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $perjalananDinas->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate() && $perjalananDinas->getStatusPerjalananDinas() == 'P'){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="perjalanan_dinas_id" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
$mapForStatusPerjalananDinas = array(
	"P" => array("value" => "P", "label" => "Pending", "group" => "", "selected" => false),
	"A" => array("value" => "A", "label" => "Disetujui", "group" => "", "selected" => false),
	"R" => array("value" => "R", "label" => "Ditolak", "group" => "", "selected" => false),
	"C" => array("value" => "C", "label" => "Dibatalkan", "group" => "", "selected" => false)
);
$specMap = array(
	"jenisPerjalananDinasId" => PicoSpecification::filter("jenisPerjalananDinasId", "number"),
	"tskId" => PicoSpecification::filter("tskId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"statusPerjalananDinas" => PicoSpecification::filter("statusPerjalananDinas", "fulltext")
);
$sortOrderMap = array(
	"jenisPerjalananDinasId" => "jenisPerjalananDinasId",
	"tskId" => "tskId",
	"proyekId" => "proyekId",
	"supervisorId" => "supervisorId",
	"nomorSppd" => "nomorSppd",
	"asal" => "asal",
	"tujuan" => "tujuan",
	"kodeLokasi" => "kodeLokasi",
	"detil" => "detil",
	"dari" => "dari",
	"hingga" => "hingga",
	"statusPerjalananDinas" => "statusPerjalananDinas",
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
		"sortBy" => "dari", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new PerjalananDinas(null, $database);

$subqueryMap = array(
"jenisPerjalananDinasId" => array(
	"columnName" => "jenis_perjalanan_dinas_id",
	"entityName" => "JenisPerjalananDinasMin",
	"tableName" => "jenis_perjalanan_dinas",
	"primaryKey" => "jenis_perjalanan_dinas_id",
	"objectName" => "jenis_perjalanan_dinas",
	"propertyName" => "nama"
), 
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
"adminBuat" => array(
	"columnName" => "admin_buat",
	"entityName" => "UserMin",
	"tableName" => "user",
	"primaryKey" => "user_id",
	"objectName" => "pembuat",
	"propertyName" => "first_name"
), 
"adminUbah" => array(
	"columnName" => "admin_ubah",
	"entityName" => "UserMin",
	"tableName" => "user",
	"primaryKey" => "user_id",
	"objectName" => "pengubah",
	"propertyName" => "first_name"
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
		$appEntityLanguage->getJenisPerjalananDinas() => $headerFormat->asString(),
		$appEntityLanguage->getTsk() => $headerFormat->asString(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getNomorSppd() => $headerFormat->getNomorSppd(),
		$appEntityLanguage->getAsal() => $headerFormat->getAsal(),
		$appEntityLanguage->getTujuan() => $headerFormat->getTujuan(),
		$appEntityLanguage->getKodeLokasi() => $headerFormat->getKodeLokasi(),
		$appEntityLanguage->getAlatAngkutan() => $headerFormat->asString(),
		$appEntityLanguage->getKeperluan() => $headerFormat->asString(),
		$appEntityLanguage->getKeterangan() => $headerFormat->asString(),
		$appEntityLanguage->getDetil() => $headerFormat->getDetil(),
		$appEntityLanguage->getDari() => $headerFormat->getDari(),
		$appEntityLanguage->getHingga() => $headerFormat->getHingga(),
		$appEntityLanguage->getAtasBebanBiaya() => $headerFormat->getAtasBebanBiaya(),
		$appEntityLanguage->getStatusPerjalananDinas() => $headerFormat->asString(),
		$appEntityLanguage->getDibayar() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuDibayar() => $headerFormat->getWaktuDibayar(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->asString(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage, $mapForStatusPerjalananDinas) {
		return array(
			sprintf("%d", $index + 1),
			$row->issetJenisPerjalananDinas() ? $row->getJenisPerjalananDinas()->getNama() : "",
			$row->issetTsk() ? $row->getTsk()->getNama() : "",
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->getNomorSppd(),
			$row->getAsal(),
			$row->getTujuan(),
			$row->getKodeLokasi(),
			$row->getAlatAngkutan(),
			$row->getKeperluan(),
			$row->getKeterangan(),
			$row->getDetil(),
			$row->getDari(),
			$row->getHingga(),
			$row->getAtasBebanBiaya(),
			isset($mapForStatusPerjalananDinas) && isset($mapForStatusPerjalananDinas[$row->getStatusPerjalananDinas()]) && isset($mapForStatusPerjalananDinas[$row->getStatusPerjalananDinas()]["label"]) ? $mapForStatusPerjalananDinas[$row->getStatusPerjalananDinas()]["label"] : "",
			$row->optionDibayar($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getWaktuDibayar(),
			$row->issetPembuat() ? $row->getPembuat()->getFirstName() : "",
			$row->issetPengubah() ? $row->getPengubah()->getFirstName() : "",
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getIpBuat(),
			$row->getIpUbah(),
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
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></span>
					<span class="filter-control">
							<select class="form-control" name="jenis_perjalanan_dinas_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinasMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama, $inputGet->getJenisPerjalananDinasId())
								; ?>
							</select>
					</span>
				</span>
				
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
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getStatusPerjalananDinas();?></span>
					<span class="filter-control">
							<select class="form-control" name="status_perjalanan_dinas" data-value="<?php echo $inputGet->getStatusPerjalananDinas();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P" <?php echo AppFormBuilder::selected($inputGet->getStatusPerjalananDinas(), 'P');?>>Pending</option>
								<option value="A" <?php echo AppFormBuilder::selected($inputGet->getStatusPerjalananDinas(), 'A');?>>Disetujui</option>
								<option value="R" <?php echo AppFormBuilder::selected($inputGet->getStatusPerjalananDinas(), 'R');?>>Ditolak</option>
								<option value="C" <?php echo AppFormBuilder::selected($inputGet->getStatusPerjalananDinas(), 'C');?>>Dibatalkan</option>
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
								<td class="data-controll data-selector" data-key="perjalanan_dinas_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-perjalanan-dinas-id"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td class="data-controll data-editor">
									<span class="fa fa-edit"></span>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="jenis_perjalanan_dinas_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></a></td>
								<td data-col-name="tsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTsk();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="nomor_sppd" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorSppd();?></a></td>
								<td data-col-name="asal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAsal();?></a></td>
								<td data-col-name="tujuan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTujuan();?></a></td>
								<td data-col-name="kode_lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKodeLokasi();?></a></td>
								<td data-col-name="detil" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDetil();?></a></td>
								<td data-col-name="dari" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDari();?></a></td>
								<td data-col-name="hingga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHingga();?></a></td>
								<td data-col-name="status_perjalanan_dinas" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatusPerjalananDinas();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($perjalananDinas = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $perjalananDinas->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="perjalanan_dinas_id">
									<input type="checkbox" class="checkbox check-slave checkbox-perjalanan-dinas-id" name="checked_row_id[]" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="jenis_perjalanan_dinas_id"><?php echo $perjalananDinas->issetJenisPerjalananDinas() ? $perjalananDinas->getJenisPerjalananDinas()->getNama() : "";?></td>
								<td data-col-name="tsk_id"><?php echo $perjalananDinas->issetTsk() ? $perjalananDinas->getTsk()->getNama() : "";?></td>
								<td data-col-name="proyek_id"><?php echo $perjalananDinas->issetProyek() ? $perjalananDinas->getProyek()->getNama() : "";?></td>
								<td data-col-name="nomor_sppd"><?php echo $perjalananDinas->getNomorSppd();?></td>
								<td data-col-name="asal"><?php echo $perjalananDinas->getAsal();?></td>
								<td data-col-name="tujuan"><?php echo $perjalananDinas->getTujuan();?></td>
								<td data-col-name="kode_lokasi"><?php echo $perjalananDinas->getKodeLokasi();?></td>
								<td data-col-name="detil"><?php echo  wordwrap($perjalananDinas->getDetil(), 50, '<br />', true);?></td>
								<td data-col-name="dari"><?php echo $perjalananDinas->getDari();?></td>
								<td data-col-name="hingga"><?php echo $perjalananDinas->getHingga();?></td>
								<td data-col-name="status_perjalanan_dinas"><?php echo isset($mapForStatusPerjalananDinas) && isset($mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]) && isset($mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]["label"]) ? $mapForStatusPerjalananDinas[$perjalananDinas->getStatusPerjalananDinas()]["label"] : "";?></td>
								<td data-col-name="aktif"><?php echo $perjalananDinas->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

