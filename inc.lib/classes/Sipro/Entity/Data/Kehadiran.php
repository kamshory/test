<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * Kehadiran is entity of table kehadiran. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="kehadiran")
 */
class Kehadiran extends MagicObject
{
	/**
	 * Kehadiran ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="kehadiran_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Kehadiran ID")
	 * @var int
	 */
	protected $kehadiranId;

	/**
	 * Grup Pengguna
	 * 
	 * @Column(name="grup_pengguna", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Grup Pengguna")
	 * @var string
	 */
	protected $grupPengguna;

	/**
	 * User ID
	 * 
	 * @Column(name="user_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="User ID")
	 * @var int
	 */
	protected $userId;

	/**
	 * User
	 * 
	 * @JoinColumn(name="user_id", referenceColumnName="user_id")
	 * @Label(content="User")
	 * @var UserMin
	 */
	protected $user;

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
	 * Periode ID
	 * 
	 * @Column(name="periode_id", type="varchar(6)", length=6, nullable=true)
	 * @Label(content="Periode ID")
	 * @var string
	 */
	protected $periodeId;

	/**
	 * Periode
	 * 
	 * @JoinColumn(name="periode_id", referenceColumnName="periode_id")
	 * @Label(content="Periode")
	 * @var PeriodeMin
	 */
	protected $periode;

	/**
	 * Waktu Masuk
	 * 
	 * @Column(name="waktu_masuk", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Masuk")
	 * @var string
	 */
	protected $waktuMasuk;

	/**
	 * Lokasi Masuk ID
	 * 
	 * @Column(name="lokasi_masuk_id", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Lokasi Masuk ID")
	 * @var string
	 */
	protected $lokasiMasukId;

	/**
	 * Lokasi Masuk
	 * 
	 * @JoinColumn(name="lokasi_masuk_id", referenceColumnName="lokasi_kehadiran_id")
	 * @Label(content="Lokasi Masuk")
	 * @var LokasiKehadiranMin
	 */
	protected $lokasiMasuk;

	/**
	 * Foto Masuk
	 * 
	 * @Column(name="foto_masuk", type="longtext", nullable=true)
	 * @Label(content="Foto Masuk")
	 * @var string
	 */
	protected $fotoMasuk;

	/**
	 * Alamat Masuk
	 * 
	 * @Column(name="alamat_masuk", type="longtext", nullable=true)
	 * @Label(content="Alamat Masuk")
	 * @var string
	 */
	protected $alamatMasuk;

	/**
	 * Latitude Masuk
	 * 
	 * @Column(name="latitude_masuk", type="double", nullable=true)
	 * @Label(content="Latitude Masuk")
	 * @var double
	 */
	protected $latitudeMasuk;

	/**
	 * Longitude Masuk
	 * 
	 * @Column(name="longitude_masuk", type="double", nullable=true)
	 * @Label(content="Longitude Masuk")
	 * @var double
	 */
	protected $longitudeMasuk;

	/**
	 * IP Masuk
	 * 
	 * @Column(name="ip_masuk", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Masuk")
	 * @var string
	 */
	protected $ipMasuk;

	/**
	 * Waktu Pulang
	 * 
	 * @Column(name="waktu_pulang", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Pulang")
	 * @var string
	 */
	protected $waktuPulang;

	/**
	 * Lokasi Pulang ID
	 * 
	 * @Column(name="lokasi_pulang_id", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Lokasi Pulang ID")
	 * @var string
	 */
	protected $lokasiPulangId;

	/**
	 * Lokasi Pulang
	 * 
	 * @JoinColumn(name="lokasi_pulang_id", referenceColumnName="lokasi_kehadiran_id")
	 * @Label(content="Lokasi Pulang")
	 * @var LokasiKehadiranMin
	 */
	protected $lokasiPulang;

	/**
	 * Foto Pulang
	 * 
	 * @Column(name="foto_pulang", type="longtext", nullable=true)
	 * @Label(content="Foto Pulang")
	 * @var string
	 */
	protected $fotoPulang;

	/**
	 * Alamat Pulang
	 * 
	 * @Column(name="alamat_pulang", type="longtext", nullable=true)
	 * @Label(content="Alamat Pulang")
	 * @var string
	 */
	protected $alamatPulang;

	/**
	 * Latitude Pulang
	 * 
	 * @Column(name="latitude_pulang", type="double", nullable=true)
	 * @Label(content="Latitude Pulang")
	 * @var double
	 */
	protected $latitudePulang;

	/**
	 * Longitude Pulang
	 * 
	 * @Column(name="longitude_pulang", type="double", nullable=true)
	 * @Label(content="Longitude Pulang")
	 * @var double
	 */
	protected $longitudePulang;

	/**
	 * IP Pulang
	 * 
	 * @Column(name="ip_pulang", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Pulang")
	 * @var string
	 */
	protected $ipPulang;

	/**
	 * Aktivitas
	 * 
	 * @Column(name="aktivitas", type="longtext", nullable=true)
	 * @Label(content="Aktivitas")
	 * @var string
	 */
	protected $aktivitas;

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