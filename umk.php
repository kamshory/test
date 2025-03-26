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
use Sipro\Entity\Data\Umk;
use Sipro\AppIncludeImpl;

require_once __DIR__ . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/", "umk", "Umk");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$umk = new Umk(null, $database);
	$umk->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setAlamat($inputPost->getAlamat(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setFaksimili($inputPost->getFaksimili(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setProvinsi($inputPost->getProvinsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setKabupaten($inputPost->getKabupaten(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setKecamatan($inputPost->getKecamatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$umk->setAdminBuat($currentUser->getUserId());
	$umk->setWaktuBuat($currentAction->getTime());
	$umk->setIpBuat($currentAction->getIp());
	$umk->setAdminUbah($currentUser->getUserId());
	$umk->setWaktuUbah($currentAction->getTime());
	$umk->setIpUbah($currentAction->getIp());
	$umk->insert();
	$newId = $umk->getUmkId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->umk_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$umk = new Umk(null, $database);
	$umk->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setAlamat($inputPost->getAlamat(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setFaksimili($inputPost->getFaksimili(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setWebsite($inputPost->getWebsite(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setProvinsi($inputPost->getProvinsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setKabupaten($inputPost->getKabupaten(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setKecamatan($inputPost->getKecamatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$umk->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$umk->setAdminUbah($currentUser->getUserId());
	$umk->setWaktuUbah($currentAction->getTime());
	$umk->setIpUbah($currentAction->getIp());
	$umk->setUmkId($inputPost->getUmkId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$umk->update();
	$newId = $umk->getUmkId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->umk_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$umk = new Umk(null, $database);
			try
			{
				$umk->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umk_id, $rowId))
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
			$umk = new Umk(null, $database);
			try
			{
				$umk->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umk_id, $rowId))
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
			$umk = new Umk(null, $database);
			$umk->deleteOneByUmkId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new Umk(), $appConfig, $currentUser->getLanguageId());
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
							<input autocomplete="off" type="text" class="form-control" name="nama" id="nama" required="required"/>
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
							<input autocomplete="off" class="form-control" type="tel" name="telepon" id="telepon"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="url" name="faksimili" id="faksimili"/>
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
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="provinsi" id="provinsi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="kabupaten" id="kabupaten"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="kecamatan" id="kecamatan"/>
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
	$umk = new Umk(null, $database);
	try{
		$umk->findOneByUmkId($inputGet->getUmkId());
		if($umk->hasValueUmkId())
		{
$appEntityLanguage = new AppEntityLanguage(new Umk(), $appConfig, $currentUser->getLanguageId());
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
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $umk->getNama();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamat();?></td>
						<td>
							<textarea class="form-control" name="alamat" id="alamat" spellcheck="false"><?php echo $umk->getAlamat();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td>
							<input class="form-control" type="tel" name="telepon" id="telepon" value="<?php echo $umk->getTelepon();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td>
							<input class="form-control" type="url" name="faksimili" id="faksimili" value="<?php echo $umk->getFaksimili();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td>
							<input class="form-control" type="email" name="email" id="email" value="<?php echo $umk->getEmail();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td>
							<input class="form-control" type="url" name="website" id="website" value="<?php echo $umk->getWebsite();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td>
							<input type="text" class="form-control" name="provinsi" id="provinsi" value="<?php echo $umk->getProvinsi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td>
							<input type="text" class="form-control" name="kabupaten" id="kabupaten" value="<?php echo $umk->getKabupaten();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td>
							<input type="text" class="form-control" name="kecamatan" id="kecamatan" value="<?php echo $umk->getKecamatan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $umk->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="umk_id" value="<?php echo $umk->getUmkId();?>"/>
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
	$umk = new Umk(null, $database);
	try{
		$subqueryMap = array(
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "User",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
			"propertyName" => "nama_depan"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "User",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama_depan"
		)
		);
		$umk->findOneWithPrimaryKeyValue($inputGet->getUmkId(), $subqueryMap);
		if($umk->hasValueUmkId())
		{
$appEntityLanguage = new AppEntityLanguage(new Umk(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($umk->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $umk->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $umk->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamat();?></td>
						<td><?php echo $umk->getAlamat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTelepon();?></td>
						<td><?php echo $umk->getTelepon();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFaksimili();?></td>
						<td><?php echo $umk->getFaksimili();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getEmail();?></td>
						<td><?php echo $umk->getEmail();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWebsite();?></td>
						<td><?php echo $umk->getWebsite();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProvinsi();?></td>
						<td><?php echo $umk->getProvinsi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKabupaten();?></td>
						<td><?php echo $umk->getKabupaten();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKecamatan();?></td>
						<td><?php echo $umk->getKecamatan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $umk->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $umk->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $umk->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $umk->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $umk->hasValuePembuat() ? $umk->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $umk->hasValuePengubah() ? $umk->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $umk->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($umk->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($umk->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->umk_id, $umk->getUmkId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="umk_id" value="<?php echo $umk->getUmkId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new Umk(), $appConfig, $currentUser->getLanguageId());
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
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
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
			    "nama" => PicoSpecification::filter("nama", "fulltext")
			);
			$sortOrderMap = array(
			    "nama" => "nama",
				"telepon" => "telepon",
				"email" => "email",
				"provinsi" => "provinsi",
				"kabupaten" => "kabupaten",
				"kecamatan" => "kecamatan",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "nama", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new Umk(null, $database);
			
			$subqueryMap = array(
			"adminBuat" => array(
				"columnName" => "admin_buat",
				"entityName" => "User",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "pembuat",
				"propertyName" => "nama_depan"
			), 
			"adminUbah" => array(
				"columnName" => "admin_ubah",
				"entityName" => "User",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "pengubah",
				"propertyName" => "nama_depan"
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
								<td class="data-controll data-selector" data-key="umk_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-umk-id"/>
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
								<td data-col-name="telepon" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTelepon();?></a></td>
								<td data-col-name="email" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getEmail();?></a></td>
								<td data-col-name="provinsi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProvinsi();?></a></td>
								<td data-col-name="kabupaten" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKabupaten();?></a></td>
								<td data-col-name="kecamatan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKecamatan();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($umk = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="umk_id">
									<input type="checkbox" class="checkbox check-slave checkbox-umk-id" name="checked_row_id[]" value="<?php echo $umk->getUmkId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->umk_id, $umk->getUmkId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->umk_id, $umk->getUmkId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama"><?php echo $umk->getNama();?></td>
								<td data-col-name="telepon"><?php echo $umk->getTelepon();?></td>
								<td data-col-name="email"><?php echo $umk->getEmail();?></td>
								<td data-col-name="provinsi"><?php echo $umk->getProvinsi();?></td>
								<td data-col-name="kabupaten"><?php echo $umk->getKabupaten();?></td>
								<td data-col-name="kecamatan"><?php echo $umk->getKecamatan();?></td>
								<td data-col-name="aktif"><?php echo $umk->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

