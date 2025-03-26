<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\File\PicoUploadFile;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\SetterGetter;
use Sipro\Entity\Data\Kehadiran;
use Sipro\Util\CalendarUtil;
use Sipro\Util\DateUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "Kehadiran";
$currentModule = new PicoModule($appConfig, $database, null, "/", "kehadiran", $appLanguage->getKehadiran());

if($inputPost->getUserAction() == UserAction::CREATE && $inputPost->getJenisKehadiran() != "")
{
    
	$kehadiran = new Kehadiran(null, $database);
    
    $tipePengguna = "supervisor";
    $tanggal = $inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
    $periodeId = substr(str_replace("-", "", $tanggal), 0, 6);
    $supervisorId = $currentLoggedInSupervisor->getSupervisorId();

    $uplodPath = $appConfig->getUpload();
    $uploadAttendance = $uplodPath->getAttendance();
    $uploadAttendanceSupervisor = $uploadAttendance . "/supervisor/".$supervisorId;

    if(!file_exists($uploadAttendanceSupervisor))
    {
        mkdir($uploadAttendanceSupervisor, 0755, true);
    }

    $selfieFilePath = $uploadAttendanceSupervisor."/".$tanggal."_".$inputPost->getJenisKehadiran().".jpg";

    $cdn = $appConfig->getCdn();
    $cdnAttendance = $cdn->getAttendance();

    $fotoSelfie = str_replace("\\", "/", str_replace($uploadAttendance, $cdnAttendance, $selfieFilePath));

    $files = new PicoUploadFile();
    foreach($files->file->getAll() as $fileItem)
    {
        $temporaryName = $fileItem->getTmpName();
        $name = $fileItem->getName();
        $size = $fileItem->getSize();
        move_uploaded_file($temporaryName, $fotoSelfie);
    }

    try
    {
        // Ambil record yang sudah ada
        $kehadiran->findOneByTipePenggunaAndSupervisorIdAndTanggal($tipePengguna, $supervisorId, $tanggal);
    }
    catch(Exception $e)
    {
        $kehadiran = new Kehadiran(null, $database);
    }

	$kehadiran->setTipePengguna($tipePengguna);
	$kehadiran->setSupervisorId($supervisorId);
	$kehadiran->setTanggal($tanggal);
	$kehadiran->setPeriodeId($periodeId);

    $aktivitas = nl2br($inputPost->getAktivitas(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true), true);

    if($inputPost->getJenisKehadiran() == "M")
    {
        // Masukkan ke bagian masuk
        $kehadiran->setWaktuMasuk($currentAction->getTime());
        $kehadiran->setLokasiMasukId($inputPost->getLokasiId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
        $kehadiran->setFotoMasuk($fotoSelfie);
        $kehadiran->setAlamatMasuk($inputPost->getAlamat(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
        $kehadiran->setLatitudeMasuk($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
        $kehadiran->setLongitudeMasuk($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
        $kehadiran->setIpMasuk($currentAction->getIp());
    }
    else
    {
        // Masukkan ke bagian pulang
        $kehadiran->setWaktuPulang($currentAction->getTime());
        $kehadiran->setLokasiPulangId($inputPost->getLokasiId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
        $kehadiran->setFotoPulang($fotoSelfie);
        $kehadiran->setAlamatPulang($inputPost->getAlamat(PicoFilterConstant::FILTER_DEFAULT, false, false, true));
        $kehadiran->setLatitudePulang($inputPost->getLatitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
        $kehadiran->setLongitudePulang($inputPost->getLongitude(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
        $kehadiran->setIpPulang($currentAction->getIp());
        $kehadiran->setAktivitas($inputPost->getAktivitas(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
    }

	$kehadiran->setAktif(true);
	$kehadiran->setWaktuBuat($currentAction->getTime());
	$kehadiran->setIpBuat($currentAction->getIp());
	$kehadiran->setWaktuUbah($currentAction->getTime());
	$kehadiran->setIpUbah($currentAction->getIp());
	try
	{
		$kehadiran->save();
		$newId = $kehadiran->getKehadiranId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kehadiran_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}
else if($inputPost->getUserAction() == UserAction::UPDATE)
{
	$kehadiran = new Kehadiran(null, $database);
	$kehadiran->setTipePengguna($inputPost->getTipePengguna(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAdminId($inputPost->getUserId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kehadiran->setSupervisorId($inputPost->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	$kehadiran->setTanggal($inputPost->getTanggal(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setPeriodeId($inputPost->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setWaktuMasuk($inputPost->getWaktuMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLokasiMasukId($inputPost->getLokasiMasukId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setFotoMasuk($inputPost->getFotoMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAlamatMasuk($inputPost->getAlamatMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLatitudeMasuk($inputPost->getLatitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setLongitudeMasuk($inputPost->getLongitudeMasuk(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setIpMasuk($inputPost->getIpMasuk(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setWaktuPulang($inputPost->getWaktuPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLokasiPulangId($inputPost->getLokasiPulangId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setFotoPulang($inputPost->getFotoPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAlamatPulang($inputPost->getAlamatPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setLatitudePulang($inputPost->getLatitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setLongitudePulang($inputPost->getLongitudePulang(PicoFilterConstant::FILTER_SANITIZE_NUMBER_FLOAT, false, false, true));
	$kehadiran->setIpPulang($inputPost->getIpPulang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAktivitas($inputPost->getAktivitas(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
	$kehadiran->setAktif($inputPost->getAktif(PicoFilterConstant::FILTER_SANITIZE_BOOL, false, false, true));
	$kehadiran->setAdminUbah($currentAction->getUserId());
	$kehadiran->setWaktuUbah($currentAction->getTime());
	$kehadiran->setIpUbah($currentAction->getIp());
	$kehadiran->setKehadiranId($inputPost->getKehadiranId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
	try
	{
		$kehadiran->update();
		$newId = $kehadiran->getKehadiranId();
		$currentModule->redirectTo(UserAction::DETAIL, Field::of()->kehadiran_id, $newId);
	}
	catch(Exception $e)
	{
		$currentModule->redirectToItself();
	}
}

if($inputGet->getUserAction() == UserAction::CREATE)
{
    require_once __DIR__ . "/selfie.php";
    exit();
}
require_once __DIR__ . "/inc.app/header-supervisor.php";
    $inputGet = new InputGet();
    
    $supervisorId = $currentLoggedInSupervisor->getSupervisorId();
    $periodeOri = date('Y-m');
    $periode = $inputGet->getPeriode();
    if(empty($periode))
    {
        $periode = date('Y-m');
    }
    $periode2 = strtotime($periode."-15");

    $sebelumnya = date('Y-m', $periode2 - (31 * 86400));
    $sesudahnya = date('Y-m', $periode2 + (31 * 86400));

    $periodeArr = explode('-', $periode);

    $calendar = new CalendarUtil(intval($periodeArr[0]), intval($periodeArr[1]), 0, true);
    
    $cal = $calendar->getCalendar();
    $calInline = $calendar->getCalendarInline();
    
    $startDate = $calendar->getStartDate();
    $endDate = $calendar->getEndDate();
    
    $proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
    if(isset($proyekId) && !empty($proyekId))
    {
        $proyekId = intval($proyekId);
    }

    // Additional filter here

    $specs = PicoSpecification::getInstance()
    ->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $supervisorId))
    ->addAnd(PicoPredicate::getInstance()->equals('tipePengguna', 'supervisor'))
    ->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals('tanggal', $startDate.' 00:00:00'))
    ->addAnd(PicoPredicate::getInstance()->lessThan('tanggal', $endDate.' 23:59:59'))
    ;
    
    $kehadiranFinder = new Kehadiran(null, $database);
    
    $absensi = array();
    $startTime = strtotime($startDate);
    $endTime = strtotime($endDate);
    
    $class = 'kosong';
    for($i = $startTime; $i<$endTime; $i+=86400)
    {
        $tanggal = date('Y-m-d', $i);
        $absensi[$tanggal] = (new SetterGetter())
            ->setBukuHarianId(null)
            ->setTanggal($tanggal)
            ->setClass($class)
            ;
    }
    try
    {
        $pageData = $kehadiranFinder->findAll($specs);
        foreach($pageData->getResult() as $hekadiran)
        {
            $tanggal = $hekadiran->getTanggal();

            if($hekadiran->getWaktuMasuk() != '' && $hekadiran->getWaktuPulang() != '')
            {
                $class = 'kehadiran-lengkap';
            }
            else if(($hekadiran->getWaktuMasuk() != '' && $hekadiran->getWaktuPulang() == '') || ($hekadiran->getWaktuMasuk() == '' && $hekadiran->getWaktuPulang() != ''))
            {
                $class = 'kehadiran-parsial';
            }
            else
            {
                $class = 'kehadiran-kosong';
            }

            $class = $hekadiran->getWaktuMasuk() != '' && $hekadiran->getWaktuPulang() != '' ? 'kehadiran-lengkap':'belum-acc';
            $absensi[$tanggal] = (new SetterGetter())
                ->setBukuHarianId($hekadiran->getBukuHarianId())
                ->setTanggal($tanggal)
                ->setClass($class)
                ;
        }
    }
    catch(Exception $e)
    {
        // 
    }
    
    ?>
    <style>
        .calendar{
            position: relative;
        }
        .jambi-wrapper .calendar .filter-section .filter-control .form-control
        {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        .calendar .btn{
            padding: 2px 8px;
        }
    </style>
    <div class="calendar">

        <table width="100%">
            <thead>
                <tr>
                    <td style="text-align: left;">
                    <button type="button" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>?periode=<?php echo $sebelumnya;?>'"><i class="fa-solid fa-chevron-left"></i></button>
                    </td>
                    <td colspan="5" style="font-size: 1rem; text-align: center;"><?php echo DateUtil::translateDate($appLanguage, date('F Y', $periode2));?></td>
                    <td style="text-align: right;">
                    <button type="button" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>?periode=<?php echo $sesudahnya;?>'"<?php echo $periode == $periodeOri ? ' disabled':'';?>><i class="fa-solid fa-chevron-right"></i></button>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Min</td>
                    <td>Sen</td>
                    <td>Sel</td>
                    <td>Rab</td>
                    <td>Kam</td>
                    <td>Jum</td>
                    <td>Sab</td>
                </tr>
                <?php
                foreach($cal as $row)
                {
                    ?>
                    <tr>
                    <?php
                    foreach($row as $col)
                    {
                        ?>
                        <td width="14%">
                            <?php
                            if($col['print'])
                            {
                                $class = $col['class'];
                                $tanggal = $col['date'];
                                $class2 = isset($absensi[$tanggal]) ? $absensi[$tanggal]->getClass() : "";
                                $class = $class.' '.$class2;
                            ?>
                            <button data-tanggal="<?php echo $tanggal;?>" class="calendar-button buku-harian-button <?php echo $class;?>" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']);?>?user_action=create&tanggal=<?php echo $tanggal;?>'"><?php echo $col['day'];?></button>
                            <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
