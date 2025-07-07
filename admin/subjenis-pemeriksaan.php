<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\MagicObject;
use MagicObject\SetterGetter;
use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use Sipro\AppEntityLanguageImpl;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use Sipro\AppIncludeImpl;
use Sipro\AppUserPermissionImpl;
use Sipro\Entity\Data\SubjenisPemeriksaan;
use Sipro\Entity\Data\JenisPemeriksaanMin;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "subjenis-pemeriksaan", $appLanguage->getSubjenisPemeriksaan());
$userPermission = new AppUserPermissionImpl($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}


$dataFilter = null;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
	$subjenisPemeriksaan->setJenisPemeriksaanId($inputPost->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$subjenisPemeriksaan->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$subjenisPemeriksaan->setLabelYa($inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$subjenisPemeriksaan->setLabelTidak($inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$subjenisPemeriksaan->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$subjenisPemeriksaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$subjenisPemeriksaan->setAdminBuat($currentAction->getUserId());
	$subjenisPemeriksaan->setWaktuBuat($currentAction->getTime());
	$subjenisPemeriksaan->setIpBuat($currentAction->getIp());
	$subjenisPemeriksaan->setAdminUbah($currentAction->getUserId());
	$subjenisPemeriksaan->setWaktuUbah($currentAction->getTime());
	$subjenisPemeriksaan->setIpUbah($currentAction->getIp());
	try
	{
		$subjenisPemeriksaan->insert();
		$newId = $subjenisPemeriksaan->getSubjenisPemeriksaanId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->subjenis_pemeriksaan_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->subjenisPemeriksaanId, $inputPost->getSubjenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
	$updater = $subjenisPemeriksaan->where($specification)
		->setJenisPemeriksaanId($inputPost->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLabelYa($inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLabelTidak($inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getSubjenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->subjenis_pemeriksaan_id, $newId);
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
			$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
			try
			{
				$subjenisPemeriksaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->subjenisPemeriksaanId, $rowId))
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
			$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
			try
			{
				$subjenisPemeriksaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->subjenisPemeriksaanId, $rowId))
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->subjenisPemeriksaanId, $rowId))
					->addAnd($dataFilter)
					;
				$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
				$subjenisPemeriksaan->where($specification)
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
else if($inputPost->getUserAction() == UserAction::SORT_ORDER)
{
	if($inputPost->getNewOrder() != null && $inputPost->countableNewOrder())
	{
		foreach($inputPost->getNewOrder() as $dataItem)
		{
			try
			{
				if(is_string($dataItem))
				{
					$dataItem = new SetterGetter(json_decode($dataItem));
				}
				$rowId = $dataItem->getPrimaryKey();
				$sortOrder = intval($dataItem->getSortOrder());
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->subjenisPemeriksaanId, $rowId))
					->addAnd($dataFilter)
					;
				$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
				$subjenisPemeriksaan->where($specification)
					->setSortOrder($sortOrder)
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
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguageImpl(new SubjenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPemeriksaan();?></td>
						<td>
							<select class="form-control" name="jenis_pemeriksaan_id" id="jenis_pemeriksaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPemeriksaanId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td>
							<input type="text" class="form-control" name="label_ya" id="label_ya" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td>
							<input type="text" class="form-control" name="label_tidak" id="label_tidak" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="" autocomplete="off"/>
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
							<button type="submit" class="btn btn-success" name="user_action" id="create_new_data" value="create"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->subjenisPemeriksaanId, $inputGet->getSubjenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
	try{
		$subjenisPemeriksaan->findOne($specification);
		if($subjenisPemeriksaan->issetSubjenisPemeriksaanId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new SubjenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPemeriksaan();?></td>
						<td>
							<select class="form-control" name="jenis_pemeriksaan_id" id="jenis_pemeriksaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPemeriksaanId, Field::of()->nama, $subjenisPemeriksaan->getJenisPemeriksaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $subjenisPemeriksaan->getNama();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td>
							<input type="text" class="form-control" name="label_ya" id="label_ya" value="<?php echo $subjenisPemeriksaan->getLabelYa();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td>
							<input type="text" class="form-control" name="label_tidak" id="label_tidak" value="<?php echo $subjenisPemeriksaan->getLabelTidak();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="<?php echo $subjenisPemeriksaan->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $subjenisPemeriksaan->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-success" name="user_action" id="update_data" value="update"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="subjenis_pemeriksaan_id" id="primary_key_value" value="<?php echo $subjenisPemeriksaan->getSubjenisPemeriksaanId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->subjenisPemeriksaanId, $inputGet->getSubjenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$subjenisPemeriksaan = new SubjenisPemeriksaan(null, $database);
	try{
		$subqueryMap = array(
		"jenisPemeriksaanId" => array(
			"columnName" => "jenis_pemeriksaan_id",
			"entityName" => "JenisPemeriksaanMin",
			"tableName" => "jenis_pemeriksaan",
			"primaryKey" => "jenis_pemeriksaan_id",
			"objectName" => "jenis_pemeriksaan",
			"propertyName" => "nama"
		)
		);
		$subjenisPemeriksaan->findOne($specification, null, $subqueryMap);
		if($subjenisPemeriksaan->issetSubjenisPemeriksaanId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new SubjenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($subjenisPemeriksaan->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $subjenisPemeriksaan->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPemeriksaan();?></td>
						<td><?php echo $subjenisPemeriksaan->issetJenisPemeriksaan() ? $subjenisPemeriksaan->getJenisPemeriksaan()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $subjenisPemeriksaan->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td><?php echo $subjenisPemeriksaan->getLabelYa();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td><?php echo $subjenisPemeriksaan->getLabelTidak();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $subjenisPemeriksaan->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $subjenisPemeriksaan->getAdminBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $subjenisPemeriksaan->getAdminUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $subjenisPemeriksaan->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $subjenisPemeriksaan->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $subjenisPemeriksaan->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $subjenisPemeriksaan->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $subjenisPemeriksaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" id="update_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->subjenis_pemeriksaan_id, $subjenisPemeriksaan->getSubjenisPemeriksaanId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="subjenis_pemeriksaan_id" id="primary_key_value" value="<?php echo $subjenisPemeriksaan->getSubjenisPemeriksaanId();?>"/>
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
$appEntityLanguage = new AppEntityLanguageImpl(new SubjenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"jenisPemeriksaanId" => PicoSpecification::filter("jenisPemeriksaanId", "number")
);
$sortOrderMap = array(
	"jenisPemeriksaanId" => "jenisPemeriksaanId",
	"nama" => "nama",
	"labelYa" => "labelYa",
	"labelTidak" => "labelTidak",
	"sortOrder" => "sortOrder",
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
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new SubjenisPemeriksaan(null, $database);

$subqueryMap = array(
"jenisPemeriksaanId" => array(
	"columnName" => "jenis_pemeriksaan_id",
	"entityName" => "JenisPemeriksaanMin",
	"tableName" => "jenis_pemeriksaan",
	"primaryKey" => "jenis_pemeriksaan_id",
	"objectName" => "jenis_pemeriksaan",
	"propertyName" => "nama"
)
);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisPemeriksaan();?></span>
					<span class="filter-control">
							<select class="form-control" name="jenis_pemeriksaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPemeriksaanId, Field::of()->nama, $inputGet->getJenisPemeriksaanId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success" id="show_data"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" id="add_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
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
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-header"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="subjenis_pemeriksaan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-subjenis-pemeriksaan-id"/>
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
								<td data-col-name="jenis_pemeriksaan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPemeriksaan();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="label_ya" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLabelYa();?></a></td>
								<td data-col-name="label_tidak" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLabelTidak();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($subjenisPemeriksaan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $subjenisPemeriksaan->getSubjenisPemeriksaanId();?>" data-sort-order="<?php echo $subjenisPemeriksaan->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $subjenisPemeriksaan->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-body data-sort-handler"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="subjenis_pemeriksaan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-subjenis-pemeriksaan-id" name="checked_row_id[]" value="<?php echo $subjenisPemeriksaan->getSubjenisPemeriksaanId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->subjenis_pemeriksaan_id, $subjenisPemeriksaan->getSubjenisPemeriksaanId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->subjenis_pemeriksaan_id, $subjenisPemeriksaan->getSubjenisPemeriksaanId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="jenis_pemeriksaan_id"><?php echo $subjenisPemeriksaan->issetJenisPemeriksaan() ? $subjenisPemeriksaan->getJenisPemeriksaan()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $subjenisPemeriksaan->getNama();?></td>
								<td data-col-name="label_ya"><?php echo $subjenisPemeriksaan->getLabelYa();?></td>
								<td data-col-name="label_tidak"><?php echo $subjenisPemeriksaan->getLabelTidak();?></td>
								<td data-col-name="sort_order" class="data-sort-order-column"><?php echo $subjenisPemeriksaan->getSortOrder();?></td>
								<td data-col-name="aktif"><?php echo $subjenisPemeriksaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
						<button type="submit" class="btn btn-success" name="user_action" id="activate_selected" value="activate"><?php echo $appLanguage->getButtonActivate();?></button>
						<button type="submit" class="btn btn-warning" name="user_action" id="deactivate_selected" value="deactivate"><?php echo $appLanguage->getButtonDeactivate();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedDelete()){ ?>
						<button type="submit" class="btn btn-danger" name="user_action" id="delete_selected" value="delete" data-onclik-message="<?php echo htmlspecialchars($appLanguage->getWarningDeleteConfirmation());?>"><?php echo $appLanguage->getButtonDelete();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedSortOrder()){ ?>
						<button type="submit" class="btn btn-primary" name="user_action" id="save_current_order" value="sort_order" disabled="disabled"><?php echo $appLanguage->getButtonSaveCurrentOrder();?></button>
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

