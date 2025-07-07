<?php

use MagicApp\UserAction;
use MagicObject\Request\InputGet;

require_once dirname(__DIR__) . "/inc.app/auth.php";

$inputGet = new InputGet();

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Papan Teken</title>
  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <link rel="stylesheet" href="../signpad/css/signature-pad.css">
</head>
<body>

  <div id="signature-pad" class="signature-pad">

    <?php
    if($inputGet->getUserAction() == UserAction::UPDATE)
    {
    ?>
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="description">Tanda Tangan Di Atas</div>

      <div class="signature-pad--actions">
        <div>
        <button type="button" class="button btn btn-primary signature-back" onclick="window.location='./'">Kembali</button>
        </div>
        <div>
        <button type="button" class="button btn btn-danger clear signature-create" data-action="clear">Hapus</button>
        <button type="button" class="button btn btn-success save signature-save" data-action="save-png" disabled>Simpan</button>
        </div>
      </div>
    </div>
    <script>
    let saveSignatureUrl = '../lib.mobile-tools/ajax-signature.php';
    </script>
    <script src="../signpad/js/signature-pad.js"></script>
    <script src="../signpad/js/app.js"></script>
    <script src="../signpad/js/resize.js"></script>
    <?php
    }
    else
    {
      ?>
      <style>
        .signature-pad--body {
          display: flex;
          justify-content: center;  /* Horizontal centering */
          align-items: center;      /* Vertical centering */
        }
        .signature-pad--body img {
          max-width: 100%;
          max-height: 100%;
          object-fit: contain;
        }
      </style>
      <div class="signature-pad--body">
        <?php
        $signaturePath2 = dirname(__DIR__)."/lib.signature/".$currentUser->getAdminId()."/signature.png";
        if(file_exists($signaturePath2))
        {
          $filetime = filemtime($signaturePath2);
          ?>
          <img src="../lib.signature/<?php echo $currentUser->getAdminId();?>/signature.png?_=<?php echo $filetime; ?>">
          <?php
        }
        ?>
      </div>
      <div class="signature-pad--footer">
      <div class="description">Tanda Tangan Di Atas</div>

      <div class="signature-pad--actions">
        <div>
        <button type="button" class="button btn btn-primary signature-back" onclick="window.location='./'">Kembali</button>
        <button type="button" class="button btn btn-success" onclick="window.location='?user_action=update'">Ubah</button>
        </div>
        <div>
        </div>
      </div>
    </div>
      <?php
    }
    ?>

  </div>
  
</body>
</html>
