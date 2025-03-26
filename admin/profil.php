<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

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
use Sipro\Entity\Data\Admin;
use Sipro\Entity\Data\UserLevelMin;
use Sipro\Entity\Data\Tsk;
use Sipro\Entity\Data\UmkMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "profil-pengguna", $appLanguage->getProfilPengguna());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

$dataFilter = PicoSpecification::getInstance()
->addAnd(['adminId', $currentUser->getAdminId()]);

if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$adminId = $inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
	$specification = PicoSpecification::getInstanceOf(Field::of()->adminId, $adminId);
	$specification->addAnd($dataFilter);
	$admin = new Admin(null, $database);
	$updater = $admin->where($specification)
		->setNamaDepan($inputPost->getNamaDepan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setNamaBelakang($inputPost->getNamaBelakang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLangId($inputPost->getLangId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLangId($inputPost->getLangId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
	;

	$updater->setNama($inputPost->getNamaDepan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true) . " " . $inputPost->getNamaBelakang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));

	$password = $inputPost->getPassword(PicoFilterConstant::FILTER_DEFAULT, false, false, true);
	$password1 = sha1(sha1($password));
	if($password != "")
	{
		// Update password if not empty
		$updater->setPassword($password1);
	}
	if($currentUser->getUserId() == $adminId)
	{
		
		if($password != "")
		{
			// Set password to session if current user and password not empty
			$sessions->adminPassword = $password1;
		}

		// Set blokir to false if current user
		$updater->setBlokir(false);
		$updater->setAktif(true);
	}

	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());

	try
	{
		$updater->update();
		$newId = $adminId;

		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->admin_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}

if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstance();
	$specification->addAnd($dataFilter);
	$admin = new Admin(null, $database);
	try{
		$admin->findOne($specification);
		if($admin->issetAdminId())
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
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td>
							<input type="text" class="form-control" name="username" id="username" value="<?php echo $admin->getUsername();?>" autocomplete="off" readonly/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPassword();?></td>
						<td>
							<input type="password" class="form-control" name="password" id="password" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDepan();?></td>
						<td>
							<input type="text" class="form-control" name="nama_depan" id="nama_depan" value="<?php echo $admin->getNamaDepan();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaBelakang();?></td>
						<td>
							<input type="text" class="form-control" name="nama_belakang" id="nama_belakang" value="<?php echo $admin->getNamaBelakang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input type="email" class="form-control" name="email" id="email" value="<?php echo $admin->getEmail();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input type="tel" class="form-control" name="telepon" id="telepon" value="<?php echo $admin->getTelepon();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td>
							<select class="form-control" name="jenis_kelamin" id="jenis_kelamin" data-value="<?php echo $admin->getJenisKelamin();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="L" <?php echo AppFormBuilder::selected($admin->getJenisKelamin(), 'L');?>>Laki-Laki</option>
								<option value="P" <?php echo AppFormBuilder::selected($admin->getJenisKelamin(), 'P');?>>Perempuan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLangId();?></td>
						<td>
							<select class="form-control" name="lang_id" id="lang_id" data-value="<?php echo $admin->getLangId();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="id" <?php echo AppFormBuilder::selected($admin->getLangId(), 'id');?>>Bahasa Indonesia</option>
								<option value="en" <?php echo AppFormBuilder::selected($admin->getLangId(), 'en');?>>English</option>
							</select>
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
							<input type="hidden" name="admin_id" value="<?php echo $admin->getAdminId();?>"/>
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
else 
{
	$specification = PicoSpecification::getInstance();
	$specification->addAnd($dataFilter);
	$admin = new Admin(null, $database);
	try{
		$subqueryMap = array(
		"userLevelId" => array(
			"columnName" => "user_level_id",
			"entityName" => "UserLevelMin",
			"tableName" => "user_level",
			"primaryKey" => "user_level_id",
			"objectName" => "user_level",
			"propertyName" => "nama"
		), 
		"umkId" => array(
			"columnName" => "umk_id",
			"entityName" => "UmkMin",
			"tableName" => "umk",
			"primaryKey" => "umk_id",
			"objectName" => "umk",
			"propertyName" => "nama"
		), 
		"tskId" => array(
			"columnName" => "tsk_id",
			"entityName" => "Tsk",
			"tableName" => "tsk",
			"primaryKey" => "tsk_id",
			"objectName" => "tsk",
			"propertyName" => "nama"
		), 
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "Ktsk",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
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
		$admin->findOne($specification, null, $subqueryMap);
		if($admin->issetAdminId())
		{
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			$mapForJenisKelamin = array(
				"L" => array("value" => "L", "label" => "Laki-Laki", "group" => "", "selected" => false),
				"P" => array("value" => "P", "label" => "Perempuan", "group" => "", "selected" => false)
			);
			$mapForTipePengguna = array(
				"admin" => array("value" => "admin", "label" => "Administrator", "group" => "", "selected" => false),
				"ktsk" => array("value" => "ktsk", "label" => "KTSK", "group" => "", "selected" => false),
				"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "group" => "", "selected" => false)
			);
			$mapForLangId = array(
				"id" => array("value" => "id", "label" => "Bahasa Indonesia", "group" => "", "selected" => false),
				"en" => array("value" => "en", "label" => "English", "group" => "", "selected" => false)
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($admin->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $admin->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td><?php echo $admin->getUsername();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $admin->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td><?php echo $admin->getEmail();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td><?php echo $admin->getTelepon();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td><?php echo isset($mapForJenisKelamin) && isset($mapForJenisKelamin[$admin->getJenisKelamin()]) && isset($mapForJenisKelamin[$admin->getJenisKelamin()]["label"]) ? $mapForJenisKelamin[$admin->getJenisKelamin()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td><?php echo $admin->issetUserLevel() ? $admin->getUserLevel()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePengguna();?></td>
						<td><?php echo isset($mapForTipePengguna) && isset($mapForTipePengguna[$admin->getTipePengguna()]) && isset($mapForTipePengguna[$admin->getTipePengguna()]["label"]) ? $mapForTipePengguna[$admin->getTipePengguna()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUmk();?></td>
						<td><?php echo $admin->issetUmk() ? $admin->getUmk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTsk();?></td>
						<td><?php echo $admin->issetTsk() ? $admin->getTsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td><?php echo $admin->issetKtsk() ? $admin->getKtsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $admin->issetSupervisor() ? $admin->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLangId();?></td>
						<td><?php echo isset($mapForLangId) && isset($mapForLangId[$admin->getLangId()]) && isset($mapForLangId[$admin->getLangId()]["label"]) ? $mapForLangId[$admin->getLangId()]["label"] : "";?></td>
					</tr>

					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $admin->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $admin->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>


				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->admin_id, $admin->getAdminId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>		
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
