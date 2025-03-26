
<?php

use MagicApp\AppLanguage;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\Request\InputGet;
use MagicObject\Util\File\FileUtil;
use MagicObject\Util\PicoIniUtil;
use MagicObject\Util\PicoStringUtil;
use Sipro\Entity\Data\ProgresProyek;
use Sipro\Entity\Data\Proyek;
use Sipro\Util\DateUtil;
use Sipro\Util\SCurveUtil;

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
        "display": false
      },
      "legend": {
        "labels": {
          "boxWidth": 10,
          "boxHeight": 8
        }
      }
    },
    "scales": {
      "x": {
        "type": "time",
        "display": true,
        "title": {
          "display": false,
          "text": "Waktu"
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
          "text": "Persen"
        }
      }
    }
  }
}
';

$dateFormat = 'j M Y H:i';

$config = [];

$inputGet = new InputGet();
if($inputGet->countableProyeks())
{
    $proyeks = $inputGet->getProyeks();
    $i = 0;
    $sCurveUtil = new SCurveUtil($database);
    foreach($proyeks as $proyekId)
    {
        $config[$i] = new MagicObject();
        $config[$i]->loadJsonString($template, false, true, true);
        $keys = [];
        $values = [];
        try
        {
            $progresProyeks = new ProgresProyek(null, $database);
            $specs = PicoSpecification::getInstance()
                ->addAnd(['proyekId', $proyekId])
            ;
            $sorts = PicoSortable::getInstance()
                ->addSortable(['waktuBuat', 'asc'])
            ;
            $pageData = $progresProyeks->findAll($specs, null, $sorts);
            $rows = $pageData->getResult();

            foreach($rows as $row)
            {
                $key = DateUtil::translateDate($appLanguage, date($dateFormat, strtotime($row->getWaktuBuat())));
                $values[] = ['x'=>$key, 'y'=>floatval($row->getPersen())];           
            }
            $sc = new MagicObject(['label'=>'Progres', 'data'=>$values]);
            $config[$i]->getData()->pushDatasets($sc);
        }
        catch(Exception $e)
        {
            $proyek = new Proyek(null, $database);
            $proyek->find($proyekId);
            $values = [
              ['x'=>DateUtil::translateDate($appLanguage, date($dateFormat, strtotime($proyek->getWaktuBuat()))), 'y'=>0],
              ['x'=>DateUtil::translateDate($appLanguage, date($dateFormat, strtotime($proyek->getWaktuUbah()))), 'y'=>floatval($proyek->getPersen())]
            ];
            $sc = new MagicObject(['label'=>'Progres', 'data'=>$values]);
            $config[$i]->getData()->pushDatasets($sc);

        }
        $config[$i]->getData()->setLabels($keys);

        $curves = $sCurveUtil->getSelectedCurve($proyekId);
        foreach($curves as $curve)
        {
          $sc = new MagicObject(['label'=>$curve['label'], 'data'=>$curve['data']]);
          $config[$i]->getData()->pushDatasets($sc);
        }
        $i++;
    }
}
$result = [];

foreach($config as $conf)
{
    $result[] = $conf->value();
}
header("Content-type: application/json");
echo json_encode($result, JSON_PRETTY_PRINT);