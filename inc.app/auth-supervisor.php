<?php

use MagicApp\AppLanguage;
use MagicObject\MagicObject;
use MagicObject\SetterGetter;
use MagicObject\Util\File\FileUtil;
use MagicObject\Util\PicoIniUtil;
use MagicObject\Util\PicoStringUtil;
use Sipro\Entity\Data\Supervisor;

require_once __DIR__."/app.php";
require_once __DIR__."/session.php";

$currentLoggedInSupervisor = new Supervisor(null, $database);
if(isset($sessions->supervisorUsername) && isset($sessions->supervisorPassword))
{
    try
    { 
        $currentLoggedInSupervisor->findOneByUsernameAndPasswordAndActiveAndBlocked($sessions->supervisorUsername, sha1($sessions->supervisorPassword), true, false);
    }
    catch(Exception $e)
    {
        require_once __DIR__ . "/default-supervisor.php";
        require_once __DIR__ . "/login-form-supervisor.php";
        exit();
    }

    $currentLoggedInSupervisor->setUserId($currentLoggedInSupervisor->getSupervisorId());

    $appLanguage = new AppLanguage(
        $appConfig,
        $currentLoggedInSupervisor->getLanguageId(),
        function($var, $value)
        {
            $inputSource = dirname(__DIR__) . "/inc.lang/source/app.ini";
            $inputSource = FileUtil::fixFilePath($inputSource);

            if(!file_exists(dirname($inputSource)))
            {
                mkdir(dirname($inputSource), 0755, true);
            }
            $sourceData = null;
            if(file_exists($inputSource) && filesize($inputSource) > 3)
            {
                $sourceData = PicoIniUtil::parseIniFile($inputSource);
            }
            if($sourceData == null || $sourceData === false)
            {
                $sourceData = array();
            }   
            $output = array_merge($sourceData, array(PicoStringUtil::snakeize($var) => $value));
            PicoIniUtil::writeIniFile($output, $inputSource);
        }
    );

    $currentAction = new SetterGetter();
    $currentAction->setTime(date('Y-m-d H:i:s'));
    $currentAction->setIp($_SERVER['REMOTE_ADDR']);
    $currentAction->setUserId($currentLoggedInSupervisor->getSupervisorId());
    $currentAction->setSupervisorId($currentLoggedInSupervisor->getSupervisorId());

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $currentAction->setRequestViaAjax(true);
    } 
    else
    {
        $currentAction->setRequestViaAjax(false);
    }
}
else
{
    require_once __DIR__ . "/default-supervisor.php";
    require_once __DIR__ . "/login-form-supervisor.php";
    exit();
}
$perms = new stdClass;
$perms->allowedList = true;
$perms->allowedDetail = true;
$perms->allowedCreate = true;
$perms->allowedUpdate = true;
$perms->allowedDelete = true;
$perms->allowedApprove = true;
$perms->allowedSortOrder = true;
$perms->allowedBatchAction = true;

$userPermission = new MagicObject($perms);
$currentUser = $currentLoggedInSupervisor;

if($currentLoggedInSupervisor->getLanguageId() == 'id')
{
    $dateTimeTranslation = array(
        // Bulan dalam format lengkap
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',

        // Bulan dalam format singkat
        'Jan' => 'Jan',
        'Feb' => 'Feb',
        'Mar' => 'Mar',
        'Apr' => 'Apr',
        'May' => 'Mei',
        'Jun' => 'Jun',
        'Jul' => 'Jul',
        'Aug' => 'Ags',
        'Sep' => 'Sep',
        'Oct' => 'Okt',
        'Nov' => 'Nov',
        'Dec' => 'Des',

        // Hari dalam format lengkap
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',

        // Hari dalam format singkat
        'Sun' => 'Min',
        'Mon' => 'Sen',
        'Tue' => 'Sel',
        'Wed' => 'Rab',
        'Thu' => 'Kam',
        'Fri' => 'Jum',
        'Sat' => 'Sab'
    );
}
else
{
    $dateTimeTranslation = null;
}