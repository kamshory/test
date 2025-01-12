<?php
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//
// @Author Karthik Tharavaad
//         karthik_tharavaad@yahoo.com
// @Contributor Maurice Svay
//              maurice@svay.Com

namespace Sipro\FaceDetector;

use GdImage;

class FaceDetector
{
    /**
     * @var array $detectionData Array containing face detection data required for the detection process.
     */
    protected $detectionData;

    /**
     * @var GdImage $canvas Image resource currently being processed for face detection.
     */
    protected $canvas;

    /**
     * @var array|null $face Information about the detected face, including position and size.
     */
    protected $face;

    /**
     * @var GdImage $reducedCanvas Resized image resource to improve detection efficiency.
     */
    private $reducedCanvas;

    /**
     * Creates a face detector with the given configuration.
     *
     * Configuration can be either passed as an array or as
     * a filepath to a serialized array file-dump.
     *
     * @param string|array $detectionData The path to detection data file or an array of detection data.
     *
     * @throws NoFaceException If detection data cannot be loaded.
     */
    public function __construct($detectionData = 'detection.dat')
    {
        if (is_array($detectionData)) {
            $this->detectionData = $detectionData;
            return;
        }
    
        if (!is_file($detectionData)) {
            // fallback to same file in this class's directory
            $detectionData = dirname(__FILE__) . DIRECTORY_SEPARATOR . $detectionData;
            
            if (!is_file($detectionData)) {
                throw new NoFaceException("Couldn't load detection data");
            }
        }
        
        $this->detectionData = unserialize(file_get_contents($detectionData));
    }

    /**
     * Detects a face in the provided image.
     *
     * @param GdImage|string $file An image resource, file path, or image string to analyze.
     *
     * @return bool Returns true if a face is detected; otherwise false.
     *
     * @throws NoFileException If the image cannot be loaded.
     */
    public function faceDetect($file)
    {
        if (is_resource($file)) {
            $this->canvas = $file;
        } elseif (is_file($file)) {
            $this->canvas = imagecreatefromjpeg($file);
        } elseif (is_string($file)) {
            $this->canvas = imagecreatefromstring($file); 
        } else {
            throw new NoFileException("Can not load $file");
        }

        $imWidth = imagesx($this->canvas);
        $imHeight = imagesy($this->canvas);

        //Resample before detection?
        $diff_width = 320 - $imWidth;
        $diff_height = 240 - $imHeight;
        if ($diff_width > $diff_height) {
            $ratio = $imWidth / 320;
        } else {
            $ratio = $imHeight / 240;
        }

        if ($ratio != 0) {
            $this->reducedCanvas = imagecreatetruecolor($imWidth / $ratio, $imHeight / $ratio);

            imagecopyresampled(
                $this->reducedCanvas,
                $this->canvas,
                0,
                0,
                0,
                0,
                $imWidth / $ratio,
                $imHeight / $ratio,
                $imWidth,
                $imHeight
            );

            $stats = $this->getImgStats($this->reducedCanvas);

            $this->face = $this->doDetectGreedyBigToSmall(
                $stats['ii'],
                $stats['ii2'],
                $stats['width'],
                $stats['height']
            );

            if ($this->face['w'] > 0) {
                $this->face['x'] *= $ratio;
                $this->face['y'] *= $ratio;
                $this->face['w'] *= $ratio;
            }
        } else {
            $stats = $this->getImgStats($this->canvas);

            $this->face = $this->doDetectGreedyBigToSmall(
                $stats['ii'],
                $stats['ii2'],
                $stats['width'],
                $stats['height']
            );
        }
        return ($this->face['w'] > 0);
    }

    /**
     * Outputs the detected face as a JPEG image.
     *
     * @return void
     */
    public function toJpeg()
    {
        $color = imagecolorallocate($this->canvas, 255, 0, 0); //red

        imagerectangle(
            $this->canvas,
            $this->face['x'],
            $this->face['y'],
            $this->face['x']+$this->face['w'],
            $this->face['y']+ $this->face['w'],
            $color
        );

        header('Content-type: image/jpeg');
        imagejpeg($this->canvas);
    }

