<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The FormulirSimak class represents an entity in the "formulir_simak" table.
 *
 * This entity maps to the "formulir_simak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="formulir_simak")
 */
class FormulirSimak extends MagicObject
{
	/**
	 * Formulir Simak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="formulir_simak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Formulir Simak ID")
	 * @var int
	 */
	protected $formulirSimakId;

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
	 * Prosedur ID
	 * 
	 * @Column(name="prosedur_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Prosedur ID")
	 * @var int
	 */
	protected $prosedurId;

	/**
	 * Prosedur
	 * 
	 * @JoinColumn(name="prosedur_id", referenceColumnName="prosedur_id")
	 * @Label(content="Prosedur")
	 * @var ProsedurMin
	 */
	protected $prosedur;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Judul Formulir
	 * 
	 * @Column(name="judul_formulir", type="text", nullable=true)
	 * @Label(content="Judul Formulir")
	 * @var string
	 */
	protected $judulFormulir;

	/**
	 * Nomor Formulir
	 * 
	 * @Column(name="nomor_formulir", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Formulir")
	 * @var string
	 */
	protected $nomorFormulir;

	/**
	 * Jumlah Daftar Simak
	 * 
	 * @Column(name="jumlah_daftar_simak", type="int(11)", length=11, defaultValue=0, nullable=true)
	 * @Label(content="Jumlah Daftar Simak")
	 * @var string
	 */
	protected $jumlahDaftarSimak;

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