<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;
use stdClass;

/**
 * The BukuHarian class represents an entity in the "buku_harian" table.
 *
 * This entity maps to the "buku_harian" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="buku_harian")
 */
class BukuHarian extends MagicObject
{
	/**
	 * Buku Harian ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="buku_harian_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Buku Harian ID")
	 * @var int
	 */
	protected $bukuHarianId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Proyek
	 * 
	 * @JoinColumn(name="proyek_id", referenceColumnName="proyek_id")
	 * @Label(content="Proyek")
	 * @var ProyekMin
	 */
	protected $proyek;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Supervisor
	 * 
	 * @JoinColumn(name="supervisor_id", referenceColumnName="supervisor_id")
	 * @Label(content="Supervisor")
	 * @var SupervisorMin
	 */
	protected $supervisor;

	/**
	 * Tanggal
	 * 
	 * @Column(name="tanggal", type="date", nullable=true)
	 * @Label(content="Tanggal")
	 * @var string
	 */
	protected $tanggal;

	/**
	 * Kegiatan
	 * 
	 * @Column(name="kegiatan", type="longtext", nullable=true)
	 * @Label(content="Kegiatan")
	 * @var string
	 */
	protected $kegiatan;

	/**
	 * Bill Of Quantity ID
	 * 
	 * @Column(name="bill_of_quantity_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Bill Of Quantity ID")
	 * @var int
	 */
	protected $billOfQuantityId;

	/**
	 * Bill Of Quantity
	 * 
	 * @JoinColumn(name="bill_of_quantity_id", referenceColumnName="bill_of_quantity_id")
	 * @Label(content="Bill Of Quantity")
	 * @var BillOfQuantityMin
	 */
	protected $billOfQuantity;

	/**
	 * C 00
	 * 
	 * @Column(name="c_00", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 00")
	 * @var string
	 */
	protected $c_00;

	/**
	 * C 01
	 * 
	 * @Column(name="c_01", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 01")
	 * @var string
	 */
	protected $c_01;

	/**
	 * C 02
	 * 
	 * @Column(name="c_02", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 02")
	 * @var string
	 */
	protected $c_02;

	/**
	 * C 03
	 * 
	 * @Column(name="c_03", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 03")
	 * @var string
	 */
	protected $c_03;

	/**
	 * C 04
	 * 
	 * @Column(name="c_04", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 04")
	 * @var string
	 */
	protected $c_04;

	/**
	 * C 05
	 * 
	 * @Column(name="c_05", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 05")
	 * @var string
	 */
	protected $c_05;

	/**
	 * C 06
	 * 
	 * @Column(name="c_06", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 06")
	 * @var string
	 */
	protected $c_06;

	/**
	 * C 07
	 * 
	 * @Column(name="c_07", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 07")
	 * @var string
	 */
	protected $c_07;

	/**
	 * C 08
	 * 
	 * @Column(name="c_08", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 08")
	 * @var string
	 */
	protected $c_08;

	/**
	 * C 09
	 * 
	 * @Column(name="c_09", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 09")
	 * @var string
	 */
	protected $c_09;

	/**
	 * C 10
	 * 
	 * @Column(name="c_10", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 10")
	 * @var string
	 */
	protected $c_10;

	/**
	 * C 11
	 * 
	 * @Column(name="c_11", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 11")
	 * @var string
	 */
	protected $c_11;

	/**
	 * C 12
	 * 
	 * @Column(name="c_12", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 12")
	 * @var string
	 */
	protected $c_12;

	/**
	 * C 13
	 * 
	 * @Column(name="c_13", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 13")
	 * @var string
	 */
	protected $c_13;

	/**
	 * C 14
	 * 
	 * @Column(name="c_14", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 14")
	 * @var string
	 */
	protected $c_14;

	/**
	 * C 15
	 * 
	 * @Column(name="c_15", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 15")
	 * @var string
	 */
	protected $c_15;

	/**
	 * C 16
	 * 
	 * @Column(name="c_16", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 16")
	 * @var string
	 */
	protected $c_16;

	/**
	 * C 17
	 * 
	 * @Column(name="c_17", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 17")
	 * @var string
	 */
	protected $c_17;

