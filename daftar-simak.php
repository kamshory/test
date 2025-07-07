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
use Sipro\Entity\Data\DaftarSimak;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\FormulirSimak;
use Sipro\Entity\Data\FormulirSimakUmk;
use Sipro\Entity\Data\ItemSimak;
use Sipro\Entity\Data\ItemSimakNative;
use Sipro\Entity\Data\JenisPemeriksaan;
use Sipro\Entity\Data\ProyekUmk;
use Sipro\Entity\Data\RiwayatDaftarSimak;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, null, "/", "daftar-simak", $appLanguage->getDaftarSimak());
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

$dataFilter = PicoSpecification::getInstance()
	->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $currentUser->getTskId()))
	->addAnd(PicoPredicate::getInstance()->equals(Field::of()->draft, false))
	->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true));


if($inputPost->getUserAction() == UserAction::CREATE)
{
	$daftarSimak = new DaftarSimak(null, $database);

	if($currentUser->getTskId() != 0)
	{
		$daftarSimak->setTskId($currentUser->getTskId());
	}
	else
	{
		$daftarSimak->setTskId($inputPost->getTskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	}

	$daftarSimak->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$daftarSimak->setFormulirSimakId($inputPost->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$daftarSimak->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setNomorFormulir($inputPost->getNomorFormulir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setNomorKontrak($inputPost->getNomorKontrak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setLokasi($inputPost->getLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setJudulDokumen($inputPost->getJudulDokumen(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setNomorDokumen($inputPost->getNomorDokumen(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setCatatan($inputPost->getCatatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$daftarSimak->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$daftarSimak->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$daftarSimak->setAdminBuat($currentUser->getAdminId());
	$daftarSimak->setWaktuBuat($currentAction->getTime());
	$daftarSimak->setIpBuat($currentAction->getIp());
	$daftarSimak->setAdminUbah($currentUser->getAdminId());
	$daftarSimak->setWaktuUbah($currentAction->getTime());
	$daftarSimak->setIpUbah($currentAction->getIp());
	try
	{
		$daftarSimak->insert();
		$newId = $daftarSimak->getDaftarSimakId();
		
		$specsFormulirSimak = PicoSpecification::getInstanceOf(Field::of()->formulirSimakId, $daftarSimak->getFormulirSimakId())
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
		;

		$sortable = PicoSortable::getInstance()
			->add(new PicoSort('pemeriksaan.sortOrder', PicoSort::ORDER_TYPE_ASC))
			->add(new PicoSort('sortOrder', PicoSort::ORDER_TYPE_ASC));

		$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
		try
		{
			$pageData = $jenisPemeriksaan->findAll($specsFormulirSimak, null, $sortable);
			foreach($pageData->getResult() as $dataItem)
			{
				$itemSimak = new ItemSimak(null, $database);
				$itemSimak->setUmkId($dataItem->getUmkId());
				$itemSimak->setTskId($currentUser->getTskId());
				$itemSimak->setFormulirSimakId($dataItem->getFormulirSimakId());
				// Set the Proyek ID
				$itemSimak->setProyekId($daftarSimak->getProyekId());
				// Set the new Daftar Simak ID
				$itemSimak->setDaftarSimakId($newId);
				$itemSimak->setPemeriksaanId($dataItem->getPemeriksaanId());
				$itemSimak->setJenisPemeriksaanId($dataItem->getJenisPemeriksaanId());

				$itemSimak->insert();
			}
		}
		catch(Exception $e)
		{
			// Do something here to handle exception
			error_log($e->getMessage());
		}
		// Update FormulirSimakUmk
		(new FormulirSimakUmk(null, $database))->updateJumlahFormulirSimak($daftarSimak->getFormulirSimakId());
		// Update DaftarSimak
		(new DaftarSimak(null, $database))->updateUmk($daftarSimak->getDaftarSimakId());
		// Simpan riwayat


		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->daftar_simak_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}

else if($inputPost->getUserAction() == 'submit')
{
	$daftarSimakId = $inputPost->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
	$specification = PicoSpecification::getInstanceOf(Field::of()->daftarSimakId, $daftarSimakId);
	$specification->addAnd($dataFilter);
	$daftarSimak = new DaftarSimak(null, $database);
	$updater = $daftarSimak->where($specification)
		->setTanggalMulai($inputPost->getTanggalMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTanggalSelesai($inputPost->getTanggalSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setNomorKontrak($inputPost->getNomorKontrak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLokasi($inputPost->getLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setJudulDokumen($inputPost->getJudulDokumen(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setNomorDokumen($inputPost->getNomorDokumen(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setCatatan($inputPost->getCatatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
	;
	$updater->setAdminUbah($currentUser->getAdminId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		
		$newId = $inputPost->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		(new FormulirSimakUmk(null, $database))->updateJumlahFormulirSimak($daftarSimak->getFormulirSimakId());

		// Update isian

		$itemSimakFinder = new ItemSimak(null, $database);

		$pageData = $itemSimakFinder->findByDaftarSimakId($daftarSimakId);
		$diisi = 0;
		$isian = 0;
		foreach($pageData->getResult() as $itemSimak)
		{
			$nilai = isset($inputPost->getItemSimakId()[$itemSimak->getItemSimakId()]) ? $inputPost->getItemSimakId()[$itemSimak->getItemSimakId()] : 0;
			
			$itemSimak->setNilai($nilai);
			if($itemSimak->getNilai() != $nilai)
			{
				$itemSimak->setSupervisorId($currentUser->getSupervisorId());
			}
			$itemSimak->setWaktuInput(date('Y-m-d H:i:s'));
			$itemSimak->setIpInput($_SERVER['REMOTE_ADDR']);
			$itemSimak->update();

			$isian++;
			if($nilai != 0)
			{
				$diisi++;
			}
		}
		$persentase = $isian == 0 ? 0 : (100*$diisi/$isian);
		$updater->setPersentase($persentase);
		$updater->update();


		try
		{
			$riwayatDaftarSimak = new RiwayatDaftarSimak(null, $database);
			$riwayatDaftarSimak->setDaftarSimakId($daftarSimakId);
			$riwayatDaftarSimak->setAdminId($currentAction->getSupervisorId());
			$riwayatDaftarSimak->setWaktuPengisian($currentAction->getTime());
			$riwayatDaftarSimak->setIpPengisian($currentAction->getIp());
			$riwayatDaftarSimak->insert();
		}
		catch(Exception $e)
		{
			// Do nothing
		}

		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->daftar_simak_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}

if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";

$specsProyek = PicoSpecification::getInstance()
	->addAnd(new PicoPredicate(Field::of()->aktif, true))
	->addAnd(new PicoPredicate(Field::of()->draft, false));
if($currentUser->getUmkId())
{
	$specsProyek->addAnd(new PicoPredicate(Field::of()->umkId, $currentUser->getUmkId()));
}
if($currentUser->getTskId())
{
	$specsProyek->addAnd(new PicoPredicate(Field::of()->tskId, $currentUser->getTskId()));
}
$specsFormulirSimak = PicoSpecification::getInstance()
	->addAnd(new PicoPredicate(Field::of()->aktif, true))
	->addAnd(new PicoPredicate(Field::of()->draft, false));

?>
<script>
	$(document).ready(function(){
		$('[name=proyek_id]').change();
		$('[name=formulir_simak_id]').change();
	})
</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" required="required" onchange="this.form.querySelector('#nomor_kontrak').value = this.options[this.selectedIndex].dataset.nomorKontrak; ">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekUmk(null, $database), 
								$specsProyek, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId(), array(Field::of()->nomorKontrak, Field::of()->tanggalMulai, Field::of()->tanggalSelesai))
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFormulirSimak();?></td>
						<td>
							<select class="form-control" name="formulir_simak_id" id="formulir_simak_id" required="required" onchange="this.form.querySelector('#nama').value = this.options[this.selectedIndex].text; this.form.querySelector('#nomor_formulir').value = this.options[this.selectedIndex].dataset.nomorFormulir">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new FormulirSimakUmk(null, $database), 
								$specsFormulirSimak, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->formulirSimakId, Field::of()->judulFormulir, $inputGet->getFormulirSimakId(), array(Field::of()->nomorFormulir))
								->setGroup(Field::of()->prosedurId, Field::of()->nama, Field::of()->prosedur)
								; ?>
							</select>
							<input type="hidden" name="nama" id="nama" value="" />
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_formulir" id="nomor_formulir" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<textarea class="form-control" name="pekerjaan" id="pekerjaan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_kontrak" id="nomor_kontrak" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<textarea class="form-control" name="lokasi" id="lokasi" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="judul_dokumen" id="judul_dokumen" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_dokumen" id="nomor_dokumen" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCatatan();?></td>
						<td>
							<textarea class="form-control" name="catatan" id="catatan" spellcheck="false"></textarea>
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->daftarSimakId, $inputGet->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);

	$specsProyek = PicoSpecification::getInstance()
		->addAnd(new PicoPredicate(Field::of()->aktif, true))
		->addAnd(new PicoPredicate(Field::of()->draft, false));
	if($currentUser->getUmkId())
	{
		$specsProyek->addAnd(new PicoPredicate(Field::of()->umkId, $currentUser->getUmkId()));
	}
	if($currentUser->getTskId())
	{
		$specsProyek->addAnd(new PicoPredicate(Field::of()->tskId, $currentUser->getTskId()));
	}


	$daftarSimak = new DaftarSimak(null, $database);
	try{
		$daftarSimak->findOne($specification);
		if($daftarSimak->issetDaftarSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								$specsProyek, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $daftarSimak->getProyekId())
								->setGroup(Field::of()->umkId, Field::of()->nama, Field::of()->umk)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFormulirSimak();?></td>
						<td>
							<select class="form-control" name="formulir_simak_id" id="formulir_simak_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new FormulirSimak(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->judulFormulir, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->formulirSimakId, Field::of()->nama, $daftarSimak->getFormulirSimakId())
								->setGroup(Field::of()->prosedurId, Field::of()->nama, Field::of()->prosedur)
								; ?>
							</select>
							<input type="hidden" name="nama" id="nama" value="<?php echo $daftarSimak->getNama();?>" />
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_formulir" id="nomor_formulir" value="<?php echo $daftarSimak->getNomorFormulir();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $daftarSimak->getTanggalMulai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo $daftarSimak->getTanggalSelesai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<input type="text" class="form-control" name="pekerjaan" id="pekerjaan" value="<?php echo $daftarSimak->getPekerjaan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_kontrak" id="nomor_kontrak" value="<?php echo $daftarSimak->getNomorKontrak();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<input type="text" class="form-control" name="lokasi" id="lokasi" value="<?php echo $daftarSimak->getLokasi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="judul_dokumen" id="judul_dokumen" value="<?php echo $daftarSimak->getJudulDokumen();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_dokumen" id="nomor_dokumen" value="<?php echo $daftarSimak->getNomorDokumen();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getCatatan();?></td>
						<td>
							<input type="text" class="form-control" name="catatan" id="catatan" value="<?php echo $daftarSimak->getCatatan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="<?php echo $daftarSimak->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $daftarSimak->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">

			<?php
			$itemSimakFinder = new ItemSimakNative(null, $database);
			$itemSimakList = $itemSimakFinder->itemSimakList($daftarSimak->getDaftarSimakId());

			$datarSimakGrouped = array();
			if(!empty($itemSimakList))
			{
				foreach($itemSimakList as $itemSimak)
				{
					if(!isset($datarSimakGrouped[$itemSimak->getPemeriksaanId()]))
					{
						$datarSimakGrouped[$itemSimak->getPemeriksaanId()] = array();
					}
					$datarSimakGrouped[$itemSimak->getPemeriksaanId()][] = $itemSimak;
				}

				?>
				<script>
					$(document).ready(function() {
						// Make checkboxes behave like radio buttons but allow unchecking all
						$(".item_simak_id").on("change", function() {
							var itemSimakId = $(this).attr("name").match(/\d+/)[0];
							var value = $(this).val();
							if ($(this).is(":checked")) {
								// Uncheck the other checkbox in the pair
								if (value == '1') {
									$('input[data-id="' + itemSimakId + '-2"]')[0].checked = false;
								} else if (value == '2') {
									$('input[data-id="' + itemSimakId + '-1"]')[0].checked = false;
								}
							}
							// If unchecked, do nothing (allows both to be unchecked)
						});
					});
				</script>
				<table class="table-bordered table-word" >
					<thead>
						<tr>
							<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
							<td><?php echo $appLanguage->getJenisPemeriksaan();?></td>
							<td colspan="2" nowrap><?php echo $appLanguage->getHasilPemeriksaan();?></td>
						</tr>
					</thead>
					<tbody>
						<?php
							
							foreach($datarSimakGrouped as $pemeriksaanId => $itemSimakGroup)
							{
								$cleanedHtml1 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimakGroup[0]->getPemeriksaanNama());
								?>
								<tr>
									<td colspan="4"><?php echo $cleanedHtml1;?></td>
								<?php
								$rowNumber = 1;
								foreach($itemSimakGroup as $itemSimak)
								{
									$checkedYa = $itemSimak->getNilai() == 1 ? " checked" : "";
									$checkedTidak = $itemSimak->getNilai() == 2 ? " checked" : "";
									$cleanedHtml2 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimak->getJenisPemeriksaanNama());
									?>
									<tr>
										<td><?php echo $rowNumber;?>.</td>
										<td><?php echo $cleanedHtml2;?></td>
										<td><label><input type="checkbox" class="item_simak_id" data-id="<?php echo $itemSimak->getItemSimakId();?>-1" name="item_simak_id[<?php echo $itemSimak->getItemSimakId();?>]" value="1"<?php echo $checkedYa;?>> <?php echo $itemSimak->getLabelYa();?></label></td>
										<td><label><input type="checkbox" class="item_simak_id" data-id="<?php echo $itemSimak->getItemSimakId();?>-2" name="item_simak_id[<?php echo $itemSimak->getItemSimakId();?>]" value="2"<?php echo $checkedTidak;?>> <?php echo $itemSimak->getLabelTidak();?></label></td>
									</tr>
									<?php
								$rowNumber++;
							}
							
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
				<button type="submit" class="btn btn-success" name="user_action" id="update_data" value="update"><?php echo $appLanguage->getButtonSave();?></button>
				<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
				<input type="hidden" name="daftar_simak_id" id="primary_key_value" value="<?php echo $daftarSimak->getDaftarSimakId();?>"/>
			</div>
			
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
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
else if($inputGet->getUserAction() == 'form')
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->daftarSimakId, $inputGet->getDaftarSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$daftarSimak = new DaftarSimak(null, $database);
	try{
		$daftarSimak->findOne($specification);
		if($daftarSimak->issetDaftarSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<?php
							echo $daftarSimak->issetProyek() ? $daftarSimak->getProyek()->getNama() : "";
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulFormulir();?></td>
						<td>
							<?php
							echo $daftarSimak->issetFormulirSimak() ? $daftarSimak->getFormulirSimak()->getJudulFormulir() : "";
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td>
							<?php
							echo $daftarSimak->issetFormulirSimak() ? $daftarSimak->getFormulirSimak()->getNomorFormulir() : "";
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $daftarSimak->getTanggalMulai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo $daftarSimak->getTanggalSelesai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<textarea class="form-control" name="pekerjaan" id="pekerjaan"><?php echo $daftarSimak->getPekerjaan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_kontrak" id="nomor_kontrak" value="<?php echo $daftarSimak->getNomorKontrak();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<input type="text" class="form-control" name="lokasi" id="lokasi" value="<?php echo $daftarSimak->getLokasi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="judul_dokumen" id="judul_dokumen" value="<?php echo $daftarSimak->getJudulDokumen();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorDokumen();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_dokumen" id="nomor_dokumen" value="<?php echo $daftarSimak->getNomorDokumen();?>" autocomplete="off"/>
						</td>
					</tr>

					

				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">

			<?php
			$itemSimakFinder = new ItemSimakNative(null, $database);
			$itemSimakList = $itemSimakFinder->itemSimakList($daftarSimak->getDaftarSimakId());

			$datarSimakGrouped = array();
			if(!empty($itemSimakList))
			{
				foreach($itemSimakList as $itemSimak)
				{
					if(!isset($datarSimakGrouped[$itemSimak->getPemeriksaanId()]))
					{
						$datarSimakGrouped[$itemSimak->getPemeriksaanId()] = array();
					}
					$datarSimakGrouped[$itemSimak->getPemeriksaanId()][] = $itemSimak;
				}

				?>
				<script>
					$(document).ready(function() {
						// Make checkboxes behave like radio buttons but allow unchecking all
						$(".item_simak_id").on("change", function() {
							var itemSimakId = $(this).attr("name").match(/\d+/)[0];
							var value = $(this).val();
							if ($(this).is(":checked")) {
								// Uncheck the other checkbox in the pair
								if (value == '1') {
									$('input[data-id="' + itemSimakId + '-2"]')[0].checked = false;
								} else if (value == '2') {
									$('input[data-id="' + itemSimakId + '-1"]')[0].checked = false;
								}
							}
							// If unchecked, do nothing (allows both to be unchecked)
						});
					});
				</script>
				<table class="table-bordered table-word" >
					<thead>
						<tr>
							<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
							<td><?php echo $appLanguage->getJenisPemeriksaan();?></td>
							<td colspan="2" nowrap><?php echo $appLanguage->getHasilPemeriksaan();?></td>
						</tr>
					</thead>
					<tbody>
						<?php
							$idx = 0;
							foreach($datarSimakGrouped as $pemeriksaanId => $itemSimakGroup)
							{
								$alpha = sprintf("%c", $idx+65);

								$cleanedHtml1 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimakGroup[0]->getPemeriksaanNama());
								?>
								<tr>
									<td colspan="4"><?php echo $alpha;?>. <?php echo $cleanedHtml1;?></td>
								<?php
								$rowNumber = 1;
								foreach($itemSimakGroup as $itemSimak)
								{
									$checkedYa = $itemSimak->getNilai() == 1 ? " checked" : "";
									$checkedTidak = $itemSimak->getNilai() == 2 ? " checked" : "";

									$cleanedHtml2 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimak->getJenisPemeriksaanNama());
									?>
									<tr>
										<td><?php echo $rowNumber;?>.</td>
										<td><?php echo $cleanedHtml2;?></td>
										<td><label><input type="checkbox" class="item_simak_id" data-id="<?php echo $itemSimak->getItemSimakId();?>-1" name="item_simak_id[<?php echo $itemSimak->getItemSimakId();?>]" value="1"<?php echo $checkedYa;?>> <?php echo $itemSimak->getLabelYa();?></label></td>
										<td><label><input type="checkbox" class="item_simak_id" data-id="<?php echo $itemSimak->getItemSimakId();?>-2" name="item_simak_id[<?php echo $itemSimak->getItemSimakId();?>]" value="2"<?php echo $checkedTidak;?>> <?php echo $itemSimak->getLabelTidak();?></label></td>
									</tr>
									<?php
								$rowNumber++;
								}
								$idx++;
							}
						?>
					</tbody>
				</table>
				<?php
			} else {
				echo "<div class='alert alert-warning'>".$appLanguage->getMessageDataNotFound()."</div>";
			}
			?>
			<div><?php echo $appEntityLanguage->getCatatan();?></div>
			<div>
			<textarea class="form-control" name="catatan" id="catatan"><?php echo $daftarSimak->getCatatan();?></textarea>
			</div>


			<div class="button-area" style="padding: 5px 0">
				<button type="submit" class="btn btn-success" name="user_action" id="update_data" value="submit"><?php echo $appLanguage->getButtonSave();?></button>
				<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
				<input type="hidden" name="daftar_simak_id" id="primary_key_value" value="<?php echo $daftarSimak->getDaftarSimakId();?>"/>
			</div>
			
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
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
									$cleanedHtml1 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimak->getPemeriksaanNama()); 
									$cleanedHtml2 = preg_replace('/(?i)color:\s*[^;\}]+;?\s*/', '', $itemSimak->getJenisPemeriksaanNama()); 
								?>
									<td rowspan="<?php echo $itemSimak->rowspan; ?>" class="data-controll data-number"><?php echo $parentNumber;?></td>
									<td rowspan="<?php echo $itemSimak->rowspan; ?>"><?php echo $cleanedHtml1;?></td>
								<?php 
								$parentNumber++;
								$rowNumber = 1;
								}
								?>
								<td class="row-number"><?php echo $rowNumber;?>.</td>
								<td><?php echo $cleanedHtml2;?></td>
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
			<div><strong><?php echo $appEntityLanguage->getCatatan();?>:</strong></div>
			<p><?php echo $daftarSimak->getCatatan();?></p>


			<div class="button-area">
				<?php if($userPermission->isAllowedUpdate()){ ?>
				<button type="button" class="btn btn-primary" id="update_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl('form', Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>';"><?php echo $appLanguage->getIsiDaftarSimak();?></button>
				<?php } ?>

				<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
				<input type="hidden" name="daftar_simak_id" id="primary_key_value" value="<?php echo $daftarSimak->getDaftarSimakId();?>"/>
			</div>
		</form>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/header-supervisor.php";
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
$appEntityLanguage = new AppEntityLanguageImpl(new DaftarSimak(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"umkId" => PicoSpecification::filter("umkId", "number"),
	"tskId" => PicoSpecification::filter("tskId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"formulirSimakId" => PicoSpecification::filter("formulirSimakId", "number")
);
$sortOrderMap = array(
	"umkId" => "umkId",
	"persentase" => "persentase",
	"proyekId" => "proyekId",
	"formulirSimakId" => "formulirSimakId",
	"nama" => "nama",
	"nomorFormulir" => "nomorFormulir",
	"tanggalMulai" => "tanggalMulai",
	"tanggalSelesai" => "tanggalSelesai",
	"pekerjaan" => "pekerjaan",
	"nomorKontrak" => "nomorKontrak",
	"lokasi" => "lokasi",
	"judulDokumen" => "judulDokumen",
	"nomorDokumen" => "nomorDokumen",
	"catatan" => "catatan",
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

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";

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
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->umkId, $currentUser->getUmkId()))
									->addAnd(new PicoPredicate(Field::of()->tskId, $currentUser->getTskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getFormulirSimak();?></span>
					<span class="filter-control">
							<select class="form-control" name="formulir_simak_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new FormulirSimak(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->formulirSimakId, Field::of()->judulFormulir, $inputGet->getFormulirSimakId())
								->setTextNodeFormat('"(%s) %s", jumlahDaftarSimak, judulFormulir')
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success" id="show_data"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" id="add_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>&proyek_id='+this.form.querySelector('[name=proyek_id]').value+'&formulir_simak_id='+this.form.querySelector('[name=formulir_simak_id]').value"><?php echo $appLanguage->getButtonAdd();?></button>
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
								<td data-col-name="judul_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNamaFormulir();?></a></td>
								<td data-col-name="nomor_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorFormulir();?></a></td>
								<td data-col-name="tanggal_mulai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalMulai();?></a></td>
								<td data-col-name="tanggal_selesai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalSelesai();?></a></td>
								<td data-col-name="lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasi();?></a></td>
								<td data-col-name="persentase" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersentase();?></a></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
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
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl('form', Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->daftar_simak_id, $daftarSimak->getDaftarSimakId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama"><?php echo $daftarSimak->issetFormulirSimak() ? $daftarSimak->getFormulirSimak()->getJudulFormulir() : "";?></td>
								<td data-col-name="nomor_formulir"><?php echo $daftarSimak->getNomorFormulir();?></td>
								<td data-col-name="tanggal_mulai"><?php echo $daftarSimak->getTanggalMulai();?></td>
								<td data-col-name="tanggal_selesai"><?php echo $daftarSimak->getTanggalSelesai();?></td>
								<td data-col-name="lokasi"><?php echo $daftarSimak->getLokasi();?></td>
								<td data-col-name="persentase"><?php echo $daftarSimak->numberFormatPersentase(2, ".", ",");?></td>
								<td data-col-name="proyek_id"><?php echo $daftarSimak->issetProyek() ? $daftarSimak->getProyek()->getNama() : "";?></td>
								<td data-col-name="aktif"><?php echo $daftarSimak->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

