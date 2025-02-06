<?php

namespace Sipro;

use GdImage;
use MagicObject\Util\File\FileUtil;
use stdClass;

class ImageUtil
{
    /**
     * Resizes an image to fit within specified maximum dimensions while maintaining aspect ratio.
     *
     * This method takes an image from the source path, resizes it to fit within the specified
     * maximum width and height, and saves the resized image to the destination path. It also
     * supports different image types (JPEG, PNG, GIF) and allows for optional interlacing and
     * quality adjustments for JPEG images.
     *
     * @param string $source       The file path of the source image to resize.
     * @param string $destination  The file path where the resized image will be saved.
     * @param int    $maxwidth    The maximum width of the resized image.
     * @param int    $maxheight   The maximum height of the resized image.
     * @param bool   $interlace    Optional. If true, the output image will be interlaced (for GIFs).
     * @param string $type        Optional. The type of the output image ('jpeg' or 'png').
     * @param int    $quality     Optional. The quality of the output JPEG image (1 to 100).
     * 
     * @return string|bool Returns the destination file path if the resizing is successful,
     *                     or false if there was an error (e.g., if the source image does not exist
     *                     or if the image type is unsupported).
     */
    public static function imageResizeMax($source, $destination, $maxwidth, $maxheight, $interlace = false, $type = 'jpeg', $quality = 80)
    {
        $source = FileUtil::fixFilePath($source);
        $destination = FileUtil::fixFilePath($destination);

        $imageinfo = getimagesize($source);
        $image = new StdClass();
        $image->width  = $imageinfo[0];
        $image->height = $imageinfo[1];
        $image->type   = $imageinfo[2];
        switch ($image->type) {
            case IMAGETYPE_GIF:
                $im = ImageCreateFromGIF($source);
                break;
            case IMAGETYPE_JPEG:
                $im = ImageCreateFromJPEG($source);
                break;
            case IMAGETYPE_PNG:
                $im = ImageCreateFromPNG($source);
                break;
            default:
                return false;
        }
        if (!$im) {
            return false;
        }

        $currentwidth = $image->width;
        $currentheight = $image->height;
        // adapting image width
        if ($currentwidth > $maxwidth) {
            $tmpwidth = round($maxwidth);
            $tmpheight = round($currentheight * ($tmpwidth / $currentwidth));

            $currentwidth = $tmpwidth;
            $currentheight = $tmpheight;
        }
        // adapting image height
        if ($currentheight > $maxheight) {
            $tmpheight = round($maxheight);
            $tmpwidth = round($currentwidth * ($tmpheight / $currentheight));
            $currentwidth = $tmpwidth;
            $currentheight = $tmpheight;
        }
        $im2 = imagecreatetruecolor($currentwidth, $currentheight);
        $white = imagecolorallocate($im2, 255, 255, 255);
        imagefilledrectangle($im2, 0, 0, $currentwidth, $currentheight, $white);
        imagecopyresampled($im2, $im, 0, 0, 0, 0, $currentwidth, $currentheight, $image->width, $image->height);
        if ($interlace) {
            imageinterlace($im2, true);
        }
        if ($type == 'png') {
            imagepng($im2, $destination);
        } else {
            imagejpeg($im2, $destination, $quality);
        }
        return $destination;
    }

    /**
     * Reads EXIF data from an image file.
     *
     * This method retrieves the EXIF data from the specified image file using the 
     * `exif_read_data` function, and includes the original width and height of the image.
     *
     * @param string $filename The path to the image file from which to read EXIF data.
     * 
     * @return array|null Returns an associative array of EXIF data, including 
     *                   'original_width' and 'original_height', or null if the 
     *                   EXIF data could not be read or if the file does not contain 
     *                   any EXIF data.
     */
    public static function readExifDataFile($filename)
    {
        $is = getimagesize($filename);
        if (function_exists("exif_read_data")) {
            $exif = @exif_read_data($filename, 0, true, false);
        }
        if (!empty($exif)) {
            $exif['original_width'] = $is[0];
            $exif['original_height'] = $is[1];
        }

        return $exif;
    }

