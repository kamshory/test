<?php

namespace Sipro\Util;

use Sipro\Entity\Data\AkhirPekan;
use Sipro\Entity\Data\Cuti;
use Sipro\Entity\Data\HariLibur;
use Sipro\Entity\Data\PerjalananDinas;

class TimeSheetUtil{
    
    /**
     * Perjalanan dinas
     *
     * @var PerjalananDinas[]
     */
    private $perjalananDinas;
    
    /**
     * Cuti
     *
     * @var Cuti[]
     */
    private $cuti;
    
    /**
     * Hari libur
     *
     * @var HariLibur[]
     */
    private $hariLibur;
    
    /**
     * Akhir pekan
     *
     * @var AkhirPekan[]
     */
    private $akhirPekan;
    
    /**
     * Kode akhir pekan
     *
     * @var string[]
     */
    private $daftarKodeAkhirPekan;
    
    /**
     * Tanggal libur
     *
     * @var string[]
     */
    private $daftarTanggalLibur;
    
    /**
     * Tanggal
     *
     * @var string[]
     */
    private $tanggal;
    
    public function __construct()
    {
        $this->perjalananDinas = array();
        $this->cuti = array();
        $this->hariLibur = array();
        $this->akhirPekan = array();
        $this->tanggal = array();
        $this->daftarKodeAkhirPekan = array();
        $this->daftarTanggalLibur = array();
    }
    
    /**
     * Add perjalanan dinas
     *
     * @param PerjalananDinas $perjalananDinas
     * @return void
     */
    public function addPerjalananDinas($perjalananDinas)
    {
        $this->perjalananDinas[] = $perjalananDinas;
        return $this;
    }
    
    /**
     * Add cuti
     *
     * @param Cuti $cuti
     * @return void
     */
    public function addCuti($cuti)
    {
        $this->cuti[] = $cuti;
        return $this;
    }
    
    /**
     * Add hari libur
     *
     * @param HariLibur $hariLibur
     * @return void
     */
    public function addHariLibur($hariLibur)
    {
        $this->hariLibur[] = $hariLibur;
        if(!$hariLibur->getBuka())
        {
            $this->daftarTanggalLibur[] = $hariLibur->getTanggal();
        }
        return $this;
    }
    
    /**
     * Add akhir pekan
     *
     * @param AkhirPekan $akhirPekan
     * @return void
     */
    public function addAkhirPekan($akhirPekan)
    {
        $this->akhirPekan[] = $akhirPekan;
        $this->daftarKodeAkhirPekan[] = trim(strtolower($akhirPekan->getKodeHari()));
        return $this;
    }
    
    /**
     * Add tanggal
     *
     * @param string[] $tanggal
     * @return self
     */
    public function addTanggal($tanggal)
    {
        if(isset($tanggal) && is_array($tanggal))
        {
            $values = array_values($tanggal);
            foreach($values as $value)
            {
                $this->tanggal[] = $value;
            }
        }
        return $this;
    }
    
    /**
     * Cek akhir pekan
     *
     * @param string $tanggalBerjalan
     * @return boolean
     */
    public function isAkhirPekan($tanggalBerjalan)
    {
        $kodeHari = trim(strtolower(date("D", strtotime($tanggalBerjalan))));
        return in_array($kodeHari, $this->daftarKodeAkhirPekan);
    }
    
    /**
     * Cek hari libur
     *
     * @param string $tanggalBerjalan
     * @return boolean
     */
    public function isHariLibur($tanggalBerjalan)
    {
        return in_array($tanggalBerjalan, $this->daftarTanggalLibur);
    }
    
    /**
     * Get info cuti
     *
     * @param string $tanggalBerjalan
     * @return Cuti
     */
    public function getInfoCuti($tanggalBerjalan)
    {
        foreach($this->cuti as $cuti)
        {
            $ts1 = strtotime($tanggalBerjalan);
            $ts2 = strtotime($cuti->getCutiDari());
            $ts3 = strtotime($cuti->getCutiHingga());
            if($cuti != null && $ts1 >= $ts2 && $ts1 <= $ts3)
            {
                return $cuti;
            }
        }
        return new Cuti();
    }
}