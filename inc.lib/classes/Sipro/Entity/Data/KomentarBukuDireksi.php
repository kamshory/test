<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The KomentarBukuDireksi class represents an entity in the "komentar_buku_direksi" table.
 *
 * This entity maps to the "komentar_buku_direksi" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="komentar_buku_direksi")
 */
class KomentarBukuDireksi extends MagicObject
{
	/**
	 * Komentar Buku Direksi ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="komentar_buku_direksi_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Komentar Buku Direksi ID")
	 * @var int
	 */
	protected $komentarBukuDireksiId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
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
	 * Buku Direksi ID
	 * 
	 * @Column(name="buku_direksi_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Direksi ID")
	 * @var int
	 */
	protected $bukuDireksiId;

	/**
	 * Buku Direksi
	 * 
	 * @JoinColumn(name="buku_direksi_id", referenceColumnName="buku_direksi_id")
	 * @Label(content="Buku Direksi")
	 * @var BukuDireksiMin
	 */
	protected $bukuDireksi;

	/**
	 * Komentar
	 * 
	 * @Column(name="komentar", type="longtext", nullable=true)
	 * @Label(content="Komentar")
	 * @var string
	 */
	protected $komentar;

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
	 * @Column(name="user_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="User ID")
	 * @var int
	 */
	protected $userId;

	/**
	 * User
	 * 
	 * @JoinColumn(name="user_id", referenceColumnName="user_id")
	 * @Label(content="User")
	 * @var UserMin
	 */
	protected $user;

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