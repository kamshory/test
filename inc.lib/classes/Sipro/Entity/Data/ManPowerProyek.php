<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The ManPowerProyek class represents an entity in the "man_power_proyek" table.
 *
 * This entity maps to the "man_power_proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="man_power_proyek")
 */
class ManPowerProyek extends MagicObject
{
	/**
	 * Man Power Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="man_power_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Man Power Proyek ID")
	 * @var int
	 */
	protected $manPowerProyekId;

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
	 * Buku Harian
	 * 
	 * @JoinColumn(name="buku_harian_id", referenceColumnName="buku_harian_id")
	 * @Label(content="Buku Harian")
	 * @var BukuHarianMin
	 */
	protected $bukuHarian;

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
	 * Man Power ID
	 * 
	 * @Column(name="man_power_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Man Power ID")
	 * @var int
	 */
	protected $manPowerId;

	/**
	 * Man Power
	 * 
	 * @JoinColumn(name="man_power_id", referenceColumnName="man_power_id")
	 * @Label(content="Man Power")
	 * @var ManPowerMin
	 */
	protected $manPower;

	/**
	 * Jumlah Pekerja
	 * 
	 * @Column(name="jumlah_pekerja", type="int(11)", length=11, nullable=true)
	 * @Label(content="Jumlah Pekerja")
	 * @var int
	 */
	protected $jumlahPekerja;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=19, nullable=true, updatable=false)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Ubah")
	 * @var string
	 */
	protected $waktuUbah;

	/**
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}