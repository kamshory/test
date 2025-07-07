<?php

namespace SiproPLNBaru\Entity\Data;

use MagicObject\MagicObject;

/**
 * The Prosedur class represents an entity in the "prosedur" table.
 *
 * This entity maps to the "prosedur" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package SiproPLNBaru\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="prosedur")
 */
class Prosedur extends MagicObject
{
	/**
	 * Prosedur ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="prosedur_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Prosedur ID")
	 * @var int
	 */
	protected $prosedurId;

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
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Nomor Prosedur
	 * 
	 * @Column(name="nomor_prosedur", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Prosedur")
	 * @var string
	 */
	protected $nomorProsedur;

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
	 * Pembuat
	 * 
	 * @JoinColumn(name="admin_buat", referenceColumnName="admin_id")
	 * @Label(content="Pembuat")
	 * @var AdminMin
	 */
	protected $pembuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var string
	 */
	protected $adminUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="admin_ubah", referenceColumnName="admin_id")
	 * @Label(content="Pengubah")
	 * @var AdminMin
	 */
	protected $pengubah;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=26, nullable=true, updatable=false)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=26, nullable=true)
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