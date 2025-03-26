<?php

$baseAssetsUrl = $appConfig->getSite()->getBaseUrl();
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title><?php echo $appConfig->getSite()->getTitle();?></title>
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
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/config.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/color-modes.js"></script>
  </head>
  <body>
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <div class="card col-md-7 p-4 mb-0">
                <div class="card-body">
                  <form action="reset-password.php" method="post">
                    <h1><?php echo $appLanguage->getResetPassword();?></h1>
                    <p class="text-body-secondary"><?php echo $appLanguage->getResetYourAccountPassword();?></p>
                    <?php
                    if(isset($sessions->message))
                    {
                    ?>
                    <div class="alert alert-warning">
                        <?php
                        echo $sessions->message;
                        ?>
                    </div>
                    <?php
                    unset($sessions->message);
                    }
                    if(isset($sessions->resetPassworSuccess))
                    {
                      ?>
                    <div class="alert alert-info">
                        Link reset password sudah dikirim ke email Anda.
                    </div>
                    <?php
                    unset($sessions->resetPassworSuccess);
                    }
                    else
                    {
                    ?>
                    <div class="input-group mb-3"><span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-envelope-closed"></use>
                        </svg></span>
                        <input class="form-control" type="email" name="email" placeholder="<?php echo $appLanguage->getPlaceholderEmail();?>">
                    </div>
                    <div class="input-group mb-3"><span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/icons/svg/free.svg#cil-task"></use>
                        </svg></span>
                        <input type="text" class="form-control" name="answer" placeholder="<?php echo $appLanguage->getPlaceholderCaptcha();?>">
                    </div>
                    <div class="input-group mb-3 text-center">
                        <img src="<?php echo $baseAssetsUrl;?>lib.captcha/captcha-image.php" alt="" width="98" height="22">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-primary px-4" type="submit"><?php echo $appLanguage->getButtonResetPassword();?></button>
                        </div>
                        <div class="col-6 text-end">
                        <button class="btn btn-link px-0" type="button" onclick="window.location='login.php'"><?php echo $appLanguage->getLoginForm();?></button>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                  </form>
                </div>
              </div>
              <div class="card col-md-5 text-white bg-primary py-5">
                <div class="card-body text-center">
                  <div>
                    <h2><?php echo $appLanguage->getSignUp();?></h2>
                    <p><?php echo $appLanguage->getMessageRegisterAsSupervisor();?></p>
                    <button class="btn btn-outline-light mt-3" type="button" onclick="window.location='registrasi-supervisor.php'"><?php echo $appLanguage->getRegisterNow();?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>vendors/simplebar/js/simplebar.min.js"></script>
    <script>
      const header = document.querySelector('header.header');

      document.addEventListener('scroll', () => {
        if (header) {
          header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
      });
    </script>
    <script>
    </script>

  </body>
</html>