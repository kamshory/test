<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The RiwayatDaftarSimak class represents an entity in the "riwayat_daftar_simak" table.
 *
 * This entity maps to the "riwayat_daftar_simak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="riwayat_daftar_simak")
 */
class RiwayatDaftarSimak extends MagicObject
{
	/**
	 * Riwayat Daftar Simak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="riwayat_daftar_simak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Riwayat Daftar Simak ID")
	 * @var int
	 */
	protected $riwayatDaftarSimakId;

	/**
	 * Daftar Simak ID
	 * 
	 * @Column(name="daftar_simak_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Daftar Simak ID")
	 * @var int
	 */
	protected $daftarSimakId;

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
	 * Waktu Pengisian
	 * 
	 * @Column(name="waktu_pengisian", type="timestamp", length=26, nullable=true)
	 * @Label(content="Waktu Pengisian")
	 * @var string
	 */
	protected $waktuPengisian;

	/**
	 * IP Pengisian
	 * 
	 * @Column(name="ip_pengisian", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Pengisian")
	 * @MaxLength(value=50)
	 * @var string
	 */
	protected $ipPengisian;

}