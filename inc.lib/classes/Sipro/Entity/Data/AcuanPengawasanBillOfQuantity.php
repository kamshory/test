<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AcuanPengawasanBillOfQuantity class represents an entity in the "acuan_pengawasan_bill_of_quantity" table.
 *
 * This entity maps to the "acuan_pengawasan_bill_of_quantity" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="acuan_pengawasan_bill_of_quantity")
 */
class AcuanPengawasanBillOfQuantity extends MagicObject
{
	/**
	 * Acuan Pengawasan Bill Of Quantity ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="acuan_pengawasan_bill_of_quantity_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Acuan Pengawasan Bill Of Quantity ID")
	 * @var int
	 */
	protected $acuanPengawasanBillOfQuantityId;

	/**
	 * Acuan Pengawasan ID
	 * 
	 * @Column(name="acuan_pengawasan_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Acuan Pengawasan ID")
	 * @var int
	 */
	protected $acuanPengawasanId;

	/**
	 * Acuan Pengawasan
	 * 
	 * @JoinColumn(name="acuan_pengawasan_id", referenceColumnName="acuan_pengawasan_id")
	 * @Label(content="Acuan Pengawasan")
	 * @var AcuanPengawasan
	 */
	protected $acuanPengawasan;

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

}