<?php

use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\Peralatan;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$resourse_peralatan = array();

$specs3 = PicoSpecification::getInstance()->add(['aktif', true]);
$sorts3 = PicoSortable::getInstance()->add(['nama', 'asc']);
echo '<option value=""></option>'."\r\n";
try
{
    $peralatan = new Peralatan(null, $database);
    $pageData3 = $peralatan->findAll($specs3, null, $sorts3);
    foreach($pageData3->getResult() as $row)
    {
        $nama = $row->getNama();
        if($row->hasValueSatuan())
        {
            $nama .= " [".$row->getSatuan()."]";
        }
        echo '<option value="'.$row->getPeralatanId().'">'.$nama.'</option>'."\r\n";
        $resourse_peralatan[] = [$row->getPeralatanId(), $nama];
    }
}
catch(Exception $e)
{
    // do nothing
}