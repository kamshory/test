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
use Sipro\Entity\Data\Proyek;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Ktsk;


require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, null, "/", "proyek", $appLanguage->getProyek());
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$proyek = new Proyek(null, $database);
	try{
		$subqueryMap = array(
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "Ktsk",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		)
		);
		$proyek->findOneWithPrimaryKeyValue($inputGet->getProyekId(), $subqueryMap);
		if($proyek->issetProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($proyek->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $proyek->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $proyek->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td><?php echo $proyek->getDeskripsi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td><?php echo $proyek->getPekerjaan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td><?php echo $proyek->getKodeLokasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td><?php echo $proyek->getNomorKontrak();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSla();?></td>
						<td><?php echo $proyek->getNomorSla();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPelaksana();?></td>
						<td><?php echo $proyek->getPelaksana();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPemberiKerja();?></td>
						<td><?php echo $proyek->getPemberiKerja();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td><?php echo $proyek->issetKtsk() ? $proyek->getKtsk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getGaleri();?></td>
						<td><?php echo $proyek->getGaleri();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td><?php echo $proyek->getTanggalMulai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td><?php echo $proyek->getTanggalSelesai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td><?php echo $proyek->getPersen();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $proyek->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $proyek->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $proyek->getAktif();?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->proyek_id, $proyek->getProyekId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="proyek_id" value="<?php echo $proyek->getProyekId();?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
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
require_once __DIR__ . "/inc.app/header-supervisor.php";
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
}
else 
{
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"ktskId" => PicoSpecification::filter("ktskId", "number")
);
$sortOrderMap = array(
	"nama" => "nama",
	"deskripsi" => "deskripsi",
	"pekerjaan" => "pekerjaan",
	"kodeLokasi" => "kodeLokasi",
	"nomorKontrak" => "nomorKontrak",
	"nomorSla" => "nomorSla",
	"pelaksana" => "pelaksana",
	"pemberiKerja" => "pemberiKerja",
	"ktskId" => "ktskId",
	"galeri" => "galeri",
	"tanggalMulai" => "tanggalMulai",
	"tanggalSelesai" => "tanggalSelesai",
	"persen" => "persen",
	"waktuBuat" => "waktuBuat",
	"waktuUbah" => "waktuUbah",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd(['aktif', true]);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "waktuBuat", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new Proyek(null, $database);

$subqueryMap = array(
"ktskId" => array(
	"columnName" => "ktsk_id",
	"entityName" => "Ktsk",
	"tableName" => "ktsk",
	"primaryKey" => "ktsk_id",
	"objectName" => "ktsk",
	"propertyName" => "nama"
)
);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getKtsk();?></span>
					<span class="filter-control">
							<select class="form-control" name="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Ktsk(null, $database), 
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
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				
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
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="pekerjaan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPekerjaan();?></a></td>
								<td data-col-name="kode_lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKodeLokasi();?></a></td>
								<td data-col-name="nomor_kontrak" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorKontrak();?></a></td>
								<td data-col-name="nomor_sla" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorSla();?></a></td>
								<td data-col-name="pelaksana" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPelaksana();?></a></td>
								<td data-col-name="pemberi_kerja" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPemberiKerja();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="tanggal_mulai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalMulai();?></a></td>
								<td data-col-name="tanggal_selesai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalSelesai();?></a></td>
								<td data-col-name="persen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersen();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($proyek = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">

								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->proyek_id, $proyek->getProyekId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama"><?php echo $proyek->getNama();?></td>
								<td data-col-name="pekerjaan"><?php echo $proyek->getPekerjaan();?></td>
								<td data-col-name="kode_lokasi"><?php echo $proyek->getKodeLokasi();?></td>
								<td data-col-name="nomor_kontrak"><?php echo $proyek->getNomorKontrak();?></td>
								<td data-col-name="nomor_sla"><?php echo $proyek->getNomorSla();?></td>
								<td data-col-name="pelaksana"><?php echo $proyek->getPelaksana();?></td>
								<td data-col-name="pemberi_kerja"><?php echo $proyek->getPemberiKerja();?></td>
								<td data-col-name="ktsk_id"><?php echo $proyek->issetKtsk() ? $proyek->getKtsk()->getNama() : "";?></td>
								<td data-col-name="tanggal_mulai"><?php echo $proyek->getTanggalMulai();?></td>
								<td data-col-name="tanggal_selesai"><?php echo $proyek->getTanggalSelesai();?></td>
								<td data-col-name="persen"><?php echo $proyek->getPersen();?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
/*ajaxSupport*/
}

