<?php

use MagicObject\File\PicoUploadFile;
use MagicObject\Request\InputPost;
use MagicObject\Util\File\FileUtil;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\GaleriProyek;
use Sipro\ImageUtil;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputPost = new InputPost();

$option = $inputPost->getOption();
$id = $inputPost->getId();

$supervisorId = $currentLoggedInSupervisor->getSupervisorId();

if($id && $option == 'delete')
{
	$galeryProyek = new GaleriProyek(null, $database);
	try
	{
		$galeryProyek->find($id);
		$proyekId = $galeryProyek->getProyekId();
		$targetDir = dirname(dirname(__FILE__))."/lib.gallery/projects/$proyekId";
		mkdir($targetDir, 0755, true);
		$path1 = $targetDir."/".$file;
		$path2 = str_replace('.jpg', '_100.jpg', $path1);
		if(file_exists($path1))
		{
			chmod($path1, 0755);
			unlink($path1);
		}
		if(file_exists($path2))
		{
			chmod($path1, 0755);
			unlink($path2);
		}
		$galeryProyek->delete();
	}
	catch(Exception $e)
	{
		// do nothing
	}
}
else
{
	$files = new PicoUploadFile();	
	$bukuHarianId = $inputPost->getBukuHarianId();
	$billOfQuantityId = $inputPost->getBillOfQuantityId();

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
			$aktif = 1;

			$uploadedFiles = $files->image;
			$thumbnailFiles = $files->thumbnail;
			$thumbnailFilesArr = $thumbnailFiles->getAll();
			$index = 0;
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

					$humbnailFile = $thumbnailFilesArr[$index];
					$tatgetTh = $targetDir."/".$th100;

					move_uploaded_file($humbnailFile->getTmpName(), $tatgetTh);					
					
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
					$galeryProyek->setAktif(true);

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
					?>    
					<div class="galeri-item">
						<span class="delete-control">
							<a href="#" data-galeri-proyek-id="<?php echo $galeriProyekId;?>"><span class="icon sign-remove"></span></a>
						</span>
						<img src="lib.gallery/projects/<?php echo $proyekId;?>/<?php echo $basename;?>">
					</div>
					<?php
				}
				else if(file_exists($targetDir."/".$name))
				{
					unlink($targetDir."/".$name);
				}

				$index++;
			}
		}
		catch(Exception $e)
		{
			// do nothing
		}
	}
}
