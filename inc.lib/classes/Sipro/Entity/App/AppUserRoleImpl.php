<?php

namespace Sipro\Entity\App;

use MagicApp\Entity\AppUserRole;

/**
 * AppUserRoleImpl 
 * 
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="hak_akses")
 */
class AppUserRoleImpl extends AppUserRole
{
    /**
     * User Role ID
     * 
     * @Id
     * @GeneratedValue(strategy=GenerationType.UUID)
     * @Column(name="hak_akses_id", type="varchar(40)", length=40, nullable=false)
     * @DefaultColumn(value="NULL")
     * @Label(content="User ID")
     * @var string
     */
    protected $userRoleld;

    /**
     * User Level ID
     * 
     * @NotNull
     * @Column(name="user_level_id", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
     * @Label(content="User Level ID")
     * @var string
     */
    protected $userLevelId;

    /**
     * User Level
     * 
     * @NotNull
     * @JoinColumn(name="user_level_id", referenceColumnName="user_level_id")
     * @Label(content="User Level")
     * @var AppUserLevelImpl
     */
    protected $userLevel;

    /**
     * Module ID
     * 
     * @Column(name="modul_id", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
     * @Label(content="Module ID")
     * @var string
     */
    protected $moduleId;

    /**
     * Module Name
     * 
     * @Column(name="kode_modul", type="varchar(40)", length=40, defaultValue="NULL", nullable=true)
     * @Label(content="Module Name")
     * @var string
     */
    protected $moduleName;

    /**
     * Module
     * 
     * @NotNull
     * @JoinColumn(name="modul_id", referenceColumnName="modul_id")
     * @Label(content="Module")
     * @var AppModuleImpl
     */
    protected $module;

    /**
     * Allowed show list
     *
     * @Column(name="allowed_list", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed List")
     * @var bool
     */
    protected $allowedList;

    /**
     * Allowed show detail
     *
     * @Column(name="allowed_detail", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Detail")
     * @var bool
     */
    protected $allowedDetail;

    /**
     * Allowed create
     *
     * @Column(name="allowed_create", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Create")
     * @var bool
     */
    protected $allowedCreate;

    /**
     * Allowed update
     *
     * @Column(name="allowed_update", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Update")
     * @var bool
     */
    protected $allowedUpdate;

    /**
     * Allowed delete
     *
     * @Column(name="allowed_delete", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Delete")
     * @var bool
     */
    protected $allowedDelete;

    /**
     * Allowed approve/reject
     *
     * @Column(name="allowed_approve", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Approve")
     * @var bool
     */
    protected $allowedApprove;

    /**
     * Allowed short order
     *
     * @Column(name="allowed_sort_order", type="tinyint(1)", length=1, defaultValue="0", nullable=true)
     * @Label(content="Allowed Sort Order")
     * @var bool
     */
    protected $allowedSortOrder;
	
	/**
	 * Allowed DownExportload
	 * 
	 * @Column(name="allowed_export", type="tinyint(1)", length=1, nullable=true)
	 * @Label(content="Allowed Export")
	 * @var bool
	 */
	protected $allowedExport;

    /**
     * Active
     *
     * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=true)
     * @Label(content="Active")
     * @var bool
     */
    protected $active;
}
