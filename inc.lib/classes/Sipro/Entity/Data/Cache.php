<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * Cache is entity of table cache. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="cache")
 */
class Cache extends MagicObject
{
	/**
	 * Cache ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @NotNull
	 * @Column(name="cache_id", type="varchar(100)", length=100, nullable=false)
	 * @Label(content="Cache ID")
	 * @var string
	 */
	protected $cacheId;

	/**
	 * Content
	 * 
	 * @Column(name="content", type="longtext", nullable=true)
	 * @Label(content="Content")
	 * @var string
	 */
	protected $content;

	/**
	 * Expire
	 * 
	 * @Column(name="expire", type="timestamp", length=19, nullable=true)
	 * @Label(content="Expire")
	 * @var string
	 */
	protected $expire;

}