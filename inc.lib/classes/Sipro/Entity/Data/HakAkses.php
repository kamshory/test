<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * HakAkses is entity of table hak_akses. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="hak_akses")
 */
class HakAkses extends MagicObject
{
	/**
	 * Hak Akses ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="hak_akses_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Hak Akses ID")
	 * @var int
	 */
	protected $hakAksesId;

	/**
	 * Modul ID
	 * 
	 * @Column(name="modul_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Modul ID")
	 * @var int
	 */
	protected $modulId;

	/**
	 * Modul
	 * 
	 * @JoinColumn(name="modul_id", referenceColumnName="modul_id")
	 * @Label(content="Modul")
	 * @var Modul
	 */
	protected $modul;

	/**
	 * Kode Modul
	 * 
	 * @NotNull
	 * @Column(name="kode_modul", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Kode Modul")
	 * @var string
	 */
	protected $kodeModul;

	/**
	 * Allowed List
	 * 
	 * @Column(name="allowed_list", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed List")
	 * @var bool
	 */
	protected $allowedList;

	/**
	 * Allowed Detail
	 * 
	 * @Column(name="allowed_detail", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Detail")
	 * @var bool
	 */
	protected $allowedDetail;

	/**
	 * Allowed Create
	 * 
	 * @Column(name="allowed_create", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Create")
	 * @var bool
	 */
	protected $allowedCreate;

	/**
	 * Allowed Update
	 * 
	 * @Column(name="allowed_update", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Update")
	 * @var bool
	 */
	protected $allowedUpdate;

	/**
	 * Allowed Delete
	 * 
	 * @Column(name="allowed_delete", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Delete")
	 * @var bool
	 */
	protected $allowedDelete;

	/**
	 * Allowed Approve
	 * 
	 * @Column(name="allowed_approve", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Approve")
	 * @var bool
	 */
	protected $allowedApprove;

	/**
	 * Allowed Sort Order
	 * 
	 * @Column(name="allowed_sort_order", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Sort Order")
	 * @var bool
	 */
	protected $allowedSortOrder;
	
	/**
	 * Allowed Export
	 * 
	 * @Column(name="allowed_export", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Export")
	 * @var bool
	 */
	protected $allowedExport;

	/**
	 * Cabang ID
	 * 
	 * @Column(name="cabang_id", type="varchar(20)", length=20, nullable=true)
	 * @Label(content="Cabang ID")
	 * @var string
	 */
	protected $cabangId;

	/**
	 * User Level ID
	 * 
	 * @Column(name="user_level_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="User Level ID")
	 * @var int
	 */
	protected $userLevelId;

	/**
	 * User Level
	 * 
	 * @JoinColumn(name="user_level_id", referenceColumnName="user_level_id")
	 * @Label(content="User Level")
	 * @var UserLevel
	 */
	protected $userLevel;

	/**
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

	/**
	 * Update kode modul
	 *
	 * @param int $hakAksesId
	 * @query("UPDATE hak_akses SET kode_modul = (SELECT modul.kode_modul FROM modul WHERE modul.modul_id = hak_akses.modul_id) WHERE hak_akses.hak_akses_id = :hakAksesId")
	 * @return void
	 */
	public function updateModule($hakAksesId)
	{
		$this->executeNativeQuery();
	}

}