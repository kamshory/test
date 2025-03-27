<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * ProgresProyek is entity of table progres_proyek. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="progres_proyek")
 */
class ProgresProyek extends MagicObject
{
	/**
	 * Progres Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="progres_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Progres Proyek ID")
	 * @var int
	 */
	protected $progresProyekId;

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
	 * Persen
	 * 
	 * @Column(name="persen", type="float", nullable=true)
	 * @Label(content="Persen")
	 * @var float
	 */
	protected $persen;

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
	 * Supervisor Buat
	 * 
	 * @Column(name="supervisor_buat", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor Buat")
	 * @var int
	 */
	protected $supervisorBuat;

	/**
	 * Pembuat
	 * 
	 * @JoinColumn(name="supervisor_buat", referenceColumnName="supervisor_id")
	 * @Label(content="Pembuat")
	 * @var SupervisorMin
	 */
	protected $pembuat;

	/**
	 * Supervisor Ubah
	 * 
	 * @Column(name="supervisor_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor Ubah")
	 * @var int
	 */
	protected $supervisorUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="supervisor_ubah", referenceColumnName="supervisor_id")
	 * @Label(content="Pengubah")
	 * @var SupervisorMin
	 */
	protected $pengubah;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true, updatable=false)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Aktif
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}