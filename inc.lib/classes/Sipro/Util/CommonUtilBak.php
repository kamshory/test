<?php

namespace Sipro\Util;

use MagicObject\Database\PicoDatabase;
use PDO;

class CommonUtilBak
{

    /**
     * Get peralatan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return array[]
     */
    public static function getPeralatan($database, $pekerjaanId)
    {
        $sql = "select `peralatan_proyek`.* , `peralatan`.`nama` as `peralatan`, `peralatan`.`satuan` as `satuan`
        from `peralatan_proyek` 
        left join (`peralatan`) on(`peralatan`.`peralatan_id` = `peralatan_proyek`.`peralatan_id`) 
        where `peralatan_proyek`.`pekerjaan_id` = '$pekerjaanId' ";
        return $database->fetchAll($sql, PDO::FETCH_ASSOC);
    }

    /**
     * Get peralatan inline
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getPeralatanInline($database, $pekerjaanId)
    {
        $arr = self::getPeralatan($database, $pekerjaanId);
        $arr2 = array();
        if($arr == null || empty($arr))
        {
            return "";
        }
        foreach($arr as $val)
        {
            $value = $val['peralatan'];
            if($val['jumlah'])
            {
                $jumlah = trim($val['jumlah']." ".$val['satuan']);
                $value .= " [".$jumlah."]";
            }
            $arr2[] = $value;
        }
        if(count($arr2))
        {
            return "<ol><li>".implode("</li><li>", $arr2)."</li></ol>";
        }
        else
        {
            return "";
        }
    }

    /**
     * Get material
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return array[]
     */
    public static function getMaterial($database, $pekerjaanId)
    {
        $sql = "select `material_proyek`.* , `material`.`nama` as `material`, `material`.`satuan` as `satuan`
        from `material_proyek` 
        left join (`material`) on(`material`.`material_id` = `material_proyek`.`material_id`) 
        where `material_proyek`.`pekerjaan_id` = '$pekerjaanId' ";
        return $database->fetchAll($sql, PDO::FETCH_ASSOC);
    }

    /**
     * Get material inline
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getMaterialInline($database, $pekerjaanId)
    {
        $arr = self::getMaterial($database, $pekerjaanId);

        if($arr == null || empty($arr))
        {
            return "";
        }
        $arr2 = array();
        foreach($arr as $val)
        {
            $value = $val['material'];
            if($val['jumlah'])
            {
                $jumlah = trim($val['jumlah']." ".$val['satuan']);
                $value .= " [".$jumlah."]";
            }
            $arr2[] = $value;
        }
        if(count($arr2))
        {
            return "<ol><li>".implode("</li><li>", $arr2)."</li></ol>";
        }
        else
        {
            return "";
        }
    }

    /**
     * Get acuan pengawasan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return array[]
     */
    public static function getAcuanPengawasan($database, $pekerjaanId)
    {
        $sql = "select `acuan_pengawasan`.`nama`, `acuan_pengawasan`.`acuan_pengawasan_id` from `acuan_pengawasan`
        inner join `acuan_pengawasan_pekerjaan` on `acuan_pengawasan_pekerjaan`.`acuan_pengawasan_id` = `acuan_pengawasan`.`acuan_pengawasan_id`
        where `acuan_pengawasan_pekerjaan`.`pekerjaan_id` = '$pekerjaanId' ";
        return $database->fetchAll($sql, PDO::FETCH_ASSOC);
    }

    /**
     * Get acuan pengawasan
     * @param PicoDatabase $database
     * @param integer $pekerjaanId
     * @return string
     */
    public static function getAcuanPengawasanList($database, $pekerjaanId)
    {
        $arr = self::getAcuanPengawasan($database, $pekerjaanId);
        if($arr == null || empty($arr))
        {
            return "";
        }
        $list = array();
        foreach($arr as $val)
        {
            $list[] = $val['nama'];
        }
        return "<ol><li>".implode("</li><li>", $list); 
    }
}