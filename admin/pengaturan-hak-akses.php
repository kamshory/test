<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

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
use Sipro\Entity\Data\HakAkses;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\UserLevel;
use Sipro\Entity\Data\Modul;
use Sipro\Util\MenuUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "pengaturan-hak-akses", $appLanguage->getPengaturanHakAkses());
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

function getValue($source, $level1, $level2)
{
	if(isset($source) && isset($source[$level1]) && isset($source[$level1][$level2]))
	{
		return $source[$level1][$level2] == 1 ? true : false;
	}
	return false;
}

if($inputPost->getUserAction() == UserAction::UPDATE)
{
	if($inputPost->countableCheckedRowId())
	{
		$sql = "UPDATE hak_akses SET kode_modul = (SELECT modul.kode_modul FROM modul WHERE modul.modul_id = hak_akses.modul_id)";
		$database->execute($sql);
		
		$inputPostArray = json_decode($inputPost."", true);
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$hakAkses = new HakAkses(null, $database);
			try
			{
				$hakAkses
					->setHakAksesId($rowId)
					->setAllowedList(getValue($inputPostArray,      Field::of()->allowedList,      $rowId))
					->setAllowedDetail(getValue($inputPostArray,    Field::of()->allowedDetail,    $rowId))
					->setAllowedCreate(getValue($inputPostArray,    Field::of()->allowedCreate,    $rowId))
					->setAllowedUpdate(getValue($inputPostArray,    Field::of()->allowedUpdate,    $rowId))
					->setAllowedDelete(getValue($inputPostArray,    Field::of()->allowedDelete,    $rowId))
					->setAllowedApprove(getValue($inputPostArray,   Field::of()->allowedApprove,   $rowId))
					->setAllowedSortOrder(getValue($inputPostArray, Field::of()->allowedSortOrder, $rowId))
					->setAllowedExport(getValue($inputPostArray,    Field::of()->allowedExport,    $rowId))
					->update()
					;
				$hakAkses->updateModule($rowId);
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
	$userLevel = new UserLevel(null, $database);
	try
	{
		$userLevel->findOneByUserLevelId($inputPost->getUserLevelId());
		MenuUtil::updateMenuByUserLevelId($database, $appConfig, $userLevel->getUserLevelId(), $userLevel->getIstimewa());   

	}
	catch(Exception $e)
	{
		// do nothing
	}
	$currentModule->redirectToItself();
}

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$hakAkses = new HakAkses(null, $database);
	try{
		$subqueryMap = array(
		"userLevelId" => array(
			"columnName" => "user_level_id",
			"entityName" => "UserLevel",
			"tableName" => "user_level",
			"primaryKey" => "user_level_id",
			"objectName" => "user_level",
			"propertyName" => "nama"
		), 
		"modulId" => array(
			"columnName" => "modul_id",
			"entityName" => "Modul",
			"tableName" => "modul",
			"primaryKey" => "modul_id",
			"objectName" => "modul",
			"propertyName" => "nama"
		)
		);
		$hakAkses->findOneWithPrimaryKeyValue($inputGet->getHakAksesId(), $subqueryMap);
		if($hakAkses->hasValueHakAksesId())
		{
$appEntityLanguage = new AppEntityLanguage(new HakAkses(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getUserLevel();?></td>
						<td><?php echo $hakAkses->hasValueUserLevel() ? $hakAkses->getUserLevel()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getModul();?></td>
						<td><?php echo $hakAkses->hasValueModul() ? $hakAkses->getModul()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getKodeModul();?></td>
						<td><?php echo $hakAkses->getKodeModul();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedList();?></td>
						<td><?php echo $hakAkses->optionAllowedList($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDetail();?></td>
						<td><?php echo $hakAkses->optionAllowedDetail($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedCreate();?></td>
						<td><?php echo $hakAkses->optionAllowedCreate($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedUpdate();?></td>
						<td><?php echo $hakAkses->optionAllowedUpdate($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedDelete();?></td>
						<td><?php echo $hakAkses->optionAllowedDelete($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedApprove();?></td>
						<td><?php echo $hakAkses->optionAllowedApprove($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedSortOrder();?></td>
						<td><?php echo $hakAkses->optionAllowedSortOrder($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAllowedExport();?></td>
						<td><?php echo $hakAkses->optionAllowedExport($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $hakAkses->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?><button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->hak_akses_id, $hakAkses->getHakAksesId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button><?php } ?>&#xD;
							<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
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
$appEntityLanguage = new AppEntityLanguage(new Modul(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<script>
	jQuery(function(){
		$(document).on('change', '.check-all-row', function(){
			let checked = $(this)[0].checked;
			$('.check-all-column').each(function(){
				$(this)[0].checked = checked;
			});
			$('.check-slave').each(function(){
				$(this)[0].checked = checked;
			});
		});
		$(document).on('change', '.check-all-column', function(){
			let checked = $(this)[0].checked;
			$(this).closest('tr').find('.check-slave').each(function(){
				$(this)[0].checked = checked;
			});
		});
	})
</script>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getUserLevel();?></span>
					<span class="filter-control">
							<select name="user_level_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new UserLevel(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->userLevelId, Field::of()->nama, $inputGet->getUserLevelId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getModul();?></span>
					<span class="filter-control">
							<select name="modul_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new Modul(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->modulId, Field::of()->nama, $inputGet->getModulId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
			</form>
		</div>
		<div class="data-section" data-ajax-support="false" data-ajax-name="main-data">
			<?php 	
			
			
			$userLevelId = $inputGet->getUserLevelId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
			
			if($inputGet->getUserLevelId() != 0)
			{

			$specification = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals("grupModul.aktif", true))
			->addAnd(PicoPredicate::getInstance()->equals("modul.aktif", true))
			;
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::getInstance()
				->addSortable(new PicoSort("grupModul.sortOrder", PicoSort::ORDER_TYPE_ASC))
				->addSortable(new PicoSort("modul.sortOrder", PicoSort::ORDER_TYPE_ASC))
			;

			$pageable = new PicoPageable(null, $sortable);
			$dataLoader = new Modul(null, $database);


			$pageData = $dataLoader->findAll($specification, null, $sortable, true);
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
			<form action="" method="post" class="data-form">
				<div class="data-wrapper">
					<table class="table table-row table-sort-by-column">
						<thead>
							<tr>								
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td><?php echo $appEntityLanguage->getGrupModul();?></td>
								<td><?php echo $appEntityLanguage->getModul();?></td>
								<td><?php echo $appEntityLanguage->getUrl();?></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-list"> <?php echo $appEntityLanguage->getList();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-detail"> <?php echo $appEntityLanguage->getDetail();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-create"> <?php echo $appEntityLanguage->getCreate();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-update"> <?php echo $appEntityLanguage->getUpdate();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-delete"> <?php echo $appEntityLanguage->getDelete();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-approve"> <?php echo $appEntityLanguage->getApprove();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-sort-order"> <?php echo $appEntityLanguage->getSortOrder();?><label></td>
								<td><label><input type="checkbox" name="allowed_list" class="checkbox check-master" data-selector=".checkbox-allowed-export"> <?php echo $appEntityLanguage->getExport();?><label></td>
								<td><label><input type="checkbox" name="allowed_all" class="checkbox check-all-row"> <?php echo $appLanguage->getAll();?><label></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							foreach($pageData->getResult() as $modul)
							{
								if($modul->hasValueModulId())
								{
								$dataIndex++;
								$hakAkses = new HakAkses(null, $database);
								try
								{
									$modulId = $modul->getModulId();
									$hakAkses->findOneByModulIdAndUserLevelId($modulId, $userLevelId);
								}
								catch(Exception $e)
								{
									// buat record hak akases
									try
									{
										$hakAkses
											->setModulId($modulId)
											->setKodeModul($modul->getKodeModul())
											->setUserLevelId($userLevelId)
											->setAktif(true)
											->insert()
											;
									}
									catch(Exception $e1)
									{
										//
									}
								}
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">							
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?><input type="hidden" name="checked_row_id[]" value="<?php echo $hakAkses->getHakAksesId();?>"></td>
								<td data-col-name="modul_modul"><?php echo $modul->hasValueGrupModul() ? $modul->getGrupModul()->getNama() : '';?></td>
								<td data-col-name="modul"><?php echo $modul->getNama();?></td>
								<td data-col-name="modul"><?php echo $modul->getUrl();?></td>
								<td data-col-name="allowed_list"><label><input type="checkbox" name="allowed_list[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-list" value="1"<?php echo $hakAkses->optionAllowedList('checked', '');?>> <?php echo $appEntityLanguage->getList();?></label></td>
								<td data-col-name="allowed_detail"><label><input type="checkbox" name="allowed_detail[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-detail" value="1"<?php echo $hakAkses->optionAllowedDetail('checked', '');?>> <?php echo $appEntityLanguage->getDetail();?></label></td>
								<td data-col-name="allowed_create"><label><input type="checkbox" name="allowed_create[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-create" value="1"<?php echo $hakAkses->optionAllowedCreate('checked', '');?>> <?php echo $appEntityLanguage->getCreate();?></label></td>
								<td data-col-name="allowed_update"><label><input type="checkbox" name="allowed_update[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-update" value="1"<?php echo $hakAkses->optionAllowedUpdate('checked', '');?>> <?php echo $appEntityLanguage->getUpdate();?></label></td>
								<td data-col-name="allowed_delete"><label><input type="checkbox" name="allowed_delete[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-delete" value="1"<?php echo $hakAkses->optionAllowedDelete('checked', '');?>> <?php echo $appEntityLanguage->getDelete();?></label></td>
								<td data-col-name="allowed_approve"><label><input type="checkbox" name="allowed_approve[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-approve" value="1"<?php echo $hakAkses->optionAllowedApprove('checked', '');?>> <?php echo $appEntityLanguage->getApprove();?></label></td>
								<td data-col-name="allowed_sort_order"><label><input type="checkbox" name="allowed_sort_order[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-sort-order" value="1"<?php echo $hakAkses->optionAllowedSortOrder('checked', '');?>> <?php echo $appEntityLanguage->getSortOrder();?></label></td>
								<td data-col-name="allowed_export"><label><input type="checkbox" name="allowed_export[<?php echo $hakAkses->getHakAksesId();?>]" class="checkbox check-slave checkbox-allowed-export" value="1"<?php echo $hakAkses->optionAllowedExport('checked', '');?>> <?php echo $appEntityLanguage->getExport();?></label></td>
								<td data-col-name="allowed_all"><label><input type="checkbox" class="checkbox check-all-column"> <?php echo $appLanguage->getAll();?></label></td>
							</tr>
							<?php 
								}
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
						<button type="submit" class="btn btn-success" name="user_action" value="update"><?php echo $appLanguage->getButtonUpdate();?></button>
						<input type="hidden" name="user_level_id" value="<?php echo $userLevelId;?>">

					</div>
				</div>
			</form>
			
			<?php 
			}
			else
			{
			?>
			    <div class="alert alert-info"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php
			}
			}
			else
			{
				?>
			    <div class="alert alert-info"><?php echo $appLanguage->getSelectOneUserLevel();?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
}

