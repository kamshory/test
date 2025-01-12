<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * Cuti is entity of table cuti. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="cuti")
 */
class Cuti extends MagicObject
{
	/**
	 * Cuti ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="cuti_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Cuti ID")
	 * @var int
	 */
	protected $cutiId;

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
	 * Proyek ID
	 * 
	 * @NotNull
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=false)
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
	 * Cuti Dari
	 * 
	 * @Column(name="cuti_dari", type="date", nullable=true)
	 * @Label(content="Cuti Dari")
	 * @var string
	 */
	protected $cutiDari;

	/**
	 * Cuti Hingga
	 * 
	 * @Column(name="cuti_hingga", type="date", nullable=true)
	 * @Label(content="Cuti Hingga")
	 * @var string
	 */
	protected $cutiHingga;

	/**
	 * Hari Cuti
	 * 
	 * @Column(name="hari_cuti", type="int(11)", length=11, nullable=true)
	 * @Label(content="Hari Cuti")
	 * @var int
	 */
	protected $hariCuti;

	/**
	 * Alasan
	 * 
	 * @Column(name="alasan", type="text", nullable=true)
	 * @Label(content="Alasan")
	 * @var string
	 */
	protected $alasan;

	/**
	 * Supervisor Pengganti
	 * 
	 * @Column(name="supervisor_pengganti", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor Pengganti")
	 * @var int
	 */
	protected $supervisorPengganti;

	/**
	 * Pengganti
	 * 
	 * @JoinColumn(name="supervisor_pengganti", referenceColumnName="supervisor_id")
	 * @Label(content="Pengganti")
	 * @var SupervisorMin
	 */
	protected $pengganti;

	/**
	 * Status
	 * 
	 * @Column(name="status", type="enum('P','A','R','O','C')", defaultValue="P", nullable=true)
	 * @DefaultColumn(value="P")
	 * @Label(content="Status")
	 * @var string
	 */
	protected $status;

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
	 * Waktu Approve
	 * 
	 * @Column(name="waktu_approve", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Approve")
	 * @var string
	 */
	protected $waktuApprove;

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
	 * IP Approve
	 * 
	 * @Column(name="ip_approve", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Approve")
	 * @var string
	 */
	protected $ipApprove;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Pembuat
	 * 
	 * @JoinColumn(name="admin_buat", referenceColumnName="user_id")
	 * @Label(content="Pembuat")
	 * @var User
	 */
	protected $pembuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="admin_ubah", referenceColumnName="user_id")
	 * @Label(content="Pengubah")
	 * @var User
	 */
	protected $pengubah;

	/**
	 * Admin Approve
	 * 
	 * @Column(name="admin_approve", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Approve")
	 * @var int
	 */
	protected $adminApprove;

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