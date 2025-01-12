<?php

// This script is generated automatically by MagicAppBuilder
// Visit https://github.com/Planetbiru/MagicAppBuilder

use MagicObject\Request\InputGet;
use MagicObject\Request\InputPost;
use MagicApp\AppEntityLanguage;
use MagicApp\PicoModule;
use MagicApp\AppUserPermission;
use Sipro\Entity\Data\HariLibur;
use Sipro\AppIncludeImpl;
use Sipro\Util\MenuUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();
$inputPost = new InputPost();

$currentModule = new PicoModule($appConfig, $database, $appModule, "/admin", "hari-libur", "Hari Libur");
$userPermission = new AppUserPermission($appConfig, $database, $appUserRole, $currentModule, $currentUser);
$appInclude = new AppIncludeImpl($appConfig, $currentModule);

if(!$userPermission->allowedAccess($inputGet, $inputPost))
{
	require_once $appInclude->appForbiddenPage(__DIR__);
	exit();
}
$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
$theme = "core-ui";
$pathHeader = dirname(__DIR__)."/lib.themes/$theme/inc.backend/header.php";
$themePath = "lib.themes/$theme/";

$appEntityLanguage = new AppEntityLanguage(new HariLibur(), $appConfig, $currentUser->getLanguageId());
$mainMenu = MenuUtil::getMainMenu($database, $appConfig, $currentUser, $appUserRoleImpl);

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
