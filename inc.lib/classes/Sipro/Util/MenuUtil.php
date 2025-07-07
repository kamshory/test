<?php

namespace Sipro\Util;

use Exception;
use MagicApp\Entity\AppUser;
use MagicApp\Menu\MainMenu;
use MagicApp\PicoModule;
use MagicObject\Database\PicoDatabase;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\MagicObject;
use MagicObject\SecretObject;
use Sipro\Entity\App\AppModuleGroupImpl;
use Sipro\Entity\App\AppModuleImpl;
use Sipro\Entity\App\AppUserRoleImpl;
use Sipro\Entity\Data\MenuCache;
use Sipro\Entity\Data\UserLevel;

/**
 * Utility class for menu-related operations such as caching, retrieval, and update
 * based on user roles and access levels.
 */
class MenuUtil
{
    /**
     * Retrieve the main menu for the currently logged-in user.
     *
     * @param PicoDatabase $database The database connection.
     * @param SecretObject $appConfig Application configuration object.
     * @param AppUser $currentUser The currently logged-in user.
     * @return MainMenu The constructed main menu.
     */
    public static function getMainMenu($database, $appConfig, $currentUser)
    {
        // only load appUserRoles for menu
        $userLevelId = $currentUser->getUserLevelId();     
        $menus = self::getMenuFromCache($database, $appConfig, $currentUser, $userLevelId);
        return new MainMenu($menus, 'moduleGroupId', 'moduleGroup');
    }

    /**
     * Clear all cached menu data from the database.
     *
     * @param PicoDatabase $database The database connection.
     * @return void
     */
    public static function clearAllCache($database)
    {
        $alwaysTrue = PicoSpecification::alwaysTrue();
        $menuCache = new MenuCache(null, $database);
        try
        {
            $menuCache->where($alwaysTrue)->delete();
        }
        catch(Exception $e)
        {
            // Do nothing
        }
    }
    
    /**
     * Get menu data from cache, or fallback to database query if unavailable.
     *
     * @param PicoDatabase $database The database connection.
     * @param SecretObject $appConfig Application configuration object.
     * @param AppUser $currentUser The currently logged-in user.
     * @param mixed $userLevelId The user level ID.
     * @return AppModuleImpl[] Array of menu items.
     */
    public static function getMenuFromCache($database, $appConfig, $currentUser, $userLevelId)
    {
        $menuCache = new MenuCache(null, $database);
        try
        {
            $menuCache->findOneByUserLevelIdAndUserType($userLevelId, 'admin');
            $content = json_decode($menuCache->getContent(), true);
            $result = array();
            if(!empty($content))
            {
                foreach($content as $menuObj)
                {
                    $menu = new AppModuleImpl($menuObj);
                    $menu->setModuleGroup(new AppModuleGroupImpl($menuObj['module_group']));
                    $result[] = $menu;
                }
            }
        }
        catch(Exception $e)
        {
            $specialAccess = $currentUser->getUserLevel() != null && $currentUser->getUserLevel()->getSpecialAccess();
            $result = self::getMenuByUserLevelId($database, $appConfig, $userLevelId, $specialAccess);
        }
        return $result;
    }
    
    /**
     * Update and retrieve menu data in cache for a specific user level.
     *
     * @param PicoDatabase $database The database connection.
     * @param SecretObject $appConfig Application configuration object.
     * @param mixed $userLevelId The user level ID.
     * @param bool $specialAccess Whether special access modules should be included.
     * @return AppModuleImpl[] Array of menu items.
     */
    public static function updateMenuByUserLevelId($database, $appConfig, $userLevelId, $specialAccess)
    {
        $menuCache = new MenuCache(null, $database);
        try
        {
            $menuCache->deleteByUserLevelIdAndUserType($userLevelId, 'admin');
        }
        catch(Exception $e)
        {
            // do nothing
        }
        return self::getMenuByUserLevelId($database, $appConfig, $userLevelId, $specialAccess);
    }
    
