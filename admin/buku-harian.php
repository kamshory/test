<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/AppBuilder

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
use Sipro\Entity\Data\BukuHarian;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Proyek;
use Sipro\Entity\Data\SupervisorMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "buku-harian", "Buku Harian");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$bukuHarian = new BukuHarian(null, $database);
			try
			{
				$bukuHarian->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->buku_harian_id, $rowId))
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
			$bukuHarian = new BukuHarian(null, $database);
			try
			{
				$bukuHarian->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->buku_harian_id, $rowId))
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
if($inputGet->getUserAction() == UserAction::DETAIL)
{
	require_once $appInclude->mainAppHeader(__DIR__);	
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
		
			<?php
			include_once __DIR__ . "/buku-harian-core.php";
			?>
			<script type="text/javascript" src="../lib.assets/js/filesaver.js"></script>
			<script type="text/javascript" src="../lib.assets/js/html-docx.js"></script>
			
			<div class="button-area">
				<button type="button" id="download" class="btn btn-primary" ><?php echo $appLanguage->getButtonDownload();?></button>
				<button type="button" id="pdf" class="btn btn-primary" onclick="window.open('buku-harian-pdf.php?buku_harian_id=<?php echo $bukuHarianId;?>')" ><?php echo $appLanguage->getButtonPrintPdf();?></button>
				<button type="button" id="showall" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>'" ><?php echo $appLanguage->getButtonShowAll();?></button>
			</div>
		</form>
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
		
}
else 
{
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentUser->getLanguageId());
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
							<select name="proyek_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Proyek(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisor();?></span>
					<span class="filter-control">
							<select name="supervisor_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									//->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId()))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorId())
								->setTextNodeFormat('%s (%s), nama, jabatan.nama')
								->setIndent(8)
								; ?>
							</select>
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getDari();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="tanggal_awal" value="<?php echo $inputGet->getTanggalAwal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);?>" autocomplete="off">
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getHingga();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="tanggal_akhir" value="<?php echo $inputGet->getTanggalAkhir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);?>" autocomplete="off">
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>

			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php 	
			
			$specMap = array(
			    "proyekId" => PicoSpecification::filter("proyekId", "number"),
				"supervisorId" => PicoSpecification::filter("supervisorId", "number")
			);
			$sortOrderMap = array(
			    "proyekId" => "proyekId",
				"supervisorId" => "supervisorId",
				"tanggal" => "tanggal",
				"latitude" => "latitude",
				"longitude" => "longitude",
				"ktskId" => "ktskId",
				"accKtsk" => "accKtsk",
				"koordinatorId" => "koordinatorId",
				"accKoordinator" => "accKoordinator",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			// Additional filter here
			//$specification->addAnd(PicoPredicate::getInstance()->equals('ktskId', $currentUser->getKtskId()));

			if($inputGet->getTanggalAwal() != "")
			{
				$specification->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals(Field::of()->tanggal, $inputGet->getTanggalAwal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true)));
			}
			if($inputGet->getTanggalAkhir() != "")
			{
				$specification->addAnd(PicoPredicate::getInstance()->lessThanOrEquals(Field::of()->tanggal, $inputGet->getTanggalAkhir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true)));
			}
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "waktuBuat", 
					"sortType" => PicoSort::ORDER_TYPE_DESC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
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
			"koordinatorId" => array(
				"columnName" => "koordinator_id",
				"entityName" => "SupervisorMin",
				"tableName" => "supervisor",
				"primaryKey" => "supervisor_id",
				"objectName" => "koordinator",
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
			try{
				$pageData = $dataLoader->findAll($specification, $pageable, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
				if($pageData->getTotalResult() > 0)
				{		
				    $pageControl = $pageData->getPageControl("page", $currentModule->getSelf())
				    ->setNavigation(
				    '<i class="fa-solid fa-angle-left"></i>', '<i class="fa-solid fa-angle-right"></i>',
				    '<i class="fa-solid fa-angles-left"></i>', '<i class="fa-solid fa-angles-right"></i>'
				    )
				    ->setMargin($appConfig->getData()->getPageMargin())
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
								<td class="data-controll data-selector" data-key="buku_harian_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-buku-harian-id"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
									<span class="fa fa-clock-rotate-left"></span></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="acc_ktsk" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAccKtsk();?></a></td>
								<td data-col-name="koordinator_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKoordinatorId();?></a></td>
								<td data-col-name="acc_koordinator" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAccKoordinator();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<td><a href="#"><?php echo $appLanguage->getLocation();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($bukuHarian = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="buku_harian_id">
									<input type="checkbox" class="checkbox check-slave checkbox-buku-harian-id" name="checked_row_id[]" value="<?php echo $bukuHarian->getBukuHarianId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>"><span class="fa fa-folder"></span></a>
									<a class="detail-control field-master" href="time-sheet.php?supervisor_id=<?php echo $bukuHarian->getSupervisorId();?>&periode_id=<?php echo substr(str_replace('-', '', $bukuHarian->getTanggal()), 0, 6);?>"><span class="fa fa-clock-rotate-left"></span></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="tanggal"><?php echo $bukuHarian->getTanggal();?></td>
								<td data-col-name="ktsk_id"><?php echo $bukuHarian->hasValueKtsk() ? $bukuHarian->getKtsk()->getNama() : "";?></td>
								<td data-col-name="acc_ktsk"><?php echo $bukuHarian->optionAccKtsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="koordinator_id"><?php echo $bukuHarian->hasValueKoordinator() ? $bukuHarian->getKoordinator()->getNama() : "";?></td>
								<td data-col-name="acc_koordinator"><?php echo $bukuHarian->optionAccKoordinator($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="aktif"><?php echo $bukuHarian->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td><a href="https://www.google.com/maps/@<?php echo $bukuHarian->getLatitude();?>,<?php echo $bukuHarian->getLongitude();?>,14z" target="_blank"><?php echo $appLanguage->getLocation();?></a></td>
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
