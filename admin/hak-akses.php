<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/AppBuilder

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
use Sipro\Entity\Data\HakAkses;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\UserLevel;
use Sipro\Entity\Data\Modul;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "pengaturan-hak-akses", $appLanguage->getPengaturanHakAkses());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$hakAkses = new HakAkses(null, $database);
	$hakAkses->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setModulId($inputPost->getModulId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setKodeModul($inputPost->getKodeModul(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$hakAkses->setAllowedList($inputPost->getAllowedList(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedDetail($inputPost->getAllowedDetail(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedCreate($inputPost->getAllowedCreate(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedUpdate($inputPost->getAllowedUpdate(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedDelete($inputPost->getAllowedDelete(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedApprove($inputPost->getAllowedApprove(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedSortOrder($inputPost->getAllowedSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$hakAkses->setAdminBuat($currentUser->getUserId());
	$hakAkses->setWaktuBuat($currentAction->getTime());
	$hakAkses->setIpBuat($currentAction->getIp());
	$hakAkses->setAdminUbah($currentUser->getUserId());
	$hakAkses->setWaktuUbah($currentAction->getTime());
	$hakAkses->setIpUbah($currentAction->getIp());
	$hakAkses->insert();
	$newId = $hakAkses->getHakAksesId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->hak_akses_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$hakAkses = new HakAkses(null, $database);
	$hakAkses->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setModulId($inputPost->getModulId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setKodeModul($inputPost->getKodeModul(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$hakAkses->setAllowedList($inputPost->getAllowedList(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedDetail($inputPost->getAllowedDetail(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedCreate($inputPost->getAllowedCreate(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedUpdate($inputPost->getAllowedUpdate(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedDelete($inputPost->getAllowedDelete(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedApprove($inputPost->getAllowedApprove(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAllowedSortOrder($inputPost->getAllowedSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$hakAkses->setAdminUbah($currentUser->getUserId());
	$hakAkses->setWaktuUbah($currentAction->getTime());
	$hakAkses->setIpUbah($currentAction->getIp());
	$hakAkses->setHakAksesId($inputPost->getHakAksesId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$hakAkses->update();
	$newId = $hakAkses->getHakAksesId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->hak_akses_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$hakAkses = new HakAkses(null, $database);
			$hakAkses->setHakAksesId($rowId)->setAktif(true)->update();
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
			$hakAkses = new HakAkses(null, $database);
			$hakAkses->setHakAksesId($rowId)->setAktif(false)->update();
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
			$hakAkses = new HakAkses(null, $database);
			$hakAkses->deleteOneByHakAksesId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new HakAkses(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td>
							<select class="form-control" name="user_level_id" id="user_level_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevel(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userLevelId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getModul();?></td>
						<td>
							<select class="form-control" name="modul_id" id="modul_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Modul(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->modulId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeModul();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="kode_modul" id="kode_modul"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedList();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_list" id="allowed_list" value="1"/> <?php echo $appEntityLanguage->getAllowedList();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDetail();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_detail" id="allowed_detail" value="1"/> <?php echo $appEntityLanguage->getAllowedDetail();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedCreate();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_create" id="allowed_create" value="1"/> <?php echo $appEntityLanguage->getAllowedCreate();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedUpdate();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_update" id="allowed_update" value="1"/> <?php echo $appEntityLanguage->getAllowedUpdate();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDelete();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_delete" id="allowed_delete" value="1"/> <?php echo $appEntityLanguage->getAllowedDelete();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedApprove();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_approve" id="allowed_approve" value="1"/> <?php echo $appEntityLanguage->getAllowedApprove();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedSortOrder();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_sort_order" id="allowed_sort_order" value="1"/> <?php echo $appEntityLanguage->getAllowedSortOrder();?></label>
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
	$hakAkses = new HakAkses(null, $database);
	try{
		$hakAkses->findOneByHakAksesId($inputGet->getHakAksesId());
		if($hakAkses->hasValueHakAksesId())
		{
$appEntityLanguage = new AppEntityLanguage(new HakAkses(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td>
							<select class="form-control" name="user_level_id" id="user_level_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevel(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userLevelId, Field::of()->nama, $hakAkses->getUserLevelId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getModul();?></td>
						<td>
							<select class="form-control" name="modul_id" id="modul_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Modul(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->modulId, Field::of()->nama, $hakAkses->getModulId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeModul();?></td>
						<td>
							<input type="text" class="form-control" name="kode_modul" id="kode_modul" value="<?php echo $hakAkses->getKodeModul();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedList();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_list" id="allowed_list" value="1" <?php echo $hakAkses->createCheckedAllowedList();?>/> <?php echo $appEntityLanguage->getAllowedList();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDetail();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_detail" id="allowed_detail" value="1" <?php echo $hakAkses->createCheckedAllowedDetail();?>/> <?php echo $appEntityLanguage->getAllowedDetail();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedCreate();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_create" id="allowed_create" value="1" <?php echo $hakAkses->createCheckedAllowedCreate();?>/> <?php echo $appEntityLanguage->getAllowedCreate();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedUpdate();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_update" id="allowed_update" value="1" <?php echo $hakAkses->createCheckedAllowedUpdate();?>/> <?php echo $appEntityLanguage->getAllowedUpdate();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDelete();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_delete" id="allowed_delete" value="1" <?php echo $hakAkses->createCheckedAllowedDelete();?>/> <?php echo $appEntityLanguage->getAllowedDelete();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedApprove();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_approve" id="allowed_approve" value="1" <?php echo $hakAkses->createCheckedAllowedApprove();?>/> <?php echo $appEntityLanguage->getAllowedApprove();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedSortOrder();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="allowed_sort_order" id="allowed_sort_order" value="1" <?php echo $hakAkses->createCheckedAllowedSortOrder();?>/> <?php echo $appEntityLanguage->getAllowedSortOrder();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $hakAkses->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="hak_akses_id" value="<?php echo $hakAkses->getHakAksesId();?>"/>
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
	$hakAkses = new HakAkses(null, $database);
	try{
		$subqueryMap = array(
		"userLevelId" => array(
			"columnName" => "user_level_id",
			"entityName" => "UserLevel",
			"tableName" => "user_level",
			"primaryKey" => "user_level_id",
			"objectName" => "user_level",
			"propertyName" => "nama"
		), 
		"modulId" => array(
			"columnName" => "modul_id",
			"entityName" => "Modul",
			"tableName" => "modul",
			"primaryKey" => "modul_id",
			"objectName" => "modul",
			"propertyName" => "nama"
		)
		);
		$hakAkses->findOneWithPrimaryKeyValue($inputGet->getHakAksesId(), $subqueryMap);
		if($hakAkses->hasValueHakAksesId())
		{
$appEntityLanguage = new AppEntityLanguage(new HakAkses(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td><?php echo $hakAkses->hasValueUserLevel() ? $hakAkses->getUserLevel()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getModul();?></td>
						<td><?php echo $hakAkses->hasValueModul() ? $hakAkses->getModul()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeModul();?></td>
						<td><?php echo $hakAkses->getKodeModul();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedList();?></td>
						<td><?php echo $hakAkses->optionAllowedList($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDetail();?></td>
						<td><?php echo $hakAkses->optionAllowedDetail($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedCreate();?></td>
						<td><?php echo $hakAkses->optionAllowedCreate($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedUpdate();?></td>
						<td><?php echo $hakAkses->optionAllowedUpdate($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDelete();?></td>
						<td><?php echo $hakAkses->optionAllowedDelete($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedApprove();?></td>
						<td><?php echo $hakAkses->optionAllowedApprove($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedSortOrder();?></td>
						<td><?php echo $hakAkses->optionAllowedSortOrder($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $hakAkses->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?><button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->hak_akses_id, $hakAkses->getHakAksesId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button><?php } ?>&#xD;
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
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
$appEntityLanguage = new AppEntityLanguage(new HakAkses(), $appConfig, $currentUser->getLanguageId());
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getUserLevel();?></span>
					<span class="filter-control">
							<select name="user_level_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevel(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userLevelId, Field::of()->nama, $inputGet->getUserLevelId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getModul();?></span>
					<span class="filter-control">
							<select name="modul_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Modul(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->modulId, Field::of()->nama, $inputGet->getModulId())
								; ?>
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
			
			$specMap = array(
			    "userLevelId" => PicoSpecification::filter("userLevelId", "number"),
				"modulId" => PicoSpecification::filter("modulId", "number")
			);
			$sortOrderMap = array(
			    "userLevelId" => "userLevelId",
				"modulId" => "modulId",
				"kodeModul" => "kodeModul",
				"allowedList" => "allowedList",
				"allowedDetail" => "allowedDetail",
				"allowedCreate" => "allowedCreate",
				"allowedUpdate" => "allowedUpdate",
				"allowedDelete" => "allowedDelete",
				"allowedApprove" => "allowedApprove",
				"allowedSortOrder" => "allowedSortOrder",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, null);
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new HakAkses(null, $database);
			
			$subqueryMap = array(
			"userLevelId" => array(
				"columnName" => "user_level_id",
				"entityName" => "UserLevel",
				"tableName" => "user_level",
				"primaryKey" => "user_level_id",
				"objectName" => "user_level",
				"propertyName" => "nama"
			), 
			"modulId" => array(
				"columnName" => "modul_id",
				"entityName" => "Modul",
				"tableName" => "modul",
				"primaryKey" => "modul_id",
				"objectName" => "modul",
				"propertyName" => "nama"
			)
			);
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
								<td class="data-controll data-selector" data-key="hak_akses_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-hak-akses-id"/>
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
								<td data-col-name="user_level_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUserLevel();?></a></td>
								<td data-col-name="modul_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getModul();?></a></td>
								<td data-col-name="kode_modul" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKodeModul();?></a></td>
								<td data-col-name="allowed_list" class="order-controll"><?php echo $appEntityLanguage->getAllowedList();?></td>
								<td data-col-name="allowed_detail" class="order-controll"><?php echo $appEntityLanguage->getAllowedDetail();?></td>
								<td data-col-name="allowed_create" class="order-controll"><?php echo $appEntityLanguage->getAllowedCreate();?></td>
								<td data-col-name="allowed_update" class="order-controll"><?php echo $appEntityLanguage->getAllowedUpdate();?></td>
								<td data-col-name="allowed_delete" class="order-controll"><?php echo $appEntityLanguage->getAllowedDelete();?></td>
								<td data-col-name="allowed_approve" class="order-controll"><?php echo $appEntityLanguage->getAllowedApprove();?></td>
								<td data-col-name="allowed_sort_order" class="order-controll"><?php echo $appEntityLanguage->getAllowedSortOrder();?></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($hakAkses = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="hak_akses_id">
									<input type="checkbox" class="checkbox check-slave checkbox-hak-akses-id" name="checked_row_id[]" value="<?php echo $hakAkses->getHakAksesId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->hak_akses_id, $hakAkses->getHakAksesId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->hak_akses_id, $hakAkses->getHakAksesId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="user_level_id"><?php echo $hakAkses->hasValueUserLevel() ? $hakAkses->getUserLevel()->getNama() : "";?></td>
								<td data-col-name="modul_id"><?php echo $hakAkses->hasValueModul() ? $hakAkses->getModul()->getNama() : "";?></td>
								<td data-col-name="kode_modul"><?php echo $hakAkses->getKodeModul();?></td>
								<td data-col-name="allowed_list"><?php echo $hakAkses->optionAllowedList($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_detail"><?php echo $hakAkses->optionAllowedDetail($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_create"><?php echo $hakAkses->optionAllowedCreate($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_update"><?php echo $hakAkses->optionAllowedUpdate($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_delete"><?php echo $hakAkses->optionAllowedDelete($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_approve"><?php echo $hakAkses->optionAllowedApprove($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="allowed_sort_order"><?php echo $hakAkses->optionAllowedSortOrder($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
			<?php /*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){ ?>
		</div>
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}

