<?php

namespace Sipro\Util;

use Exception;
use MagicApp\Field;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\KuotaCuti;

class CutiUtil
{
    /**
     * Set kuota cuti
     *
     * @param PicoDatabase $database Database connection
     * @param int $supervisorId Supervisor ID
     * @param int $tahun Year
     * @param int $kuota Leave quote
     * @return void
     */
    public static function addIfNotExists($database, $supervisorId, $tahun, $kuota)
    {
        $kuotaCuti = new KuotaCuti(null, $database);
        try
        {
            $kuotaCuti->findOneBySupervisorIdAndTahun($supervisorId, $tahun);
            // Do nothing
        }
        catch(Exception $e)
        {
            // Add if not exists
            $now = date('Y-m-d H:i:s');
            $kuotaCuti->setSupervisorId($supervisorId);
            $kuotaCuti->setJenisCutiId(null);
            $kuotaCuti->setTahun($tahun);
            $kuotaCuti->setKuota($kuota);
            $kuotaCuti->setDiambil(0);
            $kuotaCuti->setSisa($kuota);
            $kuotaCuti->setAktif(true);
            $kuotaCuti->setWaktuBuat($now);
            $kuotaCuti->setWaktuUbah($now);
            $kuotaCuti->insert();
        }
    }
    
    public static function getKuotaCutiSoecification($supervisorId, $nextYearThreshold)
    {
        $tahunSekarang = intval(date('Y'));
		$tahunMendatang = $tahunSekarang + 1;
        $specsKuotaCuti = PicoSpecification::getInstance();
        $specsKuotaCuti->addAnd([Field::of()->supervisorId, $supervisorId]);
        if(intval(date('m')) >= $nextYearThreshold)
        {
            $specsKuotaCuti->addAnd([Field::of()->tahun, [$tahunSekarang, $tahunMendatang]]);
        }
        else
        {
            $specsKuotaCuti->addAnd([Field::of()->tahun, $tahunSekarang]);
        }
        return $specsKuotaCuti;
    }
}