    /**
     * Crops the detected face from the photo.
     * This method should be called after the `faceDetect` function.
     * If a file name is provided, the cropped face will be saved to that file;
     * otherwise, it will be output directly to standard output.
     *
     * @param string|null $outFileName Optional. File name to store the cropped face. If null, the face will be printed to output.
     * @param int $margin Optional. Margin around the detected face (in pixels).
     * @param int $width Optional. Desired width of the cropped image (in pixels).
     * @param int $height Optional. Desired height of the cropped image (in pixels).
     *
     * @throws NoFaceException If no face has been detected.
     */
    public function cropFaceToJpeg($outFileName = null, $margin = 50, $width = 200, $height = 200)
    {
        if (empty($this->face)) {
            throw new NoFaceException('No face detected');
        }

        // Menghitung ukuran crop baru
        $originalWidth = $this->face['w'] + 2 * $margin;
        $originalHeight = $this->face['w'] + 2 * $margin;

        // Menghitung rasio
        $ratioWidth = $width;
        $ratioHeight = $height;

        // Menghitung ukuran baru berdasarkan rasio
        if ($ratioWidth > $ratioHeight) {
            $newWidth = $originalWidth;
            $newHeight = (int)($newWidth * $ratioHeight / $ratioWidth);
        } else {
            $newHeight = $originalHeight;
            $newWidth = (int)($newHeight * $ratioWidth / $ratioHeight);
        }

        // Menghitung posisi tengah untuk crop
        $cropX = max(0, $this->face['x'] + $this->face['w'] / 2 - $newWidth / 2);
        $cropY = max(0, $this->face['y'] + $this->face['w'] / 2 - $newHeight / 2);

        // Memastikan crop tidak keluar dari batas gambar asli
        $cropWidth = min($newWidth, imagesx($this->canvas) - $cropX);
        $cropHeight = min($newHeight, imagesy($this->canvas) - $cropY);

        $canvas = imagecreatetruecolor($ratioWidth, $ratioHeight);
        imagecopyresampled($canvas, $this->canvas, 0, 0, $cropX, $cropY, $ratioWidth, $ratioHeight, $cropWidth, $cropHeight);

        if ($outFileName === null) {
            header('Content-type: image/jpeg');
        }

        imagejpeg($canvas, $outFileName);
    }

    /**
     * Draws L-shaped frames around the detected face on the original image.
     * This method should be called after the `faceDetect` function.
     * If a file name is provided, the image with frames will be saved to that file;
     * otherwise, the image will be output directly to standard output.
     *
     * @param string|null $outFileName Optional. File name to store the image with frames. If null, the image will be printed to output.
     * @param int $margin Optional. Margin around the detected face (in pixels).
     * @param int $width Optional. Desired width of the frame (in pixels).
     * @param int $height Optional. Desired height of the frame (in pixels).
     * @param int $frameWidth Optional. Width of the L-shaped frame (in pixels).
     * @param int $frameThickness Optional. The thickness of the frame in pixels.
     *
     * @throws NoFaceException If no face has been detected.
     */
    public function drawFaceFrames($outFileName = null, $margin = 50, $width = 200, $height = 200, $frameWidth = 20, $frameThickness = 1)
    {
        if (empty($this->face)) {
            throw new NoFaceException('No face detected');
        }

        $color = imagecolorallocate($this->canvas, 255, 0, 0); // red

        // Calculate the position and size of the frame
        if($width < $height)
        {
            $x = max(0, $this->face['x'] - $margin / 2);
            $y = max(0, $this->face['y'] - $margin);
        }
        else if($width > $height)
        {
            $x = max(0, $this->face['x'] - $margin);
            $y = max(0, $this->face['y'] - $margin / 2);
        }
        else
        {
            $x = max(0, $this->face['x'] - $margin);
            $y = max(0, $this->face['y'] - $margin);
        }
        $w = $this->face['w'] + 2 * $margin;
        $h = $this->face['w'] + 2 * $margin;


        // Adjust width and height based on the provided ratio
        $aspectRatio = $width / $height;

        if ($aspectRatio > 1) {
            $newWidth = $w;
            $newHeight = (int)($newWidth / $aspectRatio);
        } else {
            $newHeight = $h;
            $newWidth = (int)($newHeight * $aspectRatio);
        }

        // Draw L-shaped frames at each corner with specified thickness
        for ($i = 0; $i < $frameThickness; $i++) {
            // Top left
            imageline($this->canvas, $x, $y + $i, $x + $frameWidth, $y + $i, $color); // Top horizontal
            imageline($this->canvas, $x + $i, $y, $x + $i, $y + $frameWidth, $color); // Left vertical

            // Top right
            imageline($this->canvas, $x + $newWidth - $frameWidth, $y + $i, $x + $newWidth, $y + $i, $color); // Top horizontal
            imageline($this->canvas, $x + $newWidth - $i, $y, $x + $newWidth - $i, $y + $frameWidth, $color); // Right vertical

            // Bottom left
            imageline($this->canvas, $x + $i, $y + $newHeight, $x + $i, $y + $newHeight - $frameWidth, $color); // Bottom vertical
            imageline($this->canvas, $x, $y + $newHeight - $i, $x + $frameWidth, $y + $newHeight - $i, $color); // Bottom horizontal

            // Bottom right
            imageline($this->canvas, $x + $newWidth - $frameWidth, $y + $newHeight - $i, $x + $newWidth, $y + $newHeight - $i, $color); // Bottom horizontal
            imageline($this->canvas, $x + $newWidth - $i, $y + $newHeight - $frameWidth, $x + $newWidth - $i, $y + $newHeight, $color); // Right vertical
        }
        if ($outFileName === null) {
            header('Content-type: image/jpeg');
        }

        imagejpeg($this->canvas, $outFileName);
    }




