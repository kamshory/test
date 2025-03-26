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
use MagicObject\Util\PicoStringUtil;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\AcuanPengawasanForList;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\BillOfQuantityMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$dataFilter = null;
$filterProyek = null;

$inputGet = new InputGet();
$inputPost = new InputPost();

$dateTimeFormat = "Y-m-d H:i:s";

// Data filter begin
if($appUserImpl->getTipePengguna() == "supervisor")
{
	// Supervisor
	if($appUserImpl->issetSupervisor() 
	&& $appUserImpl->getSupervisor()->getKoordinator() 
	&& (
		($appUserImpl->getUmkId() != null && $appUserImpl->getUmkId() != 0) 
		|| 
		($appUserImpl->getTskId() != null && $appUserImpl->getTskId() != 0)
		)
	)
	{
		$dataFilter = PicoSpecification::getInstance();
		$filterProyek = PicoSpecification::getInstance();
		if($appUserImpl->getUmkId() != null && $appUserImpl->getUmkId() != 0)
		{
			$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $appUserImpl->getUmkId()));
			$filterProyek->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $appUserImpl->getUmkId()));
		}
		if($appUserImpl->getTskId() != null && $appUserImpl->getTskId() != 0)
		{
			$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));
			$filterProyek->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));
		}
	}
	else
	{
		// Add impossible condition to prevent data leak
		$dataFilter = PicoSpecification::getInstance()
				->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, 0))
			;
		$filterProyek = PicoSpecification::getInstance()
				->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, 0))
			;
	}

	if($appUserImpl->issetSupervisor() && $appUserImpl->getSupervisor()->getKoordinator())
	{
		try
		{
			$proyek = new ProyekMin(null, $database);
			$pageData = $proyek->findAll($filterProyek, null, null, true);
			foreach($pageData->getResult() as $sp)
			{
				$proyekIds[] = $sp->getProyekId();
			}
			if($inputPost->getUserAction() == UserAction::APPROVE || $inputPost->getUserAction() == UserAction::REJECT)
			{
				// Approve or Reject
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->in(Field::of()->bukuHarianId, $inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT)))
					->addAnd(PicoPredicate::getInstance()->in(Field::of()->proyekId, $proyekIds))
					;
				if($inputPost->getUserAction() == UserAction::APPROVE)
				{
					// Approve
					$bukuHarian = new BukuHarian(null, $database);
					$bukuHarian->where($specification)
					->setAccKtsk($appUserImpl->getAdminId())
					->setStatusAccKoordinator("CHECKED")
					->setWaktuAccKtsk(date($dateTimeFormat))
					->update();
				}
				else if($inputPost->getUserAction() == UserAction::REJECT)
				{
					// Reject
					$bukuHarian = new BukuHarian(null, $database);
					$bukuHarian->where($specification)
					->setAccKtsk($appUserImpl->getAdminId())
					->setStatusAccKoordinator("REJECTED")
					->setWaktuAccKtsk(date($dateTimeFormat))
					->update();
				}
			}
		}
		catch(Exception $e)
		{
			// Add impossible condition to prevent data leak
		}
	}
}
else if($appUserImpl->getTipePengguna() == "ktsk" && $appUserImpl->getKtskId() != 0)
{
	// KTSK

	$dataFilter = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));
	$filterProyek = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));

	try
	{
		$proyek = new ProyekMin(null, $database);
		$pageData = $proyek->findAll($filterProyek, null, null, true);
		foreach($pageData->getResult() as $sp)
		{
			$proyekIds[] = $sp->getProyekId();
		}
		if($inputPost->getUserAction() == UserAction::APPROVE || $inputPost->getUserAction() == UserAction::REJECT)
		{
			// Approve or Reject
			$specification = PicoSpecification::getInstance()
				->addAnd(PicoPredicate::getInstance()->in(Field::of()->bukuHarianId, $inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT)))
				->addAnd(PicoPredicate::getInstance()->in(Field::of()->proyekId, $proyekIds))
				;
			if($inputPost->getUserAction() == UserAction::APPROVE)
			{
				// Approve
				$bukuHarian = new BukuHarian(null, $database);
				$bukuHarian->where($specification)
				->setAccKtsk($appUserImpl->getAdminId())
				->setStatusAccKtsk("APPROVED")
				->setWaktuAccKtsk(date($dateTimeFormat))
				->update();
			}
			else if($inputPost->getUserAction() == UserAction::REJECT)
			{
				// Reject
				$bukuHarian = new BukuHarian(null, $database);
				$bukuHarian->where($specification)
				->setAccKtsk($appUserImpl->getAdminId())
				->setStatusAccKtsk("REJECTED")
				->setWaktuAccKtsk(date($dateTimeFormat))
				->update();
			}
		}
	}
	catch(Exception $e)
	{
		// Add impossible condition to prevent data leak
	}
}
else if($appUserImpl->getTipePengguna() == null || $appUserImpl->getTipePengguna() == "" || $appUserImpl->getTipePengguna() == "admin")
{
	if(($appUserImpl->getUmkId() != null && $appUserImpl->getUmkId() != 0) || ($appUserImpl->getTskId() != null && $appUserImpl->getTskId() != 0))
	{
		$dataFilter = PicoSpecification::getInstance();
		if($appUserImpl->getUmkId() != null && $appUserImpl->getUmkId() != 0)
		{
			$dataFilter = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $appUserImpl->getUmkId()));
			$filterProyek = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->umkId, $appUserImpl->getUmkId()));
		}
		if($appUserImpl->getTskId() != null && $appUserImpl->getTskId() != 0)
		{
			$dataFilter = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));
			$filterProyek = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $appUserImpl->getTskId()));
		}
	}
	else
	{
		// No filter
		$dataFilter = null;
	}
}


