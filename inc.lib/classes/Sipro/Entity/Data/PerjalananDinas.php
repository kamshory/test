<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The PerjalananDinas class represents an entity in the "perjalanan_dinas" table.
 *
 * This entity maps to the "perjalanan_dinas" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="perjalanan_dinas")
 */
class PerjalananDinas extends MagicObject
{
	/**
	 * Perjalanan Dinas ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="perjalanan_dinas_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Perjalanan Dinas ID")
	 * @var int
	 */
	protected $perjalananDinasId;

	/**
	 * Jenis Perjalanan Dinas ID
	 * 
	 * @Column(name="jenis_perjalanan_dinas_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jenis Perjalanan Dinas ID")
	 * @var int
	 */
	protected $jenisPerjalananDinasId;

	/**
	 * Jenis Perjalanan Dinas
	 * 
	 * @JoinColumn(name="jenis_perjalanan_dinas_id", referenceColumnName="jenis_perjalanan_dinas_id")
	 * @Label(content="Jenis Perjalanan Dinas")
	 * @var JenisPerjalananDinasMin
	 */
	protected $jenisPerjalananDinas;

	/**
	 * Tsk ID
	 * 
	 * @Column(name="tsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tsk ID")
	 * @var int
	 */
	protected $tskId;

	/**
	 * Tsk
	 * 
	 * @JoinColumn(name="tsk_id", referenceColumnName="tsk_id")
	 * @Label(content="Tsk")
	 * @var TskMin
	 */
	protected $tsk;

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
	 * Nomor Sppd
	 * 
	 * @Column(name="nomor_sppd", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nomor Sppd")
	 * @var string
	 */
	protected $nomorSppd;

	/**
	 * Asal
	 * 
	 * @Column(name="asal", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Asal")
	 * @var string
	 */
	protected $asal;

	/**
	 * Tujuan
	 * 
	 * @Column(name="tujuan", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Tujuan")
	 * @var string
	 */
	protected $tujuan;

	/**
	 * Kode Lokasi
	 * 
	 * @Column(name="kode_lokasi", type="varchar(45)", length=45, nullable=true)
	 * @Label(content="Kode Lokasi")
	 * @var string
	 */
	protected $kodeLokasi;

	/**
	 * Alat Angkutan
	 * 
	 * @Column(name="alat_angkutan", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Alat Angkutan")
	 * @var string
	 */
	protected $alatAngkutan;

	/**
	 * Keperluan
	 * 
	 * @Column(name="keperluan", type="text", nullable=true)
	 * @Label(content="Keperluan")
	 * @var string
	 */
	protected $keperluan;

	/**
	 * Keterangan
	 * 
	 * @Column(name="keterangan", type="text", nullable=true)
	 * @Label(content="Keterangan")
	 * @var string
	 */
	protected $keterangan;

	/**
	 * Dari
	 * 
	 * @Column(name="dari", type="date", nullable=true)
	 * @Label(content="Dari")
	 * @var string
	 */
	protected $dari;

	/**
	 * Hingga
	 * 
	 * @Column(name="hingga", type="date", nullable=true)
	 * @Label(content="Hingga")
	 * @var string
	 */
	protected $hingga;

	/**
	 * Detil
	 * 
	 * @Column(name="detil", type="longtext", nullable=true)
	 * @Label(content="Detil")
	 * @var string
	 */
	protected $detil;

	/**
	 * Atas Beban Biaya
	 * 
	 * @Column(name="atas_beban_biaya", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Atas Beban Biaya")
	 * @var string
	 */
	protected $atasBebanBiaya;

	/**
	 * Status Perjalanan Dinas
	 * 
	 * @NotNull
	 * @Column(name="status_perjalanan_dinas", type="text", defaultValue="P", nullable=false)
	 * @DefaultColumn(value="P")
	 * @Label(content="Status Perjalanan Dinas")
	 * @var string
	 */
	protected $statusPerjalananDinas;

	/**
	 * Dibayar
	 * 
	 * @Column(name="dibayar", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Dibayar")
	 * @var bool
	 */
	protected $dibayar;

	/**
	 * Waktu Dibayar
	 * 
	 * @Column(name="waktu_dibayar", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Dibayar")
	 * @var string
	 */
	protected $waktuDibayar;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Pembuat
	 * 
	 * @JoinColumn(name="admin_buat", referenceColumnName="user_id")
	 * @Label(content="Pembuat")
	 * @var UserMin
	 */
	protected $pembuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="admin_ubah", referenceColumnName="user_id")
	 * @Label(content="Pengubah")
	 * @var UserMin
	 */
	protected $pengubah;

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