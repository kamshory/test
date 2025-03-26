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
use Sipro\Entity\Data\Pesan;
use Sipro\Entity\Data\PesanTrash;
use Sipro\Entity\Data\AdminMin;
use Sipro\Entity\Data\SupervisorMin;
use Sipro\Util\MessageUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "pesan", $appLanguage->getPesan());

$inputGet = new InputGet();
$inputPost = new InputPost();

if($inputPost->getUserAction() == UserAction::CREATE)
{
	$pesan = new Pesan(null, $database);
	$pesan->setPengirimUserId($inputPost->getPengirimUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pesan->setPengirimSupervisorId($inputPost->getPengirimSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pesan->setPenerimaUserId($inputPost->getPenerimaUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pesan->setPenerimaSupervisorId($inputPost->getPenerimaSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$pesan->setSubjek($inputPost->getSubjek(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pesan->setIsi($inputPost->getIsi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pesan->setDibaca($inputPost->getDibaca(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pesan->setSalinan($inputPost->getSalinan(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pesan->setWaktuBuat($inputPost->getWaktuBuat(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pesan->setWaktuUbah($inputPost->getWaktuUbah(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pesan->setWaktuBaca($inputPost->getWaktuBaca(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$pesan->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$pesan->setAdminBuat($currentAction->getUserId());
	$pesan->setTimeBuat($currentAction->getTime());
	$pesan->setIpBuat($currentAction->getIp());
	$pesan->setAdminUbah($currentAction->getUserId());
	$pesan->setTimeUbah($currentAction->getTime());
	$pesan->setIpUbah($currentAction->getIp());
	$pesan->insert();
	$newId = $pesan->getPesanId();
	$currentModule->redirectTo(UserAction::DETAIL, Field::of()->pesan_id, $newId);
}
if($inputPost->getUserAction() == 'read')
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$pesan = new Pesan(null, $database);
			try
			{
				$specification = PicoSpecification::getInstance()
					->addAnd(['pesanId', $rowId])
					->addAnd(PicoSpecification::getInstance()
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', true))
						)
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', false))
						)
					)
				;
				$pesan->where($specification)
				->setDibaca(true)
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
if($inputPost->getUserAction() == 'unread')
{
	if($inputPost->countableCheckedRowId())
	{
		foreach($inputPost->getCheckedRowId() as $rowId)
		{
			$pesan = new Pesan(null, $database);
			try
			{
				$specification = PicoSpecification::getInstance()
					->addAnd(['pesanId', $rowId])
					->addAnd(PicoSpecification::getInstance()
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', true))
						)
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', false))
						)
					)
				;
				$pesan->where($specification)
				->setDibaca(false)
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
				$pesan = new Pesan(null, $database);

				$specification = PicoSpecification::getInstance()
					->addAnd(['pesanId', $rowId])
					->addAnd(PicoSpecification::getInstance()
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', true))
						)
						->addOr(PicoSpecification::getInstance()
							->addAnd(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
							->addAnd(PicoPredicate::getInstance()->equals('salinan', false))
						)
					)
				;

				$pesan->findOne($specification);
				if($pesan->issetPesanId())
				{
					$pesanTrash = new PesanTrash($pesan, $database);
					$pesanTrash->insert();
					$pesan->delete();
				}
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
$appEntityLanguage = new AppEntityLanguage(new Pesan(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.css">
<link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.css">
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/popper/popper.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote.js"></script>
<script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/summernote/0.8.20/summernote-bs4.min.js"></script>
<style>
	.note-hint-popover {
		position: absolute;
	}
</style>
<script>
	jQuery(function(e){
		$('.summernote').summernote({
			height: 200,
			hint: {
				words: [],
				match: /\b(\w{1,})$/,
				search: function (keyword, callback) 
				{
					callback($.grep(this.words, function (item) {
						return item.indexOf(keyword) === 0;
					}));
				},
				content: function (item) {
					let span = document.createElement('span');
					span.setAttribute('data-sipro-mentioned-user', item);
					span.innerHTML = `${item}`;
					return span; 
				}
			}
		});

		$('#penerima_user_id').change(function(){
			$('#penerima_supervisor_id').val('');
		});
		
		$('#penerima_supervisor_id').change(function(){
			$('#penerima_user_id').val('');
		});
	});
	function checkPenerima()
	{
		let v1 = $('#penerima_supervisor_id').val();
		let v2 = $('#penerima_user_id').val();
		let v3 = $('#subjek').val();
		let v4 = $('#isi').val();
		return (v1 != '' || v2 != '') && v3 != '' && v4 != '';
	}
</script>
<div class="page page-jambi page-insert">
	<div class="jambi-wrapper">
		<form name="createform" id="createform" action="" method="post" onsubmit="return checkPenerima()">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getAdministrator();?></td>
						</tr>
					<tr>
						<td>
							<select class="form-control" name="penerima_user_id" id="penerima_user_id">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AdminMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->adminId, Field::of()->nama)
								; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						</tr>
					<tr>
						<td>
							<select class="form-control" name="penerima_supervisor_id" id="penerima_supervisor_id">
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
						<td><?php echo $appEntityLanguage->getSubjek();?></td>
					<tr>

						<td>
							<input autocomplete="off" type="text" class="form-control" name="subjek" id="subjek"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIsi();?></td>
					<tr>

						<td>
							<textarea class="summernote" spellcheck="false" name="isi" id="isi"></textarea>
						</td>
					</tr>
					
				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td></tr>
						<tr>
						<td>
							<button type="submit" class="btn btn-success" name="user_action" value="create"><?php echo $appLanguage->getButtonSend();?></button>
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
else if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$pesan = new Pesan(null, $database);
	try{
		$subqueryMap = array(
			"pengirimUserId" => array(
				"columnName" => "pengirim_user_id",
				"entityName" => "UserMin",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "pengirimUser",
				"propertyName" => "nama_depan"
			), 
			"pengirimSupervisorId" => array(
				"columnName" => "pengirim_supervisor_id",
				"entityName" => "SupervisorMin",
				"tableName" => "supervisor",
				"primaryKey" => "supervisor_id",
				"objectName" => "pengirimSupervisor",
				"propertyName" => "nama"
			), 
			"penerimaUserId" => array(
				"columnName" => "penerima_user_id",
				"entityName" => "UserMin",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "penerimaUser",
				"propertyName" => "nama_depan"
			), 
			"penerimaSupervisorId" => array(
				"columnName" => "penerima_supervisor_id",
				"entityName" => "Supervisor",
				"tableName" => "supervisor",
				"primaryKey" => "supervisor_id",
				"objectName" => "penerimaSupervisor",
				"propertyName" => "nama"
			)
			);
		$specification = PicoSpecification::getInstance()
			->addAnd(['pesanId', $inputGet->getPesanId()])
			->addAnd(PicoSpecification::getInstance()
				->addOr(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
					->addAnd(PicoPredicate::getInstance()->equals('salinan', true))
				)
				->addOr(PicoSpecification::getInstance()
					->addAnd(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
					->addAnd(PicoPredicate::getInstance()->equals('salinan', false))
				)
			)
		;
		$pesan->findOne($specification, null, $subqueryMap);

		if($pesan->issetPesanId())
		{
			$pesan->setDibaca(true)->update();

$appEntityLanguage = new AppEntityLanguage(new Pesan(), $appConfig, $currentUser->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			$mapForDibaca = array(
				"0" => array("value" => "0", "label" => "Belum dibaca", "default" => "false"),
				"1" => array("value" => "1", "label" => "Sudah dibaca", "default" => "false")
			);
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">

		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getPengirim();?></td>
						<td>
							<?php echo $pesan->issetPengirimUser() ? $pesan->getPengirimUser()->getNama() : "";?>
							<?php echo $pesan->issetPengirimSupervisor() ? $pesan->getPengirimSupervisor()->getNama() : "";?>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPenerima();?></td>
						<td>
							<?php echo $pesan->issetPenerimaUser() ? $pesan->getPenerimaUser()->getNama() : "";?>
							<?php echo $pesan->issetPenerimaSupervisor() ? $pesan->getPenerimaSupervisor()->getNama() : "";?>
						</td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getSubjek();?></td>
						<td><?php echo $pesan->getSubjek();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getIsi();?></td>
						<td><?php echo $pesan->getIsi();?></td>
					</tr>
					
					<tr>
						<td><?php echo $appEntityLanguage->getDikirim();?></td>
						<td><?php echo $pesan->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>

				</tbody>
			</table>
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td></td>
						<td>
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
$messageUtil = new MessageUtil($database);
$appEntityLanguage = new AppEntityLanguage(new Pesan(), $appConfig, $currentUser->getLanguageId());
$mapForDibaca = array(
	"0" => array("value" => "0", "label" => "Belum dibaca", "default" => "false"),
	"1" => array("value" => "1", "label" => "Sudah dibaca", "default" => "false")
);
$specMap = array(
	"pengirimUserId" => PicoSpecification::filter("pengirimUserId", "number"),
	"pengirimSupervisorId" => PicoSpecification::filter("pengirimSupervisorId", "number"),
	"penerimaUserId" => PicoSpecification::filter("penerimaUserId", "number"),
	"penerimaSupervisorId" => PicoSpecification::filter("penerimaSupervisorId", "number"),
	"dibaca" => PicoSpecification::filter("dibaca", "fulltext"),
	"aktif" => PicoSpecification::filter("aktif", "boolean")
);
$sortOrderMap = array(
	"pengirimUserId" => "pengirimUserId",
	"pengirimSupervisorId" => "pengirimSupervisorId",
	"penerimaUserId" => "penerimaUserId",
	"penerimaSupervisorId" => "penerimaSupervisorId",
	"subjek" => "subjek",
	"dibaca" => "dibaca",
	"waktuBuat" => "waktuBuat",
	"waktuUbah" => "waktuUbah",
	"waktuBaca" => "waktuBaca",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::getInstance();

// Additional filter here
if($inputGet->getUserId() != null)
{
	$specification->addAnd(PicoSpecification::getInstance()
		->addOr(PicoPredicate::getInstance()->equals('pengirimUserId', $inputGet->getUserId()))
		->addOr(PicoPredicate::getInstance()->equals('penerimaUserId', $inputGet->getUserId()))
	);
}
if($inputGet->getSupervisorId() != null)
{
	$specification->addAnd(PicoSpecification::getInstance()
		->addOr(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $inputGet->getSupervisorId()))
		->addOr(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $inputGet->getSupervisorId()))
	);
}

$specification->addAnd(PicoSpecification::getInstance()
	->addAnd(PicoSpecification::getInstance()
		->addOr(PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals('pengirimSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
			->addAnd(PicoPredicate::getInstance()->equals('salinan', true))
		)
		->addOr(PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals('penerimaSupervisorId', $currentLoggedInSupervisor->getSupervisorId()))
			->addAnd(PicoPredicate::getInstance()->equals('salinan', false))
		)
	)
);

if($inputGet->getDibaca() != null)
{
	$specification
		->addAnd(PicoPredicate::getInstance()->equals('dibaca', $inputGet->getDibaca()))
	;
}

// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "waktuBuat", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new Pesan(null, $database);

$subqueryMap = array(
	"pengirimUserId" => array(
		"columnName" => "pengirim_user_id",
		"entityName" => "UserMin",
		"tableName" => "admin",
		"primaryKey" => "admin_id",
		"objectName" => "pengirimUser",
		"propertyName" => "nama_depan"
	), 
	"pengirimSupervisorId" => array(
		"columnName" => "pengirim_supervisor_id",
		"entityName" => "SupervisorMin",
		"tableName" => "supervisor",
		"primaryKey" => "supervisor_id",
		"objectName" => "pengirimSupervisor",
		"propertyName" => "nama"
	), 
	"penerimaUserId" => array(
		"columnName" => "penerima_user_id",
		"entityName" => "UserMin",
		"tableName" => "admin",
		"primaryKey" => "admin_id",
		"objectName" => "penerimaUser",
		"propertyName" => "nama_depan"
	), 
	"penerimaSupervisorId" => array(
		"columnName" => "penerima_supervisor_id",
		"entityName" => "Supervisor",
		"tableName" => "supervisor",
		"primaryKey" => "supervisor_id",
		"objectName" => "penerimaSupervisor",
		"propertyName" => "nama"
	)
	);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
			<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getAdmin();?></span>
					<span class="filter-control">
							<select name="pengirim_user_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new AdminMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->adminId, Field::of()->nama, $inputGet->getUserId())
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getSupervisor();?></span>
					<span class="filter-control">
							<select name="pengirim_supervisor_id" class="form-control">
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
								; ?>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getDibaca();?></span>
					<span class="filter-control">
							<select name="dibaca" class="form-control" data-value="<?php echo $inputGet->getDibaca();?>">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<option value="0" <?php echo AppFormBuilder::selected($inputGet->getDibaca(), '0');?>>Belum dibaca</option>
								<option value="1" <?php echo AppFormBuilder::selected($inputGet->getDibaca(), '1');?>>Sudah dibaca</option>
							</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
		
				<span class="filter-group">
					<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl(UserAction::CREATE);?>'"><?php echo $appLanguage->getButtonCompose();?></button>
				</span>
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
								<td class="data-controll data-selector" data-key="pesan_id">
									<input type="checkbox" class="checkbox check-master" data-selector=".checkbox-pesan-id"/>
								</td>
								<td class="data-controll data-viewer">
									<i class="fa-solid fa-arrow-right-to-bracket"></i>
								</td>
								<td class="data-controll data-viewer">
									<i class="fa fa-envelope"></i>
								</td>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="audien" class="order-controll" width="200"><a href="#"><?php echo $appEntityLanguage->getAudien();?></a></td>
								<td data-col-name="subjek" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getSubjek();?></a></td>
								<td data-col-name="waktu_buat" class="order-controll" width="130"><a href="#"><?php echo $appEntityLanguage->getDikirim();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($pesan = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<td class="data-selector" data-key="pesan_id">
									<input type="checkbox" class="checkbox check-slave checkbox-pesan-id" name="checked_row_id[]" value="<?php echo $pesan->getPesanId();?>"/>
								</td>
								<td>
								<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->pesan_id, $pesan->getPesanId());?>"><i class="fa-solid <?php echo $pesan->optionSalinan('fa-arrow-right-from-bracket', 'fa-arrow-right-to-bracket');?>"></i></a>
								</td>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->pesan_id, $pesan->getPesanId());?>"><span class="fa fa-envelope<?php echo $pesan->optionDibaca('-open', '');?>"></span></a>
								</td>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="audien"><?php 
								echo $messageUtil->getAudienceFromSupervisor($pesan, $currentLoggedInSupervisor->getSupervisorId());
								?></td>
								<td data-col-name="subjek"><?php echo $pesan->getSubjek();?></td>
								<td data-col-name="waktu_buat"><?php echo $pesan->dateFormatWaktuBuat('j F Y H:i:s');?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">
						<button type="submit" class="btn btn-primary" name="user_action" value="read"><?php echo $appLanguage->getMarkAsRead();?></button>
						<button type="submit" class="btn btn-primary" name="user_action" value="unread"><?php echo $appLanguage->getMarkAsUnread();?></button>
						<button type="submit" class="btn btn-danger" name="user_action" value="delete" data-onclik-message="<?php echo htmlspecialchars($appLanguage->getWarningDeleteConfirmation());?>"><?php echo $appLanguage->getButtonDelete();?></button>
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
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
/*ajaxSupport*/
}

