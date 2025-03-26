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
use Sipro\Entity\Data\Permasalahan;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$dataFilter = PicoSpecification::getInstance()->add([Field::of()->ditutup, false]);
$inputGet = new InputGet();
$inputPost = new InputPost();

if($inputPost->getUserAction() == 'set-status' 
    && $inputPost->getPermasalahanId() != 0
)
{
    $status = $inputPost->getStatus(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    $permasalahanId = $inputPost->getPermasalahanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    try
    {
        $permasalahan = new Permasalahan(null, $database);
        $waktuTutup = $status ? date('Y-m-d H:i:s') : null;
        $permasalahan->setPermasalahanId($permasalahanId)->setDitutup($status)->setWaktuTutup($waktuTutup)->update();
    }
    catch(Exception $e)
    {
        // Do nothing
    }
    exit();
}
if($inputPost->getPermasalahan() != null 
    && $inputPost->getPermasalahan() != "" 
    && $inputPost->getProyekId() != null 
    && $inputPost->getProyekId() != ""
)
{
    try
    {
        $permasalahan = new Permasalahan(null, $database);
        if($inputPost->getPermasalahanId() != null && $inputPost->getPermasalahanId() != "")
        {
            $permasalahan->setPermasalahanId($inputPost->getPermasalahanId());
        }
        $permasalahan->setProyekId($inputPost->getProyekId());
        $permasalahan->setPermasalahan($inputPost->getPermasalahan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
        $permasalahan->setRekomendasi($inputPost->getRekomendasi(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
        $permasalahan->setTindakLanjut($inputPost->getTindakLanjut(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS));
        $permasalahan->setDitutup(false);
        $permasalahan->setAktif(true);
        $permasalahan->setSupervisorId($currentAction->getUserId());
        $permasalahan->setWaktuBuat($currentAction->getTime());
        $permasalahan->setIpBuat($currentAction->getIp());
        $permasalahan->setWaktuUbah($currentAction->getTime());
        $permasalahan->setIpUbah($currentAction->getIp());
        $permasalahan->save();
    }
    catch(Exception $e)
    {
        // Do nothing
    }
}

$appEntityLanguage = new AppEntityLanguage(new Permasalahan(), $appConfig, $currentUser->getLanguageId());

$specMap = array(
	"proyekId" => PicoSpecification::filter("proyekId", "number"),
	"supervisorId" => PicoSpecification::filter("supervisorId", "number")
);
$sortOrderMap = array(
	"proyekId" => "proyekId",
	"permasalahan" => "permasalahan",
	"supervisorId" => "supervisorId",
	"ditutup" => "ditutup",
	"sortOrder" => "sortOrder",
	"aktif" => "aktif"
);

// You can define your own specifications
// Pay attention to security issues
$specification = PicoSpecification::fromUserInput($inputGet, $specMap);
$specification->addAnd($dataFilter);


// You can define your own sortable
// Pay attention to security issues
$sortable = PicoSortable::fromUserInput($inputGet, $sortOrderMap, array(
	array(
		"sortBy" => "sortOrder", 
		"sortType" => PicoSort::ORDER_TYPE_ASC
	)
));
$sortable->add([Field::of()->timeCreate, PicoSort::ORDER_TYPE_DESC]);

$dataLoader = new Permasalahan(null, $database);

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
        <div class="input-area">
            <textarea class="form-control" name="permasalahan" spellcheck="false" placeholder="<?php echo $appLanguage->getPlaceholderWriteIssueHere();?>"></textarea>
        </div>
        <div class="input-area">
            <textarea class="form-control" name="rekomendasi" spellcheck="false" placeholder="<?php echo $appLanguage->getPlaceholderWriteRecommendationHere();?>"></textarea>
        </div>
        <div class="input-area">
            <textarea class="form-control" name="tindak_lanjut" spellcheck="false" placeholder="<?php echo $appLanguage->getPlaceholderWriteFollowUpHere();?>"></textarea>
        </div>
        <input type="hidden" name="permasalahan_id">
    </form>
    
    <form action="" method="post" class="data-form-list">

    <?php
    try{
        $pageData = $dataLoader->findAll($specification, null, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
        if($pageData->getTotalResult() > 0)
        {		
    ?>
        <div class="data-wrapper">
            <table class="table table-permasalahan" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <td width="20">
                            <span class="fa fa-edit"></span>
                        </td>
                        <td class="data-controll data-number" width="30"><?php echo $appLanguage->getNumero();?></td>
                        <td data-col-name="permasalahan" class="order-controll"><?php echo $appEntityLanguage->getPermasalahan();?></td>
                        <td data-col-name="ditutup" class="order-controll" width="80"><label><input type="checkbox" disabled> <?php echo $appEntityLanguage->getDitutup();?></label></td>
                    </tr>
                </thead>
                <tbody class="data-table-manual-sort" data-offset="<?php echo $pageData->getDataOffset();?>">
                    <?php 
                    $dataIndex = 0;
                    while($permasalahan = $pageData->fetch())
                    {
                        $dataIndex++;
                    ?>

                    <tr data-primary-key="<?php echo $permasalahan->getPermasalahanId();?>" 
                        data-sort-order="<?php echo $permasalahan->getSortOrder();?>" 
                        data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" 
                        data-active="<?php echo $permasalahan->optionAktif('true', 'false');?>" 
                        data-permasalahan-id="<?php echo $permasalahan->getPermasalahanId();?>" 
                        data-permasalahan="<?php echo $permasalahan->getPermasalahan();?>" 
                        data-rekomendasi="<?php echo $permasalahan->getRekomendasi();?>" 
                        data-tindak-lanjut="<?php echo $permasalahan->getTindakLanjut();?>" 
                        >
                        <td>
                            <a class="edit-control edit-issue" href="javascript:;"><span class="fa fa-edit"></span></a>
                        </td>
                        <td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
                        <td data-col-name="permasalahan"><?php echo $permasalahan->getPermasalahan();?></td>
                        <td data-col-name="ditutup"><label><input type="checkbox"<?php echo $permasalahan->optionDitutup(" checked", "");?>> <?php echo $appEntityLanguage->getDitutup();?></label> </td>
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