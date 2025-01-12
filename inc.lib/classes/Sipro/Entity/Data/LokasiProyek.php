<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The LokasiProyek class represents an entity in the "lokasi_proyek" table.
 *
 * This entity maps to the "lokasi_proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="lokasi_proyek")
 */
class LokasiProyek extends MagicObject
{
	/**
	 * Lokasi Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="lokasi_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Lokasi Proyek ID")
	 * @var int
	 */
	protected $lokasiProyekId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(200)", length=200, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Kode Lokasi
	 * 
	 * @Column(name="kode_lokasi", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Kode Lokasi")
	 * @var string
	 */
	protected $kodeLokasi;

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
	 * Latitude
	 * 
	 * @Column(name="latitude", type="float", nullable=true)
	 * @Label(content="Latitude")
	 * @var float
	 */
	protected $latitude;

	/**
	 * Longitude
	 * 
	 * @Column(name="longitude", type="float", nullable=true)
	 * @Label(content="Longitude")
	 * @var float
	 */
	protected $longitude;

	/**
	 * Atitude
	 * 
	 * @Column(name="atitude", type="float", nullable=true)
	 * @Label(content="Atitude")
	 * @var float
	 */
	protected $atitude;

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