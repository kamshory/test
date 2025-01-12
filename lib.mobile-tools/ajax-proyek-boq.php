<?php

use MagicApp\AppLanguage;
use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\Request\InputGet;
use MagicObject\Util\File\FileUtil;
use MagicObject\Util\PicoIniUtil;
use MagicObject\Util\PicoStringUtil;
use Sipro\Entity\Data\BillOfQuantityProyek;
use Sipro\Entity\Data\Proyek;
use Sipro\Util\DateUtil;

require_once dirname(__DIR__) . "/inc.app/app.php";

$appLanguage = new AppLanguage(
  $appConfig,
  'id',
  function($var, $value)
  {
      $inputSource = dirname(__DIR__) . "/inc.lang/source/app.ini";
      $inputSource = FileUtil::fixFilePath($inputSource);
      if(!file_exists(dirname($inputSource)))
      {
          mkdir(dirname($inputSource), 0755, true);
      }
      $sourceData = null;
      if(file_exists($inputSource) && filesize($inputSource) > 3)
      {
          $sourceData = PicoIniUtil::parseIniFile($inputSource);
      }
      if($sourceData == null || $sourceData === false)
      {
          $sourceData = array();
      }   
      $output = array_merge($sourceData, array(PicoStringUtil::snakeize($var) => $value));
      PicoIniUtil::writeIniFile($output, $inputSource);
  }
);
$boqProyek = new BillOfQuantityProyek(null, $database);
$inputGet = new InputGet();
$proyekId = $inputGet->getProyekId();
$proyek = null;
$datasets = [];
$boqs = [];
$boqNames = [];
$minDate = null;
$maxDate = null;
try
{
    $specs = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $proyekId)) 
        ;
    $sortable = PicoSortable::getInstance()
        ->add(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_ASC))
    ;
    $pageData = $boqProyek->findAll($specs, $sortable);

    foreach($pageData->getResult() as $billOfQuantityProyek)
    {
        $boqId = $billOfQuantityProyek->getBillOfQuantityId();
        if($billOfQuantityProyek->issetBillOfQuantity())
        {
            if(!isset($minDate))
            {
                $minDate = $billOfQuantityProyek->getWaktuBuat();
            }
            $maxDate = $billOfQuantityProyek->getWaktuBuat();
            if(!isset($proyek) && $billOfQuantityProyek->issetProyek())
            {
                $proyek = $billOfQuantityProyek->getProyek()->getNama();
            }
            $billOfQuantity = $billOfQuantityProyek->getBillOfQuantity();
            if(!isset($boqs[$boqId]))
            {
                $boqs[$boqId] = array();
                $boqNames[$boqId] = html_entity_decode($billOfQuantity->getNama()); 
            }
            $boqs[$boqId][] = array(
                "x" => $billOfQuantityProyek->getWaktuBuat(),
                "y" => $billOfQuantityProyek->getPersen(),        
            );
        }
    }

    $colors = [
        '#FF5733',
        '#33FF57',
        '#5733FF',
        '#FF33A1',
        '#33A1FF',
        '#A1FF33',
        '#FFAA33',
        '#33FFAA',
        '#AA33FF',
        '#FFA133',
        '#33FFA1',
        '#A133FF',
        '#FF33AA',
        '#33AAFF',
        '#AAFF33',
        '#FF5733',
        '#33FF57',
        '#5733FF',
        '#FFAA33',
        '#33FFAA',
        '#F15C22',
        '#4DDC5C',
        '#2E86C1',
        '#CA3B94',
        '#5EC8FA',
        '#C5DB39',
        '#FF8C33',
        '#33FF88',
        '#9C33FF',
        '#FFB533',
        '#33FFA4',
        '#BB33FF',
        '#FF33BB',
        '#33BBFF',
        '#BBFF33',
        '#FF5733',
        '#33FF57',
        '#5733FF',
        '#FFAA33',
        '#33FFAA'
    ];
    
    $i = 0;
    foreach($boqNames as $boqId=>$boqName)
    {
        $datasets[] = array(
            "label" => $boqName,
            "borderColor" => $colors[$i % count($colors)],
            "fill" => false,
            "data" => $boqs[$boqId]
        );
        $i++;
    }
}
catch(Exception $e)
{
  $proyekFinder = new Proyek(null, $database);
  try
  {
    $proyekFinder->find($proyekId);
    $proyek = $proyekFinder->getNama();
    $minDate = DateUtil::translateDate($appLanguage, date('j M Y'));
    $maxDate = DateUtil::translateDate($appLanguage, date('j M Y'));
  }
  catch(Exception $e2)
  {
    // do nothing
  }
}

$template = '{
    "type": "line",
    "data": {
      "datasets": []
    },
    "options": {
      "spanGaps": 172800000,
      "responsive": true,
      "maintainAspectRatio": false,
      "interaction": {
        "mode": "nearest"
      },
      "plugins": {
        "title": {
          "display": true,
          "text": "proyek"
        }
      },
      "scales": {
        "x": {
          "type": "time",
          "display": true,
          "title": {
            "display": true,
            "text": "Waktu Pelaporan"
          },
          "ticks": {
            "autoSkip": false,
            "maxRotation": 0,
            "major": {
              "enabled": true
            },
            "font": {
              "weight": "bold"
            }
          }
        },
        "y": {
          "display": true,
          "title": {
            "display": true,
            "text": "Persen Volume"
          }
        }
      }
    }
  }
';

$config = new MagicObject();
$config->loadJsonString($template, false, true, true);
$config->getData()->setDatasets($datasets);
$config->getOptions()->getPlugins()->getTitle()->setText($proyek);
$config->setMinDate(DateUtil::translateDate($appLanguage, date('j M Y', strtotime($minDate))));
$config->setMaxDate(DateUtil::translateDate($appLanguage, date('j M Y', strtotime($maxDate))));

header("Content-type: application/json");
echo $config;