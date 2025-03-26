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
use MagicApp\XLSX\DocumentWriter;
use MagicApp\XLSX\XLSXDataFormat;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\AcuanPengawasan;
use Sipro\Entity\Data\AcuanPengawasanBillOfQuantity;
use Sipro\Entity\Data\BillOfQuantityHistory;
use Sipro\Entity\Data\ProyekMin;
use Sipro\Entity\Data\BillOfQuantityMin;
use Sipro\Util\BoqUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "bill-of-quantity", $appLanguage->getBillOfQuantity());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$level = 1;
	try
	{
		$billOfQuantityParent = new BillOfQuantity(null, $database);
		$billOfQuantityParent->find($inputPost->getParentId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$level = $billOfQuantityParent->getLevel() + 1;
	}
	catch(Exception $e)
	{
		// do nothing
	}
	$billOfQuantity = new BillOfQuantity(null, $database);
	$billOfQuantity->setLevel($level);
	$billOfQuantity->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setParentId($inputPost->getParentId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$billOfQuantity->setSatuan($inputPost->getSatuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$volume = $inputPost->getVolume(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$billOfQuantity->setVolume($volume);
	$bobot = $inputPost->getBobot(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	if($bobot <= 0 && $volume > 0)
	{
		$bobot = 1;
	}
	$billOfQuantity->setBobot($bobot);
	$billOfQuantity->setHarga($inputPost->getHarga(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantity->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$billOfQuantity->setAdminBuat($currentUser->getUserId());
	$billOfQuantity->setWaktuBuat($currentAction->getTime());
	$billOfQuantity->setIpBuat($currentAction->getIp());
	$billOfQuantity->setAdminUbah($currentUser->getUserId());
	$billOfQuantity->setWaktuUbah($currentAction->getTime());
	$billOfQuantity->setIpUbah($currentAction->getIp());
	$billOfQuantity->insert();
	$newId = $billOfQuantity->getBillOfQuantityId();

	// Save acuan pengawasan
	if($inputPost->countableAcuanPengawasanId())
	{
		$acuanPengawasanIds = $inputPost->getAcuanPengawasanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false);
		if(!empty($acuanPengawasanIds))
		{
			foreach($acuanPengawasanIds as $acuanPengawasanId)
			{
				$acuanPengawasanBoq = new AcuanPengawasanBillOfQuantity(null, $database);
				$acuanPengawasanBoq->setBillOfQuantityId($newId);
				$acuanPengawasanBoq->setAcuanPengawasanId($acuanPengawasanId);
				$acuanPengawasanBoq->insert();
			}
		}
	}

	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->bill_of_quantity_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$billOfQuantity = new BillOfQuantity(null, $database);
	try
	{
		$billOfQuantityCopy = new BillOfQuantity(null, $database);
		$billOfQuantityCopy->find($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$billOfQuantityHistory = new BillOfQuantityHistory($billOfQuantityCopy, $database);
		$billOfQuantityHistory->insert();
	}
	catch(Exception $e)
	{
		// do nothing
	}
	$level = 1;
	try
	{
		$billOfQuantityParent = new BillOfQuantity(null, $database);
		$billOfQuantityParent->find($inputPost->getParentId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
		$level = $billOfQuantityParent->getLevel() + 1;
	}
	catch(Exception $e)
	{
		// do nothing
	}
	$billOfQuantity->setProyekId($inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setLevel($level);
	$billOfQuantity->setParentId($inputPost->getParentId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$billOfQuantity->setSatuan($inputPost->getSatuan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$volume = $inputPost->getVolume(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	$billOfQuantity->setVolume($volume);
	$bobot = $inputPost->getBobot(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true);
	if($bobot <= 0 && $volume > 0)
	{
		$bobot = 1;
	}
	$billOfQuantity->setBobot($bobot);
	$billOfQuantity->setHarga($inputPost->getHarga(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$billOfQuantity->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$billOfQuantity->setAdminUbah($currentUser->getUserId());
	$billOfQuantity->setWaktuUbah($currentAction->getTime());
	$billOfQuantity->setIpUbah($currentAction->getIp());
	$billOfQuantity->setBillOfQuantityId($inputPost->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$billOfQuantity->update();
	$newId = $billOfQuantity->getBillOfQuantityId();

	// Save acuan pengawasan
	$acuanPengawasanIds = array();
	if($inputPost->countableAcuanPengawasanId())
	{
		$acuanPengawasanIds = $inputPost->getAcuanPengawasanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false);
		if(!empty($acuanPengawasanIds))
		{
			foreach($acuanPengawasanIds as $acuanPengawasanId)
			{
				$acuanPengawasanBoq = new AcuanPengawasanBillOfQuantity(null, $database);
				try
				{
					$acuanPengawasanBoq->findOneByBillOfQuantityIdAndAcuanPengawasanId($newId, $acuanPengawasanId);
				}
				catch(Exception $e)
				{
					// Insert if not exists
					try
					{
						$acuanPengawasanBoq->setBillOfQuantityId($newId);
						$acuanPengawasanBoq->setAcuanPengawasanId($acuanPengawasanId);
						$acuanPengawasanBoq->insert();
					}
					catch(Exception $e)
					{
						// Do nothing
					}
				}
			}
		}
	}
	// Clean up
	if(!empty($acuanPengawasanIds))
	{
		// DELETE FROM acuan_pengawasan_bill_of_quantity WHERE bill_of_quantity_id = $newId AND acuan_pengawasan_id NOT IN ($acuanPengawasanIds)
		$acuanPengawasanBoq = new AcuanPengawasanBillOfQuantity(null, $database);
		try
		{
			$acuanPengawasanBoq->where(
				PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityId, $newId))
					->addAnd(PicoPredicate::getInstance()->notIn(Field::of()->acuanPengawasanId, $acuanPengawasanIds))
			)
			->delete();
		}
		catch(Exception $e)
		{
			// Do nothing
		}
	}
	else
	{
		// DELETE FROM acuan_pengawasan_bill_of_quantity WHERE bill_of_quantity_id = $newId
		$acuanPengawasanBoq = new AcuanPengawasanBillOfQuantity(null, $database);
		try
		{
			$acuanPengawasanBoq->where(
				PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityId, $newId))
			)
			->delete();
		}
		catch(Exception $e)
		{
			// Do nothing
		}
	}

	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->bill_of_quantity_id, $newId);
}
else if($inputPost->getUserAction() == UserAction::ACTIVATE)
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$billOfQuantity = new BillOfQuantity(null, $database);
			try
			{
				$billOfQuantity->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, true))
				)
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
			$billOfQuantity = new BillOfQuantity(null, $database);
			try
			{
				$billOfQuantity->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->billOfQuantityId, $rowId))
					->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->aktif, false))
				)
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
				$billOfQuantityCopy = new BillOfQuantity(null, $database);
				$billOfQuantityCopy->find($rowId);
				$billOfQuantityHistory = new BillOfQuantityHistory($billOfQuantityCopy, $database);
				$billOfQuantityHistory->insert();
				$billOfQuantityCopy->delete();
			}
			catch(Exception $e)
			{
				// do nothing
			}
		}
	}
	$currentModule->redirectToItself();
}
else if($inputPost->getUserAction() == UserAction::SORT_ORDER)
{
	$billOfQuantity = new BillOfQuantity(null, $database);
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
			$billOfQuantity->where(PicoSpecification::getInstance()->addAnd(new PicoPredicate(Field::of()->billOfQuantityId, $primaryKeyValue)))->setSortOrder($sortOrder)->update();
		}
	}
	$currentModule->redirectToItself();
}
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<script>
	jQuery(function(){
		$('[name="proyek_id"]').on('change', function(){
			let urlParams = new URLSearchParams(window.location.search);
			urlParams.set('proyek_id', $(this).val());
			window.location = window.location.pathname + '?' + urlParams.toString();
		});
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
							<select class="form-control" name="proyek_id" id="proyek_id" required>
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
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="nama" id="nama" required/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getParent();?></td>
						<td>
							<select class="form-control" name="parent_id" id="parent_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php
								$boq = new BillOfQuantity(null, $database); 
								try
								{
									$specs = PicoSpecification::getInstance()
										->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $inputGet->getProyekId()))
									;
									$pageData = $boq->findAll($specs);
									$boqUtil = new BoqUtil($pageData->getResult($inputGet->getParentId()), 0, false);
									echo $boqUtil->selectOption($inputGet->getParentId());
								}
								catch(Exception $e)
								{
									// do nothing
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td>
							<select class="form-control" name="acuan_pengawasan_id[]" id="acuan_pengawasan_id" multiple data-multiple-select
								data-placeholder="<?php echo $appLanguage->getLabelSelectItems();?>" data-search-placeholder="<?php echo $appLanguage->getLabelSearch();?>"
                                data-label-selected="<?php echo $appLanguage->getLabelSelected();?>" data-label-select-all="<?php echo $appLanguage->getLabelSelectAll();?>"
								>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AcuanPengawasan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->acuanPengawasanId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSatuan();?></td>
						<td>
							<input autocomplete="off" type="text" class="form-control" name="satuan" id="satuan"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="volume" id="volume"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBobot();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="bobot" id="bobot"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHarga();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="any" name="harga" id="harga"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input autocomplete="off" class="form-control" type="number" step="1" name="sort_order" id="sort_order"/>
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
							<button type="button" class="btn btn-primary" onclick="window.history.back()"><?php echo $appLanguage->getButtonCancel();?></button>
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
	$billOfQuantity = new BillOfQuantity(null, $database);
	try{
		$billOfQuantity->findOneByBillOfQuantityId($inputGet->getBillOfQuantityId());
		if($billOfQuantity->issetBillOfQuantityId())
		{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<script>
	jQuery(function(){
		$('[name="proyek_id"]').on('change', function(){
			let urlParams = new URLSearchParams(window.location.search);
			urlParams.set('proyek_id', $(this).val());
			window.location = window.location.pathname + '?' + urlParams.toString();
		});
	})
</script>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td>
							<select class="form-control" name="proyek_id" id="proyek_id" required>
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $billOfQuantity->getProyekId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $billOfQuantity->getNama();?>" autocomplete="off" required/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getParent();?></td>
						<td>
							<select class="form-control" name="parent_id" id="parent_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php
								$boq = new BillOfQuantity(null, $database); 
								try
								{
									$specs = PicoSpecification::getInstance()
										->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $billOfQuantity->getProyekId()))
									;
									$pageData = $boq->findAll($specs);
									$boqUtil = new BoqUtil($pageData->getResult($billOfQuantity->getParentId()), 0, false);
									echo $boqUtil->selectOption($billOfQuantity->getParentId());
								}
								catch(Exception $e)
								{
									// do nothing
								}
								?>
							</select>
						</td>
					</tr>
					<?php
					$acuanPengawasanBillOfQuantity = new AcuanPengawasanBillOfQuantity(null, $database);
					$acuanPengawasanIds = null;
					try
					{
						$pageData = $acuanPengawasanBillOfQuantity->findByBillOfQuantityId($billOfQuantity->getBillOfQuantityId());
						$acuanPengawasanIds = array();
						foreach($pageData->getResult() as $row)
						{
							$acuanPengawasanIds[] = $row->getAcuanPengawasanId();
						}
					}
					catch(Exception $e)
					{
						// Do nothing
					}	
					?>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td>
							<select class="form-control" name="acuan_pengawasan_id[]" id="acuan_pengawasan_id" multiple data-multiple-select
								data-placeholder="<?php echo $appLanguage->getLabelSelectItems();?>" data-search-placeholder="<?php echo $appLanguage->getLabelSearch();?>"
                                data-label-selected="<?php echo $appLanguage->getLabelSelected();?>" data-label-select-all="<?php echo $appLanguage->getLabelSelectAll();?>"
								>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AcuanPengawasan(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $billOfQuantity->getProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->acuanPengawasanId, Field::of()->nama, $acuanPengawasanIds)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSatuan();?></td>
						<td>
							<input type="text" class="form-control" name="satuan" id="satuan" value="<?php echo $billOfQuantity->getSatuan();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="volume" id="volume" value="<?php echo $billOfQuantity->getVolume();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBobot();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="bobot" id="bobot" value="<?php echo $billOfQuantity->getBobot();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHarga();?></td>
						<td>
							<input class="form-control" type="number" step="any" name="harga" id="harga" value="<?php echo $billOfQuantity->getHarga();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input class="form-control" type="number" step="1" name="sort_order" id="sort_order" value="<?php echo $billOfQuantity->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $billOfQuantity->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(null, Field::of()->proyek_id, $billOfQuantity->getProyekId());?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="bill_of_quantity_id" value="<?php echo $billOfQuantity->getBillOfQuantityId();?>"/>
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
	$billOfQuantity = new BillOfQuantity(null, $database);
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
		"parentId" => array(
			"columnName" => "parent_id",
			"entityName" => "BillOfQuantityMin",
			"tableName" => "bill_of_quantity",
			"primaryKey" => "bill_of_quantity_id",
			"objectName" => "parentBoq",
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
		$billOfQuantity->findOneWithPrimaryKeyValue($inputGet->getBillOfQuantityId(), $subqueryMap);
		if($billOfQuantity->issetBillOfQuantityId())
		{
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($billOfQuantity->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $billOfQuantity->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $billOfQuantity->issetProyek() ? $billOfQuantity->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $billOfQuantity->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLevel();?></td>
						<td><?php echo $billOfQuantity->getLevel();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getParent();?></td>
						<td><?php echo $billOfQuantity->issetParentBoq() ? $billOfQuantity->getParentBoq()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAcuanPengawasan();?></td>
						<td><?php
						$acuanPengawasanBillOfQuantity = new AcuanPengawasanBillOfQuantity(null, $database);
						$acuanPengawasanIds = null;
						try
						{
							$pageData = $acuanPengawasanBillOfQuantity->findByBillOfQuantityId($billOfQuantity->getBillOfQuantityId());
							$acuanPengawasanIds = array();
							if($pageData->getTotalResult() > 0)
							{
								?>
								<ol>
								<?php
								foreach($pageData->getResult() as $row)
								{
									?><li><?php echo $row->issetAcuanPengawasan() ? $row->getAcuanPengawasan()->getNama() : "";?></li><?php
								}
								?>
								</ol>
								<?php
							}
						}
						catch(Exception $e)
						{
							// Do nothing
						}	
					?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSatuan();?></td>
						<td><?php echo $billOfQuantity->getSatuan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getVolume();?></td>
						<td><?php echo $billOfQuantity->getVolume();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getBobot();?></td>
						<td><?php echo $billOfQuantity->getBobot();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getHarga();?></td>
						<td><?php echo $billOfQuantity->getHarga();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $billOfQuantity->getSortOrder();?></td>
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
						<td><?php echo $billOfQuantity->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $billOfQuantity->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $billOfQuantity->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $billOfQuantity->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $billOfQuantity->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->bill_of_quantity_id, $billOfQuantity->getBillOfQuantityId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(null, Field::of()->proyek_id, $billOfQuantity->getProyekId());?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="bill_of_quantity_id" value="<?php echo $billOfQuantity->getBillOfQuantityId();?>"/>
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
$appEntityLanguage = new AppEntityLanguage(new BillOfQuantity(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"level" => PicoSpecification::filter("level", "number"),
	"parentId" => PicoSpecification::filter("parentId", "number")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"parentId" => "parentId",
	"level" => "level",
	"nama" => "nama",
	"satuan" => "satuan",
	"volume" => "volume",
	"persen" => "persen",
	"bobot" => "bobot",
	"harga" => "harga",
	"sortOrder" => "sortOrder",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "level", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	),
	array(
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new BillOfQuantity(null, $database);

$subqueryMap = array(
"proyekId" => array(
	"columnName" => "proyek_id",
	"entityName" => "ProyekMin",
	"tableName" => "proyek",
	"primaryKey" => "proyek_id",
	"objectName" => "proyek",
	"propertyName" => "nama"
), 
"parentId" => array(
	"columnName" => "parent_id",
	"entityName" => "BillOfQuantityMin",
	"tableName" => "bill_of_quantity",
	"primaryKey" => "bill_of_quantity_id",
	"objectName" => "parentBoq",
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
		$appEntityLanguage->getBillOfQuantityId() => $headerFormat->getBillOfQuantityId(),
		$appEntityLanguage->getProyek() => $headerFormat->getProyek(),
		$appEntityLanguage->getParent() => $headerFormat->getParent(),
		$appEntityLanguage->getLevel() => $headerFormat->getLevel(),
		$appEntityLanguage->getNama() => $headerFormat->getNama(),
		$appEntityLanguage->getSatuan() => $headerFormat->getSatuan(),
		$appEntityLanguage->getVolume() => $headerFormat->getVolume(),
		$appEntityLanguage->getVolumeProyek() => $headerFormat->getVolumeProyek(),
		$appEntityLanguage->getPersen() => $headerFormat->getPersen(),
		$appEntityLanguage->getBobot() => $headerFormat->getBobot(),
		$appEntityLanguage->getHarga() => $headerFormat->getHarga(),
		$appEntityLanguage->getSortOrder() => $headerFormat->getSortOrder(),
		$appEntityLanguage->getAdminBuat() => $headerFormat->getAdminBuat(),
		$appEntityLanguage->getAdminUbah() => $headerFormat->getAdminUbah(),
		$appEntityLanguage->getWaktuBuat() => $headerFormat->getWaktuBuat(),
		$appEntityLanguage->getWaktuUbah() => $headerFormat->getWaktuUbah(),
		$appEntityLanguage->getIpBuat() => $headerFormat->getIpBuat(),
		$appEntityLanguage->getIpUbah() => $headerFormat->getIpUbah(),
		$appEntityLanguage->getAktif() => $headerFormat->asString()
	), 
	function($index, $row) use ($appLanguage) {
        
		return array(
			sprintf("%d", $index + 1),
			$row->getBillOfQuantityId(),
			$row->issetProyek() ? $row->getProyek()->getNama() : "",
			$row->issetParentBoq() ? $row->getParentBoq()->getNama() : "",
			$row->getLevel(),
			$row->getNama(),
			$row->getSatuan(),
			$row->getVolume(),
			$row->getVolumeProyek(),
			$row->getPersen(),
			$row->getBobot(),
			$row->getHarga(),
			$row->getSortOrder(),
			$row->getAdminBuat(),
			$row->getAdminUbah(),
			$row->getWaktuBuat(),
			$row->getWaktuUbah(),
			$row->getIpBuat(),
			$row->getIpUbah(),
			$row->optionAktif($appLanguage->getYes(), $appLanguage->getNo())
		);
	});
	exit();
}

if($inputGet->getProyekId() == '' || $inputGet->getLevel() == '' || $inputGet->getParentId() == '')
{
	$userPermission->setAllowedSortOrderFalse();
}

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<script>
	jQuery(function(){
		$('[name="proyek_id"]').on('change', function(){
			$(this).closest('form').find('[name="level"]').val('');
			$(this).closest('form').find('[name="parent_id"]').val('');
			$(this).closest('form').submit();
		});
		$('[name="level"]').on('change', function(){
			$(this).closest('form').find('[name="parent_id"]').val('');
			$(this).closest('form').submit();
		});
		$('[name="parent_id"]').on('change', function(){
			let level = $(this).find('option:selected').attr('data-level') || '';
			if(level != '')
			{
				$(this).closest('form').find('[name="level"]').val(parseInt(level) + 1);
			}
			else
			{
				$(this).closest('form').find('[name="level"]').val('');
			}
			$(this).closest('form').submit();
		});
	});
</script>
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
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->proyekId, PicoSort::ORDER_TYPE_DESC)), 
								Field::of()->proyekId, Field::of()->nama, $inputGet->getProyekId())
								; ?>
							</select>
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getLevel();?></span>
					<span class="filter-control">
							<select name="level" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionAllLevel();?></option>
								<?php
								for($lvl = 1; $lvl <= 10; $lvl++)
								{
									?>
									<option value="<?php echo $lvl;?>"<?php echo $lvl == $inputGet->getLevel() ? ' selected' : '';?>><?php echo $lvl;?></option>
									<?php
								}
								?>
							</select>
					</span>
				</span>
				<?php
				$parentSpecs = PicoSpecification::getInstance()
				->addAnd(new PicoPredicate(Field::of()->aktif, true))
				->addAnd(new PicoPredicate(Field::of()->draft, false))
				->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId()));

				$boqLevel = $inputGet->getLevel();
				if($boqLevel > 1)
				{
					$parentSpecs->addAnd(new PicoPredicate(Field::of()->level, $boqLevel - 1));
				}
				?>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getParent();?></span>
					<span class="filter-control">
							<select name="parent_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantityMin(null, $database), 
								$parentSpecs,
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->parentId, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->billOfQuantityId, Field::of()->nama, $inputGet->getParentId(), array('level'))
								->setTextNodeFormat('"(%d) %s", level, nama')
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
								<td class="data-controll data-selector" data-key="bill_of_quantity_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-bill-of-quantity-id"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td class="data-controll data-editor">
									<span class="fa fa-edit"></span>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedCreate()){ ?>
								<td class="data-controll data-editor">
									<span class="fa fa-plus"></span>
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
								<td data-col-name="level" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLevel();?></a></td>
								<td data-col-name="parent_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getParent();?></a></td>
								<td data-col-name="satuan" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSatuan();?></a></td>
								<td data-col-name="volume" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getVolume();?></a></td>
								<td data-col-name="persen" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPersen();?></a></td>
								<td data-col-name="bobot" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBobot();?></a></td>
								<td data-col-name="harga" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getHarga();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($billOfQuantity = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $billOfQuantity->getBillOfQuantityId();?>" data-sort-order="<?php echo $billOfQuantity->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-body data-sort-handler"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="bill_of_quantity_id">
									<input type="checkbox" class="checkbox check-slave checkbox-bill-of-quantity-id" name="checked_row_id[]" value="<?php echo $billOfQuantity->getBillOfQuantityId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->bill_of_quantity_id, $billOfQuantity->getBillOfQuantityId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedCreate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::CREATE, Field::of()->parent_id, $billOfQuantity->getBillOfQuantityId(), array(Field::of()->proyek_id => $billOfQuantity->getProyekId()));?>"><span class="fa fa-plus"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->bill_of_quantity_id, $billOfQuantity->getBillOfQuantityId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $billOfQuantity->issetProyek() ? $billOfQuantity->getProyek()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $billOfQuantity->getNama();?></td>
								<td data-col-name="level"><?php echo $billOfQuantity->getLevel();?></td>
								<td data-col-name="parent_id"><?php echo $billOfQuantity->issetParentBoq() ? $billOfQuantity->getParentBoq()->getNama() : "";?></td>
								<td data-col-name="satuan"><?php echo $billOfQuantity->getSatuan();?></td>
								<td data-col-name="volume"><?php echo $billOfQuantity->getVolume();?></td>
								<td data-col-name="persen"><span class="pie" style="--b:1px; --w:1.1rem; --p:<?php echo $billOfQuantity->getPersen();?>; --c:darkblue;"><?php echo round($billOfQuantity->getPersen());?></span></td>
								<td data-col-name="bobot"><?php echo $billOfQuantity->getBobot();?></td>
								<td data-col-name="harga"><?php echo $billOfQuantity->getHarga();?></td>
								<td data-col-name="sort_order" class="data-sort-order-column"><?php echo $billOfQuantity->getSortOrder();?></td>
								<td data-col-name="aktif"><?php echo $billOfQuantity->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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

