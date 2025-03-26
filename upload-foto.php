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
use MagicApp\AppFormBuilder;
use MagicApp\Field;
use MagicApp\PicoModule;
use Sipro\Entity\Data\LokasiProyek;
use Sipro\Entity\Data\LokasiProyekMin;
use Sipro\Entity\Data\SupervisorProyek;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$specMap = array(
	"nama" => PicoSpecification::filter("nama", "fulltext"),
    "supervisorId" => PicoSpecification::filter("supervisorId", "number")
);
$sortOrderMap = array(
	"nama" => "nama",
    "kodeLokasi" => "kodeLokasi",
    "proyekId" => "proyekId",
    "supervisorId" => "supervisorId",
    "aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
    array(
        "sortBy" => "nama", 
        "sortType" => PicoSort::ORDER_TYPE_ASC
    )
));

$pageable = new PicoPageable(new PicoPage($inputGet->getPage(), $appConfig->getData()->getPageSize()), $sortable);
$dataLoader = new LokasiProyek(null, $database);

$subqueryMap = array(
"proyekId" => array(
    "columnName" => "proyek_id",
    "entityName" => "ProyekMin",
    "tableName" => "proyek",
    "primaryKey" => "proyek_id",
    "objectName" => "proyek",
    "propertyName" => "nama"
), 
"supervisorId" => array(
    "columnName" => "supervisor_id",
    "entityName" => "SupervisorMin",
    "tableName" => "supervisor",
    "primaryKey" => "supervisor_id",
    "objectName" => "supervisor",
    "propertyName" => "nama"
)
);
$appEntityLanguage = new AppEntityLanguage(new LokasiProyek(), $appConfig, $currentUser->getLanguageId());
$currentModule = new PicoModule($appConfig, $database, null, "/", "index", $appLanguage->getHalamanDepan());

require_once __DIR__ . "/inc.app/header-supervisor.php";
?>
<div class="page page-jambi page-list">
	<div class="jambi-wrapper">
		<div class="filter-section">
			<form action="" method="get" class="filter-form">
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getProyek();?></span>
					<span class="filter-control">
                        <select name="proyek_id" class="form-control">
							<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
							<?php 
							$supervisorProyek = new SupervisorProyek(null, $database);
							$specs1 = PicoSpecification::getInstance()
								->add(['supervisorId', $currentLoggedInSupervisor->getSupervisorId()])
								->add(['supervisor.aktif', true])
								->add(['proyek.aktif', true])
							;
							$sorts1 = PicoSortable::getInstance()
								->add(['proyek.proyekId', PicoSort::ORDER_TYPE_ASC])
							;
							try
							{
								$pageData1 = $supervisorProyek->findAll($specs1, null, $sorts1, true);
								$rows = $pageData1->getResult();
								foreach($rows as $row)
								{
									$proyek = $row->getProyek();
									if($proyek != null)
									{
									?>
									<option value="<?php echo $proyek->getProyekId();?>"<?php echo $proyek->getProyekId() == $inputGet->getProyekId() ? ' selected':'';?>><?php echo $proyek->getNama();?></option>
									<?php
									}
								}
							}
							catch(Exception $e)
							{
								// do nothing
							}
							?>
						</select>
					</span>
				</span>
				<span class="filter-group">
					<span class="filter-label"><?php echo $appEntityLanguage->getLokasiProyek();?></span>
					    <span class="filter-control">
							<select name="lokasi_proyek_id" class="form-control">
								<option value=""><?php echo $appLanguage->getLabelOptionSelectOne();?></option>
								<?php echo AppFormBuilder::getInstance()->createSelectOption(new LokasiProyekMin(null, $database), 
								PicoSpecification::getInstance()
									->addAnd(new PicoPredicate(Field::of()->aktif, true))
									->addAnd(new PicoPredicate(Field::of()->proyekId, $inputGet->getProyekId())), 
								PicoSortable::getInstance()
									->add(new PicoSort(Field::of()->nama, PicoSort::ORDER_TYPE_ASC)), 
								Field::of()->lokasiProyekId, Field::of()->nama, $inputGet->getLokasiProyekId())
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
			
		</div>
	</div>
</div>
<?php 
require_once __DIR__ . "/inc.app/footer-supervisor.php";

