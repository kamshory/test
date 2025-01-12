<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * PeriodeMin represents the entity for the table periode.
 * You can join this entity to other entities using the JoinColumn annotation.
 * 
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="periode")
 */
class PeriodeMin extends MagicObject
{
	/**
	 * Periode ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="periode_id", type="varchar(6)", length=6, nullable=false)
	 * @Label(content="Periode ID")
	 * @var string
	 */
	protected $periodeId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

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