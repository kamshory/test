<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The Cuti class represents an entity in the "cuti" table.
 *
 * This entity maps to the "cuti" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
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
	 * Tsk ID
	 * 
	 * @Column(name="tsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tsk ID")
	 * @var int
	 */
	protected $tskId;

	/**
	 * Tsk
	 * 
	 * @JoinColumn(name="tsk_id", referenceColumnName="tsk_id")
	 * @Label(content="Tsk")
	 * @var TskMin
	 */
	protected $tsk;

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
	 * Dibayar
	 * 
	 * @Column(name="dibayar", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Dibayar")
	 * @var bool
	 */
	protected $dibayar;

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
	 * Detil Tanggal Cuti
	 * 
	 * @Column(name="detil_tanggal_cuti", type="text", nullable=true)
	 * @Label(content="Detil Tanggal Cuti")
	 * @var string
	 */
	protected $detilTanggalCuti;

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
	 * Status Cuti
	 * 
	 * @Column(name="status_cuti", type="text", defaultValue="P", nullable=true)
	 * @DefaultColumn(value="P")
	 * @Label(content="Status Cuti")
	 * @var string
	 */
	protected $statusCuti;

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
	 * @JoinColumn(name="admin_buat", referenceColumnName="admin_id")
	 * @Label(content="Pembuat")
	 * @var AdminMin
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
	 * @JoinColumn(name="admin_ubah", referenceColumnName="admin_id")
	 * @Label(content="Pengubah")
	 * @var AdminMin
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
	 * Approver
	 * 
	 * @JoinColumn(name="admin_approve", referenceColumnName="admin_id")
	 * @Label(content="Approver")
	 * @var Admin
	 */
	protected $approver;

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