<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The CutiSupervisor class represents an entity in the "cuti_supervisor" table.
 *
 * This entity maps to the "cuti_supervisor" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="cuti_supervisor")
 */
class CutiSupervisor extends MagicObject
{
	/**
	 * Cuti Supervisor ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="cuti_supervisor_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Cuti Supervisor ID")
	 * @var int
	 */
	protected $cutiSupervisorId;

	/**
	 * Cuti ID
	 * 
	 * @Column(name="cuti_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Cuti ID")
	 * @var int
	 */
	protected $cutiId;

	/**
	 * Cuti
	 * 
	 * @JoinColumn(name="cuti_id", referenceColumnName="cuti_id")
	 * @Label(content="Cuti")
	 * @var CutiMin
	 */
	protected $cuti;

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
	 * Jenis Cuti ID
	 * 
	 * @Column(name="jenis_cuti_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jenis Cuti ID")
	 * @var int
	 */
	protected $jenisCutiId;

	/**
	 * Jenis Cuti
	 * 
	 * @JoinColumn(name="jenis_cuti_id", referenceColumnName="jenis_cuti_id")
	 * @Label(content="Jenis Cuti")
	 * @var JenisCuti
	 */
	protected $jenisCuti;

	/**
	 * Dibayar
	 * 
	 * @Column(name="dibayar", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Dibayar")
	 * @var bool
	 */
	protected $dibayar;

	/**
	 * Tanggal
	 * 
	 * @Column(name="tanggal", type="date", nullable=true)
	 * @Label(content="Tanggal")
	 * @var string
	 */
	protected $tanggal;

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