<?php

use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\AcuanPengawasan;
use Sipro\Entity\Data\AcuanPengawasanBillOfQuantity;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputGet = new InputGet();

$specs = PicoSpecification::getInstance()
->addAnd([Field::of()->proyekId, $inputGet->getProyekId()])
;

$sorts = PicoSortable::getInstance()->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC));

$boqs = $inputGet->getBillOfQuantityId();

if(isset($boqs) && is_array($boqs))
{
    $specs->addAnd(PicoPredicate::getInstance()->in(Field::of()->billOfQuantityId, $boqs));
    $acuanPengawasan = new AcuanPengawasanBillOfQuantity(null, $database);
    try
    {
        $pageData = $acuanPengawasan->findAll($specs, null, $sorts);
        ?>
        <ul class="list-in-cell type-check">
        <?php
        foreach($pageData->getResult() as $row)
        {
            $acuanPengawasan = $row->issetAcuanPengawasan() ? $row->getAcuanPengawasan() : new AcuanPengawasan();
            ?>
            <li><label><input type="checkbox" name="acuan_pengawasan_id[]" value="<?php echo $row->getAcuanPengawasanId();?>"> 
            <?php echo $acuanPengawasan->getNomor();?> - 
            <?php echo $acuanPengawasan->issetJenisHirarkiKontrak() ? $acuanPengawasan->getJenisHirarkiKontrak()->getNama() : "";?> - 
            <?php echo $acuanPengawasan->getNama();?>
            </label></li>
            <?php
        }
        ?>
        </ul>
        <?php
    }
    catch(Exception $e)
    {
        // do nothing
    }
}