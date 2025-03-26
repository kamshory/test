<?php

use MagicApp\AppLanguage;
use MagicApp\AppUser;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\SetterGetter;
use MagicObject\Util\File\FileUtil;
use MagicObject\Util\PicoIniUtil;
use MagicObject\Util\PicoStringUtil;
use Sipro\Entity\App\AppModuleImpl;
use Sipro\Entity\App\AppUserImpl;
use Sipro\Entity\App\AppUserRoleImpl;

require_once __DIR__."/app.php";
require_once __DIR__."/session.php";

$appUserImpl = new AppUserImpl(null, $database);

if(isset($sessions->adminUsername) && isset($sessions->adminPassword))
{
    $appUserImpl = new AppUserImpl(null, $database);
    try
    {
        // Check if the user is logged in
        $specsLogin = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->like('username', $sessions->adminUsername))
            ->addAnd(PicoPredicate::getInstance()->equals('password', sha1($sessions->adminPassword)))
            ->addAnd(PicoPredicate::getInstance()->equals('aktif', true))
            ->addAnd(PicoPredicate::getInstance()->equals('blokir', false))
            ;
        $appUserImpl->findOne($specsLogin);
    }
    catch(Exception $e)
    {
        require_once __DIR__ . "/default.php";
        require_once __DIR__ . "/login-form.php";
        exit();
    }

    $appUserRoles = new PicoPageData(array(), 0);

    $currentUser = new AppUser($appUserImpl);
    $currentUser->setLanguageId($currentUser->getLangId());
    $appLanguage = new AppLanguage(
        $appConfig,
        $currentUser->getLangId(),
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
    $currentAction->setAdminId($currentUser->getAdminId());
    $currentAction->setUserId($currentUser->getAdminId());
    $currentAction->setTime(date('Y-m-d H:i:s'));
    $currentAction->setIp($_SERVER['REMOTE_ADDR']);
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $currentAction->setRequestViaAjax(true);
    } 
    else
    {
        $currentAction->setRequestViaAjax(false);
    }

    $appUserRole = new AppUserRoleImpl(null, $database);
    $appModule = new AppModuleImpl(null, $database);
    $appUserRoleImpl = new AppUserRoleImpl(null, $database);
}
else
{
    require_once __DIR__ . "/default.php";
    require_once __DIR__ . "/login-form.php";
    exit();
}

if($currentUser->getLanguageId() == 'id')
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