    /**
     * Refresh menu cache for all user levels with active status.
     *
     * @param PicoDatabase $database The database connection.
     * @param SecretObject $appConfig Application configuration object.
     * @return void
     */
    public static function updateMenuForAllUserLevelId($database, $appConfig)
    {
        $userLevel = new UserLevel(null, $database);
        try
        {
            $pageData = $userLevel->findByAktif(true);
            foreach($pageData->getResult() as $ul)
            {
                MenuUtil::updateMenuByUserLevelId($database, $appConfig, $ul->getUserLevelId(), $ul->getIstimewa());   
            }
        }
        catch(Exception $e)
        {
            // do nothing
        }
    }
    
    /**
     * Generate menu based on user level and access, then optionally cache it.
     *
     * @param PicoDatabase $database The database connection.
     * @param SecretObject $appConfig Application configuration object.
     * @param mixed $userLevelId The user level ID to filter by.
     * @param bool $specialAccess Whether special access modules should be included.
     * @return AppModuleImpl[] Array of menu items.
     */
    public static function getMenuByUserLevelId($database, $appConfig, $userLevelId, $specialAccess)
    {
        $appUserRoleImpl = new AppUserRoleImpl(null, $database);
        $userRoleSpecs = PicoSpecification::getInstance()
        ->addAnd(new PicoPredicate('userLevelId', $userLevelId))
        ->addAnd(new PicoPredicate('active', true))
        ->addAnd(
            PicoSpecification::getInstance()
                ->addOr(new PicoPredicate('allowedList', true))
                ->addOr(new PicoPredicate('allowedDetail', true))
                ->addOr(new PicoPredicate('allowedCreate', true))
                ->addOr(new PicoPredicate('allowedUpdate', true))
                ->addOr(new PicoPredicate('allowedDelete', true))
                ->addOr(new PicoPredicate('allowedApprove', true))
                ->addOr(new PicoPredicate('allowedSortOrder', true))
                ->addOr(
                PicoSpecification::getInstance()
                    ->addAnd(new PicoPredicate('userLevel.specialAccess', true))
                    ->addAnd(new PicoPredicate('module.specialAccess', true))
                )
        )
        ;
        try
        {
            $appUserRoleResult = $appUserRoleImpl->findAll($userRoleSpecs);
            $appUserRoles = $appUserRoleResult->getResult();
        }
        catch(Exception $e)
        {
            $appUserRoles = new PicoPageData(array(), 0);
        }

        $menuSpecs = PicoSpecification::getInstance()
            ->addAnd(new PicoPredicate('moduleGroup.active', true))
            ->addAnd(new PicoPredicate('menu', true))
            ->addAnd(new PicoPredicate('active', true))
        ;

        if($appConfig->getRole()->getBypassRole())
        {
            // do nothing
        }
        else
        {
            if(!isset($currentModule))
            {
                $currentModule = new PicoModule($appConfig, $database);
            }         
            $allowedModules = $currentModule->getAllowedModules($appUserRoles);
            if($specialAccess)
            {
                $menuSpecs->addAnd(
                    PicoSpecification::getInstance()
                        ->addOr(PicoPredicate::getInstance()->in('moduleId', $allowedModules))
                        ->addOr(PicoPredicate::getInstance()->equals('specialAccess', true))
                );
            }
            else
            {
                $menuSpecs->addAnd(PicoPredicate::getInstance()->in('moduleId', $allowedModules));
            }
        }

        $menu = new AppModuleImpl(null, $database);
        $menuSortable = PicoSortable::getInstance()
            ->add(new PicoSort('moduleGroup.sortOrder', PicoSort::ORDER_TYPE_ASC))
            ->add(new PicoSort('sortOrder', PicoSort::ORDER_TYPE_ASC))
        ;
        $menuPageData = $menu->findAll($menuSpecs, null, $menuSortable, true);
        
        $result = $menuPageData->getResult();
        if(!empty($result))
        {
            foreach($result as $menu)
            {
                if($menu instanceof MagicObject)
                {
                    $menus[] = json_decode($menu."");
                }
            }
            $menuCache = new MenuCache(null, $database);
            $menuCache->setUserLevelId($userLevelId)->setUserType('admin')->setContent(json_encode($menus))->insert();
        }
        return $result;
    }
}