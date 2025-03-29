<?php

// This script is generated automatically by AppBuilder
// Visit https://github.com/Planetbiru/AppBuilder

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
use Sipro\Entity\Data\GaleriProyek;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\LokasiProyekMin;
use Sipro\Entity\Data\BukuHarianMin;
use Sipro\Entity\Data\PekerjaanMin;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "galeri-proyek";
$currentModule = new PicoModule($appConfig, $database, null, "/", "galeri-proyek", $appLanguage->getGaleriProyek());

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$galeriProyek = new GaleriProyek(null, $database);
	$galeriProyek->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setFile($inputPost->getFile(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setMd5($inputPost->getMd5(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setDeskripsi($inputPost->getDeskripsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setWidth($inputPost->getWidth(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setHeight($inputPost->getHeight(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setExif($inputPost->getExif(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setAltitude($inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setWaktuFoto($inputPost->getWaktuFoto(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setAdminBuat($currentUser->getUserId());
	$galeriProyek->setWaktuBuat($currentAction->getTime());
	$galeriProyek->setIpBuat($currentAction->getIp());
	$galeriProyek->setAdminUbah($currentUser->getUserId());
	$galeriProyek->setWaktuUbah($currentAction->getTime());
	$galeriProyek->setIpUbah($currentAction->getIp());
	$galeriProyek->insert();
	$newId = $galeriProyek->getGaleriProyekId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->galeri_proyek_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$galeriProyek = new GaleriProyek(null, $database);
	$galeriProyek->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setFile($inputPost->getFile(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setMd5($inputPost->getMd5(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setDeskripsi($inputPost->getDeskripsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setWidth($inputPost->getWidth(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setHeight($inputPost->getHeight(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setExif($inputPost->getExif(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setAltitude($inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$galeriProyek->setWaktuFoto($inputPost->getWaktuFoto(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$galeriProyek->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->setAdminUbah($currentUser->getUserId());
	$galeriProyek->setWaktuUbah($currentAction->getTime());
	$galeriProyek->setIpUbah($currentAction->getIp());
	$galeriProyek->setGaleriProyekId($inputPost->getGaleriProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$galeriProyek->update();
	$newId = $galeriProyek->getGaleriProyekId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->galeri_proyek_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$galeriProyek = new GaleriProyek(null, $database);
			$galeriProyek->setGaleriProyekId($rowId)->setAktif(true)->update();
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
			$galeriProyek = new GaleriProyek(null, $database);
			$galeriProyek->setGaleriProyekId($rowId)->setAktif(false)->update();
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
			$galeriProyek = new GaleriProyek(null, $database);
			$galeriProyek->deleteOneByGaleriProyekId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new GaleriProyek(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
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
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama)
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
									->addAnd(new PicoPredicate(Field::of()->draft, true))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getpProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td>
							<select class="form-control" name="buku_harian_id" id="buku_harian_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuHarianMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->tanggal, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->bukuHarianId, Field::of()->tanggal)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<select class="form-control" name="pekerjaan_id" id="pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pekerjaanId, Field::of()->kegiatan)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama" id="nama"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFile();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="file" id="file"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getMd5();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="md5" id="md5"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="deskripsi" id="deskripsi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWidth();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="width" id="width"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHeight();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="height" id="height"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getExif();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="exif" id="exif"/>
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
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="altitude" id="altitude"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuFoto();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_foto" id="waktu_foto"/>
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
	$galeriProyek = new GaleriProyek(null, $database);
	try{
		$galeriProyek->findOneByGaleriProyekId($inputGet->getGaleriProyekId());
		if($galeriProyek->hasValueGaleriProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new GaleriProyek(), $appConfig, $currentUser->getLanguageId());
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
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->proyekId, Field::of()->nama, $galeriProyek->getProyekId())
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
									->addAnd(new PicoPredicate(Field::of()->draft, true))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getpProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama, $galeriProyek->getLokasiProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td>
							<select class="form-control" name="buku_harian_id" id="buku_harian_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuHarianMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->tanggal, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->bukuHarianId, Field::of()->tanggal, $galeriProyek->getBukuHarianId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<select class="form-control" name="pekerjaan_id" id="pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PekerjaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, true)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pekerjaanId, Field::of()->kegiatan, $galeriProyek->getPekerjaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $galeriProyek->getNama();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFile();?></td>
						<td>
							<input type="text" class="form-control" name="file" id="file" value="<?php echo $galeriProyek->getFile();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getMd5();?></td>
						<td>
							<input type="text" class="form-control" name="md5" id="md5" value="<?php echo $galeriProyek->getMd5();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td>
							<input type="text" class="form-control" name="deskripsi" id="deskripsi" value="<?php echo $galeriProyek->getDeskripsi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWidth();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="width" id="width" value="<?php echo $galeriProyek->getWidth();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHeight();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="height" id="height" value="<?php echo $galeriProyek->getHeight();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getExif();?></td>
						<td>
							<input type="text" class="form-control" name="exif" id="exif" value="<?php echo $galeriProyek->getExif();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="latitude" id="latitude" value="<?php echo $galeriProyek->getLatitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="longitude" id="longitude" value="<?php echo $galeriProyek->getLongitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="altitude" id="altitude" value="<?php echo $galeriProyek->getAltitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuFoto();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_foto" id="waktu_foto" value="<?php echo $galeriProyek->getWaktuFoto();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $galeriProyek->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="galeri_proyek_id" value="<?php echo $galeriProyek->getGaleriProyekId();?>"/>
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
	$galeriProyek = new GaleriProyek(null, $database);
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
		"lokasiProyekId" => array(
			"columnName" => "lokasi_proyek_id",
			"entityName" => "LokasiProyekMin",
			"tableName" => "lokasi_proyek",
			"primaryKey" => "lokasi_proyek_id",
			"objectName" => "lokasi_proyek",
			"propertyName" => "nama"
		), 
		"bukuHarianId" => array(
			"columnName" => "buku_harian_id",
			"entityName" => "BukuHarianMin",
			"tableName" => "buku_harian",
			"primaryKey" => "buku_harian_id",
			"objectName" => "buku_harian",
			"propertyName" => "tanggal"
		), 
		"pekerjaanId" => array(
			"columnName" => "pekerjaan_id",
			"entityName" => "PekerjaanMin",
			"tableName" => "pekerjaan",
			"primaryKey" => "pekerjaan_id",
			"objectName" => "pekerjaan",
			"propertyName" => "kegiatan"
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
		$galeriProyek->findOneWithPrimaryKeyValue($inputGet->getGaleriProyekId(), $subqueryMap);
		if($galeriProyek->hasValueGaleriProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new GaleriProyek(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $galeriProyek->hasValueProyek() ? $galeriProyek->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiProyek();?></td>
						<td><?php echo $galeriProyek->hasValueLokasiProyek() ? $galeriProyek->getLokasiProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td><?php echo $galeriProyek->hasValueBukuHarian() ? $galeriProyek->getBukuHarian()->getTanggal() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td><?php echo $galeriProyek->hasValuePekerjaan() ? $galeriProyek->getPekerjaan()->getKegiatan() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $galeriProyek->hasValueSupervisor() ? $galeriProyek->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $galeriProyek->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getFile();?></td>
						<td><?php echo $galeriProyek->getFile();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getMd5();?></td>
						<td><?php echo $galeriProyek->getMd5();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDeskripsi();?></td>
						<td><?php echo $galeriProyek->getDeskripsi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWidth();?></td>
						<td><?php echo $galeriProyek->getWidth();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHeight();?></td>
						<td><?php echo $galeriProyek->getHeight();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getExif();?></td>
						<td><?php echo $galeriProyek->getExif();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td><?php echo $galeriProyek->getLatitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td><?php echo $galeriProyek->getLongitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td><?php echo $galeriProyek->getAltitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $galeriProyek->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $galeriProyek->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuFoto();?></td>
						<td><?php echo $galeriProyek->getWaktuFoto();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $galeriProyek->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $galeriProyek->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $galeriProyek->getAdminBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $galeriProyek->getAdminUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $galeriProyek->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?><button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->galeri_proyek_id, $galeriProyek->getGaleriProyekId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button><?php } ?>&#xD;
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
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
$appEntityLanguage = new AppEntityLanguage(new GaleriProyek(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
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
									->addAnd(new PicoPredicate(Field::of()->draft, true))
									//->addAnd(new PicoPredicate(Field::of()->ktskId, $currentUser->getKtskId()))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>
				<?php
				if($inputGet->getProyekId() != "")
				{
				?>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getLokasiProyek();?></span>
					<span class="filter-control">
							<select name="lokasi_proyek_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId()))
									, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama, $inputGet->getLokasiProyekId())
								; ?>
							</select>
					</span>
				</span>
				<?php
				}
				?>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>

			</form>
		</div>


		<script type="text/javascript">
			$(document).ready(function(e) {
				$(document).on('change', '#galeriform select', function(e){
					$(this).closest('form').submit();
				});
				$(document).on('click', '.galeri-item a', function(e){
					selectImage($(this).attr('data-galeri-proyek-id'));
					e.preventDefault();
				});
			});
			function selectImage(image_id)
			{
				var obj = $('a[data-galeri-proyek-id="'+image_id+'"]');
				var exif = obj.attr('data-exif') || '';
				var nama_proyek = obj.attr('data-nama-proyek') || '';
				var waktu_upload = obj.attr('data-waktu-upload') || '';
				var kegiatan = obj.attr('data-kegiatan') || '';
				var url = obj.attr('data-url') || '';
				$('#image-container').html('<img src="'+url+'" onerror="this.src=\'lib.assets/images/error.png\';">');
				var data = {};
				if(exif != '')
				{
					data = JSON.parse(exif);
					$('#content-general').empty().append(generateGeneralInfo(data, {nama_proyek:nama_proyek,waktu_upload:waktu_upload,kegiatan:kegiatan}));
					$('#content-location').empty().append(generateMap(data));
					$('#content-camera').empty();
					if(typeof data.capture_info != 'undefined')
					{
						$('#content-camera').append(generateCaptureInfo(data));
					}
				}
			}
			function generateCaptureInfo(data)
			{
				var info = data.capture_info;
				var i, j, k, l;
				var html = '<table width="100%" border="0" cellspacing="0" cellpadding="0" data-table-type="two-cols" data-table-responsive="true">';
				for(i in info)
				{
					j = info[i];
					k = i.split('_').join(' ');
					html += '<tr><td>'+k+'</td><td>'+j+'</td></tr>';
				}
				html += '</table>';
				return html;
			}
			function generateGeneralInfo(data, extended_data)
			{
				var info = data.capture_info;
				var i, j, k, l;
				var html = '<table width="100%" border="0" cellspacing="0" cellpadding="0" data-table-type="two-cols" data-table-responsive="true">';
				html += '<tr><td>Proyek</td><td>'+extended_data.nama_proyek+'</td></tr>';
				html += '<tr><td>Kegiatan</td><td>'+extended_data.kegiatan+'</td></tr>';
				html += '<tr><td>Lebar Gambar</td><td>'+data.width+'</td></tr>';
				html += '<tr><td>Tinggi Gambar</td><td>'+data.height+'</td></tr>';
				html += '<tr><td>Waktu Pengambilan</td><td>'+fixTime(data.time)+'</td></tr>';
				html += '<tr><td>Waktu Upload</td><td>'+(extended_data.waktu_upload)+'</td></tr>';
				html += '</table>';
				return html;
			}
			function fixTime(time)
			{
				time = time.replace(' ', ':');
				arr = time.split(':');
				return arr[0]+'-'+arr[1]+'-'+arr[2]+' '+arr[3]+':'+arr[4]+':'+arr[5];
			}
			function dmsToReal(dms)
			{
				var parts = dms.split(';');
				if(parts.length == 1)
				{
					return 0;
				}
				var degrees = parseFloat(parts[0]);
				var minutes = parseFloat(parts[1]);
				var seconds = parseFloat(parts[2].replace(',','.'));
				

				var dd = degrees + minutes / 60 + seconds / (60 * 60);

				if(parts.length > 3)
				{
					var direction = parts[3];
					if(direction == 'S' || direction == 'W') 
					{
						dd = dd * -1;
					}
				} 
				console.log(dd)
				return dd;
			}
			function generateMap(data)
			{
				var latitude = dmsToReal(data.latitude);
				var longitude = dmsToReal(data.longitude);
				if(latitude == 0 && longitude == 0)
				{
					return '';
				}
				var html = '<div class="static-map-container" style="text-align:center">\r\n'+
				'<a href="https://www.google.com/maps/@'+latitude+','+longitude+',14z" target="_blank"><img alt="['+latitude.toFixed(4)+', '+longitude.toFixed(4)+']" id="static-map-image" src="lib.assets/images/map-64x64.png" ></a>\r\n'+
				'</div>';


				return html;
			}
		</script>
		<?php
		if($inputGet->getProyekId() != "")
		{

		?>
		<div class="data-section">
			
			<?php 	
			
			$specMap = array(
			    "proyekId" => PicoSpecification::filter("proyekId", "number"),
				"lokasiProyekId" => PicoSpecification::filter("lokasiProyekId", "number"),
				"supervisorId" => PicoSpecification::filter("supervisorId", "number")
			);
			$sortOrderMap = array(
			    "proyekId" => "proyekId",
				"pekerjaanId" => "pekerjaanId",
				"nama" => "nama",
				"waktuFoto" => "waktuFoto",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
			
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "waktuBuat", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				)
			));
			
			$dataLoader = new GaleriProyek(null, $database);
			
			$pageData = $dataLoader->findAll($specification, null, $sortable);
			
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

			<style>
			.row-page{
				position:relative
			}
			@media screen and (min-width: 721px){
				
			
				.col-fixed-left{
					float:left;
					width: 250px;
					box-sizing: border-box;
					padding-right: 10px;
				}
				.col-fixed-right{
					float:right;
					width: 250px;
					box-sizing: border-box;
					padding-left: 10px;
				}
				.main-content{
					width: calc(100% - 500px);
					box-sizing: border-box;
					margin-left: 250px;
					position: relative;
					text-align: center;
					padding: 0px 10px;
				}
				.main-content img{
					max-width: 100%;
				}
				.col-fixed-left,
				.col-fixed-right,
				.main-content{
					height: calc(100vh - 216px);
					overflow: auto;
					
				}
				.galeri-item{
					display: inline-block;
					width: 100px;
				}
				
				.col-fixed-right{
					position: absolute;
					top:0px;
					right:0px;
				}

				.right-bar table[data-table-responsive="true"] tr > td{
					display:block;
				}
				.right-bar table[data-table-responsive="true"] tr > td:first-child{
					font-weight: bold;
				}
			}
			@media screen and (max-width: 720px)
			{
				.col-fixed-left{
					width: 100%;
					height: 120px;
					overflow: auto;
					white-space: nowrap;
				}
				.galeri-item{
					display: inline-block;
					
				}
				.right-bar table[data-table-responsive="true"] tr > td:first-child{
					width: 120px;
				}
				.main-content{
					box-sizing: border-box;
					position: relative;
					text-align: center;
				}
				.main-content img{
					max-width: 100%;
				}
				.right-bar{
					font-size: 12px;
					margin-top:20px
				}
			}

			@media screen and (max-width: 360px)
			{
				.right-bar table[data-table-responsive="true"] tr > td{
					display:block;
				}
				.right-bar table[data-table-responsive="true"] tr > td:first-child{
					font-weight: bold;
				}
			}
			.galeri-item{
				margin-right: 8px;
				margin-bottom: 4px;
			}
			.galeri-item a{
				border: 1px solid #999999;
				padding:3px;
				width: 100px;
				height: 100px;
				display: block;
				
				box-sizing: content-box;
			}
			
			.right-bar{
				font-size: 12px;
			}
			
			</style>
			<div class="galery-container">
			<div class="row-page">

			<div class="col-fixed col-fixed-left">
				<?php 
				$dataIndex = 0;
				foreach($pageData->getResult() as $galeriProyek)
				{
					$dataIndex++;
				?>

					<span class="galeri-item" data-galeri-proyek-id="<?php echo $galeriProyek->getGaleriProyekId();?>">
					<a 
					data-galeri-proyek-id="<?php echo $galeriProyek->getGaleriProyekId();?>" 
					data-url="lib.gallery/projects/<?php echo $galeriProyek->getProyekId();?>/<?php echo $galeriProyek->getFile();?>" 
					data-nama-proyek="<?php echo $galeriProyek->hasValueProyek()? $galeriProyek->getProyek()->getNama() : "";?>" 
					data-kegiatan="<?php echo htmlspecialchars(nl2br(htmlspecialchars_decode($galeriProyek->hasValuePekerjaan()? $galeriProyek->getPekerjaan()->getKegiatan() : "")));?>" 
					data-waktu-upload="<?php echo $galeriProyek->getWaktuBuat();?>" 
					data-exif='<?php echo str_replace("'", "\\'", $galeriProyek->getExif());?>'
					href="#<?php echo $galeriProyek->getGaleriProyekId();?>" 
					>
					<img loading="lazy" 
					src="lib.gallery/projects/<?php echo $galeriProyek->getProyekId();?>/<?php echo str_replace(array(".jpg", ".png"), array("_100.jpg", "_100.png"), $galeriProyek->getFile());?>"
					onerror="this.src='lib.assets/images/error-thumbnail-100.png';"
					>
					</a>
					</span>
				<?php 
				}
				?>
			</div>
			
			<div class="main-content col-fluid">
				<div id="image-container" class="main-bar">
				</div>	
			</div>

			<div class="col-fixed col-fixed-right">
				<div class="image-info right-bar">
					<div>
						<div data-role="tab-content" data-section="general">
							<div id="content-general"></div>
						</div>
						<div data-role="tab-content" data-section="camera">
							<div id="content-camera"></div>
						</div>
						<div data-role="tab-content" data-section="location">
							<div id="content-location"></div>
						</div>
					</div>
				</div>
			</div>

			

			</div><!-- /.row -->

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
		</div>
		<?php
		}
		?>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}

