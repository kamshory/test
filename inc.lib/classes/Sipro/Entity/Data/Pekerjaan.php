<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * Pekerjaan is entity of table pekerjaan. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="pekerjaan")
 */
class Pekerjaan extends MagicObject
{
	/**
	 * Pekerjaan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

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
	 * Buku Harian ID
	 * 
	 * @Column(name="buku_harian_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Harian ID")
	 * @var int
	 */
	protected $bukuHarianId;

	/**
	 * Buku Harian
	 * 
	 * @JoinColumn(name="buku_harian_id", referenceColumnName="buku_harian_id")
	 * @Label(content="Buku Harian")
	 * @var BukuHarianMin
	 */
	protected $bukuHarian;

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
	 * Jenis Pekerjaan ID
	 * 
	 * @Column(name="jenis_pekerjaan_id", type="char(3)", length=3, nullable=true)
	 * @Label(content="Jenis Pekerjaan ID")
	 * @var string
	 */
	protected $jenisPekerjaanId;

	/**
	 * Jenis Pekerjaan
	 * 
	 * @JoinColumn(name="jenis_pekerjaan_id", referenceColumnName="jenis_pekerjaan_id")
	 * @Label(content="Jenis Pekerjaan")
	 * @var JenisPekerjaan
	 */
	protected $jenisPekerjaan;

	/**
	 * Lokasi Proyek ID
	 * 
	 * @Column(name="lokasi_proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Lokasi Proyek ID")
	 * @var int
	 */
	protected $lokasiProyekId;

	/**
	 * Lokasi Proyek
	 * 
	 * @JoinColumn(name="lokasi_proyek_id", referenceColumnName="lokasi_proyek_id")
	 * @Label(content="Lokasi Proyek")
	 * @var LokasiProyekMin
	 */
	protected $lokasiProyek;

	/**
	 * Latitude
	 * 
	 * @Column(name="latitude", type="double", nullable=true)
	 * @Label(content="Latitude")
	 * @var double
	 */
	protected $latitude;

	/**
	 * Longitude
	 * 
	 * @Column(name="longitude", type="double", nullable=true)
	 * @Label(content="Longitude")
	 * @var double
	 */
	protected $longitude;

	/**
	 * Atitude
	 * 
	 * @Column(name="atitude", type="double", nullable=true)
	 * @Label(content="Atitude")
	 * @var double
	 */
	protected $atitude;

	/**
	 * Tipe Pondasi ID
	 * 
	 * @Column(name="tipe_pondasi_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tipe Pondasi ID")
	 * @var int
	 */
	protected $tipePondasiId;

	/**
	 * Tipe Pondasi
	 * 
	 * @JoinColumn(name="tipe_pondasi_id", referenceColumnName="tipe_pondasi_id")
	 * @Label(content="Tipe Pondasi")
	 * @var TipePondasi
	 */
	protected $tipePondasi;

	/**
	 * Kelas Tower ID
	 * 
	 * @Column(name="kelas_tower_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Kelas Tower ID")
	 * @var int
	 */
	protected $kelasTowerId;

	/**
	 * Kelas Tower
	 * 
	 * @JoinColumn(name="kelas_tower_id", referenceColumnName="kelas_tower_id")
	 * @Label(content="Kelas Tower")
	 * @var KelasTower
	 */
	protected $kelasTower;

	/**
	 * Kegiatan
	 * 
	 * @Column(name="kegiatan", type="text", nullable=true)
	 * @Label(content="Kegiatan")
	 * @var string
	 */
	protected $kegiatan;

	/**
	 * Jumlah Pekerja
	 * 
	 * @Column(name="jumlah_pekerja", type="int(11)", length=11, nullable=true)
	 * @Label(content="Jumlah Pekerja")
	 * @var int
	 */
	protected $jumlahPekerja;

	/**
	 * Acuan Pengawasan
	 * 
	 * @Column(name="acuan_pengawasan", type="longtext", nullable=true)
	 * @Label(content="Acuan Pengawasan")
	 * @var string
	 */
	protected $acuanPengawasan;
	
	/**
	 * Bill of Quantity ID
	 * 
	 * @Column(name="bill_of_quantity_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Bill of Quantity ID")
	 * @var BillOfQuantity
	 */
	protected $billOfQuantityId;
	
	/**
	 * Bill of Quantity
	 * 
	 * @JoinColumn(name="bill_of_quantity_id", referenceColumnName="bill_of_quantity_id")
	 * @Label(content="Bill of Quantity")
	 * @var BillOfQuantity
	 */
	protected $billOfQuantity;

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

}