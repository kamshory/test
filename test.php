<?php

use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Util\BoqUtil;

require_once __DIR__ . "/inc.app/app.php";

$boq = new BillOfQuantity(null, $database);

try
{
    $proyekId = 168;
    $parentId = 66;
    $specs = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $proyekId))
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
    ;
    $pageData = $boq->findAll($specs);
    $boqUtil = new BoqUtil($pageData->getResult(), 0, false);
    echo '<select>'.$boqUtil->selectOption(79).'</select>';
}
catch(Exception $e)
{
    // do nothing
}


?>
<select class="form-control" name="bill_of_quantity_id" id="bill_of_quantity_id">
    <option value=""></option>
    <?php echo AppFormBuilder::getInstance()->createSelectOption(new BillOfQuantity(null, $database), 
    PicoSpecification::getInstance()
        ->addAnd(new PicoPredicate(Field::of()->proyekId, 60))
        ->addAnd(new PicoPredicate(Field::of()->parentId, 0))
        ->addAnd(new PicoPredicate(Field::of()->aktif, true)), 
    PicoSortable::getInstance()
        ->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
        ->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
    Field::of()->proyekId, Field::of()->nama)
    ; ?>
    ?>
</select>