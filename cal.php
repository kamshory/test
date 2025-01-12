<?php

use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\SetterGetter;
use Sipro\Entity\Data\BukuHarianMin;
use Sipro\Util\CalendarUtil;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

$inputGet = new InputGet();

$calendar = new CalendarUtil(2024, 8, 0, true);

$cal = $calendar->getCalendar();
$calInline = $calendar->getCalendarInline();

$startDate = $calendar->getStartDate();
$endDate = $calendar->getEndDate();

$proyekId = $inputGet->getProyekId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
if(isset($proyekId) && !empty($proyekId))
{
    $proyekId = intval($proyekId);
}
$specs = PicoSpecification::getInstance()
->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $supervisorId))
->addAnd(PicoPredicate::getInstance()->equals('proyekId', $proyekId))
->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals('tanggal', $startDate.' 00:00:00'))
->addAnd(PicoPredicate::getInstance()->lessThan('tanggal', $endDate.' 00:00:00'))
;

$bukuHarianFinder = new BukuHarianMin(null, $database);

$buhar = array();
$startTime = strtotime($startDate);
$endTime = strtotime($endDate);

$class = 'kosong';
for($i = $startTime; $i<$endTime; $i+=86400)
{
    $tanggal = date('Y-m-d', $i);
    $buhar[$tanggal] = (new SetterGetter())
        ->setBukuHarianId(null)
        ->setTanggal($tanggal)
        ->setClass($class)
        ;
}
try
{
    $pageData = $bukuHarianFinder->findAll($specs);
    foreach($pageData->getResult() as $bukuHarian)
    {
        $tanggal = $bukuHarian->getTanggal();
        $class = $bukuHarian->getAccKoordinator() ? 'sudah-acc':'belum-acc';
        $buhar[$tanggal] = (new SetterGetter())
            ->setBukuHarianId($bukuHarian->getBukuHarianId())
            ->setTanggal($tanggal)
            ->setClass($class)
            ;
    }
}
catch(Exception $e)
{
    // 
}

foreach($buhar as $btn)
{
    //echo $btn."<br><br>";
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
    .calendar
    {
        margin: 0 auto;
        max-width: 360px;
    }
    .calendar td
    {
        text-align: center;
        font-size: 10px;
    }
    button.calendar-button
    {
        width: 100%;
        font-size: 12px;
        color: #555555;
        padding: 8px 0;
        border-radius: 3px;
        border: 1px solid #919293;
    }
    button.calendar-button.cur-month
    {
        border: 1px solid #2187B9;    
    }
    
    button.calendar-button.belum-acc{
        background-color: orange;
        color: white;
    }
    button.calendar-button.sudah-acc{
        background-color: green;
        color: white;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pilih semua elemen dengan kelas 'buku-harian-button'
        const elements = document.querySelectorAll('.buku-harian-button');
        // Loop melalui setiap elemen dan tambahkan event listener
        elements.forEach(function(element) {
            element.addEventListener('click', function(e) {
                // Tambahkan kode yang ingin dijalankan saat elemen diklik di sini
                let elem = e.target;
                let proyek_id = elem.getAttribute('data-proyek-id');
                let buku_harian_id = elem.getAttribute('data-buku-harian-id');
                let tanggal = elem.getAttribute('data-tanggal');
                openUrl(proyek_id, tanggal, buku_harian_id);
            });
        });
    });
    function openUrl(proyek_id, tanggal, buku_harian_id)
    {
        if(buku_harian_id == '')
        {
            window.location = 'buku-harian.php?user_action=create&proyek_id='+proyek_id+'&tanggal='+tanggal;
        }
        else
        {
            window.location = 'buku-harian.php?user_action=update&buku_harian_id='+buku_harian_id;
        }
    }
</script>
<div class="calendar">
    <table width="100%">
        <tbody>
            <tr>
                <td>Min</td>
                <td>Sen</td>
                <td>Sel</td>
                <td>Rab</td>
                <td>Kam</td>
                <td>Jum</td>
                <td>Sab</td>
            </tr>
            <?php
            foreach($cal as $row)
            {
                ?>
                <tr>
                <?php
                foreach($row as $col)
                {
                    ?>
                    <td width="14%">
                        <?php
                        if($col['print'])
                        {
                            $class = $col['class'];
                            $tanggal = $col['date'];
                            $class2 = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getClass() : "";
                            $bukuHarianId = isset($buhar[$tanggal]) ? $buhar[$tanggal]->getBukuHarianId() : "";
                            $class = $class.' '.$class2;
                        ?>
                        <button data-proyek-id="<?php echo $proyekId;?>" data-tanggal="<?php echo $tanggal;?>" data-buku-harian-id="<?php echo $bukuHarianId;?>" class="calendar-button buku-harian-button <?php echo $class;?>"><?php echo $col['day'];?></button>
                        <?php
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>