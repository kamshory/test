<?php

namespace Sipro\Entity\Data;

use Exception;
use MagicObject\MagicObject;
use MagicObject\Database\PicoDatabase;

/**
 * The KuotaCutiScheduler class represents an entity in the "kuota_cuti" table.
 *
 * This entity maps to the "kuota_cuti" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="kuota_cuti")
 */
class KuotaCutiScheduler extends MagicObject
{
	/**
	 * Kuota Cuti ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="kuota_cuti_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Kuota Cuti ID")
	 * @var int
	 */
	protected $kuotaCutiId;

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
	 * @var JenisCutiMin
	 */
	protected $jenisCuti;

	/**
	 * Tahun
	 * 
	 * @Column(name="tahun", type="year(4)", length=4, nullable=true)
	 * @Label(content="Tahun")
	 * @var int
	 */
	protected $tahun;

	/**
	 * Kuota
	 * 
	 * @Column(name="kuota", type="int(11)", length=11, nullable=true)
	 * @Label(content="Kuota")
	 * @var int
	 */
	protected $kuota;

	/**
	 * Diambil
	 * 
	 * @Column(name="diambil", type="int(11)", length=11, nullable=true)
	 * @Label(content="Diambil")
	 * @var int
	 */
	protected $diambil;

	/**
	 * Sisa
	 * 
	 * @Column(name="sisa", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sisa")
	 * @var int
	 */
	protected $sisa;

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
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

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