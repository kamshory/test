<?php

namespace Sipro\Entity\App;

use MagicApp\Entity\AppModule;

/**
 * AppModuleImpl 
 * 
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="modul")
 */
class AppModuleImpl extends AppModule
{
	/**
	 * Module ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @Column(name="modul_id", type="varchar(40)", length=40, nullable=false)
	 * @DefaultColumn(value="NULL")
	 * @Label(content="Module ID")
	 * @var string
	 */
	protected $moduleId;

	/**
	 * Module Group ID
	 * 
	 * @NotNull
	 * @Column(name="grup_modul_id", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
	 * @Label(content="Module Group ID")
	 * @var string
	 */
	protected $moduleGroupId;

	/**
	 * Module Group
	 * 
	 * @NotNull
	 * @JoinColumn(name="grup_modul_id", referenceColumnName="grup_modul_id")
	 * @Label(content="Module Group")
	 * @var AppModuleGroupImpl
	 */
	protected $moduleGroup;

	/**
	 * Module Code
	 * 
	 * @NotNull
	 * @Column(name="kode_modul", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
	 * @Label(content="Module Code")
	 * @var string
	 */
	protected $moduleCode;

	/**
	 * Name
	 * 
	 * @NotNull
	 * @Column(name="nama", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
	 * @Label(content="Name")
	 * @var string
	 */
	protected $name;

	/**
	 * Icon
	 * 
	 * @NotNull
	 * @Column(name="icon", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
	 * @Label(content="Icon")
	 * @var string
	 */
	protected $icon;

	/**
	 * URL
	 * 
	 * @NotNull
	 * @Column(name="url", type="longtext", nullable=true)
	 * @Label(content="URL")
	 * @var string
	 */
	protected $url;

	/**
	 * Menu
	 * 
	 * @NotNull
	 * @Column(name="menu", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
	 * @Label(content="Menu")
	 * @var bool
	 */
	protected $menu;


	/**
	 * Special Access
	 * 
	 * @NotNull
	 * @Column(name="istimewa", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
	 * @Label(content="Special Access")
	 * @var bool
	 */
	protected $specialAccess;

	/**
	 * Sort Order
	 * 
	 * @NotNull
	 * @Column(name="sort_order", type="int(11)", length=1, defaultValue="0", nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Default Data
	 * 
	 * @NotNull
	 * @Column(name="default_data", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
	 * @Label(content="Default Data")
	 * @var bool
	 */
	protected $defaultData;

	/**
	 * Active
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
	 * @Label(content="Active")
	 * @var bool
	 */
	protected $active;
}
