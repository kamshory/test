<?php

use MagicApp\Field;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\Util\PicoStringUtil;
use Sipro\Entity\Data\AcuanPengawasanForList;
use Sipro\Entity\Data\BukuHarian;

require_once dirname(__DIR__) . "/inc.app/auth.php";
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=\"Buku Harian.xls\"");

$buhar = new BukuHarian(null, $database);
try{
    $pageData = $dataLoader->findAll($specification, $pageable, $sortable, true, $subqueryMap, MagicObject::FIND_OPTION_NO_FETCH_DATA);
    if($pageData->getTotalResult() > 0)
    {		
        $pageControl = $pageData->getPageControl(Field::of()->page, $currentModule->getSelf())
        ->setNavigation(
            $dataControlConfig->getPrev(), $dataControlConfig->getNext(),
            $dataControlConfig->getFirst(), $dataControlConfig->getLast()
        )
        ->setPageRange($dataControlConfig->getPageRange())
        ;
        ?>

        <table class="table table-row table-sort-by-column" border="1">
            <thead>
                <tr>   
                    <td class="data-controll data-number"><?php echo $appLanguage->getNumero();?></td>
                    <td data-col-name="tanggal" class="order-controll"><?php echo $appEntityLanguage->getTanggal();?></td>
                    <td data-col-name="supervisor_id" class="order-controll"><?php echo $appEntityLanguage->getSupervisor();?></td>
                    <td><?php echo $appLanguage->getLokasi();?></td>
                    <td data-col-name="kegiatan" class="order-controll"><?php echo $appEntityLanguage->getKegiatan();?></td>
                    <td><?php echo $appLanguage->getAcuanPengawasan();?></td>
                    <td><?php echo $appLanguage->getBillOfQuantity();?></td>
                    <td><?php echo $appLanguage->getVolume();?></td>
                    <td><?php echo $appLanguage->getTercapai();?></td>
                    <td><?php echo $appLanguage->getPersen();?></td>
                    <td><?php echo $appLanguage->getPermasalahan();?></td>
                    <td><?php echo $appLanguage->getRekomendasi();?></td>
                    <td><?php echo $appLanguage->getTindakLanjut();?></td>
                    <td><?php echo $appLanguage->getManPower();?></td>
                    <td><?php echo $appLanguage->getMaterial();?></td>
                    <td><?php echo $appLanguage->getPeralatan();?></td>
                    <td data-col-name="acc_ktsk" class="order-controll"><?php echo $appEntityLanguage->getAccKtsk();?></td>
                    <td data-col-name="latitude" class="order-controll"><?php echo $appEntityLanguage->getLatitude();?></td>
                    <td data-col-name="longitude" class="order-controll"><?php echo $appEntityLanguage->getLongitude();?></td>
                    <td data-col-name="altitude" class="order-controll"><?php echo $appEntityLanguage->getAltitude();?></td>
                </tr>
            </thead>
        
            <tbody data-offset="<?php echo $pageData->getDataOffset();?>">
                <?php 
                $dataIndex = 0;
                while($bukuHarian = $pageData->fetch())
                {
                    $dataIndex++;
                ?>

                <tr data-number="<?php echo $pageData->getDataOffset() + $dataIndex;?>" data-active="<?php echo $bukuHarian->optionAktif('true', 'false');?>">                
                    <td class="data-number"><?php echo $pageData->getDataOffset() + $dataIndex;?></td>
                    <td data-col-name="tanggal"><?php echo $bukuHarian->getTanggal();?></td>
                    <td data-col-name="supervisor_id"><?php echo $bukuHarian->issetSupervisor() ? $bukuHarian->getSupervisor()->getNama() : "";?></td>
                    <td><?php
                    $lokasiProyekObj = $buhar->getLokasiProyek($bukuHarian->getBukuHarianId());
                    $lokasiPekerjaans = $buhar->getSerialOfScalar($lokasiProyekObj);
                    if(!empty($lokasiPekerjaans))
                    {
                        echo '<ol style="padding-left:20px;"><li>'.implode("</li>\r\n<li>", $lokasiPekerjaans)."</li><ol>";
                    }
                    ?></td>
                    <td data-col-name="kegiatan"><?php echo $bukuHarian->getKegiatan();?></td>
                    <td><?php
                    $acuanPengawasanObj = $buhar->getAcuanPengawasanList($bukuHarian->getBukuHarianId());
                    $acuanPengawasanIds = $buhar->getSerialOfScalar($acuanPengawasanObj);
                    $acuanPengawasanFilder = new AcuanPengawasanForList(null, $database);
                    try
                    {
                        if(!empty($acuanPengawasanIds))
                        {
                        $pdt = $acuanPengawasanFilder->findAll(PicoSpecification::getInstance()->add([Field::of()->acuanPengawasanId, $acuanPengawasanIds]));
                        ?>
                        <ol style="padding-left:20px;">
                        <?php
                        foreach($pdt->getResult() as $acuanPengawasan)
                        {
                            ?>
                            <li><?php
                            $hirarki = $acuanPengawasan->issetJenisHirarkiKontrak() ? trim($acuanPengawasan->getJenisHirarkiKontrak()->getNama()) : "";
                            $nama = trim($acuanPengawasan->getNama());
                            $nomor = trim($acuanPengawasan->getNomor());
                            echo '['.$hirarki.'] ['.$nama.'] ['.$nomor.']';
                            ?>
                            </li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                        }
                    }
                    catch(Exception $e)
                    {
                        // Do nothing
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    $boqObj = $buhar->getBillOfQuantityProyek($bukuHarian->getBukuHarianId());
                    if(!empty($boqObj))
                    {
                        ?>
                        <ol style="padding-left:20px;">
                        <?php
                        $nline = array();
                        foreach($boqObj as $idx=>$boq)
                        {
                            $billOfQuantity = PicoStringUtil::wordChunk($boq->bill_of_quantity, 25);
                            $nline[$idx] = substr_count($billOfQuantity, "\n");
                            ?>
                            <li><?php echo nl2br($billOfQuantity);?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    if(!empty($boqObj))
                    {
                        ?>
                        <ol style="list-style-type:none; padding-left:0px;">
                        <?php
                        foreach($boqObj as $idx=>$boq)
                        {
                            $brs = str_repeat('<br />', $nline[$idx]);
                            ?>
                            <li><?php echo $boq->volume.$brs;?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    if(!empty($boqObj))
                    {
                        ?>
                        <ol style="list-style-type:none; padding-left:0px;">
                        <?php
                        foreach($boqObj as $idx=>$boq)
                        {
                            $brs = str_repeat('<br />', $nline[$idx]);
                            ?>
                            <li><?php echo $boq->volume_proyek.$brs;?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    if(!empty($boqObj))
                    {
                        ?>
                        <ol style="list-style-type:none; padding-left:0px;">
                        <?php
                        foreach($boqObj as $boq)
                        {
                            $brs = str_repeat('<br />', $nline[$idx]);
                            ?>
                            <li><?php echo number_format($boq->persen, 2).$brs;?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                        <?php
                        $permasalahanBoq = $buhar->getPermasalahanPekerjaan($bukuHarian->getBukuHarianId());
                        if(!empty($permasalahanBoq))
                        {
                            ?>
                            <ol style="padding-left:20px;">
                            <?php
                            $nline = array();
                            foreach($permasalahanBoq as $idx=>$permasalahan)
                            {
                                ?>
                                <li><?php echo $permasalahan->permasalahan;?></li>
                                <?php
                            }
                            ?>
                            </ol>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if(!empty($permasalahanBoq))
                        {
                            ?>
                            <ol style="padding-left:20px;">
                            <?php
                            $nline = array();
                            foreach($permasalahanBoq as $idx=>$permasalahan)
                            {
                                ?>
                                <li><?php echo $permasalahan->rekomendasi;?></li>
                                <?php
                            }
                            ?>
                            </ol>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if(!empty($permasalahanBoq))
                        {
                            ?>
                            <ol style="padding-left:20px;">
                            <?php
                            $nline = array();
                            foreach($permasalahanBoq as $idx=>$permasalahan)
                            {
                                ?>
                                <li><?php echo $permasalahan->tindak_lanjut;?></li>
                                <?php
                            }
                            ?>
                            </ol>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                    <?php
                    $manPowerObj = $buhar->getManPowerProyek($bukuHarian->getBukuHarianId());
                    if(!empty($manPowerObj))
                    {
                        ?>
                        <ol style="padding-left:20px;">
                        <?php
                        foreach($manPowerObj as $idx=>$manPower)
                        {
                            ?>
                            <li><?php echo $manPower->man_power. ' ['.$manPower->jumlah_pekerja.']';?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    $materialObj = $buhar->getMaterialProyek($bukuHarian->getBukuHarianId());
                    if(!empty($materialObj))
                    {
                        ?>
                        <ol style="padding-left:20px;">
                        <?php
                        foreach($materialObj as $idx=>$materialProyek)
                        {
                            $textNode = sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $materialProyek->nama, $materialProyek->satuan, $materialProyek->onsite, $materialProyek->terpasang);
                            ?>
                            <li><?php echo $textNode;?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td>
                    <?php
                    $peralatanObj = $buhar->getPeralatanProyek($bukuHarian->getBukuHarianId());
                    if(!empty($peralatanObj))
                    {
                        ?>
                        <ol style="padding-left:20px;">
                        <?php
                        foreach($peralatanObj as $idx=>$peralatanProyek)
                        {
                            ?>
                            <li><?php echo $peralatanProyek->peralatan. ' ['.$peralatanProyek->jumlah.']';?></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                    </td>
                    <td data-col-name="acc_ktsk"><?php echo $bukuHarian->optionAccKtsk($appLanguage->getYes(), $appLanguage->getNo());?></td>
                    <td data-col-name="latitude"><?php echo $bukuHarian->getLatitude();?></td>
                    <td data-col-name="longitude"><?php echo $bukuHarian->getLongitude();?></td>
                    <td data-col-name="altitude"><?php echo $bukuHarian->getAltitude();?></td>
                </tr>
                <?php 
                }
                ?>

            </tbody>
        </table>    

    <?php 
    }
}
catch(Exception $e)
{
    //
} 
