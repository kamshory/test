<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The BukuDireksiMin class represents an entity in the "buku_direksi" table.
 *
 * This entity maps to the "buku_direksi" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="buku_direksi")
 */
class BukuDireksiMin extends MagicObject
{
	/**
	 * Buku Direksi ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="buku_direksi_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Buku Direksi ID")
	 * @var int
	 */
	protected $bukuDireksiId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Tanggal
	 * 
	 * @Column(name="tanggal", type="date", nullable=true)
	 * @Label(content="Tanggal")
	 * @var string
	 */
	protected $tanggal;

	/**
	 * Nomor
	 * 
	 * @Column(name="nomor", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor")
	 * @var string
	 */
	protected $nomor;
	
	/**
	 * Lokasi
	 * 
	 * @Column(name="lokasi", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Lokasi")
	 * @var string
	 */
	protected $lokasi;

	/**
	 * Pekerjaan
	 * 
	 * @Column(name="pekerjaan", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="Pekerjaan")
	 * @var string
	 */
	protected $pekerjaan;

	/**
	 * Uraian Permasalahan
	 * 
	 * @Column(name="uraian_permasalahan", type="longtext", nullable=true)
	 * @Label(content="Uraian Permasalahan")
	 * @var string
	 */
	protected $uraianPermasalahan;

	/**
	 * Diperiksa
	 * 
	 * @Column(name="diperiksa", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Diperiksa")
	 * @var bool
	 */
	protected $diperiksa;

	/**
	 * Penyelesaian
	 * 
	 * @Column(name="penyelesaian", type="longtext", nullable=true)
	 * @Label(content="Penyelesaian")
	 * @var string
	 */
	protected $penyelesaian;

	/**
	 * Status
	 * 
	 * @Column(name="status", type="int(4)", length=4, nullable=true)
	 * @Label(content="Status")
	 * @var int
	 */
	protected $status;
	
	/**
	 * Progres
	 * 
	 * @Column(name="progres", type="float", nullable=true)
	 * @Label(content="Progres")
	 * @var float
	 */
	protected $progres;

	/**
	 * Selesai
	 * 
	 * @Column(name="selesai", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Selesai")
	 * @var bool
	 */
	protected $selesai;

	/**
	 * Waktu Selesai
	 * 
	 * @Column(name="waktu_selesai", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Selesai")
	 * @var string
	 */
	protected $waktuSelesai;

	/**
	 * Nama Direksi
	 * 
	 * @Column(name="nama_direksi", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama Direksi")
	 * @var string
	 */
	protected $namaDireksi;

	/**
	 * Jabatan Direksi
	 * 
	 * @Column(name="jabatan_direksi", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Jabatan Direksi")
	 * @var string
	 */
	protected $jabatanDireksi;

	/**
	 * Komentar Kontraktor
	 * 
	 * @Column(name="komentar_kontraktor", type="longtext", nullable=true)
	 * @Label(content="Komentar Kontraktor")
	 * @var string
	 */
	protected $komentarKontraktor;

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