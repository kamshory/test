<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * SupervisorProyekMin is entity of table supervisor_proyek. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="supervisor_proyek")
 */
class SupervisorProyekMin extends MagicObject
{
	/**
	 * Supervisor Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="supervisor_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Supervisor Proyek ID")
	 * @var int
	 */
	protected $supervisorProyekId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Supervisor
	 * 
	 * @JoinColumn(name="supervisor_id", referenceColumnName="supervisor_id")
	 * @Label(content="Supervisor")
	 * @var SupervisorMin
	 */
	protected $supervisor;

	/**
	 * Koordinator
	 * 
	 * @Column(name="koordinator", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Koordinator")
	 * @var bool
	 */
	protected $koordinator;

	/**
	 * Berjalan
	 * 
	 * @Column(name="berjalan", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Berjalan")
	 * @var bool
	 */
	protected $berjalan;

	

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