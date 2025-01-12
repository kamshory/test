<?php

use MagicApp\Field;
use MagicObject\Database\PicoDatabaseQueryBuilder;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Entity\Data\BillOfQuantityProyek;
use Sipro\Entity\Data\Proyek;
use Sipro\Util\BoqUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$boqProyek = new BillOfQuantityProyek(null, $database);
$proyekId = 168;


$boqFinder = new BillOfQuantity(null, $database);
	try
	{
		$boqData = $boqFinder->findByProyekId($proyekId);
		$boqResult = $boqData->getResult();
		$persen = BoqUtil::getAveragePercent($boqResult);
		if($persen > 0)
		{
			$proyek = new Proyek(null, $database);
			$proyek->setProyekId($proyekId)->setPersen($persen)->update();
		}
	}
	catch(Exception $e)
	{
		
	}
  exit();

$proyek = null;
try
{
    $specs = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->proyekId, $proyekId))
        
        ;
    $sortable = PicoSortable::getInstance()
        ->add(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_ASC))
        ;
    $pageData = $boqProyek->findAll($specs, $sortable);

    $datasets = [];
    $boqs = [];
    $boqNames = [];

    foreach($pageData->getResult() as $billOfQuantityProyek)
    {
        $boqId = $billOfQuantityProyek->getBillOfQuantityId();
        if($billOfQuantityProyek->issetBillOfQuantity())
        {
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

}

$template = '{
    "type": "line",
    "data": {
      "datasets": []
    },
    "options": {
      "spanGaps": 172800000,
      "responsive": true,
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

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Multi Series</title>

    <script src='lib.assets/chart/chart.js'></script>
    <script src='lib.assets/chart/date-fns.js'></script>
    <script src='lib.assets/chart/chartjs-adapter-date-fns.js'></script>
    <script src='lib.assets/chart/moment.min.js'></script>

    <style>
        canvas {
            max-width: 100vw;
            max-height: 100vh;
        }
    </style>
</head>

<body>
    <select name="" id="">
        <option value="">- Pilih Proyek -</option>
        <?php
            $queryBuilder = new PicoDatabaseQueryBuilder($database);
            $queryBuilder->newQuery()
                ->select("proyek.proyek_id, proyek.nama")
                ->from("bill_of_quantity_proyek")
                ->innerJoin("proyek")
                ->on("proyek.proyek_id = bill_of_quantity_proyek.proyek_id")
                ->where("bill_of_quantity_proyek.waktu_buat > ?", date("Y-m-d H:i:s", strtotime("-31 days")))
                ->groupBy("proyek.proyek_id, proyek.nama")
                ->orderBy("bill_of_quantity_proyek.proyek_id desc")
                ;
            $rows = $database->fetchAll($queryBuilder);
            foreach($rows as $proyek)
            {
                ?>
                <option value="<?php echo $proyek['proyek_id'];?>"><?php echo $proyek['nama'];?></option>
                <?php
            }
        ?>
    </select>
    <canvas id="myChart"></canvas>
    <script>
        let config = <?php echo $config;?>;
        var ctx = document.getElementById('myChart').getContext('2d');
        Chart.register({
            id: 'moment',
            beforeInit: function(chart) {
                chart.data.labels = chart.data.labels.map(function(label) {
                return moment(label).format('YYYY-MM-DD HH:mm:ss');
                });
            }
        });

        var chart;
        if(chart)
        {
            chart.destroy();
        }
        chart = new Chart(ctx, config);

    </script>
</body>

</html>