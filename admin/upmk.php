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
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicApp\AppUserPermission;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Upmk;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "upmk", $appLanguage->getUpmk());
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
	$upmk = new Upmk(null, $database);
	$upmk->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setAlamat($inputPost->getAlamat(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setFaksimili($inputPost->getFaksimili(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setProvinsi($inputPost->getProvinsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setKabupaten($inputPost->getKabupaten(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setKecamatan($inputPost->getKecamatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$upmk->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$upmk->setAdminBuat($currentAction->getUserId());
	$upmk->setWaktuBuat($currentAction->getTime());
	$upmk->setIpBuat($currentAction->getIp());
	$upmk->setAdminUbah($currentAction->getUserId());
	$upmk->setWaktuUbah($currentAction->getTime());
	$upmk->setIpUbah($currentAction->getIp());
	try
	{
		$upmk->insert();
		$newId = $upmk->getUpmkId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->upmk_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->upmkId, $inputPost->getUpmkId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$upmk = new Upmk(null, $database);
	$updater = $upmk->where($specification)
		->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAlamat($inputPost->getAlamat(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setFaksimili($inputPost->getFaksimili(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setProvinsi($inputPost->getProvinsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setKabupaten($inputPost->getKabupaten(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setKecamatan($inputPost->getKecamatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getUpmkId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->upmk_id, $newId);
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
			$upmk = new Upmk(null, $database);
			try
			{
				$upmk->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->upmkId, $rowId))
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
			$upmk = new Upmk(null, $database);
			try
			{
				$upmk->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->upmkId, $rowId))
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->upmkId, $rowId))
					->addAnd($dataFilter)
					;
				$upmk = new Upmk(null, $database);
				$upmk->where($specification)
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
$appEntityLanguage = new AppEntityLanguage(new Upmk(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamat();?></td>
						<td>
							<textarea class="form-control" name="alamat" id="alamat" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input type="tel" class="form-control" name="telepon" id="telepon" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td>
							<input type="tel" class="form-control" name="faksimili" id="faksimili" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input type="email" class="form-control" name="email" id="email" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td>
							<input type="url" class="form-control" name="website" id="website" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td>
							<input type="text" class="form-control" name="provinsi" id="provinsi" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td>
							<input type="text" class="form-control" name="kabupaten" id="kabupaten" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td>
							<input type="text" class="form-control" name="kecamatan" id="kecamatan" autocomplete="off"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->upmkId, $inputGet->getUpmkId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$upmk = new Upmk(null, $database);
	try{
		$upmk->findOne($specification);
		if($upmk->issetUpmkId())
		{
$appEntityLanguage = new AppEntityLanguage(new Upmk(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $upmk->getNama();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamat();?></td>
						<td>
							<textarea class="form-control" name="alamat" id="alamat" spellcheck="false"><?php echo $upmk->getAlamat();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input type="tel" class="form-control" name="telepon" id="telepon" value="<?php echo $upmk->getTelepon();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td>
							<input type="tel" class="form-control" name="faksimili" id="faksimili" value="<?php echo $upmk->getFaksimili();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input type="email" class="form-control" name="email" id="email" value="<?php echo $upmk->getEmail();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td>
							<input type="url" class="form-control" name="website" id="website" value="<?php echo $upmk->getWebsite();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td>
							<input type="text" class="form-control" name="provinsi" id="provinsi" value="<?php echo $upmk->getProvinsi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td>
							<input type="text" class="form-control" name="kabupaten" id="kabupaten" value="<?php echo $upmk->getKabupaten();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td>
							<input type="text" class="form-control" name="kecamatan" id="kecamatan" value="<?php echo $upmk->getKecamatan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $upmk->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="upmk_id" value="<?php echo $upmk->getUpmkId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->upmkId, $inputGet->getUpmkId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$upmk = new Upmk(null, $database);
	try{
		$subqueryMap = array(
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
		)
		);
		$upmk->findOne($specification, null, $subqueryMap);
		if($upmk->issetUpmkId())
		{
$appEntityLanguage = new AppEntityLanguage(new Upmk(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($upmk->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $upmk->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $upmk->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamat();?></td>
						<td><?php echo $upmk->getAlamat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td><?php echo $upmk->getTelepon();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td><?php echo $upmk->getFaksimili();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td><?php echo $upmk->getEmail();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td><?php echo $upmk->getWebsite();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td><?php echo $upmk->getProvinsi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td><?php echo $upmk->getKabupaten();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td><?php echo $upmk->getKecamatan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $upmk->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $upmk->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $upmk->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $upmk->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $upmk->issetPembuat() ? $upmk->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $upmk->issetPengubah() ? $upmk->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $upmk->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->upmk_id, $upmk->getUpmkId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="upmk_id" value="<?php echo $upmk->getUpmkId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new Upmk(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"nama" => PicoSpecification::filter("nama", "fulltext")
);
$sortOrderMap = array(
	"nama" => "nama",
	"alamat" => "alamat",
	"telepon" => "telepon",
	"faksimili" => "faksimili",
	"email" => "email",
	"website" => "website",
	"provinsi" => "provinsi",
	"kabupaten" => "kabupaten",
	"kecamatan" => "kecamatan",
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
		"sortBy" => "nama", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new Upmk(null, $database);

$subqueryMap = array(
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
		$appEntityLanguage->getUpmkId() => $headerFormat->getUpmkId(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getAlamat() => $headerFormat->asString(),
		$appEntityLanguage->getTelepon() => $headerFormat->getTelepon(),
		$appEntityLanguage->getFaksimili() => $headerFormat->getFaksimili(),
		$appEntityLanguage->getEmail() => $headerFormat->getEmail(),
		$appEntityLanguage->getWebsite() => $headerFormat->getWebsite(),
		$appEntityLanguage->getProvinsi() => $headerFormat->getProvinsi(),
		$appEntityLanguage->getKabupaten() => $headerFormat->getKabupaten(),
		$appEntityLanguage->getKecamatan() => $headerFormat->getKecamatan(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->asString(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->asString(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
		return array(
			sprintf("%d", $index + 1),
			$row->getUpmkId(),
			$row->getNama(),
			$row->getAlamat(),
			$row->getTelepon(),
			$row->getFaksimili(),
			$row->getEmail(),
			$row->getWebsite(),
			$row->getProvinsi(),
			$row->getKabupaten(),
			$row->getKecamatan(),
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getIpBuat(),
			$row->getIpUbah(),
			$row->issetPembuat() ? $row->getPembuat()->getNama() : "",
			$row->issetPengubah() ? $row->getPengubah()->getNama() : "",
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
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" class="form-control" name="nama" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
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
								<td class="data-controll data-selector" data-key="upmk_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-upmk-id"/>
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
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="alamat" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAlamat();?></a></td>
								<td data-col-name="telepon" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTelepon();?></a></td>
								<td data-col-name="faksimili" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getFaksimili();?></a></td>
								<td data-col-name="email" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getEmail();?></a></td>
								<td data-col-name="website" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWebsite();?></a></td>
								<td data-col-name="provinsi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProvinsi();?></a></td>
								<td data-col-name="kabupaten" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKabupaten();?></a></td>
								<td data-col-name="kecamatan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKecamatan();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($upmk = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $upmk->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="upmk_id">
									<input type="checkbox" class="checkbox check-slave checkbox-upmk-id" name="checked_row_id[]" value="<?php echo $upmk->getUpmkId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->upmk_id, $upmk->getUpmkId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->upmk_id, $upmk->getUpmkId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama"><?php echo $upmk->getNama();?></td>
								<td data-col-name="alamat"><?php echo $upmk->getAlamat();?></td>
								<td data-col-name="telepon"><?php echo $upmk->getTelepon();?></td>
								<td data-col-name="faksimili"><?php echo $upmk->getFaksimili();?></td>
								<td data-col-name="email"><?php echo $upmk->getEmail();?></td>
								<td data-col-name="website"><?php echo $upmk->getWebsite();?></td>
								<td data-col-name="provinsi"><?php echo $upmk->getProvinsi();?></td>
								<td data-col-name="kabupaten"><?php echo $upmk->getKabupaten();?></td>
								<td data-col-name="kecamatan"><?php echo $upmk->getKecamatan();?></td>
								<td data-col-name="aktif"><?php echo $upmk->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

