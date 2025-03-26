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
use MagicApp\UserAction;
use MagicApp\AppUserPermission;
use Sipro\Entity\Data\KurvaS;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\ProyekMin;
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "kurva-s", $appLanguage->getKurvaS());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$kurvaS = new KurvaS(null, $database);
	$kurvaS->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kurvaS->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setTanggalMulai($inputPost->getTanggalMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setTanggalSelesai($inputPost->getTanggalSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setNilai($inputPost->getNilai(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
	$kurvaS->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kurvaS->setDefaultData($inputPost->getDefaultData(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kurvaS->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kurvaS->setAdminBuat($currentAction->getUserId());
	$kurvaS->setWaktuBuat($currentAction->getTime());
	$kurvaS->setIpBuat($currentAction->getIp());
	$kurvaS->setAdminUbah($currentAction->getUserId());
	$kurvaS->setWaktuUbah($currentAction->getTime());
	$kurvaS->setIpUbah($currentAction->getIp());
	try
	{
		$kurvaS->insert();
		$newId = $kurvaS->getKurvaSId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kurva_s_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$kurvaS = new KurvaS(null, $database);
	$kurvaS->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kurvaS->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setTanggalMulai($inputPost->getTanggalMulai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setTanggalSelesai($inputPost->getTanggalSelesai(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kurvaS->setNilai($inputPost->getNilai(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
	$kurvaS->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kurvaS->setDefaultData($inputPost->getDefaultData(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kurvaS->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kurvaS->setAdminUbah($currentAction->getUserId());
	$kurvaS->setWaktuUbah($currentAction->getTime());
	$kurvaS->setIpUbah($currentAction->getIp());
	$kurvaS->setKurvaSId($inputPost->getKurvaSId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	try
	{
		$kurvaS->update();
		$newId = $kurvaS->getKurvaSId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kurva_s_id, $newId);
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
			$kurvaS = new KurvaS(null, $database);
			try
			{
				$kurvaS->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kurvaSId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
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
			$kurvaS = new KurvaS(null, $database);
			try
			{
				$kurvaS->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kurvaSId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
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
			try
			{
				$kurvaS = new KurvaS(null, $database);
				$kurvaS->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->kurva_s_id, $rowId))
				)
				->delete();
			}
			catch(Exception $e)
			{
				// Do something here to handle exception
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::SORT_ORDER)
{
	$kurvaS = new KurvaS(null, $database);
	if($inputPost->getNewOrder() != null && $inputPost->countableNewOrder())
	{
		foreach($inputPost->getNewOrder() as $dataItem)
		{
			if(is_string($dataItem))
			{
				$dataItem = new SetterGetter(json_decode($dataItem));
			}
			$primaryKeyValue = $dataItem->getPrimaryKey();
			$sortOrder = $dataItem->getSortOrder();
			$kurvaS->where(PicoSpecification::getInstance()
				->addAnd(new PicoPredicate(Field::of()->kurvaSId, $primaryKeyValue))
			)
			->setSortOrder($sortOrder)
			->update();
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new KurvaS(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>

	<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/css/coreui-chartjs.css">
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/chart.js/js/chart.umd.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chart.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chartjs-adapter-date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/kurva-s.min.js"></script>

	<script>	
		document.addEventListener('DOMContentLoaded', function() {
			initChart('#kurvaCanvas', '#tanggal_mulai', '#tanggal_selesai', true, function(lbl, dt){
				$('#nilai').val(JSON.stringify({labels:lbl, data:dt}));
			});
			createChart();
		});
	</script>

    <style>
        canvas {
            border: 1px solid #999999;
			width: 100%;
			height: 400px;
        }
    </style>
	
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
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
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama" id="nama" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="date" name="tanggal_selesai" id="tanggal_selesai" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNilai();?></td>
						<td>
							<div style="position: relative;">
								<canvas id="kurvaCanvas" width="100%" height="400"></canvas>
							</div>
							<div style="padding: 10px 0;"></div>
								<input type="hidden" name="nilai" id="nilai" value="{}">
								<button type="button" class="btn btn-tn btn-primary" onclick="moveUp()"><?php echo $appLanguage->getMoveUp();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveDown()"><?php echo $appLanguage->getMoveDown();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveLeft()"><?php echo $appLanguage->getMoveLeft();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveRight()"><?php echo $appLanguage->getMoveRight();?></button>
							</div>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="sort_order" id="sort_order"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDefaultData();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="default_data" id="default_data" value="1"/> <?php echo $appEntityLanguage->getDefaultData();?></label>
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
	$kurvaS = new KurvaS(null, $database);
	try{
		$kurvaS->findOneByKurvaSId($inputGet->getKurvaSId());
		if($kurvaS->issetKurvaSId())
		{
$appEntityLanguage = new AppEntityLanguage(new KurvaS(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>

	<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/css/coreui-chartjs.css">
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/chart.js/js/chart.umd.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chart.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chartjs-adapter-date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/kurva-s.min.js"></script>
	<script>
		let nilai = <?php echo $kurvaS->getNilai();?>;
		if(typeof nilai.labels != 'undefined')
		{
			labels = nilai.labels;
		}
		if(typeof nilai.data != 'undefined')
		{
			data = nilai.data;
		}
		document.addEventListener('DOMContentLoaded', function() {
			initChart('#kurvaCanvas', '#tanggal_mulai', '#tanggal_selesai', true, function(lbl, dt){
				$('#nilai').val(JSON.stringify({labels:lbl, data:dt}));
			});
			createChart();
			$('#nilai').val(JSON.stringify(nilai));
		});
	</script>

<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
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
								Field::of()->proyekId, Field::of()->nama, $kurvaS->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $kurvaS->getNama();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td>
							<input class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $kurvaS->getTanggalMulai();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td>
							<input class="form-control" type="date" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo $kurvaS->getTanggalSelesai();?>" autocomplete="off" required="required"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNilai();?></td>
						<td>
							<div style="position: relative;">
								<canvas id="kurvaCanvas" width="100%" height="400"></canvas>
							</div>
							<div style="padding: 10px 0;"></div>
								<input type="hidden" name="nilai" id="nilai" value="{}">
								<button type="button" class="btn btn-tn btn-primary" onclick="moveUp()"><?php echo $appLanguage->getMoveUp();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveDown()"><?php echo $appLanguage->getMoveDown();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveLeft()"><?php echo $appLanguage->getMoveLeft();?></button>
								<button type="button" class="btn btn-tn btn-primary" onclick="moveRight()"><?php echo $appLanguage->getMoveRight();?></button>								
							</div>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="sort_order" id="sort_order" value="<?php echo $kurvaS->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDefaultData();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="default_data" id="default_data" value="1" <?php echo $kurvaS->createCheckedDefaultData();?>/> <?php echo $appEntityLanguage->getDefaultData();?></label>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $kurvaS->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="kurva_s_id" value="<?php echo $kurvaS->getKurvaSId();?>"/>
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
	$kurvaS = new KurvaS(null, $database);
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
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
			"propertyName" => "nama_depan"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "UserMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama_depan"
		)
		);
		$kurvaS->findOneWithPrimaryKeyValue($inputGet->getKurvaSId(), $subqueryMap);
		if($kurvaS->issetKurvaSId())
		{
$appEntityLanguage = new AppEntityLanguage(new KurvaS(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>

	<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/css/coreui-chartjs.css">
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/chart.js/js/chart.umd.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chart.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/chartjs-adapter-date-fns.js"></script>
    <script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js"></script>
	<script src="<?php echo $baseAssetsUrl;?>lib.assets/chart/kurva-s.min.js"></script>


	<input type="hidden" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $kurvaS->getTanggalMulai();?>"/>
	<input type="hidden" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo $kurvaS->getTanggalSelesai();?>"/>


	<script>
		let nilai = <?php echo $kurvaS->getNilai();?>;
		if(typeof nilai.labels != 'undefined')
		{
			labels = nilai.labels;
		}
		if(typeof nilai.data != 'undefined')
		{
			data = nilai.data;
		}
		document.addEventListener('DOMContentLoaded', function() {
			initChart('#kurvaCanvas', '#tanggal_mulai', '#tanggal_selesai', false, function(lbl, dt){
				$('#nilai').val(JSON.stringify({labels:lbl, data:dt}));
			});			
			createChart();
		});

	</script>

<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($kurvaS->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $kurvaS->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $kurvaS->issetProyek() ? $kurvaS->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $kurvaS->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalMulai();?></td>
						<td><?php echo $kurvaS->getTanggalMulai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggalSelesai();?></td>
						<td><?php echo $kurvaS->getTanggalSelesai();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNilai();?></td>
						<td>
							<div style="position: relative;">
								<canvas id="kurvaCanvas" width="100%" height="400"></canvas>
							</div>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $kurvaS->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getDefaultData();?></td>
						<td><?php echo $kurvaS->optionDefaultData($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $kurvaS->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $kurvaS->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $kurvaS->issetPembuat() ? $kurvaS->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $kurvaS->issetPengubah() ? $kurvaS->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $kurvaS->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $kurvaS->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $kurvaS->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->kurva_s_id, $kurvaS->getKurvaSId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="kurva_s_id" value="<?php echo $kurvaS->getKurvaSId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new KurvaS(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"nama" => PicoSpecification::filter("nama", "fulltext")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"nama" => "nama",
	"tanggalMulai" => "tanggalMulai",
	"tanggalSelesai" => "tanggalSelesai",
	"nilai" => "nilai",
	"sortOrder" => "sortOrder",
	"defaultData" => "defaultData",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


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

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new KurvaS(null, $database);

$subqueryMap = array(
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"adminBuat" => array(
	"columnName" => "admin_buat",
	"entityName" => "UserMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "pembuat",
	"propertyName" => "nama_depan"
), 
"adminUbah" => array(
	"columnName" => "admin_ubah",
	"entityName" => "UserMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "pengubah",
	"propertyName" => "nama_depan"
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
		$appEntityLanguage->getKurvaSId() => $headerFormat->getKurvaSId(),
		$appEntityLanguage->getProyek() => $headerFormat->asString(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getTanggalMulai() => $headerFormat->getTanggalMulai(),
		$appEntityLanguage->getTanggalSelesai() => $headerFormat->getTanggalSelesai(),
		$appEntityLanguage->getNilai() => $headerFormat->asString(),
		$appEntityLanguage->getSortOrder() => $headerFormat->getSortOrder(),
		$appEntityLanguage->getDefaultData() => $headerFormat->asString(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->asString(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->asString(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
		
		return array(
			sprintf("%d", $index + 1),
			$row->getKurvaSId(),
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->getNama(),
			$row->getTanggalMulai(),
			$row->getTanggalSelesai(),
			$row->getNilai(),
			$row->getSortOrder(),
			$row->optionDefaultData($appLanguage->getYes(), $appLanguage->getNo()),
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->issetPembuat() ? $row->getPembuat()->getNama() : "",
			$row->issetPengubah() ? $row->getPengubah()->getNama() : "",
			$row->getIpBuat(),
			$row->getIpUbah(),
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
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
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
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-header"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="kurva_s_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-kurva-s-id"/>
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
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="tanggal_mulai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalMulai();?></a></td>
								<td data-col-name="tanggal_selesai" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggalSelesai();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="default_data" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getDefaultData();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($kurvaS = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $kurvaS->getKurvaSId();?>" data-sort-order="<?php echo $kurvaS->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-body data-sort-handler"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="kurva_s_id">
									<input type="checkbox" class="checkbox check-slave checkbox-kurva-s-id" name="checked_row_id[]" value="<?php echo $kurvaS->getKurvaSId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->kurva_s_id, $kurvaS->getKurvaSId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->kurva_s_id, $kurvaS->getKurvaSId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $kurvaS->issetProyek() ? $kurvaS->getProyek()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $kurvaS->getNama();?></td>
								<td data-col-name="tanggal_mulai"><?php echo $kurvaS->getTanggalMulai();?></td>
								<td data-col-name="tanggal_selesai"><?php echo $kurvaS->getTanggalSelesai();?></td>
								<td data-col-name="sort_order" class="data-sort-order-column"><?php echo $kurvaS->getSortOrder();?></td>
								<td data-col-name="default_data"><?php echo $kurvaS->optionDefaultData($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="aktif"><?php echo $kurvaS->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
						<?php if($userPermission->isAllowedSortOrder()){ ?>
						<button type="submit" class="btn btn-primary" name="user_action" value="sort_order" disabled="disabled"><?php echo $appLanguage->getButtonSaveCurrentOrder();?></button>
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

