<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The Ktsk class represents an entity in the "ktsk" table.
 *
 * This entity maps to the "ktsk" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="ktsk")
 */
class Ktsk extends MagicObject
{
	/**
	 * Ktsk ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="ktsk_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Ktsk ID")
	 * @var int
	 */
	protected $ktskId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Nip
	 * 
	 * @Column(name="nip", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Nip")
	 * @var string
	 */
	protected $nip;

	/**
	 * Jabatan ID
	 * 
	 * @Column(name="jabatan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jabatan ID")
	 * @var int
	 */
	protected $jabatanId;

	/**
	 * Jabatan
	 * 
	 * @JoinColumn(name="jabatan_id", referenceColumnName="jabatan_id")
	 * @Label(content="Jabatan")
	 * @var JabatanMin
	 */
	protected $jabatan;

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
	 * Jenis Kelamin
	 * 
	 * @Column(name="jenis_kelamin", type="varchar(3)" length=3, defaultValue="L", nullable=true)
	 * @DefaultColumn(value="L")
	 * @Label(content="Jenis Kelamin")
	 * @var string
	 */
	protected $jenisKelamin;

	/**
	 * Tempat Lahir
	 * 
	 * @Column(name="tempat_lahir", type="varchar(45)", length=45, nullable=true)
	 * @Label(content="Tempat Lahir")
	 * @var string
	 */
	protected $tempatLahir;

	/**
	 * Tanggal Lahir
	 * 
	 * @Column(name="tanggal_lahir", type="date", nullable=true)
	 * @Label(content="Tanggal Lahir")
	 * @var string
	 */
	protected $tanggalLahir;

	/**
	 * Email
	 * 
	 * @Column(name="email", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Email")
	 * @var string
	 */
	protected $email;

	/**
	 * Telepon
	 * 
	 * @Column(name="telepon", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Telepon")
	 * @var string
	 */
	protected $telepon;

	/**
	 * Username
	 * 
	 * @Column(name="username", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Username")
	 * @var string
	 */
	protected $username;

	/**
	 * Password
	 * 
	 * @Column(name="password", type="varchar(45)", length=45, nullable=true)
	 * @Label(content="Password")
	 * @var string
	 */
	protected $password;

	/**
	 * Auth
	 * 
	 * @Column(name="auth", type="varchar(45)", length=45, nullable=true)
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
	 * @var UserMin
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
	 * @var UserMin
	 */
	protected $pengubah;

	/**
	 * Waktu Terakhir Aktif
	 * 
	 * @Column(name="waktu_terakhir_aktif", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Terakhir Aktif")
	 * @var string
	 */
	protected $waktuTerakhirAktif;

	/**
	 * IP Terakhir Aktif
	 * 
	 * @Column(name="ip_terakhir_aktif", type="varchar(45)", length=45, nullable=true)
	 * @Label(content="IP Terakhir Aktif")
	 * @var string
	 */
	protected $ipTerakhirAktif;

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