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
use Sipro\Entity\Data\CatatanSalahLogin;
use Sipro\Entity\Data\UserMin;
use Sipro\Entity\Data\SupervisorMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "catatan-salah-login", "Catatan Salah Login");
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
	$catatanSalahLogin = new CatatanSalahLogin(null, $database);
	$catatanSalahLogin->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$catatanSalahLogin->setGrupPengguna($inputPost->getGrupPengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$catatanSalahLogin->setUserId($inputPost->getUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$catatanSalahLogin->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
;
;
	$catatanSalahLogin->setAdminBuat($currentAction->getUserId());
	$catatanSalahLogin->setWaktuBuat($currentAction->getTime());
	$catatanSalahLogin->setIpBuat($currentAction->getIp());
	$catatanSalahLogin->setAdminUbah($currentAction->getUserId());
	$catatanSalahLogin->setWaktuUbah($currentAction->getTime());
	$catatanSalahLogin->setIpUbah($currentAction->getIp());
	try
	{
		$catatanSalahLogin->insert();
		$newId = $catatanSalahLogin->getCatatanSalahLoginId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->catatan_salah_login_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->catatanSalahLoginId, $inputPost->getCatatanSalahLoginId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
	$specification->addAnd($dataFilter);
	$catatanSalahLogin = new CatatanSalahLogin(null, $database);
	$updater = $catatanSalahLogin->where($specification)
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
		->setGrupPengguna($inputPost->getGrupPengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setUserId($inputPost->getUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
	
	
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getCatatanSalahLoginId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->catatan_salah_login_id, $newId);
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
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS) as $rowId)
		{
			$catatanSalahLogin = new CatatanSalahLogin(null, $database);
			try
			{
				$catatanSalahLogin->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->catatanSalahLoginId, $rowId))
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
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS) as $rowId)
		{
			$catatanSalahLogin = new CatatanSalahLogin(null, $database);
			try
			{
				$catatanSalahLogin->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->catatanSalahLoginId, $rowId))
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
		foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS) as $rowId)
		{
			try
			{
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->catatanSalahLoginId, $rowId))
					->addAnd($dataFilter)
					;
				$catatanSalahLogin = new CatatanSalahLogin(null, $database);
				$catatanSalahLogin->where($specification)
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
$appEntityLanguage = new AppEntityLanguage(new CatatanSalahLogin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="aktif" id="aktif"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGrupPengguna();?></td>
						<td>
							<select class="form-control" name="grup_pengguna" id="grup_pengguna">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="supervisor">Supervisor</option>
								<option value="user">Administrator</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUser();?></td>
						<td>
							<select class="form-control" name="user_id" id="user_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userId, Field::of()->nama)
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
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_buat" id="waktu_buat"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="ip_buat" id="ip_buat"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->catatanSalahLoginId, $inputGet->getCatatanSalahLoginId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
	$specification->addAnd($dataFilter);
	$catatanSalahLogin = new CatatanSalahLogin(null, $database);
	try{
		$catatanSalahLogin->findOne($specification);
		if($catatanSalahLogin->issetCatatanSalahLoginId())
		{
$appEntityLanguage = new AppEntityLanguage(new CatatanSalahLogin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="aktif" id="aktif" value="<?php echo $catatanSalahLogin->getAktif();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGrupPengguna();?></td>
						<td>
							<select class="form-control" name="grup_pengguna" id="grup_pengguna" data-value="<?php echo $catatanSalahLogin->getGrupPengguna();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="supervisor" <?php echo AppFormBuilder::selected($catatanSalahLogin->getGrupPengguna(), 'supervisor');?>>Supervisor</option>
								<option value="user" <?php echo AppFormBuilder::selected($catatanSalahLogin->getGrupPengguna(), 'user');?>>Administrator</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUser();?></td>
						<td>
							<select class="form-control" name="user_id" id="user_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userId, Field::of()->nama, $catatanSalahLogin->getUserId())
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
								Field::of()->supervisorId, Field::of()->nama, $catatanSalahLogin->getSupervisorId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_buat" id="waktu_buat" value="<?php echo $catatanSalahLogin->getWaktuBuat();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td>
							<input class="form-control" type="text" name="ip_buat" id="ip_buat" value="<?php echo $catatanSalahLogin->getIpBuat();?>" autocomplete="off"/>
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
							<input type="hidden" name="catatan_salah_login_id" value="<?php echo $catatanSalahLogin->getCatatanSalahLoginId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->catatanSalahLoginId, $inputGet->getCatatanSalahLoginId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
	$specification->addAnd($dataFilter);
	$catatanSalahLogin = new CatatanSalahLogin(null, $database);
	try{
		$subqueryMap = array(
		"userId" => array(
			"columnName" => "user_id",
			"entityName" => "UserMin",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "user",
			"propertyName" => "first_name"
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
		$catatanSalahLogin->findOne($specification, null, $subqueryMap);
		if($catatanSalahLogin->issetCatatanSalahLoginId())
		{
$appEntityLanguage = new AppEntityLanguage(new CatatanSalahLogin(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			$mapForGrupPengguna = array(
				"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "default" => "true"),
				"user" => array("value" => "user", "label" => "Administrator", "default" => "true")
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($catatanSalahLogin->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $catatanSalahLogin->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $catatanSalahLogin->getAktif();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGrupPengguna();?></td>
						<td><?php echo isset($mapForGrupPengguna) && isset($mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]) && isset($mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]["label"]) ? $mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUser();?></td>
						<td><?php echo $catatanSalahLogin->issetUser() ? $catatanSalahLogin->getUser()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $catatanSalahLogin->issetSupervisor() ? $catatanSalahLogin->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $catatanSalahLogin->getWaktuBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $catatanSalahLogin->getIpBuat();?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->catatan_salah_login_id, $catatanSalahLogin->getCatatanSalahLoginId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="catatan_salah_login_id" value="<?php echo $catatanSalahLogin->getCatatanSalahLoginId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new CatatanSalahLogin(), $appConfig, $currentUser->getLanguageId());
$mapForGrupPengguna = array(
	"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "default" => "true"),
	"user" => array("value" => "user", "label" => "Administrator", "default" => "true")
);
$specMap = array(
	"grupPengguna" => PicoSpecification::filter("grupPengguna", "fulltext"),
	"userId" => PicoSpecification::filter("userId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number")
);
$sortOrderMap = array(
	"aktif" => "aktif",
	"grupPengguna" => "grupPengguna",
	"userId" => "userId",
	"supervisorId" => "supervisorId",
	"waktuBuat" => "waktuBuat",
	"ipBuat" => "ipBuat"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "waktuBuat", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new CatatanSalahLogin(null, $database);

$subqueryMap = array(
"userId" => array(
	"columnName" => "user_id",
	"entityName" => "UserMin",
	"tableName" => "user",
	"primaryKey" => "user_id",
	"objectName" => "user",
	"propertyName" => "first_name"
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
	$exporter = DocumentWriter::getCSVDocumentWriter($appLanguage);
	$fileName = $currentModule->getModuleName()."-".date("Y-m-d-H-i-s").".csv";
	$sheetName = "Sheet 1";

	$headerFormat = new XLSXDataFormat($dataLoader, 3);
	$pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_COUNT_DATA | MagicObject::FIND_OPTION_NO_FETCH_DATA);
	$exporter->write($pageData, $fileName, $sheetName, array(
		$appLanguage->getNumero() => $headerFormat->asNumber(),
		$appEntityLanguage->getAktif() => $headerFormat->getAktif(),
		$appEntityLanguage->getCatatanSalahLoginId() => $headerFormat->getCatatanSalahLoginId(),
		$appEntityLanguage->getGrupPengguna() => $headerFormat->asString(),
		$appEntityLanguage->getUser() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat()
	), 
	function($index, $row, $appLanguage){
		global $mapForGrupPengguna;
		return array(
			sprintf("%d", $index + 1),
			$row->getAktif(),
			$row->getCatatanSalahLoginId(),
			isset($mapForGrupPengguna) && isset($mapForGrupPengguna[$row->getGrupPengguna()]) && isset($mapForGrupPengguna[$row->getGrupPengguna()]["label"]) ? $mapForGrupPengguna[$row->getGrupPengguna()]["label"] : "",
			$row->issetUser() ? $row->getUser()->getFirstName() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->getWaktuBuat(),
			$row->getIpBuat()
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
					<span class="filter-label"><?php echo $appEntityLanguage->getGrupPengguna();?></span>
					<span class="filter-control">
							<select class="form-control" name="grup_pengguna" data-value="<?php echo $inputGet->getGrupPengguna();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="supervisor" <?php echo AppFormBuilder::selected($inputGet->getGrupPengguna(), 'supervisor');?>>Supervisor</option>
								<option value="user" <?php echo AppFormBuilder::selected($inputGet->getGrupPengguna(), 'user');?>>Administrator</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getUser();?></span>
					<span class="filter-control">
							<select class="form-control" name="user_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userId, Field::of()->firstName, $inputGet->getUserId())
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
				<?php if($userPermission->isAllowedDetail()){ ?>
		
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
								<td class="data-controll data-selector" data-key="catatan_salah_login_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-catatan-salah-login-id"/>
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
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<td data-col-name="grup_pengguna" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getGrupPengguna();?></a></td>
								<td data-col-name="user_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUser();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="waktu_buat" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuBuat();?></a></td>
								<td data-col-name="ip_buat" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getIpBuat();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($catatanSalahLogin = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="catatan_salah_login_id">
									<input type="checkbox" class="checkbox check-slave checkbox-catatan-salah-login-id" name="checked_row_id[]" value="<?php echo $catatanSalahLogin->getCatatanSalahLoginId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->catatan_salah_login_id, $catatanSalahLogin->getCatatanSalahLoginId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->catatan_salah_login_id, $catatanSalahLogin->getCatatanSalahLoginId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="aktif"><?php echo $catatanSalahLogin->getAktif();?></td>
								<td data-col-name="grup_pengguna"><?php echo isset($mapForGrupPengguna) && isset($mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]) && isset($mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]["label"]) ? $mapForGrupPengguna[$catatanSalahLogin->getGrupPengguna()]["label"] : "";?></td>
								<td data-col-name="user_id"><?php echo $catatanSalahLogin->issetUser() ? $catatanSalahLogin->getUser()->getFirstName() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $catatanSalahLogin->issetSupervisor() ? $catatanSalahLogin->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="waktu_buat"><?php echo $catatanSalahLogin->getWaktuBuat();?></td>
								<td data-col-name="ip_buat"><?php echo $catatanSalahLogin->getIpBuat();?></td>
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

