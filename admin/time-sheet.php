<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\MagicObject;
use MagicObject\Database\PicoPage;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use MagicApp\UserAction;
use MagicApp\AppUserPermission;
use Sipro\Entity\Data\Supervisor;
use Sipro\AppIncludeImpl;
use Sipro\Entity\Data\BukuHarianMin;
use Sipro\Entity\Data\JabatanMin;
use Sipro\Entity\Data\TskMin;
use Sipro\Util\CommonUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$dataFilter = null;

if($currentUser->getTskId() != 0)
{
	$dataFilter = PicoSpecification::getInstance()
		->addAnd(['tskId', $currentUser->getTskId()])
		;
}

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "time-sheet", "Time Sheet");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}

$supervisorId = $inputGet->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
$periodeId = $inputGet->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

if($supervisorId != 0 && !empty($periodeId))
{
	require_once $appInclude->mainAppHeader(__DIR__);
    require_once __DIR__ . "/time-sheet-core.php";
	?>
    <script type="text/javascript" src="../lib.assets/js/filesaver.js"></script>
	<script type="text/javascript" src="../lib.assets/js/html-docx.js"></script>
	<script type="application/javascript">
    var filename = 'time-sheet-<?php echo $supervisor->getNama(); ?>-<?php echo $tahun; ?>-<?php echo $bulan; ?>';
    $(document).ready(function(e) {
        $(document).on('click', '#download', function(e) {
            var content = $('.table-scroll-horizontal').html();
            var html = '<div>' + content + '</div>';
            var doc = $(html);
            doc = convertCanvasToBase64Image(doc);
            doc = replaceBase(doc);
            doc.find('.signature-image img').css({
                'width': '80px',
                'height': 'auto'
            });
            doc.find('.signature-image img').attr({
                'width': '80'
            });
            doc.find('.signature-image img').removeAttr('height');
            var content = doc.html();
            var title = 'Lembar Waktu Kerja';
            var style = `
			<style type="text/css">
			.time-sheet {
				border-collapse: collapse;
			}

			.time-sheet td {
				padding: 2px;
			}

			.time-sheet p {
				margin: 0;
				padding: 0;
			}

			.time-sheet img {
				vertical-align: bottom;
			}

			.supervisor-sign-text {
				white-space: nowrap;
			}

			h1 {
				font-size: 18px;
				text-align: center;
				margin: 4px;
				padding: 0;
			}

			.time-sheet td table td {
				padding: 0;
			}

			.day {
				text-align: center;
			}

			.weekend {
				background-color: #B9B9B9;
			}

			.dayoff {
				background-color: #F60;
			}

			.day.dayoff.weekend {
				background-color: #F60 !important;
			}

			.travel.dayoff {
				background-color: #03F !important;
			}

			.travel.weekend {
				background-color: #03F !important;
			}

			.travel.weekend.dayoff {
				background-color: #03F !important;
			}

			.travel {
				background-color: #03F !important;
			}

			tfoot .leave {
				background-color: #EE0000;
			}
			</style>`;
            content = `<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
			<title>${title}</title>
			${style}
			</head>
			<body style="position:relative;">
			${content}
			</body>
			</html>
			`;
            var converted = htmlDocx.asBlob('<!DOCTYPE html>' + content, {
                orientation: 'landscape'
            });
            saveAs(converted, filename + '.docx');
        });
    });

    function replaceBase(doc) {
        var regularLinks = doc.find('a');
        var lnk = "";
        [].forEach.call(regularLinks, function(obj) {
            var aElement = obj;
            lnk = aElement.getAttribute('href');
            lnk = convertRelToAbsUrl(lnk);
            aElement.setAttribute('href', lnk);
        });
        return doc;
    }

    function convertCanvasToBase64Image(doc) {
        var regularImages = doc.find('img');
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        [].forEach.call(regularImages, function(obj) {
            var imgElement = obj;
            canvas.width = imgElement.width;
            canvas.height = imgElement.height;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(imgElement, 0, 0, imgElement.width, imgElement.height);
            var dataURL = canvas.toDataURL();
            imgElement.setAttribute('src', dataURL);
            imgElement.style.width = canvas.width + 'px';
            imgElement.style.maxWidth = '100%';
            imgElement.style.height = 'auto';
            imgElement.removeAttribute('width');
            imgElement.removeAttribute('height');
        });
        canvas.remove();
        return doc;
    }
	</script>
	<div class="button-area mb-lg-3">
		<button type="button" id="download" class="btn btn-primary">Download Word</button>
		<button type="button" id="pdf" class="btn btn-primary" onclick="window.open('time-sheet-pdf.php?<?php echo $_SERVER['QUERY_STRING']; ?>')">Print</button>
		<button type="button" id="kembali" class="btn btn-secondary" onclick="window.location='<?php echo basename($_SERVER['PHP_SELF']); ?>'">Kembali</button>
	</div>
	<?php
	require_once $appInclude->mainAppFooter(__DIR__);
    exit();
}
else 
{
$appEntityLanguage = new AppEntityLanguage(new Supervisor(), $appConfig, $currentUser->getLanguageId());
/*ajaxSupport*/
if(!$currentAction->isRequestViaAjax()){
require_once $appInclude->mainAppHeader(__DIR__);
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getTsk();?></span>
					<span class="filter-control">
							<select name="tsk_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new TskMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->tskId, Field::of()->nama, $inputGet->getTskId())
								; ?>
							</select>
					</span>
				</span>

                <span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getPeriode();?></span>
					<span class="filter-control">
							<select name="periode_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo CommonUtil::getPeriode(new BukuHarianMin(null, $database), $inputGet->getPeriodeId(), $appLanguage)
								; ?>
							</select>
					</span>
				</span>

				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getNama();?></span>
					<span class="filter-control">
						<input type="text" name="nama" class="form-control" value="<?php echo $inputGet->getNama();?>" autocomplete="off"/>
					</span>
				</span>
				
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getJabatan();?></span>
					<span class="filter-control">
							<select name="jabatan_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new JabatanMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->draft, false)), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->sortOrder, PicoSort::ORDER_TYPE_ASC))
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->jabatanId, Field::of()->nama, $inputGet->getJabatanId())
								; ?>
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
			<?php
			
			if($inputGet->getPeriodeId() != '')
			{
			
			$mapForJenisKelamin = array(
				"L" => array("value" => "L", "label" => "Laki-Laki", "default" => "true"),
				"P" => array("value" => "P", "label" => "Perempuan", "default" => "true")
			);
			$specMap = array(
			    "nama" => PicoSpecification::filter("nama", "fulltext"),
				"jabatanId" => PicoSpecification::filter("jabatanId", "number")
			);
			$sortOrderMap = array(
			    "nip" => "nip",
				"nama" => "nama",
				"koordinator" => "koordinator",
				"jabatanId" => "jabatanId",
				"jenisKelamin" => "jenisKelamin",
				"sortOrder" => "sortOrder",
				"blokir" => "blokir",
				"aktif" => "aktif"
			);
			
			// You can define your own specifications
			// Pay attention to security issues
			$specification = PicoSpecification::fromUserInput($inputGet, $specMap);

			if($inputGet->getTskId() != 0)
			{
				$specification->addAnd(['tskId', $inputGet->getTskId()]);
			}
			else
			{
				$specification->addAnd($dataFilter);
			}
			
			// You can define your own sortable
			// Pay attention to security issues
			$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
				array(
					"sortBy" => "nama", 
					"sortType" => PicoSort::ORDER_TYPE_ASC
				)
			));
			
			$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
			$dataLoader = new Supervisor(null, $database);
			
			$subqueryMap = array(
			"jabatanId" => array(
				"columnName" => "jabatan_id",
				"entityName" => "JabatanMin",
				"tableName" => "jabatan",
				"primaryKey" => "jabatan_id",
				"objectName" => "jabatan",
				"propertyName" => "nama"
			), 
			"adminBuat" => array(
				"columnName" => "admin_buat",
				"entityName" => "User",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "pembuat",
				"propertyName" => "nama_depan"
			), 
			"adminUbah" => array(
				"columnName" => "admin_ubah",
				"entityName" => "User",
				"tableName" => "admin",
				"primaryKey" => "admin_id",
				"objectName" => "pengubah",
				"propertyName" => "nama_depan"
			)
			);
			try{
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
								<td data-col-name="nip" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNip();?></a></td>
								<td data-col-name="nama" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getNama();?></a></td>
								<td data-col-name="koordinator" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getKoordinator();?></a></td>
								<td data-col-name="jabatan_id" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJabatan();?></a></td>
								<td data-col-name="jenis_kelamin" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getJenisKelamin();?></a></td>
								<td data-col-name="blokir" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getBlokir();?></a></td>
								<td data-col-name="aktif" class="order-controll"><a href="#"><?php echo $appEntityLanguage->getAktif();?></a></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval"><?php echo $appLanguage->getApproval();?></td>
								<?php } ?>
							</tr>
						</thead>
					
						<tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
							<?php 
							$dataIndex = 0;
							while($supervisor = $pageData->fetch())
							{
								$dataIndex++;
							?>
		
							<tr data-primary-key="<?php echo $supervisor->getSupervisorId();?>" data-sort-order="<?php echo $supervisor->getSortOrder();?>" data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>">

								<?php if($userPermission->isAllowedDetail()){ ?>
								<td>
									<a class="detail-control field-master" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->supervisor_id, $supervisor->getSupervisorId(), array(Field::of()->periode_id=>$inputGet->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true)));?>"><span class="fa fa-folder"></span></a>
								</td>
								<?php } ?>
								<td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
								<td data-col-name="nip"><?php echo $supervisor->getNip();?></td>
								<td data-col-name="nama"><?php echo $supervisor->getNama();?></td>
								<td data-col-name="koordinator"><?php echo $supervisor->optionKoordinator($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="jabatan_id"><?php echo $supervisor->hasValueJabatan() ? $supervisor->getJabatan()->getNama() : "";?></td>
								<td data-col-name="jenis_kelamin"><?php echo isset($mapForJenisKelamin) && isset($mapForJenisKelamin[$supervisor->getJenisKelamin()]) && isset($mapForJenisKelamin[$supervisor->getJenisKelamin()]["label"]) ? $mapForJenisKelamin[$supervisor->getJenisKelamin()]["label"] : "";?></td>
								<td data-col-name="blokir"><?php echo $supervisor->optionBlokir($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<td data-col-name="aktif"><?php echo $supervisor->optionAktif($appLanguage->getYes(), $appLanguage->getNo());?></td>
								<?php if($userPermission->isAllowedApprove()){ ?>
								<td class="data-controll data-approval">
									<?php if(UserAction::isRequireApproval($supervisor->getWaitingFor())){ ?>
									<a class="btn btn-tn btn-success" href="<?php echo $currentModule->getRedirectUrl(UserAction::DETAIL, Field::of()->supervisor_id, $supervisor->getSupervisorId(), array(UserAction::NEXT_ACTION => UserAction::APPROVAL));?>"><?php echo $appLanguage->getButtonApproveTiny();?></a>
									<?php echo UserAction::getWaitingForText($appLanguage, $supervisor->getWaitingFor());?>
									<?php } ?>
								</td>
								<?php } ?>
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
			else if($inputGet->isShowRequireApprovalOnly())
			{
			    ?>
			    <div class="alert alert-info"><?php echo $appLanguage->getMessageNoDataRequireApproval();?></div>
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
		}
			?>
			<?php /*ajaxSupport*/ if(!$currentAction->isRequestViaAjax()){ ?>
		</div>
	</div>
</div>
<?php 
require_once $appInclude->mainAppFooter(__DIR__);
}
/*ajaxSupport*/
}
