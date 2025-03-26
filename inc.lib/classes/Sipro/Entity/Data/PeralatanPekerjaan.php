<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * PeralatanPekerjaan is entity of table peralatan_pekerjaan. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="peralatan_pekerjaan")
 */
class PeralatanPekerjaan extends MagicObject
{
	/**
	 * Peralatan Pekerjaan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="peralatan_pekerjaan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Peralatan Pekerjaan ID")
	 * @var int
	 */
	protected $peralatanPekerjaanId;

	/**
	 * Peralatan ID
	 * 
	 * @Column(name="peralatan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Peralatan ID")
	 * @var int
	 */
	protected $peralatanId;

	/**
	 * Peralatan
	 * 
	 * @JoinColumn(name="peralatan_id", referenceColumnName="peralatan_id")
	 * @Label(content="Peralatan")
	 * @var Peralatan
	 */
	protected $peralatan;

	/**
	 * Jenis Pekerjaan ID
	 * 
	 * @Column(name="jenis_pekerjaan_id", type="char(3)", length=3, nullable=true)
	 * @Label(content="Jenis Pekerjaan ID")
	 * @var string
	 */
	protected $jenisPekerjaanId;

	/**
	 * Jenis Pekerjaan
	 * 
	 * @JoinColumn(name="jenis_pekerjaan_id", referenceColumnName="jenis_pekerjaan_id")
	 * @Label(content="Jenis Pekerjaan")
	 * @var JenisPekerjaan
	 */
	protected $jenisPekerjaan;

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