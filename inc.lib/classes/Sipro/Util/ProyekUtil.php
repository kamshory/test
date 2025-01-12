<?php
namespace Sipro\Util;

use Exception;
use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\Cache;
use stdClass;

class ProyekUtil
{
    public static function getDaftarProyek($database, $hari, $lifetime = 3600)
    {
        $cacheKey = 'daftar-proyek';
        $expire = date('Y-m-d H:i:s', time() + $lifetime);
        $now = date('Y-m-d H:i:s');

        $specsCache = PicoSpecification::getInstance()
        ->addAnd(PicoPredicate::getInstance()->equals(Field::of()->cacheId, $cacheKey))
        ->addAnd(PicoPredicate::getInstance()->greaterThan(Field::of()->expire, $now))
        ;
        $cache = new Cache(null, $database);
        $textConverter = new TextConverter();
        try
        {          
            $cache->findOne($specsCache);        
            $content = $cache->getContent();
            $value = json_decode($content, true);
            $result = new stdClass;
            $result->daftarProyek = $value['daftarProyek'];
            $result->proyekDipilih = $value['proyekDipilih'];
            $result->daftarNamaSupervisor = $value['daftarNamaSupervisor'];
        }
        catch(Exception $e)
        {
            $proyekDipilih = array();
   
            $specsBukuHarian = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals(Field::of()->waktuBuat, date('Y-m-d H:i:s', strtotime("-$hari days"))))
            ->addAnd(PicoPredicate::getInstance()->lessThanOrEquals(Field::of()->waktuBuat, date('Y-m-d H:i:s', strtotime("1 days"))))
            ;
            $sortsBukuHarian = PicoSortable::getInstance()
            ->addSortable(new PicoSort(Field::of()->waktuBuat, PicoSort::ORDER_TYPE_ASC))
            ;
            $finderBukuHarian = new BukuHarian(null, $database);
            $daftarProyek = array();
            $daftarNamaSupervisor = array();
            try
            {
                $pageDataBukuHarian = $finderBukuHarian->findAll($specsBukuHarian, null, $sortsBukuHarian);
                foreach($pageDataBukuHarian->getResult() as $bukuHarian)
                {
                    $idx = intval($bukuHarian->getProyekId());
                    $proyekDipilih[$idx] = array(
                    'proyek_id'=>$bukuHarian->getProyekId(), 
                    'nama'=>$bukuHarian->issetProyek() ? $textConverter->execute($bukuHarian->getProyek()->getNama()) : '',
                    'persen'=>$bukuHarian->issetProyek() ? floatval($bukuHarian->getProyek()->getPersen()) : 0
                    );
                    $daftarProyek[] = intval($bukuHarian->getProyekId());
                    $proyekId = $bukuHarian->getProyekId();
                    if($bukuHarian->hasValueSupervisor())
                    {
                        if(!isset($daftarNamaSupervisor[$proyekId]))
                        {
                            $daftarNamaSupervisor[$proyekId] = array();
                        }
                        $daftarNamaSupervisor[$proyekId][] = $bukuHarian->getSupervisor()->getNama();
                        $daftarNamaSupervisor[$proyekId] = array_unique($daftarNamaSupervisor[$proyekId]);

                    }                 
                }
            }
            catch(Exception $e)
            {
            // do nothing
            }

            $daftarProyek = array_unique($daftarProyek);

            $result = new stdClass;
            $result->daftarProyek = $daftarProyek;
            $result->proyekDipilih = $proyekDipilih;
            $result->daftarNamaSupervisor = $daftarNamaSupervisor;

            $cache = new Cache(null, $database);
            $cache->setCacheId($cacheKey)->setContent(json_encode($result))->setExpire($expire);
            $cache->save();
        }    
        return $result;
    } 
}