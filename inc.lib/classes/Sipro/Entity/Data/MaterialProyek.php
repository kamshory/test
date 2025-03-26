<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The MaterialProyek class represents an entity in the "material_proyek" table.
 *
 * This entity maps to the "material_proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="material_proyek")
 */
class MaterialProyek extends MagicObject
{
	/**
	 * Material Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="material_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Material Proyek ID")
	 * @var int
	 */
	protected $materialProyekId;

	/**
	 * Pekerjaan ID
	 * 
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

	/**
	 * Material ID
	 * 
	 * @Column(name="material_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Material ID")
	 * @var int
	 */
	protected $materialId;

	/**
	 * Material
	 * 
	 * @JoinColumn(name="material_id", referenceColumnName="material_id")
	 * @Label(content="Material")
	 * @var MaterialMin
	 */
	protected $material;

	/**
	 * Jumlah
	 * 
	 * @Column(name="jumlah", type="float", nullable=true)
	 * @Label(content="Jumlah")
	 * @var float
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
	 * Buku Harian ID
	 * 
	 * @Column(name="buku_harian_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Harian ID")
	 * @var int
	 */
	protected $bukuHarianId;

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

	/**
	 * Get material terpasang
	 *
	 * @param int $materialId
	 * @return stdClass
	 * @query("SELECT SUM(jumlah) AS terpasang FROM material_proyek WHERE material_id = :materialId;")
	 */
	public function getTotalTerpasang($materialId)
	{
		return $this->executeNativeQuery();
	}

}