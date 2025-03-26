<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The JenisHirarkiKontrakForList class represents an entity in the "jenis_hirarki_kontrak" table.
 *
 * This entity maps to the "jenis_hirarki_kontrak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="jenis_hirarki_kontrak")
 */
class JenisHirarkiKontrakForList extends MagicObject
{
	/**
	 * Jenis Hirarki Kontrak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="jenis_hirarki_kontrak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Jenis Hirarki Kontrak ID")
	 * @var int
	 */
	protected $jenisHirarkiKontrakId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;
}