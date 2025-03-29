<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * BillOfQuantityMin is entity of table bill_of_quantity. You can join this entity to other entity using annotation JoinColumn. 
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="bill_of_quantity")
 */
class BillOfQuantityMin extends MagicObject
{
	/**
	 * Bill Of Quantity ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="bill_of_quantity_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Bill Of Quantity ID")
	 * @var int
	 */
	protected $billOfQuantityId;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Parent ID
	 * 
	 * @Column(name="parent_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Parent ID")
	 * @var int
	 */
	protected $parentId;

	/**
	 * Level
	 * 
	 * @Column(name="level", type="int(11)", length=11, nullable=true)
	 * @Label(content="Level")
	 * @var int
	 */
	protected $level;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(255)", length=255, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Satuan
	 * 
	 * @Column(name="satuan", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Satuan")
	 * @var string
	 */
	protected $satuan;

	/**
	 * Volume
	 * 
	 * @Column(name="volume", type="float", nullable=true)
	 * @Label(content="Volume")
	 * @var double
	 */
	protected $volume;

	/**
	 * Bobot
	 * 
	 * @Column(name="bobot", type="float", nullable=true)
	 * @Label(content="Bobot")
	 * @var double
	 */
	protected $bobot;
	
	/**
	 * Volume Proyek
	 * 
	 * @Column(name="volume_proyek", type="float", nullable=true)
	 * @Label(content="Volume Proyek")
	 * @var double
	 */
	protected $volumeProyek;

	/**
	 * Persen
	 * 
	 * @Column(name="persen", type="float", nullable=true)
	 * @Label(content="Persen")
	 * @var double
	 */
	protected $persen;

	/**
	 * Waktu Ubah Volume Proyek
	 * 
	 * @Column(name="waktu_ubah_volume_proyek", type="timestamp", length=19, nullable=true, updatable=false)
	 * @Label(content="Waktu Ubah Volume Proyek")
	 * @var string
	 */
	protected $waktuUbahVolumeProyek;

	/**
	 * Harga
	 * 
	 * @Column(name="harga", type="float", nullable=true)
	 * @Label(content="Harga")
	 * @var double
	 */
	protected $harga;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="bigint(20)", length=20, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var int
	 */
	protected $adminBuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var int
	 */
	protected $adminUbah;

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
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true, updatable=false)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Aktif
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var int
	 */
	protected $aktif;

}