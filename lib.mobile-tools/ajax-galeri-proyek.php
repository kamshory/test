<?php

use MagicObject\File\PicoUploadFile;
use MagicObject\Request\InputPost;
use MagicObject\Util\File\FileUtil;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\GaleriProyek;
use Sipro\ImageUtil;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$files = new PicoUploadFile();
$inputPost = new InputPost();

$bukuHarianId = $inputPost->getBukuHarianId();
$billOfQuantityId = $inputPost->getBillOfQuantityId();
$billOfQuantityProyekId = $inputPost->getBillOfQuantityProyekId();
$supervisorId = $currentAction->getSupervisorId();

if(isset($files->image) && $bukuHarianId)
{
    $bukuHarian = new BukuHarian(null, $database);

    try
    {

        $bukuHarian->findOneByBukuHarianIdAndSupervisorId($bukuHarianId, $supervisorId);
        $proyekId = $bukuHarian->getProyekId();
        $lokasiProyekId = $bukuHarian->getLokasiProyekId();
        
        $targetDir = $appConfig->getUpload()->getGallery()."/projects/$proyekId";
        if(!file_exists($targetDir))
        {
            mkdir($targetDir, 0755, true);
        }

        $waktuBuat = date('Y-m-d H:i:s');
        $waktuUbah = date('Y-m-d H:i:s');
        $ipBuat = $_SERVER['REMOTE_ADDR'];
        $ipUbah = $_SERVER['REMOTE_ADDR'];
        $aktif = true;

        $uploadedFiles = $files->image;
        foreach($uploadedFiles->getAll() as $fileItem)
        {
            $temporaryName = $fileItem->getTmpName();
            $name = $fileItem->getName();
            $originalName = addslashes($name);
            $size = $fileItem->getSize();

            $arr = explode(".", $originalName);
            $ext = end($arr);

            $uuid = $database->generateNewId();
            move_uploaded_file($temporaryName, $targetDir."/".$name);

            $info = getimagesize($targetDir."/".$name);
            $extra = "";
            $exifdata = null;
            if(stripos($info['mime'],'image')!==false)
            {
                $newname = $uuid;
                $md5Original = md5_file($targetDir."/".$name);
                if(stripos($info['mime'],'image/jpeg')!==false)
                {
                    $newname = $uuid.".jpg";
                    
                    // compress file
                    $exifdataraw = ImageUtil::readExifDataFile($targetDir."/".$name);
                    
                    if(isset($exifdataraw['IFD0']))
                    {
                        $maker = "";
                        $model = "";
                        if(isset($exifdataraw['IFD0']['Make']))
                        {
                            $maker = $exifdataraw['IFD0']['Make'];
                        }
                        if(isset($exifdataraw['IFD0']['Model']))
                        {
                            $model = $exifdataraw['IFD0']['Model'];
                        }
                    }
                    $exifdata = ImageUtil::packExifData($exifdataraw);                   
                    $extra = addslashes(json_encode($exifdata));
                    if($info[0] > 1000 || $info[1] > 1000)
                    {
                        ImageUtil::imageResizeMax($targetDir."/".$name, $targetDir."/".$newname, 1000, 1000, true, 80);
                        $info = getimagesize($targetDir."/".$newname);
                    }
                    else
                    {
                        @rename($targetDir."/".$name, $targetDir."/".$newname);
                    }
                }
                else if(stripos($info['mime'],'image/png')!==false)
                {
                    $newname = $uuid.".png";
                    rename($targetDir."/".$name, $targetDir."/".$newname);
                }
                else if(stripos($info['mime'],'image/gif')!==false)
                {
                    $newname = $uuid.".gif";
                    rename($targetDir."/".$name, $targetDir."/".$newname);
                }
                // create thumbnail
                $th100 = str_replace(array(".jpg", ".png", ".gif"), "_100.jpg", $newname);
                ImageUtil::createThumbImage($targetDir."/".$newname, $targetDir."/".$th100, 100, 100, true, 80);
                $type = addslashes($info['mime']);
                $width = $info[0];
                $height = $info[1];
                if($height==0) 
                {
                    $height = 1;
                }
                $width2 = round(100*$width/$height);

                $file = FileUtil::fixFilePath($targetDir."/".$newname);
                
                $md5 = md5_file($targetDir."/".$newname);
                $ip = addslashes($_SERVER['REMOTE_ADDR']);
                $basename = basename($newname);

                $galeryProyek = new GaleriProyek(null, $database);
                $galeryProyek->setProyekId($proyekId);
                $galeryProyek->setLokasiProyekId($lokasiProyekId);
                $galeryProyek->setBukuHarianId($bukuHarianId);
                $galeryProyek->setBillOfQuantityId($billOfQuantityId);
                $galeryProyek->setSupervisorId($supervisorId);
                $galeryProyek->setOriginalName($newname);
                $galeryProyek->setBasename($basename);
                $galeryProyek->setNama($fileItem->getName());
                $galeryProyek->setFile($newname);
                $galeryProyek->setMd5($md5);
                $galeryProyek->setWidth($width);
                $galeryProyek->setHeight($height);
                $galeryProyek->setWaktuBuat($waktuBuat);
                $galeryProyek->setWaktuUbah($waktuUbah);
                $galeryProyek->setIpBuat($ipBuat);
                $galeryProyek->setIpUbah($ipUbah);
                $galeryProyek->setAktif($aktif);

                $galeryProyek->insert();


                $galeriProyekId = $galeryProyek->getGaleryProyekId();

                if($exifdata !== null)
                {
                    $latitude = @$exifdata['latitude'];
                    $longitude = @$exifdata['longitude'];
                    
                    $lat = str_replace(array(";", "  "), array("", " "), $latitude);
                    $arr = explode(" ", $lat);
                    $latitude = ImageUtil::dmstoreal(@$arr[0], @$arr[1], @$arr[2]);
                    if(strtoupper(@$arr[3]) == 'S') 
                    {
                        $latitude = $latitude * -1;
                    }
                    
                    $lon = str_replace(array(";", "  "), array("", " "), $longitude);
                    $arr = explode(" ", $lon);
                    $longitude = ImageUtil::dmstoreal(@$arr[0], @$arr[1], @$arr[2]);
                    if(strtoupper(@$arr[3]) == 'W') 
                    {
                        $longitude = $longitude * -1;
                    }
                    
                    $altitude = @$exifdata['altitude'];

                    if($latitude != '-' && $longitude != '-' && $altitude != '-')
                    {
                        $galeryProyek->setLatitude($latitude);
                        $galeryProyek->setLongitude($longitude);
                        $galeryProyek->setAltitude($altitude);

                        $galeryProyek->update();
                    }
                    $jsonExif = json_encode($exifdata);

                    $galeryProyek->update($jsonExif);
                    $galeryProyek->setExif();
                }
            }
            else if(file_exists($targetDir."/".$name))
            {
                unlink($targetDir."/".$name);
            }
        }
    }
    catch(Exception $e)
    {
        // do nothing
    }
}