<?php

use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputPost;
use MagicObject\SecretObject;
use Sipro\Entity\Data\CatatanSalahLogin;
use Sipro\Entity\Data\Supervisor;

require_once __DIR__ . "/inc.app/default.php";
require_once __DIR__ . "/inc.app/session.php";

$inputPost = new InputPost();

$currentSupervisor = null;

if($inputPost->getPassword() != null && $inputPost->getUsername() != null)
{
    try
    {
        $loginRule = $appConfig->getLoginRule();
        if($loginRule == null)
        {
            $loginRule = new SecretObject();
            $loginRule->setInvalidLoginTimeRange(30);
            $loginRule->setInvalidLoginMaximumCount(5);
        }
        
        $now = date('Y-m-d H:i:s');
        $thresholdInvalidPassword = date('Y-m-d H:i:s', time() - ($loginRule->getInvalidLoginTimeRange() * 60));
        
        $password1 = sha1(trim($inputPost->getPassword()));
        $password2 = sha1($password1);
        $username = trim($inputPost->getUsername());
        
        $catatanSalahLogin = new CatatanSalahLogin(null, $database);
        
        $specs = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals('grupPengguna', 'supervisor'))
            ->addAnd(PicoPredicate::getInstance()->equals('supervisor.username', $username))
            ->addAnd(PicoPredicate::getInstance()->greaterThan('waktuBuat', $thresholdInvalidPassword))
            ;
        $sorts = PicoSortable::getInstance()
            ->addSortable(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_DESC))
        ;

        try
        {
            $pageDataInvalidLogin = $catatanSalahLogin->findAll($specs, null, $sorts);
            if($pageDataInvalidLogin->getTotalResult() > $loginRule->getInvalidLoginMaximumCount())
            {
                // maximum invalid login exeeded
                $sessions->adminUsername = $username;
                
                $waiting = $appConfig->getInvalidLoginTimeRange() * 60;
                // dapatkan data ke n
                $result = $pageDataInvalidLogin->getResult();
                if($loginRule->getInvalidLoginMaximumCount() > 0 && isset($result[$loginRule->getInvalidLoginMaximumCount() - 1]) && $result[$loginRule->getInvalidLoginMaximumCount() - 1] instanceof CatatanSalahLogin)
                {
                    $ndata = $result[$loginRule->getInvalidLoginMaximumCount() - 1];
                    $tc = strtotime($ndata->getWaktuBuat());
                    $waiting = time() - $tc;
                }
                
                header("Location: ./batas-percobaan-login.php?waiting=".base64_encode($waiting.""));
                exit();
            }
        }
        catch(Exception $e)
        {
            // ok
        }
        $currentSupervisor = new Supervisor(null, $database);
        $currentSupervisor->findOneByUsernameAndAktifAndBlokir($username, true, false);

        if(!$currentSupervisor->equalsPassword($password2))
        {
            // invalid password
            $catatanSalahLoginCreate = new CatatanSalahLogin(null, $database);
            $catatanSalahLoginCreate
                ->setGrupPengguna('supervisor')
                ->setSupervisorId($currentSupervisor->getSupervisorId())
                ->setWaktuBuat($now)
                ->insert();
        }
        else if($currentSupervisor->getBlokir())
        {
            // user blocked
            $sessions->supervisorUsername = $username;
            header("Location: ./terblokir.php");
            exit();
        }
        else 
        {
            $sessions->supervisorUsername = $username;
            $sessions->supervisorPassword = $password1;
            header("Location: ./");
            exit();
        }
        
    }
    catch(Exception $e)
    {
        $currentSupervisor = null;
    }
}

if($currentSupervisor == null)
{
    require_once __DIR__ . "/inc.app/login-form.php";
    exit();
}