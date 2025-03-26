<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The StatusAcuanPengawasanForList class represents an entity in the "status_acuan_pengawasan" table.
 *
 * This entity maps to the "status_acuan_pengawasan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="status_acuan_pengawasan")
 */
class StatusAcuanPengawasanForList extends MagicObject
{
	/**
	 * Status Acuan Pengawasan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="status_acuan_pengawasan_id", type="varchar(20)", length=20, nullable=false)
	 * @Label(content="Kode")
	 * @var string
	 */
	protected $statusAcuanPengawasanId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

}