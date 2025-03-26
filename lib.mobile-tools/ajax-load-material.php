<?php

use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\Material;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$inputGet = new InputGet();
$resourse_material = [];
$proyekId = $inputGet->getProyekId();
$specs4 = PicoSpecification::getInstance()->add(['aktif', true])->add('proyekId', $proyekId);
$sorts4 = PicoSortable::getInstance()->add(['nama', PicoSort::ORDER_TYPE_ASC]);
$resourse_material[] = '<option value="">'.$appLanguage->getLabelOptionSelectOne().'</option>';
try
{
    $material = new Material(null, $database);
    $pageData4 = $material->findAll($specs4, null, $sorts4);

    $categories = array();
    $uncategories = array();

    foreach($pageData4->getResult() as $row)
    {
        $group = $row->getKategoriMaterial()->getNama();
        if(!empty($group))
        {
            if(!isset($categories[$group]))
            {
                $categories[$group] = array();
            }
            $categories[$group][] = $row;
        }
        else
        {
            $uncategories[] = $row;
        }
    }

    if(!empty($categories))
    {
        foreach($categories as $group=>$rows)
        {
            $resourse_material[] = '<optgroup label="'.$group.'">';
            foreach($rows as $row)
            {
                $belumTerpasang = $row->getBelumTerpasang();

                $onsite = rtrim(sprintf("%f", $row->getOnsite()), ".0");
                $terpasang = rtrim(sprintf("%f", $row->getTerpasang()), ".0");
                $textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $row->getNama(), $row->getSatuan(), $onsite, $terpasang);
                $resourse_material[] = "\t".'<option value="'.$row->getMaterialId().'" data-max="'.$belumTerpasang.'">'.$textNode.'</option>';
            }
            $resourse_material[] = '</optgroup>';
        }
    }
    foreach($uncategories as $row)
    {
        $belumTerpasang = $row->getBelumTerpasang();
        $onsite = rtrim(sprintf("%f", $row->getOnsite()), ".0");
        $terpasang = rtrim(sprintf("%f", $row->getTerpasang()), ".0");
        $textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $row->getNama(), $row->getSatuan(), $onsite, $terpasang);
        $resourse_material[] = '<option value="'.$row->getMaterialId().'" data-max="'.$belumTerpasang.'">'.$textNode.'</option>';
    }
    echo implode("\r\n", $resourse_material);
}
catch(Exception $e)
{
    // do nothing
}