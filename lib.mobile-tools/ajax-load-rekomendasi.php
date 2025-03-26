<?php

use MagicApp\AppEntityLanguage;
use MagicApp\Field;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\Rekomendasi;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$dataFilter = PicoSpecification::getInstance()->add([Field::of()->ditutup, false]);
$inputGet = new InputGet();
$inputPost = new InputPost();

if($inputPost->getUserAction() == 'set-status' 
    && $inputPost->getRekomendasiId() != 0
)
{
    $status = $inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    $rekomendasiId = $inputPost->getRekomendasiId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    try
    {
        $rekomendasi = new Rekomendasi(null, $database);
        $rekomendasi->setRekomendasiId($rekomendasiId)->setDitutup($status)->update();
    }
    catch(Exception $e)
    {
        // Do nothing
    }
    exit();
}

if($inputPost->getRekomendasi() != null 
    && $inputPost->getRekomendasi() != "" 
    && $inputPost->getProyekId() != null 
    && $inputPost->getProyekId() != ""
)
{
    try
    {
        $rekomendasi = new Rekomendasi(null, $database);
        if($inputPost->getRekomendasiId() != null && $inputPost->getRekomendasiId() != "")
        {
            $rekomendasi->setRekomendasiId($inputPost->getRekomendasiId());
        }
        $rekomendasi->setProyekId($inputPost->getProyekId());
        $rekomendasi->setRekomendasi($inputPost->getRekomendasi());
        $rekomendasi->setDitutup(false);
        $rekomendasi->setAktif(true);
        $rekomendasi->setSupervisorId($currentAction->getUserId());
        $rekomendasi->setWaktuBuat($currentAction->getTime());
        $rekomendasi->setIpBuat($currentAction->getIp());
        $rekomendasi->setWaktuUbah($currentAction->getTime());
        $rekomendasi->setIpUbah($currentAction->getIp());
        $rekomendasi->save();
    }
    catch(Exception $e)
    {
        // Do nothing
    }
}

$appEntityLanguage = new AppEntityLanguage(new Rekomendasi(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"rekomendasi" => "rekomendasi",
	"supervisorId" => "supervisorId",
	"ditutup" => "ditutup",
	"sortOrder" => "sortOrder",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security recommendations
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security recommendations
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));

$sortable->add([Field::of()->timeCreate, PicoSort::ORDER_TYPE_DESC]);

$dataLoader = new Rekomendasi(null, $database);

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

?>
    <form action="" method="post" class="data-form-edit">
        <textarea class="form-control" name="rekomendasi" placeholder="<?php echo $appLanguage->getPlaceholderWriteRecommendationHere();?>"></textarea>
        <input type="hidden" name="rekomendasi_id">
    </form>

    
    <form action="" method="post" class="data-form-list">
    <?php
try{
    $pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
    if($pageData->getTotalResult() > 0)
    {		
?>
        <div class="data-wrapper">
            <table class="table table-rekomendasi" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <td width="20">
                            <span class="fa fa-edit"></span>
                        </td>
                        <td class="data-controll data-number" width="30"><?php echo $appLanguage->getNumero();?></td>
                        <td data-col-name="rekomendasi" class="order-controll"><?php echo $appEntityLanguage->getRekomendasi();?></td>
                        <td data-col-name="ditutup" class="order-controll" width="80"><label><input type="checkbox" disabled> <?php echo $appEntityLanguage->getDitutup();?></label></td>
                    </tr>
                </thead>
                <tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
                    <?php 
                    $dataIndex = 0;
                    while($rekomendasi = $pageData->fetch())
                    {
                        $dataIndex++;
                    ?>

                    <tr data-primary-key="<?php echo $rekomendasi->getRekomendasiId();?>" 
                        data-sort-order="<?php echo $rekomendasi->getSortOrder();?>" 
                        data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" 
                        data-active="<?php echo $rekomendasi->optionAktif('true', 'false');?>"  
                        data-rekomendasi-id="<?php echo $rekomendasi->getRekomendasiId();?>" 
                        data-rekomendasi="<?php echo $rekomendasi->getRekomendasi();?>"
                        >
                        <td>
                            <a class="edit-control edit-recommendation" href="javascript:;"><span class="fa fa-edit"></span></a>
                        </td>
                        <td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
                        <td data-col-name="rekomendasi"><?php echo $rekomendasi->getRekomendasi();?></td>
                        <td data-col-name="ditutup"><label><input type="checkbox"<?php echo $rekomendasi->optionDitutup(" checked", "");?>></label> <?php echo $appEntityLanguage->getDitutup();?></td>
                    </tr>
                    <?php 
                    }
                    ?>

                </tbody>
            </table>
        </div>
                
<?php 
}
else
{
    ?>
    <div class="alert alert-info"><?php echo $appLanguage->getMessageDataNotFound();?></div>
    <?php
}
}
catch(Exception $e)
{
    ?>
    <div class="alert alert-danger"><?php echo $appInclude->printException($e);?></div>
    <?php
} 
?>
</form>    