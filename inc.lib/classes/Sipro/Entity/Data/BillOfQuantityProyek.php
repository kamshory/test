<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The BillOfQuantityProyek class represents an entity in the "bill_of_quantity_proyek" table.
 *
 * This entity maps to the "bill_of_quantity_proyek" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="bill_of_quantity_proyek")
 */
class BillOfQuantityProyek extends MagicObject
{
	/**
	 * Bill Of Quantity Proyek ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="bill_of_quantity_proyek_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Bill Of Quantity Proyek ID")
	 * @var int
	 */
	protected $billOfQuantityProyekId;

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
	 * @var Proyek
	 */
	protected $proyek;

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
	 * Bill Of Quantity ID
	 * 
	 * @Column(name="bill_of_quantity_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Bill Of Quantity ID")
	 * @var int
	 */
	protected $billOfQuantityId;

	/**
	 * Bill Of Quantity
	 * 
	 * @JoinColumn(name="bill_of_quantity_id", referenceColumnName="bill_of_quantity_id")
	 * @Label(content="Bill Of Quantity")
	 * @var BillOfQuantityMin
	 */
	protected $billOfQuantity;

	/**
	 * Volume
	 * 
	 * @Column(name="volume", type="float", nullable=true)
	 * @Label(content="Volume")
	 * @var float
	 */
	protected $volume;

	/**
	 * Volume Proyek
	 * 
	 * @Column(name="volume_proyek", type="float", nullable=true)
	 * @Label(content="Volume Proyek")
	 * @var float
	 */
	protected $volumeProyek;

	/**
	 * Persen
	 * 
	 * @Column(name="persen", type="float", nullable=true)
	 * @Label(content="Persen")
	 * @var float
	 */
	protected $persen;

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
	 * Supervisor Buat
	 * 
	 * @Column(name="supervisor_buat", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor Buat")
	 * @var int
	 */
	protected $supervisorBuat;

	/**
	 * Pembuat
	 * 
	 * @JoinColumn(name="supervisor_buat", referenceColumnName="supervisor_id")
	 * @Label(content="Pembuat")
	 * @var Supervisor
	 */
	protected $pembuat;

	/**
	 * Supervisor Ubah
	 * 
	 * @Column(name="supervisor_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor Ubah")
	 * @var int
	 */
	protected $supervisorUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="supervisor_ubah", referenceColumnName="supervisor_id")
	 * @Label(content="Pengubah")
	 * @var Supervisor
	 */
	protected $pengubah;

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