<?php

use MagicObject\MagicObject;

require_once dirname(dirname(dirname(__DIR__))) . "/inc.app/auth-supervisor.php";

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();

if(isset($currentModule) && $currentModule->getModuleTitle() != null)
{
  $__siteTitle = trim($appConfig->getSite()->getTitle().' - '.$currentModule->getModuleTitle(), ' - ');
}
else
{
  $__siteTitle = $appConfig->getSite()->getTitle();
}

?><!DOCTYPE html><html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Planetbiru/MagicAppBuilder">
    <meta name="keyword" content="Planetbiru/MagicAppBuilder">
    <title><?php echo $__siteTitle;?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/color-modes.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/datetime-picker/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/ajax.js"></script>
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/planetbiru/multi-select/multi-select.css">
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/planetbiru/multi-select/multi-select.js"></script>
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/vendors/simplebar.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/style.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/custom.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/datetime-picker/bootstrap-datetimepicker.min.css">
    
  </head>
  <body>
    <div class="sidebar sidebar-fixed border-end" id="sidebar">
      <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
          <div class="sidebar-brand-full">
            <!-- sidbar brand full -->
          </div>
          <div class="sidebar-brand-narrow">
            <!-- sidbar brand narrow -->
          </div>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
      </div>

      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">
            <svg class="nav-icon">
              <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
            </svg> <?php echo $appLanguage->getDashboard();?></a></li>
        <li class="nav-item"><a class="nav-link" href="./">
            <svg class="nav-icon">
              <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-home"></use>
            </svg> <?php echo $appLanguage->getAttendance();?></a></li>
        <li class="nav-title"><?php echo $appLanguage->getMainMenu();?></li>

        <?php

        $mainMenu = new MagicObject();
        $menuPath = dirname(dirname(dirname(__DIR__)))."/inc.app/supervisor.yml";
        $mainMenu->loadYamlFile($menuPath, false, true, true);

        foreach($mainMenu->getMenu() as $parent)
        {
            $parentIcon = $parent->getIcon();
            echo '<li class="nav-group"><a class="nav-link nav-group-toggle" href="#">'."\r\n";
            echo '<svg class="nav-icon">'."\r\n";
            echo '<use xlink:href="'.$baseAssetsUrl.$themePath.'vendors/@coreui/icons/svg/free.svg#cil-'.$parentIcon.'"></use>'."\r\n";
            echo '</svg> '.$parent->getName().'</a>'."\r\n";
            echo '<ul class="nav-group-items compact">';
            $menuItems = $parent->getMenuItem();

            if(isset($menuItems) && is_array($menuItems) && !empty($menuItems))
            {
              foreach($menuItems as $menuItem)
              {
                  $menuIcon = $menuItem->getIcon();
                  echo '<li class="nav-item"><a class="nav-link" href="'.$menuItem->getUrl().'"><svg class="nav-icon">
                  <use xlink:href="'.$baseAssetsUrl.$themePath.'vendors/@coreui/icons/svg/free.svg#cil-'.$menuIcon.'"></use>
                </svg> '.$menuItem->getName().'</a></li>'."\r\n";
              }
            }
            echo '</ul>';
            echo '</li>';
        }

        ?>
      </ul>

      <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
      </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
      <header class="header header-sticky p-0 mb-3">
        <div class="container-fluid border-bottom px-3">
          <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
              <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
            </svg>
          </button>
          <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="./"><?php echo $appLanguage->getHome();?></a></li>
            <li class="nav-item"><a class="nav-link">&raquo;</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo basename($_SERVER['PHP_SELF']);?>"><?php echo $currentModule->getModuleTitle();?></a></li>
          </ul>

          <?php
            require_once __DIR__ . "/supervisor-header-menu-1.php";
          ?>

          <?php
            require_once __DIR__ . "/supervisor-header-menu-2.php";
          ?>


        </div>
      </header>

      <div class="pb-container">
        <div class="progress progress-infinite">
          <div class="progress-loading" >
          </div>
        </div>
      </div>
      <div class="body flex-grow-1">
        <div class="px-3">




