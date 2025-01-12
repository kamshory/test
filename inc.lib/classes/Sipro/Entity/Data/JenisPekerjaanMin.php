<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * JenisPekerjaan is entity of table jenis_pekerjaan. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="jenis_pekerjaan")
 */
class JenisPekerjaanMin extends MagicObject
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
	 * @Column(name="tipe_pondasi_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tipe Pondasi ID")
	 * @var int
	 */
	protected $tipePondasiId;

	/**
	 * Kelas Tower ID
	 * 
	 * @Column(name="kelas_tower_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Kelas Tower ID")
	 * @var int
	 */
	protected $kelasTowerId;

	/**
	 * Lokasi Proyek ID
	 * 
	 * @Column(name="lokasi_proyek_id", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Lokasi Proyek ID")
	 * @var bool
	 */
	protected $lokasiProyekId;

	/**
	 * Kegiatan
	 * 
	 * @Column(name="kegiatan", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
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
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

}