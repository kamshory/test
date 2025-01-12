<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AcuanPengawasanPekerjaan class represents an entity in the "acuan_pengawasan_pekerjaan" table.
 *
 * This entity maps to the "acuan_pengawasan_pekerjaan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="acuan_pengawasan_pekerjaan")
 */
class AcuanPengawasanPekerjaan extends MagicObject
{
	/**
	 * Acuan Pengawasan Pekerjaan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="acuan_pengawasan_pekerjaan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Acuan Pengawasan Pekerjaan ID")
	 * @var int
	 */
	protected $acuanPengawasanPekerjaanId;

	/**
	 * Pekerjaan ID
	 * 
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

	/**
	 * Pekerjaan
	 * 
	 * @JoinColumn(name="pekerjaan_id", referenceColumnName="pekerjaan_id")
	 * @Label(content="Pekerjaan")
	 * @var PekerjaanMin
	 */
	protected $pekerjaan;

	/**
	 * Acuan Pengawasan ID
	 * 
	 * @Column(name="acuan_pengawasan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Acuan Pengawasan ID")
	 * @var int
	 */
	protected $acuanPengawasanId;

	/**
	 * Acuan Pengawasan
	 * 
	 * @JoinColumn(name="acuan_pengawasan_id", referenceColumnName="acuan_pengawasan_id")
	 * @Label(content="Acuan Pengawasan")
	 * @var AcuanPengawasan
	 */
	protected $acuanPengawasan;

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