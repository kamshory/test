<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicObject\File\PicoUploadFile;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use Sipro\Entity\Data\SupervisorProfilePicture;
use Sipro\ImageUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "foto-profil";
$currentModule = new PicoModule($appConfig, $database, null, "/", "foto-profil", $appLanguage->getFotoPofil());


$files = new PicoUploadFile();
if(isset($files->file))
{
    $supervisorId = $currentLoggedInSupervisor->getSupervisorId();

    $uplodPath = $appConfig->getUpload();
    $uploadProfilePicture = $uplodPath->getProfilePicture();
    $uploadProfilePictureSupervisor = $uploadProfilePicture . "/supervisor/".$supervisorId;

    if(!file_exists($uploadProfilePictureSupervisor))
    {
        mkdir($uploadProfilePictureSupervisor, 0755, true);
    }

    $selfieFilePath = $uploadProfilePictureSupervisor."/320.jpg";

    $cdn = $appConfig->getCdn();
    $cdnProfilePicture = $cdn->getProfilePicture();

    $fotoSelfie = str_replace("\\", "/", str_replace($uploadProfilePicture, $cdnProfilePicture, $selfieFilePath));
    $fotoSelfie128 = str_replace("320.jpg", "128.jpg", $fotoSelfie);

    foreach($files->file->getAll() as $fileItem)
    {
        $temporaryName = $fileItem->getTmpName();
        $name = $fileItem->getName();
        $size = $fileItem->getSize();
        move_uploaded_file($temporaryName, $fotoSelfie);

        ImageUtil::imageResizeMax($fotoSelfie, $fotoSelfie128, 128, 128, true, 'jpeg', 80);
        $ppInfo = new SupervisorProfilePicture(null, $database);
        $ppInfo->setSupervisorId($supervisorId)->setWaktuUbahFoto(date($currentAction->getTime()))->update();
    }
}


if($inputGet->getUserAction() == UserAction::UPDATE)
{
    require_once __DIR__ . "/inc.app/header-supervisor.php";
    ?>
<link rel="stylesheet" href="lib.assets/css/foto-profil.css">
<script src="lib.assets/js/foto-profil.js"></script>
<form action="" method="post" id="form-foto-rofil">
    <div class="selfie-container">
        <div class="selfie-inner">
            <div id="videoContainer">
                <video id="video" autoplay></video>
                <canvas id="overlay"></canvas>
            </div>
            <canvas id="croppedCanvas" width="320" height="320"></canvas>
        </div>
    </div>

    <div class="button-container">
        <button type="button" class="btn btn-sm btn-primary" id="captureButton" disabled><?php echo $appLanguage->getButtonCaptureImage();?></button>
        <button type="button" class="btn btn-sm btn-primary" id="clearButton" disabled><?php echo $appLanguage->getButtonClearImage();?></button>
        <button type="button" class="btn btn-sm btn-primary" id="uploadButton" name="user_action" value="create" disabled><?php echo $appLanguage->getButtonSave();?></button>
        <button type="button" class="btn btn-sm btn-primary" onclick="window.location='foto-profil.php';"><?php echo $appLanguage->getButtonCancel();?></button>
        <div id="progressContainer" style="display:none;">
            <progress id="progressBar" value="0" max="100"></progress>
            <span id="progressPercent">0%</span>
        </div>
    </div>
    <canvas id="sourceCanvas" width="320" height="320" style="display: none;"></canvas>
</form>
<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
else
{
    require_once __DIR__ . "/inc.app/header-supervisor.php";

    $supervisorId = $currentLoggedInSupervisor->getSupervisorId();

    $uplodPath = $appConfig->getUpload();
    $uploadProfilePicture = $uplodPath->getProfilePicture();
    $uploadProfilePictureSupervisor = $uploadProfilePicture . "/supervisor/".$supervisorId;
    $selfieFilePath = $uploadProfilePictureSupervisor."/320.jpg";

    $cdn = $appConfig->getCdn();
    $cdnProfilePicture = $cdn->getProfilePicture();

    $fotoSelfie = str_replace("\\", "/", str_replace($uploadProfilePicture, $cdnProfilePicture, $selfieFilePath));
    ?>
<link rel="stylesheet" href="lib.assets/css/foto-profil.css">
<form action="" method="post" id="form-foto-rofil">
    <div class="selfie-container">
        <div class="selfie-inner">
            <img alt="<?php echo $appLanguage->getProfilePicture();?>" src="<?php echo $fotoSelfie;?>?hash=<?php echo str_replace(array(' ', '-', ':'), '', $currentLoggedInSupervisor->getWaktuUbahFoto());?>" alt="<?php echo $appLanguage->getProfilePicture();?>">
        </div>
    </div>

    <div class="button-container">
        <button type="button" class="btn btn-sm btn-primary" onclick="window.location='?user_action=update';"><?php echo $appLanguage->getButtonUpdateProfilePicture();?></button>
    </div>
    <canvas id="sourceCanvas" width="320" height="320" style="display: none;"></canvas>
</form>
<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
?>
