<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\File\PicoUploadFile;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$files = new PicoUploadFile();

if($files->getFile() != null)
{ 
    $tanggal = date("Ymd");
    $adminId = $currentUser->getAdminId();
    $uplodPath = $appConfig->getUpload();
    $uploadSignature = $uplodPath->getSignature();
    $uploadSignature = $uploadSignature . "/".$adminId;
    if(!file_exists($uploadSignature))
    {
        mkdir($uploadSignature, 0755, true);
    }
    $signatureFilePath = $uploadSignature."/signature.png";
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