<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The WaktuUbah class represents an entity in the "waktu_ubah" table.
 *
 * This entity maps to the "waktu_ubah" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(propertyNamingStrategy=SNAKE_CASE, prettify=false)
 * @Table(name="waktu_ubah")
 */
class WaktuUbah extends MagicObject
{

}