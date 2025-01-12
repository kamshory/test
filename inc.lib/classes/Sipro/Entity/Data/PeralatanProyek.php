<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * PeralatanProyek is entity of table peralatan_proyek. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="peralatan_proyek")
 */
class PeralatanProyek extends MagicObject
{
	/**
	 * Peralatan Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="peralatan_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Peralatan Proyek ID")
	 * @var int
	 */
	protected $peralatanProyekId;

	/**
	 * Pekerjaan ID
	 * 
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

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
	 * Jumlah
	 * 
	 * @Column(name="jumlah", type="float", nullable=true)
	 * @Label(content="Jumlah")
	 * @var double
	 */
	protected $jumlah;

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
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}