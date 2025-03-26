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

class MenuUtil
{
    /**
     * Get main menu
     *
     * @param PicoDatabase $database
     * @param SecretObject $appConfig
     * @param AppUser $currentUser
     * @return MainMenu
     */
    public static function getMainMenu($database, $appConfig, $currentUser)
    {
        // only load appUserRoles for menu
        $userLevelId = $currentUser->getUserLevelId();     
        $menus = self::getMenuFromCache($database, $appConfig, $currentUser, $userLevelId);
        return new MainMenu($menus, 'moduleGroupId', 'moduleGroup');
    }
    
    /**
     * Get menu from cache
     *
     * @param PicoDatabase $database
     * @param SecretObject $appConfig
     * @param AppUser $currentUser
     * @param AppUserRoleImpl $appUserRoleImpl
     * @param mixed $userLevelId
     * @return AppModuleImpl[]
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
     * Update menu on cache for specified user level
     *
     * @param PicoDatabase $database
     * @param SecretObject $appConfig
     * @param mixed $userLevelId
     * @param boolean $specialAccess
     * @return AppModuleImpl[]
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
     * Update menu on cache for all user level
     *
     * @param PicoDatabase $database
     * @param SecretObject $appConfig
     * @return AppModuleImpl[]
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
     * Get menu from cache
     *
     * @param PicoDatabase $database
     * @param SecretObject $appConfig
     * @param mixed $userLevelId
     * @param boolean $specialAccess
     * @return AppModuleImpl[]
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