<ul class="header-nav ms-auto ms-md-0">
    <li class="nav-item py-1">
        <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
    </li>
    <li class="nav-item dropdown">
        <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
            <svg class="icon icon-lg">
                <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-language"></use>
            </svg>
        </button>
        <div class="dropdown-menu-body">
            <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                <?php
                $langId = $currentLoggedInSupervisor->getLanguageId();
                ?>
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
                <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
            </svg>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
            <li>
                <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="light">
                    <svg class="icon icon-lg me-3">
                        <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                    </svg><span data-coreui-i18n="light">Light</span>
                </button>
            </li>
            <li>
                <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                    <svg class="icon icon-lg me-3">
                        <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-moon"></use>
                    </svg><span data-coreui-i18n="dark">Dark</span>
                </button>
            </li>
            <li>
                <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="auto">
                    <svg class="icon icon-lg me-3">
                        <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
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
            $supervisorId = $currentLoggedInSupervisor->getSupervisorId();
            $pp = $cdnProfilePicture."/supervisor/".$supervisorId."/128.jpg";
            ?>
            <div class="avatar avatar-md"><img class="avatar-img" src="<?php echo $pp;?>?hash=<?php echo str_replace(array("-", " ", ":"), "", $currentLoggedInSupervisor->getWaktuUbahFoto());?>" alt="<?php echo $appLanguage->getProfilePicture();?>"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-end pt-0">
            
            <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="settings"><?php echo $appLanguage->getAccount();?></div>
                <a class="dropdown-item" href="kehadiran.php">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getAttendance();?></span></a>
                <a class="dropdown-item" href="tanda-tangan.php">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getSignature();?></span></a>

                <a class="dropdown-item" href="#">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getSidebarMenuAccount();?></span></a>
                <a class="dropdown-item" href="foto-profil.php">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                </svg><span data-coreui-i18n="profile"><?php echo $appLanguage->getProfilePicture();?></span></a>
                <a class="dropdown-item" href="tanda-tangan.php">
                <a class="dropdown-item" href="#">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-settings"></use>
                </svg><span data-coreui-i18n="settings"><?php echo $appLanguage->getSidebarMenuSettings();?></span>
                </a>
                <a class="dropdown-item" href="#">
                <svg class="icon me-2">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                </svg><span data-coreui-i18n="projects"><?php echo $appLanguage->getSidebarMenuProjects();?></span><span class="badge badge-sm bg-primary-gradient ms-2">42</span>
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