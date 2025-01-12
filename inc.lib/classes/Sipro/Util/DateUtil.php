<?php

namespace Sipro\Util;

class DateUtil
{
    public static function translateDate($appLanguage, $dateString)
    {
        $arr1_en = self::getMonthArray();
        $arr1_id = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        
        $arr2_en = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $arr2_id = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
        
        $arr3_en = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $arr3_id = array('Minggu', 'Senin',  'Selasa',  'Rabu',      'Kamis',    'Jumat',  'Sabtu');
        
        $arr4_en = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $arr4_id = array('Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab');
        
        $dateString = str_replace($arr1_en, $arr1_id, $dateString); 
        $dateString = str_replace($arr3_en, $arr3_id, $dateString); 
        $dateString = str_replace($arr2_en, $arr2_id, $dateString); 
        $dateString = str_replace($arr4_en, $arr4_id, $dateString); 
        
        return $dateString;
        
    }
    
    public static function getMonthArray()
    {
        return array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',  'September', 'October', 'November', 'December');
    }
}