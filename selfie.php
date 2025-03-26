<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\Kehadiran;
use Sipro\Entity\Data\LokasiKehadiran;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "Kehadiran";
$currentModule = new PicoModule($appConfig, $database, null, "/", "kehadiran", $appLanguage->getKehadiran());

require_once __DIR__ . "/inc.app/header-supervisor.php";

$kehadiran = new Kehadiran(null, $database);
    
$tipePengguna = "supervisor";
$tanggal = $inputGet->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
$periodeId = substr(str_replace("-", "", $tanggal), 0, 6);
$supervisorId = $currentLoggedInSupervisor->getSupervisorId();
$jenisKehadiran = "M";
try
{
    // Ambil record yang sudah ada
    $kehadiran->findOneByTipePenggunaAndSupervisorIdAndTanggal($tipePengguna, $supervisorId, $tanggal);

    if($kehadiran->getWaktuMasuk() != '' && $kehadiran->getWaktuPulang() != '')
    {
        // sudah lengkap
        $jenisKehadiran = "";
    }
    else if($kehadiran->getWaktuMasuk() == '' && $kehadiran->getWaktuPulang() != '')
    {
        // sudah absen pulang
        // belum absen masuk
        $jenisKehadiran = "M";
    }
    else if($kehadiran->getWaktuMasuk() != '' && $kehadiran->getWaktuPulang() == '')
    {
        // sudah absen masuk
        // belum absen pulang
        $jenisKehadiran = "P";
    }
}
catch(Exception $e)
{
}
?>
<link rel="stylesheet" href="lib.assets/css/kehadiran.min.css">
<script src="lib.assets/js/kehadiran.min.js"></script>


<form action="" method="post" id="form-kehadiran">
    <div class="selfie-container">
        <div class="selfie-inner">
            <div id="videoContainer">
                <video id="video" autoplay></video>
                <canvas id="overlay"></canvas>
            </div>
            <canvas id="croppedCanvas" width="320" height="426"></canvas>
        </div>
    </div>

    <div class="button-container">
        <div class="lokasi-kehadiran">
            <select name="jenis_kehadiran" class="form-control">
                <option value="">Jenis Kehadiran</option>
                <option value="M"<?php echo $jenisKehadiran == 'M' ? ' selected=selected' : '';?>>Masuk</option>
                <option value="P"<?php echo $jenisKehadiran == 'P' ? ' selected=selected' : '';?>>Pulang</option>
            </select>
        <select name="lokasi_id" class="form-control">
            <option value=""><?php echo $appLanguage->getSellectAttendanceLocation();?></option>
            <?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiKehadiran(null, $database), 
            PicoSpecification::getInstance()
                ->addAnd(new PicoPredicate(Field::of()->aktif, true))
                ->addAnd(new PicoPredicate(Field::of()->draft, false)), 
            PicoSortable::getInstance()
                ->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
                ->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
            Field::of()->lokasiKehadiranId, Field::of()->nama)
            ; ?>
        </select>
        <textarea name="aktivitas" class="form-control"<?php echo $jenisKehadiran == 'P' ? ' style="display:block"' : '';?>></textarea>

    </div>
    <input type="hidden" name="alamat" value="">
    <input type="hidden" name="latitude" value="">
    <input type="hidden" name="longitude" value="">
    <input type="hidden" name="tanggal" value="<?php echo $inputGet->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS);?>">
    <button type="button" class="btn btn-sm btn-primary" id="detecLocation" onclick="triggerDetectLocation()"><?php echo $appLanguage->getButtonDetectLocation();?></button>
    <button type="button" class="btn btn-sm btn-primary" id="captureButton" disabled><?php echo $appLanguage->getButtonCaptureImage();?></button>
    <button type="button" class="btn btn-sm btn-primary" id="clearButton" disabled><?php echo $appLanguage->getButtonClearImage();?></button>
    <button type="button" class="btn btn-sm btn-primary" id="uploadButton" name="user_action" value="create" disabled><?php echo $appLanguage->getButtonUploadImage();?></button>

    <div id="progressContainer" style="display:none;">
        <progress id="progressBar" value="0" max="100"></progress>
        <span id="progressPercent">0%</span>
    </div>

    </div>
    <canvas id="sourceCanvas" width="320" height="426" style="display: none;"></canvas>
</form>
<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
?>
