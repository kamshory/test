<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicApp\Field;
use MagicApp\PicoModule;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use Sipro\Entity\Data\BillOfQuantity;
use Sipro\Util\DateUtil;
use Sipro\Util\ProyekUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$moduleName = "Home";
$currentModule = new PicoModule($appConfig, $database, null, "/", "index", "Halaman Depan");

require_once __DIR__ . "/inc.app/header-supervisor.php";
?> 

    <?php
    $hari = $appConfig->getHariProyek();
    $cache = ProyekUtil::getDaftarProyek($database, $hari, 7200);
    $daftarProyek = $cache->daftarProyek;
    $proyekDipilih = $cache->proyekDipilih;
    $daftarNamaSupervisor = $cache->daftarNamaSupervisor;
    
    $specsBOQ = PicoSpecification::getInstance()
      ->addAnd(PicoPredicate::getInstance()->in(Field::of()->proyekId, $daftarProyek))
      ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
      ->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->volume, null))
      ->addAnd(PicoPredicate::getInstance()->notEquals(Field::of()->volume, 0))
    ;

    $sortsBOQ = PicoSortable::getInstance()
      ->addSortable(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_ASC))
    ;

    $finderBOQ = new BillOfQuantity(null, $database);

    $listProyek = array();
    $sortsBukuHarian = PicoSortable::getInstance()
        ->addSortable(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_ASC))
        ;
    try
    {
      $pageDataBOQ = $finderBOQ->findAll($specsBOQ, null, $sortsBukuHarian);
      $listBOQ = $pageDataBOQ->getResult();
      foreach($listBOQ as $idx=>$boq)
      {
        if(!isset($listProyek[$boq->getProyekId()]))
        {
          $listProyek[$boq->getProyekId()] = array();
        }
        $listProyek[$boq->getProyekId()][] = $boq;
      }
    }
    catch(Exception $e)
    {
      // do nothing
    }

    $proyekDipilihVal = array_values($proyekDipilih);
    $nproyek = count($proyekDipilihVal);

    if($nproyek >= 4)
    {
      $class = "col-sm-12 col-xl-3";
    }
    else if($nproyek == 3)
    {
      $class = "col-sm-12 col-xl-4";
    }
    else if($nproyek == 2)
    {
      $class = "col-sm-12 col-xl-6";
    }
    else if($nproyek == 1)
    {
      $class = "col-sm-12 col-xl-12";
    }

    ?>
    <style>
      .progres-proyek > .card .card-body .proyek-nama{
        margin-top: -6px;
      }
    </style>
    <div class="container">
    
    <!-- /.row-->
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Progress Pekerjaan Proyek</div>
          <div class="card-body">

            <div class="table-responsive">
              <table class="table border mb-0">
                <thead class="fw-semibold text-nowrap">
                  <tr class="align-middle">
                    <th class="bg-body-secondary"><?php echo $appLanguage->getProject();?></th>
                    <th class="bg-body-secondary"><?php echo $appLanguage->getBillOfQuantity();?></th>
                    <th class="bg-body-secondary"><?php echo $appLanguage->getSupervisor();?></th>
                    <th class="bg-body-secondary"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($listProyek as $bh)
                  {
                    $proyekId = $bh[0]->getProyekId();
                    $namaProyek = $bh[0]->hasValueProyek() ? $bh[0]->getProyek()->getNama() : "";
                    $waktuBuatProyek = $bh[0]->hasValueProyek() ? $bh[0]->getProyek()->getWaktuBuat() : "";
                    if(strlen($namaProyek) > 50)
                    {
                      $namaProyek = substr($namaProyek, 0, 50);
                    }
                  ?>
                  <tr class="align-top">
                    <td>
                      <div class="text-nowrap"><?php echo $namaProyek;?></div>
                      <div class="small text-body-secondary text-nowrap"><?php echo DateUtil::translateDate($appLanguage, date('j F Y H:i', strtotime($waktuBuatProyek)));?></div>
                    </td>
                    <td>
                      <?php
                      $bobotTotal = 0;
                      $persenTotal = 0;
                      $persenItem = 0;
                      foreach($bh as $boq)
                      {
                        $namaBoq = $boq->getNama();
                        if(strlen($namaBoq) > 50)
                        {
                          $namaBoq = substr($namaBoq, 0, 50);
                        }
                        $percent = $boq->getVolume() > 0 ? (100 * $boq->getVolumeProyek() / $boq->getVolume()) : 0; 
                        $bobot = $boq->getBobot();
                        if($bobot == 0)
                        {
                          $bobot = 1;
                        }
                        $bobotTotal += $bobot;
                        $persenTotal += ($percent * $bobot);
                        $persenItem++;
                        ?>
                      <div>
                      <div class="d-flex justify-content-between align-items-baseline">
                        <div class="text-nowrap small text-body-secondary me-3"><?php echo $namaBoq;?></div>
                        <div class="fw-semibold"><?php echo number_format($percent, 2, ",", ".");?>%</div>
                      </div>
                      <div class="progress progress-thin">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percent;?>%" aria-valuenow="<?php echo $percent;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      </div>
                      <?php
                      }
                      $persenRata = $bobotTotal > 0 ? $persenTotal/$bobotTotal : 0; 
                      ?>
                      <hr style="height: 2px; line-height: 2px; margin-bottom:0px">
                      <div>
                      <div class="d-flex justify-content-between align-items-baseline">
                        <div class="text-nowrap small text-body-secondary me-3">Rata-Rata Progres</div>
                        <div class="fw-semibold"><?php echo number_format($persenRata, 2, ",", ".");?>%</div>
                      </div>
                      <div class="progress progress-thin">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persenRata;?>%" aria-valuenow="<?php echo $persenRata;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      </div>
                    </td>

                    <td>
                      <div class="small text-body-secondary"><?php echo implode("<br>\r\n", $daftarNamaSupervisor[$proyekId]);?></div>
                    </td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-transparent p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <svg class="icon">
                            <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                          </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                          <a class="dropdown-item" href="bill-of-quantity-proyek.php?user_action=chart&proyek_id=<?php echo $boq->getProyekId();?>">Grafik</a>
                          <a class="dropdown-item" href="bill-of-quantity-proyek.php?proyek_id=<?php echo $boq->getProyekId();?>">Edit</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
    <!-- /.row-->
          
    <!-- Plugins and scripts required by this view-->
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/css/coreui-chartjs.css">
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/chart.js/js/chart.umd.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src='<?php echo $baseAssetsUrl;?>lib.assets/chart/chart.js'></script>
    <script src='<?php echo $baseAssetsUrl;?>lib.assets/chart/date-fns.js'></script>
    <script src='<?php echo $baseAssetsUrl;?>lib.assets/chart/chartjs-adapter-date-fns.js'></script>
    <script src='<?php echo $baseAssetsUrl;?>lib.assets/chart/moment.min.js'></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/utils/js/index.js"></script>
    
    <script>
      var urlBoq = 'lib.mobile-tools/ajax-proyek-boq.php';
      var urlProgres = 'lib.mobile-tools/ajax-proyek-progres.php';
    </script>
    <script src='<?php echo $baseAssetsUrl;?>lib.assets/chart/home.js'></script>
        
    <?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";