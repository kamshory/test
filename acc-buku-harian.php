<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\Database\PicoDatabaseQueryBuilder;
use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\BukuHarianAcc;
use Sipro\Entity\Data\SupervisorProyek;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "acc-buku-harian", $appLanguage->getAccBukuHarian());

$inputGet = new InputGet();
$inputPost = new InputPost();

$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

if($inputPost->getUserAction() == 'acc')
{
    $bukuHarianId = $inputPost->getBukuHarianId();

    $bukuHarian = new BukuHarianAcc(null, $database);
    $sperVisorProyek = new SupervisorProyek(null, $database);
    try
    {
        $bukuHarian->find($bukuHarianId);
        $sperVisorProyek->findOneByProyekIdAndSupervisorIdAndKoordinator($bukuHarian->getProyekId(), $supervisorId, true);

        $bukuHarian->setKomentarKoordinator($inputPost->getKomentarKoordinator());
        $bukuHarian->setAccKoordinator(true);
        $bukuHarian->setWaktuAccKoordinator($currentAction->getTime());
        $bukuHarian->update();
    }
    catch(Exception $e)
    {
        // do nothing
    }
}
if($inputPost->getUserAction() == 'cancel-acc')
{
    $bukuHarianId = $inputPost->getBukuHarianId();

    $bukuHarian = new BukuHarianAcc(null, $database);
    $sperVisorProyek = new SupervisorProyek(null, $database);
    try
    {
        $bukuHarian->find($bukuHarianId);
        $sperVisorProyek->findOneByProyekIdAndSupervisorIdAndKoordinator($bukuHarian->getProyekId(), $supervisorId, true);

        $bukuHarian->setKomentarKoordinator(null);
        $bukuHarian->setAccKoordinator(false);
        $bukuHarian->setWaktuAccKoordinator(null);
        $bukuHarian->update();
    }
    catch(Exception $e)
    {
        // do nothing
    }
}

