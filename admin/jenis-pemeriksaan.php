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
use Sipro\Entity\Data\JenisPemeriksaan;
use Sipro\Entity\Data\PemeriksaanMin;


require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "jenis-pemeriksaan", $appLanguage->getJenisPemeriksaan());
$userPermission = new AppUserPermissionImpl($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

/**
 * Clean HTML
 *
 * @param string $html
 * @return string
 */
function cleanWordHtml($html) {
    // 1. Hapus komentar HTML seperti <!--[if gte mso 9]> ... <![endif]-->
    $html = preg_replace('/<!--\[if.*?<!\[endif\]-->/is', '', $html);

    // 2. Hapus tag XML (termasuk isi dalam tag)
    $html = preg_replace('/<xml.*?<\/xml>/is', '', $html);

    // 3. Hapus namespace tag (seperti <o:p> atau <w:something>)
    $html = preg_replace('/<\/*[a-z]+:[^>]*>/i', '', $html);

    // 4. Opsional: hapus script dan style (Word kadang menyisipkan ini juga)
    $html = preg_replace('/<script.*?<\/script>/is', '', $html);
    $html = preg_replace('/<style.*?<\/style>/is', '', $html);

    // 5. Trim spasi berlebih
    $html = trim($html);

    return $html;
}

$dataFilter = null;

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	$jenisPemeriksaan->setPemeriksaanId($inputPost->getPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$jenisPemeriksaan->setNama(cleanWordHtml($inputPost->getNama(PicoFilterConstant::FILTER_DEFAULT, false, false, true)));
	$jenisPemeriksaan->setLabelYa($inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$jenisPemeriksaan->setLabelTidak($inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$jenisPemeriksaan->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$jenisPemeriksaan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
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
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->jenis_pemeriksaan_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$specification = PicoSpecification::getInstanceOf(Field::of()->jenisPemeriksaanId, $inputPost->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	$updater = $jenisPemeriksaan->where($specification)
		->setPemeriksaanId($inputPost->getPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setNama(cleanWordHtml($inputPost->getNama(PicoFilterConstant::FILTER_DEFAULT, false, false, true)))
		->setLabelYa($inputPost->getLabelYa(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setLabelTidak($inputPost->getLabelTidak(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true))
		->setSortOrder($inputPost->getSortOrder(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true))
		->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true))
	;
	$updater->setAdminUbah($currentAction->getUserId());
	$updater->setWaktuUbah($currentAction->getTime());
	$updater->setIpUbah($currentAction->getIp());
	try
	{
		$updater->update();
		$newId = $inputPost->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->jenis_pemeriksaan_id, $newId);
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
if($inputGet->getUserAction() == UserAction::CREATE)
{
$appEntityLanguage = new AppEntityLanguageImpl(new JenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);

?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<script>
	jQuery(function(e){
		$('.summernote').summernote({
			height: 200,
			callbacks: {
				
			},
			spellCheck: false
		});

	});
</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPemeriksaan();?></td>
						<td>
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
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<textarea class="form-control summernote" name="nama" id="nama" spellcheck="false" required="required"></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td>
							<input type="text" class="form-control" name="label_ya" id="label_ya" value="" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td>
							<input type="text" class="form-control" name="label_tidak" id="label_tidak" value="" autocomplete="off"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->jenisPemeriksaanId, $inputGet->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	try{
		$jenisPemeriksaan->findOne($specification);
		if($jenisPemeriksaan->issetJenisPemeriksaanId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new JenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<script>
	jQuery(function(e){
		$('.summernote').summernote({
			height: 200,
			callbacks: {
				
			},
			spellCheck: false
		});

	});
</script>
<div class="page page-jambi page-update">
	<div class="jambi-wrapper">
		<form name="updateform" id="updateform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPemeriksaan();?></td>
						<td>
							<select class="form-control" name="pemeriksaan_id" id="pemeriksaan_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new PemeriksaanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->pemeriksaanId, Field::of()->nama, $jenisPemeriksaan->getPemeriksaanId())
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td>
							<textarea class="form-control summernote" name="nama" id="nama" spellcheck="false" required="required"><?php echo $jenisPemeriksaan->getNama();?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td>
							<input type="text" class="form-control" name="label_ya" id="label_ya" value="<?php echo $jenisPemeriksaan->getLabelYa();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td>
							<input type="text" class="form-control" name="label_tidak" id="label_tidak" value="<?php echo $jenisPemeriksaan->getLabelTidak();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td>
							<input type="number" step="1" class="form-control" name="sort_order" id="sort_order" value="<?php echo $jenisPemeriksaan->getSortOrder();?>" autocomplete="off"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td>
							<label><input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" <?php echo $jenisPemeriksaan->createCheckedAktif();?>/> <?php echo $appEntityLanguage->getAktif();?></label>
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
							<input type="hidden" name="jenis_pemeriksaan_id" id="primary_key_value" value="<?php echo $jenisPemeriksaan->getJenisPemeriksaanId();?>"/>
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
	$specification = PicoSpecification::getInstanceOf(Field::of()->jenisPemeriksaanId, $inputGet->getJenisPemeriksaanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT));
	$specification->addAnd($dataFilter);
	$jenisPemeriksaan = new JenisPemeriksaan(null, $database);
	try{
		$subqueryMap = array(
		"pemeriksaanId" => array(
			"columnName" => "pemeriksaan_id",
			"entityName" => "PemeriksaanMin",
			"tableName" => "pemeriksaan",
			"primaryKey" => "pemeriksaan_id",
			"objectName" => "pemeriksaan",
			"propertyName" => "nama"
		), 
		"adminBuat" => array(
			"columnName" => "admin_buat",
			"entityName" => "AdminMin",
			"tableName" => "admin",
			"primaryKey" => "admin_id",
			"objectName" => "pembuat",
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
		$jenisPemeriksaan->findOne($specification, null, $subqueryMap);
		if($jenisPemeriksaan->issetJenisPemeriksaanId())
		{
$appEntityLanguage = new AppEntityLanguageImpl(new JenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());
require_once $appInclude->mainAppHeader(__DIR__);
			// Define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($jenisPemeriksaan->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $jenisPemeriksaan->getWaitingFor());?></div>
				<?php
		}
		?>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPemeriksaan();?></td>
						<td><?php echo $jenisPemeriksaan->issetPemeriksaan() ? $jenisPemeriksaan->getPemeriksaan()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getNama();?></td>
						<td><?php echo $jenisPemeriksaan->getNama();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelYa();?></td>
						<td><?php echo $jenisPemeriksaan->getLabelYa();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getLabelTidak();?></td>
						<td><?php echo $jenisPemeriksaan->getLabelTidak();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSortOrder();?></td>
						<td><?php echo $jenisPemeriksaan->getSortOrder();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminBuat();?></td>
						<td><?php echo $jenisPemeriksaan->issetPembuat() ? $jenisPemeriksaan->getPembuat()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAdminUbah();?></td>
						<td><?php echo $jenisPemeriksaan->issetPengubah() ? $jenisPemeriksaan->getPengubah()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $jenisPemeriksaan->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $jenisPemeriksaan->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpBuat();?></td>
						<td><?php echo $jenisPemeriksaan->getIpBuat();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIpUbah();?></td>
						<td><?php echo $jenisPemeriksaan->getIpUbah();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getAktif();?></td>
						<td><?php echo $jenisPemeriksaan->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
					</tr>
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
							<?php if($userPermission->isAllowedUpdate()){ ?>
							<button type="button" class="btn btn-primary" id="update_data" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->jenis_pemeriksaan_id, $jenisPemeriksaan->getJenisPemeriksaanId());?>';"><?php echo $appLanguage->getButtonUpdate();?></button>
							<?php } ?>
		
							<button type="button" class="btn btn-primary" id="back_to_list" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
							<input type="hidden" name="jenis_pemeriksaan_id" id="primary_key_value" value="<?php echo $jenisPemeriksaan->getJenisPemeriksaanId();?>"/>
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
$appEntityLanguage = new AppEntityLanguageImpl(new JenisPemeriksaan(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"pemeriksaanId" => PicoSpecification::filter("pemeriksaanId", "number"),
	"nama" => PicoSpecification::filter("nama", "fulltext")
);
$sortOrderMap = array(
	"pemeriksaanId" => "pemeriksaanId",
	"nama" => "nama",
	"labelYa" => "labelYa",
	"labelTidak" => "labelTidak",
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
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

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
), 
"adminBuat" => array(
	"columnName" => "admin_buat",
	"entityName" => "AdminMin",
	"tableName" => "admin",
	"primaryKey" => "admin_id",
	"objectName" => "pembuat",
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

if($inputGet->getPemeriksaanId() == 0)
{
	$userPermission->setAllowedSortOrderFalse();
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
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
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
									<a class="edit-control" href="<?php echo $currentModule->getRedirectUrl(UserAction::UPDATE, Field::of()->jenis_pemeriksaan_id, $jenisPemeriksaan->getJenisPemeriksaanId());?>"><span class="fa fa-edit"></span></a>
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
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}

