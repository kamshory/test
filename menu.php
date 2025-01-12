<?php

use MagicApp\Menu\MainMenu;
use MagicApp\PicoModule;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\App\AppModuleImpl;

require_once __DIR__ . "/inc.app/auth.php";


        // only load appUserRoles for menu
        $userRoleSpecs = PicoSpecification::getInstance()
        ->addAnd(new PicoPredicate('userLevelId', $currentUser->getUserLevelId()))
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
                /*
                ->addOr(
                  PicoSpecification::getInstance()
                    ->addAnd(new PicoPredicate('userLevel.specialAccess', true))
                    ->addAnd(new PicoPredicate('module.specialAccess', true))
                )
                */
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
        

        $specification = PicoSpecification::getInstance()
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
          if($currentUser->hasValueUserLevel() && $currentUser->getUserLevel()->getSpecialAccess())
          {
            $specification->addAnd(
                PicoSpecification::getInstance()
                    ->addOr(PicoPredicate::getInstance()->in('moduleId', $allowedModules))
                    ->addOr(PicoPredicate::getInstance()->in('specialAccess', true))
            );
          }
          else
          {
            $specification->addAnd(PicoPredicate::getInstance()->in('moduleId', $allowedModules));
          }
        }


        $menu = new AppModuleImpl(null, $database);
        $sortable = PicoSortable::getInstance()
          ->add(new PicoSort('moduleGroup.sortOrder', PicoSort::ORDER_TYPE_ASC))
          ->add(new PicoSort('sortOrder', PicoSort::ORDER_TYPE_ASC))
        ;
        $pageData = $menu->findAll($specification, null, $sortable, true);
        $arr = array();

        $mainMenu = new MainMenu($pageData->getResult(), 'moduleGroupId', 'moduleGroup');

        foreach($mainMenu->getMenu() as $menuGroup)
        {
            $parent = $menuGroup['menuGroup'];
            $parentIcon = $parent->getIcon();
            echo '<li class="nav-group"><a class="nav-link nav-group-toggle" href="#">'."\r\n";
            echo '<svg class="nav-icon">'."\r\n";
            echo '<use xlink:href="'.$baseAssetsUrl.$themePath.'vendors/@coreui/icons/svg/free.svg#cil-'.$parentIcon.'"></use>'."\r\n";
            echo '</svg> '.$parent->getName().'</a>'."\r\n";
            echo '<ul class="nav-group-items compact">';
            foreach($menuGroup['menuItem'] as $menuItem)
            {
                $menuIcon = $menuItem->getIcon();
                echo '<li class="nav-item"><a class="nav-link" href="'.$menuItem->getUrl().'"><svg class="nav-icon">
                <use xlink:href="'.$baseAssetsUrl.$themePath.'vendors/@coreui/icons/svg/free.svg#cil-'.$menuIcon.'"></use>
              </svg> '.$menuItem->getName().'</a></li>'."\r\n";
            }
            echo '</ul>';
            echo '</li>';
        }