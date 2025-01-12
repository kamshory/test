<?php

use MagicObject\Request\InputGet;
use Sipro\Entity\Data\KonfirmasiPendaftaranSupervisor;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$success = false;
if($inputGet->getHash() != "")
{
    $now = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $konfirmasiPendaftaranSupervisor = new KonfirmasiPendaftaranSupervisor(null, $database);
    try
    {
        $hash = hash("sha512", $inputGet->getHash());
        $konfirmasiPendaftaranSupervisor->findOneByKodeKonfirmasiEmailAndKonfirmasi($hash, false);
        if($konfirmasiPendaftaranSupervisor->issetSupervisor())
        {
            $supervisor = $konfirmasiPendaftaranSupervisor->getSupervisor();
            $supervisor->setKonfirmasiEmail(true);
            $supervisor->update();

            $konfirmasiPendaftaranSupervisor->setKonfirmasi(true);
            $konfirmasiPendaftaranSupervisor->setWaktuBuat($now);
            $konfirmasiPendaftaranSupervisor->setIpBuat($ip);
            $konfirmasiPendaftaranSupervisor->setWaktuUbah($now);
            $konfirmasiPendaftaranSupervisor->setIpUbah($ip);
            $konfirmasiPendaftaranSupervisor->update();
            $success = true;
        }
    }
    catch(Exception $e)
    {
        // do nothing
    }
}

if($success)
{
    $template = file_get_contents(__DIR__ . "/template/konfirmasi-email.html");
    echo file_get_contents($template);
    exit();
}