if($inputGet->getUserAction() == UserAction::DETAIL)
{
	$bukuHarian = new BukuHarian(null, $database);
	try{
		$subqueryMap = array(
		"supervisorId" => array(
			"columnName" => "supervisor_id",
			"entityName" => "SupervisorMin",
			"tableName" => "supervisor",
			"primaryKey" => "supervisor_id",
			"objectName" => "supervisor",
			"propertyName" => "nama"
		), 
		"proyekId" => array(
			"columnName" => "proyek_id",
			"entityName" => "ProyekMin",
			"tableName" => "proyek",
			"primaryKey" => "proyek_id",
			"objectName" => "proyek",
			"propertyName" => "nama"
		), 
		"ktskId" => array(
			"columnName" => "ktsk_id",
			"entityName" => "KtskMin",
			"tableName" => "ktsk",
			"primaryKey" => "ktsk_id",
			"objectName" => "ktsk",
			"propertyName" => "nama"
		)
		);
		$bukuHarian->findOneWithPrimaryKeyValue($inputGet->getBukuHarianId(), $subqueryMap);
		if($bukuHarian->hasValueBukuHarianId())
		{
			$x = array(1=>'cerah', 2=>'berawan', 3=>'hujan', 4=>'hujan-lebat');
			$data_cuaca = array();
			for($i = 0; $i<24; $i++)
			{
				$tt = sprintf("%02d", $i);
				$tv = $bukuHarian->get('c'.$tt);
				$data_cuaca[$tt] = isset($x[$tv]) ? $x[$tv] : null;
			}
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
require_once __DIR__ . "/inc.app/header-supervisor.php";
			// define map here
			
?>
<div class="page page-jambi page-detail">
	<div class="jambi-wrapper">
		<?php
		if(UserAction::isRequireNextAction($inputGet) && UserAction::isRequireApproval($bukuHarian->getWaitingFor()))
		{
				?>
				<div class="alert alert-info"><?php echo UserAction::getWaitingForMessage($appLanguage, $bukuHarian->getWaitingFor());?></div>
				<?php
		}
		?>
		<style type="text/css">
		.item-pekerjaan{
			padding:0 0 8px 0;
		}
		.separator{
			margin-top:5px;
			margin-bottom:5px;
			border-bottom:1px solid #EEEEEE;
			height:1px;
			box-sizing:border-box;
		}
		.cuaca-container{
			padding:10px 0;
		}
		.detail-proyek{
			margin-bottom:10px;
		}
		</style>
		<link rel="stylesheet" type="text/css" href="lib.assets/mobile-style/buku-harian.css">
		<script type="text/javascript" src="lib.assets/mobile-script/buku-harian.js">
		</script>
		<script type="text/javascript">
		var dataCuaca = <?php echo json_encode($data_cuaca);?>;
		var bukuHarianId = <?php echo $bukuHarian->getBukuHarianId();?>;
		$(document).ready(function(e) {
			renderCuaca('area.cuaca-control', dataCuaca);
		});
		</script>
		
		<form name="detailform" id="detailform" action="" method="post">
			<table class="responsive responsive-two-cols" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><?php echo $appEntityLanguage->getSupervisor();?></td>
						<td><?php echo $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getProyek();?></td>
						<td><?php echo $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek()->getNama() : "";?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getTanggal();?></td>
						<td><?php echo $bukuHarian->getTanggal();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getPermasalahan();?></td>
						<td><?php echo $bukuHarian->getPermasalahan();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getRekomendasi();?></td>
						<td><?php echo $bukuHarian->getRekomendasi();?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuBuat();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuBuat('j F Y H:i:s');?></td>
					</tr>
					<tr>
						<td><?php echo $appEntityLanguage->getWaktuUbah();?></td>
						<td><?php echo $bukuHarian->dateFormatWaktuUbah('j F Y H:i:s');?></td>
					</tr>
                    <tr>
						<td><?php echo $appEntityLanguage->getKomentar();?></td>
						<td><textarea name="komentar_koordinator" id="komentar_koordinator" class="form-control"></textarea></td>
					</tr>
				</tbody>
			</table>

			
			<?php
				include "inc.app/dom-buku-harian.php";
			?>
			<div class="button-area">
                <button type="submit" name="user_action" value="acc" class="btn btn-success"><?php echo $appLanguage->getButtonAcc();?></button>
				<button type="button" class="btn btn-primary" onclick="window.location='<?php echo $currentModule->getRedirectUrl();?>';"><?php echo $appLanguage->getButtonBackToList();?></button>
			</div>

			</table>
		</form>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
		}
		else
		{
			// Do somtething here when data is not found
			?>
			<div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			<?php
		}
	}
	catch(Exception $e)
	{
require_once __DIR__ . "/inc.app/header-supervisor.php";
		// Do somtething here when exception
		?>
		<div class="alert alert-danger"><?php echo $e->getMessage();?></div>
		<?php
require_once __DIR__ . "/inc.app/footer-supervisor.php";
	}
}
else 
{
$appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentLoggedInSupervisor->getLanguageId());
$periode = $inputGet->getPeriode(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
$accKoordinator = $inputGet->getAccKoordinator(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

$specMap = array(
	"supervisorId" => PicoSpecification::filter("supervisorId", "number"),
	"proyekId" => PicoSpecification::filter("proyekId", "number")
);
$sortOrderMap = array(
	"supervisorId" => "supervisorId",
	"proyekId" => "proyekId",
	"tanggal" => "tanggal",
	"latitude" => "latitude",
	"longitude" => "longitude",
	"ktskId" => "ktskId",
	"accKtsk" => "accKtsk",
	"koordinatorId" => "koordinatorId",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);

// Additional filter here
if($periode != "")
{
	$specification->addAnd(PicoPredicate::getInstance()->like('tanggal', PicoPredicate::generateLikeStarts($periode)));
}

// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "waktuBuat", 
		"sortType" => PicoSort::ORDER_TYPE_DESC
	)
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);

$bulan_ini = date("Y-m");
if($inputGet->getAccKoordinator() == null)
{
	$accKoordinator = '0';
}

$sql_filter = "";
if($accKoordinator == '-')
{
	$sql_filter .= " and buku_harian.tanggal like '$bulan_ini%' ";
}
else if($accKoordinator == '0')
{
	$sql_filter .= " and (buku_harian.acc_koordinator is null or buku_harian.acc_koordinator = false) ";
}
else if($accKoordinator == '1')
{
	$sql_filter .= " and (buku_harian.acc_koordinator = true) and buku_harian.tanggal like '$bulan_ini%' ";
}

$dataLimit = $pageable->getPage()->getLimit(); 

