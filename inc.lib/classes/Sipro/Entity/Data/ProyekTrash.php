<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The ProyekTrash class represents an entity in the "proyek_trash" table.
 *
 * This entity maps to the "proyek_trash" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="proyek_trash")
 */
class ProyekTrash extends MagicObject
{
	/**
	 * Proyek Trash ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @Column(name="proyek_trash_id", type="varchar(40)", length=40, nullable=false)
	 * @DefaultColumn(value="NULL")
	 * @Label(content="Proyek Trash ID")
	 * @var string
	 */
	protected $proyekTrashId;

	/**
	 * Proyek ID
	 * 
	 * @NotNull
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=false)
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
	 * @var double
	 */
	protected $persen;

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
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}