    /**
     * Packs EXIF data into an associative array with relevant information.
     *
     * This method processes the provided EXIF data, extracting relevant information
     * such as image dimensions, camera details, GPS coordinates, and capture time.
     *
     * @param array $exif The EXIF data array to process. It should contain keys like 
     *                    'original_width', 'original_height', and optionally 'GPS'.
     * 
     * @return array|null Returns an associative array with packed EXIF data including:
     *                   - 'width': Original width of the image
     *                   - 'height': Original height of the image
     *                   - 'time': Capture time of the image
     *                   - 'camera': Camera maker and model information
     *                   - 'latitude': Latitude in degrees, minutes, and seconds
     *                   - 'real_latitude': Decimal representation of latitude
     *                   - 'longitude': Longitude in degrees, minutes, and seconds
     *                   - 'real_longitude': Decimal representation of longitude
     *                   - 'altitude': Altitude value from GPS data
     *                   - 'altref': Altitude reference indicator
     *                   - 'capture_info': Additional capture information
     *                   Returns null if the input EXIF array is empty or not set.
     */
    public static function packExifData($exif)
    {
        if (count($exif)) {
            $width = isset($exif['original_width']) ? $exif['original_width'] : null;
            $height = isset($exif['original_height']) ? $exif['original_height'] : null;

            if (isset($exif['IFD0']['Make']) && isset($exif['IFD0']['Model']) && strpos($exif['IFD0']['Model'], $exif['IFD0']['Make']) === 0) {
                $exif['IFD0']['Make'] = '';
            }

            $camera = self::getCameraMaker($exif);
            $time_capture = self::getCaptureTime($exif, '-');

            $latd = $latm = $lats = $longd = $longm = $longs = 0;
            $latitude = "-";
            $longitude = "-";
            $altitude = "-";
            $altref = null;
            $reallat = null;
            $reallong = null;      

            if (isset($exif['GPS'])) {
                $gpsinfo = $exif['GPS'];

                $latd = isset($gpsinfo['GPSLatitude'][0]) ? self::getFromFraction($gpsinfo['GPSLatitude'][0]) : 0;
                $latm = isset($gpsinfo['GPSLatitude'][1]) ? self::getFromFraction($gpsinfo['GPSLatitude'][1]) : 0;
                $lats = isset($gpsinfo['GPSLatitude'][2]) ? self::getFromFraction($gpsinfo['GPSLatitude'][2]) : 0;
                
                $reallat = self::dmstoreal($latd, $latm, $lats);
                if (isset($gpsinfo['GPSLatitudeRef']) && stripos($gpsinfo['GPSLatitudeRef'], "S") !== false) {
                    $reallat *= -1;
                }
                $latitude = trim("$latd; $latm; $lats " . (isset($gpsinfo['GPSLatitudeRef']) ? $gpsinfo['GPSLatitudeRef'] : ''), " ; ");

                $longd = isset($gpsinfo['GPSLongitude'][0]) ? self::getFromFraction($gpsinfo['GPSLongitude'][0]) : 0;
                $longm = isset($gpsinfo['GPSLongitude'][1]) ? self::getFromFraction($gpsinfo['GPSLongitude'][1]) : 0;
                $longs = isset($gpsinfo['GPSLongitude'][2]) ? self::getFromFraction($gpsinfo['GPSLongitude'][2]) : 0;

                $reallong = self::dmstoreal($longd, $longm, $longs);
                if (isset($gpsinfo['GPSLongitudeRef']) && stripos($gpsinfo['GPSLongitudeRef'], "W") !== false) {
                    $reallong *= -1;
                }
                $longitude = trim("$longd; $longm; $longs " . (isset($gpsinfo['GPSLongitudeRef']) ? $gpsinfo['GPSLongitudeRef'] : ''), " ; ");

                $altitude = isset($gpsinfo['GPSAltitude'][0]) ? self::getFromFraction($gpsinfo['GPSAltitude'][0]) : 0;
                $altref = isset($gpsinfo['GPSAltitudeRef']) ? $gpsinfo['GPSAltitudeRef'] : '';
            }

            return array(
                'width' => $width,
                'height' => $height,
                'time' => $time_capture,
                'camera' => $camera,
                'latitude' => $latitude,
                'real_latitude' => $reallat,
                'longitude' => $longitude,
                'real_longitude' => $reallong,
                'altitude' => $altitude,
                'altref' => $altref,
                'capture_info' => self::getCaptureInfo($exif)
            );
        }
        return null;
    }

