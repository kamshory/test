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
use Sipro\AppEntityLanguageImpl;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use Sipro\AppIncludeImpl;
use Sipro\AppUserPermissionImpl;
use Sipro\Entity\Data\DaftarSimak;
use Sipro\Entity\Data\FormulirSimakUmk;
use Sipro\Entity\Data\ItemSimakNative;
use Sipro\Entity\Data\ProyekUmk;
use Sipro\Entity\Data\Tsk;
use Sipro\Entity\Data\UmkMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "daftar-simak", $appLanguage->getDaftarSimak());
$userPermission = new AppUserPermissionImpl($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

$dataFilter = null;

if($currentUser->getTskId() != 0)
{
	$dataFilter = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $currentUser->getTskId()))
		;
}

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->daftarSimakId, $inputGet->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$daftarSimak = new DaftarSimak(null, $database);
	try{
		$subqueryMap = array(
		"umkId" => array(
			"columnName" => "umk_id",
			"entityName" => "UmkMin",
			"tableName" => "umk",
			"primaryKey" => "umk_id",
			"objectName" => "umk",
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
		"formulirSimakId" => array(
			"columnName" => "formulir_simak_id",
			"entityName" => "FormulirSimak",
			"tableName" => "formulir_simak",
			"primaryKey" => "formulir_simak_id",
			"objectName" => "formulir_simak",
			"propertyName" => "nama"
		), 
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
		$daftarSimak->findOne($specification, null, $subqueryMap);
		if($daftarSimak->issetDaftarSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($daftarSimak->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $daftarSimak->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUmk();?></td>
						<td><?php echo $daftarSimak->issetUmk() ? $daftarSimak->getUmk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $daftarSimak->issetProyek() ? $daftarSimak->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFormulirSimak();?></td>
						<td><?php echo $daftarSimak->issetFormulirSimak() ? $daftarSimak->getFormulirSimak()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $daftarSimak->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td><?php echo $daftarSimak->getNomorFormulir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td><?php echo $daftarSimak->getTanggalMulai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td><?php echo $daftarSimak->getTanggalSelesai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td><?php echo $daftarSimak->getPekerjaan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td><?php echo $daftarSimak->getNomorKontrak();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td><?php echo $daftarSimak->getLokasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulDokumen();?></td>
						<td><?php echo $daftarSimak->getJudulDokumen();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorDokumen();?></td>
						<td><?php echo $daftarSimak->getNomorDokumen();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCatatan();?></td>
						<td><?php echo $daftarSimak->getCatatan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $daftarSimak->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $daftarSimak->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td>
							<?php echo $appEntityLanguage->getKontributor();?>
						</td>
						
						<td>
							<?php echo $daftarSimak->daftarKontributor();?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			$itemSimakFinder = new ItemSimakNative(null, $database);
			$itemSimakList = $itemSimakFinder->itemSimakList($daftarSimak->getDaftarSimakId());
			if($itemSimakList && !empty($itemSimakList))
			{
				?>
				<table class="table table-row">
					<thead>
						<tr>
							<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
							<td><?php echo $appLanguage->getPemeriksaan();?></td>
							<td><?php echo $appLanguage->getJenisPemeriksaan();?></td>
							<td><?php echo $appLanguage->getLabelYa();?></td>
							<td><?php echo $appLanguage->getLabelTidak();?></td>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($itemSimakList as $itemSimak)
						{
							$iconYa = $itemSimak->getNilai() == 1 ? "fa-regular fa-square-check" : "fa-regular fa-square"; // NOSONAR
							$iconTidak = $itemSimak->getNilai() == 2 ? "fa-regular fa-square-check" : "fa-regular fa-square"; // NOSONAR
							?>
							<tr>
								<td class="data-controll data-number"><?php echo $itemSimak->getItemSimakId();?></td>
								<td><?php echo $itemSimak->getPemeriksaanNama();?></td>
								<td><?php echo $itemSimak->getJenisPemeriksaanNama();?></td>
								<td><i class="fa <?php echo $iconYa;?>"></i> <?php echo $itemSimak->getLabelYa();?></td>
								<td><i class="fa <?php echo $iconTidak;?>"></i> <?php echo $itemSimak->getLabelTidak();?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else
			{
				?>
				<div class="alert alert-warning">
					<?php
					echo $appLanguage->getMessageDataNotFound();
					?>
				</div>
				<?php
			}
			?>


			<div class="button-area">

				<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
				<input type="hidden" name="daftar_simak_id" id="primary_key_value" value="<?php echo $daftarSimak->getDaftarSimakId();?>"/>
			</div>
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
else if($inputGet->getUserAction() == 'print')
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->daftarSimakId, $inputGet->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$daftarSimak = new DaftarSimak(null, $database);
	try{
		$subqueryMap = array(
		"umkId" => array(
			"columnName" => "umk_id",
			"entityName" => "UmkMin",
			"tableName" => "umk",
			"primaryKey" => "umk_id",
			"objectName" => "umk",
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
		"formulirSimakId" => array(
			"columnName" => "formulir_simak_id",
			"entityName" => "FormulirSimak",
			"tableName" => "formulir_simak",
			"primaryKey" => "formulir_simak_id",
			"objectName" => "formulir_simak",
			"propertyName" => "nama"
		), 
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
		$daftarSimak->findOne($specification, null, $subqueryMap);
		if($daftarSimak->issetDaftarSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>

<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<table class="table-bordered" width="100%">
			<tbody>
				<tr>
					<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
					<td><?php echo $daftarSimak->getTanggalMulai();?></td>
				</tr>
				<tr>
					<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
					<td><?php echo $daftarSimak->getTanggalSelesai();?></td>
				</tr>
				
				<tr>
					<td>
						<?php echo $appEntityLanguage->getPekerjaan();?>
					</td>
					<td>
						<?php echo $daftarSimak->getPekerjaan();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getNomorKontrak();?>
					</td>
					<td>
						<?php echo $daftarSimak->getNomorKontrak();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getLokasi();?>
					</td>
					<td>
						<?php echo $daftarSimak->getLokasi();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getJudulDokumen();?>
					</td>
					
					<td>
						<?php echo $daftarSimak->getJudulDokumen();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getNomorDokumen();?>
					</td>
					
					<td>
						<?php echo $daftarSimak->getNomorDokumen();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getKontributor();?>
					</td>
					
					<td>
						<?php echo $daftarSimak->daftarKontributor();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getCatatan();?>
					</td>
					
					<td>
						<?php echo $daftarSimak->getCatatan();?>
					</td>
				</tr>
		</table>
		<p>&nbsp;</p>
		<form name="detailform" id="detailform" action="" method="post">
			
			
			<?php
			$itemSimakFinder = new ItemSimakNative(null, $database);
			$itemSimakList = $itemSimakFinder->itemSimakList($daftarSimak->getDaftarSimakId());

			if ($itemSimakList && !empty($itemSimakList)) {
				?>
				<table class="table-bordered table-word" >
					<thead>
						<tr>
							<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
							<td><?php echo $appLanguage->getPemeriksaan();?></td>
							<td colspan=2"><?php echo $appLanguage->getJenisPemeriksaan();?></td>
							<td colspan="2" nowrap><?php echo $appLanguage->getHasilPemeriksaan();?></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$previousPemeriksaanId = null;
						$rowspanCount = 0;
						$currentIndex = 0;

						foreach ($itemSimakList as $index => $itemSimak) {
							// Check if the current pemeriksaanId is the same as the previous one
							if ($itemSimak->getPemeriksaanId() === $previousPemeriksaanId) {
								$rowspanCount++;
							} else {
								// If it's a new pemeriksaanId, and there was a previous one,
								// we need to update the rowspan for the previous block
								if ($previousPemeriksaanId !== null) {
									// Find the starting index of the previous block
									$startIndex = $currentIndex - $rowspanCount;
									// You would typically store this information in an array or re-render
									// For a direct HTML output like this, we need to prepare the data first
								}

								// Reset for the new pemeriksaanId
								$previousPemeriksaanId = $itemSimak->getPemeriksaanId();
								$rowspanCount = 1;
							}

							// To correctly apply rowspan, we need to know the total count
							// before rendering the first row of a group.
							// A two-pass approach or pre-processing the list is more robust.
							// Let's pre-process the list to get the rowspan counts.
							$itemSimakList[$index]->rowspan = 0; // Initialize rowspan
						}

						// --- Pre-processing the list to determine rowspans ---
						$processedItemSimakList = [];
						$tempGroup = [];

						foreach ($itemSimakList as $item) {
							if (empty($tempGroup) || $tempGroup[0]->getPemeriksaanId() === $item->getPemeriksaanId()) {
								$tempGroup[] = $item;
							} else {
								// Process the completed group
								$groupSize = count($tempGroup);
								foreach ($tempGroup as $i => $groupedItem) {
									if ($i === 0) {
										$groupedItem->rowspan = $groupSize; // Set rowspan for the first item in the group
									} else {
										$groupedItem->rowspan = 0; // Subsequent items in the group don't get a rowspan
									}
									$processedItemSimakList[] = $groupedItem;
								}
								$tempGroup = [$item]; // Start a new group
							}
						}
						// Add the last group
						if (!empty($tempGroup)) {
							$groupSize = count($tempGroup);
							foreach ($tempGroup as $i => $groupedItem) {
								if ($i === 0) {
									$groupedItem->rowspan = $groupSize;
								} else {
									$groupedItem->rowspan = 0;
								}
								$processedItemSimakList[] = $groupedItem;
							}
						}
						// --- End of pre-processing ---

						$parentNumber = 1; // Assuming you want to start numbering from 1 for the first pemeriksaan
						$rowNumber = 1; // For sequential numbering as per your example
						foreach ($processedItemSimakList as $itemSimak) {
							$iconYa = $itemSimak->getNilai() == 1 ? "fa-regular fa-square-check" : "fa-regular fa-square";
							$iconTidak = $itemSimak->getNilai() == 2 ? "fa-regular fa-square-check" : "fa-regular fa-square";
							?>
							<tr>
								<?php if ($itemSimak->rowspan > 0)
								{ 
								?>
									<td rowspan="<?php echo $itemSimak->rowspan; ?>" class="data-controll data-number"><?php echo $parentNumber;?></td>
									<td rowspan="<?php echo $itemSimak->rowspan; ?>"><?php echo $itemSimak->getPemeriksaanNama();?></td>
								<?php 
								$parentNumber++;
								}
								?>
								<td class="row-number"><?php echo $rowNumber;?>.</td>
								<td><?php echo $itemSimak->getJenisPemeriksaanNama();?></td>
								<td nowrap><i class="fa <?php echo $iconYa;?>"></i> <?php echo $itemSimak->getLabelYa();?></td>
								<td nowrap><i class="fa <?php echo $iconTidak;?>"></i> <?php echo $itemSimak->getLabelTidak();?></td>
							</tr>
							<?php
							$rowNumber++;
						}
						?>
					</tbody>
				</table>
				<?php
			} else {
				echo "<div class='alert alert-warning'>".$appLanguage->getMessageDataNotFound()."</div>";
			}
			?>


			<div class="button-area">
				<?php if($userPermission->isAllowedUpdate()){ ?>
				<button type="button" class="btn btn-primary" id="update_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
				<?php } ?>

				<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
				<input type="hidden" name="daftar_simak_id" id="primary_key_value" value="<?php echo $daftarSimak->getDaftarSimakId();?>"/>
			</div>
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
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"umkId" => PicoSpecification::filter("umkId", "number"),
	"tskId" => PicoSpecification::filter("tskId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"formulirSimakId" => PicoSpecification::filter("formulirSimakId", "number")
);
$sortOrderMap = array(
	"umkId" => "umkId",
	"tskId" => "tskId",
	"proyekId" => "proyekId",
	"formulirSimakId" => "formulirSimakId",
	"nama" => "nama",
	"nomorFormulir" => "nomorFormulir",
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
		"sortBy" => "proyekId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	),
	array(
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new DaftarSimak(null, $database);

$subqueryMap = array(
"umkId" => array(
	"columnName" => "umk_id",
	"entityName" => "UmkMin",
	"tableName" => "umk",
	"primaryKey" => "umk_id",
	"objectName" => "umk",
	"propertyName" => "nama"
), 
"tskId" => array(
	"columnName" => "tsk_id",
	"entityName" => "TskMin",
	"tableName" => "tsk",
	"primaryKey" => "tsk_id",
	"objectName" => "tsk",
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
"formulirSimakId" => array(
	"columnName" => "formulir_simak_id",
	"entityName" => "FormulirSimak",
	"tableName" => "formulir_simak",
	"primaryKey" => "formulir_simak_id",
	"objectName" => "formulir_simak",
	"propertyName" => "judul_formulir"
)
);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);



$specsTsk = PicoSpecification::getInstance()
	->addAnd(new PicoPredicate(Field::of()->aktif, true))
	->addAnd(new PicoPredicate(Field::of()->draft, false));
if($inputGet->getUmkId() != "")
{
	$specsTsk->addAnd(new PicoPredicate(Field::of()->umkId, $inputGet->getUmkId()));
}
$specsFormulirSimak = PicoSpecification::getInstance()
	->addAnd(new PicoPredicate(Field::of()->aktif, true))
	->addAnd(new PicoPredicate(Field::of()->draft, false));
if($inputGet->getUmkId() != "")
{
	$specsFormulirSimak->addAnd(new PicoPredicate(Field::of()->umkId, $inputGet->getUmkId()));
}

$specsProyek = PicoSpecification::getInstance()
	->addAnd(new PicoPredicate(Field::of()->aktif, true))
	->addAnd(new PicoPredicate(Field::of()->draft, false));
if($inputGet->getUmkId())
{
	$specsProyek->addAnd(new PicoPredicate(Field::of()->umkId, $inputGet->getUmkId()));
}
if($inputGet->getTskId())
{
	$specsProyek->addAnd(new PicoPredicate(Field::of()->tskId, $inputGet->getTskId()));
}
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getUmk();?></span>
					<span class="filter-control">
							<select class="form-control" name="umk_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UmkMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->umkId, Field::of()->nama, $inputGet->getUmkId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTsk();?></span>
					<span class="filter-control">
							<select class="form-control" name="tsk_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Tsk(null, $database), 
								$specsTsk, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tskId, Field::of()->nama, $inputGet->getTskId())
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekUmk(null, $database), 
								$specsProyek, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getFormulirSimak();?></span>
					<span class="filter-control">
							<select class="form-control" name="formulir_simak_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new FormulirSimakUmk(null, $database), 
								$specsFormulirSimak, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->formulirSimakId, Field::of()->nama, $inputGet->getFormulirSimakId())
								->setTextNodeFormat('"%s &raquo; %s", nama, jumlahDaftarSimak')
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								
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
								
								
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
									<span class="fa fa-print"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="umk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getUmk();?></a></td>
								<td data-col-name="tsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTsk();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="judul_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNamaFormulir();?></a></td>
								<td data-col-name="nomor_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorFormulir();?></a></td>
								<td data-col-name="persentase" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersentase();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($daftarSimak = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $daftarSimak->getDaftarSimakId();?>" data-sort-order="<?php echo $daftarSimak->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $daftarSimak->optionAktif('true', 'false');?>">
								
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>"><span class="fa fa-folder"></span></a>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl('print', Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>"><span class="fa fa-print"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="umk_id"><?php echo $daftarSimak->issetUmk() ? $daftarSimak->getUmk()->getNama() : "";?></td>
								<td data-col-name="tsk_id"><?php echo $daftarSimak->issetTsk() ? $daftarSimak->getTsk()->getNama() : "";?></td>
								<td data-col-name="proyek_id"><?php echo $daftarSimak->issetProyek() ? $daftarSimak->getProyek()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $daftarSimak->issetFormulirSimak() ? $daftarSimak->getFormulirSimak()->getJudulFormulir() : "";?></td>
								<td data-col-name="nomor_formulir"><?php echo $daftarSimak->getNomorFormulir();?></td>
								<td data-col-name="persentase"><?php echo $daftarSimak->numberFormatPersentase(2, ".", ",");?></td>
								<td data-col-name="aktif"><?php echo $daftarSimak->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">

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

