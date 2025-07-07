<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * UserApv is entity of table user_apv. You can join this entity to other entity using annotation JoinColumn. 
 * Don't forget to add "use" statement if the entity is outside the namespace.
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="user_apv")
 */
class UserApv extends MagicObject
{
	/**
	 * User Apv ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.UUID)
	 * @Column(name="user_apv_id", type="varchar(40)", length=40, nullable=false)
	 * @DefaultColumn(value="NULL")
	 * @Label(content="User Apv ID")
	 * @var string
	 */
	protected $userApvId;

	/**
	 * User ID
	 * 
	 * @NotNull
	 * @Column(name="user_id", type="bigint(20)", length=20, nullable=false)
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
	 * @Column(name="first_name", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Nama Depan")
	 * @var string
	 */
	protected $firstName;

	/**
	 * Last Name
	 * 
	 * @NotNull
	 * @Column(name="last_name", type="varchar(40)", length=40, nullable=false)
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
	 * Website
	 * 
	 * @NotNull
	 * @Column(name="website", type="varchar(100)", length=100, nullable=false)
	 * @Label(content="Website")
	 * @var string
	 */
	protected $website;

	/**
	 * Phone
	 * 
	 * @NotNull
	 * @Column(name="phone", type="varchar(50)", length=50, nullable=false)
	 * @Label(content="Phone")
	 * @var string
	 */
	protected $phone;

	/**
	 * Gender ID
	 * 
	 * @NotNull
	 * @Column(name="gender_id", type="tinyint(4)", length=4, nullable=false)
	 * @Label(content="Jenis Kelamin")
	 * @var int
	 */
	protected $genderId;

	/**
	 * User Level ID
	 * 
	 * @NotNull
	 * @Column(name="user_level_id", type="bigint(20)", length=20, nullable=false)
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
	 * Admin Tsk
	 * 
	 * @Column(name="admin_tsk", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Admin TSK")
	 * @var bool
	 */
	protected $adminTsk;

	/**
	 * Ktsk ID
	 * 
	 * @Column(name="ktsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="KTSK ID")
	 * @var int
	 */
	protected $ktskId;

	/**
	 * Ktsk
	 * 
	 * @JoinColumn(name="ktsk_id", referenceColumnName="ktsk_id")
	 * @Label(content="KTSK")
	 * @var KtskMin
	 */
	protected $ktsk;

	/**
	 * Branch
	 * 
	 * @NotNull
	 * @Column(name="branch", type="varchar(255)", length=255, nullable=false)
	 * @Label(content="Branch")
	 * @var string
	 */
	protected $branch;

	/**
	 * Selected Branch
	 * 
	 * @NotNull
	 * @Column(name="selected_branch", type="varchar(20)", length=20, nullable=false)
	 * @Label(content="Selected Branch")
	 * @var string
	 */
	protected $selectedBranch;

	/**
	 * Lang ID
	 * 
	 * @NotNull
	 * @Column(name="lang_id", type="varchar(5)", length=5, nullable=false)
	 * @Label(content="Lang ID")
	 * @var string
	 */
	protected $languageId;

	/**
	 * Theme ID
	 * 
	 * @NotNull
	 * @Column(name="theme_id", type="varchar(50)", length=50, defaultValue="default", nullable=false)
	 * @DefaultColumn(value="default")
	 * @Label(content="Theme ID")
	 * @var string
	 */
	protected $themeId;

	/**
	 * Blocked
	 * 
	 * @NotNull
	 * @Column(name="blocked", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Blocked")
	 * @var bool
	 */
	protected $blocked;

	/**
	 * Active
	 * 
	 * @NotNull
	 * @Column(name="active", type="tinyint(1)", length=1, nullable=false)
	 * @Label(content="Active")
	 * @var bool
	 */
	protected $active;

	/**
	 * Last Check
	 * 
	 * @NotNull
	 * @Column(name="last_check", type="timestamp", length=19, nullable=false)
	 * @Label(content="Last Check")
	 * @var string
	 */
	protected $lastCheck;

	/**
	 * Last Login
	 * 
	 * @NotNull
	 * @Column(name="last_login", type="timestamp", length=19, nullable=false)
	 * @Label(content="Last Login")
	 * @var string
	 */
	protected $lastLogin;

	/**
	 * Last IP
	 * 
	 * @NotNull
	 * @Column(name="last_ip", type="varchar(40)", length=40, nullable=false)
	 * @Label(content="Last IP")
	 * @var string
	 */
	protected $lastIp;

	/**
	 * Question
	 * 
	 * @NotNull
	 * @Column(name="question", type="varchar(200)", length=200, nullable=false)
	 * @Label(content="Question")
	 * @var string
	 */
	protected $question;

	/**
	 * Answer
	 * 
	 * @NotNull
	 * @Column(name="answer", type="varchar(200)", length=200, nullable=false)
	 * @Label(content="Answer")
	 * @var string
	 */
	protected $answer;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, defaultValue="NULL", nullable=true)
	 * @DefaultColumn(value="NULL")
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Aktif
	 * 
	 * @Column(name="aktif", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

	/**
	 * Approval Status
	 * 
	 * @Column(name="approval_status", type="int(4)", length=4, nullable=true)
	 * @Label(content="Approval Status")
	 * @var int
	 */
	protected $approvalStatus;

}