    /**
     * Retrieves the camera maker from EXIF data.
     *
     * This method checks the provided EXIF data for the camera maker information
     * and returns it. If the camera maker is not found, it returns a specified 
     * default value.
     *
     * @param array  $exif   The EXIF data array from which to retrieve the camera maker.
     * @param string $default Optional. The default value to return if the camera maker is not found.
     * 
     * @return string Returns the camera maker if found, or the specified default value.
     */
    public static function getCameraMaker($exif, $default = '')
    {
        if(isset($exif['IFD0']) && isset($exif['IFD0']['Make']))
        {
            return $exif['IFD0']['Make'];
        }
        else
        {
            return $default;
        }
    }

    /**
     * Retrieves the capture time from EXIF data.
     *
     * This method checks the provided EXIF data for the capture time information.
     * It first looks for the 'Datetime' key, and if not found, it checks for 
     * 'DateTimeOriginal'. If neither is available, it returns a specified default value.
     *
     * @param array  $exif   The EXIF data array from which to retrieve the capture time.
     * @param string $default Optional. The default value to return if the capture time is not found.
     * 
     * @return string Returns the capture time if found, or the specified default value.
     */
    public static function getCaptureTime($exif, $default = '')
    {
        if(isset($exif['IFD0']) && isset($exif['IFD0']['Datetime']))
        {
            return $exif['IFD0']['Datetime'];
        }
        else if(isset($exif['IFD0']) && isset($exif['IFD0']['DateTimeOriginal']))
        {
            return $exif['IFD0']['DateTimeOriginal'];
        }
        else
        {
            return $default;
        }
    }

    /**
     * Get value from fraction string
     * @param string $str
     * @return float
     */
    public static function getFromFraction($str)
    {
        $longs = 0;
        $longar = explode("/", $str);
        if (count($longar) > 1 && $longar[1]) {
            $longs = $longar[0] / $longar[1];
        }
        return $longs;
    }

    /**
     * Converts degrees, minutes, and seconds to a decimal degree representation.
     *
     * This method takes the degrees, minutes, and seconds of a geographical coordinate
     * and converts them into a decimal degree format. This is commonly used in 
     * geographical data processing.
     *
     * @param float $deg The degrees component of the coordinate.
     * @param float $min The minutes component of the coordinate.
     * @param float $sec The seconds component of the coordinate.
     * 
     * @return float Returns the decimal degree representation of the coordinate.
     */
    public static function dmstoreal($deg, $min, $sec)
    {
        return $deg + ((($min / 60) + ($sec)) / 3600);
    }

    /**
     * Converts a decimal degree value to degrees, minutes, and seconds (DMS).
     *
     * This method takes a decimal degree value and converts it into a 
     * representation of degrees, minutes, and seconds. It adjusts the 
     * hours for the local timezone by subtracting 7 hours.
     *
     * @param float $val The decimal degree value to convert.
     * 
     * @return array Returns an array containing the degrees, minutes, and seconds.
     */
    public static function real2dms($val)
    {
        $tm = $val * 3600;
        $tm = round($tm);
        $h = sprintf("%02d", date("H", $tm) - 7);
        if ($h < 0) {
            $h += 24;
        }
        $m = date("i", $tm);
        $s = date("s", $tm);
        return array($h, $m, $s);
    }

