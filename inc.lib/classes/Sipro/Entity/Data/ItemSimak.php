<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The ItemSimak class represents an entity in the "item_simak" table.
 *
 * This entity maps to the "item_simak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="item_simak")
 */
class ItemSimak extends MagicObject
{
	/**
	 * Item Simak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="item_simak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Item Simak ID")
	 * @var int
	 */
	protected $itemSimakId;

	/**
	 * Umk ID
	 * 
	 * @Column(name="umk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Umk ID")
	 * @var int
	 */
	protected $umkId;

	/**
	 * Tsk ID
	 * 
	 * @Column(name="tsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tsk ID")
	 * @var int
	 */
	protected $tskId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Formulir Simak ID
	 * 
	 * @Column(name="formulir_simak_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Formulir Simak ID")
	 * @var int
	 */
	protected $formulirSimakId;

	/**
	 * Daftar Simak ID
	 * 
	 * @Column(name="daftar_simak_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Daftar Simak ID")
	 * @var int
	 */
	protected $daftarSimakId;

	/**
	 * Pemeriksaan ID
	 * 
	 * @Column(name="pemeriksaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pemeriksaan ID")
	 * @var int
	 */
	protected $pemeriksaanId;

	/**
	 * Jenis Pemeriksaan ID
	 * 
	 * @Column(name="jenis_pemeriksaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jenis Pemeriksaan ID")
	 * @var int
	 */
	protected $jenisPemeriksaanId;

	/**
	 * Nilai
	 * 
	 * @Column(name="nilai", type="int(11)", length=11, nullable=true)
	 * @Label(content="Nilai")
	 * @var int
	 */
	protected $nilai;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Waktu Input
	 * 
	 * @Column(name="waktu_input", type="timestamp", length=26, nullable=true)
	 * @Label(content="Waktu Input")
	 * @var string
	 */
	protected $waktuInput;

	/**
	 * IP Input
	 * 
	 * @Column(name="ip_input", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Input")
	 * @var string
	 */
	protected $ipInput;

}