    /**
     * Returns the detected face data in JSON format.
     *
     * @return string JSON-encoded face data.
     */
    public function toJson()
    {
        return json_encode($this->face);
    }

    /**
     * Returns the detected face data.
     *
     * @return array|null The detected face data, or null if no face is detected.
     */
    public function getFace()
    {
        return $this->face;
    }

    /**
     * Calculates image statistics required for detection.
     *
     * @param GdImage $canvas The image resource.
     *
     * @return array Array containing image width, height, integral image, and squared integral image.
     */
    protected function getImgStats($canvas)
    {
        $imageWidth = imagesx($canvas);
        $imageHeight = imagesy($canvas);
        $iis =  $this->computeII($canvas, $imageWidth, $imageHeight);
        return array(
            'width' => $imageWidth,
            'height' => $imageHeight,
            'ii' => $iis['ii'],
            'ii2' => $iis['ii2']
        );
    }

    /**
     * Computes integral images for the given canvas.
     *
     * @param GdImage $canvas The image resource.
     * @param int $imageWidth The width of the image.
     * @param int $imageHeight The height of the image.
     *
     * @return array Array containing the integral image and the squared integral image.
     */
    protected function computeII($canvas, $imageWidth, $imageHeight)
    {
        $ii_w = $imageWidth+1;
        $ii_h = $imageHeight+1;
        $ii = array();
        $ii2 = array();

        for ($i=0; $i<$ii_w; $i++) {
            $ii[$i] = 0;
            $ii2[$i] = 0;
        }

        for ($i=1; $i<$ii_h-1; $i++) {
            $ii[$i*$ii_w] = 0;
            $ii2[$i*$ii_w] = 0;
            $rowsum = 0;
            $rowsum2 = 0;
            for ($j=1; $j<$ii_w-1; $j++) {
                $rgb = ImageColorAt($canvas, $j, $i);
                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;
                $grey = (0.2989*$red + 0.587*$green + 0.114*$blue)>>0;  // this is what matlab uses
                $rowsum += $grey;
                $rowsum2 += $grey*$grey;

                $iiAbove = ($i-1)*$ii_w + $j;
                $iiThis = $i*$ii_w + $j;

                $ii[$iiThis] = $ii[$iiAbove] + $rowsum;
                $ii2[$iiThis] = $ii2[$iiAbove] + $rowsum2;
            }
        }
        return array('ii'=>$ii, 'ii2' => $ii2);
    }

