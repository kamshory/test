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
use Sipro\Entity\Data\Cuti;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\JenisCuti;
use Sipro\Entity\Data\JenisCutiMin;
use Sipro\Entity\Data\ProyekMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "cuti", "Cuti");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$cuti = new Cuti(null, $database);
	$cuti->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setJenisCutiId($inputPost->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setDibayar($inputPost->getDibayar(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setCutiDari($inputPost->getCutiDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setCutiHingga($inputPost->getCutiHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setHariCuti($inputPost->getHariCuti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setAlasan($inputPost->getAlasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setSupervisorPengganti($inputPost->getSupervisorPengganti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setStatus($inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setWaktuApprove($inputPost->getWaktuApprove(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setIpApprove($inputPost->getIpApprove(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setAdminApprove($inputPost->getAdminApprove(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cuti->setAdminBuat($currentUser->getUserId());
	$cuti->setWaktuBuat($currentAction->getTime());
	$cuti->setIpBuat($currentAction->getIp());
	$cuti->setAdminUbah($currentUser->getUserId());
	$cuti->setWaktuUbah($currentAction->getTime());
	$cuti->setIpUbah($currentAction->getIp());
	$cuti->insert();
	$newId = $cuti->getCutiId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$cuti = new Cuti(null, $database);
	$cuti->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setJenisCutiId($inputPost->getJenisCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setDibayar($inputPost->getDibayar(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setCutiDari($inputPost->getCutiDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setCutiHingga($inputPost->getCutiHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setHariCuti($inputPost->getHariCuti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setAlasan($inputPost->getAlasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setSupervisorPengganti($inputPost->getSupervisorPengganti(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setStatus($inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setWaktuApprove($inputPost->getWaktuApprove(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setIpApprove($inputPost->getIpApprove(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$cuti->setAdminApprove($inputPost->getAdminApprove(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$cuti->setAdminUbah($currentUser->getUserId());
	$cuti->setWaktuUbah($currentAction->getTime());
	$cuti->setIpUbah($currentAction->getIp());
	$cuti->setCutiId($inputPost->getCutiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$cuti->update();
	$newId = $cuti->getCutiId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->cuti_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$cuti = new Cuti(null, $database);
			try
			{
				$cuti->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cuti_id, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
				)
				->setAktif(true)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here when record is not found
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
			$cuti = new Cuti(null, $database);
			try
			{
				$cuti->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cuti_id, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
				)
				->setAktif(false)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here when record is not found
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
			$cuti = new Cuti(null, $database);
			$cuti->deleteOneByCutiId($rowId);
		}
	}
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
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td>
							<select class="form-control" name="jenis_cuti_id" id="jenis_cuti_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisCuti(null, $database), 
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
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiDari();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="cuti_dari" id="cuti_dari"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiHingga();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="cuti_hingga" id="cuti_hingga"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHariCuti();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="hari_cuti" id="hari_cuti"/>
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
							<select class="form-control" name="supervisor_pengganti" id="supervisor_pengganti">
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
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td>
							<select class="form-control" name="status" id="status">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P">Tertunda</option>
								<option value="A">Diterima</option>
								<option value="R">Ditolak</option>
								<option value="O">Dianulir</option>
								<option value="C">Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuApprove();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_approve" id="waktu_approve"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpApprove();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="ip_approve" id="ip_approve"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminApprove();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="admin_approve" id="admin_approve"/>
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
	$cuti = new Cuti(null, $database);
	try{
		$cuti->findOneByCutiId($inputGet->getCutiId());
		if($cuti->hasValueCutiId())
		{
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
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
								Field::of()->supervisorId, Field::of()->nama, $cuti->getSupervisorId())
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td>
							<select class="form-control" name="jenis_cuti_id" id="jenis_cuti_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisCuti(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisCutiId, Field::of()->nama, $cuti->getJenisCutiId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dibayar" id="dibayar" value="1" <?php echo $cuti->createCheckedDibayar();?>/> <?php echo $appEntityLanguage->getDibayar();?></label>
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
									->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $cuti->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiDari();?></td>
						<td>
							<input class="form-control" type="date" name="cuti_dari" id="cuti_dari" value="<?php echo $cuti->getCutiDari();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCutiHingga();?></td>
						<td>
							<input class="form-control" type="date" name="cuti_hingga" id="cuti_hingga" value="<?php echo $cuti->getCutiHingga();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHariCuti();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="hari_cuti" id="hari_cuti" value="<?php echo $cuti->getHariCuti();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlasan();?></td>
						<td>
							<textarea class="form-control" name="alasan" id="alasan" spellcheck="false"><?php echo $cuti->getAlasan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorPengganti();?></td>
						<td>
							<select class="form-control" name="supervisor_pengganti" id="supervisor_pengganti">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $cuti->getSupervisorPengganti())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td>
							<select class="form-control" name="status" id="status" data-value="<?php echo $cuti->getStatus();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P" <?php echo AppFormBuilder::selected($cuti->getStatus(), 'P');?>>Tertunda</option>
								<option value="A" <?php echo AppFormBuilder::selected($cuti->getStatus(), 'A');?>>Diterima</option>
								<option value="R" <?php echo AppFormBuilder::selected($cuti->getStatus(), 'R');?>>Ditolak</option>
								<option value="O" <?php echo AppFormBuilder::selected($cuti->getStatus(), 'O');?>>Dianulir</option>
								<option value="C" <?php echo AppFormBuilder::selected($cuti->getStatus(), 'C');?>>Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuApprove();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_approve" id="waktu_approve" value="<?php echo $cuti->getWaktuApprove();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpApprove();?></td>
						<td>
							<input class="form-control" type="text" name="ip_approve" id="ip_approve" value="<?php echo $cuti->getIpApprove();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminApprove();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="admin_approve" id="admin_approve" value="<?php echo $cuti->getAdminApprove();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $cuti->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="cuti_id" value="<?php echo $cuti->getCutiId();?>"/>
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
	$cuti = new Cuti(null, $database);
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
		"jenisCutiId" => array(
			"columnName" => "jenis_cuti_id",
			"entityName" => "JenisCuti",
			"tableName" => "jenis_cuti",
			"primaryKey" => "jenis_cuti_id",
			"objectName" => "jenis_cuti",
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
		"supervisorPengganti" => array(
			"columnName" => "supervisor_pengganti",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "pengganti",
			"propertyName" => "nama"
		), 
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "User",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pembuat",
			"propertyName" => "first_name"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "User",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pengubah",
			"propertyName" => "first_name"
		)
		);
		$cuti->findOneWithPrimaryKeyValue($inputGet->getCutiId(), $subqueryMap);
		if($cuti->hasValueCutiId())
		{
$appEntityLanguage = new AppEntityLanguage(new Cuti(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			$mapForStatus = array(
				"P" => array("value" => "P", "label" => "Tertunda", "default" => "false"),
				"A" => array("value" => "A", "label" => "Diterima", "default" => "false"),
				"R" => array("value" => "R", "label" => "Ditolak", "default" => "false"),
				"O" => array("value" => "O", "label" => "Dianulir", "default" => "false"),
				"C" => array("value" => "C", "label" => "Dibatalkan", "default" => "false")
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
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $cuti->hasValueSupervisor() ? $cuti->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisCuti();?></td>
						<td><?php echo $cuti->hasValueJenisCuti() ? $cuti->getJenisCuti()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td><?php echo $cuti->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $cuti->hasValueProyek() ? $cuti->getProyek()->getNama() : "";?></td>
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
						<td><?php echo $appEntityLanguage->getAlasan();?></td>
						<td><?php echo $cuti->getAlasan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorPengganti();?></td>
						<td><?php echo $cuti->hasValuePengganti() ? $cuti->getPengganti()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td><?php echo isset($mapForStatus) && isset($mapForStatus[$cuti->getStatus()]) && isset($mapForStatus[$cuti->getStatus()]["label"]) ? $mapForStatus[$cuti->getStatus()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $cuti->getWaktuBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $cuti->getWaktuUbah();?></td>
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
						<td><?php echo $cuti->hasValuePembuat() ? $cuti->getPembuat()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $cuti->hasValuePengubah() ? $cuti->getPengubah()->getFirstName() : "";?></td>
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
							<?php if($inputGet->getNextAction() == UserAction::APPROVAL && UserAction::isRequireApproval($cuti->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($cuti->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($cuti->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
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
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisor();?></span>
					<span class="filter-control">
							<select name="supervisor_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorId())
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								->setIndent(8)
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisCuti();?></span>
					<span class="filter-control">
							<select name="jenis_cuti_id" class="form-control">
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
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select name="proyek_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getStatus();?></span>
					<span class="filter-control">
							<select name="status" class="form-control" data-value="<?php echo $inputGet->getStatus();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="P" <?php echo AppFormBuilder::selected($inputGet->getStatus(), 'P');?>>Tertunda</option>
								<option value="A" <?php echo AppFormBuilder::selected($inputGet->getStatus(), 'A');?>>Diterima</option>
								<option value="R" <?php echo AppFormBuilder::selected($inputGet->getStatus(), 'R');?>>Ditolak</option>
								<option value="O" <?php echo AppFormBuilder::selected($inputGet->getStatus(), 'O');?>>Dianulir</option>
								<option value="C" <?php echo AppFormBuilder::selected($inputGet->getStatus(), 'C');?>>Dibatalkan</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getAktif();?></span>
					<span class="filter-control">
							<select name="aktif" class="form-control" data-value="<?php echo $inputGet->getAktif();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getAktif(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getAktif(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
				</span>
				<?php } ?>
			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php 	
			$mapForStatus = array(
				"P" => array("value" => "P", "label" => "Tertunda", "default" => "false"),
				"A" => array("value" => "A", "label" => "Diterima", "default" => "false"),
				"R" => array("value" => "R", "label" => "Ditolak", "default" => "false"),
				"O" => array("value" => "O", "label" => "Dianulir", "default" => "false"),
				"C" => array("value" => "C", "label" => "Dibatalkan", "default" => "false")
			);
			$specMap = array(
			    "supervisorId" => PicoSpecification::filter("supervisorId", "number"),
				"jenisCutiId" => PicoSpecification::filter("jenisCutiId", "number"),
				"proyekId" => PicoSpecification::filter("proyekId", "number"),
				"status" => PicoSpecification::filter("status", "fulltext"),
				"aktif" => PicoSpecification::filter("aktif", "boolean")
			);
			$sortOrderMap = array(
			    "supervisorId" => "supervisorId",
				"jenisCutiId" => "jenisCutiId",
				"dibayar" => "dibayar",
				"proyekId" => "proyekId",
				"cutiDari" => "cutiDari",
				"cutiHingga" => "cutiHingga",
				"hariCuti" => "hariCuti",
				"supervisorPengganti" => "supervisorPengganti",
				"status" => "status",
				"waktuApprove" => "waktuApprove",
				"adminApprove" => "adminApprove",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "cutiDari", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new Cuti(null, $database);
			
			$subqueryMap = array(
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
				"entityName" => "JenisCuti",
				"tableName" => "jenis_cuti",
				"primaryKey" => "jenis_cuti_id",
				"objectName" => "jenis_cuti",
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
			"supervisorPengganti" => array(
				"columnName" => "supervisor_pengganti",
				"entityName" => "SupervisorMin",
				"tableName" => "supervisor",
				"primaryKey" => "supervisor_id",
				"objectName" => "pengganti",
				"propertyName" => "nama"
			), 
			"adminBuat" => array(
				"columnName" => "admin_buat",
				"entityName" => "User",
				"tableName" => "user",
				"primaryKey" => "user_id",
				"objectName" => "pembuat",
				"propertyName" => "first_name"
			), 
			"adminUbah" => array(
				"columnName" => "admin_ubah",
				"entityName" => "User",
				"tableName" => "user",
				"primaryKey" => "user_id",
				"objectName" => "pengubah",
				"propertyName" => "first_name"
			)
			);
			try{
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
								<td class="data-controll data-selector" data-key="cuti_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-cuti-id"/>
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
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="jenis_cuti_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisCuti();?></a></td>
								<td data-col-name="dibayar" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDibayar();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="cuti_dari" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiDari();?></a></td>
								<td data-col-name="cuti_hingga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getCutiHingga();?></a></td>
								<td data-col-name="hari_cuti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHariCuti();?></a></td>
								<td data-col-name="supervisor_pengganti" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisorPengganti();?></a></td>
								<td data-col-name="status" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatus();?></a></td>
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
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="cuti_id">
									<input type="checkbox" class="checkbox check-slave checkbox-cuti-id" name="checked_row_id[]" value="<?php echo $cuti->getCutiId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->cuti_id, $cuti->getCutiId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->cuti_id, $cuti->getCutiId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="supervisor_id"><?php echo $cuti->hasValueSupervisor() ? $cuti->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="jenis_cuti_id"><?php echo $cuti->hasValueJenisCuti() ? $cuti->getJenisCuti()->getNama() : "";?></td>
								<td data-col-name="dibayar"><?php echo $cuti->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="proyek_id"><?php echo $cuti->hasValueProyek() ? $cuti->getProyek()->getNama() : "";?></td>
								<td data-col-name="cuti_dari"><?php echo $cuti->getCutiDari();?></td>
								<td data-col-name="cuti_hingga"><?php echo $cuti->getCutiHingga();?></td>
								<td data-col-name="hari_cuti"><?php echo $cuti->getHariCuti();?></td>
								<td data-col-name="supervisor_pengganti"><?php echo $cuti->hasValuePengganti() ? $cuti->getPengganti()->getNama() : "";?></td>
								<td data-col-name="status"><?php echo isset($mapForStatus) && isset($mapForStatus[$cuti->getStatus()]) && isset($mapForStatus[$cuti->getStatus()]["label"]) ? $mapForStatus[$cuti->getStatus()]["label"] : "";?></td>
								<td data-col-name="waktu_approve"><?php echo $cuti->getWaktuApprove();?></td>
								<td data-col-name="admin_approve"><?php echo $cuti->getAdminApprove();?></td>
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

