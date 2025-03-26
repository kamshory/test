<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AktivitasDownload class represents an entity in the "aktivitas_download" table.
 *
 * This entity maps to the "aktivitas_download" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="aktivitas_download")
 */
class AktivitasDownload extends MagicObject
{
	/**
	 * Aktivitas Download ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="aktivitas_download_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Aktivitas Download ID")
	 * @var int
	 */
	protected $aktivitasDownloadId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="tinyint(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var int
	 */
	protected $nama;

	/**
	 * Url
	 * 
	 * @Column(name="url", type="text", nullable=true)
	 * @Label(content="Url")
	 * @var string
	 */
	protected $url;

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
	 * Waktu Download
	 * 
	 * @Column(name="waktu_download", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Download")
	 * @var string
	 */
	protected $waktuDownload;

	/**
	 * IP Download
	 * 
	 * @Column(name="ip_download", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Download")
	 * @var string
	 */
	protected $ipDownload;

}