<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * SupervisorResetPassword is entity of table supervisor. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="supervisor")
 */
class SupervisorResetPassword extends MagicObject
{
	/**
	 * Supervisor ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Username
	 * 
	 * @Column(name="username", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Username")
	 * @var string
	 */
	protected $username;

	/**
	 * Password
	 * 
	 * @Column(name="password", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Password")
	 * @var string
	 */
	protected $password;

	/**
	 * Nama Depan
	 * 
	 * @Column(name="nama_depan", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama Depan")
	 * @var string
	 */
	protected $namaDepan;

	/**
	 * Nama Belakang
	 * 
	 * @Column(name="nama_belakang", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama Belakang")
	 * @var string
	 */
	protected $namaBelakang;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Email
	 * 
	 * @Column(name="email", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Email")
	 * @var string
	 */
	protected $email;

	/**
	 * Auth
	 * 
	 * @Column(name="auth", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Auth")
	 * @var string
	 */
	protected $auth;

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
	 * Blokir
	 * 
	 * @Column(name="blokir", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Blokir")
	 * @var bool
	 */
	protected $blokir;

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