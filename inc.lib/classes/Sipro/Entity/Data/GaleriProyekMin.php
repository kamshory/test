<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The GaleriProyekMin class represents an entity in the "galeri_proyek" table.
 *
 * This entity maps to the "galeri_proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="galeri_proyek")
 */
class GaleriProyekMin extends MagicObject
{
	/**
	 * Galeri Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="galeri_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Galeri Proyek ID")
	 * @var int
	 */
	protected $galeriProyekId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Lokasi Proyek ID
	 * 
	 * @Column(name="lokasi_proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Lokasi Proyek ID")
	 * @var int
	 */
	protected $lokasiProyekId;

	/**
	 * Buku Harian ID
	 * 
	 * @Column(name="buku_harian_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Harian ID")
	 * @var int
	 */
	protected $bukuHarianId;

	/**
	 * Pekerjaan ID
	 * 
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

	/**
	 * Bill Of Quantity ID
	 * 
	 * @Column(name="bill_of_quantity_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Bill Of Quantity ID")
	 * @var int
	 */
	protected $billOfQuantityId;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * File
	 * 
	 * @Column(name="file", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="File")
	 * @var string
	 */
	protected $file;

	/**
	 * Md5
	 * 
	 * @Column(name="md5", type="varchar(32)", length=32, nullable=true)
	 * @Label(content="Md5")
	 * @var string
	 */
	protected $md5;

	/**
	 * Deskripsi
	 * 
	 * @Column(name="deskripsi", type="longtext", nullable=true)
	 * @Label(content="Deskripsi")
	 * @var string
	 */
	protected $deskripsi;

	/**
	 * Width
	 * 
	 * @NotNull
	 * @Column(name="width", type="int(11)", length=11, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Width")
	 * @var int
	 */
	protected $width;

	/**
	 * Height
	 * 
	 * @NotNull
	 * @Column(name="height", type="int(11)", length=11, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Height")
	 * @var int
	 */
	protected $height;

	/**
	 * Exif
	 * 
	 * @Column(name="exif", type="longtext", nullable=true)
	 * @Label(content="Exif")
	 * @var string
	 */
	protected $exif;

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
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=19, nullable=true)
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
	 * Waktu Foto
	 * 
	 * @Column(name="waktu_foto", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Foto")
	 * @var string
	 */
	protected $waktuFoto;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true)
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