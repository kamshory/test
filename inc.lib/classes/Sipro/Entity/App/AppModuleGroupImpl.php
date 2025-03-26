<?php

namespace Sipro\Entity\App;

use MagicApp\Entity\AppModuleGroup;

/**
 * AppModuleGroupImpl 
 * 
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="grup_modul")
 */
class AppModuleGroupImpl extends AppModuleGroup
{
	/**
	 * Module Group ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @Column(name="grup_modul_id", type="varchar(40)", length=40, nullable=false)
	 * @DefaultColumn(value="NULL")
	 * @Label(content="Module Group ID")
	 * @var string
	 */
	protected $moduleGroupId;

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