	/**
	 * C 18
	 * 
	 * @Column(name="c_18", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 18")
	 * @var string
	 */
	protected $c_18;

	/**
	 * C 19
	 * 
	 * @Column(name="c_19", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 19")
	 * @var string
	 */
	protected $c_19;

	/**
	 * C 20
	 * 
	 * @Column(name="c_20", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 20")
	 * @var string
	 */
	protected $c_20;

	/**
	 * C 21
	 * 
	 * @Column(name="c_21", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 21")
	 * @var string
	 */
	protected $c_21;

	/**
	 * C 22
	 * 
	 * @Column(name="c_22", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 22")
	 * @var string
	 */
	protected $c_22;

	/**
	 * C 23
	 * 
	 * @Column(name="c_23", type="char(1)", length=1, nullable=true)
	 * @Label(content="C 23")
	 * @var string
	 */
	protected $c_23;

	/**
	 * UMK ID
	 *
	 * @Column(name="umk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="UMK ID")
	 * @var int
	 */
	protected $umkId;

	/**
	 * TSK ID
	 *
	 * @Column(name="tsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="TSK ID")
	 * @var int
	 */
	protected $tskId;

	/**
	 * Ktsk ID
	 * 
	 * @Column(name="ktsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Ktsk ID")
	 * @var int
	 */
	protected $ktskId;

	/**
	 * Ktsk
	 * 
	 * @JoinColumn(name="ktsk_id", referenceColumnName="ktsk_id")
	 * @Label(content="Ktsk")
	 * @var KtskMin
	 */
	protected $ktsk;

	/**
	 * Acc Ktsk
	 * 
	 * @Column(name="acc_ktsk", type="int(11)", length=11, nullable=true)
	 * @Label(content="Acc Ktsk")
	 * @var int
	 */
	protected $accKtsk;

	/**
	 * Status Acc Ktsk
	 * 
	 * @Column(name="status_acc_ktsk", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Status Acc Ktsk")
	 * @var string
	 */
	protected $statusAccKtsk;

	/**
	 * Waktu Acc Ktsk
	 * 
	 * @Column(name="waktu_acc_ktsk", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Acc Ktsk")
	 * @var string
	 */
	protected $waktuAccKtsk;

	/**
	 * Koordinator ID
	 * 
	 * @Column(name="koordinator_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Koordinator ID")
	 * @var int
	 */
	protected $koordinatorId;

	/**
	 * Koordinator
	 * 
	 * @JoinColumn(name="koordinator_id", referenceColumnName="supervisor_id")
	 * @Label(content="Koordinator")
	 * @var SupervisorMin
	 */
	protected $koordinator;

	/**
	 * Acc Koordinator
	 * 
	 * @Column(name="acc_koordinator", type="int(11)", length=11, nullable=true)
	 * @Label(content="Acc Koordinator")
	 * @var int
	 */
	protected $accKoordinator;

	/**
	 * Status Acc Koordinator
	 * 
	 * @Column(name="status_acc_koordinator", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Status Acc Koordinator")
	 * @var string
	 */
	protected $statusAccKoordinator;

	/**
	 * Waktu Acc Koordinator
	 * 
	 * @Column(name="waktu_acc_koordinator", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Acc Koordinator")
	 * @var string
	 */
	protected $waktuAccKoordinator;

	/**
	 * Komentar Ktsk
	 * 
	 * @Column(name="komentar_ktsk", type="longtext", nullable=true)
	 * @Label(content="Komentar Ktsk")
	 * @var string
	 */
	protected $komentarKtsk;

	/**
	 * Komentar Koordinator
	 * 
	 * @Column(name="komentar_koordinator", type="longtext", nullable=true)
	 * @Label(content="Komentar Koordinator")
	 * @var string
	 */
	protected $komentarKoordinator;

	/**
	 * Latitude
	 * 
	 * @Column(name="latitude", type="float", nullable=true)
	 * @Label(content="Latitude")
	 * @var float
	 */
	protected $latitude;

	/**
	 * Longitude
	 * 
	 * @Column(name="longitude", type="float", nullable=true)
	 * @Label(content="Longitude")
	 * @var float
	 */
	protected $longitude;

