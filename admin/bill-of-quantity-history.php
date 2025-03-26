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
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicApp\AppUserPermission;
use Sipro\Entity\Data\BillOfQuantityHistory;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\ProyekMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "bill-of-quantity-history", $appLanguage->getBillOfQuantityHistory());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}
if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$billOfQuantityHistory = new BillOfQuantityHistory(null, $database);
	try{
		$subqueryMap = array(
		"billOfQuantityId" => array(
			"columnName" => "bill_of_quantity_id",
			"entityName" => "BillOfQuantityMin",
			"tableName" => "bill_of_quantity",
			"primaryKey" => "bill_of_quantity_id",
			"objectName" => "bill_of_quantity",
			"propertyName" => "nama"
		), 
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"parentId" => array(
			"columnName" => "parent_id",
			"entityName" => "BillOfQuantityMin",
			"tableName" => "bill_of_quantity",
			"primaryKey" => "bill_of_quantity_id",
			"objectName" => "parent",
			"propertyName" => "nama"
		),
		"pembuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
			"propertyName" => "nama_depan"
		), 
		"pengubah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama_depan"
		)
		);
		$billOfQuantityHistory->findOneWithPrimaryKeyValue($inputGet->getBillOfQuantityHistoryId(), $subqueryMap);
		if($billOfQuantityHistory->issetBillOfQuantityHistoryId())
		{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityHistory(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($billOfQuantityHistory->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $billOfQuantityHistory->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
						<td><?php echo $billOfQuantityHistory->issetBillOfQuantity() ? $billOfQuantityHistory->getBillOfQuantity()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $billOfQuantityHistory->issetProyek() ? $billOfQuantityHistory->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getParent();?></td>
						<td><?php echo $billOfQuantityHistory->issetParent() ? $billOfQuantityHistory->getParent()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLevel();?></td>
						<td><?php echo $billOfQuantityHistory->getLevel();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $billOfQuantityHistory->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSatuan();?></td>
						<td><?php echo $billOfQuantityHistory->getSatuan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td><?php echo $billOfQuantityHistory->getVolume();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBobot();?></td>
						<td><?php echo $billOfQuantityHistory->getBobot();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolumeProyek();?></td>
						<td><?php echo $billOfQuantityHistory->getVolumeProyek();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td><?php echo $billOfQuantityHistory->getPersen();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbahVolumeProyek();?></td>
						<td><?php echo $billOfQuantityHistory->getWaktuUbahVolumeProyek();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHarga();?></td>
						<td><?php echo $billOfQuantityHistory->getHarga();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $billOfQuantityHistory->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPembuat();?></td>
						<td><?php echo $billOfQuantityHistory->issetPembuat() ? $billOfQuantityHistory->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPengubah();?></td>
						<td><?php echo $billOfQuantityHistory->issetPengubah() ? $billOfQuantityHistory->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $billOfQuantityHistory->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $billOfQuantityHistory->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $billOfQuantityHistory->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $billOfQuantityHistory->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $billOfQuantityHistory->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="bill_of_quantity_history_id" value="<?php echo $billOfQuantityHistory->getBillOfQuantityHistoryId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityHistory(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"billOfQuantityId" => PicoSpecification::filter("billOfQuantityId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"parentId" => PicoSpecification::filter("parentId", "number"),
	"level" => PicoSpecification::filter("level", "number"),
	"nama" => PicoSpecification::filter("nama", "fulltext")
);
$sortOrderMap = array(
	"billOfQuantityId" => "billOfQuantityId",
	"proyekId" => "proyekId",
	"parentId" => "parentId",
	"level" => "level",
	"nama" => "nama",
	"satuan" => "satuan",
	"volume" => "volume",
	"bobot" => "bobot",
	"volumeProyek" => "volumeProyek",
	"persen" => "persen",
	"waktuUbahVolumeProyek" => "waktuUbahVolumeProyek",
	"harga" => "harga",
	"sortOrder" => "sortOrder",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "billOfQuantityHistoryId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new BillOfQuantityHistory(null, $database);

$subqueryMap = array(
"billOfQuantityId" => array(
	"columnName" => "bill_of_quantity_id",
	"entityName" => "BillOfQuantityMin",
	"tableName" => "bill_of_quantity",
	"primaryKey" => "bill_of_quantity_id",
	"objectName" => "bill_of_quantity",
	"propertyName" => "nama"
), 
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"parentId" => array(
	"columnName" => "parent_id",
	"entityName" => "BillOfQuantityMin",
	"tableName" => "bill_of_quantity",
	"primaryKey" => "bill_of_quantity_id",
	"objectName" => "parent",
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
		$appEntityLanguage->getBillOfQuantityHistoryId() => $headerFormat->getBillOfQuantityHistoryId(),
		$appEntityLanguage->getBillOfQuantity() => $headerFormat->asString(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getParent() => $headerFormat->asString(),
		$appEntityLanguage->getLevel() => $headerFormat->getLevel(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getSatuan() => $headerFormat->getSatuan(),
		$appEntityLanguage->getVolume() => $headerFormat->getVolume(),
		$appEntityLanguage->getBobot() => $headerFormat->getBobot(),
		$appEntityLanguage->getVolumeProyek() => $headerFormat->getVolumeProyek(),
		$appEntityLanguage->getPersen() => $headerFormat->getPersen(),
		$appEntityLanguage->getWaktuUbahVolumeProyek() => $headerFormat->getWaktuUbahVolumeProyek(),
		$appEntityLanguage->getHarga() => $headerFormat->getHarga(),
		$appEntityLanguage->getSortOrder() => $headerFormat->getSortOrder(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->getAdminBuat(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->getAdminUbah(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
        
		return array(
			sprintf("%d", $index + 1),
			$row->getBillOfQuantityHistoryId(),
			$row->issetBillOfQuantity() ? $row->getBillOfQuantity()->getNama() : "",
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetParent() ? $row->getParent()->getNama() : "",
			$row->getLevel(),
			$row->getNama(),
			$row->getSatuan(),
			$row->getVolume(),
			$row->getBobot(),
			$row->getVolumeProyek(),
			$row->getPersen(),
			$row->getWaktuUbahVolumeProyek(),
			$row->getHarga(),
			$row->getSortOrder(),
			$row->getAdminBuat(),
			$row->getAdminUbah(),
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getIpBuat(),
			$row->getIpUbah(),
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
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getLevel();?></span>
					<span class="filter-control">
							<select class="form-control" name="level" data-value="<?php echo $inputGet->getLevel();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '1');?>>1</option>
								<option value="2" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '2');?>>2</option>
								<option value="3" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '3');?>>3</option>
								<option value="4" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '4');?>>4</option>
								<option value="5" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '5');?>>5</option>
								<option value="6" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '6');?>>6</option>
								<option value="7" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '7');?>>7</option>
								<option value="8" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '8');?>>8</option>
								<option value="9" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '9');?>>9</option>
								<option value="10" <?php echo AppFormBuilder::selected($inputGet->getLevel(), '10');?>>10</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
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
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="level" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLevel();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="satuan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSatuan();?></a></td>
								<td data-col-name="volume" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getVolume();?></a></td>
								<td data-col-name="bobot" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBobot();?></a></td>
								<td data-col-name="volume_proyek" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getVolumeProyek();?></a></td>
								<td data-col-name="persen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersen();?></a></td>
								<td data-col-name="waktu_ubah_volume_proyek" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuUbahVolumeProyek();?></a></td>
								<td data-col-name="harga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHarga();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($billOfQuantityHistory = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->bill_of_quantity_history_id, $billOfQuantityHistory->getBillOfQuantityHistoryId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $billOfQuantityHistory->issetProyek() ? $billOfQuantityHistory->getProyek()->getNama() : "";?></td>
								<td data-col-name="level"><?php echo $billOfQuantityHistory->getLevel();?></td>
								<td data-col-name="nama"><?php echo $billOfQuantityHistory->getNama();?></td>
								<td data-col-name="satuan"><?php echo $billOfQuantityHistory->getSatuan();?></td>
								<td data-col-name="volume"><?php echo $billOfQuantityHistory->getVolume();?></td>
								<td data-col-name="bobot"><?php echo $billOfQuantityHistory->getBobot();?></td>
								<td data-col-name="volume_proyek"><?php echo $billOfQuantityHistory->getVolumeProyek();?></td>
								<td data-col-name="persen"><?php echo $billOfQuantityHistory->getPersen();?></td>
								<td data-col-name="waktu_ubah_volume_proyek"><?php echo $billOfQuantityHistory->getWaktuUbahVolumeProyek();?></td>
								<td data-col-name="harga"><?php echo $billOfQuantityHistory->getHarga();?></td>
								<td data-col-name="sort_order"><?php echo $billOfQuantityHistory->getSortOrder();?></td>
								<td data-col-name="aktif"><?php echo $billOfQuantityHistory->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
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

