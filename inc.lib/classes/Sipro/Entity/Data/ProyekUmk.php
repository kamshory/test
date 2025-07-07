<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The ProyekUmk class represents an entity in the "proyek" table.
 *
 * This entity maps to the "proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="proyek")
 */
class ProyekUmk extends MagicObject
{
	/**
	 * Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(200)", length=200, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Deskripsi
	 * 
	 * @Column(name="deskripsi", type="longtext", nullable=true)
	 * @Label(content="Deskripsi")
	 * @var string
	 */
	protected $deskripsi;

	/**
	 * Pekerjaan
	 * 
	 * @Column(name="pekerjaan", type="text", nullable=true)
	 * @Label(content="Pekerjaan")
	 * @var string
	 */
	protected $pekerjaan;

	/**
	 * Kode Lokasi
	 * 
	 * @Column(name="kode_lokasi", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Kode Lokasi")
	 * @var string
	 */
	protected $kodeLokasi;

	/**
	 * Nomor Kontrak
	 * 
	 * @Column(name="nomor_kontrak", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Kontrak")
	 * @var string
	 */
	protected $nomorKontrak;

	/**
	 * Nomor Sla
	 * 
	 * @Column(name="nomor_sla", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Sla")
	 * @var string
	 */
	protected $nomorSla;

	/**
	 * Pelaksana
	 * 
	 * @Column(name="pelaksana", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Pelaksana")
	 * @var string
	 */
	protected $pelaksana;

	/**
	 * Pemberi Kerja
	 * 
	 * @Column(name="pemberi_kerja", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Pemberi Kerja")
	 * @var string
	 */
	protected $pemberiKerja;

	/**
	 * Umk ID
	 * 
	 * @Column(name="umk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Umk ID")
	 * @var int
	 */
	protected $umkId;

	/**
	 * Umk
	 * 
	 * @JoinColumn(name="umk_id", referenceColumnName="umk_id")
	 * @Label(content="Umk")
	 * @var UmkMin
	 */
	protected $umk;

	/**
	 * Galeri
	 * 
	 * @Column(name="galeri", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Galeri")
	 * @var int
	 */
	protected $galeri;

	/**
	 * Tanggal Mulai
	 * 
	 * @Column(name="tanggal_mulai", type="date", nullable=true)
	 * @Label(content="Tanggal Mulai")
	 * @var string
	 */
	protected $tanggalMulai;

	/**
	 * Tanggal Selesai
	 * 
	 * @Column(name="tanggal_selesai", type="date", nullable=true)
	 * @Label(content="Tanggal Selesai")
	 * @var string
	 */
	protected $tanggalSelesai;

	/**
	 * Persen
	 * 
	 * @Column(name="persen", type="float", nullable=true)
	 * @Label(content="Persen")
	 * @var float
	 */
	protected $persen;

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
	 * Draft
	 * 
	 * @Column(name="draft", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Draft")
	 * @var bool
	 */
	protected $draft;

	/**
	 * Waiting For
	 * 
	 * @Column(name="waiting_for", type="int(4)", length=4, nullable=true)
	 * @Label(content="Waiting For")
	 * @var int
	 */
	protected $waitingFor;

	/**
	 * Approval ID
	 * 
	 * @Column(name="approval_id", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Approval ID")
	 * @var string
	 */
	protected $approvalId;

}