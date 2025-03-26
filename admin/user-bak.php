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
use Sipro\Entity\Data\Admin;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\UserLevel;
use Sipro\Entity\Data\KtskMin;
use MagicApp\XLSX\XLSXDocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "pengaturan-pengguna", "User");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$user = new Admin(null, $database);
	$user->setFirstName($inputPost->getFirstName(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setLastName($inputPost->getLastName(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setUsername($inputPost->getUsername(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setPassword($inputPost->getPassword(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setPhone($inputPost->getPhone(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setAdminTsk($inputPost->getAdminTsk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setKtskId($inputPost->getKtskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setBlocked($inputPost->getBlocked(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$user->setActive($inputPost->getActive(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$user->setAdminBuat($currentUser->getUserId());
	$user->setWaktuBuat($currentAction->getTime());
	$user->setIpBuat($currentAction->getIp());
	$user->setAdminUbah($currentUser->getUserId());
	$user->setWaktuUbah($currentAction->getTime());
	$user->setIpUbah($currentAction->getIp());
	$user->insert();
	$newId = $user->getUserId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->admin_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$user = new Admin(null, $database);
	$user->setFirstName($inputPost->getFirstName(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setLastName($inputPost->getLastName(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setUsername($inputPost->getUsername(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setPassword($inputPost->getPassword(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setPhone($inputPost->getPhone(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$user->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setAdminTsk($inputPost->getAdminTsk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setKtskId($inputPost->getKtskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->setBlocked($inputPost->getBlocked(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$user->setActive($inputPost->getActive(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$user->setAdminUbah($currentUser->getUserId());
	$user->setWaktuUbah($currentAction->getTime());
	$user->setIpUbah($currentAction->getIp());
	$user->setAdminId($inputPost->getUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$user->update();
	$newId = $user->getUserId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->admin_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$user = new Admin(null, $database);
			try
			{
				$user->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->admin_id, $rowId))
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
			$user = new Admin(null, $database);
			try
			{
				$user->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->admin_id, $rowId))
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
			$user = new Admin(null, $database);
			$user->deleteOneByUserId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getFirstName();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama_depan" id="nama_depan"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastName();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama_belakang" id="nama_belakang"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="username" id="username"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPassword();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="password" name="password" id="password"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="email" name="email" id="email"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="url" name="website" id="website"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPhone();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="tel" name="phone" id="phone"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td>
							<select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1">Laki-Laki</option>
								<option value="2">Perempuan</option>
							</select>
						</td>
					</tr>
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
						<td><?php echo $appEntityLanguage->getAdminTsk();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="admin_tsk" id="admin_tsk" value="1"/> <?php echo $appEntityLanguage->getAdminTsk();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td>
							<select class="form-control" name="ktsk_id" id="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->ktskId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlocked();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="blokir" id="blocked" value="1"/> <?php echo $appEntityLanguage->getBlocked();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getActive();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="active" id="active" value="1"/> <?php echo $appEntityLanguage->getActive();?></label>
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
	$user = new Admin(null, $database);
	try{
		$user->findOneByUserId($inputGet->getUserId());
		if($user->hasValueUserId())
		{
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getFirstName();?></td>
						<td>
							<input type="text" class="form-control" name="nama_depan" id="nama_depan" value="<?php echo $user->getFirstName();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastName();?></td>
						<td>
							<input type="text" class="form-control" name="nama_belakang" id="nama_belakang" value="<?php echo $user->getLastName();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td>
							<input type="text" class="form-control" name="username" id="username" value="<?php echo $user->getUsername();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPassword();?></td>
						<td>
							<input class="form-control" type="password" name="password" id="password" value="<?php echo $user->getPassword();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input class="form-control" type="email" name="email" id="email" value="<?php echo $user->getEmail();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td>
							<input class="form-control" type="url" name="website" id="website" value="<?php echo $user->getWebsite();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPhone();?></td>
						<td>
							<input class="form-control" type="tel" name="phone" id="phone" value="<?php echo $user->getPhone();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td>
							<select class="form-control" name="jenis_kelamin" id="jenis_kelamin" data-value="<?php echo $user->getJenisKelamin();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($user->getJenisKelamin(), '1');?>>Laki-Laki</option>
								<option value="2" <?php echo AppFormBuilder::selected($user->getJenisKelamin(), '2');?>>Perempuan</option>
							</select>
						</td>
					</tr>
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
								Field::of()->userLevelId, Field::of()->nama, $user->getUserLevelId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminTsk();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="admin_tsk" id="admin_tsk" value="1" <?php echo $user->createCheckedAdminTsk();?>/> <?php echo $appEntityLanguage->getAdminTsk();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td>
							<select class="form-control" name="ktsk_id" id="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->ktskId, Field::of()->nama, $user->getKtskId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlocked();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="blokir" id="blocked" value="1" <?php echo $user->createCheckedBlocked();?>/> <?php echo $appEntityLanguage->getBlocked();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getActive();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="active" id="active" value="1" <?php echo $user->createCheckedActive();?>/> <?php echo $appEntityLanguage->getActive();?></label>
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
							<input type="hidden" name="admin_id" value="<?php echo $user->getUserId();?>"/>
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
	$user = new Admin(null, $database);
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
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		)
		);
		$user->findOneWithPrimaryKeyValue($inputGet->getUserId(), $subqueryMap);
		if($user->hasValueUserId())
		{
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			$mapForGenderId = array(
				"1" => array("value" => "1", "label" => "Laki-Laki", "default" => "true"),
				"2" => array("value" => "2", "label" => "Perempuan", "default" => "true")
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($user->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $user->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getFirstName();?></td>
						<td><?php echo $user->getFirstName();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastName();?></td>
						<td><?php echo $user->getLastName();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td><?php echo $user->getUsername();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPassword();?></td>
						<td><?php echo $user->getPassword();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td><?php echo $user->getEmail();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td><?php echo $user->getWebsite();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPhone();?></td>
						<td><?php echo $user->getPhone();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td><?php echo isset($mapForGenderId) && isset($mapForGenderId[$user->getJenisKelamin()]) && isset($mapForGenderId[$user->getJenisKelamin()]["label"]) ? $mapForGenderId[$user->getJenisKelamin()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td><?php echo $user->hasValueUserLevel() ? $user->getUserLevel()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminTsk();?></td>
						<td><?php echo $user->optionAdminTsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td><?php echo $user->hasValueKtsk() ? $user->getKtsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlocked();?></td>
						<td><?php echo $user->optionBlocked($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastCheck();?></td>
						<td><?php echo $user->getLastCheck();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastLogin();?></td>
						<td><?php echo $user->getLastLogin();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLastIp();?></td>
						<td><?php echo $user->getLastIp();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getActive();?></td>
						<td><?php echo $user->optionActive($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVAL && UserAction::isRequireApproval($user->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($user->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($user->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->admin_id, $user->getUserId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="admin_id" value="<?php echo $user->getUserId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
$mapForGenderId = array(
	"1" => array("value" => "1", "label" => "Laki-Laki", "default" => "true"),
	"2" => array("value" => "2", "label" => "Perempuan", "default" => "true")
);
$specMap = array(
	"firstName" => PicoSpecification::filter("firstName", "fulltext"),
	"username" => PicoSpecification::filter("username", "fulltext"),
	"jenisKelamin" => PicoSpecification::filter("jenisKelamin", "fulltext"),
	"userLevelId" => PicoSpecification::filter("userLevelId", "number"),
	"ktskId" => PicoSpecification::filter("ktskId", "number"),
	"active" => PicoSpecification::filter("active", "boolean")
);
$sortOrderMap = array(
	"firstName" => "firstName",
	"lastName" => "lastName",
	"username" => "username",
	"phone" => "phone",
	"jenisKelamin" => "jenisKelamin",
	"userLevelId" => "userLevelId",
	"adminTsk" => "adminTsk",
	"ktskId" => "ktskId",
	"blocked" => "blocked",
	"active" => "active"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "nama_depan", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new Admin(null, $database);

$subqueryMap = array(
"userLevelId" => array(
	"columnName" => "user_level_id",
	"entityName" => "UserLevel",
	"tableName" => "user_level",
	"primaryKey" => "user_level_id",
	"objectName" => "user_level",
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

if($inputGet->getUserAction() == UserAction::EXPORT)
{
	$exporter = new XLSXDocumentWriter($appLanguage);
	$fileName = $currentModule->getModuleName()."-".date("Y-m-d-H-i-s").".xlsx";
	$sheetName = "Sheet 1";

	$headerFormat = new XLSXDataFormat($dataLoader, 3);
	$pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_COUNT_DATA | MagicObject::FIND_OPTION_NO_FETCH_DATA);
	$exporter->write($pageData, $fileName, $sheetName, array(
		$appLanguage->getNumero() => $headerFormat->asNumber(),
		$appEntityLanguage->getUserId() => $headerFormat->getUserId(),
		$appEntityLanguage->getFirstName() => $headerFormat->getFirstName(),
		$appEntityLanguage->getLastName() => $headerFormat->getLastName(),
		$appEntityLanguage->getUsername() => $headerFormat->getUsername(),
		$appEntityLanguage->getPassword() => $headerFormat->getPassword(),
		$appEntityLanguage->getToken() => $headerFormat->getToken(),
		$appEntityLanguage->getKodeTeller() => $headerFormat->getKodeTeller(),
		$appEntityLanguage->getEmail() => $headerFormat->getEmail(),
		$appEntityLanguage->getWebsite() => $headerFormat->getWebsite(),
		$appEntityLanguage->getPhone() => $headerFormat->getPhone(),
		$appEntityLanguage->getJenisKelamin() => $headerFormat->asString(),
		$appEntityLanguage->getUserLevel() => $headerFormat->asString(),
		$appEntityLanguage->getAdminTsk() => $headerFormat->asString(),
		$appEntityLanguage->getKtsk() => $headerFormat->asString(),
		$appEntityLanguage->getBranch() => $headerFormat->getBranch(),
		$appEntityLanguage->getSelectedBranch() => $headerFormat->getSelectedBranch(),
		$appEntityLanguage->getLanguageId() => $headerFormat->getLanguageId(),
		$appEntityLanguage->getThemeId() => $headerFormat->getThemeId(),
		$appEntityLanguage->getBlocked() => $headerFormat->asString(),
		$appEntityLanguage->getLastCheck() => $headerFormat->getLastCheck(),
		$appEntityLanguage->getLastLogin() => $headerFormat->getLastLogin(),
		$appEntityLanguage->getLastIp() => $headerFormat->getLastIp(),
		$appEntityLanguage->getQuestion() => $headerFormat->getQuestion(),
		$appEntityLanguage->getAnswer() => $headerFormat->getAnswer(),
		$appEntityLanguage->getActive() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
        global $mapForGenderId;
		return array(
			sprintf("%d", $index + 1),
			$row->getUserId(),
			$row->getFirstName(),
			$row->getLastName(),
			$row->getUsername(),
			$row->getPassword(),
			$row->getToken(),
			$row->getKodeTeller(),
			$row->getEmail(),
			$row->getWebsite(),
			$row->getPhone(),
			isset($mapForGenderId) && isset($mapForGenderId[$row->getJenisKelamin()]) && isset($mapForGenderId[$row->getJenisKelamin()]["label"]) ? $mapForGenderId[$row->getJenisKelamin()]["label"] : "",
			$row->hasValueUserLevel() ? $row->getUserLevel()->getNama() : "",
			$row->optionAdminTsk($appLanguage->getYes(), $appLanguage->getNo()),
			$row->hasValueKtsk() ? $row->getKtsk()->getNama() : "",
			$row->getBranch(),
			$row->getSelectedBranch(),
			$row->getLanguageId(),
			$row->getThemeId(),
			$row->optionBlocked($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getLastCheck(),
			$row->getLastLogin(),
			$row->getLastIp(),
			$row->getQuestion(),
			$row->getAnswer(),
			$row->optionActive($appLanguage->getYes(), $appLanguage->getNo())
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
					<span class="filter-label"><?php echo $appEntityLanguage->getFirstName();?></span>
					<span class="filter-control">
						<input type="text" name="nama_depan" class="form-control" value="<?php echo $inputGet->getFirstName();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getUsername();?></span>
					<span class="filter-control">
						<input type="text" name="username" class="form-control" value="<?php echo $inputGet->getUsername();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisKelamin();?></span>
					<span class="filter-control">
							<select name="jenis_kelamin" class="form-control" data-value="<?php echo $inputGet->getJenisKelamin();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="M" <?php echo AppFormBuilder::selected($inputGet->getJenisKelamin(), 'M');?>>Laki-Laki</option>
								<option value="W" <?php echo AppFormBuilder::selected($inputGet->getJenisKelamin(), 'W');?>>Perempuan</option>
							</select>
					</span>
				</span>
				
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
					<span class="filter-label"><?php echo $appEntityLanguage->getKtsk();?></span>
					<span class="filter-control">
							<select name="ktsk_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->ktskId, Field::of()->nama, $inputGet->getKtskId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getActive();?></span>
					<span class="filter-control">
							<select name="active" class="form-control" data-value="<?php echo $inputGet->getActive();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getActive(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getActive(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
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
								<td class="data-controll data-selector" data-key="admin_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-user-id"/>
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
								<td data-col-name="nama_depan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getFirstName();?></a></td>
								<td data-col-name="nama_belakang" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLastName();?></a></td>
								<td data-col-name="username" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUsername();?></a></td>
								<td data-col-name="phone" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPhone();?></a></td>
								<td data-col-name="jenis_kelamin" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisKelamin();?></a></td>
								<td data-col-name="user_level_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUserLevel();?></a></td>
								<td data-col-name="admin_tsk" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAdminTsk();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="blokir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBlocked();?></a></td>
								<td data-col-name="active" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getActive();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($user = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="admin_id">
									<input type="checkbox" class="checkbox check-slave checkbox-user-id" name="checked_row_id[]" value="<?php echo $user->getUserId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->admin_id, $user->getUserId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->admin_id, $user->getUserId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama_depan"><?php echo $user->getFirstName();?></td>
								<td data-col-name="nama_belakang"><?php echo $user->getLastName();?></td>
								<td data-col-name="username"><?php echo $user->getUsername();?></td>
								<td data-col-name="phone"><?php echo $user->getPhone();?></td>
								<td data-col-name="jenis_kelamin"><?php echo isset($mapForGenderId) && isset($mapForGenderId[$user->getJenisKelamin()]) && isset($mapForGenderId[$user->getJenisKelamin()]["label"]) ? $mapForGenderId[$user->getJenisKelamin()]["label"] : "";?></td>
								<td data-col-name="user_level_id"><?php echo $user->hasValueUserLevel() ? $user->getUserLevel()->getNama() : "";?></td>
								<td data-col-name="admin_tsk"><?php echo $user->optionAdminTsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="ktsk_id"><?php echo $user->hasValueKtsk() ? $user->getKtsk()->getNama() : "";?></td>
								<td data-col-name="blokir"><?php echo $user->optionBlocked($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="active"><?php echo $user->optionActive($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

