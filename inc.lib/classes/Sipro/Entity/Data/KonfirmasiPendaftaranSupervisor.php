<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * KonfirmasiPendaftaranSupervisor is entity of table konfirmasi_pendaftaran_supervisor. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="konfirmasi_pendaftaran_supervisor")
 */
class KonfirmasiPendaftaranSupervisor extends MagicObject
{
    /**
	 * Konfirmasi Pendaftaran Supervisor ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="konfirmasi_pendaftaran_supervisor_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Konfirmasi Pendaftaran Supervisor ID")
	 * @var int
	 */
	protected $konfirmasiPendaftaranSupervisorId;

    /**
	 * Supervisor ID
	 * 
	 * @NotNull
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=false)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

    /**
	 * Supervisor
	 * 
	 * @JoinColumn(name="supervisor_id", referenceColumnName="supervisor_id")
	 * @Label(content="Supervisor")
	 * @var Supervisor
	 */
	protected $supervisor;

    /**
	 * Email
	 * 
	 * @Column(name="email", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Email")
	 * @var string
	 */
	protected $email;

    
    /**
	 * Kode Konfirmasi Email
	 * 
	 * @Column(name="kode_konfirmasi_email", type="varchar(512)", length=512, defaultValue="NULL" nullable=true)
	 * @Label(content="Kode Konfirmasi Email")
	 * @var string
	 */
	protected $kodeKonfirmasiEmail;

    /**
	 * Konfirmasi
	 * 
	 * @Column(name="konfirmasi", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Konfirmasi")
	 * @var bool
	 */
	protected $konfirmasi;

    /**
	 * Kedaluarsa
	 * 
	 * @Column(name="kedaluarsa", type="datetime", length=19, nullable=true)
	 * @Label(content="Kedaluarsa")
	 * @var string
	 */
	protected $kedaluarsa;

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
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}