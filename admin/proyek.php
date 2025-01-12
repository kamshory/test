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
use MagicApp\AppUserPermission;
use Sipro\Entity\Data\Proyek;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\ProyekApv;
use Sipro\Entity\Data\ProyekTrash;
use Sipro\Entity\Data\KtskMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "proyek", "Proyek");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$proyek = new Proyek(null, $database);
	$proyek->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setDeskripsi($inputPost->getDeskripsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setKodeLokasi($inputPost->getKodeLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setNomorKontrak($inputPost->getNomorKontrak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setNomorSla($inputPost->getNomorSla(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setPelaksana($inputPost->getPelaksana(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setPemberiKerja($inputPost->getPemberiKerja(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setKtskId($inputPost->getKtskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$proyek->setTanggalMulai($inputPost->getTanggalMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setTanggalSelesai($inputPost->getTanggalSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyek->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$proyek->setDraft(true);
	$proyek->setWaitingFor(WaitingFor::CREATE);
	$proyek->setAdminBuat($currentAction->getUserId());
	$proyek->setWaktuBuat($currentAction->getTime());
	$proyek->setIpBuat($currentAction->getIp());
	$proyek->setAdminUbah($currentAction->getUserId());
	$proyek->setWaktuUbah($currentAction->getTime());
	$proyek->setIpUbah($currentAction->getIp());

	try
	{
		$proyek->insert();
		$newId = $proyek->getProyekId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->proyek_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$proyekApv = new ProyekApv(null, $database);
	$proyekApv->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setDeskripsi($inputPost->getDeskripsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setPekerjaan($inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setKodeLokasi($inputPost->getKodeLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setNomorKontrak($inputPost->getNomorKontrak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setNomorSla($inputPost->getNomorSla(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setPelaksana($inputPost->getPelaksana(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setPemberiKerja($inputPost->getPemberiKerja(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setKtskId($inputPost->getKtskId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$proyekApv->setTanggalMulai($inputPost->getTanggalMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setTanggalSelesai($inputPost->getTanggalSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$proyekApv->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$proyekApv->setAdminUbah($currentAction->getUserId());
	$proyekApv->setWaktuUbah($currentAction->getTime());
	$proyekApv->setIpUbah($currentAction->getIp());

	try
	{
		$proyekApv->insert();

		$proyek = new Proyek(null, $database);
		$proyek->setAdminMintaUbah($currentAction->getUserId());
		$proyek->setWaktuMintaUbah($currentAction->getTime());
		$proyek->setIpMintaUbah($currentAction->getIp());
		$proyek->setProyekId($inputPost->getProyekId())->setApprovalId($proyekApv->getProyekApvId())->setWaitingFor(WaitingFor::UPDATE)->update();
		$newId = $proyek->getProyekId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->proyek_id, $newId);
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
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$proyek = new Proyek(null, $database);
			try
			{
				$proyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->setProyekId($rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
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
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$proyek = new Proyek(null, $database);
			try
			{
				$proyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->setProyekId($rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
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
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$proyek = new Proyek(null, $database);
			try
			{
				$proyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->setProyekId($rowId))
					->addAnd(
						PicoSpecification::getInstance()
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, WaitingFor::NOTHING))
							->addOr(PicoPredicate::getInstance()->equals(Field::of()->waitingFor, null))
					)
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
	if($inputPost->issetProyekId())
	{
		$proyekId = $inputPost->getProyekId();
		$proyek = new Proyek(null, $database);
		$proyek->findOneByProyekId($proyekId);
		if($proyek->issetProyekId())
		{
			$approval = new PicoApproval(
			$proyek, 
			$entityInfo, 
			$entityApvInfo, 
			function($param1 = null, $param2 = null, $param3 = null, $userId = null) {
				// Approval validation logic
				// If the return is false, approval will not proceed
				
				// Example: return $param1->notEqualsAdminMintaUbah($userId);
				return true;
			}, 
			true, 
			new ProyekTrash() 
			);

			$approvalCallback = new SetterGetter();
			$approvalCallback->setAfterInsert(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after new data is inserted
				// Your code goes here
				
				return true;
			});

			$approvalCallback->setBeforeUpdate(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute before updating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterUpdate(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after updating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterActivate(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after activating data
				// Your code goes here
				
			});

			$approvalCallback->setAfterDeactivate(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after deactivating data
				// Your code goes here
				
			});

			$approvalCallback->setBeforeDelete(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute before deleting data
				// Your code goes here
				
			});

			$approvalCallback->setAfterDelete(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after deleting data
				// Your code goes here
				
			});

			$approvalCallback->setAfterApprove(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after approval
				// Your code goes here
				
			});

			// List of properties to be copied from ProyekApv to Proyek when the user approves data modification.
			// You can modify this list by adding or removing fields as needed.
			$columToBeCopied = array(
				Field::of()->nama, 
				Field::of()->deskripsi, 
				Field::of()->pekerjaan, 
				Field::of()->kodeLokasi, 
				Field::of()->nomorKontrak, 
				Field::of()->nomorSla, 
				Field::of()->pelaksana, 
				Field::of()->pemberiKerja, 
				Field::of()->ktskId, 
				Field::of()->tanggalMulai, 
				Field::of()->tanggalSelesai, 
				Field::of()->aktif
			);

			$approval->approve($columToBeCopied, new ProyekApv(), new ProyekTrash(), 
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
	if($inputPost->issetProyekId())
	{
		$proyekId = $inputPost->getProyekId();
		$proyek = new Proyek(null, $database);
		$proyek->findOneByProyekId($proyekId);
		if($proyek->issetProyekId())
		{
			$approval = new PicoApproval(
			$proyek, 
			$entityInfo, 
			$entityApvInfo, 
			function($param1 = null, $param2 = null, $param3 = null, $userId = null) {
				// Approval validation logic
				// If the return is false, approval will not proceed
				
				// Example: return $param1->notEqualsAdminMintaUbah($userId);
				return true;
			});


			$approvalCallback = new SetterGetter();
			$approvalCallback->setBeforeReject(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute before reject data
				// Your code goes here
				
			});

			$approvalCallback->setAfterReject(function($param1 = null, $param2 = null, $param3 = null) {
				// Logic to execute after reject data
				// Your code goes here
				
			});

			$approval->reject(new ProyekApv(),
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
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
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
							<input autocomplete="off" class="form-control" type="text" name="nama" id="nama"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td>
							<textarea class="form-control" name="deskripsi" id="deskripsi" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<textarea class="form-control" name="pekerjaan" id="pekerjaan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="kode_lokasi" id="kode_lokasi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="nomor_kontrak" id="nomor_kontrak"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSla();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="nomor_sla" id="nomor_sla"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPelaksana();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="pelaksana" id="pelaksana"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPemberiKerja();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="text" name="pemberi_kerja" id="pemberi_kerja"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td>
							<select class="form-control" name="ktsk_id" id="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->ktskId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="tanggal_selesai" id="tanggal_selesai"/>
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
	$proyek = new Proyek(null, $database);
	try{
		$proyek->findOneByProyekId($inputGet->getProyekId());
		if($proyek->issetProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
		if(!UserAction::isRequireApproval($proyek->getWaitingFor()))
		{
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input class="form-control" type="text" name="nama" id="nama" value="<?php echo $proyek->getNama();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td>
							<textarea class="form-control" name="deskripsi" id="deskripsi" spellcheck="false"><?php echo $proyek->getDeskripsi();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<textarea class="form-control" name="pekerjaan" id="pekerjaan" spellcheck="false"><?php echo $proyek->getPekerjaan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td>
							<input class="form-control" type="text" name="kode_lokasi" id="kode_lokasi" value="<?php echo $proyek->getKodeLokasi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<input class="form-control" type="text" name="nomor_kontrak" id="nomor_kontrak" value="<?php echo $proyek->getNomorKontrak();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSla();?></td>
						<td>
							<input class="form-control" type="text" name="nomor_sla" id="nomor_sla" value="<?php echo $proyek->getNomorSla();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPelaksana();?></td>
						<td>
							<input class="form-control" type="text" name="pelaksana" id="pelaksana" value="<?php echo $proyek->getPelaksana();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPemberiKerja();?></td>
						<td>
							<input class="form-control" type="text" name="pemberi_kerja" id="pemberi_kerja" value="<?php echo $proyek->getPemberiKerja();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td>
							<select class="form-control" name="ktsk_id" id="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->ktskId, Field::of()->nama, $proyek->getKtskId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $proyek->getTanggalMulai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input class="form-control" type="date" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo $proyek->getTanggalSelesai();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $proyek->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="proyek_id" value="<?php echo $proyek->getProyekId();?>"/>
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
	$proyek = new Proyek(null, $database);
	try{
		$subqueryMap = array(
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		), 
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "UserMin",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pembuat",
			"propertyName" => "first_name"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "UserMin",
			"tableName" => "user",
			"primaryKey" => "user_id",
			"objectName" => "pengubah",
			"propertyName" => "first_name"
		)
		);
		$proyek->findOneByProyekId($inputGet->getProyekId(), $subqueryMap);
		if($proyek->issetProyekId())
		{
			// Define map here
			
			if(UserAction::isRequireNextAction($inputGet) && $proyek->notNullApprovalId())
			{
				$proyekApv = new ProyekApv(null, $database);
				try
				{
					$proyekApv->findOneBy($proyek->getApprovalId(), $subqueryMap);
				}
				catch(Exception $e)
				{
					// do something here
				}
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
			<div class="alert alert-info">	
			<?php
			echo UserAction::getWaitingForMessage($appLanguage, $proyek->getWaitingFor());
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
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNama($proyekApv->getNama()));?>"><?php echo $proyek->getNama();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNama($proyekApv->getNama()));?>"><?php echo $proyekApv->getNama();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsDeskripsi($proyekApv->getDeskripsi()));?>"><?php echo $proyek->getDeskripsi();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsDeskripsi($proyekApv->getDeskripsi()));?>"><?php echo $proyekApv->getDeskripsi();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPekerjaan($proyekApv->getPekerjaan()));?>"><?php echo $proyek->getPekerjaan();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPekerjaan($proyekApv->getPekerjaan()));?>"><?php echo $proyekApv->getPekerjaan();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsKodeLokasi($proyekApv->getKodeLokasi()));?>"><?php echo $proyek->getKodeLokasi();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsKodeLokasi($proyekApv->getKodeLokasi()));?>"><?php echo $proyekApv->getKodeLokasi();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorKontrak();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNomorKontrak($proyekApv->getNomorKontrak()));?>"><?php echo $proyek->getNomorKontrak();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNomorKontrak($proyekApv->getNomorKontrak()));?>"><?php echo $proyekApv->getNomorKontrak();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSla();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNomorSla($proyekApv->getNomorSla()));?>"><?php echo $proyek->getNomorSla();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsNomorSla($proyekApv->getNomorSla()));?>"><?php echo $proyekApv->getNomorSla();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPelaksana();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPelaksana($proyekApv->getPelaksana()));?>"><?php echo $proyek->getPelaksana();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPelaksana($proyekApv->getPelaksana()));?>"><?php echo $proyekApv->getPelaksana();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPemberiKerja();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPemberiKerja($proyekApv->getPemberiKerja()));?>"><?php echo $proyek->getPemberiKerja();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPemberiKerja($proyekApv->getPemberiKerja()));?>"><?php echo $proyekApv->getPemberiKerja();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKtsk();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsKtskId($proyekApv->getKtskId()));?>"><?php echo $proyek->issetKtsk() ? $proyek->getKtsk()->getNama() : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsKtskId($proyekApv->getKtskId()));?>"><?php echo $proyekApv->issetKtsk() ? $proyekApv->getKtsk()->getNama() : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsTanggalMulai($proyekApv->getTanggalMulai()));?>"><?php echo $proyek->getTanggalMulai();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsTanggalMulai($proyekApv->getTanggalMulai()));?>"><?php echo $proyekApv->getTanggalMulai();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsTanggalSelesai($proyekApv->getTanggalSelesai()));?>"><?php echo $proyek->getTanggalSelesai();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsTanggalSelesai($proyekApv->getTanggalSelesai()));?>"><?php echo $proyekApv->getTanggalSelesai();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPersen($proyekApv->getPersen()));?>"><?php echo $proyek->getPersen();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsPersen($proyekApv->getPersen()));?>"><?php echo $proyekApv->getPersen();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsWaktuBuat($proyekApv->getWaktuBuat()));?>"><?php echo $proyek->getWaktuBuat();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsWaktuBuat($proyekApv->getWaktuBuat()));?>"><?php echo $proyekApv->getWaktuBuat();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsWaktuUbah($proyekApv->getWaktuUbah()));?>"><?php echo $proyek->getWaktuUbah();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsWaktuUbah($proyekApv->getWaktuUbah()));?>"><?php echo $proyekApv->getWaktuUbah();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsIpBuat($proyekApv->getIpBuat()));?>"><?php echo $proyek->getIpBuat();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsIpBuat($proyekApv->getIpBuat()));?>"><?php echo $proyekApv->getIpBuat();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsIpUbah($proyekApv->getIpUbah()));?>"><?php echo $proyek->getIpUbah();?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsIpUbah($proyekApv->getIpUbah()));?>"><?php echo $proyekApv->getIpUbah();?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAdminBuat($proyekApv->getAdminBuat()));?>"><?php echo $proyek->issetPembuat() ? $proyek->getPembuat()->getFirstName() : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAdminBuat($proyekApv->getAdminBuat()));?>"><?php echo $proyekApv->issetPembuat() ? $proyekApv->getPembuat()->getFirstName() : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAdminUbah($proyekApv->getAdminUbah()));?>"><?php echo $proyek->issetPengubah() ? $proyek->getPengubah()->getFirstName() : "";?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAdminUbah($proyekApv->getAdminUbah()));?>"><?php echo $proyekApv->issetPengubah() ? $proyekApv->getPengubah()->getFirstName() : "";?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAktif($proyekApv->getAktif()));?>"><?php echo $proyek->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></span>
						</td>
						<td>
							<span class="<?php echo AppFormBuilder::classCompareData($proyek->notEqualsAktif($proyekApv->getAktif()));?>"><?php echo $proyekApv->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></span>
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
							<input type="hidden" name="proyek_id" value="<?php echo $proyek->getProyekId();?>"/>
							<?php 
							}
							else if($inputGet->getNextAction() == UserAction::APPROVE)
							{
							?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="proyek_id" value="<?php echo $proyek->getProyekId();?>"/>
							<?php 
							}
							else
							{
							?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="proyek_id" value="<?php echo $proyek->getProyekId();?>"/>
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
require_once $appInclude->mainAppFooter(__DIR__);
			}
			else
			{
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
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
						<td><?php echo $proyek->getWaktuBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $proyek->getWaktuUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $proyek->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $proyek->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $proyek->issetPembuat() ? $proyek->getPembuat()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $proyek->issetPengubah() ? $proyek->getPengubah()->getFirstName() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $proyek->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVAL && UserAction::isRequireApproval($proyek->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($proyek->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($proyek->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
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
require_once $appInclude->mainAppFooter(__DIR__);
			}
		}
		else
		{
require_once $appInclude->mainAppHeader(__DIR__);
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php 
require_once $appInclude->mainAppFooter(__DIR__);
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
$appEntityLanguage = new AppEntityLanguage(new Proyek(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"nama" => PicoSpecification::filter("nama", "fulltext"),
	"kodeLokasi" => PicoSpecification::filter("kodeLokasi", "fulltext"),
	"nomorKontrak" => PicoSpecification::filter("nomorKontrak", "fulltext"),
	"nomorSla" => PicoSpecification::filter("nomorSla", "fulltext"),
	"ktskId" => PicoSpecification::filter("ktskId", "number"),
	"aktif" => PicoSpecification::filter("aktif", "boolean")
);
$sortOrderMap = array(
	"nama" => "nama",
	"kodeLokasi" => "kodeLokasi",
	"nomorKontrak" => "nomorKontrak",
	"nomorSla" => "nomorSla",
	"ktskId" => "ktskId",
	"persen" => "persen",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);

if($inputGet->isShowRequireApprovalOnly()){
	$specification->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->waitingFor, WaitingFor::NOTHING));
	$specification->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->waitingFor, null));
}

// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "proyekId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new Proyek(null, $database);

$subqueryMap = array(
"ktskId" => array(
	"columnName" => "ktsk_id",
	"entityName" => "KtskMin",
	"tableName" => "ktsk",
	"primaryKey" => "ktsk_id",
	"objectName" => "ktsk",
	"propertyName" => "nama"
), 
"adminBuat" => array(
	"columnName" => "admin_buat",
	"entityName" => "UserMin",
	"tableName" => "user",
	"primaryKey" => "user_id",
	"objectName" => "pembuat",
	"propertyName" => "first_name"
), 
"adminUbah" => array(
	"columnName" => "admin_ubah",
	"entityName" => "UserMin",
	"tableName" => "user",
	"primaryKey" => "user_id",
	"objectName" => "pengubah",
	"propertyName" => "first_name"
)
);

if($inputGet->getUserAction() == UserAction::EXPORT)
{
	$exporter = DocumentWriter::getXLSXDocumentWriter($appLanguage);
	$fileName = $currentModule->getModuleName()."-".date("Y-m-d-H-i-s").".xlsx";
	$sheetName = "Sheet 1";

	$headerFormat = new XLSXDataFormat($dataLoader, 3);
	$pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_COUNT_DATA | MagicObject::FIND_OPTION_NO_FETCH_DATA);
	$exporter->write($pageData, $fileName, $sheetName, array(
		$appLanguage->getNumero() => $headerFormat->asNumber(),
		$appEntityLanguage->getProyekId() => $headerFormat->getProyekId(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getDeskripsi() => $headerFormat->asString(),
		$appEntityLanguage->getPekerjaan() => $headerFormat->asString(),
		$appEntityLanguage->getKodeLokasi() => $headerFormat->getKodeLokasi(),
		$appEntityLanguage->getNomorKontrak() => $headerFormat->getNomorKontrak(),
		$appEntityLanguage->getNomorSla() => $headerFormat->getNomorSla(),
		$appEntityLanguage->getPelaksana() => $headerFormat->getPelaksana(),
		$appEntityLanguage->getPemberiKerja() => $headerFormat->getPemberiKerja(),
		$appEntityLanguage->getKtsk() => $headerFormat->asString(),
		$appEntityLanguage->getGaleri() => $headerFormat->getGaleri(),
		$appEntityLanguage->getTanggalMulai() => $headerFormat->getTanggalMulai(),
		$appEntityLanguage->getTanggalSelesai() => $headerFormat->getTanggalSelesai(),
		$appEntityLanguage->getPersen() => $headerFormat->getPersen(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->asString(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->asString(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row, $appLanguage){
		
		return array(
			sprintf("%d", $index + 1),
			$row->getProyekId(),
			$row->getNama(),
			$row->getDeskripsi(),
			$row->getPekerjaan(),
			$row->getKodeLokasi(),
			$row->getNomorKontrak(),
			$row->getNomorSla(),
			$row->getPelaksana(),
			$row->getPemberiKerja(),
			$row->issetKtsk() ? $row->getKtsk()->getNama() : "",
			$row->getGaleri(),
			$row->getTanggalMulai(),
			$row->getTanggalSelesai(),
			$row->getPersen(),
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getIpBuat(),
			$row->getIpUbah(),
			$row->issetPembuat() ? $row->getPembuat()->getFirstName() : "",
			$row->issetPengubah() ? $row->getPengubah()->getFirstName() : "",
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
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getKodeLokasi();?></span>
					<span class="filter-control">
						<input type="text" name="kode_lokasi" class="form-control" value="<?php echo $inputGet->getKodeLokasi();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNomorKontrak();?></span>
					<span class="filter-control">
						<input type="text" name="nomor_kontrak" class="form-control" value="<?php echo $inputGet->getNomorKontrak();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNomorSla();?></span>
					<span class="filter-control">
						<input type="text" name="nomor_sla" class="form-control" value="<?php echo $inputGet->getNomorSla();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getKtsk();?></span>
					<span class="filter-control">
							<select class="form-control" name="ktsk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KtskMin(null, $database), 
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
					<span class="filter-label"><?php echo $appEntityLanguage->getAktif();?></span>
					<span class="filter-control">
							<select class="form-control" name="aktif" data-value="<?php echo $inputGet->getAktif();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="yes" <?php echo AppFormBuilder::selected($inputGet->getAktif(), 'yes');?>><?php echo $appLanguage->getOptionLabelYes();?></option>
								<option value="no" <?php echo AppFormBuilder::selected($inputGet->getAktif(), 'no');?>><?php echo $appLanguage->getOptionLabelNo();?></option>
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
				<?php if($userPermission->isAllowedDetail()){ ?>
		
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
								<td class="data-controll data-selector" data-key="proyek_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-proyek-id"/>
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
								<td data-col-name="kode_lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKodeLokasi();?></a></td>
								<td data-col-name="nomor_kontrak" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorKontrak();?></a></td>
								<td data-col-name="nomor_sla" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorSla();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="persen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersen();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval"><?php echo $appLanguage->getApproval();?></td>
								<?php } ?>
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
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="proyek_id">
									<input type="checkbox" class="checkbox check-slave checkbox-proyek-id" name="checked_row_id[]" value="<?php echo $proyek->getProyekId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->proyek_id, $proyek->getProyekId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->proyek_id, $proyek->getProyekId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nama"><?php echo $proyek->getNama();?></td>
								<td data-col-name="kode_lokasi"><?php echo $proyek->getKodeLokasi();?></td>
								<td data-col-name="nomor_kontrak"><?php echo $proyek->getNomorKontrak();?></td>
								<td data-col-name="nomor_sla"><?php echo $proyek->getNomorSla();?></td>
								<td data-col-name="ktsk_id"><?php echo $proyek->issetKtsk() ? $proyek->getKtsk()->getNama() : "";?></td>
								<td data-col-name="persen"><?php echo $proyek->getPersen();?></td>
								<td data-col-name="aktif"><?php echo $proyek->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval">
									<?php if(UserAction::isRequireApproval($proyek->getWaitingFor())){ ?>
									<a class="btn btn-tn btn-success" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->proyek_id, $proyek->getProyekId(), array(UserAction::NEXT_ACTION => UserAction::APPROVAL));?>"><?php echo $appLanguage->getButtonApproveTiny();?></a>
									<?php echo UserAction::getWaitingForText($appLanguage, $proyek->getWaitingFor());?>
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
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}

