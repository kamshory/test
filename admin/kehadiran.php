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
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Kehadiran;
use Sipro\Entity\Data\AdminMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\PeriodeMin;
use Sipro\Entity\Data\LokasiKehadiranMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "kehadiran", $appLanguage->getKehadiran());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

$dataFilter = null;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$kehadiran = new Kehadiran(null, $database);
	$kehadiran->setTipePengguna($inputPost->getTipePengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAdminId($inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kehadiran->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kehadiran->setPeriodeId($inputPost->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setTipeKehadiran($inputPost->getTipeKehadiran(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setWaktuMasuk($inputPost->getWaktuMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLokasiMasukId($inputPost->getLokasiMasukId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setFotoMasuk($inputPost->getFotoMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAlamatMasuk($inputPost->getAlamatMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLatitudeMasuk($inputPost->getLatitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setLongitudeMasuk($inputPost->getLongitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setIpMasuk($inputPost->getIpMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setWaktuPulang($inputPost->getWaktuPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLokasiPulangId($inputPost->getLokasiPulangId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kehadiran->setFotoPulang($inputPost->getFotoPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAlamatPulang($inputPost->getAlamatPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLatitudePulang($inputPost->getLatitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setLongitudePulang($inputPost->getLongitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setIpPulang($inputPost->getIpPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAktivitas($inputPost->getAktivitas(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kehadiran->setAdminBuat($currentAction->getUserId());
	$kehadiran->setWaktuBuat($currentAction->getTime());
	$kehadiran->setIpBuat($currentAction->getIp());
	$kehadiran->setAdminUbah($currentAction->getUserId());
	$kehadiran->setWaktuUbah($currentAction->getTime());
	$kehadiran->setIpUbah($currentAction->getIp());
	try
	{
		$kehadiran->insert();
		$newId = $kehadiran->getKehadiranId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kehadiran_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->kehadiranId, $inputPost->getKehadiranId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$kehadiran = new Kehadiran(null, $database);
	$updater = $kehadiran->where($specification)
		->setTipePengguna($inputPost->getTipePengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAdminId($inputPost->getAdminId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setPeriodeId($inputPost->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setTipeKehadiran($inputPost->getTipeKehadiran(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setWaktuMasuk($inputPost->getWaktuMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLokasiMasukId($inputPost->getLokasiMasukId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setFotoMasuk($inputPost->getFotoMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAlamatMasuk($inputPost->getAlamatMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLatitudeMasuk($inputPost->getLatitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setLongitudeMasuk($inputPost->getLongitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setIpMasuk($inputPost->getIpMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setWaktuPulang($inputPost->getWaktuPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLokasiPulangId($inputPost->getLokasiPulangId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setFotoPulang($inputPost->getFotoPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAlamatPulang($inputPost->getAlamatPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLatitudePulang($inputPost->getLatitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setLongitudePulang($inputPost->getLongitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setIpPulang($inputPost->getIpPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAktivitas($inputPost->getAktivitas(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getKehadiranId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kehadiran_id, $newId);
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
			$kehadiran = new Kehadiran(null, $database);
			try
			{
				$kehadiran->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kehadiranId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
					->addAnd($dataFilter)
				)
				->setAdminUbah($currentAction->getUserId())
				->setWaktuUbah($currentAction->getTime())
				->setIpUbah($currentAction->getIp())
				->setAktif(true)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				error_log($e->getMessage());
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
			$kehadiran = new Kehadiran(null, $database);
			try
			{
				$kehadiran->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kehadiranId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
					->addAnd($dataFilter)
				)
				->setAdminUbah($currentAction->getUserId())
				->setWaktuUbah($currentAction->getTime())
				->setIpUbah($currentAction->getIp())
				->setAktif(false)
				->update();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				error_log($e->getMessage());
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
			try
			{
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kehadiranId, $rowId))
					->addAnd($dataFilter)
					;
				$kehadiran = new Kehadiran(null, $database);
				$kehadiran->where($specification)
					->delete();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
				error_log($e->getMessage());
			}
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new Kehadiran(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePengguna();?></td>
						<td>
							<select class="form-control" name="tipe_pengguna" id="tipe_pengguna">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="admin">Administrator</option>
								<option value="ktsk">KTSK</option>
								<option value="supervisor">Supervisor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdmin();?></td>
						<td>
							<select class="form-control" name="admin_id" id="admin_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AdminMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->adminId, Field::of()->nama)
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
						<td><?php echo $appEntityLanguage->getPeriode();?></td>
						<td>
							<select class="form-control" name="periode_id" id="periode_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PeriodeMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->periodeId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal" id="tanggal" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeKehadiran();?></td>
						<td>
							<select class="form-control" name="tipe_kehadiran" id="tipe_kehadiran">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="H">Hadir</option>
								<option value="I">Ijin</option>
								<option value="C">Cuti</option>
								<option value="D">Dinas</option>
								<option value="T">Tanpa Keterangan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMasuk();?></td>
						<td>
							<input type="datetime-local" class="form-control" name="waktu_masuk" id="waktu_masuk" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiMasuk();?></td>
						<td>
							<select class="form-control" name="lokasi_masuk_id" id="lokasi_masuk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiranMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiKehadiranId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoMasuk();?></td>
						<td>
							<input type="text" class="form-control" name="foto_masuk" id="foto_masuk" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatMasuk();?></td>
						<td>
							<textarea class="form-control" name="alamat_masuk" id="alamat_masuk" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudeMasuk();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="latitude_masuk" id="latitude_masuk" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudeMasuk();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude_masuk" id="longitude_masuk" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpMasuk();?></td>
						<td>
							<input type="text" class="form-control" name="ip_masuk" id="ip_masuk" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuPulang();?></td>
						<td>
							<input type="datetime-local" class="form-control" name="waktu_pulang" id="waktu_pulang" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiPulang();?></td>
						<td>
							<select class="form-control" name="lokasi_pulang_id" id="lokasi_pulang_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiranMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiKehadiranId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoPulang();?></td>
						<td>
							<input type="text" class="form-control" name="foto_pulang" id="foto_pulang" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatPulang();?></td>
						<td>
							<textarea class="form-control" name="alamat_pulang" id="alamat_pulang" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudePulang();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="latitude_pulang" id="latitude_pulang" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudePulang();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude_pulang" id="longitude_pulang" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpPulang();?></td>
						<td>
							<input type="text" class="form-control" name="ip_pulang" id="ip_pulang" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktivitas();?></td>
						<td>
							<textarea class="form-control" name="aktivitas" id="aktivitas" spellcheck="false"></textarea>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->kehadiranId, $inputGet->getKehadiranId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$kehadiran = new Kehadiran(null, $database);
	try{
		$kehadiran->findOne($specification);
		if($kehadiran->issetKehadiranId())
		{
$appEntityLanguage = new AppEntityLanguage(new Kehadiran(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePengguna();?></td>
						<td>
							<select class="form-control" name="tipe_pengguna" id="tipe_pengguna" data-value="<?php echo $kehadiran->getTipePengguna();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="admin" <?php echo AppFormBuilder::selected($kehadiran->getTipePengguna(), 'admin');?>>Administrator</option>
								<option value="ktsk" <?php echo AppFormBuilder::selected($kehadiran->getTipePengguna(), 'ktsk');?>>KTSK</option>
								<option value="supervisor" <?php echo AppFormBuilder::selected($kehadiran->getTipePengguna(), 'supervisor');?>>Supervisor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdmin();?></td>
						<td>
							<select class="form-control" name="admin_id" id="admin_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AdminMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->adminId, Field::of()->nama, $kehadiran->getAdminId())
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
								Field::of()->supervisorId, Field::of()->nama, $kehadiran->getSupervisorId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPeriode();?></td>
						<td>
							<select class="form-control" name="periode_id" id="periode_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PeriodeMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->periodeId, Field::of()->nama, $kehadiran->getPeriodeId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td>
							<input type="date" class="form-control" name="tanggal" id="tanggal" value="<?php echo $kehadiran->getTanggal();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeKehadiran();?></td>
						<td>
							<select class="form-control" name="tipe_kehadiran" id="tipe_kehadiran" data-value="<?php echo $kehadiran->getTipeKehadiran();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="H" <?php echo AppFormBuilder::selected($kehadiran->getTipeKehadiran(), 'H');?>>Hadir</option>
								<option value="I" <?php echo AppFormBuilder::selected($kehadiran->getTipeKehadiran(), 'I');?>>Ijin</option>
								<option value="C" <?php echo AppFormBuilder::selected($kehadiran->getTipeKehadiran(), 'C');?>>Cuti</option>
								<option value="D" <?php echo AppFormBuilder::selected($kehadiran->getTipeKehadiran(), 'D');?>>Dinas</option>
								<option value="T" <?php echo AppFormBuilder::selected($kehadiran->getTipeKehadiran(), 'T');?>>Tanpa Keterangan</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMasuk();?></td>
						<td>
							<input type="datetime-local" class="form-control" name="waktu_masuk" id="waktu_masuk" value="<?php echo $kehadiran->getWaktuMasuk();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiMasuk();?></td>
						<td>
							<select class="form-control" name="lokasi_masuk_id" id="lokasi_masuk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiranMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiKehadiranId, Field::of()->nama, $kehadiran->getLokasiMasukId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoMasuk();?></td>
						<td>
							<input type="text" class="form-control" name="foto_masuk" id="foto_masuk" value="<?php echo $kehadiran->getFotoMasuk();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatMasuk();?></td>
						<td>
							<textarea class="form-control" name="alamat_masuk" id="alamat_masuk" spellcheck="false"><?php echo $kehadiran->getAlamatMasuk();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudeMasuk();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="latitude_masuk" id="latitude_masuk" value="<?php echo $kehadiran->getLatitudeMasuk();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudeMasuk();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude_masuk" id="longitude_masuk" value="<?php echo $kehadiran->getLongitudeMasuk();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpMasuk();?></td>
						<td>
							<input type="text" class="form-control" name="ip_masuk" id="ip_masuk" value="<?php echo $kehadiran->getIpMasuk();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuPulang();?></td>
						<td>
							<input type="datetime-local" class="form-control" name="waktu_pulang" id="waktu_pulang" value="<?php echo $kehadiran->getWaktuPulang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiPulang();?></td>
						<td>
							<select class="form-control" name="lokasi_pulang_id" id="lokasi_pulang_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiranMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiKehadiranId, Field::of()->nama, $kehadiran->getLokasiPulangId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoPulang();?></td>
						<td>
							<input type="text" class="form-control" name="foto_pulang" id="foto_pulang" value="<?php echo $kehadiran->getFotoPulang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatPulang();?></td>
						<td>
							<textarea class="form-control" name="alamat_pulang" id="alamat_pulang" spellcheck="false"><?php echo $kehadiran->getAlamatPulang();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudePulang();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="latitude_pulang" id="latitude_pulang" value="<?php echo $kehadiran->getLatitudePulang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudePulang();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude_pulang" id="longitude_pulang" value="<?php echo $kehadiran->getLongitudePulang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpPulang();?></td>
						<td>
							<input type="text" class="form-control" name="ip_pulang" id="ip_pulang" value="<?php echo $kehadiran->getIpPulang();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktivitas();?></td>
						<td>
							<textarea class="form-control" name="aktivitas" id="aktivitas" spellcheck="false"><?php echo $kehadiran->getAktivitas();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $kehadiran->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="kehadiran_id" value="<?php echo $kehadiran->getKehadiranId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->kehadiranId, $inputGet->getKehadiranId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$kehadiran = new Kehadiran(null, $database);
	try{
		$subqueryMap = array(
		"adminId" => array(
			"columnName" => "admin_id",
			"entityName" => "AdminMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "admin",
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
		"periodeId" => array(
			"columnName" => "periode_id",
			"entityName" => "PeriodeMin",
			"tableName" => "periode",
			"primaryKey" => "periode_id",
			"objectName" => "periode",
			"propertyName" => "nama"
		), 
		"lokasiMasukId" => array(
			"columnName" => "lokasi_masuk_id",
			"entityName" => "LokasiKehadiranMin",
			"tableName" => "lokasi_kehadiran",
			"primaryKey" => "lokasi_kehadiran_id",
			"objectName" => "lokasiMasuk",
			"propertyName" => "nama"
		), 
		"lokasiPulangId" => array(
			"columnName" => "lokasi_pulang_id",
			"entityName" => "LokasiKehadiranMin",
			"tableName" => "lokasi_kehadiran",
			"primaryKey" => "lokasi_kehadiran_id",
			"objectName" => "lokasiPulang",
			"propertyName" => "nama"
		)
		);
		$kehadiran->findOne($specification, null, $subqueryMap);
		if($kehadiran->issetKehadiranId())
		{
$appEntityLanguage = new AppEntityLanguage(new Kehadiran(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			$mapForTipePengguna = array(
				"admin" => array("value" => "admin", "label" => "Administrator", "group" => "", "selected" => false),
				"ktsk" => array("value" => "ktsk", "label" => "KTSK", "group" => "", "selected" => false),
				"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "group" => "", "selected" => false)
			);
			$mapForTipeKehadiran = array(
				"H" => array("value" => "H", "label" => "Hadir", "group" => "", "selected" => false),
				"I" => array("value" => "I", "label" => "Ijin", "group" => "", "selected" => false),
				"C" => array("value" => "C", "label" => "Cuti", "group" => "", "selected" => false),
				"D" => array("value" => "D", "label" => "Dinas", "group" => "", "selected" => false),
				"T" => array("value" => "T", "label" => "Tanpa Keterangan", "group" => "", "selected" => false)
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($kehadiran->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $kehadiran->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePengguna();?></td>
						<td><?php echo isset($mapForTipePengguna) && isset($mapForTipePengguna[$kehadiran->getTipePengguna()]) && isset($mapForTipePengguna[$kehadiran->getTipePengguna()]["label"]) ? $mapForTipePengguna[$kehadiran->getTipePengguna()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdmin();?></td>
						<td><?php echo $kehadiran->issetAdmin() ? $kehadiran->getAdmin()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $kehadiran->issetSupervisor() ? $kehadiran->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPeriode();?></td>
						<td><?php echo $kehadiran->issetPeriode() ? $kehadiran->getPeriode()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $kehadiran->getTanggal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeKehadiran();?></td>
						<td><?php echo isset($mapForTipeKehadiran) && isset($mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]) && isset($mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]["label"]) ? $mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]["label"] : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuMasuk();?></td>
						<td><?php echo $kehadiran->getWaktuMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiMasuk();?></td>
						<td><?php echo $kehadiran->issetLokasiMasuk() ? $kehadiran->getLokasiMasuk()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoMasuk();?></td>
						<td><?php echo $kehadiran->getFotoMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatMasuk();?></td>
						<td><?php echo $kehadiran->getAlamatMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudeMasuk();?></td>
						<td><?php echo $kehadiran->getLatitudeMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudeMasuk();?></td>
						<td><?php echo $kehadiran->getLongitudeMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpMasuk();?></td>
						<td><?php echo $kehadiran->getIpMasuk();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuPulang();?></td>
						<td><?php echo $kehadiran->getWaktuPulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiPulang();?></td>
						<td><?php echo $kehadiran->issetLokasiPulang() ? $kehadiran->getLokasiPulang()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFotoPulang();?></td>
						<td><?php echo $kehadiran->getFotoPulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlamatPulang();?></td>
						<td><?php echo $kehadiran->getAlamatPulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitudePulang();?></td>
						<td><?php echo $kehadiran->getLatitudePulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitudePulang();?></td>
						<td><?php echo $kehadiran->getLongitudePulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpPulang();?></td>
						<td><?php echo $kehadiran->getIpPulang();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktivitas();?></td>
						<td><?php echo $kehadiran->getAktivitas();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $kehadiran->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->kehadiran_id, $kehadiran->getKehadiranId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="kehadiran_id" value="<?php echo $kehadiran->getKehadiranId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new Kehadiran(), $appConfig, $currentUser->getLanguageId());
$mapForTipePengguna = array(
	"admin" => array("value" => "admin", "label" => "Administrator", "group" => "", "selected" => false),
	"ktsk" => array("value" => "ktsk", "label" => "KTSK", "group" => "", "selected" => false),
	"supervisor" => array("value" => "supervisor", "label" => "Supervisor", "group" => "", "selected" => false)
);
$mapForTipeKehadiran = array(
	"H" => array("value" => "H", "label" => "Hadir", "group" => "", "selected" => false),
	"I" => array("value" => "I", "label" => "Ijin", "group" => "", "selected" => false),
	"C" => array("value" => "C", "label" => "Cuti", "group" => "", "selected" => false),
	"D" => array("value" => "D", "label" => "Dinas", "group" => "", "selected" => false),
	"T" => array("value" => "T", "label" => "Tanpa Keterangan", "group" => "", "selected" => false)
);
$specMap = array(
	"tipePengguna" => PicoSpecification::filter("tipePengguna", "fulltext"),
	"adminId" => PicoSpecification::filter("adminId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"periodeId" => PicoSpecification::filter("periodeId", "fulltext"),
	"tanggal" => PicoSpecification::filter("tanggal", "fulltext"),
	"tipeKehadiran" => PicoSpecification::filter("tipeKehadiran", "fulltext"),
	"lokasiMasukId" => PicoSpecification::filter("lokasiMasukId", "fulltext")
);
$sortOrderMap = array(
	"tipePengguna" => "tipePengguna",
	"adminId" => "adminId",
	"supervisorId" => "supervisorId",
	"periodeId" => "periodeId",
	"tanggal" => "tanggal",
	"tipeKehadiran" => "tipeKehadiran",
	"waktuMasuk" => "waktuMasuk",
	"lokasiMasukId" => "lokasiMasukId",
	"fotoMasuk" => "fotoMasuk",
	"waktuPulang" => "waktuPulang",
	"lokasiPulangId" => "lokasiPulangId",
	"fotoPulang" => "fotoPulang",
	"aktivitas" => "aktivitas",
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
		"sortBy" => "tanggal", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new Kehadiran(null, $database);

$subqueryMap = array(
"adminId" => array(
	"columnName" => "admin_id",
	"entityName" => "AdminMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "admin",
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
"periodeId" => array(
	"columnName" => "periode_id",
	"entityName" => "PeriodeMin",
	"tableName" => "periode",
	"primaryKey" => "periode_id",
	"objectName" => "periode",
	"propertyName" => "nama"
), 
"lokasiMasukId" => array(
	"columnName" => "lokasi_masuk_id",
	"entityName" => "LokasiKehadiranMin",
	"tableName" => "lokasi_kehadiran",
	"primaryKey" => "lokasi_kehadiran_id",
	"objectName" => "lokasiMasuk",
	"propertyName" => "nama"
), 
"lokasiPulangId" => array(
	"columnName" => "lokasi_pulang_id",
	"entityName" => "LokasiKehadiranMin",
	"tableName" => "lokasi_kehadiran",
	"primaryKey" => "lokasi_kehadiran_id",
	"objectName" => "lokasiPulang",
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
		$appEntityLanguage->getKehadiranId() => $headerFormat->getKehadiranId(),
		$appEntityLanguage->getTipePengguna() => $headerFormat->asString(),
		$appEntityLanguage->getAdmin() => $headerFormat->asString(),
		$appEntityLanguage->getSupervisor() => $headerFormat->asString(),
		$appEntityLanguage->getPeriode() => $headerFormat->asString(),
		$appEntityLanguage->getTanggal() => $headerFormat->getTanggal(),
		$appEntityLanguage->getTipeKehadiran() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuMasuk() => $headerFormat->getWaktuMasuk(),
		$appEntityLanguage->getLokasiMasuk() => $headerFormat->asString(),
		$appEntityLanguage->getFotoMasuk() => $headerFormat->getFotoMasuk(),
		$appEntityLanguage->getAlamatMasuk() => $headerFormat->asString(),
		$appEntityLanguage->getLatitudeMasuk() => $headerFormat->getLatitudeMasuk(),
		$appEntityLanguage->getLongitudeMasuk() => $headerFormat->getLongitudeMasuk(),
		$appEntityLanguage->getIpMasuk() => $headerFormat->getIpMasuk(),
		$appEntityLanguage->getWaktuPulang() => $headerFormat->getWaktuPulang(),
		$appEntityLanguage->getLokasiPulang() => $headerFormat->asString(),
		$appEntityLanguage->getFotoPulang() => $headerFormat->getFotoPulang(),
		$appEntityLanguage->getAlamatPulang() => $headerFormat->asString(),
		$appEntityLanguage->getLatitudePulang() => $headerFormat->getLatitudePulang(),
		$appEntityLanguage->getLongitudePulang() => $headerFormat->getLongitudePulang(),
		$appEntityLanguage->getIpPulang() => $headerFormat->getIpPulang(),
		$appEntityLanguage->getAktivitas() => $headerFormat->asString(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage, $mapForTipePengguna, $mapForTipeKehadiran) {
		return array(
			sprintf("%d", $index + 1),
			$row->getKehadiranId(),
			isset($mapForTipePengguna) && isset($mapForTipePengguna[$row->getTipePengguna()]) && isset($mapForTipePengguna[$row->getTipePengguna()]["label"]) ? $mapForTipePengguna[$row->getTipePengguna()]["label"] : "",
			$row->issetAdmin() ? $row->getAdmin()->getNama() : "",
			$row->issetSupervisor() ? $row->getSupervisor()->getNama() : "",
			$row->issetPeriode() ? $row->getPeriode()->getNama() : "",
			$row->getTanggal(),
			isset($mapForTipeKehadiran) && isset($mapForTipeKehadiran[$row->getTipeKehadiran()]) && isset($mapForTipeKehadiran[$row->getTipeKehadiran()]["label"]) ? $mapForTipeKehadiran[$row->getTipeKehadiran()]["label"] : "",
			$row->getWaktuMasuk(),
			$row->issetLokasiMasuk() ? $row->getLokasiMasuk()->getNama() : "",
			$row->getFotoMasuk(),
			$row->getAlamatMasuk(),
			$row->getLatitudeMasuk(),
			$row->getLongitudeMasuk(),
			$row->getIpMasuk(),
			$row->getWaktuPulang(),
			$row->issetLokasiPulang() ? $row->getLokasiPulang()->getNama() : "",
			$row->getFotoPulang(),
			$row->getAlamatPulang(),
			$row->getLatitudePulang(),
			$row->getLongitudePulang(),
			$row->getIpPulang(),
			$row->getAktivitas(),
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
					<span class="filter-label"><?php echo $appEntityLanguage->getTipePengguna();?></span>
					<span class="filter-control">
							<select class="form-control" name="tipe_pengguna" data-value="<?php echo $inputGet->getTipePengguna();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="administrator" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'administrator');?>>Administrator</option>
								<option value="ktsk" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'ktsk');?>>KTSK</option>
								<option value="supervisor" <?php echo AppFormBuilder::selected($inputGet->getTipePengguna(), 'supervisor');?>>Aupervisor</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getAdmin();?></span>
					<span class="filter-control">
							<select class="form-control" name="admin_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AdminMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->adminId, Field::of()->nama, $inputGet->getAdminId())
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
					<span class="filter-label"><?php echo $appEntityLanguage->getPeriode();?></span>
					<span class="filter-control">
							<select class="form-control" name="periode_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PeriodeMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->periodeId, Field::of()->nama, $inputGet->getPeriodeId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTanggal();?></span>
					<span class="filter-control">
						<input type="date" class="form-control" name="tanggal" value="<?php echo $inputGet->getTanggal();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTipeKehadiran();?></span>
					<span class="filter-control">
							<select class="form-control" name="tipe_kehadiran" data-value="<?php echo $inputGet->getTipeKehadiran();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="H" <?php echo AppFormBuilder::selected($inputGet->getTipeKehadiran(), 'H');?>>Hadir</option>
								<option value="I" <?php echo AppFormBuilder::selected($inputGet->getTipeKehadiran(), 'I');?>>Ijin</option>
								<option value="C" <?php echo AppFormBuilder::selected($inputGet->getTipeKehadiran(), 'C');?>>Cuti</option>
								<option value="D" <?php echo AppFormBuilder::selected($inputGet->getTipeKehadiran(), 'D');?>>Dinas</option>
								<option value="T" <?php echo AppFormBuilder::selected($inputGet->getTipeKehadiran(), 'T');?>>Tanpa Keterangan</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getLokasiMasuk();?></span>
					<span class="filter-control">
							<select class="form-control" name="lokasi_masuk_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiranMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiKehadiranId, Field::of()->nama, $inputGet->getLokasiMasukId())
								; ?>
							</select>
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
								<td class="data-controll data-selector" data-key="kehadiran_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-kehadiran-id"/>
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
								<td data-col-name="tipe_pengguna" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTipePengguna();?></a></td>
								<td data-col-name="admin_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAdmin();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="periode_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPeriode();?></a></td>
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="tipe_kehadiran" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTipeKehadiran();?></a></td>
								<td data-col-name="waktu_masuk" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuMasuk();?></a></td>
								<td data-col-name="lokasi_masuk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasiMasuk();?></a></td>
								<td data-col-name="foto_masuk" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getFotoMasuk();?></a></td>
								<td data-col-name="waktu_pulang" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getWaktuPulang();?></a></td>
								<td data-col-name="lokasi_pulang_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasiPulang();?></a></td>
								<td data-col-name="foto_pulang" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getFotoPulang();?></a></td>
								<td data-col-name="aktivitas" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktivitas();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($kehadiran = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $kehadiran->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="kehadiran_id">
									<input type="checkbox" class="checkbox check-slave checkbox-kehadiran-id" name="checked_row_id[]" value="<?php echo $kehadiran->getKehadiranId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->kehadiran_id, $kehadiran->getKehadiranId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->kehadiran_id, $kehadiran->getKehadiranId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="tipe_pengguna"><?php echo isset($mapForTipePengguna) && isset($mapForTipePengguna[$kehadiran->getTipePengguna()]) && isset($mapForTipePengguna[$kehadiran->getTipePengguna()]["label"]) ? $mapForTipePengguna[$kehadiran->getTipePengguna()]["label"] : "";?></td>
								<td data-col-name="admin_id"><?php echo $kehadiran->issetAdmin() ? $kehadiran->getAdmin()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $kehadiran->issetSupervisor() ? $kehadiran->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="periode_id"><?php echo $kehadiran->issetPeriode() ? $kehadiran->getPeriode()->getNama() : "";?></td>
								<td data-col-name="tanggal"><?php echo $kehadiran->getTanggal();?></td>
								<td data-col-name="tipe_kehadiran"><?php echo isset($mapForTipeKehadiran) && isset($mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]) && isset($mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]["label"]) ? $mapForTipeKehadiran[$kehadiran->getTipeKehadiran()]["label"] : "";?></td>
								<td data-col-name="waktu_masuk"><?php echo $kehadiran->getWaktuMasuk();?></td>
								<td data-col-name="lokasi_masuk_id"><?php echo $kehadiran->issetLokasiMasuk() ? $kehadiran->getLokasiMasuk()->getNama() : "";?></td>
								<td data-col-name="foto_masuk"><?php echo $kehadiran->getFotoMasuk();?></td>
								<td data-col-name="waktu_pulang"><?php echo $kehadiran->getWaktuPulang();?></td>
								<td data-col-name="lokasi_pulang_id"><?php echo $kehadiran->issetLokasiPulang() ? $kehadiran->getLokasiPulang()->getNama() : "";?></td>
								<td data-col-name="foto_pulang"><?php echo $kehadiran->getFotoPulang();?></td>
								<td data-col-name="aktivitas"><?php echo $kehadiran->getAktivitas();?></td>
								<td data-col-name="aktif"><?php echo $kehadiran->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

