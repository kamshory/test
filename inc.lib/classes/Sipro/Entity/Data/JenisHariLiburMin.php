<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * JenisHariLiburMin is entity of table jenis_hari_libur. You can join this entity to other entity using annotation JoinColumn.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 *
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="jenis_hari_libur")
 */
class JenisHariLiburMin extends MagicObject
{
	/**
	 * Jenis Hari Libur ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="jenis_hari_libur_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Jenis Hari Libur ID")
	 * @var int
	 */
	protected $jenisHariLiburId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

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