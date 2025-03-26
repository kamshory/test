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
use Sipro\Entity\Data\Pekerjaan;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Entity\Data\JenisPekerjaanMin;
use Sipro\Entity\Data\LokasiProyekMin;
use Sipro\Entity\Data\TipePondasiMin;
use Sipro\Entity\Data\KelasTowerMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "riwayat-pekerjaan", "Riwayat Pekerjaan");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$pekerjaan = new Pekerjaan(null, $database);
	$pekerjaan->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setJenisPekerjaanId($inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setAtitude($inputPost->getAtitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setTipePondasiId($inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setTipeTowerId($inputPost->getTipeTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setJumlahPekerja($inputPost->getJumlahPekerja(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setAcuanPengawasan($inputPost->getAcuanPengawasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pekerjaan->setAdminBuat($currentUser->getUserId());
	$pekerjaan->setWaktuBuat($currentAction->getTime());
	$pekerjaan->setIpBuat($currentAction->getIp());
	$pekerjaan->setAdminUbah($currentUser->getUserId());
	$pekerjaan->setWaktuUbah($currentAction->getTime());
	$pekerjaan->setIpUbah($currentAction->getIp());
	$pekerjaan->insert();
	$newId = $pekerjaan->getPekerjaanId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->pekerjaan_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$pekerjaan = new Pekerjaan(null, $database);
	$pekerjaan->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setJenisPekerjaanId($inputPost->getJenisPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setAtitude($inputPost->getAtitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$pekerjaan->setTipePondasiId($inputPost->getTipePondasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setTipeTowerId($inputPost->getTipeTowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setKegiatan($inputPost->getKegiatan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setJumlahPekerja($inputPost->getJumlahPekerja(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->setAcuanPengawasan($inputPost->getAcuanPengawasan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pekerjaan->setAdminUbah($currentUser->getUserId());
	$pekerjaan->setWaktuUbah($currentAction->getTime());
	$pekerjaan->setIpUbah($currentAction->getIp());
	$pekerjaan->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pekerjaan->update();
	$newId = $pekerjaan->getPekerjaanId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->pekerjaan_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$pekerjaan = new Pekerjaan(null, $database);
			try
			{
				$pekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->pekerjaan_id, $rowId))
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
			$pekerjaan = new Pekerjaan(null, $database);
			try
			{
				$pekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->pekerjaan_id, $rowId))
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
else if($inputPost->getUserAction() == UserAction::DELETE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$pekerjaan = new Pekerjaan(null, $database);
			$pekerjaan->deleteOneByPekerjaanId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new Pekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
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
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td>
							<select class="form-control" name="jenis_pekerjaan_id" id="jenis_pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiProyek();?></td>
						<td>
							<select class="form-control" name="lokasi_proyek_id" id="lokasi_proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="latitude" id="latitude"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="longitude" id="longitude"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtitude();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="atitude" id="atitude"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePondasi();?></td>
						<td>
							<select class="form-control" name="tipe_pondasi_id" id="tipe_pondasi_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TipePondasiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipePondasiId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeTower();?></td>
						<td>
							<select class="form-control" name="kelas_tower_id" id="kelas_tower_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KelasTowerMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipeTowerId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKegiatan();?></td>
						<td>
							<textarea class="form-control" name="kegiatan" id="kegiatan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJumlahPekerja();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="jumlah_pekerja" id="jumlah_pekerja"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td>
							<textarea class="form-control" name="acuan_pengawasan" id="acuan_pengawasan" spellcheck="false"></textarea>
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
	$pekerjaan = new Pekerjaan(null, $database);
	try{
		$pekerjaan->findOneByPekerjaanId($inputGet->getPekerjaanId());
		if($pekerjaan->hasValuePekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new Pekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
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
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $pekerjaan->getProyekId())
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
								Field::of()->supervisorId, Field::of()->nama, $pekerjaan->getSupervisorId())
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td>
							<select class="form-control" name="jenis_pekerjaan_id" id="jenis_pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama, $pekerjaan->getJenisPekerjaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiProyek();?></td>
						<td>
							<select class="form-control" name="lokasi_proyek_id" id="lokasi_proyek_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama, $pekerjaan->getLokasiProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="latitude" id="latitude" value="<?php echo $pekerjaan->getLatitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="longitude" id="longitude" value="<?php echo $pekerjaan->getLongitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="atitude" id="atitude" value="<?php echo $pekerjaan->getAtitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePondasi();?></td>
						<td>
							<select class="form-control" name="tipe_pondasi_id" id="tipe_pondasi_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TipePondasiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipePondasiId, Field::of()->nama, $pekerjaan->getTipePondasiId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeTower();?></td>
						<td>
							<select class="form-control" name="kelas_tower_id" id="kelas_tower_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KelasTowerMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipeTowerId, Field::of()->nama, $pekerjaan->getTipeTowerId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKegiatan();?></td>
						<td>
							<textarea class="form-control" name="kegiatan" id="kegiatan" spellcheck="false"><?php echo $pekerjaan->getKegiatan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJumlahPekerja();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="jumlah_pekerja" id="jumlah_pekerja" value="<?php echo $pekerjaan->getJumlahPekerja();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td>
							<textarea class="form-control" name="acuan_pengawasan" id="acuan_pengawasan" spellcheck="false"><?php echo $pekerjaan->getAcuanPengawasan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $pekerjaan->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="pekerjaan_id" value="<?php echo $pekerjaan->getPekerjaanId();?>"/>
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
	$pekerjaan = new Pekerjaan(null, $database);
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
		"jenisPekerjaanId" => array(
			"columnName" => "jenis_pekerjaan_id",
			"entityName" => "JenisPekerjaanMin",
			"tableName" => "jenis_pekerjaan",
			"primaryKey" => "jenis_pekerjaan_id",
			"objectName" => "jenis_pekerjaan",
			"propertyName" => "nama"
		), 
		"lokasiProyekId" => array(
			"columnName" => "lokasi_proyek_id",
			"entityName" => "LokasiProyekMin",
			"tableName" => "lokasi_proyek",
			"primaryKey" => "lokasi_proyek_id",
			"objectName" => "lokasi_proyek",
			"propertyName" => "nama"
		), 
		"tipePondasiId" => array(
			"columnName" => "tipe_pondasi_id",
			"entityName" => "TipePondasiMin",
			"tableName" => "tipe_pondasi",
			"primaryKey" => "tipe_pondasi_id",
			"objectName" => "tipe_pondasi",
			"propertyName" => "nama"
		), 
		"tipeTowerId" => array(
			"columnName" => "kelas_tower_id",
			"entityName" => "KelasTowerMin",
			"tableName" => "kelas_tower",
			"primaryKey" => "kelas_tower_id",
			"objectName" => "kelas_tower",
			"propertyName" => "nama"
		)
		);
		$pekerjaan->findOneWithPrimaryKeyValue($inputGet->getPekerjaanId(), $subqueryMap);
		if($pekerjaan->hasValuePekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new Pekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($pekerjaan->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $pekerjaan->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $pekerjaan->hasValueProyek() ? $pekerjaan->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $pekerjaan->hasValueSupervisor() ? $pekerjaan->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPekerjaan();?></td>
						<td><?php echo $pekerjaan->hasValueJenisPekerjaan() ? $pekerjaan->getJenisPekerjaan()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiProyek();?></td>
						<td><?php echo $pekerjaan->hasValueLokasiProyek() ? $pekerjaan->getLokasiProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td><?php echo $pekerjaan->getLatitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td><?php echo $pekerjaan->getLongitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtitude();?></td>
						<td><?php echo $pekerjaan->getAtitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipePondasi();?></td>
						<td><?php echo $pekerjaan->hasValueTipePondasi() ? $pekerjaan->getTipePondasi()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTipeTower();?></td>
						<td><?php echo $pekerjaan->hasValueTipeTower() ? $pekerjaan->getTipeTower()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKegiatan();?></td>
						<td><?php echo $pekerjaan->getKegiatan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJumlahPekerja();?></td>
						<td><?php echo $pekerjaan->getJumlahPekerja();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td><?php echo $pekerjaan->getAcuanPengawasan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $pekerjaan->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $pekerjaan->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $pekerjaan->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $pekerjaan->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $pekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVAL && UserAction::isRequireApproval($pekerjaan->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($pekerjaan->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($pekerjaan->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->pekerjaan_id, $pekerjaan->getPekerjaanId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="pekerjaan_id" value="<?php echo $pekerjaan->getPekerjaanId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new Pekerjaan(), $appConfig, $currentUser->getLanguageId());
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
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
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
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorId())
								->setTextNodeFormat('"%s (%s)", nama, jabatan.nama')
								->setIndent(8)
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisPekerjaan();?></span>
					<span class="filter-control">
							<select name="jenis_pekerjaan_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPekerjaanId, Field::of()->nama, $inputGet->getJenisPekerjaanId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTipePondasi();?></span>
					<span class="filter-control">
							<select name="tipe_pondasi_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TipePondasiMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipePondasiId, Field::of()->nama, $inputGet->getTipePondasiId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTipeTower();?></span>
					<span class="filter-control">
							<select name="kelas_tower_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new KelasTowerMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tipeTowerId, Field::of()->nama, $inputGet->getTipeTowerId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
				</span>
				<?php } ?>
			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php 	
			
			$specMap = array(
			    "proyekId" => PicoSpecification::filter("proyekId", "number"),
				"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
				"jenisPekerjaanId" => PicoSpecification::filter("jenisPekerjaanId", "fulltext"),
				"tipePondasiId" => PicoSpecification::filter("tipePondasiId", "number"),
				"tipeTowerId" => PicoSpecification::filter("tipeTowerId", "number")
			);
			$sortOrderMap = array(
			    "proyekId" => "proyekId",
				"supervisorId" => "supervisorId",
				"jenisPekerjaanId" => "jenisPekerjaanId",
				"lokasiProyekId" => "lokasiProyekId",
				"latitude" => "latitude",
				"longitude" => "longitude",
				"atitude" => "atitude",
				"tipePondasiId" => "tipePondasiId",
				"tipeTowerId" => "tipeTowerId",
				"kegiatan" => "kegiatan",
				"jumlahPekerja" => "jumlahPekerja",
				"acuanPengawasan" => "acuanPengawasan",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			// Additional filter here
			$specification->addAnd(PicoPredicate::getInstance()->equals('ktskId', $currentUser->getKtskId()));
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "waktuBuat", 
					"sortType" => PicoSort::ORDER_TYPE_DESC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new Pekerjaan(null, $database);
			
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
			"jenisPekerjaanId" => array(
				"columnName" => "jenis_pekerjaan_id",
				"entityName" => "JenisPekerjaanMin",
				"tableName" => "jenis_pekerjaan",
				"primaryKey" => "jenis_pekerjaan_id",
				"objectName" => "jenis_pekerjaan",
				"propertyName" => "nama"
			), 
			"lokasiProyekId" => array(
				"columnName" => "lokasi_proyek_id",
				"entityName" => "LokasiProyekMin",
				"tableName" => "lokasi_proyek",
				"primaryKey" => "lokasi_proyek_id",
				"objectName" => "lokasi_proyek",
				"propertyName" => "nama"
			), 
			"tipePondasiId" => array(
				"columnName" => "tipe_pondasi_id",
				"entityName" => "TipePondasiMin",
				"tableName" => "tipe_pondasi",
				"primaryKey" => "tipe_pondasi_id",
				"objectName" => "tipe_pondasi",
				"propertyName" => "nama"
			), 
			"tipeTowerId" => array(
				"columnName" => "kelas_tower_id",
				"entityName" => "KelasTowerMin",
				"tableName" => "kelas_tower",
				"primaryKey" => "kelas_tower_id",
				"objectName" => "kelas_tower",
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
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="pekerjaan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-pekerjaan-id"/>
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
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="jenis_pekerjaan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPekerjaan();?></a></td>
								<td data-col-name="lokasi_proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasiProyek();?></a></td>
								<td data-col-name="latitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLatitude();?></a></td>
								<td data-col-name="longitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLongitude();?></a></td>
								<td data-col-name="atitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAtitude();?></a></td>
								<td data-col-name="tipe_pondasi_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTipePondasi();?></a></td>
								<td data-col-name="kelas_tower_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTipeTower();?></a></td>
								<td data-col-name="kegiatan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKegiatan();?></a></td>
								<td data-col-name="jumlah_pekerja" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJumlahPekerja();?></a></td>
								<td data-col-name="acuan_pengawasan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAcuanPengawasan();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($pekerjaan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="pekerjaan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-pekerjaan-id" name="checked_row_id[]" value="<?php echo $pekerjaan->getPekerjaanId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->pekerjaan_id, $pekerjaan->getPekerjaanId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->pekerjaan_id, $pekerjaan->getPekerjaanId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $pekerjaan->hasValueProyek() ? $pekerjaan->getProyek()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $pekerjaan->hasValueSupervisor() ? $pekerjaan->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="jenis_pekerjaan_id"><?php echo $pekerjaan->hasValueJenisPekerjaan() ? $pekerjaan->getJenisPekerjaan()->getNama() : "";?></td>
								<td data-col-name="lokasi_proyek_id"><?php echo $pekerjaan->hasValueLokasiProyek() ? $pekerjaan->getLokasiProyek()->getNama() : "";?></td>
								<td data-col-name="latitude"><?php echo $pekerjaan->getLatitude();?></td>
								<td data-col-name="longitude"><?php echo $pekerjaan->getLongitude();?></td>
								<td data-col-name="atitude"><?php echo $pekerjaan->getAtitude();?></td>
								<td data-col-name="tipe_pondasi_id"><?php echo $pekerjaan->hasValueTipePondasi() ? $pekerjaan->getTipePondasi()->getNama() : "";?></td>
								<td data-col-name="kelas_tower_id"><?php echo $pekerjaan->hasValueTipeTower() ? $pekerjaan->getTipeTower()->getNama() : "";?></td>
								<td data-col-name="kegiatan"><?php echo $pekerjaan->getKegiatan();?></td>
								<td data-col-name="jumlah_pekerja"><?php echo $pekerjaan->getJumlahPekerja();?></td>
								<td data-col-name="acuan_pengawasan"><?php echo $pekerjaan->getAcuanPengawasan();?></td>
								<td data-col-name="aktif"><?php echo $pekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