     /**
     * Detects faces in the image using a greedy approach from big to small.
     *
     * @param array $ii Integral image.
     * @param array $ii2 Squared integral image.
     * @param int $width Width of the image.
     * @param int $height Height of the image.
     *
     * @return array|null Coordinates and size of the detected face, or null if no face is detected.
     */
    protected function doDetectGreedyBigToSmall($ii, $ii2, $width, $height)
    {
        $s_w = $width/20.0;
        $s_h = $height/20.0;
        $startScale = $s_h < $s_w ? $s_h : $s_w;
        $scale_update = 1 / 1.2;
        for ($scale = $startScale; $scale > 1; $scale *= $scale_update) {
            $w = (20*$scale) >> 0;
            $endx = $width - $w - 1;
            $endy = $height - $w - 1;
            $step = max($scale, 2) >> 0;
            $invArea = 1 / ($w*$w);
            for ($y = 0; $y < $endy; $y += $step) {
                for ($x = 0; $x < $endx; $x += $step) {
                    $passed = $this->detectOnSubImage($x, $y, $scale, $ii, $ii2, $w, $width+1, $invArea);
                    if ($passed) {
                        return array('x'=>$x, 'y'=>$y, 'w'=>$w);
                    }
                } // end x
            } // end y
        }  // end scale
        return null;
    }

    /**
     * Detects a face on a sub-image.
     *
     * @param int $x X-coordinate of the sub-image.
     * @param int $y Y-coordinate of the sub-image.
     * @param float $scale Scale of the sub-image.
     * @param array $ii Integral image.
     * @param array $ii2 Squared integral image.
     * @param int $w Width of the sub-image.
     * @param int $iiw Width of the integral image.
     * @param float $invArea Inverse area of the sub-image.
     *
     * @return bool True if a face is detected; otherwise false.
     */
    protected function detectOnSubImage($x, $y, $scale, $ii, $ii2, $w, $iiw, $invArea)
    {
        $mean  = ($ii[($y+$w)*$iiw + $x + $w] + $ii[$y*$iiw+$x] - $ii[($y+$w)*$iiw+$x] - $ii[$y*$iiw+$x+$w])*$invArea;

        $vnorm = ($ii2[($y+$w)*$iiw + $x + $w]
                  + $ii2[$y*$iiw+$x]
                  - $ii2[($y+$w)*$iiw+$x]
                  - $ii2[$y*$iiw+$x+$w])*$invArea - ($mean*$mean);

        $vnorm = $vnorm > 1 ? sqrt($vnorm) : 1;

        $countData = count($this->detectionData);

        for ($iStage = 0; $iStage < $countData; $iStage++) {
            $stage = $this->detectionData[$iStage];
            $trees = $stage[0];

            $stageThresh = $stage[1];
            $stageSum = 0;

            $countTrees = count($trees);

            for ($iTree = 0; $iTree < $countTrees; $iTree++) {
                $tree = $trees[$iTree];
                $currentNode = $tree[0];
                $treeSum = 0;
                while ($currentNode != null) {
                    $vals = $currentNode[0];
                    $nodeThresh = $vals[0];
                    $leftval = $vals[1];
                    $rightval = $vals[2];
                    $leftidx = $vals[3];
                    $rightidx = $vals[4];
                    $rects = $currentNode[1];

                    $rectSum = 0;
                    $countRects = count($rects);

                    for ($iRect = 0; $iRect < $countRects; $iRect++) {
                        $s = $scale;
                        $rect = $rects[$iRect];
                        $rx = ($rect[0]*$s+$x)>>0;
                        $ry = ($rect[1]*$s+$y)>>0;
                        $rw = ($rect[2]*$s)>>0;
                        $rh = ($rect[3]*$s)>>0;
                        $wt = $rect[4];

                        $r_sum = ($ii[($ry+$rh)*$iiw + $rx + $rw]
                                  + $ii[$ry*$iiw+$rx]
                                  - $ii[($ry+$rh)*$iiw+$rx]
                                  - $ii[$ry*$iiw+$rx+$rw])*$wt;

                        $rectSum += $r_sum;
                    }

                    $rectSum *= $invArea;

                    $currentNode = null;

                    if ($rectSum >= $nodeThresh*$vnorm) {

                        if ($rightidx == -1) {

                            $treeSum = $rightval;

                        } else {

                            $currentNode = $tree[$rightidx];

                        }

                    } else {

                        if ($leftidx == -1) {

                            $treeSum = $leftval;

                        } else {

                            $currentNode = $tree[$leftidx];
                        }
                    }
                }

                $stageSum += $treeSum;
            }
            if ($stageSum < $stageThresh) {
                return false;
            }
        }
        return true;
    }
}
