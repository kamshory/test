<?php

use MagicObject\Util\File\FileUtil;
use MagicObject\Util\PicoIniUtil;

require_once __DIR__ . "/inc.lib/vendor/autoload.php";

$inputEn = __DIR__ . "/inc.lang/en/app.ini";
$inputId = __DIR__ . "/inc.lang/id/app.ini";
$inputSource = __DIR__ . "/inc.lang/source/app.ini";

function write_ini_file($array, $path) {
    $arrayMulti = false;
    
    # See if the array input is multidimensional.
    foreach($array as $arrayTest){
        if(is_array($arrayTest)) {
          $arrayMulti = true;
        }
    }
    
    $content = "";

    # Use categories in the INI file for multidimensional array OR use basic INI file:
    if ($arrayMulti) {
        foreach ($array as $key => $elem) {
            $content .= "[" . $key . "]\n";
            foreach ($elem as $key2 => $elem2) {
                if (is_array($elem2)) {
                    for ($i = 0; $i < count($elem2); $i++) {
                        $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n";
                    }
                } else if ($elem2 == "") {
                    $content .= $key2 . " = \n";
                } else {
                    $content .= $key2 . " = \"" . $elem2 . "\"\n";
                }
            }
        }
    } else {
        foreach ($array as $key2 => $elem2) {
            if (is_array($elem2)) {
                for ($i = 0; $i < count($elem2); $i++) {
                    $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n";
                }
            } else if ($elem2 == "") {
                $content .= $key2 . " = \n";
            } else {
                $content .= $key2 . " = \"" . $elem2 . "\"\n";
            }
        }
    }

    if (!$handle = fopen($path, 'w')) {
        return false;
    }
    if (!fwrite($handle, $content)) {
        return false;
    }
    fclose($handle);
    return true;
}

$arr = array(
    $inputEn,
    $inputId,
    $inputSource
);

foreach($arr as $file)
{
    $file = FileUtil::fixFilePath($file);
    if(!file_exists(dirname($file)))
    {
        mkdir(dirname($file), 0755, true);
        file_put_contents($file, "");
    }
}

if(isset($_POST['translate']))
{
    $inputStr = trim($_POST['translate']);
    $keysStr = trim($_POST['keys']);
    if(strlen($inputStr))
    {
        $tranlate = explode("\r\n", $inputStr);
        $keys = explode("|", $keysStr);
        $inputId = FileUtil::fixFilePath($inputId);
        if(file_exists($inputId))
        {
            $inputArray = PicoIniUtil::parseIniFile($inputId);
        }
        else
        {
            $inputArray = array();
        }
        $i = 0;
        foreach($tranlate as $index=>$line)
        {
            $j = 0;
            $key = $keys[$index];
            $inputArray[$key] = $line;
            $i++;
        }
        
        $outputArray = PicoIniUtil::parseIniFile($inputId); 
        foreach($inputArray as $key=>$value)
        {
            $outputArray[$key] = $value;
        } 
        write_ini_file($outputArray, $inputId);
    }
}
foreach($arr as $file)
{
    $file = FileUtil::fixFilePath($file);
    if(!file_exists(dirname($file)))
    {
        mkdir(dirname($file), 0755, true);
        file_put_contents($file, "");
    }
}
$inputSource = FileUtil::fixFilePath($inputSource);
if(file_exists($inputSource))
{
    $inputArray = @PicoIniUtil::parseIniFile($inputSource);
    if(!is_array($inputArray))
    {
        $inputArray = array();
    }
}
else
{
    $inputArray = array();
}
$outputArray = @PicoIniUtil::parseIniFile($inputId);
foreach($outputArray as $key=>$value)
{
    if(isset($inputArray[$key]))
    {
        unset($inputArray[$key]);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translate</title>
    <style>
        body
        {
            margin: 0;
            position: relative;
        }
        .all{
            padding: 10px;
            position: relative;
        }
        table{
            width: 100%;
            box-sizing: border-box;
            border-collapse: collapse;
        }
        table tr > td:nth-child(1){
            padding: 0 5px 0 0;
        }
        table tr > td:nth-child(2){
            padding: 0 0 0 5px;
        }
        textarea
        {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            min-height: 300px;
            height: calc(100vh - 180px);
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.45;
        }
        input[type="submit"]{
            padding: 5px 16px;
        }
        .button-area{
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="all">
        <div>
            <form action="" method="post">
                <table>
                    <tbody>
                        <tr>
                            
                            <td width="50%"><textarea name="original" id="original" spellcheck="false"><?php echo implode("\r\n", array_values($inputArray));?></textarea></td>
                            <td width="50%"><textarea name="translate" id="translate" spellcheck="false"></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="keys" value="<?php echo implode("|", array_keys($inputArray));?>" >
                <div class="button-area">
                    <input type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
</body>
</html>