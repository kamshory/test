<?php

use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\Supervisor;

require_once dirname(__DIR__) . "/inc.app/app.php";
$found = false;
try
{
    $inputGet = new InputGet();
    $username = $inputGet->getUsername();
    $supervisor = new Supervisor(null, $database);
    $specs = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->like(Field::of()->username, $username));
    $supervisor->findOne($specs);
    $found = $supervisor->issetSupervisorId();
}
catch(Exception $e)
{
    // do nothing
}

header("Content-type: application/json");
echo json_encode(array(
    'found'=>$found
));