	/**
	 * Altitude
	 * 
	 * @Column(name="altitude", type="float", nullable=true)
	 * @Label(content="Altitude")
	 * @var float
	 */
	protected $altitude;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=19, nullable=true, updatable=false)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Ubah")
	 * @var string
	 */
	protected $waktuUbah;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true, updatable=false)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

	/**
	 * Extracts the first scalar value from each object in an array of stdClass objects.
	 *
	 * @param stdClass[] $input Array of stdClass objects.
	 * @return mixed[] An array of scalar values extracted from the input objects.
	 */
	public function getSerialOfScalar($input)
	{
		$result = array();
		if(isset($input) && !empty($input))
		{
			$arr = json_decode(json_encode($input), true);
			foreach($arr as $val)
			{
				$value = array_values($val);
				$result[] = $value[0];
			}
		}
		return $result;
	}

	/**
	 * Get lokasi proyek
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT lokasi_pekerjaan.lokasi_proyek_id FROM lokasi_pekerjaan WHERE lokasi_pekerjaan.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getLokasiProyekList($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get acuan pengawasan
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT acuan_pengawasan_proyek.acuan_pengawasan_id FROM acuan_pengawasan_proyek WHERE acuan_pengawasan_proyek.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getAcuanPengawasanList($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get lokasi proyek
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT 
	 (SELECT lokasi_proyek.nama FROM lokasi_proyek WHERE lokasi_proyek.lokasi_proyek_id = lokasi_pekerjaan.lokasi_proyek_id) as lokasi_proyek 
	 FROM lokasi_pekerjaan 
	 WHERE lokasi_pekerjaan.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getLokasiProyek($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get bill of quantity proyek
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT 
	 (SELECT bill_of_quantity.nama FROM bill_of_quantity WHERE bill_of_quantity.bill_of_quantity_id = bill_of_quantity_proyek.bill_of_quantity_id) as bill_of_quantity,
	 bill_of_quantity_proyek.volume as volume, bill_of_quantity_proyek.volume_proyek as volume_proyek, bill_of_quantity_proyek.persen as persen
	 FROM bill_of_quantity_proyek 
	 WHERE bill_of_quantity_proyek.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getBillOfQuantityProyek($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get permasalahan pekerjaan
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT permasalahan.permasalahan, permasalahan.rekomendasi, permasalahan.tindak_lanjut
	 FROM rekomendasi_pekerjaan
	 INNER JOIN (permasalahan) ON (permasalahan.permasalahan_id = rekomendasi_pekerjaan.permasalahan_id)
	 WHERE
	 rekomendasi_pekerjaan.buku_harian_id = :bukuHarianId
	 ")
	 */
	public function getPermasalahanPekerjaan($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get man power
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT 
	 (SELECT man_power.nama FROM man_power WHERE man_power.man_power_id = man_power_proyek.man_power_id) as man_power,
	 man_power_proyek.jumlah_pekerja as jumlah_pekerja 
	 FROM man_power_proyek 
	 WHERE man_power_proyek.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getManPowerProyek($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get material proyek
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT 
	 material.nama as nama, material.satuan as satuan, material.onsite as onsite, material.terpasang as terpasang,
	 material_proyek.jumlah as jumlah 
	 FROM material_proyek 
	 INNER JOIN (material) ON (material.material_id = material_proyek.material_id)
	 WHERE material_proyek.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getMaterialProyek($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get peralatan proyek
	 *
	 * @param int $bukuHarianId
	 * @return stdClass[]
	 * @query("
	 SELECT 
	 (SELECT peralatan.nama FROM peralatan WHERE peralatan.peralatan_id = peralatan_proyek.peralatan_id) as peralatan,
	 peralatan_proyek.jumlah as jumlah 
	 FROM peralatan_proyek 
	 WHERE peralatan_proyek.buku_harian_id = :bukuHarianId 
	 ")
	 */
	public function getPeralatanProyek($bukuHarianId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Format material proyek
	 *
	 * @param stdClass $materialProyek
	 * @return string
	 */
	public function formatMaterialProyek($materialProyek)
	{
		return sprintf("%s [%s] &raquo; onsite [%s] terpasang [%s]", $materialProyek->nama, $materialProyek->satuan, $materialProyek->onsite, $materialProyek->terpasang);
	}

}