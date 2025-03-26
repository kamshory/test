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
use Sipro\Entity\Data\LokasiPekerjaan;
use Sipro\Entity\Data\Pekerjaan;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\LokasiProyekMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "lokasi-pekerjaan", $appLanguage->getLokasiPekerjaan());
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
	$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
	$lokasiPekerjaan->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lokasiPekerjaan->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lokasiPekerjaan->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$lokasiPekerjaan->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiPekerjaan->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiPekerjaan->setAltitude($inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$lokasiPekerjaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$lokasiPekerjaan->setAdminBuat($currentAction->getUserId());
	$lokasiPekerjaan->setWaktuBuat($currentAction->getTime());
	$lokasiPekerjaan->setIpBuat($currentAction->getIp());
	$lokasiPekerjaan->setAdminUbah($currentAction->getUserId());
	$lokasiPekerjaan->setWaktuUbah($currentAction->getTime());
	$lokasiPekerjaan->setIpUbah($currentAction->getIp());
	try
	{
		$lokasiPekerjaan->insert();
		$newId = $lokasiPekerjaan->getLokasiPekerjaanId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->lokasi_pekerjaan_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->lokasiPekerjaanId, $inputPost->getLokasiPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
	$updater = $lokasiPekerjaan->where($specification)
		->setPekerjaanId($inputPost->getPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setLokasiProyekId($inputPost->getLokasiProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setLatitude($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setLongitude($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setAltitude($inputPost->getAltitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getLokasiPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->lokasi_pekerjaan_id, $newId);
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
			$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
			try
			{
				$lokasiPekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lokasiPekerjaanId, $rowId))
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
			$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
			try
			{
				$lokasiPekerjaan->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lokasiPekerjaanId, $rowId))
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->lokasiPekerjaanId, $rowId))
					->addAnd($dataFilter)
					;
				$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
				$lokasiPekerjaan->where($specification)
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
$appEntityLanguage = new AppEntityLanguage(new LokasiPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<select class="form-control" name="pekerjaan_id" id="pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Pekerjaan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pekerjaanId, Field::of()->kegiatan)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td>
							<select class="form-control" name="buku_harian_id" id="buku_harian_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuHarian(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuHarianId, Field::of()->tanggal)
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
							<input type="number" step="any" class="form-control" name="latitude" id="latitude" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude" id="longitude" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="altitude" id="altitude" autocomplete="off"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->lokasiPekerjaanId, $inputGet->getLokasiPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
	try{
		$lokasiPekerjaan->findOne($specification);
		if($lokasiPekerjaan->issetLokasiPekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new LokasiPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td>
							<select class="form-control" name="pekerjaan_id" id="pekerjaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Pekerjaan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pekerjaanId, Field::of()->kegiatan, $lokasiPekerjaan->getPekerjaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td>
							<select class="form-control" name="buku_harian_id" id="buku_harian_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BukuHarian(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuHarianId, Field::of()->tanggal, $lokasiPekerjaan->getBukuHarianId())
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
								Field::of()->lokasiProyekId, Field::of()->nama, $lokasiPekerjaan->getLokasiProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="latitude" id="latitude" value="<?php echo $lokasiPekerjaan->getLatitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="longitude" id="longitude" value="<?php echo $lokasiPekerjaan->getLongitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td>
							<input type="number" step="any" class="form-control" name="altitude" id="altitude" value="<?php echo $lokasiPekerjaan->getAltitude();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $lokasiPekerjaan->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="lokasi_pekerjaan_id" value="<?php echo $lokasiPekerjaan->getLokasiPekerjaanId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->lokasiPekerjaanId, $inputGet->getLokasiPekerjaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$lokasiPekerjaan = new LokasiPekerjaan(null, $database);
	try{
		$subqueryMap = array(
		"pekerjaanId" => array(
			"columnName" => "pekerjaan_id",
			"entityName" => "Pekerjaan",
			"tableName" => "pekerjaan",
			"primaryKey" => "pekerjaan_id",
			"objectName" => "pekerjaan",
			"propertyName" => "kegiatan"
		), 
		"bukuHarianId" => array(
			"columnName" => "buku_harian_id",
			"entityName" => "BukuHarian",
			"tableName" => "buku_harian",
			"primaryKey" => "buku_harian_id",
			"objectName" => "buku_harian",
			"propertyName" => "tanggal"
		), 
		"lokasiProyekId" => array(
			"columnName" => "lokasi_proyek_id",
			"entityName" => "LokasiProyekMin",
			"tableName" => "lokasi_proyek",
			"primaryKey" => "lokasi_proyek_id",
			"objectName" => "lokasi_proyek",
			"propertyName" => "nama"
		)
		);
		$lokasiPekerjaan->findOne($specification, null, $subqueryMap);
		if($lokasiPekerjaan->issetLokasiPekerjaanId())
		{
$appEntityLanguage = new AppEntityLanguage(new LokasiPekerjaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($lokasiPekerjaan->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $lokasiPekerjaan->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPekerjaan();?></td>
						<td><?php echo $lokasiPekerjaan->issetPekerjaan() ? $lokasiPekerjaan->getPekerjaan()->getKegiatan() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td><?php echo $lokasiPekerjaan->issetBukuHarian() ? $lokasiPekerjaan->getBukuHarian()->getTanggal() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLokasiProyek();?></td>
						<td><?php echo $lokasiPekerjaan->issetLokasiProyek() ? $lokasiPekerjaan->getLokasiProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLatitude();?></td>
						<td><?php echo $lokasiPekerjaan->getLatitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLongitude();?></td>
						<td><?php echo $lokasiPekerjaan->getLongitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAltitude();?></td>
						<td><?php echo $lokasiPekerjaan->getAltitude();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $lokasiPekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->lokasi_pekerjaan_id, $lokasiPekerjaan->getLokasiPekerjaanId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="lokasi_pekerjaan_id" value="<?php echo $lokasiPekerjaan->getLokasiPekerjaanId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new LokasiPekerjaan(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	
);
$sortOrderMap = array(
	"pekerjaanId" => "pekerjaanId",
	"bukuHarianId" => "bukuHarianId",
	"lokasiProyekId" => "lokasiProyekId",
	"latitude" => "latitude",
	"longitude" => "longitude",
	"altitude" => "altitude",
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
		"sortBy" => "lokasiPekerjaanId", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new LokasiPekerjaan(null, $database);

$subqueryMap = array(
"pekerjaanId" => array(
	"columnName" => "pekerjaan_id",
	"entityName" => "Pekerjaan",
	"tableName" => "pekerjaan",
	"primaryKey" => "pekerjaan_id",
	"objectName" => "pekerjaan",
	"propertyName" => "kegiatan"
), 
"bukuHarianId" => array(
	"columnName" => "buku_harian_id",
	"entityName" => "BukuHarian",
	"tableName" => "buku_harian",
	"primaryKey" => "buku_harian_id",
	"objectName" => "buku_harian",
	"propertyName" => "tanggal"
), 
"lokasiProyekId" => array(
	"columnName" => "lokasi_proyek_id",
	"entityName" => "LokasiProyekMin",
	"tableName" => "lokasi_proyek",
	"primaryKey" => "lokasi_proyek_id",
	"objectName" => "lokasi_proyek",
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
		$appEntityLanguage->getPekerjaan() => $headerFormat->asString(),
		$appEntityLanguage->getBukuHarian() => $headerFormat->asString(),
		$appEntityLanguage->getLokasiProyek() => $headerFormat->asString(),
		$appEntityLanguage->getLatitude() => $headerFormat->getLatitude(),
		$appEntityLanguage->getLongitude() => $headerFormat->getLongitude(),
		$appEntityLanguage->getAltitude() => $headerFormat->getAltitude(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
		return array(
			sprintf("%d", $index + 1),
			$row->issetPekerjaan() ? $row->getPekerjaan()->getKegiatan() : "",
			$row->issetBukuHarian() ? $row->getBukuHarian()->getTanggal() : "",
			$row->issetLokasiProyek() ? $row->getLokasiProyek()->getNama() : "",
			$row->getLatitude(),
			$row->getLongitude(),
			$row->getAltitude(),
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
								<td class="data-controll data-selector" data-key="lokasi_pekerjaan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-lokasi-pekerjaan-id"/>
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
								<td data-col-name="pekerjaan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPekerjaan();?></a></td>
								<td data-col-name="buku_harian_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBukuHarian();?></a></td>
								<td data-col-name="lokasi_proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLokasiProyek();?></a></td>
								<td data-col-name="latitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLatitude();?></a></td>
								<td data-col-name="longitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLongitude();?></a></td>
								<td data-col-name="altitude" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAltitude();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($lokasiPekerjaan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $lokasiPekerjaan->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="lokasi_pekerjaan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-lokasi-pekerjaan-id" name="checked_row_id[]" value="<?php echo $lokasiPekerjaan->getLokasiPekerjaanId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->lokasi_pekerjaan_id, $lokasiPekerjaan->getLokasiPekerjaanId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->lokasi_pekerjaan_id, $lokasiPekerjaan->getLokasiPekerjaanId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="pekerjaan_id"><?php echo $lokasiPekerjaan->issetPekerjaan() ? $lokasiPekerjaan->getPekerjaan()->getKegiatan() : "";?></td>
								<td data-col-name="buku_harian_id"><?php echo $lokasiPekerjaan->issetBukuHarian() ? $lokasiPekerjaan->getBukuHarian()->getTanggal() : "";?></td>
								<td data-col-name="lokasi_proyek_id"><?php echo $lokasiPekerjaan->issetLokasiProyek() ? $lokasiPekerjaan->getLokasiProyek()->getNama() : "";?></td>
								<td data-col-name="latitude"><?php echo $lokasiPekerjaan->getLatitude();?></td>
								<td data-col-name="longitude"><?php echo $lokasiPekerjaan->getLongitude();?></td>
								<td data-col-name="altitude"><?php echo $lokasiPekerjaan->getAltitude();?></td>
								<td data-col-name="aktif"><?php echo $lokasiPekerjaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

