<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * MenuCache is entity of table menu_cache. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="menu_cache")
 */
class MenuCache extends MagicObject
{
	/**
	 * Menu Cache ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="menu_cache_id", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Menu Cache ID")
	 * @var string
	 */
	protected $menuCacheId;

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
	 * User Type
	 * 
	 * @Column(name="user_type", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="User Type")
	 * @var string
	 */
	protected $userType;

	/**
	 * Content
	 * 
	 * @Column(name="content", type="longtext", nullable=true)
	 * @Label(content="Content")
	 * @var string
	 */
	protected $content;

}