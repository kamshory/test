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
use Sipro\Entity\Data\CutiSupervisor;
use Sipro\Entity\Data\TskMin;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\JenisCutiMin;
use Sipro\Entity\Data\CutiMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once __DIR__ . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/", "cuti-supervisor", $appLanguage->getCutiSupervisor());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

$dataFilter = null;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$cutiSupervisor = new CutiSupervisor(null, $database);
	$cutiSupervisor->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cutiSupervisor->setTskId($inputPost->getTskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cutiSupervisor->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cutiSupervisor->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cutiSupervisor->setJenisCutiId($inputPost->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cutiSupervisor->setCutiId($inputPost->getCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cutiSupervisor->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cutiSupervisor->setStatusCuti($inputPost->getStatusCuti(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cutiSupervisor->setDisetujui($inputPost->getDisetujui(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cutiSupervisor->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cutiSupervisor->setAdminBuat($currentAction->getUserId());
	$cutiSupervisor->setWaktuBuat($currentAction->getTime());
	$cutiSupervisor->setIpBuat($currentAction->getIp());
	$cutiSupervisor->setAdminUbah($currentAction->getUserId());
	$cutiSupervisor->setWaktuUbah($currentAction->getTime());
	$cutiSupervisor->setIpUbah($currentAction->getIp());
	try
	{
		$cutiSupervisor->insert();
		$newId = $cutiSupervisor->getCutiSupervisorId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_supervisor_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->cutiSupervisorId, $inputPost->getCutiSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$cutiSupervisor = new CutiSupervisor(null, $database);
	$updater = $cutiSupervisor->where($specification)
		->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTskId($inputPost->getTskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setJenisCutiId($inputPost->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setCutiId($inputPost->getCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setStatusCuti($inputPost->getStatusCuti(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setDisetujui($inputPost->getDisetujui(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getCutiSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_supervisor_id, $newId);
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
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			$cutiSupervisor = new CutiSupervisor(null, $database);
			try
			{
				$cutiSupervisor->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiSupervisorId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
					->addAnd($dataFilter)
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
				error_log($e->getMessage());
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::DEACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
		{
			$cutiSupervisor = new CutiSupervisor(null, $database);
			try
			{
				$cutiSupervisor->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiSupervisorId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
					->addAnd($dataFilter)
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
				error_log($e->getMessage());
			}
		}
	}
	$currentModule->redirectToItself();
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cutiSupervisorId, $rowId))
					->addAnd($dataFilter)
					;
				$cutiSupervisor = new CutiSupervisor(null, $database);
				$cutiSupervisor->where($specification)
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
$appEntityLanguage = new AppEntityLanguage(new CutiSupervisor(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal" value="" id="tanggal" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td>
							<select class="form-control" name="tsk_id" id="tsk_id" required="required">
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
							<select class="form-control" name="proyek_id" id="proyek_id" required="required">
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
							<select class="form-control" name="supervisor_id" id="supervisor_id" required="required">
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
							<select class="form-control" name="jenis_cuti_id" id="jenis_cuti_id" required="required">
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
						<td><?php echo $appEntityLanguage->getCuti();?></td>
						<td>
							<select class="form-control" name="cuti_id" id="cuti_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new CutiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->cutiId, Field::of()->cutiDari)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"></textarea>
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
						<td><?php echo $appEntityLanguage->getDisetujui();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="disetujui" id="disetujui" value="1"/> <?php echo $appEntityLanguage->getDisetujui();?></label>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->cutiSupervisorId, $inputGet->getCutiSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$cutiSupervisor = new CutiSupervisor(null, $database);
	try{
		$cutiSupervisor->findOne($specification);
		if($cutiSupervisor->issetCutiSupervisorId())
		{
$appEntityLanguage = new AppEntityLanguage(new CutiSupervisor(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal" id="tanggal" value="<?php echo $cutiSupervisor->getTanggal();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td>
							<select class="form-control" name="tsk_id" id="tsk_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort("umk.nama", PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tskId, Field::of()->nama, $cutiSupervisor->getTskId())
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $cutiSupervisor->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $cutiSupervisor->getSupervisorId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td>
							<select class="form-control" name="jenis_cuti_id" id="jenis_cuti_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisCutiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisCutiId, Field::of()->nama, $cutiSupervisor->getJenisCutiId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCuti();?></td>
						<td>
							<select class="form-control" name="cuti_id" id="cuti_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new CutiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->cutiId, Field::of()->cutiDari, $cutiSupervisor->getCutiId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"><?php echo $cutiSupervisor->getKeterangan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatusCuti();?></td>
						<td>
							<select class="form-control" name="status_cuti" id="status_cuti" data-value="<?php echo $cutiSupervisor->getStatusCuti();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P" <?php echo AppFormBuilder::selected($cutiSupervisor->getStatusCuti(), 'P');?>>Menunggu Persetujuan</option>
								<option value="A" <?php echo AppFormBuilder::selected($cutiSupervisor->getStatusCuti(), 'A');?>>Disetujui</option>
								<option value="R" <?php echo AppFormBuilder::selected($cutiSupervisor->getStatusCuti(), 'R');?>>Ditolak</option>
								<option value="C" <?php echo AppFormBuilder::selected($cutiSupervisor->getStatusCuti(), 'C');?>>Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDisetujui();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="disetujui" id="disetujui" value="1" <?php echo $cutiSupervisor->createCheckedDisetujui();?>/> <?php echo $appEntityLanguage->getDisetujui();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $cutiSupervisor->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="cuti_supervisor_id" value="<?php echo $cutiSupervisor->getCutiSupervisorId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->cutiSupervisorId, $inputGet->getCutiSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$cutiSupervisor = new CutiSupervisor(null, $database);
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
		"cutiId" => array(
			"columnName" => "cuti_id",
			"entityName" => "CutiMin",
			"tableName" => "cuti",
			"primaryKey" => "cuti_id",
			"objectName" => "cuti",
			"propertyName" => "cuti_dari"
		)
		);
		$cutiSupervisor->findOne($specification, null, $subqueryMap);
		if($cutiSupervisor->issetCutiSupervisorId())
		{
$appEntityLanguage = new AppEntityLanguage(new CutiSupervisor(), $appConfig, $currentUser->getLanguageId());
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
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($cutiSupervisor->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $cutiSupervisor->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $cutiSupervisor->getTanggal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td><?php echo $cutiSupervisor->issetTsk() ? $cutiSupervisor->getTsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $cutiSupervisor->issetProyek() ? $cutiSupervisor->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $cutiSupervisor->issetSupervisor() ? $cutiSupervisor->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td><?php echo $cutiSupervisor->issetJenisCuti() ? $cutiSupervisor->getJenisCuti()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCuti();?></td>
						<td><?php echo $cutiSupervisor->issetCuti() ? $cutiSupervisor->getCuti()->getCutiDari() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td><?php echo $cutiSupervisor->getKeterangan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatusCuti();?></td>
						<td><?php echo isset($mapForStatusCuti) && isset($mapForStatusCuti[$cutiSupervisor->getStatusCuti()]) && isset($mapForStatusCuti[$cutiSupervisor->getStatusCuti()]["label"]) ? $mapForStatusCuti[$cutiSupervisor->getStatusCuti()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDisetujui();?></td>
						<td><?php echo $cutiSupervisor->optionDisetujui($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td><?php echo $cutiSupervisor->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $cutiSupervisor->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->cuti_supervisor_id, $cutiSupervisor->getCutiSupervisorId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="cuti_supervisor_id" value="<?php echo $cutiSupervisor->getCutiSupervisorId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new CutiSupervisor(), $appConfig, $currentUser->getLanguageId());
$mapForStatusCuti = array(
	"P" => array("value" => "P", "label" => "Menunggu Persetujuan", "group" => "", "selected" => false),
	"A" => array("value" => "A", "label" => "Disetujui", "group" => "", "selected" => false),
	"R" => array("value" => "R", "label" => "Ditolak", "group" => "", "selected" => false),
	"C" => array("value" => "C", "label" => "Dibatalkan", "group" => "", "selected" => false)
);
$specMap = array(
	"tanggal" => PicoSpecification::filter("tanggal", "fulltext"),
	"tskId" => PicoSpecification::filter("tskId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"statusCuti" => PicoSpecification::filter("statusCuti", "fulltext"),
	"disetujui" => PicoSpecification::filter("disetujui", "boolean")
);
$sortOrderMap = array(
	"tanggal" => "tanggal",
	"tskId" => "tskId",
	"proyekId" => "proyekId",
	"supervisorId" => "supervisorId",
	"jenisCutiId" => "jenisCutiId",
	"cutiId" => "cutiId",
	"keterangan" => "keterangan",
	"statusCuti" => "statusCuti",
	"disetujui" => "disetujui",
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
		"sortBy" => "tanggal", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	),
	array(
		"sortBy" => "supervisorId", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new CutiSupervisor(null, $database);

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
"cutiId" => array(
	"columnName" => "cuti_id",
	"entityName" => "CutiMin",
	"tableName" => "cuti",
	"primaryKey" => "cuti_id",
	"objectName" => "cuti",
	"propertyName" => "cuti_dari"
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
		$appEntityLanguage->getCutiSupervisorId() => $headerFormat->getCutiSupervisorId(),
		$appEntityLanguage->getTanggal() => $headerFormat->getTanggal(),
		$appEntityLanguage->getTsk() => $headerFormat->asString(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getJenisCuti() => $headerFormat->asString(),
		$appEntityLanguage->getCuti() => $headerFormat->asString(),
		$appEntityLanguage->getKeterangan() => $headerFormat->asString(),
		$appEntityLanguage->getStatusCuti() => $headerFormat->asString(),
		$appEntityLanguage->getDisetujui() => $headerFormat->asString(),
		$appEntityLanguage->getDibayar() => $headerFormat->asString(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage, $mapForStatusCuti) {
		return array(
			sprintf("%d", $index + 1),
			$row->getCutiSupervisorId(),
			$row->getTanggal(),
			$row->issetTsk() ? $row->getTsk()->getNama() : "",
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->issetJenisCuti() ? $row->getJenisCuti()->getNama() : "",
			$row->issetCuti() ? $row->getCuti()->getCutiDari() : "",
			$row->getKeterangan(),
			isset($mapForStatusCuti) && isset($mapForStatusCuti[$row->getStatusCuti()]) && isset($mapForStatusCuti[$row->getStatusCuti()]["label"]) ? $mapForStatusCuti[$row->getStatusCuti()]["label"] : "",
			$row->optionDisetujui($appLanguage->getYes(), $appLanguage->getNo()),
			$row->optionDibayar($appLanguage->getYes(), $appLanguage->getNo()),
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
					<span class="filter-label"><?php echo $appEntityLanguage->getTanggal();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="tanggal" value="<?php echo $inputGet->getTanggal();?>" autocomplete="off"/>
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
					<span class="filter-label"><?php echo $appEntityLanguage->getDisetujui();?></span>
					<span class="filter-control">
							<select class="form-control" name="disetujui" data-value="<?php echo $inputGet->getDisetujui();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getDisetujui(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getDisetujui(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
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
								<td class="data-controll data-selector" data-key="cuti_supervisor_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-cuti-supervisor-id"/>
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
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="tsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTsk();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="jenis_cuti_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisCuti();?></a></td>
								<td data-col-name="cuti_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCuti();?></a></td>
								<td data-col-name="keterangan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKeterangan();?></a></td>
								<td data-col-name="status_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatusCuti();?></a></td>
								<td data-col-name="disetujui" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDisetujui();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($cutiSupervisor = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $cutiSupervisor->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="cuti_supervisor_id">
									<input type="checkbox" class="checkbox check-slave checkbox-cuti-supervisor-id" name="checked_row_id[]" value="<?php echo $cutiSupervisor->getCutiSupervisorId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->cuti_supervisor_id, $cutiSupervisor->getCutiSupervisorId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->cuti_supervisor_id, $cutiSupervisor->getCutiSupervisorId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="tanggal"><?php echo $cutiSupervisor->getTanggal();?></td>
								<td data-col-name="tsk_id"><?php echo $cutiSupervisor->issetTsk() ? $cutiSupervisor->getTsk()->getNama() : "";?></td>
								<td data-col-name="proyek_id"><?php echo $cutiSupervisor->issetProyek() ? $cutiSupervisor->getProyek()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $cutiSupervisor->issetSupervisor() ? $cutiSupervisor->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="jenis_cuti_id"><?php echo $cutiSupervisor->issetJenisCuti() ? $cutiSupervisor->getJenisCuti()->getNama() : "";?></td>
								<td data-col-name="cuti_id"><?php echo $cutiSupervisor->issetCuti() ? $cutiSupervisor->getCuti()->getCutiDari() : "";?></td>
								<td data-col-name="keterangan"><?php echo $cutiSupervisor->getKeterangan();?></td>
								<td data-col-name="status_cuti"><?php echo isset($mapForStatusCuti) && isset($mapForStatusCuti[$cutiSupervisor->getStatusCuti()]) && isset($mapForStatusCuti[$cutiSupervisor->getStatusCuti()]["label"]) ? $mapForStatusCuti[$cutiSupervisor->getStatusCuti()]["label"] : "";?></td>
								<td data-col-name="disetujui"><?php echo $cutiSupervisor->optionDisetujui($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="aktif"><?php echo $cutiSupervisor->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

