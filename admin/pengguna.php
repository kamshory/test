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
use Sipro\Entity\Data\Admin;
use Sipro\Entity\Data\UserLevelMin;
use Sipro\Entity\Data\KtskMin;
use Sipro\Entity\Data\SupervisorMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "pengguna", $appLanguage->getPengguna());
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
	$admin = new Admin(null, $database);
	$admin->setUsername($inputPost->getUsername(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));

	$password = $inputPost->getPassword(PicoFilterConstant::FILTER_DEFAULT, false, false, true);
	$namaDepan = $inputPost->getNamaDepan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$namaBelakang = $inputPost->getNamaBelakang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$password1 = sha1(sha1($password));

	$nama = trim(trim($namaDepan) . " " . $namaBelakang);

	$admin->setPassword($password1);
	$admin->setNamaDepan($namaDepan);
	$admin->setNamaBelakang($namaBelakang);
	$admin->setNama($nama);

	$admin->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$admin->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$admin->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$admin->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$admin->setTipePengguna($inputPost->getTipePengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$admin->setKtskId($inputPost->getKtskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$admin->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$admin->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$admin->setBlokir($inputPost->getBlokir(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$admin->setAdminBuat($currentAction->getUserId());
	$admin->setWaktuBuat($currentAction->getTime());
	$admin->setIpBuat($currentAction->getIp());
	$admin->setAdminUbah($currentAction->getUserId());
	$admin->setWaktuUbah($currentAction->getTime());
	$admin->setIpUbah($currentAction->getIp());
	try
	{
		$admin->insert();
		$newId = $admin->getAdminId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->admin_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->adminId, $inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$admin = new Admin(null, $database);

	$password = $inputPost->getPassword(PicoFilterConstant::FILTER_DEFAULT, false, false, true);
	$username = $inputPost->getUsername(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$namaDepan = $inputPost->getNamaDepan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$namaBelakang = $inputPost->getNamaBelakang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$password1 = sha1(sha1($password));

	$nama = trim(trim($namaDepan) . " " . $namaBelakang);

	$updater = $admin->where($specification)
		->setUsername($username)
		->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setUserLevelId($inputPost->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
	;

	// Update password
	if(!empty($password))
	{
		$updater->setPassword($password1);
	}

	// Update user properties
	if($currentAction->getUserId() == $inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT))
	{
		$updater->setAktif(true);
		$updater->setBlokir(false);

		$sessions->adminUsername = $username;
		if(!empty($password))
		{
			$sessions->adminPassword = sha1($password);
		}
	}

	$updater->setNamaDepan($namaDepan);
	$updater->setNamaBelakang($namaBelakang);
	$updater->setNama($nama);


	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->admin_id, $newId);
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
			$admin = new Admin(null, $database);
			try
			{
				$admin->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->adminId, $rowId))
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
			$admin = new Admin(null, $database);
			try
			{
				$admin->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->adminId, $rowId))
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->adminId, $rowId))
					->addAnd($dataFilter)
					;
				$admin = new Admin(null, $database);
				$admin->where($specification)
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
$appEntityLanguage = new AppEntityLanguage(new Admin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUsername();?></td>
						<td>
							<input type="text" class="form-control" name="username" id="username" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPassword();?></td>
						<td>
							<input type="password" class="form-control" name="password" id="password" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDepan();?></td>
						<td>
							<input type="text" class="form-control" name="nama_depan" id="nama_depan" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaBelakang();?></td>
						<td>
							<input type="text" class="form-control" name="nama_belakang" id="nama_belakang" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input type="text" class="form-control" name="email" id="email" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input type="text" class="form-control" name="telepon" id="telepon" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisKelamin();?></td>
						<td>
							<select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="L" selected="selected">Laki-Laki</option>
								<option value="P">Perempuan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td>
							<select class="form-control" name="user_level_id" id="user_level_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevelMin(null, $database), 
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
						<td><?php echo $appEntityLanguage->getTipePengguna();?></td>
						<td>
							<select class="form-control" name="tipe_pengguna" id="tipe_pengguna">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="admin">Administrator</option>
								<option value="ktsk">KTSK</option>
								<option value="supervisor">Supervisor</option>
							</select>
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
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1"/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlokir();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="blokir" id="blokir" value="1"/> <?php echo $appEntityLanguage->getBlokir();?></label>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->adminId, $inputGet->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
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
							<input type="text" class="form-control" name="username" id="username" value="<?php echo $admin->getUsername();?>"<?php echo $admin->notEqualsTipePengguna("admin") ? ' readonly' : '';?> autocomplete="off" required="required"/>
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
							<input type="text" class="form-control" name="nama_depan" id="nama_depan" value="<?php echo $admin->getNamaDepan();?>"<?php echo $admin->notEqualsTipePengguna("admin") ? ' readonly' : '';?> autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaBelakang();?></td>
						<td>
							<input type="text" class="form-control" name="nama_belakang" id="nama_belakang" value="<?php echo $admin->getNamaBelakang();?>"<?php echo $admin->notEqualsTipePengguna("admin") ? ' readonly' : '';?> autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input type="text" class="form-control" name="email" id="email" value="<?php echo $admin->getEmail();?>"<?php echo $admin->notEqualsTipePengguna("admin") ? ' readonly' : '';?> autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input type="text" class="form-control" name="telepon" id="telepon" value="<?php echo $admin->getTelepon();?>"<?php echo $admin->notEqualsTipePengguna("admin") ? ' readonly' : '';?> autocomplete="off"/>
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
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td>
							<select class="form-control" name="user_level_id" id="user_level_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevelMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userLevelId, Field::of()->nama, $admin->getUserLevelId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $admin->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlokir();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="blokir" id="blokir" value="1" <?php echo $admin->createCheckedBlokir();?>/> <?php echo $appEntityLanguage->getBlokir();?></label>
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->adminId, $inputGet->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
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
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
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
				"L" => array("value" => "L", "label" => "Laki-Laki", "group" => "", "selected" => true),
				"P" => array("value" => "P", "label" => "Perempuan", "group" => "", "selected" => false)
			);
			$mapForTipePengguna = array(
				"admin" => array("value" => "admin", "label" => "Administrator", "group" => "", "selected" => false),
				"ktsk" => array("value" => "ktsk", "label" => "KTSK", "group" => "", "selected" => false),
				"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "group" => "", "selected" => false)
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
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td><?php echo $admin->issetKtsk() ? $admin->getKtsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $admin->issetSupervisor() ? $admin->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $admin->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBlokir();?></td>
						<td><?php echo $admin->optionBlokir($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuCekTerakhir();?></td>
						<td><?php echo $admin->getWaktuCekTerakhir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuLoginTerakhir();?></td>
						<td><?php echo $admin->getWaktuLoginTerakhir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpCekTerakhir();?></td>
						<td><?php echo $admin->getIpCekTerakhir();?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->admin_id, $admin->getAdminId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="admin_id" value="<?php echo $admin->getAdminId();?>"/>
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
$mapForJenisKelamin = array(
	"L" => array("value" => "L", "label" => "Laki-Laki", "group" => "", "selected" => true),
	"P" => array("value" => "P", "label" => "Perempuan", "group" => "", "selected" => false)
);
$mapForTipePengguna = array(
	"admin" => array("value" => "admin", "label" => "Administrator", "group" => "", "selected" => false),
	"ktsk" => array("value" => "ktsk", "label" => "KTSK", "group" => "", "selected" => false),
	"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "group" => "", "selected" => false)
);
$specMap = array(
	"userLevelId" => PicoSpecification::filter("userLevelId", "number"),
	"tipePengguna" => PicoSpecification::filter("tipePengguna", "fulltext"),
	"ktskId" => PicoSpecification::filter("ktskId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number")
);
$sortOrderMap = array(
	"username" => "username",
	"nama" => "nama",
	"email" => "email",
	"telepon" => "telepon",
	"jenisKelamin" => "jenisKelamin",
	"userLevelId" => "userLevelId",
	"tipePengguna" => "tipePengguna",
	"ktskId" => "ktskId",
	"supervisorId" => "supervisorId",
	"aktif" => "aktif",
	"blokir" => "blokir"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, null);

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new Admin(null, $database);

$subqueryMap = array(
"userLevelId" => array(
	"columnName" => "user_level_id",
	"entityName" => "UserLevelMin",
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
		$appEntityLanguage->getAdminId() => $headerFormat->getAdminId(),
		$appEntityLanguage->getUsername() => $headerFormat->getUsername(),
		$appEntityLanguage->getNamaDepan() => $headerFormat->getNamaDepan(),
		$appEntityLanguage->getNamaBelakang() => $headerFormat->getNamaBelakang(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getEmail() => $headerFormat->getEmail(),
		$appEntityLanguage->getTelepon() => $headerFormat->getTelepon(),
		$appEntityLanguage->getJenisKelamin() => $headerFormat->asString(),
		$appEntityLanguage->getUserLevel() => $headerFormat->asString(),
		$appEntityLanguage->getTipePengguna() => $headerFormat->asString(),
		$appEntityLanguage->getKtsk() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getLangId() => $headerFormat->getLangId(),
		$appEntityLanguage->getAktif() => $headerFormat->asString(),
		$appEntityLanguage->getBlokir() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuCekTerakhir() => $headerFormat->getWaktuCekTerakhir(),
		$appEntityLanguage->getWaktuLoginTerakhir() => $headerFormat->getWaktuLoginTerakhir(),
		$appEntityLanguage->getIpCekTerakhir() => $headerFormat->getIpCekTerakhir(),
		$appEntityLanguage->getPertanyaan() => $headerFormat->getPertanyaan(),
		$appEntityLanguage->getJawaban() => $headerFormat->getJawaban()
	), 
	function($index, $row) use ($appLanguage, $mapForJenisKelamin, $mapForTipePengguna) {
		return array(
			sprintf("%d", $index + 1),
			$row->getAdminId(),
			$row->getUsername(),
			$row->getNamaDepan(),
			$row->getNamaBelakang(),
			$row->getNama(),
			$row->getEmail(),
			$row->getTelepon(),
			isset($mapForJenisKelamin) && isset($mapForJenisKelamin[$row->getJenisKelamin()]) && isset($mapForJenisKelamin[$row->getJenisKelamin()]["label"]) ? $mapForJenisKelamin[$row->getJenisKelamin()]["label"] : "",
			$row->issetUserLevel() ? $row->getUserLevel()->getNama() : "",
			isset($mapForTipePengguna) && isset($mapForTipePengguna[$row->getTipePengguna()]) && isset($mapForTipePengguna[$row->getTipePengguna()]["label"]) ? $mapForTipePengguna[$row->getTipePengguna()]["label"] : "",
			$row->issetKtsk() ? $row->getKtsk()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->getLangId(),
			$row->optionAktif($appLanguage->getYes(), $appLanguage->getNo()),
			$row->optionBlokir($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getWaktuCekTerakhir(),
			$row->getWaktuLoginTerakhir(),
			$row->getIpCekTerakhir(),
			$row->getPertanyaan(),
			$row->getJawaban()
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
					<span class="filter-label"><?php echo $appEntityLanguage->getUserLevel();?></span>
					<span class="filter-control">
							<select class="form-control" name="user_level_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevelMin(null, $database), 
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
					<span class="filter-label"><?php echo $appEntityLanguage->getTipePengguna();?></span>
					<span class="filter-control">
							<select class="form-control" name="tipe_pengguna" data-value="<?php echo $inputGet->getTipePengguna();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="administrator" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'administrator');?>>Administrator</option>
								<option value="ktsk" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'ktsk');?>>KTSK</option>
								<option value="supervisor" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'supervisor');?>>Aupervisor</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getKtsk();?></span>
					<span class="filter-control">
							<select class="form-control" name="ktsk_id">
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
								<td class="data-controll data-selector" data-key="admin_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-admin-id"/>
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
								<td data-col-name="username" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUsername();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="email" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getEmail();?></a></td>
								<td data-col-name="telepon" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTelepon();?></a></td>
								<td data-col-name="jenis_kelamin" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisKelamin();?></a></td>
								<td data-col-name="user_level_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUserLevel();?></a></td>
								<td data-col-name="tipe_pengguna" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTipePengguna();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<td data-col-name="blokir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBlokir();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($admin = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $admin->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="admin_id">
									<input type="checkbox" class="checkbox check-slave checkbox-admin-id" name="checked_row_id[]" value="<?php echo $admin->getAdminId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->admin_id, $admin->getAdminId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->admin_id, $admin->getAdminId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="username"><?php echo $admin->getUsername();?></td>
								<td data-col-name="nama"><?php echo $admin->getNama();?></td>
								<td data-col-name="email"><?php echo $admin->getEmail();?></td>
								<td data-col-name="telepon"><?php echo $admin->getTelepon();?></td>
								<td data-col-name="jenis_kelamin"><?php echo isset($mapForJenisKelamin) && isset($mapForJenisKelamin[$admin->getJenisKelamin()]) && isset($mapForJenisKelamin[$admin->getJenisKelamin()]["label"]) ? $mapForJenisKelamin[$admin->getJenisKelamin()]["label"] : "";?></td>
								<td data-col-name="user_level_id"><?php echo $admin->issetUserLevel() ? $admin->getUserLevel()->getNama() : "";?></td>
								<td data-col-name="tipe_pengguna"><?php echo isset($mapForTipePengguna) && isset($mapForTipePengguna[$admin->getTipePengguna()]) && isset($mapForTipePengguna[$admin->getTipePengguna()]["label"]) ? $mapForTipePengguna[$admin->getTipePengguna()]["label"] : "";?></td>
								<td data-col-name="ktsk_id"><?php echo $admin->issetKtsk() ? $admin->getKtsk()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $admin->issetSupervisor() ? $admin->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="aktif"><?php echo $admin->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="blokir"><?php echo $admin->optionBlokir($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