    /**
     * Extracts camera and capture information from EXIF data.
     *
     * This method retrieves various EXIF data fields related to the camera 
     * and image capture from the provided EXIF array. It returns an 
     * associative array of the retrieved information.
     *
     * @param array $exif The EXIF data array from which to extract camera information.
     * 
     * @return array|null Returns an associative array of capture information or null if no data is found.
     */
    public static function getCaptureInfo($exif)
    {
        /* 
        Copyright 2013 Kamshory Developer
        */
        $exifdata = array();
        $tmpdt = array();

        if (is_array($exif)) {
            $tmpdt['Camera_Maker'] = isset($exif['IFD0']['Make']) ? $exif['IFD0']['Make'] : null;
            $tmpdt['Camera_Model'] = isset($exif['IFD0']['Model']) ? $exif['IFD0']['Model'] : null;
            $tmpdt['Capture_Time'] = self::getCaptureTime($exif, '');
            $tmpdt['Aperture_F_Number'] = isset($exif['COMPUTED']['ApertureFNumber']) ? $exif['COMPUTED']['ApertureFNumber'] : null;
            $tmpdt['Orientation'] = isset($exif['IFD0']['Orientation']) ? $exif['IFD0']['Orientation'] : null;
            $tmpdt['X_Resolution'] = isset($exif['IFD0']['XResolution']) ? $exif['IFD0']['XResolution'] : null;
            $tmpdt['Y_Resolution'] = isset($exif['IFD0']['YResolution']) ? $exif['IFD0']['YResolution'] : null;
            $tmpdt['YCbCr_Positioning'] = isset($exif['IFD0']['YCbCrPositioning']) ? $exif['IFD0']['YCbCrPositioning'] : null;
            $tmpdt['Exposure_Time'] = isset($exif['EXIF']['ExposureTime']) ? $exif['EXIF']['ExposureTime'] : null;
            $tmpdt['F_Number'] = isset($exif['EXIF']['FNumber']) ? $exif['EXIF']['FNumber'] : null;
            $tmpdt['ISO_Speed_Ratings'] = isset($exif['EXIF']['ISOSpeedRatings']) ? $exif['EXIF']['ISOSpeedRatings'] : null;
            $tmpdt['Shutter_Speed_Value'] = isset($exif['EXIF']['ShutterSpeedValue']) ? $exif['EXIF']['ShutterSpeedValue'] : null;
            $tmpdt['Aperture_Value'] = isset($exif['EXIF']['ApertureValue']) ? $exif['EXIF']['ApertureValue'] : null;
            $tmpdt['Light_Source'] = isset($exif['EXIF']['LightSource']) ? $exif['EXIF']['LightSource'] : null;
            $tmpdt['Flash'] = isset($exif['EXIF']['Flash']) ? $exif['EXIF']['Flash'] : null;
            $tmpdt['Focal_Length'] = isset($exif['EXIF']['FocalLength']) ? $exif['EXIF']['FocalLength'] : null;
            $tmpdt['SubSec_Time_Original'] = isset($exif['EXIF']['SubSecTimeOriginal']) ? $exif['EXIF']['SubSecTimeOriginal'] : null;
            $tmpdt['SubSec_Time_Digitized'] = isset($exif['EXIF']['SubSecTimeDigitized']) ? $exif['EXIF']['SubSecTimeDigitized'] : null;
            $tmpdt['Flash_Pix_Version'] = isset($exif['EXIF']['FlashPixVersion']) ? $exif['EXIF']['FlashPixVersion'] : null;
            $tmpdt['Color_Space'] = isset($exif['EXIF']['ColorSpace']) ? $exif['EXIF']['ColorSpace'] : null;
            $tmpdt['Custom_Rendered'] = isset($exif['EXIF']['CustomRendered']) ? $exif['EXIF']['CustomRendered'] : null;
            $tmpdt['Exposure_Mode'] = isset($exif['EXIF']['ExposureMode']) ? $exif['EXIF']['ExposureMode'] : null;
            $tmpdt['White_Balance'] = isset($exif['EXIF']['WhiteBalance']) ? $exif['EXIF']['WhiteBalance'] : null;
            $tmpdt['Digital_Zoom_Ratio'] = isset($exif['EXIF']['DigitalZoomRatio']) ? $exif['EXIF']['DigitalZoomRatio'] : null;
            $tmpdt['Scene_Capture_Type'] = isset($exif['EXIF']['SceneCaptureType']) ? $exif['EXIF']['SceneCaptureType'] : null;
            $tmpdt['Gain_Control'] = isset($exif['EXIF']['GainControl']) ? $exif['EXIF']['GainControl'] : null;

            foreach ($tmpdt as $key => $val) {
                if ($val !== null && $val !== "") {
                    $exifdata[$key] = $val;
                }
            }

            return $exifdata;
        }
        
        return null;
    }

