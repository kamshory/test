<?php

use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Util\BoqUtil;


require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$inputGet = new InputGet();

$boq = new BillOfQuantity(null, $database);
try
{
    $proyekId = $inputGet->getProyekId();
    $parentId = $inputGet->getParentId();
    $specs = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $proyekId))
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
    ;
    $pageData = $boq->findAll($specs);
    $boqUtil = new BoqUtil($pageData->getResult(), $parentId, true);
    echo $boqUtil->selectOption(null, true);
}
catch(Exception $e)
{
    echo $e->getMessage();
    // do nothing
}
