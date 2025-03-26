<?php

namespace Sipro\Util;

use Exception;
use MagicApp\Field;
use MagicObject\Database\PicoDatabase;
use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\KurvaS;

class SCurveUtil
{
    /**
     * Database connection
     *
     * @var PicoDatabase
     */
    private $database;

    /**
     * Constructor
     *
     * @param PicoDatabase $database
     */
    public function __construct($database)
    {
        $this->database = $database; 
    }

    /**
     * Get selected curve
     *
     * @param integer $proyekId
     * @return array
     */
    public function getSelectedCurve($proyekId)
    {
        $specs = PicoSpecification::getInstance()
        ->addAnd([Field::of()->proyekId, $proyekId])
        ->addAnd([Field::of()->aktif, true]);

        $sortable = PicoSortable::getInstance()
        ->addSortable(new PicoSort(Field::of()->defaultData, PicoSort::ORDER_TYPE_DESC))
        ->addSortable(new PicoSort(Field::of()->amandemen, PicoSort::ORDER_TYPE_DESC))
        ;

        $pageable = new PicoPageable(new PicoPage(1, 1), $sortable);

        $finder = new KurvaS(null, $this->database);

        $curves = [];
        try
        {
            $pageData = $finder->findAll($specs, $pageable, $sortable, true);
            $rows = $pageData->getResult();
            foreach($rows as $kurvaS)
            {
                $json = json_decode($kurvaS->getNilai());
                $data = [];
                foreach($json->labels as $index=>$label)
                {
                    $key = date('Y-m-d H:i:s', round($label / 1000));
                    $data[] = ["x"=>$key, "y"=>floatval($json->data[$index])];
                }
                $curves[$kurvaS->getKurvaSId()] = ['label'=>$kurvaS->getNama(), 'data'=> $data];
            }
        }
        catch(Exception $e)
        {
            // do nothing
        }

        return $curves;
    }

    /**
     * Get selected curve
     *
     * @param integer $proyekId
     * @return array
     */
    public function getAllCurve($proyekId)
    {
        $specs = PicoSpecification::getInstance()
        ->addAnd([Field::of()->proyekId, $proyekId])
        ->addAnd([Field::of()->aktif, true]);

        $sortable = PicoSortable::getInstance()
        ->addSortable(new PicoSort(Field::of()->defaultData, PicoSort::ORDER_TYPE_DESC))
        ->addSortable(new PicoSort(Field::of()->amandemen, PicoSort::ORDER_TYPE_DESC))
        ;

        $finder = new KurvaS(null, $this->database);

        $curves = [];
        try
        {
            $pageData = $finder->findAll($specs, null, $sortable, true);
            $rows = $pageData->getResult();
            foreach($rows as $kurvaS)
            {
                $json = json_decode($kurvaS->getNilai());
                $labels = $json->labels;
                $labelsString = [];
                $data = $json->data;
                foreach($labels as $label)
                {
                    $labelsString[] = date('Y-m-d H:i:s', round($label / 1000));
                }
                $curves[$kurvaS->getKurvaSId()] = array('label'=>$kurvaS->getNama(), 'data'=> array_combine($labelsString, $data));
            }  
        }
        catch(Exception $e)
        {
            // do nothing
        }

        return $curves;
    }
}