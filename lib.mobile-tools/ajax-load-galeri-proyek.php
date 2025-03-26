<?php

use MagicApp\AppEntityLanguage;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\GaleriProyek;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";
$inputGet = new InputGet();
$inputPost = new InputPost();
$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
$bukuHarianId = $inputGet->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
$billOfQuantityId = $inputGet->getBillOfQuantityId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
$appEntityLanguage = new AppEntityLanguage(new GaleriProyek(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
$specs4 = PicoSpecification::getInstance()
    ->add(['proyekId', $proyekId])
    ->add(['bukuHarianId', $bukuHarianId])
    ->add(['billOfQuantityId', $billOfQuantityId])
    ->add(['aktif', true])
    ;
$sorts4 = PicoSortable::getInstance()->add(['waktuBuat', 'desc']);
$galeriProyek = new GaleriProyek(null, $database);
try
{
    $pageData4 = $galeriProyek->findAll($specs4, null, $sorts4);
    if($pageData4->getTotalResult() > 0)
    {
        foreach($pageData4->getResult() as $galeriProyek)
        {
            $file = $galeriProyek->getFile();
            $file = str_replace([".jpg", ".jpeg", ".png"], ["_100.jpg", "_100.jpeg", "_100.png"], $file);
            echo "<img src=\"lib.gallery/projects/$proyekId/$file\"> ";
        }
    }
}
catch(Exception $e)
{
    // Do nothing
}