<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AcuanPengawasanMin class represents an entity in the "acuan_pengawasan" table.
 *
 * This entity maps to the "acuan_pengawasan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="acuan_pengawasan")
 */
class AcuanPengawasanMin extends MagicObject
{
	/**
	 * Acuan Pengawasan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="acuan_pengawasan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Acuan Pengawasan ID")
	 * @var int
	 */
	protected $acuanPengawasanId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Nomor
	 * 
	 * @Column(name="nomor", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nomor")
	 * @var string
	 */
	protected $nomor;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var string
	 */
	protected $proyekId;

	/**
	 * Jenis Hirarki Kontrak ID
	 * 
	 * @Column(name="jenis_hirarki_kontrak_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jenis Hirarki Kontrak ID")
	 * @var int
	 */
	protected $jenisHirarkiKontrakId;

	/**
	 * Status Acuan Pengawasan ID
	 * 
	 * @Column(name="status_acuan_pengawasan_id", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Status Acuan Pengawasan ID")
	 * @var string
	 */
	protected $statusAcuanPengawasanId;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="varchar(40)", length=40, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var string
	 */
	protected $adminBuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var string
	 */
	protected $adminUbah;

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
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}