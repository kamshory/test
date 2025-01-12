<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The LampiranBukuDireksi class represents an entity in the "lampiran_buku_direksi" table.
 *
 * This entity maps to the "lampiran_buku_direksi" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="lampiran_buku_direksi")
 */
class LampiranBukuDireksi extends MagicObject
{
	/**
	 * Lampiran Buku Direksi ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="lampiran_buku_direksi_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Lampiran Buku Direksi ID")
	 * @var int
	 */
	protected $lampiranBukuDireksiId;

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
	 * Buku Direksi ID
	 * 
	 * @Column(name="buku_direksi_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Direksi ID")
	 * @var int
	 */
	protected $bukuDireksiId;

	/**
	 * Buku Direksi
	 * 
	 * @JoinColumn(name="buku_direksi_id", referenceColumnName="buku_direksi_id")
	 * @Label(content="Buku Direksi")
	 * @var BukuDireksiMin
	 */
	protected $bukuDireksi;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

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
	 * Grup Lampiran
	 * 
	 * @Column(name="grup_lampiran", type="varchar(2)", length=2, nullable=true)
	 * @Label(content="Grup Lampiran")
	 * @var string
	 */
	protected $grupLampiran;

	/**
	 * File
	 * 
	 * @Column(name="file", type="varchar(512)", length=512, nullable=true)
	 * @Label(content="File")
	 * @var string
	 */
	protected $file;

	/**
	 * Mime
	 * 
	 * @Column(name="mime", type="varchar(120)", length=120, nullable=true)
	 * @Label(content="Mime")
	 * @var string
	 */
	protected $mime;

	/**
	 * Gambar
	 * 
	 * @Column(name="gambar", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Gambar")
	 * @var bool
	 */
	protected $gambar;

	/**
	 * Lebar
	 * 
	 * @Column(name="lebar", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Lebar")
	 * @var int
	 */
	protected $lebar;

	/**
	 * Tinggi
	 * 
	 * @Column(name="tinggi", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tinggi")
	 * @var int
	 */
	protected $tinggi;

	/**
	 * Dokumen
	 * 
	 * @Column(name="dokumen", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Dokumen")
	 * @var bool
	 */
	protected $dokumen;

	/**
	 * Ukuran File
	 * 
	 * @Column(name="ukuran_file", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Ukuran File")
	 * @var int
	 */
	protected $ukuranFile;

	/**
	 * Sha1 File
	 * 
	 * @Column(name="sha1_file", type="varchar(512)", length=512, nullable=true)
	 * @Label(content="Sha1 File")
	 * @var string
	 */
	protected $sha1File;

	/**
	 * Diunduh
	 * 
	 * @Column(name="diunduh", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Diunduh")
	 * @var int
	 */
	protected $diunduh;

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