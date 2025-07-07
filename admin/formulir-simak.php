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
use Sipro\AppEntityLanguageImpl;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use Sipro\AppIncludeImpl;
use Sipro\AppUserPermissionImpl;
use Sipro\Entity\Data\FormulirSimak;
use Sipro\Entity\Data\JenisPemeriksaan;
use Sipro\Entity\Data\PemeriksaanMin;
use Sipro\Entity\Data\UmkMin;
use Sipro\Entity\Data\ProsedurMin;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "formulir-simak", $appLanguage->getFormulirSimak());
$userPermission = new AppUserPermissionImpl($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

function getLastOrder($database, $formulirSimakId, $pemeriksaanId)
{
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	$specs = PicoSpecification::getInstance()
	->addAnd(['formulirSimakId'], $formulirSimakId)
	->addAnd(['pemeriksaanId', $pemeriksaanId])
	;
	$sortable = PicoSortable::getInstance()->add('sortOrder', PicoSort::ORDER_TYPE_DESC);
	$page = new PicoPage(1, 1);
	$pageable = new PicoPageable($page, $sortable);
	try
	{
		$jenisPemeriksaan->findOne($specs, $pageable);
		return $jenisPemeriksaan->getSortOrder();
	}
	catch(Exception $e)
	{
		return 0;
	}
}

if($inputPost->getUserAction() == 'add-list' 
	&& $inputPost->getPemeriksaanId() != "" 
	&& $inputPost->getNama() != "" 
	&& $inputPost->getFormulirSimakId() != ""
	)
{
	$formulirSimakId = $inputPost->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
	$nama = $inputPost->getNama(PicoFilterConstant::FILTER_DEFAULT, false, false, true);
	$pemeriksaanId = $inputPost->getPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
	$labelYa = $inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$labelTidak = $inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	$sortOrder = getLastOrder($database, $formulirSimakId, $pemeriksaanId) + 1;
	
	$jenisPemeriksaan->setFormulirSimakId($formulirSimakId);
	$jenisPemeriksaan->setPemeriksaanId($pemeriksaanId);
	$jenisPemeriksaan->setNama($nama);
	$jenisPemeriksaan->setLabelYa($labelYa);
	$jenisPemeriksaan->setLabelTidak($labelTidak);
	$jenisPemeriksaan->setLabelYa($labelYa);
	$jenisPemeriksaan->setlabelTidak($labelTidak);
	$jenisPemeriksaan->setSortOrder($sortOrder);
	
	$jenisPemeriksaan->setAktif(true);
	$jenisPemeriksaan->setAdminBuat($currentAction->getUserId());
	$jenisPemeriksaan->setWaktuBuat($currentAction->getTime());
	$jenisPemeriksaan->setIpBuat($currentAction->getIp());
	$jenisPemeriksaan->setAdminUbah($currentAction->getUserId());
	$jenisPemeriksaan->setWaktuUbah($currentAction->getTime());
	$jenisPemeriksaan->setIpUbah($currentAction->getIp());
	try
	{
		$jenisPemeriksaan->insert();
		$newId = $jenisPemeriksaan->getJenisPemeriksaanId();
		header('Location: '.basename($_SERVER['PHP_SELF']).'?user_action=add-list&formulir_simak_id='.$formulirSimakId.'&pemeriksaan_id='.$pemeriksaanId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}

}

if($inputPost->getUserAction() == 'update-list' 
	&& $inputPost->getPemeriksaanId() != "" 
	&& $inputPost->getNama() != "" 
	&& $inputPost->getFormulirSimakId() != ""
	)
{
	$formulirSimakId = $inputPost->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
	$nama = $inputPost->getNama(PicoFilterConstant::FILTER_DEFAULT, false, false, true);
	$jenisPemeriksaanId = $inputPost->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
	$pemeriksaanId = $inputPost->getPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
	$labelYa = $inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	$labelTidak = $inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
	
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	$sortOrder = getLastOrder($database, $formulirSimakId, $pemeriksaanId) + 1;
	
	$jenisPemeriksaan->setJenisPemeriksaanId($jenisPemeriksaanId);
	$jenisPemeriksaan->setFormulirSimakId($formulirSimakId);
	$jenisPemeriksaan->setPemeriksaanId($pemeriksaanId);
	$jenisPemeriksaan->setNama($nama);
	$jenisPemeriksaan->setLabelYa($labelYa);
	$jenisPemeriksaan->setLabelTidak($labelTidak);
	$jenisPemeriksaan->setLabelYa($labelYa);
	$jenisPemeriksaan->setlabelTidak($labelTidak);
	$jenisPemeriksaan->setSortOrder($sortOrder);
	
	$jenisPemeriksaan->setAktif(true);
	$jenisPemeriksaan->setAdminBuat($currentAction->getUserId());
	$jenisPemeriksaan->setWaktuBuat($currentAction->getTime());
	$jenisPemeriksaan->setIpBuat($currentAction->getIp());
	$jenisPemeriksaan->setAdminUbah($currentAction->getUserId());
	$jenisPemeriksaan->setWaktuUbah($currentAction->getTime());
	$jenisPemeriksaan->setIpUbah($currentAction->getIp());
	try
	{
		$jenisPemeriksaan->update();
		$newId = $jenisPemeriksaan->getJenisPemeriksaanId();
		header('Location: '.basename($_SERVER['PHP_SELF']).'?user_action=add-list&formulir_simak_id='.$formulirSimakId.'&pemeriksaan_id='.$pemeriksaanId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
	exit();

}


if($inputGet->getUserAction() == 'add-list' && $inputGet->getFormulirSimakId() != 0)
{
	// Update jenis pemeriksaan
	if($inputPost->getUserAction() == UserAction::ACTIVATE)
	{
		if($inputPost->countableCheckedRowId())
		{
			foreach($inputPost->getCheckedRowId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT) as $rowId)
			{
				$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
				try
				{
					$jenisPemeriksaan->where(PicoSpecification::getInstance()
						->addAnd(PicoPredicate::getInstance()->equals(Field::of()->jenisPemeriksaanId, $rowId))
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
				$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
				try
				{
					$jenisPemeriksaan->where(PicoSpecification::getInstance()
						->addAnd(PicoPredicate::getInstance()->equals(Field::of()->jenisPemeriksaanId, $rowId))
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
						->addAnd(PicoPredicate::getInstance()->equals(Field::of()->jenisPemeriksaanId, $rowId))
						->addAnd($dataFilter)
						;
					$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
					$jenisPemeriksaan->where($specification)
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
	else if($inputPost->getUserAction() == UserAction::SORT_ORDER)
	{
		if($inputPost->getNewOrder() != null && $inputPost->countableNewOrder())
		{
			foreach($inputPost->getNewOrder() as $dataItem)
			{
				try
				{
					if(is_string($dataItem))
					{
						$dataItem = new SetterGetter(json_decode($dataItem));
					}
					$rowId = $dataItem->getPrimaryKey();
					$sortOrder = intval($dataItem->getSortOrder());
					$specification = PicoSpecification::getInstance()
						->addAnd(PicoPredicate::getInstance()->equals(Field::of()->jenisPemeriksaanId, $rowId))
						->addAnd($dataFilter)
						;
					$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
					$jenisPemeriksaan->where($specification)
						->setSortOrder($sortOrder)
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
}



$dataFilter = null;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$formulirSimak = new FormulirSimak(null, $database);
	$formulirSimak->setProsedurId($inputPost->getProsedurId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$formulirSimak->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$formulirSimak->setJudulFormulir($inputPost->getJudulFormulir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$formulirSimak->setNomorFormulir($inputPost->getNomorFormulir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$formulirSimak->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$formulirSimak->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$formulirSimak->setAdminBuat($currentAction->getUserId());
	$formulirSimak->setWaktuBuat($currentAction->getTime());
	$formulirSimak->setIpBuat($currentAction->getIp());
	$formulirSimak->setAdminUbah($currentAction->getUserId());
	$formulirSimak->setWaktuUbah($currentAction->getTime());
	$formulirSimak->setIpUbah($currentAction->getIp());
	try
	{
		$formulirSimak->insert();
		$newId = $formulirSimak->getFormulirSimakId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->formulir_simak_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->formulirSimakId, $inputPost->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$formulirSimak = new FormulirSimak(null, $database);
	$updater = $formulirSimak->where($specification)
		->setProsedurId($inputPost->getProsedurId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setNama($inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setJudulFormulir($inputPost->getJudulFormulir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setNomorFormulir($inputPost->getNomorFormulir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->formulir_simak_id, $newId);
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
			$formulirSimak = new FormulirSimak(null, $database);
			try
			{
				$formulirSimak->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->formulirSimakId, $rowId))
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
			$formulirSimak = new FormulirSimak(null, $database);
			try
			{
				$formulirSimak->where(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->formulirSimakId, $rowId))
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
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->formulirSimakId, $rowId))
					->addAnd($dataFilter)
					;
				$formulirSimak = new FormulirSimak(null, $database);
				$formulirSimak->where($specification)
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
else if($inputPost->getUserAction() == UserAction::SORT_ORDER)
{
	if($inputPost->getNewOrder() != null && $inputPost->countableNewOrder())
	{
		foreach($inputPost->getNewOrder() as $dataItem)
		{
			try
			{
				if(is_string($dataItem))
				{
					$dataItem = new SetterGetter(json_decode($dataItem));
				}
				$rowId = $dataItem->getPrimaryKey();
				$sortOrder = intval($dataItem->getSortOrder());
				$specification = PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals(Field::of()->formulirSimakId, $rowId))
					->addAnd($dataFilter)
					;
				$formulirSimak = new FormulirSimak(null, $database);
				$formulirSimak->where($specification)
					->setSortOrder($sortOrder)
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
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguageImpl(new FormulirSimak(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					
					<tr>
						<td><?php echo $appEntityLanguage->getProsedur();?></td>
						<td>
							<select class="form-control" name="prosedur_id" id="prosedur_id" onchange="this.form.querySelector('#nama').value=this.options[this.selectedIndex].dataset.nomorProsedur">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php 
								$specs = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
								echo AppFormBuilder::getInstance()->createSelectOption(new ProsedurMin(null, $database), 
								$specs, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->prosedurId, Field::of()->nama, null, [Field::of()->nomorProsedur])
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="judul_formulir" id="judul_formulir" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_formulir" id="nomor_formulir" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="" autocomplete="off"/>
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
							<button type="submit" class="btn btn-success" name="user_action" id="create_new_data" value="create"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->formulirSimakId, $inputGet->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$formulirSimak = new FormulirSimak(null, $database);
	try{
		$formulirSimak->findOne($specification);
		if($formulirSimak->issetFormulirSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new FormulirSimak(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					
					<tr>
						<td><?php echo $appEntityLanguage->getProsedur();?></td>
						<td>
							<select class="form-control" name="prosedur_id" id="prosedur_id" onchange="this.form.querySelector('#nama').value=this.options[this.selectedIndex].dataset.nomorProsedur">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php 
								$specs = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
								echo AppFormBuilder::getInstance()->createSelectOption(new ProsedurMin(null, $database), 
								$specs, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->prosedurId, Field::of()->nama, $formulirSimak->getProsedurId(), [Field::of()->nomorProsedur])
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<input type="text" class="form-control" name="nama" id="nama" value="<?php echo $formulirSimak->getNama();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="judul_formulir" id="judul_formulir" value="<?php echo $formulirSimak->getJudulFormulir();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td>
							<input type="text" class="form-control" name="nomor_formulir" id="nomor_formulir" value="<?php echo $formulirSimak->getNomorFormulir();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="<?php echo $formulirSimak->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $formulirSimak->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-success" name="user_action" id="update_data" value="update"><?php echo $appLanguage->getButtonSave();?></button>
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonCancel();?></button>
							<input type="hidden" name="formulir_simak_id" id="primary_key_value" value="<?php echo $formulirSimak->getFormulirSimakId();?>"/>
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
else if($inputGet->getUserAction() == 'add-list')
{
	$formulirSimakId = $inputGet->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, true, true);
	$specification = PicoSpecification::getInstanceOf(Field::of()->formulirSimakId, $formulirSimakId);
	$specification->addAnd($dataFilter);
	$formulirSimak = new FormulirSimak(null, $database);
	try{

		$formulirSimak->findOne($specification);
		if($formulirSimak->issetFormulirSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new FormulirSimak(), $appConfig, $currentUser->getLanguageId());
/*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
		
	
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($formulirSimak->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $formulirSimak->getWaitingFor());?></div>
				<?php
		}
		?>
		
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $formulirSimak->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNamaProsedur();?></td>
						<td><?php echo $formulirSimak->retrieve('prosedur', 'nama');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorProsedur();?></td>
						<td><?php echo $formulirSimak->retrieve('prosedur', 'nomorProsedur');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulFormulir();?></td>
						<td><?php echo $formulirSimak->getJudulFormulir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td><?php echo $formulirSimak->getNomorFormulir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $formulirSimak->getSortOrder();?></td>
					</tr>
				</tbody>
			</table>
			
			<div class="filter-section">
				<form action="" method="get" class="filter-form">
					<span class="filter-group">
						<span class="filter-label"><?php echo $appEntityLanguage->getPemeriksaan();?></span>
						<span class="filter-control">
							<select class="form-control" name="pemeriksaan_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pemeriksaanId, Field::of()->nama, $inputGet->getPemeriksaanId())
								; ?>
							</select>
						</span>
					</span>
					<input type="hidden" name="user_action" value="add-list">
					<input type="hidden" name="formulir_simak_id" value="<?php echo $formulirSimakId;?>">
					
					<span class="filter-group">
						<button type="submit" class="btn btn-success" id="show_data"><?php echo $appLanguage->getButtonSearch();?></button>
					</span>


				</form>
			</div>
			
			<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php 
			}
			
			$appEntityLanguage = new AppEntityLanguageImpl(new JenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());

			$specMap = array(
				"formulirSimakId" => PicoSpecification::filter("formulirSimakId", "number"),
				"pemeriksaanId" => PicoSpecification::filter("pemeriksaanId", "number")
			);
			$sortOrderMap = array(
				"formulirSimakId" => "formulirSimakId",
				"pemeriksaanId" => "pemeriksaanId",
				"nama" => "nama",
				"labelYa" => "labelYa",
				"labelTidak" => "labelTidak",
				"nilai" => "nilai",
				"sortOrder" => "sortOrder",
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
					"sortBy" => "pemeriksaan.sortOrder", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				),
				array(
					"sortBy" => "sortOrder", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				)
			));
			
			// $dataControlConfig->setPageSize(1);

			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
			$dataLoader = new JenisPemeriksaan(null, $database);

			$subqueryMap = array(
			
			"pemeriksaanId" => array(
				"columnName" => "pemeriksaan_id",
				"entityName" => "PemeriksaanMin",
				"tableName" => "pemeriksaan",
				"primaryKey" => "pemeriksaan_id",
				"objectName" => "pemeriksaan",
				"propertyName" => "nama"
			)
			);
			
			try{
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
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-header"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="jenis_pemeriksaan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-jenis-pemeriksaan-id"/>
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
								<td data-col-name="pemeriksaan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getPemeriksaan();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisPemeriksaan();?></a></td>
								<td data-col-name="label_ya" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLabelYa();?></a></td>
								<td data-col-name="label_tidak" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getLabelTidak();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($jenisPemeriksaan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $jenisPemeriksaan->getJenisPemeriksaanId();?>" data-sort-order="<?php echo $jenisPemeriksaan->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $jenisPemeriksaan->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-body data-sort-handler"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="jenis_pemeriksaan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-jenis-pemeriksaan-id" name="checked_row_id[]" value="<?php echo $jenisPemeriksaan->getJenisPemeriksaanId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" 
									href="javascript:;"
									onclick="editList(this)"
									data-jenis-pemeriksaan-id="<?php echo $jenisPemeriksaan->getJenisPemeriksaanId();?>" 
									data-pemeriksaan-id="<?php echo $jenisPemeriksaan->getPemeriksaanId();?>"
									data-nama="<?php echo htmlspecialchars($jenisPemeriksaan->getNama());?>"
									data-label-ya="<?php echo $jenisPemeriksaan->getLabelYa();?>"
									data-label-tidak="<?php echo $jenisPemeriksaan->getLabelTidak();?>"
									><span class="fa fa-edit"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->jenis_pemeriksaan_id, $jenisPemeriksaan->getJenisPemeriksaanId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="pemeriksaan_id"><?php echo $jenisPemeriksaan->issetPemeriksaan() ? $jenisPemeriksaan->getPemeriksaan()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $jenisPemeriksaan->getNama();?></td>
								<td data-col-name="label_ya"><?php echo $jenisPemeriksaan->getLabelYa();?></td>
								<td data-col-name="label_tidak"><?php echo $jenisPemeriksaan->getLabelTidak();?></td>
								<td data-col-name="sort_order" class="data-sort-order-column"><?php echo $jenisPemeriksaan->getSortOrder();?></td>
								<td data-col-name="aktif"><?php echo $jenisPemeriksaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
						<button type="submit" class="btn btn-success" name="user_action" id="activate_selected" value="activate"><?php echo $appLanguage->getButtonActivate();?></button>
						<button type="submit" class="btn btn-warning" name="user_action" id="deactivate_selected" value="deactivate"><?php echo $appLanguage->getButtonDeactivate();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedDelete()){ ?>
						<button type="submit" class="btn btn-danger" name="user_action" id="delete_selected" value="delete" data-onclik-message="<?php echo htmlspecialchars($appLanguage->getWarningDeleteConfirmation());?>"><?php echo $appLanguage->getButtonDelete();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedSortOrder()){ ?>
						<button type="submit" class="btn btn-primary" name="user_action" id="save_current_order" value="sort_order" disabled="disabled"><?php echo $appLanguage->getButtonSaveCurrentOrder();?></button>
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
			
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td>
							<button type="button" class="btn btn-primary" id="add_list" onclick="addList('<?php echo $formulirSimak->getFormulirSimakId();?>');"><?php echo $appLanguage->getTambahDaftar();?></button>
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
						</td>
					</tr>
				</tbody>
			</table>
	</div>
</div>


<div class="modal modal-lg fade" data-mode="list" id="jenis-pemeriksaan-modal" tabindex="-1" aria-labelledby="jenisPemeriksaanLabel" aria-hidden="true" data-formulir-simak-id="<?php echo $formulirSimak->getFormulirSimakId();?>">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title" id="jenisPemeriksaanLabel"><?php echo $appLanguage->getJenisPemeriksaan();?></h5>
		<button type="button" class="btn-close" onclick="closeListModal()" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<form action="" method="post" class="data-form-edit">
				<div class="input-area">
					<div class="label"><?php echo $appEntityLanguage->getPemeriksaan();?></div>
					<div class="control">
					<select class="form-control" name="pemeriksaan_id" id="pemeriksaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pemeriksaanId, Field::of()->nama)
								; ?>
							</select>
					</div>
				</div>
				<div class="input-area">
					<div class="label"><?php echo $appEntityLanguage->getJenisPemeriksaan();?></div>
					<div class="control">
					<textarea name="nama" id="nama" class="form-control summernote"></textarea>
					<input type="hidden" name="user_action" value="add-list">
					<input type="hidden" name="formulir_simak_id" value="<?php echo $formulirSimak->getFormulirSimakId();?>">
					</div>
				</div>
				<div class="input-area">
					<div class="label"><?php echo $appEntityLanguage->getPilihan();?></div>
					<div class="control">
						<select id="pilihan" class="form-control">
							<option value="/">Pilih satu</option>
							<option value="Ya/Tidak">Ya / Tidak</option>
							<option value="Sesuai/Tidak">Sesuai / Tidak</option>
							<option value="Ada/Tidak Ada">Ada / Tidak Ada</option>
							<option value="Sudah/Belum">Sudah / Belum</option>
							<option value="Lengkap/Belum Lengkap">Lengkap / Belum Lengkap</option>
						</select>
					</div>
				</div>
				<div class="input-area">
					<div class="label"><?php echo $appEntityLanguage->getLabelYa();?></div>
					<div class="control">
						<input type="text" class="form-control" name="label_ya">
					</div>
				</div>
				<div class="input-area">
					<div class="label"><?php echo $appEntityLanguage->getLabelTidak();?></div>
					<div class="control">
						<input type="text" class="form-control" name="label_tidak">
					</div>
				</div>
				<input type="hidden" name="jenis_pemeriksaan_id" value="">
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success save-jenis-pemeriksaan"><?php echo $appLanguage->getButtonSave();?></button>
			<button type="button" class="btn btn-secondary" onclick="closeListModal()"><?php echo $appLanguage->getButtonClose();?></button>
		</div>
	</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<script>
	function addList()
	{
		let modal = $('#jenis-pemeriksaan-modal');
		modal.find('[name="jenis_pemeriksaan_id"]').val('');
		modal.find('[name="user_action"]').val('add-list');
		modal.find('[name="pemeriksaan_id"]').val('');		
		modal.find('[name="nama"]').summernote('code', '');
		modal.find('[name="label_ya"]').val('');
		modal.find('[name="label_tidak"]').val('');
		modal.modal('show');
	}
	function closeListModal()
	{
		$('#jenis-pemeriksaan-modal').modal('hide');
	}
	function addJenisPemeriksaan()
	{
		let modal = $('#jenis-pemeriksaan-modal');
		let formulirSimakId = modal.attr('data-formulir-simak-id');
		let pemeriksaanId = modal.find('[name="pemeriksaan_id"]').val();
		let jenisPemeriksaan = modal.find('[name="nama"]').val();
	}
	function editList(elem)
	{
		let modal = $('#jenis-pemeriksaan-modal');
		modal.find('[name="user_action"]').val('update-list');
		modal.find('[name="jenis_pemeriksaan_id"]').val(elem.dataset.jenisPemeriksaanId);		
		modal.find('[name="pemeriksaan_id"]').val(elem.dataset.pemeriksaanId);		
		modal.find('[name="nama"]').summernote('code', elem.dataset.nama);
		modal.find('[name="label_ya"]').val(elem.dataset.labelYa);
		modal.find('[name="label_tidak"]').val(elem.dataset.labelTidak);
		modal.modal('show');
	}

	var elements = [];
	let editors = [];
	jQuery(function($) {
		$('.save-jenis-pemeriksaan').on('click', function(e){
			let modal = $('#jenis-pemeriksaan-modal');
			modal.find('form')[0].submit();
		})
		$('#pilihan').on('change', function(e){
			let val = $(this).val().split('/');
			$('[name="label_ya"]').val(val[0]);
			$('[name="label_tidak"]').val(val[1]);
		});
		
		var activeEditor = null;	
		$('textarea.summernote').each(function(index){
			$(this).attr('data-index', index);
			$(this).addClass('summernote-source');
			editors[index] = $(this).summernote({
				height: 200,
				hint: {
					words: [],
					match: /\b(\w{1,})$/,
					search: function (keyword, callback) {
						callback($.grep(this.words, function (item) {
							return item.indexOf(keyword) === 0;
						}));
					}
				},
				toolbar: [
					['style', ['style', 'bold', 'italic', 'underline']],
					['para', ['ul', 'ol', 'paragraph']],
					['font', ['fontname', 'fontsize', 'color', 'background']],
					['insert', ['table']],
				],
				callbacks: {
					onImageUpload: function (files) {
					},
					onMediaDelete: function (target) {
					},
					onFocus: function() {
						let idx = $(this).attr('data-index');
						activeEditor = editors[idx];
					}
				}
			});
			elements[index] = $(this);
		});
	});
</script>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
			}
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->formulirSimakId, $inputGet->getFormulirSimakId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$formulirSimak = new FormulirSimak(null, $database);
	try{
		$subqueryMap = array(
		"umkId" => array(
			"columnName" => "umk_id",
			"entityName" => "UmkMin",
			"tableName" => "umk",
			"primaryKey" => "umk_id",
			"objectName" => "umk",
			"propertyName" => "nama"
		), 
		"prosedurId" => array(
			"columnName" => "prosedur_id",
			"entityName" => "ProsedurMin",
			"tableName" => "prosedur",
			"primaryKey" => "prosedur_id",
			"objectName" => "prosedur",
			"propertyName" => "nama"
		), 
		"adminUbah" => array(
			"columnName" => "admin_ubah",
			"entityName" => "AdminMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pengubah",
			"propertyName" => "nama"
		)
		);
		$formulirSimak->findOne($specification, null, $subqueryMap);
		if($formulirSimak->issetFormulirSimakId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new FormulirSimak(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($formulirSimak->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $formulirSimak->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getProsedur();?></td>
						<td><?php echo $formulirSimak->issetProsedur() ? $formulirSimak->getProsedur()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $formulirSimak->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getJudulFormulir();?></td>
						<td><?php echo $formulirSimak->getJudulFormulir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNomorFormulir();?></td>
						<td><?php echo $formulirSimak->getNomorFormulir();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $formulirSimak->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $formulirSimak->getAdminBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $formulirSimak->issetPengubah() ? $formulirSimak->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $formulirSimak->dateFormatWaktuBuat('Y m d H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $formulirSimak->dateFormatWaktuUbah('Y m d H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $formulirSimak->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $formulirSimak->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $formulirSimak->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" id="update_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->formulir_simak_id, $formulirSimak->getFormulirSimakId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="formulir_simak_id" id="primary_key_value" value="<?php echo $formulirSimak->getFormulirSimakId();?>"/>
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
$appEntityLanguage = new AppEntityLanguageImpl(new FormulirSimak(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"umkId" => PicoSpecification::filter("umkId", "number"),
	"prosedurId" => PicoSpecification::filter("prosedurId", "number"),
	"nama" => PicoSpecification::filter("nama", "fulltext")
);
$sortOrderMap = array(
	"umkId" => "umkId",
	"prosedurId" => "prosedurId",
	"nama" => "nama",
	"judulFormulir" => "judulFormulir",
	"nomorFormulir" => "nomorFormulir",
	"sortOrder" => "sortOrder",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, null);

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $dataControlConfig->getPageSize()), $sortable);
$dataLoader = new FormulirSimak(null, $database);

$subqueryMap = array(
"umkId" => array(
	"columnName" => "umk_id",
	"entityName" => "UmkMin",
	"tableName" => "umk",
	"primaryKey" => "umk_id",
	"objectName" => "umk",
	"propertyName" => "nama"
), 
"prosedurId" => array(
	"columnName" => "prosedur_id",
	"entityName" => "ProsedurMin",
	"tableName" => "prosedur",
	"primaryKey" => "prosedur_id",
	"objectName" => "prosedur",
	"propertyName" => "nama"
), 
"adminUbah" => array(
	"columnName" => "admin_ubah",
	"entityName" => "AdminMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
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
				<?php
				
				$specs = PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false));
				?>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProsedur();?></span>
					<span class="filter-control">
							<select class="form-control" name="prosedur_id" onchange="this.form.submit()">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new ProsedurMin(null, $database), 
								$specs, 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->prosedurId, Field::of()->nama, $inputGet->getProsedurId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" class="form-control" name="nama" value="<?php echo $inputGet->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, false, true);?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success" id="show_data"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
				<?php if($userPermission->isAllowedCreate()){ ?>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" id="add_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonAdd();?></button>
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
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-header"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-controll data-selector" data-key="formulir_simak_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-formulir-simak-id"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td class="data-controll data-editor">
									<span class="fa fa-edit"></span>
								</td>
								<td class="data-controll data-editor">
									<span class="fa fa-list"></span>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="prosedur_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProsedur();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="judul_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJudulFormulir();?></a></td>
								<td data-col-name="nomor_formulir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNomorFormulir();?></a></td>
								<td data-col-name="sort_order" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSortOrder();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($formulirSimak = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $formulirSimak->getFormulirSimakId();?>" data-sort-order="<?php echo $formulirSimak->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $formulirSimak->optionAktif('true', 'false');?>">
								<?php if($userPermission->isAllowedSortOrder()){ ?>
								<td class="data-sort data-sort-body data-sort-handler"></td>
								<?php } ?>
								<?php if($userPermission->isAllowedBatchAction()){ ?>
								<td class="data-selector" data-key="formulir_simak_id">
									<input type="checkbox" class="checkbox check-slave checkbox-formulir-simak-id" name="checked_row_id[]" value="<?php echo $formulirSimak->getFormulirSimakId();?>"/>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedUpdate()){ ?>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->formulir_simak_id, $formulirSimak->getFormulirSimakId());?>"><span class="fa fa-edit"></span></a>
								</td>
								<td>
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl('add-list', Field::of()->formulir_simak_id, $formulirSimak->getFormulirSimakId());?>"><span class="fa fa-list"></span></a>
								</td>
								<?php } ?>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->formulir_simak_id, $formulirSimak->getFormulirSimakId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="prosedur_id"><?php echo $formulirSimak->issetProsedur() ? $formulirSimak->getProsedur()->getNama() : "";?></td>
								<td data-col-name="nama"><?php echo $formulirSimak->getNama();?></td>
								<td data-col-name="judul_formulir"><?php echo $formulirSimak->getJudulFormulir();?></td>
								<td data-col-name="nomor_formulir"><?php echo $formulirSimak->getNomorFormulir();?></td>
								<td data-col-name="sort_order" class="data-sort-order-column"><?php echo $formulirSimak->getSortOrder();?></td>
								<td data-col-name="aktif"><?php echo $formulirSimak->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
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
						<button type="submit" class="btn btn-success" name="user_action" id="activate_selected" value="activate"><?php echo $appLanguage->getButtonActivate();?></button>
						<button type="submit" class="btn btn-warning" name="user_action" id="deactivate_selected" value="deactivate"><?php echo $appLanguage->getButtonDeactivate();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedDelete()){ ?>
						<button type="submit" class="btn btn-danger" name="user_action" id="delete_selected" value="delete" data-onclik-message="<?php echo htmlspecialchars($appLanguage->getWarningDeleteConfirmation());?>"><?php echo $appLanguage->getButtonDelete();?></button>
						<?php } ?>
						<?php if($userPermission->isAllowedSortOrder()){ ?>
						<button type="submit" class="btn btn-primary" name="user_action" id="save_current_order" value="sort_order" disabled="disabled"><?php echo $appLanguage->getButtonSaveCurrentOrder();?></button>
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

