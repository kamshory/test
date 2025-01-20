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
use Sipro\Entity\Data\MaterialPekerjaan;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Material;
use Sipro\Entity\Data\JenisPekerjaan;
use Sipro\Entity\Data\JenisPekerjaanMin;

require_once __DIR__ . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/", "material-pekerjaan", "Material Pekerjaan");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$materialPekerjaan = new MaterialPekerjaan(null, $database);
	$materialPekerjaan->setMaterialId($inputPost->getMaterialId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$materialPekerjaan->setJenisPekerjaanId($inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$materialPekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$materialPekerjaan->setAdminBuat($currentUser->getUserId());
	$materialPekerjaan->setWaktuBuat($currentAction->getTime());
	$materialPekerjaan->setIpBuat($currentAction->getIp());
	$materialPekerjaan->setAdminUbah($currentUser->getUserId());
	$materialPekerjaan->setWaktuUbah($currentAction->getTime());
	$materialPekerjaan->setIpUbah($currentAction->getIp());
	$materialPekerjaan->insert();
	$newId = $materialPekerjaan->getMaterialPekerjaanId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->material_pekerjaan_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$materialPekerjaan = new MaterialPekerjaan(null, $database);
	$materialPekerjaan->setMaterialId($inputPost->getMaterialId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$materialPekerjaan->setJenisPekerjaanId($inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$materialPekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$materialPekerjaan->setAdminUbah($currentUser->getUserId());
	$materialPekerjaan->setWaktuUbah($currentAction->getTime());
	$materialPekerjaan->setIpUbah($currentAction->getIp());
	$materialPekerjaan->setMaterialPekerjaanId($inputPost->getMaterialPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$materialPekerjaan->update();
	$newId = $materialPekerjaan->getMaterialPekerjaanId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->material_pekerjaan_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$materialPekerjaan = new MaterialPekerjaan(null, $database);
			try
			{
				$materialPekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->material_pekerjaan_id, $rowId))
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
			$materialPekerjaan = new MaterialPekerjaan(null, $database);
			try
			{
				$materialPekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->material_pekerjaan_id, $rowId))
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
			$materialPekerjaan = new MaterialPekerjaan(null, $database);
			$materialPekerjaan->deleteOneByMaterialPekerjaanId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new MaterialPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getMaterial();?></td>
						<td>
							<select class="form-control" name="material_id" id="material_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Material(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->materialId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td>
							<select class="form-control" name="jenis_pekerjaan_id" id="jenis_pekerjaan_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama)
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
	$materialPekerjaan = new MaterialPekerjaan(null, $database);
	try{
		$materialPekerjaan->findOneByMaterialPekerjaanId($inputGet->getMaterialPekerjaanId());
		if($materialPekerjaan->hasValueMaterialPekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new MaterialPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getMaterial();?></td>
						<td>
							<select class="form-control" name="material_id" id="material_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Material(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->materialId, Field::of()->nama, $materialPekerjaan->getMaterialId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td>
							<select class="form-control" name="jenis_pekerjaan_id" id="jenis_pekerjaan_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama, $materialPekerjaan->getJenisPekerjaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $materialPekerjaan->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="material_pekerjaan_id" value="<?php echo $materialPekerjaan->getMaterialPekerjaanId();?>"/>
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
	$materialPekerjaan = new MaterialPekerjaan(null, $database);
	try{
		$subqueryMap = array(
		"materialId" => array(
			"columnName" => "material_id",
			"entityName" => "Material",
			"tableName" => "material",
			"primaryKey" => "material_id",
			"objectName" => "material",
			"propertyName" => "nama"
		), 
		"jenisPekerjaanId" => array(
			"columnName" => "jenis_pekerjaan_id",
			"entityName" => "JenisPekerjaan",
			"tableName" => "jenis_pekerjaan",
			"primaryKey" => "jenis_pekerjaan_id",
			"objectName" => "jenis_pekerjaan",
			"propertyName" => "nama"
		)
		);
		$materialPekerjaan->findOneWithPrimaryKeyValue($inputGet->getMaterialPekerjaanId(), $subqueryMap);
		if($materialPekerjaan->hasValueMaterialPekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new MaterialPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($materialPekerjaan->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $materialPekerjaan->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getMaterial();?></td>
						<td><?php echo $materialPekerjaan->hasValueMaterial() ? $materialPekerjaan->getMaterial()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td><?php echo $materialPekerjaan->hasValueJenisPekerjaan() ? $materialPekerjaan->getJenisPekerjaan()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $materialPekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($materialPekerjaan->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($materialPekerjaan->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->material_pekerjaan_id, $materialPekerjaan->getMaterialPekerjaanId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="material_pekerjaan_id" value="<?php echo $materialPekerjaan->getMaterialPekerjaanId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new MaterialPekerjaan(), $appConfig, $currentUser->getLanguageId());
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getMaterial();?></span>
					<span class="filter-control">
							<select name="material_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Material(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->materialId, Field::of()->nama, $inputGet->getMaterialId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisPekerjaan();?></span>
					<span class="filter-control">
							<select name="jenis_pekerjaan_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama, $inputGet->getJenisPekerjaanId())
								; ?>
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
			
			$specMap = array(
			    "materialId" => PicoSpecification::filter("materialId", "number"),
				"jenisPekerjaanId" => PicoSpecification::filter("jenisPekerjaanId", "fulltext"),
				"aktif" => PicoSpecification::filter("aktif", "boolean")
			);
			$sortOrderMap = array(
			    "materialId" => "materialId",
				"jenisPekerjaanId" => "jenisPekerjaanId",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "materialPekerjaanId", 
					"sortType" => PicoSort::ORDER_TYPE_DESC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new MaterialPekerjaan(null, $database);
			
			$subqueryMap = array(
			"materialId" => array(
				"columnName" => "material_id",
				"entityName" => "Material",
				"tableName" => "material",
				"primaryKey" => "material_id",
				"objectName" => "material",
				"propertyName" => "nama"
			), 
			"jenisPekerjaanId" => array(
				"columnName" => "jenis_pekerjaan_id",
				"entityName" => "JenisPekerjaan",
				"tableName" => "jenis_pekerjaan",
				"primaryKey" => "jenis_pekerjaan_id",
				"objectName" => "jenis_pekerjaan",
				"propertyName" => "nama"
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
								<td class="data-controll data-selector" data-key="material_pekerjaan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-material-pekerjaan-id"/>
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
								<td data-col-name="material_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getMaterial();?></a></td>
								<td data-col-name="jenis_pekerjaan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPekerjaan();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($materialPekerjaan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="material_pekerjaan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-material-pekerjaan-id" name="checked_row_id[]" value="<?php echo $materialPekerjaan->getMaterialPekerjaanId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->material_pekerjaan_id, $materialPekerjaan->getMaterialPekerjaanId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->material_pekerjaan_id, $materialPekerjaan->getMaterialPekerjaanId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="material_id"><?php echo $materialPekerjaan->hasValueMaterial() ? $materialPekerjaan->getMaterial()->getNama() : "";?></td>
								<td data-col-name="jenis_pekerjaan_id"><?php echo $materialPekerjaan->hasValueJenisPekerjaan() ? $materialPekerjaan->getJenisPekerjaan()->getNama() : "";?></td>
								<td data-col-name="aktif"><?php echo $materialPekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

