<?php

use MagicApp\AppEntityLanguage;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\Peralatan;
use Sipro\Util\CommonUtil;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$appEntityLanguage = new AppEntityLanguage(new Peralatan(), $appConfig, $currentUser->getLanguageId());

$inputGet = new InputGet();

$peralatanId = $inputGet->getPeralatanId();
$option = $inputGet->getOption();

if($option != '' && $option != 'add' && $option != 'edit')
{
	$option = '';
}
if($option == 'add')
{
	?>
	<form name="form1" method="post" action="">
		<div class="form-label">
		<?php echo $appEntityLanguage->getPeralatan();?>
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
		<?php echo CommonUtil::getPeralatanPekerjaan($database, 'check');?>
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
	$peralatan = new Peralatan(null, $database);
	try
	{
		$peralatan->find($peralatanId);
		?>
		<form name="form1" method="post" action="">
			<div class="form-label">
			<?php echo $appEntityLanguage->getPeralatan();?>
			</div>
			<div class="form-control">
			<input type="text" data-full-width="true" name="nama" value="<?php echo $peralatan->getNama();?>" placeholder="<?php echo $appEntityLanguage->getNama();?>" autocomplete="off" required="required">
			</div>
			<div class="form-label">
			<?php echo $appEntityLanguage->getSatuan();?>
			</div>
			<div class="form-control">
			<input type="text" data-full-width="true" name="satuan" value="<?php echo $peralatan->getSatuan();?>" placeholder="<?php echo $appEntityLanguage->getSatuan();?>" autocomplete="off">
			</div>
			<div class="form-label">
			<?php echo $appEntityLanguage->getJenisPekerjaan();?>
			</div>
			<div class="form-control">
			<?php echo CommonUtil::getPeralatanPekerjaan($database, 'check', $peralatan->getPeralatanId());?>
			</div>
			<div class="form-control">
			<input type="hidden" name="option" value="<?php echo $option;?>">
			<input type="hidden" name="peralatan_id2" value="<?php echo $peralatan->getPeralatanId();?>">
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
