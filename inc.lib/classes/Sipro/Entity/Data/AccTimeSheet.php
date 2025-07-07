<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The AccTimeSheet class represents an entity in the "acc_time_sheet" table.
 *
 * This entity maps to the "acc_time_sheet" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="acc_time_sheet")
 */
class AccTimeSheet extends MagicObject
{
	/**
	 * Acc Time Sheet ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="acc_time_sheet_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Acc Time Sheet ID")
	 * @var int
	 */
	protected $accTimeSheetId;

	/**
	 * Supervisor ID
	 * 
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

	/**
	 * Periode ID
	 * 
	 * @Column(name="periode_id", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Periode ID")
	 * @var string
	 */
	protected $periodeId;

	/**
	 * Acc Koordinator
	 * 
	 * @Column(name="acc_koordinator", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Acc Koordinator")
	 * @var bool
	 */
	protected $accKoordinator;

	/**
	 * Koordinator ID
	 * 
	 * @Column(name="koordinator_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Koordinator ID")
	 * @var int
	 */
	protected $koordinatorId;

	/**
	 * Waktu Acc Koordinator
	 * 
	 * @Column(name="waktu_acc_koordinator", type="timestamp", length=26, nullable=true)
	 * @Label(content="Waktu Acc Koordinator")
	 * @var string
	 */
	protected $waktuAccKoordinator;

	/**
	 * IP Acc Koordinator
	 * 
	 * @Column(name="ip_acc_koordinator", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Acc Koordinator")
	 * @var string
	 */
	protected $ipAccKoordinator;

	/**
	 * Acc Ktsk
	 * 
	 * @Column(name="acc_ktsk", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Acc Ktsk")
	 * @var bool
	 */
	protected $accKtsk;

	/**
	 * Ktsk ID
	 * 
	 * @Column(name="ktsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Ktsk ID")
	 * @var int
	 */
	protected $ktskId;

	/**
	 * Waktu Acc Ktsk
	 * 
	 * @Column(name="waktu_acc_ktsk", type="timestamp", length=26, nullable=true)
	 * @Label(content="Waktu Acc Ktsk")
	 * @var string
	 */
	protected $waktuAccKtsk;

	/**
	 * IP Acc Ktsk
	 * 
	 * @Column(name="ip_acc_ktsk", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Acc Ktsk")
	 * @var string
	 */
	protected $ipAccKtsk;

}