<?php

use MagicApp\AppEntityLanguage;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use Sipro\Entity\Data\ManPower;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor.php";

$resourceManPower = [];

$inputGet = new InputGet();
$inputPost = new InputPost();
$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
$appEntityLanguage = new AppEntityLanguage(new ManPower(), $appConfig, $currentLoggedInSupervisor->getLanguageId());

$now = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
if($inputPost->getAction() == 'edit')
{
    $proyekId = $inputPost->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    $manPowerId = $inputPost->getManPowerId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    $nama = $inputPost->getNama(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS);
    $pekerjaan = $inputPost->getPekerjaan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS);
    $jumlahPekerja = $inputPost->getJumlahPekerja(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT);
    $manPower = new ManPower(null, $database);
    
    try
    {
        $manPower
            ->setProyekId($proyekId)
            ->setNama($nama)
            ->setPekerjaan($pekerjaan)
            ->setJumlahPekerja($jumlahPekerja)
            ->setWaktuBuat($now)
            ->setWaktuUbah($now)
            ->setIpBuat($ip)
            ->setIpUbah($ip)
            ;
        if($manPowerId != 0)
        {
            $manPower->setManPowerId($manPowerId)->update();
        }
        else
        {
            $manPower->insert();
        }
        error_log($manPower);
    }
    catch(Exception $e)
    {
        // do nothing
    }
    header("Content-type: application/json");
    echo "{}";
    exit();

}
else if($inputGet->getAction() == 'list')
{
    $specs4 = PicoSpecification::getInstance()->add(['proyekId', $proyekId])->add(['aktif', true]);
    error_log($specs4);
    $sorts4 = PicoSortable::getInstance()->add(['sortOrder', 'asc'])->add(['nama', 'asc']);
    $manPower = new ManPower(null, $database);
    try
    {
        
        $pageData4 = $manPower->findAll($specs4, null, $sorts4);
        if($pageData4->getTotalResult() > 0)
        {
            ?>
            <div class="data-wrapper">
                <table class="table table-man-power" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <td width="20">
                                <span class="fa fa-edit"></span>
                            </td>
                            <td class="data-controll data-number" width="30"><?php echo $appLanguage->getNumero();?></td>
                            <td data-col-name="nama" class="order-controll"><?php echo $appEntityLanguage->getNama();?></td>
                            <td data-col-name="jumlah_pekerja" class="order-controll" width="80"><?php echo $appEntityLanguage->getJumlah();?></td>
                        </tr>
                    </thead>
                    <tbody class="data-table-manual-sort" data-label-option-select-one="<?php echo $appLanguage->getLabelOptionSelectOne();?>">
                        <?php 
                        $dataIndex = 0;
                        foreach($pageData4->getResult() as $manPower)
                        {
                            $dataIndex++;

                            $nama = $manPower->getNama();
                            
                            if($manPower->issetPekerjaan())
                            {
                                $nama .= " [".$manPower->getPekerjaan()."]";
                            }
                            $jumlahPekerja = $manPower->getJumlahPekerja();        
                            $nama .= " [".$jumlahPekerja." orang]";
                        ?>

                        <tr data-primary-key="<?php echo $manPower->getManPowerId();?>" 
                            data-sort-order="<?php echo $manPower->getSortOrder();?>" 
                            data-number="<?php echo $dataIndex;?>" 
                            data-active="<?php echo $manPower->optionAktif('true', 'false');?>"  
                            data-man-power-id="<?php echo $manPower->getManPowerId();?>"
                            data-proyek-id="<?php echo $manPower->getProyekId();?>" 
                            data-nama-lengkap="<?php echo $nama;?>"
                            data-nama="<?php echo $manPower->getNama();?>"
                            data-pekerjaan="<?php echo $manPower->getPekerjaan();?>"
                            data-jumlah-pekerja="<?php echo $manPower->getJumlahPekerja();?>"
                            >
                            <td>
                                <a class="edit-control edit-man-power" href="javascript:;"><span class="fa fa-edit"></span></a>
                            </td>
                            <td class="data-number"><?php echo $dataIndex;?></td>
                            <td data-col-name="nama"><?php echo $manPower->getNama();?></td>
                            <td data-col-name="jumlah_pekerja"><?php echo $manPower->getJumlahPekerja();?></td>
                        </tr>
                        <?php 
                        }
                        ?>

                    </tbody>
                </table>
            </div>
            <?php
        }
    }
    catch(Exception $e)
    {
        // do nothing
    }
}
else
{
    $specs4 = PicoSpecification::getInstance()->add(['proyekId', $proyekId])->add(['aktif', true]);
    $sorts4 = PicoSortable::getInstance()->add(['nama', 'asc']);
    echo '<option value="">'.$appLanguage->getLabelOptionSelectOne().'</option>'."\r\n";
    try
    {
        $manPower = new ManPower(null, $database);
        $pageData4 = $manPower->findAll($specs4, null, $sorts4);
        foreach($pageData4->getResult() as $row)
        {
            $nama = $row->getNama();
            if($row->issetPekerjaan())
            {
                $nama .= " [".$row->getPekerjaan()."]";
            }
            $jumlahPekerja = $row->getJumlahPekerja();        
            $nama .= " [".$jumlahPekerja." orang]";
            
            echo '<option value="'.$row->getManPowerId().'" data-jumlah-pekerja="'.$jumlahPekerja.'">'.$nama.'</option>'."\r\n";
            $resourceManPower[] = [$row->getManPowerId(), $nama];
        }
    }
    catch(Exception $e)
    {
        // do nothing
    }
}