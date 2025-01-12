<?php

use Sipro\FaceDetector\FaceDetector;

require_once __DIR__ . "/inc.app/app.php";

$faceDetector = new FaceDetector();
$faceDetector->faceDetect(__DIR__."/images.jpg");
$faceDetector->cropFaceToJpeg(__DIR__."/images-out1.jpg", 200, 300, 300);