$queryBuilder = new PicoDatabaseQueryBuilder($database);
$queryBuilder
->select("buku_harian.*, 
(select supervisor.nama from supervisor where supervisor.supervisor_id = buku_harian.supervisor_id) as supervisor,
(select proyek.nama from proyek where proyek.proyek_id = buku_harian.proyek_id) as proyek")
->from("buku_harian")
->innerJoin("supervisor_proyek")->on("supervisor_proyek.proyek_id = buku_harian.proyek_id")
->where("supervisor_proyek.supervisor_id = '$supervisorId' and supervisor_proyek.koordinator = true $sql_filter");

$stmt = $database->query($queryBuilder);
$totalResult = $stmt->rowCount();


$queryBuilder
->orderBy("buku_harian.waktu_buat asc")
->limit($dataLimit->getLimit())
->offset($dataLimit->getOffset())
;
$dataToShown = $database->query($queryBuilder);
$pageData = new PicoPageData(null, time(), $totalResult, $pageable);

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
    require_once __DIR__ . "/inc.app/header-supervisor.php";
    
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
			
				<span class="filter-group">
					<span class="filter-label"><?php echo $appLanguage->getApprovalStatus();?></span>
					<span class="filter-control">
					<select name="acc_koordinator" id="acc_koordinator" class="form-control">
						<option value="0"<?php echo ($accKoordinator == '0') ? ' selected' : '';?>>Belum di-Acc</option>
						<option value="1"<?php echo ($accKoordinator == '1') ? ' selected' : '';?>>Sudah di-Acc</option>
						<option value="-"<?php echo ($accKoordinator == '-') ? ' selected' : '';?>>Semua</option>
					</select>
					</span>
				</span>
				
				<span class="filter-group">
					<button type="submit" class="btn btn-success"><?php echo $appLanguage->getButtonSearch();?></button>
				</span>
		
			</form>
		</div>
		<div class="data-section" data-ajax-support="true" data-ajax-name="main-data">
			<?php } /*ajaxSupport*/ ?>
			<?php try{
				if($pageData->getTotalResult() > 0)
				{		
				    $pageControl = $pageData->getPageControl("page", $currentModule->getSelf())
				    ->setNavigation(
				    '<i class="fa-solid fa-angle-left"></i>', '<i class="fa-solid fa-angle-right"></i>',
				    '<i class="fa-solid fa-angles-left"></i>', '<i class="fa-solid fa-angles-right"></i>'
				    )
				    ->setPageRange($appConfig->getData()->getPageRange())
				    ;
			?>
			<div class="pagination pagination-top">
			    <div class="pagination-number">
			    <?php echo $pageControl; ?>
			    </div>
			</div>
			<form action="" method="post" class="data-form">
				<div class="data-wrapper">
					<table class="table table-row table-sort-by-column">
						<thead>
							<tr>
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td class="data-controll data-viewer">
									<span class="fa fa-check"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td><?php echo $appEntityLanguage->getProyek();?></td>
								<td><?php echo $appEntityLanguage->getTanggal();?></td>
								<td><?php echo $appEntityLanguage->getKtsk();?></td>
								<td><?php echo $appEntityLanguage->getAccKtsk();?></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php  ?>">
							<?php 
							$dataIndex = 0;
							while($row = $dataToShown->fetch(PDO::FETCH_ASSOC))
							{
								$dataIndex++;
                                $bukuHarian = new BukuHarian($row);
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>"><span class="fa fa-check"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $bukuHarian->getProyek();?></td>
								<td data-col-name="tanggal"><?php echo $bukuHarian->getTanggal();?></td>
								<td data-col-name="ktsk_id"><?php echo $bukuHarian->getKtsk();?></td>
								<td data-col-name="acc_ktsk"><?php echo $bukuHarian->optionAccKoordinator($appLanguage->getAlready(), $appLanguage->getNotYet());?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
			</form>
			<div class="pagination pagination-bottom">
			    <div class="pagination-number">
			    <?php echo $pageControl; ?>
			    </div>
			</div>
			
			<?php 
			}
			else
			{
			    ?>
			    <div class="alert alert-info"><?php echo $appLanguage->getMessageDataNotFound();?></div>
			    <?php
			}
			?>
			
			<?php
			}
			catch(Exception $e)
			{
			    ?>
			    <div class="alert alert-danger"><?php echo $e->getMessage();?></div>
			    <?php
			} 
			?>
			<?php /*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){ ?>
		</div>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";
}
/*ajaxSupport*/
}

