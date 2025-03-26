<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The PermasalahanMin class represents an entity in the "permasalahan" table.
 *
 * This entity maps to the "permasalahan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="permasalahan")
 */
class PermasalahanMin extends MagicObject
{
	/**
	 * Permasalahan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="permasalahan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Permasalahan ID")
	 * @var int
	 */
	protected $permasalahanId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Permasalahan
	 * 
	 * @Column(name="permasalahan", type="text", nullable=true)
	 * @Label(content="Permasalahan")
	 * @var string
	 */
	protected $permasalahan;
	
	/**
	 * Rekomendasi
	 * 
	 * @Column(name="rekomendasi", type="longtext", nullable=true)
	 * @Label(content="Rekomendasi")
	 * @var string
	 */
	protected $rekomendasi;
	
	/**
	 * Tindak Lanjut
	 * 
	 * @Column(name="tindak_lanjut", type="longtext", nullable=true)
	 * @Label(content="Tindak Lanjut")
	 * @var string
	 */
	protected $tindakLanjut;

	/**
	 * Supervisor
	 * 
	 * @JoinColumn(name="supervisor_id", referenceColumnName="supervisor_id")
	 * @Label(content="Supervisor")
	 * @var SupervisorMin
	 */
	protected $supervisor;

	/**
	 * Ditutup
	 * 
	 * @Column(name="ditutup", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Ditutup")
	 * @var bool
	 */
	protected $ditutup;

	/**
	 * Waktu Tutup
	 * 
	 * @Column(name="waktu_tutup", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Tutup")
	 * @var string
	 */
	protected $waktuTutup;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="varchar(40)", length=40, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var string
	 */
	protected $adminBuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var string
	 */
	protected $adminUbah;

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
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}