<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\File\PicoUploadFile;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$files = new PicoUploadFile();

if($files->getFile() != null)
{ 
    $tanggal = date("Ymd");
    $supervisorId = $currentLoggedInSupervisor->getSupervisorId();
    $uplodPath = $appConfig->getUpload();
    $uploadSignature = $uplodPath->getSignature();
    $uploadSignatureSupervisor = $uploadSignature . "/supervisor/".$supervisorId;
    if(!file_exists($uploadSignatureSupervisor))
    {
        mkdir($uploadSignatureSupervisor, 0755, true);
    }
    $signatureFilePath = $uploadSignatureSupervisor."/".$tanggal.".png";
    $cdn = $appConfig->getCdn();
    $cdnSignature = $cdn->getSignature();
    $signatureFile = str_replace("\\", "/", str_replace($uploadSignature, $cdnSignature, $signatureFilePath));
    foreach($files->file->getAll() as $fileItem)
    {
        $temporaryName = $fileItem->getTmpName();
        $name = $fileItem->getName();
        $size = $fileItem->getSize();
        if(file_exists($temporaryName))
        {
            move_uploaded_file($temporaryName, $signatureFilePath);
        }
    }
}