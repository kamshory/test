<?php
use Sipro\Util\MenuUtil;

require_once dirname(dirname(dirname(__DIR__))) . "/inc.app/auth.php";

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();

$mainMenu = MenuUtil::getMainMenu($database, $appConfig, $currentUser);

$__pageTitle = '';
if(isset($currentModule) && $currentModule->getModuleName() != null && isset($mainMenu) && is_array($mainMenu->getModule()))
{
  if(isset($mainMenu->getModule()[$currentModule->getModuleName()]))
  {
    $__pageTitle = $mainMenu->getModule()[$currentModule->getModuleName()];
  }
  else if($currentModule->getModuleTitle())
  {
    $__pageTitle = $currentModule->getModuleTitle();
  }
}
if(!empty($__pageTitle))
{
  $__siteTitle = trim($appConfig->getSite()->getTitle().' - '.$__pageTitle, ' - ');
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
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/planetbiru/multi-select/multi-select.css">
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/planetbiru/multi-select/multi-select.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/color-modes.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/datetime-picker/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/PicoTagEditor.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/custom.js"></script>
    <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/ajax.min.js"></script>
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

          </div>
          <div class="sidebar-brand-narrow">

          </div>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
      </div>

      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item"><a class="nav-link" href="./">
            <svg class="nav-icon">
              <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
            </svg> Dashboard</a></li>
        <li class="nav-title"><?php echo $appLanguage->getMainMenu();?></li>

        <?php

        

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
            <li class="nav-item"><a class="nav-link" href="./">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link">&raquo;</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $currentModule->getRedirectUrl();?>"><?php echo $__pageTitle;?></a></li>
          </ul>

          <ul class="header-nav d-md-flex ms-auto">

            <?php
              require_once __DIR__ . "/header-menu-1.php";
            ?>

            
          </ul>
          <ul class="header-nav ms-auto ms-md-0">
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
              <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                <svg class="icon icon-lg">
                  <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-language"></use>
                </svg>
              </button>
              <div class="dropdown-menu-body">
                <?php
                $langId = $currentUser->getLanguageId();
                ?>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">

                  <li>
                    <button class="dropdown-item d-flex align-items-center<?php echo $langId == 'id' ? ' active' : '';?>" type="button" data-coreui-language-value="en" onclick="window.location='set-language.php?language_id=id'" style="padding-left: 5px;">
                    <svg class="icon icon-lg my-1 mx-2">
                      <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/flag.svg#cif-id"></use>
                    </svg>
                    &nbsp;Indonesia
                    </button>
                  </li>

                  <li>
                    <button class="dropdown-item d-flex align-items-center<?php echo $langId == 'en' ? ' active' : '';?>" type="button" data-coreui-language-value="en" onclick="window.location='set-language.php?language_id=en'" style="padding-left: 5px;">
                    <svg class="icon icon-lg my-1 mx-2">
                      <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/flag.svg#cif-gb"></use>
                    </svg>
                    &nbsp;English
                    </button>
                  </li>

                </ul>
              </div>
            </li>

            <li class="nav-item dropdown">
              <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                <svg class="icon icon-lg theme-icon-active">
                  <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                </svg>
              </button>

              <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                <li>
                  <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="light">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                    </svg><span data-coreui-i18n="light">Light</span>
                  </button>
                </li>

                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-moon"></use>
                    </svg><span data-coreui-i18n="dark">Dark</span>
                  </button>
                </li>

                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="auto">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
                    </svg>Auto
                  </button>
                </li>

              </ul>
            </li>
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <?php
                  $cdn = $appConfig->getCdn();
                  $cdnProfilePicture = $cdn->getProfilePicture();
                  $userId = $currentUser->getUserId();
                  $pp = $cdnProfilePicture."/user/".$userId."/128.jpg";
                  ?>
                  <div class="avatar avatar-md"><img class="avatar-img" src="<?php echo $pp;?>?hash=<?php echo str_replace(array("-", " ", ":"), "", $currentUser->getWaktuUbahFoto());?>" alt="<?php echo $appLanguage->getProfilePicture();?>"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end pt-0">
                  
                  <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="settings"><?php echo $appLanguage->getAccount();?></div>
                  <a class="dropdown-item" href="profil.php">
                      <svg class="icon me-2">
                          <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getSidebarMenuProfileSetting();?></span>
                  </a>

                  <a class="dropdown-item" href="foto-profil.php">
                    <svg class="icon me-2">
                        <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                    </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getProfilePicture();?></span>
                  </a>

                  <a class="dropdown-item" href="tanda-tangan.php">
                    <svg class="icon me-2">
                        <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                    </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getSignature();?></span>
                  </a>

                  <a class="dropdown-item" href="proyek.php">
                      <svg class="icon me-2">
                          <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                      </svg><span data-coreui-i18n="projects"><?php echo $appLanguage->getSidebarMenuProjects();?></span>
                  </a>
                  <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="logout.php">
                          <svg class="icon me-2">
                              <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                          </svg><span data-coreui-i18n="logout"><?php echo $appLanguage->getSidebarMenuLogout();?></span>
                      </a>
              </div>
          </li>
            
          </ul>
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




