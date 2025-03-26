<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * SupervisorLang represents the entity for the table supervisor.
 * You can join this entity to other entities using the JoinColumn annotation.
 * 
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="supervisor")
 */
class SupervisorLang extends MagicObject
{
	/**
	 * Supervisor ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="supervisor_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Supervisor ID")
	 * @var int
	 */
	protected $supervisorId;

    /**
	 * Bahasa
	 * 
	 * @Column(name="lang_id", type="varchar(5)", length=5, defaultValue="id", nullable=true)
	 * @DefaultColumn(value="id")
	 * @Label(content="Bahasa")
	 * @var string
	 */
	protected $languageId;
}