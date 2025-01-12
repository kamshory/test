<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The JenisPekerjaan class represents an entity in the "jenis_pekerjaan" table.
 *
 * This entity maps to the "jenis_pekerjaan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="jenis_pekerjaan")
 */
class JenisPekerjaan extends MagicObject
{
	/**
	 * Jenis Pekerjaan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="jenis_pekerjaan_id", type="char(3)", length=3, nullable=false)
	 * @Label(content="Jenis Pekerjaan ID")
	 * @var string
	 */
	protected $jenisPekerjaanId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Tipe Pondasi ID
	 * 
	 * @Column(name="tipe_pondasi_id", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Tipe Pondasi ID")
	 * @var bool
	 */
	protected $tipePondasiId;

	/**
	 * Kelas Tower ID
	 * 
	 * @Column(name="kelas_tower_id", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Kelas Tower ID")
	 * @var bool
	 */
	protected $kelasTowerId;

	/**
	 * Lokasi Proyek ID
	 * 
	 * @Column(name="lokasi_proyek_id", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Lokasi Proyek ID")
	 * @var bool
	 */
	protected $lokasiProyekId;

	/**
	 * Kegiatan
	 * 
	 * @Column(name="kegiatan", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Kegiatan")
	 * @var bool
	 */
	protected $kegiatan;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Default Data
	 * 
	 * @Column(name="default_data", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Default Data")
	 * @var bool
	 */
	protected $defaultData;

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
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=19, nullable=true)
	 * @Label(content="Waktu Ubah")
	 * @var string
	 */
	protected $waktuUbah;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

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