<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The MaterialMin class represents an entity in the "material" table.
 *
 * This entity maps to the "material" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="material")
 */
class MaterialMin extends MagicObject
{
	/**
	 * Material ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="material_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Material ID")
	 * @var int
	 */
	protected $materialId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Satuan
	 * 
	 * @Column(name="satuan", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Satuan")
	 * @var string
	 */
	protected $satuan;

	/**
	 * Tipe
	 * 
	 * @Column(name="tipe", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Tipe")
	 * @var string
	 */
	protected $tipe;

	/**
	 * Merek
	 * 
	 * @Column(name="merek", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Merek")
	 * @var string
	 */
	protected $merek;

	/**
	 * Nomor Seri
	 * 
	 * @Column(name="nomor_seri", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Seri")
	 * @var string
	 */
	protected $nomorSeri;

	/**
	 * Tanggal Onsite
	 * 
	 * @Column(name="tanggal_onsite", type="date", nullable=true)
	 * @Label(content="Tanggal Onsite")
	 * @var string
	 */
	protected $tanggalOnsite;

	/**
	 * Kategori Material ID
	 * 
	 * @Column(name="kategori_material_id", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Kategori Material ID")
	 * @var string
	 */
	protected $kategoriMaterialId;

	/**
	 * Onsite
	 * 
	 * @Column(name="onsite", type="float", nullable=true)
	 * @Label(content="Onsite")
	 * @var float
	 */
	protected $onsite;

	/**
	 * Terpasang
	 * 
	 * @Column(name="terpasang", type="float", nullable=true)
	 * @Label(content="Terpasang")
	 * @var float
	 */
	protected $terpasang;

	/**
	 * Belum Terpasang
	 * 
	 * @Column(name="belum_terpasang", type="float", nullable=true)
	 * @Label(content="Belum Terpasang")
	 * @var float
	 */
	protected $belumTerpasang;

	/**
	 * Gambar
	 * 
	 * @Column(name="gambar", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Gambar")
	 * @var bool
	 */
	protected $gambar;

	/**
	 * Waktu Upload Gambar
	 * 
	 * @Column(name="waktu_upload_gambar", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Upload Gambar")
	 * @var string
	 */
	protected $waktuUploadGambar;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Ubah")
	 * @var string
	 */
	protected $waktuUbah;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

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