    /**
     * Flips an image horizontally.
     *
     * @param GdImage $im The image resource to be flipped.
     * @return GdImage The flipped image resource.
     */
    public static function flip_horizontal($im)
    {
        $wid = imagesx($im);
        $hei = imagesy($im);
        $im2 = imagecreatetruecolor($wid, $hei);
        for ($i = 0; $i < $wid; $i++) {
            for ($j = 0; $j < $hei; $j++) {
                $ref = imagecolorat($im, $i, $j);
                imagesetpixel($im2, ($wid - $i - 1), $j, $ref);
            }
        }
        return $im2;
    }

    /**
     * Flips an image vertically.
     *
     * @param GdImage $im The image resource to be flipped.
     * @return GdImage The flipped image resource.
     */
    public static function flip_vertical($im)
    {
        $wid = imagesx($im);
        $hei = imagesy($im);
        $im2 = imagecreatetruecolor($wid, $hei);

        for ($i = 0; $i < $wid; $i++) {
            for ($j = 0; $j < $hei; $j++) {
                $ref = imagecolorat($im, $i, $j);
                imagesetpixel($im2, $i, ($hei - $j - 1), $ref);
            }
        }
        return $im2;
    }

    /**
     * Creates a thumbnail image from the original file.
     *
     * @param string $originalfile The path to the original image file.
     * @param string $destination The path where the thumbnail image will be saved.
     * @param int $dwidth The desired width of the thumbnail.
     * @param int $dheight The desired height of the thumbnail.
     * @param bool $interlace Optional. Whether to enable interlacing (default is false).
     * @param int $quality Optional. The quality of the JPEG image (default is 80).
     * @return mixed Returns 1 on success, 0 if the image creation failed, or false on error.
     */
    public static function createThumbImage($originalfile, $destination, $dwidth, $dheight, $interlace = false, $quality = 80)
    {
        $image = new stdClass();
        $imageinfo = getimagesize($originalfile);
        if (empty($imageinfo)) {
            if (file_exists($originalfile)) {
                unlink($originalfile);
            }
            return false;
        }
        $image->width  = $imageinfo[0];
        $image->height = $imageinfo[1];
        $image->type   = $imageinfo[2];

        switch ($image->type) {
            case IMAGETYPE_GIF:
                $im = @ImageCreateFromGIF($originalfile);
                break;
            case IMAGETYPE_JPEG:
                $im = @ImageCreateFromJPEG($originalfile);
                break;
            case IMAGETYPE_PNG:
                $im = @ImageCreateFromPNG($originalfile);
                break;
            default:
                unlink($originalfile);
                return false;
        }
        
        $im1 = imagecreatetruecolor($dwidth, $dheight);  
        
        $mindim = min($image->width, $image->height);
        $xstart = 0;
        $ystart = 0;
        if ($image->width > $image->height) {
            $xstart = floor((max($image->width, $image->height) - min($image->width, $image->height)) / 2.0);
        } else {
            $ystart = floor((max($image->width, $image->height) - min($image->width, $image->height)) / 2.0);
        }
        imagecopyresampled($im1, $im, 0, 0, $xstart, $ystart, $dwidth, $dheight, $mindim, $mindim);
        if ($interlace) {
            imageinterlace($im1, true);
        }

        if (function_exists('ImageJpeg')) {
            @touch($destination);  // Helps in Safe mode
            if (
                ImageJpeg($im1, $destination, $quality)
            ) {
                @chmod($destination, 0666);
                return 1;
            }
        } else {
            error_log('PHP has not been configured to support JPEG images.  Please correct this.');
        }
        return 0;
    }
}
