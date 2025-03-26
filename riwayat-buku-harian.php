<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
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
use MagicObject\MagicObject;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Util\DateUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$currentModule = new PicoModule($appConfig, $database, null, "/", "riwayat-buku-harian", $appLanguage->getRiwayatBukuHarian());

$inputGet = new InputGet();
$inputPost = new InputPost();

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
				</tbody>
			</table>

			
			<?php
				include "inc.app/dom-buku-harian.php";
			?>
			<div class="button-area">
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
$specification->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $currentLoggedInSupervisor->getSupervisorId()));
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
$dataLoader = new BukuHarian(null, $database);

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

/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once __DIR__ . "/inc.app/header-supervisor.php";


$bukuHarianMin = new BukuHarian(null, $database);
$bukuHarianMax = new BukuHarian(null, $database);

$pSpecification = PicoSpecification::getInstance()
	->addAnd(array('supervisorId', $currentLoggedInSupervisor->getSupervisorId()))
	->addAnd(PicoPredicate::getInstance()->notEquals('tanggal', null))
	->addAnd(PicoPredicate::getInstance()->notEquals('tanggal', '0000-00-00'))
	;
$sortable1 = PicoSortable::getInstance()->add(array('tanggal', 'asc'));
$sortable2 = PicoSortable::getInstance()->add(array('tanggal', 'desc'));

try
{
	$bukuHarianMin->findOne($pSpecification, $sortable1);
	$bukuHarianMax->findOne($pSpecification, $sortable2);
	$date_min = $bukuHarianMin->getTanggal();
	$date_max = $bukuHarianMax->getTanggal();
}
catch(Exception $e)
{
	// do nothing
}

if(!isset($date_min) || !isset($date_max))
{
	$date_min = $date_max = date('Y-m-d');
}
if($date_min == '0000-00-00')
{
	$date_min = '2018-01-01';
}
if($date_max == '0000-00-00')
{
	$date_max = date('Y-m-d');
}
$ymin = date('Y', strtotime($date_min));
$ymax = date('Y', strtotime($date_max));
$mmin = date('n', strtotime($date_min));
$mmax = date('n', strtotime($date_max));

$dmin = strtotime($date_min);
$dmax = strtotime($date_max);

$arr_period = array();
for($i = $dmin, $j = 0; $i <= $dmax; $j++)
{
	$arr_period[] = array(date("Y-m", $i), date("F Y", $i));
	$i = strtotime(date('Y-m-01', $i).' +1 months');
}
$arr_period = array_reverse($arr_period);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
			
				<span class="filter-group">
					<span class="filter-label"><?php echo $appLanguage->getPeriod();?></span>
					<span class="filter-control">
						<select name="periode" id="periode" class="form-control">
							<option value=""><?php echo $appLanguage->getSelectOptionSelectMonth();?></option>
							<?php
							foreach($arr_period as $key=>$value)
							{
							?>
							<option value="<?php echo $value[0];?>"<?php echo $value[0] == $periode ? ' selected="selected"' : '';?>><?php echo DateUtil::translatedate($appLanguage, $value[1]);?></option>
							<?php
							}
							?>
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
				$pageData = $dataLoader->findAll($specification, $pageable, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
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
									<span class="fa fa-folder"></span>
								</td>
								<?php } ?>
								<td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
								<td data-col-name="proyek_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getProyek();?></a></td>
								<td data-col-name="tanggal" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getTanggal();?></a></td>
								<td data-col-name="ktsk_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKtsk();?></a></td>
								<td data-col-name="acc_ktsk" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAccKtsk();?></a></td>
								<td data-col-name="koordinator_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKoordinatorId();?></a></td>
							</tr>
						</thead>
					
						<tbody data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($bukuHarian = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">
								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->buku_harian_id, $bukuHarian->getBukuHarianId());?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="proyek_id"><?php echo $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek()->getNama() : "";?></td>
								<td data-col-name="tanggal"><?php echo $bukuHarian->getTanggal();?></td>
								<td data-col-name="ktsk_id"><?php echo $bukuHarian->hasValueKtsk() ? $bukuHarian->getKtsk()->getNama() : "";?></td>
								<td data-col-name="acc_ktsk"><?php echo $bukuHarian->optionAccKtsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="koordinator_id"><?php echo $bukuHarian->getKoordinatorId();?></td>
							</tr>
							<?php 
							}
							?>
		
						</tbody>
					</table>
				</div>
				<div class="button-wrapper">
					<div class="button-area">

					</div>
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
			    <div class="alert alert-danger"><?php echo $appInclude->printException($e);?></div>
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

