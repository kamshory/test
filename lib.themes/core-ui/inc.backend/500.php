<!DOCTYPE html><!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.0.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2024 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
--><?php 
$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
?>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="SIPRO PLN">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>SIPRO PLN</title>
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
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/style.css" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/examples.css" rel="stylesheet">
    <!-- We use those styles to style Carbon ads and CoreUI PRO banner, you should remove them in your application.-->
    <link href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>css/ads.css" rel="stylesheet">
    <script src="js/config.js"></script>
    <script src="js/color-modes.js"></script>
  </head>
  <body>
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="clearfix">
              <h1 class="float-start display-3 me-4 pt-3">500</h1>
              <h4 class="pt-3"><?php echo $appLanguage->getInternalServerError();?></h4>
              <p class="text-body-secondary"><?php echo $appLanguage->getInternalServerError();?></p>
              <p class="text-body-secondary"><button class="btn btn-info" type="button" onclick="window.location='./';"><?php echo $appLanguage->getGoHome();?></button></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>