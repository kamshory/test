<?php

use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\Modul;

require_once __DIR__ . "/inc.app/app.php";

$specification = PicoSpecification::getInstance()
    ->addAnd(PicoPredicate::getInstance()->equals("grupModul.aktif", true))
    //->addAnd(PicoPredicate::getInstance()->equals("modul.aktif", true))
    ->addAnd(["modul.aktif", true])
    ;
    // You can define your own sortable
    // Pay attention to security issues
    $sortable = PicoSortable::getInstance()
        ->addSortable(new PicoSort("grupModul.sortOrder", PicoSort::ORDER_TYPE_ASC))
        ->addSortable(new PicoSort("modul.sortOrder", PicoSort::ORDER_TYPE_ASC))
    ;

    $pageable = new PicoPageable(null, $sortable);
    $dataLoader = new Modul(null, $database);

    $database->setCallbackDebugQuery(function($query){
        echo $query."\r\n";
    });

    $pageData = $dataLoader->findAll($specification, null, $sortable, true);