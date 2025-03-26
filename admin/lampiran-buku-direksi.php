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
use Sipro\Entity\Data\LampiranBukuDireksi;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\BukuDireksiMin;
use Sipro\Entity\Data\SupervisorMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "lampiran-buku-direksi", "Lampiran Buku Direksi");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
	$lampiranBukuDireksi->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setBukuDireksiId($inputPost->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lampiranBukuDireksi->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setGrupLampiran($inputPost->getGrupLampiran(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lampiranBukuDireksi->setDokumen($inputPost->getDokumen(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$lampiranBukuDireksi->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$lampiranBukuDireksi->setAdminBuat($currentAction->getUserId());
	$lampiranBukuDireksi->setWaktuBuat($currentAction->getTime());
	$lampiranBukuDireksi->setIpBuat($currentAction->getIp());
	$lampiranBukuDireksi->setAdminUbah($currentAction->getUserId());
	$lampiranBukuDireksi->setWaktuUbah($currentAction->getTime());
	$lampiranBukuDireksi->setIpUbah($currentAction->getIp());
	try
	{
		$lampiranBukuDireksi->insert();
		$newId = $lampiranBukuDireksi->getLampiranBukuDireksiId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->lampiran_buku_direksi_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
	$lampiranBukuDireksi->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setBukuDireksiId($inputPost->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lampiranBukuDireksi->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lampiranBukuDireksi->setGrupLampiran($inputPost->getGrupLampiran(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$lampiranBukuDireksi->setDokumen($inputPost->getDokumen(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$lampiranBukuDireksi->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$lampiranBukuDireksi->setAdminUbah($currentAction->getUserId());
	$lampiranBukuDireksi->setWaktuUbah($currentAction->getTime());
	$lampiranBukuDireksi->setIpUbah($currentAction->getIp());
	$lampiranBukuDireksi->setLampiranBukuDireksiId($inputPost->getLampiranBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	try
	{
		$lampiranBukuDireksi->update();
		$newId = $lampiranBukuDireksi->getLampiranBukuDireksiId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->lampiran_buku_direksi_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
			try
			{
				$lampiranBukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lampiranBukuDireksiId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
				)
				->setAdminUbah($currentAction->getUserId())
				->setWaktuUbah($currentAction->getTime())
				->setIpUbah($currentAction->getIp())
				->setAktif(true)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				$error_log($e->getMessage());
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
			$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
			try
			{
				$lampiranBukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lampiranBukuDireksiId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
				)
				->setAdminUbah($currentAction->getUserId())
				->setWaktuUbah($currentAction->getTime())
				->setIpUbah($currentAction->getIp())
				->setAktif(false)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				$error_log($e->getMessage());
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
				$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
				$lampiranBukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lampiran_buku_direksi_id, $rowId))
				)
				->delete();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				$error_log($e->getMessage());
			}
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new LampiranBukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuDireksi();?></td>
						<td>
							<select class="form-control" name="buku_direksi_id" id="buku_direksi_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuDireksiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuDireksiId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama" id="nama" required="required"/>
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
						<td><?php echo $appEntityLanguage->getGrupLampiran();?></td>
						<td>
							<select class="form-control" name="grup_lampiran" id="grup_lampiran">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="M">Permasalahan</option>
								<option value="S">Penyelesaian</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDokumen();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dokumen" id="dokumen" value="1"/> <?php echo $appEntityLanguage->getDokumen();?></label>
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
else if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
	try{
		$lampiranBukuDireksi->findOneByLampiranBukuDireksiId($inputGet->getLampiranBukuDireksiId());
		if($lampiranBukuDireksi->issetLampiranBukuDireksiId())
		{
$appEntityLanguage = new AppEntityLanguage(new LampiranBukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $lampiranBukuDireksi->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuDireksi();?></td>
						<td>
							<select class="form-control" name="buku_direksi_id" id="buku_direksi_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuDireksiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuDireksiId, Field::of()->nama, $lampiranBukuDireksi->getBukuDireksiId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $lampiranBukuDireksi->getNama();?>" autocomplete="off" required="required"/>
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
								Field::of()->supervisorId, Field::of()->nama, $lampiranBukuDireksi->getSupervisorId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGrupLampiran();?></td>
						<td>
							<select class="form-control" name="grup_lampiran" id="grup_lampiran" data-value="<?php echo $lampiranBukuDireksi->getGrupLampiran();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="M" <?php echo AppFormBuilder::selected($lampiranBukuDireksi->getGrupLampiran(), 'M');?>>Permasalahan</option>
								<option value="S" <?php echo AppFormBuilder::selected($lampiranBukuDireksi->getGrupLampiran(), 'S');?>>Penyelesaian</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDokumen();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dokumen" id="dokumen" value="1" <?php echo $lampiranBukuDireksi->createCheckedDokumen();?>/> <?php echo $appEntityLanguage->getDokumen();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $lampiranBukuDireksi->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="lampiran_buku_direksi_id" value="<?php echo $lampiranBukuDireksi->getLampiranBukuDireksiId();?>"/>
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
require_once $appInclude->mainAppFooter(__DIR__);
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$lampiranBukuDireksi = new LampiranBukuDireksi(null, $database);
	try{
		$subqueryMap = array(
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"bukuDireksiId" => array(
			"columnName" => "buku_direksi_id",
			"entityName" => "BukuDireksiMin",
			"tableName" => "buku_direksi",
			"primaryKey" => "buku_direksi_id",
			"objectName" => "buku_direksi",
			"propertyName" => "nama"
		), 
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		)
		);
		$lampiranBukuDireksi->findOneWithPrimaryKeyValue($inputGet->getLampiranBukuDireksiId(), $subqueryMap);
		if($lampiranBukuDireksi->issetLampiranBukuDireksiId())
		{
$appEntityLanguage = new AppEntityLanguage(new LampiranBukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			$mapForGrupLampiran = array(
				"M" => array("value" => "M", "label" => "Permasalahan", "default" => "false"),
				"S" => array("value" => "S", "label" => "Penyelesaian", "default" => "false")
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($lampiranBukuDireksi->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $lampiranBukuDireksi->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $lampiranBukuDireksi->issetProyek() ? $lampiranBukuDireksi->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuDireksi();?></td>
						<td><?php echo $lampiranBukuDireksi->issetBukuDireksi() ? $lampiranBukuDireksi->getBukuDireksi()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $lampiranBukuDireksi->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $lampiranBukuDireksi->issetSupervisor() ? $lampiranBukuDireksi->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGrupLampiran();?></td>
						<td><?php echo isset($mapForGrupLampiran) && isset($mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]) && isset($mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]["label"]) ? $mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFile();?></td>
						<td><?php echo $lampiranBukuDireksi->getFile();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getMime();?></td>
						<td><?php echo $lampiranBukuDireksi->getMime();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGambar();?></td>
						<td><?php echo $lampiranBukuDireksi->optionGambar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLebar();?></td>
						<td><?php echo $lampiranBukuDireksi->getLebar();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTinggi();?></td>
						<td><?php echo $lampiranBukuDireksi->getTinggi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDokumen();?></td>
						<td><?php echo $lampiranBukuDireksi->optionDokumen($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUkuranFile();?></td>
						<td><?php echo $lampiranBukuDireksi->getUkuranFile();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSha1File();?></td>
						<td><?php echo $lampiranBukuDireksi->getSha1File();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDiunduh();?></td>
						<td><?php echo $lampiranBukuDireksi->getDiunduh();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $lampiranBukuDireksi->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $lampiranBukuDireksi->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $lampiranBukuDireksi->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $lampiranBukuDireksi->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $lampiranBukuDireksi->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->lampiran_buku_direksi_id, $lampiranBukuDireksi->getLampiranBukuDireksiId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="lampiran_buku_direksi_id" value="<?php echo $lampiranBukuDireksi->getLampiranBukuDireksiId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new LampiranBukuDireksi(), $appConfig, $currentUser->getLanguageId());
$mapForGrupLampiran = array(
	"M" => array("value" => "M", "label" => "Permasalahan", "default" => "false"),
	"S" => array("value" => "S", "label" => "Penyelesaian", "default" => "false")
);
$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"bukuDireksiId" => PicoSpecification::filter("bukuDireksiId", "number"),
	"nama" => PicoSpecification::filter("nama", "fulltext"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"grupLampiran" => PicoSpecification::filter("grupLampiran", "fulltext"),
	"gambar" => PicoSpecification::filter("gambar", "boolean"),
	"dokumen" => PicoSpecification::filter("dokumen", "boolean")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"bukuDireksiId" => "bukuDireksiId",
	"nama" => "nama",
	"supervisorId" => "supervisorId",
	"grupLampiran" => "grupLampiran",
	"file" => "file",
	"gambar" => "gambar",
	"dokumen" => "dokumen",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "bukuDireksiId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	),
	array(
		"sortBy" => "lampiranBukuDireksiId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new LampiranBukuDireksi(null, $database);

$subqueryMap = array(
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"bukuDireksiId" => array(
	"columnName" => "buku_direksi_id",
	"entityName" => "BukuDireksiMin",
	"tableName" => "buku_direksi",
	"primaryKey" => "buku_direksi_id",
	"objectName" => "buku_direksi",
	"propertyName" => "nama"
), 
"supervisorId" => array(
	"columnName" => "supervisor_id",
	"entityName" => "SupervisorMin",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "supervisor",
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
		$appEntityLanguage->getLampiranBukuDireksiId() => $headerFormat->getLampiranBukuDireksiId(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getBukuDireksi() => $headerFormat->asString(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getGrupLampiran() => $headerFormat->asString(),
		$appEntityLanguage->getFile() => $headerFormat->getFile(),
		$appEntityLanguage->getMime() => $headerFormat->getMime(),
		$appEntityLanguage->getGambar() => $headerFormat->asString(),
		$appEntityLanguage->getLebar() => $headerFormat->getLebar(),
		$appEntityLanguage->getTinggi() => $headerFormat->getTinggi(),
		$appEntityLanguage->getDokumen() => $headerFormat->asString(),
		$appEntityLanguage->getUkuranFile() => $headerFormat->getUkuranFile(),
		$appEntityLanguage->getSha1File() => $headerFormat->getSha1File(),
		$appEntityLanguage->getDiunduh() => $headerFormat->getDiunduh(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
		global $mapForGrupLampiran;
		return array(
			sprintf("%d", $index + 1),
			$row->getLampiranBukuDireksiId(),
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetBukuDireksi() ? $row->getBukuDireksi()->getNama() : "",
			$row->getNama(),
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			isset($mapForGrupLampiran) && isset($mapForGrupLampiran[$row->getGrupLampiran()]) && isset($mapForGrupLampiran[$row->getGrupLampiran()]["label"]) ? $mapForGrupLampiran[$row->getGrupLampiran()]["label"] : "",
			$row->getFile(),
			$row->getMime(),
			$row->optionGambar($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getLebar(),
			$row->getTinggi(),
			$row->optionDokumen($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getUkuranFile(),
			$row->getSha1File(),
			$row->getDiunduh(),
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
require_once $appInclude->mainAppHeader(__DIR__);
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
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getBukuDireksi();?></span>
					<span class="filter-control">
							<select class="form-control" name="buku_direksi_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuDireksiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuDireksiId, Field::of()->nama, $inputGet->getBukuDireksiId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
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
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getGrupLampiran();?></span>
					<span class="filter-control">
							<select class="form-control" name="grup_lampiran" data-value="<?php echo $inputGet->getGrupLampiran();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="M" <?php echo AppFormBuilder::selected($inputGet->getGrupLampiran(), 'M');?>>Permasalahan</option>
								<option value="S" <?php echo AppFormBuilder::selected($inputGet->getGrupLampiran(), 'S');?>>Penyelesaian</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getGambar();?></span>
					<span class="filter-control">
							<select class="form-control" name="gambar" data-value="<?php echo $inputGet->getGambar();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getGambar(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getGambar(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getDokumen();?></span>
					<span class="filter-control">
							<select class="form-control" name="dokumen" data-value="<?php echo $inputGet->getDokumen();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getDokumen(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getDokumen(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
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
				    $pageControl = $pageData->getPageControl("page", $currentModule->getSelf())
				    ->setNavigation(
				    '<i class="fa-solid fa-angle-left"></i>', '<i class="fa-solid fa-angle-right"></i>',
				    '<i class="fa-solid fa-angles-left"></i>', '<i class="fa-solid fa-angles-right"></i>'
				    )
				    ->setPageRange($appConfig->getData()->getPageRange())
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
								<td class="data-controll data-selector" data-key="lampiran_buku_direksi_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-lampiran-buku-direksi-id"/>
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
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="buku_direksi_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBukuDireksi();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="grup_lampiran" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getGrupLampiran();?></a></td>
								<td data-col-name="file" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getFile();?></a></td>
								<td data-col-name="gambar" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getGambar();?></a></td>
								<td data-col-name="dokumen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDokumen();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($lampiranBukuDireksi = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="lampiran_buku_direksi_id">
									<input type="checkbox" class="checkbox check-slave checkbox-lampiran-buku-direksi-id" name="checked_row_id[]" value="<?php echo $lampiranBukuDireksi->getLampiranBukuDireksiId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->lampiran_buku_direksi_id, $lampiranBukuDireksi->getLampiranBukuDireksiId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->lampiran_buku_direksi_id, $lampiranBukuDireksi->getLampiranBukuDireksiId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $lampiranBukuDireksi->issetProyek() ? $lampiranBukuDireksi->getProyek()->getNama() : "";?></td>
								<td data-col-name="buku_direksi_id"><?php echo $lampiranBukuDireksi->issetBukuDireksi() ? $lampiranBukuDireksi->getBukuDireksi()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $lampiranBukuDireksi->getNama();?></td>
								<td data-col-name="supervisor_id"><?php echo $lampiranBukuDireksi->issetSupervisor() ? $lampiranBukuDireksi->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="grup_lampiran"><?php echo isset($mapForGrupLampiran) && isset($mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]) && isset($mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]["label"]) ? $mapForGrupLampiran[$lampiranBukuDireksi->getGrupLampiran()]["label"] : "";?></td>
								<td data-col-name="file"><?php echo $lampiranBukuDireksi->getFile();?></td>
								<td data-col-name="gambar"><?php echo $lampiranBukuDireksi->optionGambar($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="dokumen"><?php echo $lampiranBukuDireksi->optionDokumen($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="aktif"><?php echo $lampiranBukuDireksi->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}

