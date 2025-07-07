<?php

use MagicApp\Field;
use MagicApp\UserAction;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\AppEntityLanguageImpl;
use Sipro\Entity\Data\DaftarSimak;
use Sipro\Entity\Data\ItemSimakNative;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

if($inputGet->getUserAction() == 'print')
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
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// Define map here
			
?>

<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<table class="table-bordered" width="100%">
			<tbody>
				<tr>
					<td width="22%">
						<?php echo $appEntityLanguage->getTanggalMulai();?>
					</td>
					<td width="28%">
						<?php echo $daftarSimak->getTanggalMulai();?>
					</td>
					<td width="22%">
						<?php echo $appEntityLanguage->getTanggalSelesai();?>
					</td>
					<td width="28%">
						<?php echo $daftarSimak->getTanggalSelesai();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getPekerjaan();?>
					</td>
					<td>
						<?php echo $daftarSimak->getPekerjaan();?>
					</td>
					<td>
						<?php echo $appEntityLanguage->getJenisDokumen();?>
					</td>
					<td>
						<?php echo $daftarSimak->getJenisDokumen();?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $appEntityLanguage->getNomorKontrak();?>
					</td>
					<td colspan="3">
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
					<td>
						<?php echo $appEntityLanguage->getNomorDokumen();?>
					</td>
					<td>
						<?php echo $daftarSimak->getNomorDokumen();?>
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
