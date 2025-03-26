<?php

use MagicApp\AppEntityLanguage;
use MagicApp\UserAction;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\BukuDireksi;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$bukuDireksi = new BukuDireksi(null, $database);

function convertMinutesToTime($minutes) {
    // If the input is 0 minutes, return an empty string
    if ($minutes == 0) {
        return "";
    }

    // Calculate the number of days
    $days = floor($minutes / 1440); // 1440 minutes in a day

    // Calculate the remaining minutes after calculating days
    $remainingMinutes = $minutes % 1440;

    // Calculate the number of hours
    $hours = floor($remainingMinutes / 60); // 60 minutes in an hour

    // Calculate the remaining minutes after calculating hours
    $remainingMinutes = $remainingMinutes % 60;

    // Construct the result string
    $result = "";
    if ($days > 0) {
        $result .= $days . " hari";
    }
    if ($hours > 0) {
        if ($result != "") $result .= ", "; // Add a comma if there were previous days
        $result .= $hours . " jam";
    }
    if ($remainingMinutes > 0 || $result == "") {
        if ($result != "") $result .= ", "; // Add a comma if there were previous days/hours
        $result .= $remainingMinutes . " menit";
    }

    // Return the final formatted result
    return $result;
}

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$bukuDireksi = new BukuDireksi(null, $database);
    $appEntityLanguage = new AppEntityLanguage(new BukuDireksi(), $appConfig, $currentUser->getLanguageId());

    // Define map here
    $mapForDiperiksa = array(
        "1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
        "0" => array("value" => "0", "label" => "Belum", "default" => "false")
    );
    $mapForStatus = array(
        "0" => array("value" => "0", "label" => "Baru", "default" => "false"),
        "1" => array("value" => "1", "label" => "Dalam Proses", "default" => "false"),
        "2" => array("value" => "2", "label" => "Selesai", "default" => "false"),
        "3" => array("value" => "3", "label" => "Dibatalkan", "default" => "false")
    );
    $mapForSelesai = array(
        "1" => array("value" => "1", "label" => "Sudah", "default" => "false"),
        "0" => array("value" => "0", "label" => "Belum", "default" => "false")
    );
    
	try{
		$subqueryMap = array(
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		)
		);
		$bukuDireksi->findOneWithPrimaryKeyValue($inputGet->getBukuDireksiId(), $subqueryMap);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $appLanguage->getBukuDireksi();?></title>
    <style>
        body
        {
            font-family: Palatino, "Palatino Linotype", "Book Antiqua", serif;
            font-size: 14px;
        }
        .all > h1, .all > h2, .all > h3, .all > h4 {
            text-align: center;
        }
        .all{
            width: 100%;
            max-width: 800px;
            margin: auto;
        }
        .responsive-two-cols tbody tr td{
            padding: 4px 0;
            vertical-align: text-top;
        }
        .responsive-two-cols tbody tr td:nth-child(1){
            width: 200px;

        }
    </style>
</head>
<body>
    <div class="all">
        <h1><?php echo $appLanguage->getBukuDireksi();?></h1>
        <table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <td><?php echo $appEntityLanguage->getNama();?></td>
                <td><?php echo $bukuDireksi->getNama();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getProyek();?></td>
                <td><?php echo $bukuDireksi->issetProyek() ? $bukuDireksi->getProyek()->getNama() : "";?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getSupervisor();?></td>
                <td><?php echo $bukuDireksi->issetSupervisor() ? $bukuDireksi->getSupervisor()->getNama() : "";?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getTanggal();?></td>
                <td><?php echo $bukuDireksi->getTanggal();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getPermasalahan();?></td>
                <td><?php echo $bukuDireksi->getPermasalahan();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getPerkiraanLamaPenyelesaian();?></td>
                <td><?php echo convertMinutesToTime($bukuDireksi->getPerkiraanLamaPenyelesaian());?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getDiperiksa();?></td>
                <td><?php echo isset($mapForDiperiksa) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]) && isset($mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"]) ? $mapForDiperiksa[$bukuDireksi->getDiperiksa()]["label"] : "";?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getWaktuMulai();?></td>
                <td><?php echo $bukuDireksi->getWaktuMulai();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getPenyelesaian();?></td>
                <td><?php echo $bukuDireksi->getPenyelesaian();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getStatus();?></td>
                <td><?php echo isset($mapForStatus) && isset($mapForStatus[$bukuDireksi->getStatus()]) && isset($mapForStatus[$bukuDireksi->getStatus()]["label"]) ? $mapForStatus[$bukuDireksi->getStatus()]["label"] : "";?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getProgres();?></td>
                <td><?php echo $bukuDireksi->getProgres();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getSelesai();?></td>
                <td><?php echo isset($mapForSelesai) && isset($mapForSelesai[$bukuDireksi->getSelesai()]) && isset($mapForSelesai[$bukuDireksi->getSelesai()]["label"]) ? $mapForSelesai[$bukuDireksi->getSelesai()]["label"] : "";?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getWaktuSelesai();?></td>
                <td><?php echo $bukuDireksi->getWaktuSelesai();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getLamaPenyelesaian();?></td>
                <td><?php echo convertMinutesToTime($bukuDireksi->getLamaPenyelesaian());?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getNamaDireksi();?></td>
                <td><?php echo $bukuDireksi->getNamaDireksi();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getJabatanDireksi();?></td>
                <td><?php echo $bukuDireksi->getJabatanDireksi();?></td>
            </tr>
            <tr>
                <td><?php echo $appEntityLanguage->getKomentarDireksi();?></td>
                <td><?php echo $bukuDireksi->getKomentarDireksi();?></td>
            </tr>

        </tbody>
    </table>
    </div>
</body>
</html>
    <?php
    }
    catch(Exception $e)
    {

    }
}