<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The User class represents an entity in the "admin" table.
 *
 * This entity maps to the "admin" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="admin")
 */
class User extends MagicObject
{
	/**
	 * Admin ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="admin_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Admin ID")
	 * @var int
	 */
	protected $adminId;

	/**
	 * Username
	 * 
	 * @NotNull
	 * @Column(name="username", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Username")
	 * @var string
	 */
	protected $username;

	/**
	 * Password
	 * 
	 * @NotNull
	 * @Column(name="password", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Password")
	 * @var string
	 */
	protected $password;

	/**
	 * Token
	 * 
	 * @NotNull
	 * @Column(name="token", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Token")
	 * @var string
	 */
	protected $token;

	/**
	 * Nama Depan
	 * 
	 * @NotNull
	 * @Column(name="nama_depan", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Nama Depan")
	 * @var string
	 */
	protected $namaDepan;

	/**
	 * Nama Belakang
	 * 
	 * @NotNull
	 * @Column(name="nama_belakang", type="varchar(50)", length=50, nullable=false)
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
	 * @NotNull
	 * @Column(name="email", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Email")
	 * @var string
	 */
	protected $email;

	/**
	 * Telepon
	 * 
	 * @NotNull
	 * @Column(name="telepon", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Telepon")
	 * @var string
	 */
	protected $telepon;

	/**
	 * Gender ID
	 * 
	 * @NotNull
	 * @Column(name="jenis_kelamin", type="tinyint(4)", length=4, nullable=false)
	 * @Label(content="Gender ID")
	 * @var int
	 */
	protected $jenisKelamin;

	/**
	 * User Level ID
	 * 
	 * @NotNull
	 * @Column(name="user_level_id", type="bigint(20)", length=20, nullable=false)
	 * @Label(content="User Level ID")
	 * @var int
	 */
	protected $userLevelId;

	/**
	 * User Level
	 * 
	 * @JoinColumn(name="user_level_id", referenceColumnName="user_level_id")
	 * @Label(content="User Level")
	 * @var UserLevelMin
	 */
	protected $userLevel;

	/**
	 * Tipe Pengguna
	 * 
	 * @Column(name="tipe_pengguna", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Tipe Pengguna")
	 * @var string
	 */
	protected $tipePengguna;

	/**
	 * Ktsk ID
	 * 
	 * @Column(name="ktsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Ktsk ID")
	 * @var int
	 */
	protected $ktskId;

	/**
	 * Ktsk
	 * 
	 * @JoinColumn(name="ktsk_id", referenceColumnName="ktsk_id")
	 * @Label(content="Ktsk")
	 * @var KtskMin
	 */
	protected $ktsk;

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
	 * Lang ID
	 * 
	 * @NotNull
	 * @Column(name="lang_id", type="varchar(5)", length=5, nullable=false)
	 * @Label(content="Lang ID")
	 * @var string
	 */
	protected $langId;

	/**
	 * Waktu Ubah Foto
	 * 
	 * @Column(name="waktu_ubah_foto", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Ubah Foto")
	 * @var string
	 */
	protected $waktuUbahFoto;

	/**
	 * Blokir
	 * 
	 * @NotNull
	 * @Column(name="blokir", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Blokir")
	 * @var bool
	 */
	protected $blokir;

	/**
	 * Aktif
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

	/**
	 * Waktu Cek Terakhir
	 * 
	 * @NotNull
	 * @Column(name="waktu_cek_terakhir", type="timestamp", length=19, defaultValue="0000-00-00 00:00:00", nullable=false)
	 * @DefaultColumn(value="0000-00-00 00:00:00")
	 * @Label(content="Waktu Cek Terakhir")
	 * @var string
	 */
	protected $waktuCekTerakhir;

	/**
	 * Waktu Login Terakhir
	 * 
	 * @NotNull
	 * @Column(name="waktu_login_terakhir", type="timestamp", length=19, defaultValue="0000-00-00 00:00:00", nullable=false)
	 * @DefaultColumn(value="0000-00-00 00:00:00")
	 * @Label(content="Waktu Login Terakhir")
	 * @var string
	 */
	protected $waktuLoginTerakhir;

	/**
	 * IP Cek Terakhir
	 * 
	 * @NotNull
	 * @Column(name="ip_cek_terakhir", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="IP Cek Terakhir")
	 * @var string
	 */
	protected $ipCekTerakhir;

	/**
	 * Pertanyaan
	 * 
	 * @NotNull
	 * @Column(name="pertanyaan", type="varchar(200)", length=200, nullable=false)
	 * @Label(content="Pertanyaan")
	 * @var string
	 */
	protected $pertanyaan;

	/**
	 * Jawaban
	 * 
	 * @NotNull
	 * @Column(name="jawaban", type="varchar(200)", length=200, nullable=false)
	 * @Label(content="Jawaban")
	 * @var string
	 */
	protected $jawaban;

}