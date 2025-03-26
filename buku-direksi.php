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
use MagicApp\AppEntityLanguage;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\PicoApproval;
use MagicApp\WaitingFor;
use MagicApp\UserAction;
use Sipro\Entity\Data\BukuDireksi;
use Sipro\Entity\Data\BukuDireksiApv;
use Sipro\Entity\Data\BukuDireksiTrash;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "buku-direksi", $appLanguage->getBukuDireksi());

$inputGet = new InputGet();
$inputPost = new InputPost();

$dataFilter = PicoSpecification::getInstance();
$dataFilter->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $currentUser->getSupervisorId()));

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$bukuDireksi = new BukuDireksi(null, $database);
	$bukuDireksi->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuDireksi->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuDireksi->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setNomor($inputPost->getNomor(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setLokasi($inputPost->getLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setUraianPermasalahan($inputPost->getUraianPermasalahan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setPerkiraanLamaPenyelesaian($inputPost->getPerkiraanLamaPenyelesaian(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuDireksi->setDiperiksa($inputPost->getDiperiksa(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$bukuDireksi->setWaktuMulai($inputPost->getWaktuMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setPenyelesaian($inputPost->getPenyelesaian(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
	$bukuDireksi->setStatus($inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuDireksi->setProgres($inputPost->getProgres(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$bukuDireksi->setSelesai($inputPost->getSelesai(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$bukuDireksi->setWaktuSelesai($inputPost->getWaktuSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setLamaPenyelesaian($inputPost->getLamaPenyelesaian(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$bukuDireksi->setNamaDireksi($inputPost->getNamaDireksi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setJabatanDireksi($inputPost->getJabatanDireksi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setKomentarKontraktor($inputPost->getKomentarKontraktor(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$bukuDireksi->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$bukuDireksi->setDraft(true);
	$bukuDireksi->setWaitingFor(WaitingFor::CREATE);
	$bukuDireksi->setAdminBuat($currentAction->getUserId());
	$bukuDireksi->setWaktuBuat($currentAction->getTime());
	$bukuDireksi->setIpBuat($currentAction->getIp());
	$bukuDireksi->setAdminUbah($currentAction->getUserId());
	$bukuDireksi->setWaktuUbah($currentAction->getTime());
	$bukuDireksi->setIpUbah($currentAction->getIp());

	try
	{
		$bukuDireksi->insert();
		$newId = $bukuDireksi->getBukuDireksiId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_direksi_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	try
	{
		$specification = PicoSpecification::getInstanceOf(Field::of()->bukuDireksiId, $inputPost->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
		$specification->addAnd($dataFilter);

		$bukuDireksi = new BukuDireksi(null, $database);
		$bukuDireksi->findOne($specification);

		$bukuDireksiApv = new BukuDireksiApv(null, $database);
		$bukuDireksiApv->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$bukuDireksiApv->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$bukuDireksiApv->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setNomor($inputPost->getNomor(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setLokasi($inputPost->getLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setUraianPermasalahan($inputPost->getUraianPermasalahan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setPerkiraanLamaPenyelesaian($inputPost->getPerkiraanLamaPenyelesaian(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$bukuDireksiApv->setDiperiksa($inputPost->getDiperiksa(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
		$bukuDireksiApv->setWaktuMulai($inputPost->getWaktuMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setPenyelesaian($inputPost->getPenyelesaian(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
		$bukuDireksiApv->setStatus($inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$bukuDireksiApv->setProgres($inputPost->getProgres(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
		$bukuDireksiApv->setSelesai($inputPost->getSelesai(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
		$bukuDireksiApv->setWaktuSelesai($inputPost->getWaktuSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setLamaPenyelesaian($inputPost->getLamaPenyelesaian(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$bukuDireksiApv->setNamaDireksi($inputPost->getNamaDireksi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setJabatanDireksi($inputPost->getJabatanDireksi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setKomentarKontraktor($inputPost->getKomentarKontraktor(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
		$bukuDireksiApv->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
		$bukuDireksiApv->setAdminUbah($currentAction->getUserId());
		$bukuDireksiApv->setWaktuUbah($currentAction->getTime());
		$bukuDireksiApv->setIpUbah($currentAction->getIp());
		$bukuDireksiApv->setBukuDireksiId($bukuDireksi->getBukuDireksiId());
		$bukuDireksiApv->insert();

		$bukuDireksi->setAdminMintaUbah($currentAction->getUserId());
		$bukuDireksi->setWaktuMintaUbah($currentAction->getTime());
		$bukuDireksi->setIpMintaUbah($currentAction->getIp());
		$bukuDireksi->setApprovalId($bukuDireksiApv->getBukuDireksiApvId())->setWaitingFor(WaitingFor::UPDATE)->update();
		$newId = $bukuDireksi->getBukuDireksiId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->buku_direksi_id, $newId);
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
			$bukuDireksi = new BukuDireksi(null, $database);
			try
			{
				$bukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuDireksiId, $rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
					->addAnd($dataFilter)
				)
				->setAdminMintaUbah($currentAction->getUserId())
				->setWaktuMintaUbah($currentAction->getTime())
				->setIpMintaUbah($currentAction->getIp())
				->setWaitingFor(WaitingFor::ACTIVATE)
				->update();
			}
			catch(Exception $e)
			{
				// Perform an action here when the record is not found.
				error_log($e);
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
			$bukuDireksi = new BukuDireksi(null, $database);
			try
			{
				$bukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuDireksiId, $rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
					->addAnd($dataFilter)
				)
				->setAdminMintaUbah($currentAction->getUserId())
				->setWaktuMintaUbah($currentAction->getTime())
				->setIpMintaUbah($currentAction->getIp())
				->setWaitingFor(WaitingFor::DEACTIVATE)
				->update();
			}
			catch(Exception $e)
			{
				// Perform an action here when the record is not found.
				error_log($e);
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
			$bukuDireksi = new BukuDireksi(null, $database);
			try
			{
				$bukuDireksi->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuDireksiId, $rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
					->addAnd($dataFilter)
				)
				->setAdminMintaUbah($currentAction->getUserId())
				->setWaktuMintaUbah($currentAction->getTime())
				->setIpMintaUbah($currentAction->getIp())
				->setWaitingFor(WaitingFor::DELETE)
				->update();
			}
			catch(Exception $e)
			{
				// Perform an action here when the record is not found.
				error_log($e);
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::APPROVE)
{
	if($inputPost->issetBukuDireksiId())
	{
		$specification = PicoSpecification::getInstanceOf(Field::of()->bukuDireksiId, $inputPost->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
		$specification->addAnd($dataFilter);

		$bukuDireksi = new BukuDireksi(null, $database);
		$bukuDireksi->findOne($specification);

		if($bukuDireksi->issetBukuDireksiId())
		{
			$approval = new PicoApproval(
			$bukuDireksi, 
			$entityInfo, 
			$entityApvInfo, 
			function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Approval validation logic
				// If the return is false, approval will not proceed
				
				// Example: return $param1->notEqualsAdminMintaUbah($currentAction->getUserId());
				return true;
			}, 
			true, 
			new BukuDireksiTrash() 
			);

			$approvalCallback = new SetterGetter();
			$approvalCallback->setAfterInsert(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after new data is inserted
				// Your code goes here
				
				return true;
			});

			$approvalCallback->setBeforeUpdate(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute before updating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterUpdate(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after updating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterActivate(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after activating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterDeactivate(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after deactivating data
				// Your code goes here
				
			});

			$approvalCallback->setBeforeDelete(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute before deleting data
				// Your code goes here
				
			});

			$approvalCallback->setAfterDelete(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after deleting data
				// Your code goes here
				
			});

			$approvalCallback->setAfterApprove(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after approval
				// Your code goes here
				
			});

			// List of properties to be copied from BukuDireksiApv to BukuDireksi when the user approves data modification.
			// You can modify this list by adding or removing fields as needed.
			$columnToBeCopied = array(
				Field::of()->nama, 
				Field::of()->proyekId, 
				Field::of()->supervisorId, 
				Field::of()->tanggal, 
				Field::of()->nomor, 
				Field::of()->lokasi, 
				Field::of()->pekerjaan, 
				Field::of()->uraianPermasalahan, 
				Field::of()->perkiraanLamaPenyelesaian, 
				Field::of()->diperiksa, 
				Field::of()->waktuMulai, 
				Field::of()->penyelesaian, 
				Field::of()->status, 
				Field::of()->progres, 
				Field::of()->selesai, 
				Field::of()->waktuSelesai, 
				Field::of()->lamaPenyelesaian, 
				Field::of()->namaDireksi, 
				Field::of()->jabatanDireksi, 
				Field::of()->komentarKontraktor, 
				Field::of()->aktif
			);

			$approval->approve($columnToBeCopied, new BukuDireksiApv(), new BukuDireksiTrash(), 
			$currentAction->getUserId(),  
			$currentAction->getTime(),  
			$currentAction->getIp(), 
			$approvalCallback);
		}
	}
	$currentModule->redirectToItselfWithRequireApproval();
}
else if($inputPost->getUserAction() == UserAction::REJECT)
{
	if($inputPost->issetBukuDireksiId())
	{
		$specification = PicoSpecification::getInstanceOf(Field::of()->bukuDireksiId, $inputPost->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
		$specification->addAnd($dataFilter);

		$bukuDireksi = new BukuDireksi(null, $database);
		$bukuDireksi->findOne($specification);

		if($bukuDireksi->issetBukuDireksiId())
		{
			$approval = new PicoApproval(
			$bukuDireksi, 
			$entityInfo, 
			$entityApvInfo, 
			function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Approval validation logic
				// If the return is false, approval will not proceed
				
				// Example: return $param1->notEqualsAdminMintaUbah($currentAction->getUserId());
				return true;
			});


			$approvalCallback = new SetterGetter();
			$approvalCallback->setBeforeReject(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute before reject data
				// Your code goes here
				
			});

			$approvalCallback->setAfterReject(function($param1 = null, $param2 = null, $param3 = null) use ($dataControlConfig, $database, $currentAction) {
				// Logic to execute after reject data
				// Your code goes here
				
			});

			$approval->reject(new BukuDireksiApv(),
			$currentAction->getUserId(),  
			$currentAction->getTime(),  
			$currentAction->getIp(), 
			$approvalCallback
			);
		}
	}
	$currentModule->redirectToItselfWithRequireApproval();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<style>
	.note-hint-popover {
		position: absolute;
	}
</style>
<script>
	jQuery(function(e){
		$('textarea').summernote({
			height: 200,
			hint: {
				words: [],
				match: /\b(\w{1,})$/,
				search: function (keyword, callback) 
				{
					callback($.grep(this.words, function (item) {
						return item.indexOf(keyword) === 0;
					}));
				}
			},
			toolbar: [
				['style', ['style', 'bold', 'italic', 'underline']],
				['para', ['ul', 'ol', 'paragraph']],
				['font', ['fontname', 'fontsize', 'color', 'background']],
				['insert', ['picture', 'table']],
			]
		});
	});
</script>
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
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="tanggal" id="tanggal"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomor();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nomor" id="nomor"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="lokasi" id="lokasi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="pekerjaan" id="pekerjaan"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUraianPermasalahan();?></td>
						<td>
							<textarea class="form-control" name="uraian_permasalahan" id="uraian_permasalahan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPerkiraanLamaPenyelesaian();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="perkiraan_lama_penyelesaian" id="perkiraan_lama_penyelesaian"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDiperiksa();?></td>
						<td>
							<select class="form-control" name="diperiksa" id="diperiksa">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1">Sudah</option>
								<option value="0">Belum</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMulai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_mulai" id="waktu_mulai"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPenyelesaian();?></td>
						<td>
							<textarea class="form-control" name="penyelesaian" id="penyelesaian" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td>
							<select class="form-control" name="status" id="status">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="0">Baru</option>
								<option value="1">Dalam Proses</option>
								<option value="2">Selesai</option>
								<option value="3">Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProgres();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="progres" id="progres"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSelesai();?></td>
						<td>
							<select class="form-control" name="selesai" id="selesai">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1">Sudah</option>
								<option value="0">Belum</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuSelesai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_selesai" id="waktu_selesai"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLamaPenyelesaian();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="lama_penyelesaian" id="lama_penyelesaian"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDireksi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama_direksi" id="nama_direksi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJabatanDireksi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="jabatan_direksi" id="jabatan_direksi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKontraktor();?></td>
						<td>
							<textarea class="form-control" name="komentar_kontraktor" id="komentar_kontraktor" spellcheck="false"></textarea>
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else if($inputGet->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->bukuDireksiId, $inputGet->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$bukuDireksi = new BukuDireksi(null, $database);
	try{
		$bukuDireksi->findOne($specification);
		if($bukuDireksi->issetBukuDireksiId())
		{
$appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
		if(!UserAction::isRequireApproval($bukuDireksi->getWaitingFor()))
		{
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<style>
	.note-hint-popover {
		position: absolute;
	}
</style>
<script>
	jQuery(function(e){
		$('textarea').summernote({
			height: 200,
			hint: {
				words: [],
				match: /\b(\w{1,})$/,
				search: function (keyword, callback) 
				{
					callback($.grep(this.words, function (item) {
						return item.indexOf(keyword) === 0;
					}));
				}
			},
			toolbar: [
				['style', ['style', 'bold', 'italic', 'underline']],
				['para', ['ul', 'ol', 'paragraph']],
				['font', ['fontname', 'fontsize', 'color', 'background']],
				['insert', ['picture', 'table']],
			]
		});
	});
</script>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $bukuDireksi->getNama();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $bukuDireksi->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $bukuDireksi->getSupervisorId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input class="form-control" type="date" name="tanggal" id="tanggal" value="<?php echo $bukuDireksi->getTanggal();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomor();?></td>
						<td>
							<input type="text" class="form-control" name="nomor" id="nomor" value="<?php echo $bukuDireksi->getNomor();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<input type="text" class="form-control" name="lokasi" id="lokasi" value="<?php echo $bukuDireksi->getLokasi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<input type="text" class="form-control" name="pekerjaan" id="pekerjaan" value="<?php echo $bukuDireksi->getPekerjaan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUraianPermasalahan();?></td>
						<td>
							<textarea class="form-control" name="uraian_permasalahan" id="uraian_permasalahan" spellcheck="false"><?php echo $bukuDireksi->getUraianPermasalahan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPerkiraanLamaPenyelesaian();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="perkiraan_lama_penyelesaian" id="perkiraan_lama_penyelesaian" value="<?php echo $bukuDireksi->getPerkiraanLamaPenyelesaian();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDiperiksa();?></td>
						<td>
							<select class="form-control" name="diperiksa" id="diperiksa" data-value="<?php echo $bukuDireksi->getDiperiksa();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($bukuDireksi->getDiperiksa(), '1');?>>Sudah</option>
								<option value="0" <?php echo AppFormBuilder::selected($bukuDireksi->getDiperiksa(), '0');?>>Belum</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMulai();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_mulai" id="waktu_mulai" value="<?php echo $bukuDireksi->getWaktuMulai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPenyelesaian();?></td>
						<td>
							<textarea class="form-control" name="penyelesaian" id="penyelesaian" spellcheck="false"><?php echo $bukuDireksi->getPenyelesaian();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td>
							<select class="form-control" name="status" id="status" data-value="<?php echo $bukuDireksi->getStatus();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="0" <?php echo AppFormBuilder::selected($bukuDireksi->getStatus(), '0');?>>Baru</option>
								<option value="1" <?php echo AppFormBuilder::selected($bukuDireksi->getStatus(), '1');?>>Dalam Proses</option>
								<option value="2" <?php echo AppFormBuilder::selected($bukuDireksi->getStatus(), '2');?>>Selesai</option>
								<option value="3" <?php echo AppFormBuilder::selected($bukuDireksi->getStatus(), '3');?>>Dibatalkan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProgres();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="progres" id="progres" value="<?php echo $bukuDireksi->getProgres();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSelesai();?></td>
						<td>
							<select class="form-control" name="selesai" id="selesai" data-value="<?php echo $bukuDireksi->getSelesai();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($bukuDireksi->getSelesai(), '1');?>>Sudah</option>
								<option value="0" <?php echo AppFormBuilder::selected($bukuDireksi->getSelesai(), '0');?>>Belum</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuSelesai();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_selesai" id="waktu_selesai" value="<?php echo $bukuDireksi->getWaktuSelesai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLamaPenyelesaian();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="lama_penyelesaian" id="lama_penyelesaian" value="<?php echo $bukuDireksi->getLamaPenyelesaian();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDireksi();?></td>
						<td>
							<input type="text" class="form-control" name="nama_direksi" id="nama_direksi" value="<?php echo $bukuDireksi->getNamaDireksi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJabatanDireksi();?></td>
						<td>
							<input type="text" class="form-control" name="jabatan_direksi" id="jabatan_direksi" value="<?php echo $bukuDireksi->getJabatanDireksi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKontraktor();?></td>
						<td>
							<textarea class="form-control" name="komentar_kontraktor" id="komentar_kontraktor" spellcheck="false"><?php echo $bukuDireksi->getKomentarKontraktor();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $bukuDireksi->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="buku_direksi_id" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
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
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageNoneditableDataWaitingApproval();?></div>
			<div class="button-area"><button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button></div>
			<?php 
		}
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->bukuDireksiId, $inputGet->getBukuDireksiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$bukuDireksi = new BukuDireksi(null, $database);
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
		)
		);
		$bukuDireksi->findOne($specification, null, $subqueryMap);
		if($bukuDireksi->issetBukuDireksiId())
		{
			// Define map here
			$mapForDiperiksa = array(
				"1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
				"0" => array("value" => "0", "label" => "Belum", "default" => "false")
			);
			$mapForStatus = array(
				"0" => array("value" => "0", "label" => "Baru", "default" => "false"),
				"1" => array("value" => "1", "label" => "Dalam Proses", "default" => "false"),
				"2" => array("value" => "2", "label" => "Selesai", "default" => "false"),
				"3" => array("value" => "3", "label" => "Dibatalkan", "default" => "false")
			);
			$mapForSelesai = array(
				"1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
				"0" => array("value" => "0", "label" => "Belum", "default" => "false")
			);
			if(UserAction::isRequireNextAction($inputGet) && $bukuDireksi->notNullApprovalId())
			{
				$bukuDireksiApv = new BukuDireksiApv(null, $database);
				try
				{
					$bukuDireksiApv->findOneWithPrimaryKeyValue($bukuDireksi->getApprovalId(), $subqueryMap);
				}
				catch(Exception $e)
				{
					// do something here
				}
$appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
			<div class="alert alert-info">	
			<?php
			echo UserAction::getWaitingForMessage($appLanguage, $bukuDireksi->getWaitingFor());
			?>
			</div>
			<table class="responsive responsive-three-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead>
					<tr>
						<td><?php echo $appLanguage->getColumnName();?></td>
						<td><?php echo $appLanguage->getValueBefore();?></td>
						<td><?php echo $appLanguage->getValueAfter();?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNama($bukuDireksiApv->getNama()));?>"><?php echo $bukuDireksi->getNama();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNama($bukuDireksiApv->getNama()));?>"><?php echo $bukuDireksiApv->getNama();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsProyekId($bukuDireksiApv->getProyekId()));?>"><?php echo $bukuDireksi->issetProyek() ? $bukuDireksi->getProyek()->getNama() : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsProyekId($bukuDireksiApv->getProyekId()));?>"><?php echo $bukuDireksiApv->issetProyek() ? $bukuDireksiApv->getProyek()->getNama() : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsSupervisorId($bukuDireksiApv->getSupervisorId()));?>"><?php echo $bukuDireksi->issetSupervisor() ? $bukuDireksi->getSupervisor()->getNama() : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsSupervisorId($bukuDireksiApv->getSupervisorId()));?>"><?php echo $bukuDireksiApv->issetSupervisor() ? $bukuDireksiApv->getSupervisor()->getNama() : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsTanggal($bukuDireksiApv->getTanggal()));?>"><?php echo $bukuDireksi->getTanggal();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsTanggal($bukuDireksiApv->getTanggal()));?>"><?php echo $bukuDireksiApv->getTanggal();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomor();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNomor($bukuDireksiApv->getNomor()));?>"><?php echo $bukuDireksi->getNomor();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNomor($bukuDireksiApv->getNomor()));?>"><?php echo $bukuDireksiApv->getNomor();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsLokasi($bukuDireksiApv->getLokasi()));?>"><?php echo $bukuDireksi->getLokasi();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsLokasi($bukuDireksiApv->getLokasi()));?>"><?php echo $bukuDireksiApv->getLokasi();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPekerjaan($bukuDireksiApv->getPekerjaan()));?>"><?php echo $bukuDireksi->getPekerjaan();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPekerjaan($bukuDireksiApv->getPekerjaan()));?>"><?php echo $bukuDireksiApv->getPekerjaan();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUraianPermasalahan();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsUraianPermasalahan($bukuDireksiApv->getUraianPermasalahan()));?>"><?php echo $bukuDireksi->getUraianPermasalahan();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsUraianPermasalahan($bukuDireksiApv->getUraianPermasalahan()));?>"><?php echo $bukuDireksiApv->getUraianPermasalahan();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPerkiraanLamaPenyelesaian();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPerkiraanLamaPenyelesaian($bukuDireksiApv->getPerkiraanLamaPenyelesaian()));?>"><?php echo $bukuDireksi->getPerkiraanLamaPenyelesaian();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPerkiraanLamaPenyelesaian($bukuDireksiApv->getPerkiraanLamaPenyelesaian()));?>"><?php echo $bukuDireksiApv->getPerkiraanLamaPenyelesaian();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDiperiksa();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsDiperiksa($bukuDireksiApv->getDiperiksa()));?>"><?php echo isset($mapForDiperiksa) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"]) ? $mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"] : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsDiperiksa($bukuDireksiApv->getDiperiksa()));?>"><?php echo isset($mapForDiperiksa) && isset($mapForDiperiksa[$bukuDireksiApv->getDiperiksa()]) && isset($mapForDiperiksa[$bukuDireksiApv->getDiperiksa()]["label"]) ? $mapForDiperiksa[$bukuDireksiApv->getDiperiksa()]["label"] : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMulai();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuMulai($bukuDireksiApv->getWaktuMulai()));?>"><?php echo $bukuDireksi->getWaktuMulai();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuMulai($bukuDireksiApv->getWaktuMulai()));?>"><?php echo $bukuDireksiApv->getWaktuMulai();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPenyelesaian();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPenyelesaian($bukuDireksiApv->getPenyelesaian()));?>"><?php echo $bukuDireksi->getPenyelesaian();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsPenyelesaian($bukuDireksiApv->getPenyelesaian()));?>"><?php echo $bukuDireksiApv->getPenyelesaian();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsStatus($bukuDireksiApv->getStatus()));?>"><?php echo isset($mapForStatus) && isset($mapForStatus[$bukuDireksi->getStatus()]) && isset($mapForStatus[$bukuDireksi->getStatus()]["label"]) ? $mapForStatus[$bukuDireksi->getStatus()]["label"] : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsStatus($bukuDireksiApv->getStatus()));?>"><?php echo isset($mapForStatus) && isset($mapForStatus[$bukuDireksiApv->getStatus()]) && isset($mapForStatus[$bukuDireksiApv->getStatus()]["label"]) ? $mapForStatus[$bukuDireksiApv->getStatus()]["label"] : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProgres();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsProgres($bukuDireksiApv->getProgres()));?>"><?php echo $bukuDireksi->getProgres();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsProgres($bukuDireksiApv->getProgres()));?>"><?php echo $bukuDireksiApv->getProgres();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSelesai();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsSelesai($bukuDireksiApv->getSelesai()));?>"><?php echo isset($mapForSelesai) && isset($mapForSelesai[$bukuDireksi->getSelesai()]) && isset($mapForSelesai[$bukuDireksi->getSelesai()]["label"]) ? $mapForSelesai[$bukuDireksi->getSelesai()]["label"] : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsSelesai($bukuDireksiApv->getSelesai()));?>"><?php echo isset($mapForSelesai) && isset($mapForSelesai[$bukuDireksiApv->getSelesai()]) && isset($mapForSelesai[$bukuDireksiApv->getSelesai()]["label"]) ? $mapForSelesai[$bukuDireksiApv->getSelesai()]["label"] : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuSelesai();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuSelesai($bukuDireksiApv->getWaktuSelesai()));?>"><?php echo $bukuDireksi->getWaktuSelesai();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuSelesai($bukuDireksiApv->getWaktuSelesai()));?>"><?php echo $bukuDireksiApv->getWaktuSelesai();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLamaPenyelesaian();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsLamaPenyelesaian($bukuDireksiApv->getLamaPenyelesaian()));?>"><?php echo $bukuDireksi->getLamaPenyelesaian();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsLamaPenyelesaian($bukuDireksiApv->getLamaPenyelesaian()));?>"><?php echo $bukuDireksiApv->getLamaPenyelesaian();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDireksi();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNamaDireksi($bukuDireksiApv->getNamaDireksi()));?>"><?php echo $bukuDireksi->getNamaDireksi();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsNamaDireksi($bukuDireksiApv->getNamaDireksi()));?>"><?php echo $bukuDireksiApv->getNamaDireksi();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJabatanDireksi();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsJabatanDireksi($bukuDireksiApv->getJabatanDireksi()));?>"><?php echo $bukuDireksi->getJabatanDireksi();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsJabatanDireksi($bukuDireksiApv->getJabatanDireksi()));?>"><?php echo $bukuDireksiApv->getJabatanDireksi();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKontraktor();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsKomentarKontraktor($bukuDireksiApv->getKomentarKontraktor()));?>"><?php echo $bukuDireksi->getKomentarKontraktor();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsKomentarKontraktor($bukuDireksiApv->getKomentarKontraktor()));?>"><?php echo $bukuDireksiApv->getKomentarKontraktor();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuBuat($bukuDireksiApv->getWaktuBuat()));?>"><?php echo $bukuDireksi->getWaktuBuat();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuBuat($bukuDireksiApv->getWaktuBuat()));?>"><?php echo $bukuDireksiApv->getWaktuBuat();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuUbah($bukuDireksiApv->getWaktuUbah()));?>"><?php echo $bukuDireksi->getWaktuUbah();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsWaktuUbah($bukuDireksiApv->getWaktuUbah()));?>"><?php echo $bukuDireksiApv->getWaktuUbah();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsIpBuat($bukuDireksiApv->getIpBuat()));?>"><?php echo $bukuDireksi->getIpBuat();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsIpBuat($bukuDireksiApv->getIpBuat()));?>"><?php echo $bukuDireksiApv->getIpBuat();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsIpUbah($bukuDireksiApv->getIpUbah()));?>"><?php echo $bukuDireksi->getIpUbah();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsIpUbah($bukuDireksiApv->getIpUbah()));?>"><?php echo $bukuDireksiApv->getIpUbah();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsAktif($bukuDireksiApv->getAktif()));?>"><?php echo $bukuDireksi->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($bukuDireksi->notEqualsAktif($bukuDireksiApv->getAktif()));?>"><?php echo $bukuDireksiApv->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></span>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php 
							if($inputGet->getNextAction() == UserAction::APPROVAL)
							{
							?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="buku_direksi_id" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
							<?php 
							}
							else if($inputGet->getNextAction() == UserAction::APPROVE)
							{
							?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="buku_direksi_id" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
							<?php 
							}
							else
							{
							?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="buku_direksi_id" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
							<?php 
							}
							?>
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
$appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($bukuDireksi->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $bukuDireksi->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $bukuDireksi->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $bukuDireksi->issetProyek() ? $bukuDireksi->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $bukuDireksi->issetSupervisor() ? $bukuDireksi->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $bukuDireksi->getTanggal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomor();?></td>
						<td><?php echo $bukuDireksi->getNomor();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasi();?></td>
						<td><?php echo $bukuDireksi->getLokasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td><?php echo $bukuDireksi->getPekerjaan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getUraianPermasalahan();?></td>
						<td><?php echo $bukuDireksi->getUraianPermasalahan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPerkiraanLamaPenyelesaian();?></td>
						<td><?php echo $bukuDireksi->getPerkiraanLamaPenyelesaian();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDiperiksa();?></td>
						<td><?php echo isset($mapForDiperiksa) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"]) ? $mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMulai();?></td>
						<td><?php echo $bukuDireksi->getWaktuMulai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPenyelesaian();?></td>
						<td><?php echo $bukuDireksi->getPenyelesaian();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getStatus();?></td>
						<td><?php echo isset($mapForStatus) && isset($mapForStatus[$bukuDireksi->getStatus()]) && isset($mapForStatus[$bukuDireksi->getStatus()]["label"]) ? $mapForStatus[$bukuDireksi->getStatus()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProgres();?></td>
						<td><?php echo $bukuDireksi->getProgres();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSelesai();?></td>
						<td><?php echo isset($mapForSelesai) && isset($mapForSelesai[$bukuDireksi->getSelesai()]) && isset($mapForSelesai[$bukuDireksi->getSelesai()]["label"]) ? $mapForSelesai[$bukuDireksi->getSelesai()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuSelesai();?></td>
						<td><?php echo $bukuDireksi->getWaktuSelesai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLamaPenyelesaian();?></td>
						<td><?php echo $bukuDireksi->getLamaPenyelesaian();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaDireksi();?></td>
						<td><?php echo $bukuDireksi->getNamaDireksi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJabatanDireksi();?></td>
						<td><?php echo $bukuDireksi->getJabatanDireksi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKomentarKontraktor();?></td>
						<td><?php echo $bukuDireksi->getKomentarKontraktor();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $bukuDireksi->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuDireksi->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $bukuDireksi->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $bukuDireksi->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $bukuDireksi->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVAL && UserAction::isRequireApproval($bukuDireksi->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($bukuDireksi->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($bukuDireksi->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->buku_direksi_id, $bukuDireksi->getBukuDireksiId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="buku_direksi_id" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
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
		}
		else
		{
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
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
$appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());
$mapForDiperiksa = array(
	"1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
	"0" => array("value" => "0", "label" => "Belum", "default" => "false")
);
$mapForStatus = array(
	"0" => array("value" => "0", "label" => "Baru", "default" => "false"),
	"1" => array("value" => "1", "label" => "Dalam Proses", "default" => "false"),
	"2" => array("value" => "2", "label" => "Selesai", "default" => "false"),
	"3" => array("value" => "3", "label" => "Dibatalkan", "default" => "false")
);
$mapForSelesai = array(
	"1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
	"0" => array("value" => "0", "label" => "Belum", "default" => "false")
);
$specMap = array(
	"nama" => PicoSpecification::filter("nama", "fulltext"),
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"diperiksa" => PicoSpecification::filter("diperiksa", "fulltext"),
	"status" => PicoSpecification::filter("status", "number"),
	"selesai" => PicoSpecification::filter("selesai", "fulltext")
);
$sortOrderMap = array(
	"tanggal" => "tanggal",
	"nomor" => "nomor",
	"lokasi" => "lokasi",
	"pekerjaan" => "pekerjaan",
	"diperiksa" => "diperiksa",
	"waktuMulai" => "waktuMulai",
	"status" => "status",
	"progres" => "progres",
	"selesai" => "selesai",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);

if($inputGet->isShowRequireApprovalOnly()){
	$specification->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->waitingFor, WaitingFor::NOTHING));
	$specification->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->waitingFor, null));
}

// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "bukuDireksiId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new BukuDireksi(null, $database);

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
		$appEntityLanguage->getBukuDireksiId() => $headerFormat->getBukuDireksiId(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getTanggal() => $headerFormat->getTanggal(),
		$appEntityLanguage->getNomor() => $headerFormat->getNomor(),
		$appEntityLanguage->getLokasi() => $headerFormat->getLokasi(),
		$appEntityLanguage->getPekerjaan() => $headerFormat->getPekerjaan(),
		$appEntityLanguage->getUraianPermasalahan() => $headerFormat->asString(),
		$appEntityLanguage->getPerkiraanLamaPenyelesaian() => $headerFormat->getPerkiraanLamaPenyelesaian(),
		$appEntityLanguage->getDiperiksa() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuMulai() => $headerFormat->getWaktuMulai(),
		$appEntityLanguage->getPenyelesaian() => $headerFormat->asString(),
		$appEntityLanguage->getStatus() => $headerFormat->asString(),
		$appEntityLanguage->getProgres() => $headerFormat->getProgres(),
		$appEntityLanguage->getSelesai() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuSelesai() => $headerFormat->getWaktuSelesai(),
		$appEntityLanguage->getLamaPenyelesaian() => $headerFormat->getLamaPenyelesaian(),
		$appEntityLanguage->getNamaDireksi() => $headerFormat->getNamaDireksi(),
		$appEntityLanguage->getJabatanDireksi() => $headerFormat->getJabatanDireksi(),
		$appEntityLanguage->getKomentarKontraktor() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
		global $mapForDiperiksa;
		global $mapForStatus;
		global $mapForSelesai;
		return array(
			sprintf("%d", $index + 1),
			$row->getBukuDireksiId(),
			$row->getNama(),
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->getTanggal(),
			$row->getNomor(),
			$row->getLokasi(),
			$row->getPekerjaan(),
			$row->getUraianPermasalahan(),
			$row->getPerkiraanLamaPenyelesaian(),
			isset($mapForDiperiksa) && isset($mapForDiperiksa[$row->getDiperiksa()]) && isset($mapForDiperiksa[$row->getDiperiksa()]["label"]) ? $mapForDiperiksa[$row->getDiperiksa()]["label"] : "",
			$row->getWaktuMulai(),
			$row->getPenyelesaian(),
			isset($mapForStatus) && isset($mapForStatus[$row->getStatus()]) && isset($mapForStatus[$row->getStatus()]["label"]) ? $mapForStatus[$row->getStatus()]["label"] : "",
			$row->getProgres(),
			isset($mapForSelesai) && isset($mapForSelesai[$row->getSelesai()]) && isset($mapForSelesai[$row->getSelesai()]["label"]) ? $mapForSelesai[$row->getSelesai()]["label"] : "",
			$row->getWaktuSelesai(),
			$row->getLamaPenyelesaian(),
			$row->getNamaDireksi(),
			$row->getJabatanDireksi(),
			$row->getKomentarKontraktor(),
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
require_once __DIR__ . "/inc.app/header-supervisor.php";
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
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
							<select class="form-control" name="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
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
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getDiperiksa();?></span>
					<span class="filter-control">
							<select class="form-control" name="diperiksa" data-value="<?php echo $inputGet->getDiperiksa();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($inputGet->getDiperiksa(), '1');?>>Sudah</option>
								<option value="0" <?php echo AppFormBuilder::selected($inputGet->getDiperiksa(), '0');?>>Belum</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getStatus();?></span>
					<span class="filter-control">
							<select class="form-control" name="status" data-value="<?php echo $inputGet->getStatus();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="0" <?php echo AppFormBuilder::selected($inputGet->getStatus(), '0');?>>Baru</option>
								<option value="1" <?php echo AppFormBuilder::selected($inputGet->getStatus(), '1');?>>Dalam Proses</option>
								<option value="2" <?php echo AppFormBuilder::selected($inputGet->getStatus(), '2');?>>Selesai</option>
								<option value="3" <?php echo AppFormBuilder::selected($inputGet->getStatus(), '3');?>>Dibatalkan</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSelesai();?></span>
					<span class="filter-control">
							<select class="form-control" name="selesai" data-value="<?php echo $inputGet->getSelesai();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="1" <?php echo AppFormBuilder::selected($inputGet->getSelesai(), '1');?>>Sudah</option>
								<option value="0" <?php echo AppFormBuilder::selected($inputGet->getSelesai(), '0');?>>Belum</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedApprove()){ ?>
		
				<span class="filter-group">
					<button type="submit" name="show_require_approval_only" value="true" class="btn btn-success"><?php echo $appLanguage->getButtonShowRequireApproval();?></button>
				</span>
				<?php } ?>
				<?php if($userPermission->isAllowedExport()){ ?>
		
				<span class="filter-group">
					<button type="submit" name="user_action" value="export" class="btn btn-success"><?php echo $appLanguage->getButtonExport();?></button>
				</span>
				<?php } ?>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
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
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="buku_direksi_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-buku-direksi-id"/>
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
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="nomor" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomor();?></a></td>
								<td data-col-name="lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasi();?></a></td>
								<td data-col-name="pekerjaan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPekerjaan();?></a></td>
								<td data-col-name="diperiksa" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDiperiksa();?></a></td>
								<td data-col-name="waktu_mulai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuMulai();?></a></td>
								<td data-col-name="status" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getStatus();?></a></td>
								<td data-col-name="progres" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProgres();?></a></td>
								<td data-col-name="selesai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSelesai();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval"><?php echo $appLanguage->getApproval();?></td>
								<?php } ?>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($bukuDireksi = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="buku_direksi_id">
									<input type="checkbox" class="checkbox check-slave checkbox-buku-direksi-id" name="checked_row_id[]" value="<?php echo $bukuDireksi->getBukuDireksiId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->buku_direksi_id, $bukuDireksi->getBukuDireksiId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_direksi_id, $bukuDireksi->getBukuDireksiId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="tanggal"><?php echo $bukuDireksi->getTanggal();?></td>
								<td data-col-name="nomor"><?php echo $bukuDireksi->getNomor();?></td>
								<td data-col-name="lokasi"><?php echo $bukuDireksi->getLokasi();?></td>
								<td data-col-name="pekerjaan"><?php echo $bukuDireksi->getPekerjaan();?></td>
								<td data-col-name="diperiksa"><?php echo isset($mapForDiperiksa) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"]) ? $mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"] : "";?></td>
								<td data-col-name="waktu_mulai"><?php echo $bukuDireksi->getWaktuMulai();?></td>
								<td data-col-name="status"><?php echo isset($mapForStatus) && isset($mapForStatus[$bukuDireksi->getStatus()]) && isset($mapForStatus[$bukuDireksi->getStatus()]["label"]) ? $mapForStatus[$bukuDireksi->getStatus()]["label"] : "";?></td>
								<td data-col-name="progres"><?php echo $bukuDireksi->getProgres();?></td>
								<td data-col-name="selesai"><?php echo isset($mapForSelesai) && isset($mapForSelesai[$bukuDireksi->getSelesai()]) && isset($mapForSelesai[$bukuDireksi->getSelesai()]["label"]) ? $mapForSelesai[$bukuDireksi->getSelesai()]["label"] : "";?></td>
								<td data-col-name="aktif"><?php echo $bukuDireksi->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval">
									<?php if(UserAction::isRequireApproval($bukuDireksi->getWaitingFor())){ ?>
									<a class="btn btn-tn btn-success" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_direksi_id, $bukuDireksi->getBukuDireksiId(), array(UserAction::NEXT_ACTION => UserAction::APPROVAL));?>"><?php echo $appLanguage->getButtonApproveTiny();?></a>
									<?php echo UserAction::getWaitingForText($appLanguage, $bukuDireksi->getWaitingFor());?>
									<?php } ?>
								</td>
								<?php } ?>
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
			else if($inputGet->isShowRequireApprovalOnly())
			{
			    ?>
			    <div class="alert alert-info"><?php echo $appLanguage->getMessageNoDataRequireApproval();?></div>
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

