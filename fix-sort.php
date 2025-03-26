<?php

use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\SupervisorProyek;

require_once __DIR__ . "/inc.app/app.php";

$supervisorProyek = new SupervisorProyek(null, $database);
$specs1 = PicoSpecification::getInstance()
    ->add(['supervisor.aktif', true])
    ->add(['proyek.aktif', true])
;
$sorts1 = PicoSortable::getInstance()
    ->add(['proyek.proyekId', PicoSort::ORDER_TYPE_DESC])
;
$database->setCallbackDebugQuery(function($query) {
    echo $query;
});
try
{
    $pageData1 = $supervisorProyek->findAll($specs1, null, $sorts1, true);
    $rows = $pageData1->getResult();
    foreach($rows as $row)
    {
        $proyek = $row->getProyek();
        if($proyek != null)
        {
        ?>
        <option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $inputGet->getProyekId() ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
        <?php
        }
    }
}
catch(Exception $e)
{
    // do nothing
}