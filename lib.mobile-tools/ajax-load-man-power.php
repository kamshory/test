<?php

use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\ManPower;

require_once dirname(__DIR__) . "/inc.app/app.php";

$resourceManPower = [];

$inputGet = new InputGet();
$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
$specs4 = PicoSpecification::getInstance()->add(['proyekId', $proyekId])->add(['aktif', true]);
$sorts4 = PicoSortable::getInstance()->add(['nama', 'asc']);
echo '<option value=""></option>'."\r\n";
try
{
    $manPower = new ManPower(null, $database);
    $pageData4 = $manPower->findAll($specs4, null, $sorts4);
    foreach($pageData4->getResult() as $row)
    {
        $nama = $row->getNama();
        if($row->issetPekerjaan())
        {
            $nama .= " [".$row->getPekerjaan()."]";
        }
        $jumlahPekerja = $row->getJumlahPekerja();        
        $nama .= " [".$jumlahPekerja." orang]";
        
        echo '<option value="'.$row->getManPowerId().'" data-jumlah-pekerja="'.$jumlahPekerja.'">'.$nama.'</option>'."\r\n";
        $resourceManPower[] = [$row->getManPowerId(), $nama];
    }
}
catch(Exception $e)
{
    // do nothing
}