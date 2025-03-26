<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The LokasiPekerjaan class represents an entity in the "lokasi_pekerjaan" table.
 *
 * This entity maps to the "lokasi_pekerjaan" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="lokasi_pekerjaan")
 */
class LokasiPekerjaan extends MagicObject
{
	/**
	 * Lokasi Pekerjaan ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="lokasi_pekerjaan_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Lokasi Pekerjaan ID")
	 * @var int
	 */
	protected $lokasiPekerjaanId;

	/**
	 * Pekerjaan ID
	 * 
	 * @Column(name="pekerjaan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Pekerjaan ID")
	 * @var int
	 */
	protected $pekerjaanId;

	/**
	 * Pekerjaan
	 * 
	 * @JoinColumn(name="pekerjaan_id", referenceColumnName="pekerjaan_id")
	 * @Label(content="Pekerjaan")
	 * @var Pekerjaan
	 */
	protected $pekerjaan;

	/**
	 * Buku Harian ID
	 * 
	 * @Column(name="buku_harian_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Buku Harian ID")
	 * @var int
	 */
	protected $bukuHarianId;

	/**
	 * Buku Harian
	 * 
	 * @JoinColumn(name="buku_harian_id", referenceColumnName="buku_harian_id")
	 * @Label(content="Buku Harian")
	 * @var BukuHarian
	 */
	protected $bukuHarian;

	/**
	 * Lokasi Proyek ID
	 * 
	 * @Column(name="lokasi_proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Lokasi Proyek ID")
	 * @var int
	 */
	protected $lokasiProyekId;

	/**
	 * Lokasi Proyek
	 * 
	 * @JoinColumn(name="lokasi_proyek_id", referenceColumnName="lokasi_proyek_id")
	 * @Label(content="Lokasi Proyek")
	 * @var LokasiProyekMin
	 */
	protected $lokasiProyek;

	/**
	 * Latitude
	 * 
	 * @Column(name="latitude", type="float", nullable=true)
	 * @Label(content="Latitude")
	 * @var float
	 */
	protected $latitude;

	/**
	 * Longitude
	 * 
	 * @Column(name="longitude", type="float", nullable=true)
	 * @Label(content="Longitude")
	 * @var float
	 */
	protected $longitude;

	/**
	 * Altitude
	 * 
	 * @Column(name="altitude", type="float", nullable=true)
	 * @Label(content="Altitude")
	 * @var float
	 */
	protected $altitude;

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