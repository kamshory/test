<?php

namespace Sipro\Util;

use Exception;
use MagicApp\AppLanguage;
use MagicApp\Field;
use MagicObject\Database\PicoDatabase;
use MagicObject\Database\PicoDatabaseQueryBuilder;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use Sipro\Entity\Data\AcuanPengawasan;
use Sipro\Entity\Data\AkhirPekan;
use Sipro\Entity\Data\HariLibur;
use Sipro\Entity\Data\MaterialPekerjaan;
use Sipro\Entity\Data\MaterialProyek;
use Sipro\Entity\Data\PeralatanPekerjaan;
use Sipro\Entity\Data\PeralatanProyek;

class CommonUtil
{
    /**
     * Build list
     * @param string[] $arr2
     * @return string
     */
    public static function buildList($arr2)
    {
        return "<ol>".self::buildListItem($arr2)."</ol>";
    }
    
    /**
     * Build list item
     * @param string[] $arr2
     * @return string
     */
    public static function buildListItem($arr2)
    {
        if($arr2 == null || empty($arr2))
        {
            return "";
        }
        return "<li>".implode("</li><li>", $arr2)."</li>";
    }

    /**
     * Get peralatan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return PicoPageData
     */
    public static function getPeralatan($database, $pekerjaanId)
    {
        $specs = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->pekerjaanId, $pekerjaanId));
        $obj1 = new PeralatanProyek(null, $database);
        return $obj1->findAll($specs);
    }

    /**
     * Get peralatan inline
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getPeralatanInline($database, $pekerjaanId)
    {
        $arr2 = array();
        try
        {
            $arr = self::getPeralatan($database, $pekerjaanId);
            foreach($arr->getResult() as $val)
            {
                if($val->hasValuePeralatan())
                {
                    $value = $val->getPeralatan()->getNama();
                    $jumlah = trim($val->getJumlah()." ".$val->getPeralatan()->getSatuan());
                    $value .= " [".$jumlah."]";
                    $arr2[] = $value;
                }
            }
            return self::buildList($arr2);
        }
        catch(Exception $e)
        {
            return "";
        }
    }

    /**
     * Get material
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return PicoPageData
     */
    public static function getMaterial($database, $pekerjaanId)
    {
        $specs = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->pekerjaanId, $pekerjaanId));
        $obj1 = new MaterialProyek(null, $database);
        return $obj1->findAll($specs);
    }

    /**
     * Get material inline
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getMaterialInline($database, $pekerjaanId)
    {
        $arr2 = array();
        try
        {
            $arr = self::getMaterial($database, $pekerjaanId);
            foreach($arr->getResult() as $val)
            {
                if($val->hasValuePeralatan())
                {
                    $value = $val->getMaterial()->getNama();
                    $jumlah = trim($val->getJumlah()." ".$val->getMaterial()->getSatuan());
                    $value .= " [".$jumlah."]";
                    $arr2[] = $value;
                }
            }
            return self::buildList($arr2);
        }
        catch(Exception $e)
        {
            return "";
        }
    }

    /**
     * Get acuan pengawasan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return PicoPageData
     */
    public static function getAcuanPengawasan($database, $pekerjaanId)
    {
        $specs = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals('acuanPengawasanPekerjaan.pekerjaanId', $pekerjaanId));
        $obj1 = new AcuanPengawasan(null, $database);
        return $obj1->findAll($specs);
    }

    /**
     * Get acuan pengawasan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getAcuanPengawasanList($database, $pekerjaanId)
    {
        $arr2 = array();
        try
        {
            $arr = self::getAcuanPengawasan($database, $pekerjaanId);
            foreach($arr->getResult() as $val)
            {
                if($val->hasValueAcuanPengawasanPekerjaan())
                {
                    $arr2[] = $val->getNama();
                }
            }
            return self::buildList($arr2);
        }
        catch(Exception $e)
        {
            return "";
        }
    }


    /**
     * Get periode
     * @param MagicObject $entity
     * @param string $periodeId
     * @param AppLanguage $appLanguage
     * @return string
     */
    public static function getPeriode($entity, $periodeId, $appLanguage)
    {
        $tableInfo = $entity->tableInfo();
        $tableName = $tableInfo->getTableName();
        $tanggal = $tableInfo->getColumns()[Field::of()->tanggal]['name'];

        $database = $entity->currentDatabase();

        $queryBuilder1 = new PicoDatabaseQueryBuilder($database);
        $queryBuilder2 = new PicoDatabaseQueryBuilder($database);

        $queryBuilder2->newQuery()
            ->select("max($tableName.$tanggal)")
            ->from($tableName)
            ->where("$tableName.$tanggal is not null and $tableName.$tanggal != '0000-00-00'");

        $queryBuilder1->newQuery()
            ->select("min($tableName.$tanggal) as tanggal_awal, ($queryBuilder2) as tanggal_akhir")
            ->from($tableName)
            ->where("$tableName.$tanggal is not null and $tableName.$tanggal != '0000-00-00'");

        $data = new MagicObject($database->fetch($queryBuilder1));
        $arrayPeriode = self::createMonthRange($data->getTanggalAwal(), $data->getTanggalAkhir());

        if($periodeId == 0)
        {
            $periodeId = $arrayPeriode[0][0];
        }
        $options = array();
        foreach($arrayPeriode as $val)
        {
            $selected = $val[0] == $periodeId ? ' selected="selected"' : '';
            $options[] = '<option value="'.$val[0].'"'.$selected.'>'.DateUtil::translateDate($appLanguage, $val[1]).'</option>';
        }
        return implode("\r\n", $options);
    }

    public static function createMonthRange($date1, $date2)
    {
        if($date1 == "0000-00-00" || $date2 == "0000-00-00")
        {
            return array();
        }
        
        $array_bulan = array(
            1=>'January',
            2=>'February',
            3=>'March',
            4=>'April',
            5=>'May',
            6=>'June',
            7=>'July',
            8=>'August',
            9=>'September',
            10=>'October',
            11=>'November',
            12=>'December'
        );

        $arr1 = explode("-", $date1);
        $arr2 = explode("-", $date2);
        $year1 = $arr1[0];
        $year2 = $arr2[0];
        
        $month1 = $arr1[1];
        $month2 = $arr2[1];
        
        $result = array();
        
        for($i = $year1; $i <= $year2; $i++)
        {
            if($i == $year1)
            {
                $mstart = $month1;
            }
            else
            {
                $mstart = 1;
            }
            if($i == $year2)
            {
                $mstop = $month2;
            }
            else
            {
                $mstop = 12;
            }
            for($j = $mstart; $j <= $mstop; $j++)
            {
                $result[] = array(sprintf("%04d%02d", $i, $j), $array_bulan[$j]." $i");
            }
        }
        return array_reverse($result);
    }
    
    /**
     * Get calendar class
     *
     * @param array $tanggal
     * @return string
     */
    public static function getCalendarClass($tanggal)
    {
        if(!isset($tanggal))
        {
            return "";
        }
        $classes = array();
        if (self::isTrue($tanggal['akhir_pekan'])) {
            $classes[] = "weekend";
        }
        if (self::isTrue($tanggal['tanggal_merah'])) {
            $classes[] = "dayoff";
        }
        if (self::isTrue($tanggal['cuti'])) {
            $classes[] = "leave";
        }
        return implode(" ", $classes);
    }
    
    /**
     * Check if value is true
     *
     * @param mixed $object
     * @return boolean
     */
    public static function isTrue($object)
    {
        return isset($object) && $object == 1;
    }

    public static function getAcuanPengawasanPekerjaan($database, $type = "list", $pekerjaan_id = null, $value = null)
    {
        $add = "";
        if($pekerjaan_id != null)
        {
            $pekerjaan_id = intval($pekerjaan_id);
            $add .= ", (select `acuan_pengawasan_pekerjaan`.`acuan_pengawasan_pekerjaan_id` from `acuan_pengawasan_pekerjaan` 
            where `acuan_pengawasan_pekerjaan`.`pekerjaan_id` = '$pekerjaan_id' 
            and `acuan_pengawasan_pekerjaan`.`acuan_pengawasan_id` = `acuan_pengawasan`.`acuan_pengawasan_id` group by `acuan_pengawasan_pekerjaan_id` limit 0,1) 
            as `acuan_pengawasan_pekerjaan_id`";
        }
        $sql = "select `acuan_pengawasan`.* $add 
        from `acuan_pengawasan`
        where `acuan_pengawasan`.`aktif` = '1'
        order by `acuan_pengawasan`.`nama` asc
        ";
        $rows = $database->fetchAll($sql);
        $html = "";
        if($type == "check")
        {
            $html .= "<ul class=\"list-in-cell type-check\">\r\n";
            
            foreach($rows as $data)
            {
                $val = "";
                if($pekerjaan_id != null && $data['acuan_pengawasan_pekerjaan_id'])
                {
                    $val .= " checked=\"checked\"";
                }
                $html .= "<li><label><input type=\"checkbox\" name=\"acuan_pengawasan_id[]\" value=\"".$data['acuan_pengawasan_id']."\"$val> ".$data['nama']."</label></li>\r\n";
            }
            $html .= "</ul>\r\n";
        }
        else if($type == "select")
        {
            foreach($rows as $data)
            {
                $val = "";
                if($pekerjaan_id != null && $data['acuan_pengawasan_pekerjaan_id'])
                {
                    if($data['acuan_pengawasan_id'] == $value)
                    {
                        $val .= " selected=\"selected\"";
                    }
                    $html .= "<option value=\"".$data['acuan_pengawasan_id']."\" data-kelas-pondasi-id=\"".$data['kelas_pondasi_id']."\" data-tipe-tower-id=\"".$data['tipe_tower_id']."\" data-lokasi-proyek-id=\"".$data['lokasi_proyek_id']."\" data-kegiatan=\"".$data['kegiatan']."\" $val> ".$data['nama']."</option>\r\n";
                }
            }
        }
        else
        {
            $html .= "<ul class=\"list-in-cell type-list\">\r\n";
            foreach($rows as $data)
            {
                $val = "";
                if($pekerjaan_id != null && $data['acuan_pengawasan_pekerjaan_id'])
                {
                    $html .= "<li>".$data['nama']."</li>\r\n";
                }
            }
            $html .= "</ul>\r\n";
        }
        return $html;
    }

    /**
     * Check if date if holiday
     * @param PicoDatabase $database
     * @param string $date
     * @return boolean
     */
    public static function isHariLibur($database, $date)
    {
        $kodeHari = strtoupper(date('D', strtotime($date)));

        $akhirPekan = new AkhirPekan(null, $database);
        $hariLibur = new HariLibur(null, $database);

        $nlibur = $akhirPekan->countByKodeHari($kodeHari);
        if($nlibur == 0)
        {
            $nlibur = $hariLibur->countByTanggal($date);
        }
        return $nlibur > 0;
    }

    /**
     * Get material pekerjaan
     * @param PicoDatabase $database
     * @param string $type
     * @param integer $materialId
     * @param mixed $value
     * @return string
     */
    public static function getMaterialPekerjaan($database, $type = "list", $materialId = null, $value = null)
    {
        $add = "";
        if($materialId != null)
        {
            $add .= ", (select `material_pekerjaan`.`material_pekerjaan_id` from `material_pekerjaan` 
            where `material_pekerjaan`.`material_id` = '$materialId' 
            and `material_pekerjaan`.`jenis_pekerjaan_id` = `jenis_pekerjaan`.`jenis_pekerjaan_id` group by `material_pekerjaan_id` limit 0,1) 
            as `material_pekerjaan_id`";
        }
        $sql = "select `jenis_pekerjaan`.* $add 
        from `jenis_pekerjaan`
        where `jenis_pekerjaan`.`aktif` = '1'
        order by `jenis_pekerjaan`.`nama` asc
        ";
        $rows = $database->fetchAll($sql);
        $html = "";
        if($type == "check")
        {
            $html .= "<ul class=\"list-in-cell type-check\">\r\n";
            foreach($rows as $data)
            {
                $val = "";
                if($materialId != null && $data['material_pekerjaan_id'])
                {
                    $val .= " checked=\"checked\"";
                }
                $html .= "<li><label><input type=\"checkbox\" name=\"jenis_pekerjaan_id[]\" value=\"".$data['jenis_pekerjaan_id']."\"$val> ".$data['nama']."</label></li>\r\n";
            }
            $html .= "</ul>\r\n";
        }
        else if($type == "select")
        {
            foreach($rows as $data)
            {
                $val = "";
                if($materialId != null && $data['material_pekerjaan_id'])
                {         
                    if($data['jenis_pekerjaan_id'] == $value)
                    {
                        $val .= " selected=\"selected\"";
                    }
                    $html .= "<option value=\"".$data['jenis_pekerjaan_id']."\" data-kelas-pondasi-id=\"".$data['kelas_pondasi_id']."\" data-tipe-tower-id=\"".$data['tipe_tower_id']."\" data-lokasi-proyek-id=\"".$data['lokasi_proyek_id']."\" data-kegiatan=\"".$data['kegiatan']."\" $val> ".$data['nama']."</option>\r\n";    
                }
            }
        }
        else
        {
            $html .= "<ul class=\"list-in-cell type-list\">\r\n";
            foreach($rows as $data)
            {
                $val = "";
                if($materialId != null && $data['material_pekerjaan_id'])
                {
                    $html .= "<li>".$data['nama']."</li>\r\n";
                }
            }
            $html .= "</ul>\r\n";
        }
        return $html;
    }

    /**
     * Update material pekerjaan
     * @param PicoDatabase $database Database connection
     * @param array $jenisPekerjaan Jenis Pekerjaan
     * @param integer $materialId Material ID
     * @return void
     */
    public static function updateMaterialPekerjaan($database, $jenisPekerjaan, $materialId)
    {
        $materialPekerjaan = new MaterialPekerjaan(null, $database);
        $materialPekerjaan->deleteByMaterialId($materialId);
        if(isset($jenisPekerjaan) && is_array($jenisPekerjaan))
        {
            foreach($jenisPekerjaan as $jenisPekerjaanId)
            {
                $materialPekerjaan = new MaterialPekerjaan(null, $database);
                $materialPekerjaan->setMaterialId($materialId);
                $materialPekerjaan->setJenisPekerjaanId($jenisPekerjaanId);
                $materialPekerjaan->setAktif(true);
                $materialPekerjaan->insert();
            }
        }
    }

    public static function getPeralatanPekerjaan($database, $type = "list", $peralatanId = null, $value = null)
    {
        $add = "";
        if($peralatanId != null)
        {
            $add .= ", (select `peralatan_pekerjaan`.`peralatan_pekerjaan_id` from `peralatan_pekerjaan` 
            where `peralatan_pekerjaan`.`peralatan_id` = '$peralatanId' 
            and `peralatan_pekerjaan`.`jenis_pekerjaan_id` = `jenis_pekerjaan`.`jenis_pekerjaan_id` group by `peralatan_pekerjaan_id` limit 0,1) 
            as `peralatan_pekerjaan_id`";
        }
        $sql = "select `jenis_pekerjaan`.* $add 
        from `jenis_pekerjaan`
        where `jenis_pekerjaan`.`aktif` = '1'
        order by `jenis_pekerjaan`.`nama` asc
        ";
        $rows = $database->fetchAll($sql);
        $html = "";
        if($type == "check")
        {
            $html .= "<ul class=\"list-in-cell type-check\">\r\n";
            foreach($rows as $data)
            {
                $val = "";
                if($peralatanId != null && $data['peralatan_pekerjaan_id'])
                {
                    $val .= " checked=\"checked\"";
                }
                $html .= "<li><label><input type=\"checkbox\" name=\"jenis_pekerjaan_id[]\" value=\"".$data['jenis_pekerjaan_id']."\"$val> ".$data['nama']."</label></li>\r\n";
            }
            $html .= "</ul>\r\n";
        }
        else if($type == "select")
        {
            foreach($rows as $data)
            {
                $val = "";
                if($peralatanId != null && $data['peralatan_pekerjaan_id'])
                {                
                    if($data['jenis_pekerjaan_id'] == $value)
                    {
                        $val .= " selected=\"selected\"";
                    }
                    $html .= "<option value=\"".$data['jenis_pekerjaan_id']."\" data-kelas-pondasi-id=\"".$data['kelas_pondasi_id']."\" data-tipe-tower-id=\"".$data['tipe_tower_id']."\" data-lokasi-proyek-id=\"".$data['lokasi_proyek_id']."\" data-kegiatan=\"".$data['kegiatan']."\" $val> ".$data['nama']."</option>\r\n";
                }
            }
        }
        else
        {
            $html .= "<ul class=\"list-in-cell type-list\">\r\n";
            foreach($rows as $data)
            {
                $val = "";
                if($peralatanId != null && $data['peralatan_pekerjaan_id'])
                {
                    $html .= "<li>".$data['nama']."</li>\r\n";
                }
            }
            $html .= "</ul>\r\n";
        }
        return $html;
    }

    /**
     * Update peralatan pekerjaan
     * @param PicoDatabase $database Database connection
     * @param array $jenisPekerjaan Jenis Pekerjaan
     * @param integer $materialId Material ID
     * @return void
     */
    public static function updatePeralatanPekerjaan($database, $jenisPekerjaan, $peralatanId)
    {
        $peralatanPekerjaan = new PeralatanPekerjaan(null, $database);
        $peralatanPekerjaan->deleteByPeralatanId($peralatanId);

        if(isset($jenisPekerjaan) && is_array($jenisPekerjaan))
        {
            foreach($jenisPekerjaan as $jenisPekerjaanId)
            {
                $peralatanPekerjaan = new PeralatanPekerjaan(null, $database);
                $peralatanPekerjaan->setPeralatanId($peralatanId);
                $peralatanPekerjaan->setJenisPekerjaanId($jenisPekerjaanId);
                $peralatanPekerjaan->setAktif(true);
                $peralatanPekerjaan->insert();
            }
        }
    }
}