<?php

use MagicApp\AppEntityLanguage;
use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Util\File\FileUtil;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\Pekerjaan;
use Sipro\Util\CommonUtil;
use Sipro\Util\DateUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$bukuHarianId = $inputGet->getBukuHarianId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
$bukuHarian = new BukuHarian(null, $database);
try {
    $bukuHarian->find($bukuHarianId);
    if ($bukuHarian->hasValueBukuHarianId()) {
        $tanggal_supervisi = substr($bukuHarian->getWaktuBuat(), 0, 10);

        $appEntityLanguage = new AppEntityLanguage(new BukuHarian(), $appConfig, $currentUser->getLanguageId());
        // define map here
        $proyek = new MagicObject();
        if ($bukuHarian->hasValueProyek()) {
            $proyek = $bukuHarian->getProyek();
        }
        $umk = new MagicObject();
        $tsk = new MagicObject();
        $ktsk = new MagicObject();
        $telepon = "";
        $email = "";
        $website = "";
        $faksimili = "";
        $alamat = "";

        if ($proyek->hasValueKtsk()) {
            $ktsk = $proyek->getKtsk();
            
            if ($ktsk->hasValueTsk()) {
                $tsk = $ktsk->getTsk();
                if($tsk->hasValueUmk())
                {
                    $umk = $tsk->getUmk();
                }     
                $telepon = $tsk->getTelepon();
                $email = $tsk->getEmail();
                $website = $tsk->getWebsite();
                $faksimili = $tsk->getFaksimili();
                $alamat = $tsk->getAlamat();
            }
        }

        $x = array(
            1 => 'cerah', 
            2 => 'berawan', 
            3 => 'hujan', 
            4 => 'hujan-lebat'
        );
        $data_cuaca = array();
        for ($i = 0; $i < 24; $i++) {
            $tt = sprintf("%02d", $i);
            $tv = $bukuHarian->get('c_' . $tt);
            $data_cuaca[$tt] = isset($x[$tv]) ? $x[$tv] : "";
            $proyek_id = $bukuHarian->getProyekId();
        }

?>
        <style type="text/css">
            .buku-harian * {
                font-family: Tahoma, Geneva, sans-serif;
                font-size: 12px;
            }

            .buku-harian p {
                line-height: 1.45;
                margin: 2px 0;
            }

            .content-header h1 {
                font-size: 18px;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                font-weight: normal;
            }

            .content-header p {
                margin: 0;
                padding: 0;
                line-height: 1.3;
            }

            .info-table,
            .main-table {
                border-collapse: collapse;
            }

            .info-table td,
            .main-table td {
                padding: 4px 5px;
                border: 1px solid #555555;
            }

            .main-table td {
                vertical-align: text-top;
            }

            .main-table thead td {
                font-weight: bold;
            }
            td .signature-container{
                margin-top: -3px;
                margin-bottom: -3px;
            }
        </style>

        <div class="buku-harian">
        <div id="report-container" data-id="<?php echo $bukuHarianId; ?>" data-tanggal="<?php echo $tanggal_supervisi; ?>">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="content-header image-container">
                <tr>
                    <td width="70"><img src="../lib.assets/report/image/logo-pln-80.png" /></td>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="4" align="center">
                                    <h1>PT PLN (PERSERO) PUSMANPRO <?php echo $umk->getNama(); ?> <?php echo strtoupper($tsk->getNama()); ?></h1>
                                    <p><?php echo $alamat; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td width="55">
                                    <p>Website</p>
                                </td>
                                <td style="white-space: nowrap">
                                    <p>: <?php echo $website; ?></p>
                                </td>
                                <td width="55">
                                    <p>Telepon</p>
                                </td>
                                <td style="white-space: nowrap">
                                    <p>: <?php echo $telepon; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>E-Mail</p>
                                </td>
                                <td>
                                    <p>: <?php echo $email; ?></p>
                                </td>
                                <td>
                                    <p>Faksimili</p>
                                </td>
                                <td>
                                    <p>: <?php echo $faksimili; ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right" width="140"><img src="../lib.assets/report/image/logo-kan-60.png" width="130" height="60" /></td>
                </tr>
            </table>

            <p style="font-size:6px; line-height:1; padding:0; margin:0;">&nbsp;</p>
            <div>
                <table width="100%" border="1" cellpadding="0" cellspacing="0" class="info-table">
                    <tr>
                        <td width="120">Pekerjaan</td>
                        <td><?php echo $proyek->getPekerjaan(); ?></td>
                        <td colspan="2"><strong>CATATAN HARIAN <em>SUPERVISOR</em></strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Kontrak</td>
                        <td><?php echo $proyek->getNomorKontrak(); ?></td>
                        <td width="80">Hari</td>
                        <td width="140"><?php echo DateUtil::translateDate($appLanguage, date('l', strtotime($bukuHarian->getTanggal()))); ?></td>
                    </tr>
                    <tr>
                        <td>Pelaksana</td>
                        <td><?php echo $proyek->getPelaksana(); ?></td>
                        <td>Tanggal</td>
                        <td><span id="tanggal-supervisi"><?php echo DateUtil::translateDate($appLanguage, date('j F Y', strtotime($bukuHarian->getTanggal()))); ?></span></td>
                    </tr>
                    <tr>
                        <td>Pemberi Kerja</td>
                        <td><?php echo $proyek->getPemberiKerja(); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>

            <?php
            $arr = array();

            $specification = PicoSpecification::getInstance()
                ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->bukuHarianId, $bukuHarianId))
                ;

            $sortable = PicoSortable::getInstance()
                ->add(new PicoSort('jenisPekerjaan.sortOrder', PicoSort::ORDER_TYPE_ASC))
                ->add(new PicoSort('pekerjaanId', PicoSort::ORDER_TYPE_ASC))
            ;

            $pekerjaanList = new Pekerjaan(null, $database);
            try{
                $pageData = $pekerjaanList->findAll($specification, null, $sortable);
                foreach($pageData->getResult() as $row)
                {
                    $jenisPekerjaan = $row->hasValueJenisPekerjaan() ? $row->getJenisPekerjaan()->getNama() : null;
                    if(isset($jenisPekerjaan))
                    {
                        if(!isset($arr[$jenisPekerjaan]))
                        {
                            $arr[$jenisPekerjaan] = array();
                        }
                        $arr[$jenisPekerjaan][] = $row;
                    }
                }
            }
            catch(Exception $e)
            {
                // do nothing
            }

            $romans = array(
                1 => "I", 
                2 => "II", 
                3 => "III", 
                4 => "IV", 
                5 => "V", 
                6 => "VI", 
                7 => "VII", 
                8 => "VIII", 
                9 => "IX", 
                10 => "X"
            );
            $i = 1;
            ?>
            <p style="font-size:6px; line-height:1; padding:0; margin:0;">&nbsp;</p>
            <div class="main-container">
                <table width="100%" cellpadding="0" cellspacing="0" border="1" class="main-table">
                    <thead>
                        <tr>
                            <td width="20">No</td>
                            <td width="48%">Lokasi/Kegiatan/Waktu</td>
                            <td>Uraian</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($arr as $key => $val) {
                        ?>
                            <tr>
                                <td colspan="3">
                                    <h3><?php echo $romans[$i]; ?>. <?php echo strtoupper($key); ?></h3>
                                </td>
                            </tr>
                            <?php
                            if (count($val)) {
                            ?>
                                <?php
                                foreach ($val as $key2 => $val2) {
                                ?>
                                    <tr>
                                        <td align="right"><?php echo $key2 + 1; ?></td>
                                        <td>
                                            <div>Pekerjaan : <?php echo $val2->getKegiatan(); ?></div>
                                            <div>Lokasi : <?php echo $val2->hasValueLokasiProyek() ? $val2->getLokasiProyek()->getNama() : ""; ?></div>
                                            <?php
                                            $kelas_pondasi = $val2->hasValueKelasPondasi() ? $val2->getKelasPondasi()->getNama() : "";
                                            if ($kelas_pondasi != '') {
                                            ?>
                                                <div>Tipe Pondasi : <?php echo $kelas_pondasi; ?></div>
                                            <?php
                                            }
                                            $tipe_tower = $val2->hasValueTipeTower() ? $val2->getTipeTower()->getNama() : "";
                                            if ($tipe_tower != '') {
                                            ?>
                                                <div>Kelas Tower : <?php echo $tipe_tower; ?></div>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div>Jumlah Pekerja : <?php echo $val2->getJumlahPekerja(); ?> orang</div>
                                            <?php
                                            $peralatan = CommonUtil::getPeralatanInline($database, $val2->getPekerjaanId());
                                            if ($peralatan != '') {
                                            ?>
                                                <div>Peralatan Kerja : <?php echo $peralatan; ?></div>
                                            <?php
                                            }
                                            $material = CommonUtil::getMaterialInline($database, $val2->getPekerjaanId());
                                            if ($material != '') {
                                            ?>
                                                <div>Material : <?php echo $material; ?></div>
                                            <?php
                                            }
                                            ?>
                                            <div>Acuan Pengawasan : <?php echo CommonUtil::getAcuanPengawasanList($database, $val2->getPekerjaanId()); ?></div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <p style="font-size:6px; line-height:1; padding:0; margin:0;">&nbsp;</p>

            <table class="main-table" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse" border="1">
                <tr>
                    <td style="padding:5px">
                        <p>Permasalahan : <?php echo $bukuHarian->getPermasalahan(); ?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px">
                        <p>Rekomendasi : <?php echo $bukuHarian->getRekomendasi(); ?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px">
                        <p>Komentar Koordinator : <?php echo $bukuHarian->getKomentarKoordinator(); ?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px">
                        <p>Komentar KTSK : <?php echo $bukuHarian->getKomentarKtsk(); ?></p>
                    </td>
                </tr>
            </table>

            <p style="font-size:6px; line-height:1; padding:0; margin:0;">&nbsp;</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
                <tr>
                    <td align="center">
                        <p>AM (00:00 – 11:59)</p>
                        <div class="cuaca-image">
                            <canvas id="canvas1" style="width:280px; height:280px;"></canvas>
                        </div>
                    </td>
                    <td align="center">
                        <p>PM (12:00 – 23:59)</p>
                        <div class="cuaca-image">
                            <canvas id="canvas2" style="width:280px; height:280px;"></canvas>
                        </div>
                    </td>
                </tr>
            </table>
            <p style="font-size:6px; line-height:1; padding:0; margin:0;">&nbsp;</p>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="20%" align="center" valign="top">
                        <strong>KTSK</strong>
                        <div style="height:120px; overflow:visible" class="image-container signature-container">
                            <?php
                            if ($bukuHarian->getAccKtsk()) {
                                $signaturePath1 = dirname(dirname(__FILE__)) . "/lib.signature/ktsk/" . $bukuHarian->getKtskId() . "/signature.png";
                                $signaturePath1 = FileUtil::fixFilePath($signaturePath1);
                                if(file_exists($signaturePath1))
                                {
                                    $filetime = filemtime($signaturePath1);
                            ?>
                            <img class="signature-image" style="height:120px; width:120px" src="../lib.signature/ktsk/<?php echo $bukuHarian->getKtskId(); ?>/signature.png?_=<?php echo $filetime; ?>" />
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <div><?php echo $ktsk->getNama(); ?></div>
                    </td>
                    <td width="60%" align="center" valign="top"><strong>Koordinator</strong>
                        <?php
                        if ($bukuHarian->hasValueKoordinator()) {
                            
                        ?>
                            <div style="height:120px; overflow:visible" class="image-container signature-container">
                            <?php
                            $signaturePath2 = dirname(dirname(__FILE__)) . "/lib.signature/supervisor/" . $bukuHarian->getKoordinatorId() . "/signature.png";
                            $signaturePath2 = FileUtil::fixFilePath($signaturePath2);
                            if(file_exists($signaturePath2))
                            {
                                $filetime = filemtime($signaturePath2);
                            ?>
                            <img class="signature-image" style="height:120px; width:120px" src="../lib.signature/supervisor/<?php echo $bukuHarian->getKoordinatorId(); ?>/signature.png?_=<?php echo $filetime; ?>" />
                            <?php
                            }
                            ?>
                            </div>
                            <div><?php echo $bukuHarian->getKoordinator()->getNama(); ?></div>
                        <?php
                        }
                        ?>
                    </td>
                    <td width="20%" align="center" valign="top"><strong>Supervisor</strong>
                        <div style="height:120px; overflow:visible" class="image-container signature-container">
                        <?php
                            $signaturePath3 = dirname(dirname(__FILE__)) . "/lib.signature/supervisor/" . $bukuHarian->getSupervisorId() . "/signature.png";
                            if(file_exists($signaturePath3))
                            {
                                $filetime = filemtime($signaturePath3);
                            ?>
                        <img class="signature-image" width="120" height="120" src="../lib.signature/supervisor/<?php echo $bukuHarian->getSupervisorId(); ?>/signature.png?_=<?php echo $filetime; ?>" />
                        <?php
                            }
                        ?>
                        </div>
                        <div><span id="nama-supervisor"><?php echo $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor()->getNama() : ""; ?></span></div>
                    </td>
                </tr>
            </table>
        </div>
        </div>
        <?php
        if($appConfig->getSite()->getSignatureImageTransparent())
        {
        ?>
        <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/transparent-image.min.js"></script>
        <?php
        }
        ?>
        <?php
        if($appConfig->getSite()->getSignatureImageMonochrome())
        {
        ?>
        <style>
            .signature-container
            {
                filter: saturate(0%) contrast(104%);
            }
        </style>
        <?php
        }
        ?>

        <script type="text/javascript">
            var dataCuaca = <?php echo json_encode($data_cuaca); ?>;
            var img = {};
            img['juring'] = '../lib.assets/mobile-style/images/juring-12.png';
            img['cerah'] = '../lib.assets/mobile-style/images/cerah.png';
            img['berawan'] = '../lib.assets/mobile-style/images/berawan.png';
            img['hujan'] = '../lib.assets/mobile-style/images/hujan.png';
            img['hujan-lebat'] = '../lib.assets/mobile-style/images/hujan-lebat.png';

            function downloadReport() {
                var content = $('#report-container').html();
                var html = '<div>' + content + '</div>';
                var doc = $(html);
                doc = convertCanvasToBase64Canvas(doc);
                doc = convertCanvasToBase64Image(doc);
                doc = replaceBase(doc);
                doc.find('.cuaca-image img').css({
                    'width': '200px',
                    'height': '200px'
                });
                doc.find('.cuaca-image img').attr({
                    'width': '200',
                    'height': '200'
                });
                doc.find('.signature-image img').attr({
                    'width': '120',
                    'height': '120'
                });
                var content = doc.html();
                var title = 'Buku Harian';
                var style = '<style type="text/css">body{font-family:"Times New Roman", Times, serif; font-size:16px; position:relative;line-height:100%;} table td{font-family:"Times New Roman", Times, serif; font-size:14px;} table[border="1"]{border-collapse:collapse; box-sizing:border-box; max-width:100%;} table[border="1"] td{padding:4px 5px;} table[border="0"] td{padding:4px 0;} p, li{line-height:1.5;} a{color:#000000; text-decoration:none;} h1{font-size:30px;} h2{font-size:26px;} h3{font-size:22px;} h4{font-size:16px;} .info-table{border-collapse:collapse;} .info-table td{ border:1px solid #000000; padding:4px 4px;} .info-table td p{line-height:1; margin:0px; padding:0px;} table p{line-height:10px;margin:0px;padding:0px;} h1{font-size:16px} table.content-header td{padding:0px;}  .main-container{margin:20px 0 20px 0; } .main-table{border-collapse:collapse;} .main-table td{padding:4px; vertical-align:top;} .main-table td h3{font-size:16px; margin:0;padding:0;} .main-table thead td{font-weight:bold;} .content-header td, .content-header td{padding:0;margin:0} .content-header td h1, .content-header td p, .content-header td table td p{margin:0;padding:0; line-height:1;} </style>';
                content = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"><title>' + title + '</title>' + style + '</head><body style="position:relative;">' + content + '</body></html>';
                var converted = htmlDocx.asBlob('<!DOCTYPE html>' + content, {
                    orientation: 'portrait'
                });
                var buku_harian_id = $('#report-container').attr('data-id');
                var tanggal_supervisi = $('#report-container').attr('data-tanggal');
                var nama_supervisor = $('#nama-supervisor').text().trim();
                saveAs(converted, nama_supervisor + '-' + tanggal_supervisi + '-' + buku_harian_id + '.docx');
            }

            function convertCanvasToBase64Canvas(doc) {
                var regularImages = doc.find('canvas');
                var canvas = document.createElement('canvas');
                [].forEach.call(regularImages, function(obj) {
                    var imgElement = obj;
                    var id = imgElement.getAttribute('id');
                    var dataURL = document.getElementById(id).toDataURL();
                    var newImage = document.createElement('img');
                    newImage.setAttribute('src', dataURL);
                    newImage.style.width = canvas.width + 'px';
                    newImage.style.maxWidth = '100%';
                    newImage.style.height = 'auto';
                    newImage.removeAttribute('height');
                    $(imgElement).replaceWith(newImage.outerHTML);
                });
                canvas.remove();
                return doc;
            }

            function convertCanvasToBase64Image(doc) {
                var regularImages = doc.find('.image-container img');
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                [].forEach.call(regularImages, function(obj) {
                    var imgElement = obj;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    if($(obj).attr('width') && $(obj).attr('height'))
                    {
                        canvas.width = parseInt($(obj).attr('width'));
                        canvas.height = parseInt($(obj).attr('height'));
                    }
                    else
                    {
                        canvas.width = imgElement.width;
                        canvas.height = imgElement.height;
                    }
                    
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

            function convertRelToAbsUrl(url) {
                var baseUrl = null;
                if (/^(https?|file|ftps?|mailto|javascript|data:image\/[^;]{2,9};):/i.test(url)) {
                    return url; // url is already absolute
                }
                baseUrl = window.location.href.match(/^(.+)\/?(?:#.+)?$/)[0] + '/';
                if (url.substring(0, 2) === '//') {
                    return location.protocol + url;
                }
                if (url.charAt(0) === '/') {
                    return location.protocol + '//' + location.host + url;
                }
                if (url.substring(0, 2) === './') {
                    url = '.' + url;
                } else if (/^\s*$/.test(url)) {
                    return ''; // empty = return nothing
                }
                url = baseUrl + '../' + url;
                while (/\/\.\.\//.test(url)) {
                    url = url.replace(/[^\/]+\/+\.\.\//g, '');
                }
                url = url.replace(/\.$/, '').replace(/\/\./g, '').replace(/"/g, '%22')
                    .replace(/'/g, '%27').replace(/</g, '%3C').replace(/>/g, '%3E');
                return url;
            }
            window.onload = function() {
                $(document).on('click', '#download', function(e) {
                    downloadReport();
                });
                preloadImage(img, function() {
                    var canvas1 = document.getElementById('canvas1');
                    canvas1.width = 280;
                    canvas1.height = 280;
                    var context1 = canvas1.getContext('2d');
                    var juring = new Image();
                    juring.src = img.juring;
                    context1.drawImage(juring, 0, 0, 280, 280, 0, 0, 280, 280);
                    var i, j, k, l;
                    for (i = 0; i < 12; i++) {
                        if (i < 10) {
                            j = '0' + i;
                        } else {
                            j = i;
                        }
                        k = (Math.PI * i / 6) - (Math.PI / 12) - (Math.PI / 2);
                        var pin = [];
                        if (typeof dataCuaca[j] != 'undefined') {
                            pin[i] = new Image();
                            pin[i].src = img[dataCuaca[j]];
                            var x = Math.round(Math.cos(k) * 85) + 140 - 17;
                            var y = Math.round(Math.sin(k) * 85) + 140 - 17;
                            context1.drawImage(pin[i], 0, 0, 34, 34, x, y, 34, 34);
                        }
                    }
                });

                preloadImage(img, function() {
                    var canvas2 = document.getElementById('canvas2');
                    canvas2.width = 280;
                    canvas2.height = 280;
                    var context2 = canvas2.getContext('2d');
                    var juring = new Image();
                    juring.src = img.juring;
                    context2.drawImage(juring, 0, 0, 280, 280, 0, 0, 280, 280);
                    var i, j, k, l;
                    for (i = 12; i < 24; i++) {
                        if (i < 10) {
                            j = '0' + i;
                        } else {
                            j = i;
                        }
                        k = (Math.PI * i / 6) - (Math.PI / 12) - (Math.PI / 2);
                        var pin = [];
                        if (typeof dataCuaca[j] != 'undefined') {
                            pin[i] = new Image();
                            pin[i].src = img[dataCuaca[j]];
                            var x = Math.round(Math.cos(k) * 85) + 140 - 17;
                            var y = Math.round(Math.sin(k) * 85) + 140 - 17;
                            context2.drawImage(pin[i], 0, 0, 34, 34, x, y, 34, 34);
                        }
                    }
                });
            }

            function preloadImage(images, callback) {
                var i;
                var numImg = 5;
                var numLoaded = 0;
                var arr = [];
                for (i in images) {
                    arr[i] = new Image();
                    arr[i].onload = function() {
                        numLoaded++;
                        if (numLoaded == numImg) {
                            callback();
                        }
                    }
                    arr[i].src = images[i];
                }
            }
        </script>


    <?php
    } else {
        // Do somtething here when data is not found
    ?>
        <div class="alert alert-warning"><?php echo $appLanguage->getMessageDataNotFound(); ?></div>
    <?php
    }
} catch (Exception $e) {
    ?>
    <div class="alert alert-danger"><?php echo $e->getMessage(); ?></div>
<?php
}