// Data filter end



$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "buku-harian", $appLanguage->getBukuHarian());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->bukuHarianId, $inputGet->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$bukuHarian = new BukuHarian(null, $database);
	try{
		$subqueryMap = array(
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		), 
		"billOfQuantityId" => array(
			"columnName" => "bill_of_quantity_id",
			"entityName" => "BillOfQuantityMin",
			"tableName" => "bill_of_quantity",
			"primaryKey" => "bill_of_quantity_id",
			"objectName" => "bill_of_quantity",
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
		"koordinatorId" => array(
			"columnName" => "koordinator_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "koordinator",
			"propertyName" => "nama"
		)
		);
		$bukuHarian->findOne($specification, null, $subqueryMap);
		if($bukuHarian->issetBukuHarianId())
		{
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($bukuHarian->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $bukuHarian->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $bukuHarian->getTanggal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $bukuHarian->issetSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKegiatan();?></td>
						<td><?php echo $bukuHarian->getKegiatan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
						<td><?php echo $bukuHarian->issetBillOfQuantity() ? $bukuHarian->getBillOfQuantity()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td><?php echo $bukuHarian->issetKtsk() ? $bukuHarian->getKtsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAccKtsk();?></td>
						<td><?php echo $bukuHarian->optionAccKtsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuAccKtsk();?></td>
						<td><?php echo $bukuHarian->getWaktuAccKtsk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKoordinator();?></td>
						<td><?php echo $bukuHarian->issetKoordinator() ? $bukuHarian->getKoordinator()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAccKoordinator();?></td>
						<td><?php echo $bukuHarian->optionAccKoordinator($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuAccKoordinator();?></td>
						<td><?php echo $bukuHarian->getWaktuAccKoordinator();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKtsk();?></td>
						<td><?php echo $bukuHarian->getKomentarKtsk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKoordinator();?></td>
						<td><?php echo $bukuHarian->getKomentarKoordinator();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td><?php echo $bukuHarian->getLatitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td><?php echo $bukuHarian->getLongitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td><?php echo $bukuHarian->getAltitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $bukuHarian->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $bukuHarian->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $bukuHarian->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="buku_harian_id" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"billOfQuantityId" => PicoSpecification::filter("billOfQuantityId", "number")
);
$sortOrderMap = array(
	"tanggal" => "tanggal",
	"supervisorId" => "supervisorId",
	"kegiatan" => "kegiatan",
	"accKtsk" => "accKtsk",
	"latitude" => "latitude",
	"longitude" => "longitude",
	"altitude" => "altitude",
	"statusAccKoordinator"=>"statusAccKoordinator",
	"statusAccKtsk"=>"statusAccKtsk",
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);

$dariTanggal = $inputGet->getDariTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
$hinggaTanggal = $inputGet->getHinggaTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

if(isset($dariTanggal) && isset($hinggaTanggal))
{
	$specification->addAnd(PicoPredicate::getInstance()->between(Field::of()->tanggal, $dariTanggal, $hinggaTanggal));
}


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "bukuHarianId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new BukuHarian(null, $database);

$subqueryMap = array(
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"supervisorId" => array(
	"columnName" => "supervisor_id",
	"entityName" => "SupervisorMin",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "supervisor",
	"propertyName" => "nama"
), 
"billOfQuantityId" => array(
	"columnName" => "bill_of_quantity_id",
	"entityName" => "BillOfQuantityMin",
	"tableName" => "bill_of_quantity",
	"primaryKey" => "bill_of_quantity_id",
	"objectName" => "bill_of_quantity",
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
"koordinatorId" => array(
	"columnName" => "koordinator_id",
	"entityName" => "SupervisorMin",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "koordinator",
	"propertyName" => "nama"
)
);

if($inputGet->getUserAction() == UserAction::EXPORT)
{
	include_once __DIR__ . "/download-buku-harian.php";
	exit();
}

?>
<style>

</style>
<?php

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
						<span class="filter-control">
							<select class="form-control" name="proyek_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php 

								$specsProyek = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
								
								if($filterProyek)
								{
									$specsProyek->addAnd($filterProyek);
								}
								
								echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								$specsProyek, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getDariTanggal();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="dari_tanggal" value="<?php echo $dariTanggal;?>" autocomplete="off"/>
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getHinggaTanggal();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="hingga_tanggal" value="<?php echo $hinggaTanggal;?>" autocomplete="off"/>
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
								->setGroup(Field::of()->jabatanId, Field::of()->nama, Field::of()->jabatan)
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getBillOfQuantity();?></span>
					<span class="filter-control">
							<select class="form-control" name="bill_of_quantity_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantityMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId()))
									->addAnd(new PicoPredicate(Field::of()->level, 1))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->billOfQuantityId, Field::of()->nama, $inputGet->getBillOfQuantityId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>

				<span class="filter-group">
					<button type="submit" class="btn btn-success" name="user_action" value="export"><?php echo $appLanguage->getButtonExport();?></button>
				</span>
				
			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php 
			if($inputGet->getProyekId())
			{
				$buhar = new BukuHarian(null, $database);
			try{
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
								<?php if($appUserImpl->getTipePengguna() == "supervisor" || $appUserImpl->getTipePengguna() == "ktsk"){ ?>
								<td class="data-controll data-selector" data-key="buku_harian_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-buku-harian-id"/>
								</td>
								<?php } ?>
								
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td><?php echo $appLanguage->getLokasi();?></td>
								<td data-col-name="kegiatan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKegiatan();?></a></td>
								<td><?php echo $appLanguage->getAcuanPengawasan();?></td>
								<td><?php echo $appLanguage->getBillOfQuantity();?></td>
								<td><?php echo $appLanguage->getVolume();?></td>
								<td><?php echo $appLanguage->getTercapai();?></td>
								<td><?php echo $appLanguage->getPersen();?></td>
								<td><?php echo $appLanguage->getPermasalahan();?></td>
								<td><?php echo $appLanguage->getRekomendasi();?></td>
								<td><?php echo $appLanguage->getTindakLanjut();?></td>
								<td><?php echo $appLanguage->getManPower();?></td>
								<td><?php echo $appLanguage->getMaterial();?></td>
								<td><?php echo $appLanguage->getPeralatan();?></td>
								<td><?php echo $appEntityLanguage->getAccKoordinator();?></td>
								<td><?php echo $appEntityLanguage->getAccKtsk();?></td>
								<td><?php echo $appLanguage->getKoordinat();?></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($bukuHarian = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $bukuHarian->optionAktif('true', 'false');?>">
								<?php if($appUserImpl->getTipePengguna() == "supervisor" || $appUserImpl->getTipePengguna() == "ktsk"){ ?>
								<td class="data-selector" data-key="buku_harian_id">
									<input type="checkbox" class="checkbox check-slave checkbox-buku-harian-id" name="checked_row_id[]" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
								</td>
								<?php } ?>
								
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="tanggal"><?php echo $bukuHarian->getTanggal();?></td>
								<td data-col-name="supervisor_id"><?php echo $bukuHarian->issetSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
								<td><?php
								$lokasiProyekObj = $buhar->getLokasiProyek($bukuHarian->getBukuHarianId());
								$lokasiPekerjaans = $buhar->getSerialOfScalar($lokasiProyekObj);
								if(!empty($lokasiPekerjaans))
								{
									echo '<ol style="padding-left:20px;"><li>'.implode("</li>\r\n<li>", $lokasiPekerjaans)."</li><ol>";
								}
								?></td>
								<td data-col-name="kegiatan"><?php echo $bukuHarian->getKegiatan();?></td>
								<td><?php
								$acuanPengawasanObj = $buhar->getAcuanPengawasanList($bukuHarian->getBukuHarianId());
								$acuanPengawasanIds = $buhar->getSerialOfScalar($acuanPengawasanObj);
								$acuanPengawasanFilder = new AcuanPengawasanForList(null, $database);
								try
								{
									if(!empty($acuanPengawasanIds))
									{
									$pdt = $acuanPengawasanFilder->findAll(PicoSpecification::getInstance()->add([Field::of()->acuanPengawasanId, $acuanPengawasanIds]));
									?>
									<ol style="padding-left:20px;">
									<?php
									foreach($pdt->getResult() as $acuanPengawasan)
									{
										?>
										<li><?php
										$hirarki = $acuanPengawasan->issetJenisHirarkiKontrak() ? trim($acuanPengawasan->getJenisHirarkiKontrak()->getNama()) : "";
										$nama = trim($acuanPengawasan->getNama());
										$nomor = trim($acuanPengawasan->getNomor());
										echo nl2br(PicoStringUtil::wordChunk('['.$hirarki.'] ['.$nama.'] ['.$nomor.']', 25));
										?>
										</li>
										<?php
									}
									?>
									</ol>
									<?php
									}
								}
								catch(Exception $e)
								{
									// Do nothing
								}
								?>
								</td>
								<td>
								<?php
								$boqObj = $buhar->getBillOfQuantityProyek($bukuHarian->getBukuHarianId());
								if(!empty($boqObj))
								{
									?>
									<ol style="padding-left:20px;">
									<?php
									$nline = array();
									foreach($boqObj as $idx=>$boq)
									{
										$billOfQuantity = PicoStringUtil::wordChunk($boq->bill_of_quantity, 25);
										$nline[$idx] = substr_count($billOfQuantity, "\n");
										?>
										<li><?php echo nl2br($billOfQuantity);?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
								<?php
								if(!empty($boqObj))
								{
									?>
									<ol style="list-style-type:none; padding-left:0px;">
									<?php
									foreach($boqObj as $idx=>$boq)
									{
										$brs = str_repeat('<br />', $nline[$idx]);
										?>
										<li><?php echo $boq->volume.$brs;?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
								<?php
								if(!empty($boqObj))
								{
									?>
									<ol style="list-style-type:none; padding-left:0px;">
									<?php
									foreach($boqObj as $idx=>$boq)
									{
										$brs = str_repeat('<br />', $nline[$idx]);
										?>
										<li><?php echo $boq->volume_proyek.$brs;?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
								<?php
								if(!empty($boqObj))
								{
									?>
									<ol style="list-style-type:none; padding-left:0px;">
									<?php
									foreach($boqObj as $boq)
									{
										$brs = str_repeat('<br />', $nline[$idx]);
										?>
										<li><?php echo number_format($boq->persen, 2).$brs;?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
									<?php
									$permasalahanBoq = $buhar->getPermasalahanPekerjaan($bukuHarian->getBukuHarianId());
									if(!empty($permasalahanBoq))
									{
										?>
										<ol style="padding-left:20px;">
										<?php
										$nline = array();
										foreach($permasalahanBoq as $idx=>$permasalahan)
										{
											?>
											<li><?php echo nl2br(PicoStringUtil::wordChunk($permasalahan->permasalahan, 25));?></li>
											<?php
										}
										?>
										</ol>
										<?php
									}
									?>
								</td>
								<td>
									<?php
									if(!empty($permasalahanBoq))
									{
										?>
										<ol style="padding-left:20px;">
										<?php
										$nline = array();
										foreach($permasalahanBoq as $idx=>$permasalahan)
										{
											?>
											<li><?php echo nl2br(PicoStringUtil::wordChunk($permasalahan->rekomendasi, 25));?></li>
											<?php
										}
										?>
										</ol>
										<?php
									}
									?>
								</td>
								<td>
									<?php
									if(!empty($permasalahanBoq))
									{
										?>
										<ol style="padding-left:20px;">
										<?php
										$nline = array();
										foreach($permasalahanBoq as $idx=>$permasalahan)
										{
											?>
											<li><?php echo nl2br(PicoStringUtil::wordChunk($permasalahan->tindak_lanjut, 25));?></li>
											<?php
										}
										?>
										</ol>
										<?php
									}
									?>
								</td>
								<td>
								<?php
								$manPowerObj = $buhar->getManPowerProyek($bukuHarian->getBukuHarianId());
								if(!empty($manPowerObj))
								{
									?>
									<ol style="padding-left:20px;">
									<?php
									foreach($manPowerObj as $idx=>$manPower)
									{
										?>
										<li><?php echo nl2br(PicoStringUtil::wordChunk($manPower->man_power. ' ['.$manPower->jumlah_pekerja.']', 25));?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
								<?php
								$materialObj = $buhar->getMaterialProyek($bukuHarian->getBukuHarianId());
								if(!empty($materialObj))
								{
									?>
									<ol style="padding-left:20px;">
									<?php
									foreach($materialObj as $idx=>$materialProyek)
									{
										$textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $materialProyek->nama, $materialProyek->satuan, $materialProyek->onsite, $materialProyek->terpasang);
										?>
										<li><?php echo nl2br(PicoStringUtil::wordChunk($textNode, 25));?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								?>
								</td>
								<td>
								<?php
								$peralatanObj = $buhar->getPeralatanProyek($bukuHarian->getBukuHarianId());
								if(!empty($peralatanObj))
								{
									?>
									<ol style="padding-left:20px;">
									<?php
									foreach($peralatanObj as $idx=>$peralatanProyek)
									{
										?>
										<li><?php echo nl2br(PicoStringUtil::wordChunk($peralatanProyek->peralatan. ' ['.$peralatanProyek->jumlah.']', 25));?></li>
										<?php
									}
									?>
									</ol>
									<?php
								}
								$latitude = $bukuHarian->getLatitude();
								$longitude = $bukuHarian->getLongitude();
								$zoom = 15;
								$googleMapsUrl = "https://www.google.com/maps/@$latitude,$longitude,{$zoom}z";
								?>
								</td>
								<td data-col-name="status_acc_koordinator"><?php echo $bukuHarian->getStatusAccKoordinator();?></td>
								<td data-col-name="status_acc_ktsk"><?php echo $bukuHarian->getStatusAccKtsk();?></td>
								<td><a href="<?php echo $googleMapsUrl;?>" target="_blank"><?php echo $appLanguage->getKoordinat();?></a></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
						<?php 
						if($appUserImpl->getTipePengguna() == "supervisor" || $appUserImpl->getTipePengguna() == "ktsk")
						{
							?>
							<button type="submit" class="btn btn-success" name="user_action" value="approve"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="reject"><?php echo $appLanguage->getButtonReject();?></button>
							<?php
						}
						else if($userPermission->isAllowedUpdate())
						{ 
							?>
							<button type="submit" class="btn btn-success" name="user_action" value="activate"><?php echo $appLanguage->getButtonActivate();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="deactivate"><?php echo $appLanguage->getButtonDeactivate();?></button>
							<?php 
						} ?>

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

