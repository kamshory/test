<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * UserResetPassword is entity of table user. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="admin")
 */
class UserResetPassword extends MagicObject
{
	/**
	 * User ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="admin_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="User ID")
	 * @var int
	 */
	protected $userId;

	/**
	 * Username
	 * 
	 * @NotNull
	 * @Column(name="username", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Username")
	 * @var string
	 */
	protected $username;

	/**
	 * Password
	 * 
	 * @NotNull
	 * @Column(name="password", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Password")
	 * @var string
	 */
	protected $password;

	/**
	 * Token
	 * 
	 * @NotNull
	 * @Column(name="token", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Token")
	 * @var string
	 */
	protected $token;

	/**
	 * First Name
	 * 
	 * @NotNull
	 * @Column(name="nama_depan", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Nama Depan")
	 * @var string
	 */
	protected $firstName;

	/**
	 * Last Name
	 * 
	 * @NotNull
	 * @Column(name="nama_belakang", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Nama Belakang")
	 * @var string
	 */
	protected $lastName;

	/**
	 * Email
	 * 
	 * @NotNull
	 * @Column(name="email", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Email")
	 * @var string
	 */
	protected $email;

	/**
	 * Phone
	 * 
	 * @NotNull
	 * @Column(name="telepon", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Phone")
	 * @var string
	 */
	protected $phone;

	/**
	 * Blocked
	 * 
	 * @NotNull
	 * @Column(name="blokir", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Blocked")
	 * @var bool
	 */
	protected $blokir;

	/**
	 * Active
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Active")
	 * @var bool
	 */
	protected $active;

}