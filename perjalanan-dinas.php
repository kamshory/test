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
use Sipro\Entity\Data\PerjalananDinas;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\JenisPerjalananDinas;
use Sipro\Entity\Data\SupervisorMin;

require_once __DIR__ . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/", "perjalanan-dinas", "Perjalanan Dinas");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$perjalananDinas = new PerjalananDinas(null, $database);
	$perjalananDinas->setJenisPerjalananDinasId($inputPost->getJenisPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setNomorSppd($inputPost->getNomorSppd(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAsal($inputPost->getAsal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setTujuan($inputPost->getTujuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKodeLokasi($inputPost->getKodeLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAlatAngkutan($inputPost->getAlatAngkutan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeperluan($inputPost->getKeperluan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDari($inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setHingga($inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAtasBebanBiaya($inputPost->getAtasBebanBiaya(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDibayar($inputPost->getDibayar(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setWaktuDibayar($inputPost->getWaktuDibayar(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$perjalananDinas->setAdminBuat($currentUser->getUserId());
	$perjalananDinas->setWaktuBuat($currentAction->getTime());
	$perjalananDinas->setIpBuat($currentAction->getIp());
	$perjalananDinas->setAdminUbah($currentUser->getUserId());
	$perjalananDinas->setWaktuUbah($currentAction->getTime());
	$perjalananDinas->setIpUbah($currentAction->getIp());
	$perjalananDinas->insert();
	$newId = $perjalananDinas->getPerjalananDinasId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$perjalananDinas = new PerjalananDinas(null, $database);
	$perjalananDinas->setJenisPerjalananDinasId($inputPost->getJenisPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setNomorSppd($inputPost->getNomorSppd(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAsal($inputPost->getAsal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setTujuan($inputPost->getTujuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKodeLokasi($inputPost->getKodeLokasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAlatAngkutan($inputPost->getAlatAngkutan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeperluan($inputPost->getKeperluan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setKeterangan($inputPost->getKeterangan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDari($inputPost->getDari(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setHingga($inputPost->getHingga(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAtasBebanBiaya($inputPost->getAtasBebanBiaya(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setDibayar($inputPost->getDibayar(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->setWaktuDibayar($inputPost->getWaktuDibayar(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$perjalananDinas->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$perjalananDinas->setAdminUbah($currentUser->getUserId());
	$perjalananDinas->setWaktuUbah($currentAction->getTime());
	$perjalananDinas->setIpUbah($currentAction->getIp());
	$perjalananDinas->setPerjalananDinasId($inputPost->getPerjalananDinasId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$perjalananDinas->update();
	$newId = $perjalananDinas->getPerjalananDinasId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$perjalananDinas = new PerjalananDinas(null, $database);
			try
			{
				$perjalananDinas->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->perjalanan_dinas_id, $rowId))
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
			$perjalananDinas = new PerjalananDinas(null, $database);
			try
			{
				$perjalananDinas->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->perjalanan_dinas_id, $rowId))
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
			$perjalananDinas = new PerjalananDinas(null, $database);
			$perjalananDinas->deleteOneByPerjalananDinasId($rowId);
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td>
							<select class="form-control" name="jenis_perjalanan_dinas_id" id="jenis_perjalanan_dinas_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinas(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama)
								->setTextNodeFormat('"%s", nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSppd();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nomor_sppd" id="nomor_sppd"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="asal" id="asal"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="tujuan" id="tujuan"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="kode_lokasi" id="kode_lokasi"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td>
							<textarea class="form-control" name="alat_angkutan" id="alat_angkutan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td>
							<textarea class="form-control" name="keperluan" id="keperluan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="dari" id="dari"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="hingga" id="hingga"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtasBebanBiaya();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="atas_beban_biaya" id="atas_beban_biaya"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dibayar" id="dibayar" value="1"/> <?php echo $appEntityLanguage->getDibayar();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuDibayar();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="datetime-local" name="waktu_dibayar" id="waktu_dibayar"/>
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
	$perjalananDinas = new PerjalananDinas(null, $database);
	try{
		$perjalananDinas->findOneByPerjalananDinasId($inputGet->getPerjalananDinasId());
		if($perjalananDinas->hasValuePerjalananDinasId())
		{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td>
							<select class="form-control" name="jenis_perjalanan_dinas_id" id="jenis_perjalanan_dinas_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinas(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama, $perjalananDinas->getJenisPerjalananDinasId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td>
							<select class="form-control" name="supervisor_id" id="supervisor_id" required="required">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $perjalananDinas->getSupervisorId())
								->setTextNodeFormat('"%s", nama')
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSppd();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_sppd" id="nomor_sppd" value="<?php echo $perjalananDinas->getNomorSppd();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td>
							<input type="text" class="form-control" name="asal" id="asal" value="<?php echo $perjalananDinas->getAsal();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td>
							<input type="text" class="form-control" name="tujuan" id="tujuan" value="<?php echo $perjalananDinas->getTujuan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td>
							<input type="text" class="form-control" name="kode_lokasi" id="kode_lokasi" value="<?php echo $perjalananDinas->getKodeLokasi();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td>
							<textarea class="form-control" name="alat_angkutan" id="alat_angkutan" spellcheck="false"><?php echo $perjalananDinas->getAlatAngkutan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td>
							<textarea class="form-control" name="keperluan" id="keperluan" spellcheck="false"><?php echo $perjalananDinas->getKeperluan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td>
							<textarea class="form-control" name="keterangan" id="keterangan" spellcheck="false"><?php echo $perjalananDinas->getKeterangan();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td>
							<input class="form-control" type="date" name="dari" id="dari" value="<?php echo $perjalananDinas->getDari();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td>
							<input class="form-control" type="date" name="hingga" id="hingga" value="<?php echo $perjalananDinas->getHingga();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtasBebanBiaya();?></td>
						<td>
							<input type="text" class="form-control" name="atas_beban_biaya" id="atas_beban_biaya" value="<?php echo $perjalananDinas->getAtasBebanBiaya();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="dibayar" id="dibayar" value="1" <?php echo $perjalananDinas->createCheckedDibayar();?>/> <?php echo $appEntityLanguage->getDibayar();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuDibayar();?></td>
						<td>
							<input class="form-control" type="datetime-local" name="waktu_dibayar" id="waktu_dibayar" value="<?php echo $perjalananDinas->getWaktuDibayar();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $perjalananDinas->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="perjalanan_dinas_id" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
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
	$perjalananDinas = new PerjalananDinas(null, $database);
	try{
		$subqueryMap = array(
		"jenisPerjalananDinasId" => array(
			"columnName" => "jenis_perjalanan_dinas_id",
			"entityName" => "JenisPerjalananDinas",
			"tableName" => "jenis_perjalanan_dinas",
			"primaryKey" => "jenis_perjalanan_dinas_id",
			"objectName" => "jenis_perjalanan_dinas",
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
		"pembuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
			"propertyName" => "nama_depan"
		), 
		"pengubah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama_depan"
		)
		);
		$perjalananDinas->findOneWithPrimaryKeyValue($inputGet->getPerjalananDinasId(), $subqueryMap);
		if($perjalananDinas->hasValuePerjalananDinasId())
		{
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($perjalananDinas->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $perjalananDinas->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></td>
						<td><?php echo $perjalananDinas->hasValueJenisPerjalananDinas() ? $perjalananDinas->getJenisPerjalananDinas()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $perjalananDinas->hasValueSupervisor() ? $perjalananDinas->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorSppd();?></td>
						<td><?php echo $perjalananDinas->getNomorSppd();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAsal();?></td>
						<td><?php echo $perjalananDinas->getAsal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTujuan();?></td>
						<td><?php echo $perjalananDinas->getTujuan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeLokasi();?></td>
						<td><?php echo $perjalananDinas->getKodeLokasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAlatAngkutan();?></td>
						<td><?php echo $perjalananDinas->getAlatAngkutan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeperluan();?></td>
						<td><?php echo $perjalananDinas->getKeperluan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKeterangan();?></td>
						<td><?php echo $perjalananDinas->getKeterangan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDari();?></td>
						<td><?php echo $perjalananDinas->getDari();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHingga();?></td>
						<td><?php echo $perjalananDinas->getHingga();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAtasBebanBiaya();?></td>
						<td><?php echo $perjalananDinas->getAtasBebanBiaya();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDibayar();?></td>
						<td><?php echo $perjalananDinas->optionDibayar($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuDibayar();?></td>
						<td><?php echo $perjalananDinas->getWaktuDibayar();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPembuat();?></td>
						<td><?php echo $billOfQuantity->issetPembuat() ? $billOfQuantity->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPengubah();?></td>
						<td><?php echo $billOfQuantity->issetPengubah() ? $billOfQuantity->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $perjalananDinas->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $perjalananDinas->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $perjalananDinas->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $perjalananDinas->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $perjalananDinas->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($inputGet->getNextAction() == UserAction::APPROVE && UserAction::isRequireApproval($perjalananDinas->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-success" name="user_action" value="<?php echo UserAction::APPROVE;?>"><?php echo $appLanguage->getButtonApprove();?></button>
							<?php } else if($inputGet->getNextAction() == UserAction::REJECT && UserAction::isRequireApproval($perjalananDinas->getWaitingFor()) && $userPermission->isAllowedApprove()){ ?>
							<button type="submit" class="btn btn-warning" name="user_action" value="<?php echo UserAction::REJECT;?>"><?php echo $appLanguage->getButtonReject();?></button>
							<?php } else if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="perjalanan_dinas_id" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new PerjalananDinas(), $appConfig, $currentUser->getLanguageId());
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></span>
					<span class="filter-control">
							<select name="jenis_perjalanan_dinas_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JenisPerjalananDinas(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jenisPerjalananDinasId, Field::of()->nama, $inputGet->getJenisPerjalananDinasId())
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
					<span class="filter-label"><?php echo $appEntityLanguage->getNomorSppd();?></span>
					<span class="filter-control">
						<input type="text" name="nomor_sppd" class="form-control" value="<?php echo $inputGet->getNomorSppd();?>" autocomplete="off"/>
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
			    "jenisPerjalananDinasId" => PicoSpecification::filter("jenisPerjalananDinasId", "number"),
				"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
				"nomorSppd" => PicoSpecification::filter("nomorSppd", "fulltext")
			);
			$sortOrderMap = array(
			    "jenisPerjalananDinasId" => "jenisPerjalananDinasId",
				"supervisorId" => "supervisorId",
				"nomorSppd" => "nomorSppd",
				"asal" => "asal",
				"tujuan" => "tujuan",
				"kodeLokasi" => "kodeLokasi",
				"dari" => "dari",
				"hingga" => "hingga",
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
					"sortType" => PicoSort::ORDER_TYPE_DESC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new PerjalananDinas(null, $database);
			
			$subqueryMap = array(
			"jenisPerjalananDinasId" => array(
				"columnName" => "jenis_perjalanan_dinas_id",
				"entityName" => "JenisPerjalananDinas",
				"tableName" => "jenis_perjalanan_dinas",
				"primaryKey" => "jenis_perjalanan_dinas_id",
				"objectName" => "jenis_perjalanan_dinas",
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
								<td class="data-controll data-selector" data-key="perjalanan_dinas_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-perjalanan-dinas-id"/>
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
								<td data-col-name="jenis_perjalanan_dinas_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPerjalananDinas();?></a></td>
								<td data-col-name="supervisor_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisor();?></a></td>
								<td data-col-name="nomor_sppd" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorSppd();?></a></td>
								<td data-col-name="asal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAsal();?></a></td>
								<td data-col-name="tujuan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTujuan();?></a></td>
								<td data-col-name="kode_lokasi" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKodeLokasi();?></a></td>
								<td data-col-name="dari" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDari();?></a></td>
								<td data-col-name="hingga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHingga();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($perjalananDinas = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="perjalanan_dinas_id">
									<input type="checkbox" class="checkbox check-slave checkbox-perjalanan-dinas-id" name="checked_row_id[]" value="<?php echo $perjalananDinas->getPerjalananDinasId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->perjalanan_dinas_id, $perjalananDinas->getPerjalananDinasId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="jenis_perjalanan_dinas_id"><?php echo $perjalananDinas->hasValueJenisPerjalananDinas() ? $perjalananDinas->getJenisPerjalananDinas()->getNama() : "";?></td>
								<td data-col-name="supervisor_id"><?php echo $perjalananDinas->hasValueSupervisor() ? $perjalananDinas->getSupervisor()->getNama() : "";?></td>
								<td data-col-name="nomor_sppd"><?php echo $perjalananDinas->getNomorSppd();?></td>
								<td data-col-name="asal"><?php echo $perjalananDinas->getAsal();?></td>
								<td data-col-name="tujuan"><?php echo $perjalananDinas->getTujuan();?></td>
								<td data-col-name="kode_lokasi"><?php echo $perjalananDinas->getKodeLokasi();?></td>
								<td data-col-name="dari"><?php echo $perjalananDinas->getDari();?></td>
								<td data-col-name="hingga"><?php echo $perjalananDinas->getHingga();?></td>
								<td data-col-name="aktif"><?php echo $perjalananDinas->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

