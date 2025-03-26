<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The CatatanSalahLogin class represents an entity in the "catatan_salah_login" table.
 *
 * This entity maps to the "catatan_salah_login" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="catatan_salah_login")
 */
class CatatanSalahLogin extends MagicObject
{
	/**
	 * Catatan Salah Login ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="catatan_salah_login_id", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Catatan Salah Login ID")
	 * @var string
	 */
	protected $catatanSalahLoginId;

	/**
	 * Tipe Pengguna
	 * 
	 * @Column(name="tipe_pengguna", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Tipe Pengguna")
	 * @var string
	 */
	protected $tipePengguna;

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

	/**
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}