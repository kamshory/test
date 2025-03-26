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
use Sipro\Entity\Data\BillOfQuantityProyek;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\Proyek;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\BillOfQuantityMin;
use Sipro\Entity\Data\SupervisorMin;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "bill-of-quantity-proyek", $appLanguage->getBillOfQuantityProyek());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
	$billOfQuantityProyek->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setVolume($inputPost->getVolume(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setVolumeProyek($inputPost->getVolumeProyek(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setPersen($inputPost->getPersen(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setAdminBuat($currentAction->getUserId());
	$billOfQuantityProyek->setWaktuBuat($currentAction->getTime());
	$billOfQuantityProyek->setIpBuat($currentAction->getIp());
	$billOfQuantityProyek->setAdminUbah($currentAction->getUserId());
	$billOfQuantityProyek->setWaktuUbah($currentAction->getTime());
	$billOfQuantityProyek->setIpUbah($currentAction->getIp());
	$billOfQuantityProyek->insert();
	$newId = $billOfQuantityProyek->getBillOfQuantityProyekId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->bill_of_quantity_proyek_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
	$billOfQuantityProyek->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setBukuHarianId($inputPost->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->setVolume($inputPost->getVolume(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setVolumeProyek($inputPost->getVolumeProyek(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setPersen($inputPost->getPersen(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantityProyek->setAdminUbah($currentAction->getUserId());
	$billOfQuantityProyek->setWaktuUbah($currentAction->getTime());
	$billOfQuantityProyek->setIpUbah($currentAction->getIp());
	$billOfQuantityProyek->setBillOfQuantityProyekId($inputPost->getBillOfQuantityProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantityProyek->update();
	$newId = $billOfQuantityProyek->getBillOfQuantityProyekId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->bill_of_quantity_proyek_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
			try
			{
				$billOfQuantityProyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityProyekId, $rowId))
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
			$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
			try
			{
				$billOfQuantityProyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityProyekId, $rowId))
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
				$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
				$billOfQuantityProyek->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bill_of_quantity_proyek_id, $rowId))
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
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityProyek(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
$proyekId = $inputGet->getProyekId();
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" onchange="window.location='?user_action=create&proyek_id='+$('#proyek_id').val()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Proyek(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $proyekId)
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
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->tanggal, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuHarianId, Field::of()->nama)
								->setTextNodeFormat(function($data){
									return str_replace(
										['January', 'February', 'March', 'May', 'June', 'July', 'August', 'October', 'December'], 
										['Januari', 'Februari', 'Maret', 'Mei', 'Juni', 'Juli', 'Agustus', 'October', 'Desember'], 
										date("j F Y", strtotime($data->getTanggal()))
									) . ($data->issetSupervisor() ? " (".$data->getSupervisor()->getNama().")" : '');
								})
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
						<td>
							<select class="form-control" name="bill_of_quantity_id" id="bill_of_quantity_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantityMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $proyekId)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->billOfQuantityId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="volume" id="volume"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolumeProyek();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="volume_proyek" id="volume_proyek"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="persen" id="persen"/>
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
	$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
	try{
		$billOfQuantityProyek->findOneByBillOfQuantityProyekId($inputGet->getBillOfQuantityProyekId());
		if($billOfQuantityProyek->issetBillOfQuantityProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityProyek(), $appConfig, $currentUser->getLanguageId());
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
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Proyek(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $billOfQuantityProyek->getProyekId())
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
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $billOfQuantityProyek->getProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->tanggal, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->bukuHarianId, Field::of()->tanggal, $billOfQuantityProyek->getBukuHarianId())
								->setTextNodeFormat(function($data){
									return str_replace(
										['January', 'February', 'March', 'May', 'June', 'July', 'August', 'October', 'December'], 
										['Januari', 'Februari', 'Maret', 'Mei', 'Juni', 'Juli', 'Agustus', 'October', 'Desember'], 
										date("j F Y", strtotime($data->getTanggal()))
									) . ($data->issetSupervisor() ? " (".$data->getSupervisor()->getNama().")" : '');
								})
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
						<td>
							<select class="form-control" name="bill_of_quantity_id" id="bill_of_quantity_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantityMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->billOfQuantityId, Field::of()->nama, $billOfQuantityProyek->getBillOfQuantityId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="volume" id="volume" value="<?php echo $billOfQuantityProyek->getVolume();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolumeProyek();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="volume_proyek" id="volume_proyek" value="<?php echo $billOfQuantityProyek->getVolumeProyek();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="persen" id="persen" value="<?php echo $billOfQuantityProyek->getPersen();?>" autocomplete="off"/>
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
							<input type="hidden" name="bill_of_quantity_proyek_id" value="<?php echo $billOfQuantityProyek->getBillOfQuantityProyekId();?>"/>
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
	$billOfQuantityProyek = new BillOfQuantityProyek(null, $database);
	try{
		$subqueryMap = array(
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "Proyek",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"bukuHarianId" => array(
			"columnName" => "buku_harian_id",
			"entityName" => "BukuHarian",
			"tableName" => "buku_harian",
			"primaryKey" => "buku_harian_id",
			"objectName" => "buku_harian",
			"propertyName" => "tanggal"
		), 
		"billOfQuantityId" => array(
			"columnName" => "bill_of_quantity_id",
			"entityName" => "BillOfQuantityMin",
			"tableName" => "bill_of_quantity",
			"primaryKey" => "bill_of_quantity_id",
			"objectName" => "bill_of_quantity",
			"propertyName" => "nama"
		), 
		"supervisorBuat" => array(
			"columnName" => "supervisor_buat",
			"entityName" => "Supervisor",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "pembuat",
			"propertyName" => "nama"
		), 
		"supervisorUbah" => array(
			"columnName" => "supervisor_ubah",
			"entityName" => "Supervisor",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "pengubah",
			"propertyName" => "nama"
		)
		);
		$billOfQuantityProyek->findOneWithPrimaryKeyValue($inputGet->getBillOfQuantityProyekId(), $subqueryMap);
		if($billOfQuantityProyek->issetBillOfQuantityProyekId())
		{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityProyek(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($billOfQuantityProyek->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $billOfQuantityProyek->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $billOfQuantityProyek->issetProyek() ? $billOfQuantityProyek->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBukuHarian();?></td>
						<td><?php echo $billOfQuantityProyek->issetBukuHarian() ? $billOfQuantityProyek->getBukuHarian()->getTanggal() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBillOfQuantity();?></td>
						<td><?php echo $billOfQuantityProyek->issetBillOfQuantity() ? $billOfQuantityProyek->getBillOfQuantity()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td><?php echo $billOfQuantityProyek->getVolume();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolumeProyek();?></td>
						<td><?php echo $billOfQuantityProyek->getVolumeProyek();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPersen();?></td>
						<td><?php echo $billOfQuantityProyek->getPersen();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $billOfQuantityProyek->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $billOfQuantityProyek->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorBuat();?></td>
						<td><?php echo $billOfQuantityProyek->issetPembuat() ? $billOfQuantityProyek->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisorUbah();?></td>
						<td><?php echo $billOfQuantityProyek->issetPengubah() ? $billOfQuantityProyek->getPengubah()->getNama() : "";?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->bill_of_quantity_proyek_id, $billOfQuantityProyek->getBillOfQuantityProyekId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="bill_of_quantity_proyek_id" value="<?php echo $billOfQuantityProyek->getBillOfQuantityProyekId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantityProyek(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"billOfQuantityId" => PicoSpecification::filter("billOfQuantityId", "number"),
	"supervisorBuat" => PicoSpecification::filter("supervisorBuat", "number"),
	"supervisorUbah" => PicoSpecification::filter("supervisorUbah", "number")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"bukuHarianId" => "bukuHarianId",
	"billOfQuantityId" => "billOfQuantityId",
	"volume" => "volume",
	"volumeProyek" => "volumeProyek",
	"persen" => "persen",
	"supervisorBuat" => "supervisorBuat",
	"supervisorUbah" => "supervisorUbah"
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
$dataLoader = new BillOfQuantityProyek(null, $database);

$subqueryMap = array(
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "Proyek",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"bukuHarianId" => array(
	"columnName" => "buku_harian_id",
	"entityName" => "BukuHarian",
	"tableName" => "buku_harian",
	"primaryKey" => "buku_harian_id",
	"objectName" => "buku_harian",
	"propertyName" => "tanggal"
), 
"billOfQuantityId" => array(
	"columnName" => "bill_of_quantity_id",
	"entityName" => "BillOfQuantityMin",
	"tableName" => "bill_of_quantity",
	"primaryKey" => "bill_of_quantity_id",
	"objectName" => "bill_of_quantity",
	"propertyName" => "nama"
), 
"supervisorBuat" => array(
	"columnName" => "supervisor_buat",
	"entityName" => "Supervisor",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "pembuat",
	"propertyName" => "nama"
), 
"supervisorUbah" => array(
	"columnName" => "supervisor_ubah",
	"entityName" => "Supervisor",
	"tableName" => "supervisor",
	"primaryKey" => "supervisor_id",
	"objectName" => "pengubah",
	"propertyName" => "nama"
)
);

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
					<span class="filter-label"><?php echo $appEntityLanguage->getBillOfQuantity();?></span>
					<span class="filter-control">
							<select class="form-control" name="bill_of_quantity_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantityMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->billOfQuantityId, Field::of()->nama, $inputGet->getBillOfQuantityId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisorBuat();?></span>
					<span class="filter-control">
							<select class="form-control" name="supervisor_buat">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorBuat())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisorUbah();?></span>
					<span class="filter-control">
							<select class="form-control" name="supervisor_ubah">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new SupervisorMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->supervisorId, Field::of()->nama, $inputGet->getSupervisorUbah())
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
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="bill_of_quantity_proyek_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-bill-of-quantity-proyek-id"/>
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
								<td data-col-name="buku_harian_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBukuHarian();?></a></td>
								<td data-col-name="bill_of_quantity_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBillOfQuantity();?></a></td>
								<td data-col-name="volume" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getVolume();?></a></td>
								<td data-col-name="volume_proyek" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getVolumeProyek();?></a></td>
								<td data-col-name="persen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersen();?></a></td>
								<td data-col-name="supervisor_buat" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisorBuat();?></a></td>
								<td data-col-name="supervisor_ubah" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSupervisorUbah();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($billOfQuantityProyek = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="bill_of_quantity_proyek_id">
									<input type="checkbox" class="checkbox check-slave checkbox-bill-of-quantity-proyek-id" name="checked_row_id[]" value="<?php echo $billOfQuantityProyek->getBillOfQuantityProyekId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->bill_of_quantity_proyek_id, $billOfQuantityProyek->getBillOfQuantityProyekId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->bill_of_quantity_proyek_id, $billOfQuantityProyek->getBillOfQuantityProyekId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $billOfQuantityProyek->issetProyek() ? $billOfQuantityProyek->getProyek()->getNama() : "";?></td>
								<td data-col-name="buku_harian_id"><?php echo $billOfQuantityProyek->issetBukuHarian() ? $billOfQuantityProyek->getBukuHarian()->getTanggal() : "";?></td>
								<td data-col-name="bill_of_quantity_id"><?php echo $billOfQuantityProyek->issetBillOfQuantity() ? $billOfQuantityProyek->getBillOfQuantity()->getNama() : "";?></td>
								<td data-col-name="volume"><?php echo $billOfQuantityProyek->getVolume();?></td>
								<td data-col-name="volume_proyek"><?php echo $billOfQuantityProyek->getVolumeProyek();?></td>
								<td data-col-name="persen"><?php echo $billOfQuantityProyek->getPersen();?></td>
								<td data-col-name="supervisor_buat"><?php echo $billOfQuantityProyek->issetPembuat() ? $billOfQuantityProyek->getPembuat()->getNama() : "";?></td>
								<td data-col-name="supervisor_ubah"><?php echo $billOfQuantityProyek->issetPengubah() ? $billOfQuantityProyek->getPengubah()->getNama() : "";?></td>
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

