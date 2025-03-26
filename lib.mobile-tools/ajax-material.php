<?php

use MagicApp\AppEntityLanguage;
use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\Material;
use Sipro\Util\CommonUtil;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$appEntityLanguage = new AppEntityLanguage(new Material(), $appConfig, $currentUser->getLanguageId());

$inputGet = new InputGet();

$materialId = $inputGet->getMaterialId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_UINT, false, false, true);
$option = $inputGet->getOption(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
if($option != '' && $option != 'add' && $option != 'edit')
{
	$option = '';
}

if($option == 'add')
{
	?>
	<form name="form1" method="post" action="">
		<div class="form-label">
			<?php echo $appEntityLanguage->getMaterial();?>
		</div>
		<div class="form-control">
		<input type="text" data-full-width="true" name="nama" autocomplete="off" placeholder="<?php echo $appEntityLanguage->getNama();?>" required="required">
		</div>
		<div class="form-label">
			<?php echo $appEntityLanguage->getSatuan();?>
		</div>
		<div class="form-control">
		<input type="text" data-full-width="true" name="satuan" autocomplete="off" placeholder="<?php echo $appEntityLanguage->getSatuan();?>">
		</div>
		<div class="form-label">
			<?php echo $appEntityLanguage->getJenisPekerjaan();?>
		</div>
		<div class="form-control">
			<?php echo CommonUtil::getMaterialPekerjaan($database, 'check');?>
		</div>
		<div class="form-control">
			<input type="hidden" name="option" value="<?php echo $option;?>">
			<button type="submit" class="btn btn-success" name="save" value="Simpan"><?php echo $appEntityLanguage->getButtonSave();?></button>
		</div>
	</form>
	<?php
}
else if($option == 'edit')
{
	$material = new Material(null, $database);
	try
	{
		$material->find($materialId);
		?>
		<form name="form1" method="post" action="">
			<div class="form-label">
				<?php echo $appEntityLanguage->getMaterial();?>
			</div>
			<div class="form-control">
				<input type="text" data-full-width="true" name="nama" value="<?php echo $material->getNama();?>" placeholder="<?php echo $appEntityLanguage->getNama();?>" autocomplete="off" required="required">
			</div>
			<div class="form-label">
				<?php echo $appEntityLanguage->getSatuan();?>
			</div>
			<div class="form-control">
				<input type="text" data-full-width="true" name="satuan" value="<?php echo $material->getSatuan();?>" placeholder="<?php echo $appEntityLanguage->getSatuan();?>" autocomplete="off">
			</div>
			<div class="form-label">
				<?php echo $appEntityLanguage->getJenisPekerjaan();?>
			</div>
			<div class="form-control">
				<?php echo CommonUtil::getMaterialPekerjaan($database, 'check', $material->getMaterialId());?>
			</div>
			<div class="form-control">
				<input type="hidden" name="option" value="<?php echo $option;?>">
				<input type="hidden" name="material_id2" value="<?php echo $material->getMaterialId();?>">
				<button type="submit" class="btn btn-success" name="save" value="Simpan"><?php echo $appEntityLanguage->getButtonSave();?></button>
			</div>
		</form>
		<?php
	}
	catch(Exception $e)
	{
		// do nothing
	}
}

