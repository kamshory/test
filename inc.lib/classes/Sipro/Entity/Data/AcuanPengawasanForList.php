<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AcuanPengawasanForList class represents an entity in the "acuan_pengawasan" table.
 *
 * This entity maps to the "acuan_pengawasan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="acuan_pengawasan")
 */
class AcuanPengawasanForList extends MagicObject
{
	/**
	 * Acuan Pengawasan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="acuan_pengawasan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Acuan Pengawasan ID")
	 * @var int
	 */
	protected $acuanPengawasanId;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Nomor
	 * 
	 * @Column(name="nomor", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nomor")
	 * @var string
	 */
	protected $nomor;

	/**
	 * Jenis Hirarki Kontrak ID
	 * 
	 * @Column(name="jenis_hirarki_kontrak_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Jenis Hirarki Kontrak ID")
	 * @var int
	 */
	protected $jenisHirarkiKontrakId;

	/**
	 * Jenis Hirarki Kontrak
	 * 
	 * @JoinColumn(name="jenis_hirarki_kontrak_id", referenceColumnName="jenis_hirarki_kontrak_id")
	 * @Label(content="Jenis Hirarki Kontrak")
	 * @var JenisHirarkiKontrakForList
	 */
	protected $jenisHirarkiKontrak;

	/**
	 * Status Acuan Pengawasan ID
	 * 
	 * @Column(name="status_acuan_pengawasan_id", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Status Acuan Pengawasan ID")
	 * @var string
	 */
	protected $statusAcuanPengawasanId;

	/**
	 * Status Acuan Pengawasan
	 * 
	 * @JoinColumn(name="status_acuan_pengawasan_id", referenceColumnName="status_acuan_pengawasan_id")
	 * @Label(content="Status Acuan Pengawasan")
	 * @var StatusAcuanPengawasanForList
	 */
	protected $statusAcuanPengawasan;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

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