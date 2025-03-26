<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * Notifikasi is entity of table notifikasi. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="notifikasi")
 */
class Notifikasi extends MagicObject
{
	/**
	 * Notifikasi ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="notifikasi_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Notifikasi ID")
	 * @var int
	 */
	protected $notifikasiId;

	/**
	 * Tipe Pengguna
	 * 
	 * @Column(name="tipe_pengguna", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Tipe Pengguna")
	 * @var string
	 */
	protected $tipePengguna;

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
	 * User ID
	 * 
	 * @Column(name="admin_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="User ID")
	 * @var int
	 */
	protected $userId;

	/**
	 * User
	 * 
	 * @JoinColumn(name="admin_id", referenceColumnName="admin_id")
	 * @Label(content="User")
	 * @var UserMin
	 */
	protected $user;

	/**
	 * Icon
	 * 
	 * @Column(name="icon", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Icon")
	 * @var string
	 */
	protected $icon;

	/**
	 * Subjek
	 * 
	 * @Column(name="subjek", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="Subjek")
	 * @var string
	 */
	protected $subjek;

	/**
	 * Teks
	 * 
	 * @Column(name="teks", type="text", nullable=true)
	 * @Label(content="Teks")
	 * @var string
	 */
	protected $teks;

	/**
	 * Tautan
	 * 
	 * @Column(name="tautan", type="text", nullable=true)
	 * @Label(content="Tautan")
	 * @var string
	 */
	protected $tautan;

	/**
	 * Dibaca
	 * 
	 * @Column(name="dibaca", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Dibaca")
	 * @var bool
	 */
	protected $dibaca;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=19, nullable=true, updatable=false)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true, updatable=false)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

}