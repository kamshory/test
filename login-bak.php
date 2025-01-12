<?php

use MagicObject\Request\InputPost;
use Sipro\Entity\Data\Supervisor;

require_once __DIR__ . "/inc.app/default.php";
require_once __DIR__ . "/inc.app/session.php";

$inputPost = new InputPost();

$currentSupervisor = null;
if($inputPost->getPassword() != null && $inputPost->getUsername() != null)
{
    try
    {
        $password1 = sha1(trim($inputPost->getPassword()));
        $password2 = sha1($password1);
        $username = trim($inputPost->getUsername());

        $currentSupervisor = new Supervisor(null, $database);
        $currentSupervisor->findOneByUsernameAndPasswordAndActiveAndBlocked($username, $password2, true, false);

        $sessions->supervisorPassword = $password1;
        $sessions->supervisorUsername = $username;
        header("Location: ./");
        exit();
        
    }
    catch(Exception $e)
    {
        // do nothing
        $currentSupervisor = null;
    }
}

if($currentSupervisor == null)
{
    require_once __DIR__ . "/inc.app/login-form.php";
    exit();
}