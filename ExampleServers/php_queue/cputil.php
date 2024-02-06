<?php
// Sample for creating print data

$cputilpath = "";

/*
    Determine the path of cputil and call name.
    If the path of "cputil" application set in the environment variable, 
    then it will be "cputil" or "cputil.exe". 
    If create the directory of "cputil" in same directory with "cloudprnt.php"
    and put the "cputil" application, then it will be "cputil\cputil.exe".
    (In this sample indicated on Windows environment)
*/
if (substr(PHP_OS,0,3) == 'WIN') {
    if (file_exists(dirname(__FILE__).'\cputil\cputil.exe')) {
        $cputilpath = 'cputil\cputil.exe';
    } else {
        $cputilpath = 'cputil.exe';
    }
} else {
    if (file_exists(dirname(__FILE__).'/cputil/cputil')) {
        $cputilpath = 'cputil/cputil';
    } else {
        $cputilpath = 'cputil';
    }
}

/*
	Use cputil to convert an input file to the requested output format and device width
	and write to a requested output file.
*/
function getCPConvertedJob($inputFile, $outputFormat, $deviceWidth, $outputFile){
    global $cputilpath;
    $options = "";

    if ($deviceWidth <= (58 * 8)) {
        $options = $options."thermal2";
    } elseif ($deviceWidth <= (72 * 8)) {
        $options = $options."thermal3";
    } elseif ($deviceWidth <= (82 * 8)) {
        $options = $options."thermal82";
    } elseif ($deviceWidth <= (112 * 8)) {
        $options = $options."thermal4";
    }

    $options = $options." scale-to-fit dither ";

    system($cputilpath." ".$options." decode \"".$outputFormat."\" \"".$inputFile."\" \"".$outputFile."\"", $retval);
}

/*
	Create the print job using Star Markup 
*/
function renderMarkupJob($filename, $position, $queue, $design) {
    $file = fopen($filename, 'w+');

    if ($file != FALSE) {
        fwrite($file, "[align: centre]");

        if (isset($design['Logo'])) {
            fwrite($file, "[image: url ".$design['Logo']."; width 100%]\n");
        }

        if (isset($design['Header'])) {
            fwrite($file, $design['Header']."\n");
        }

        fwrite($file, "[align: centre]");
        fwrite($file, "[mag: w 4; h 4]".$position."[mag]\n");

        if (isset($design['Footer'])) {
            fwrite($file, $design['Footer']."\n");
        }

        if (isset($design['Coupon'])) {
            fwrite($file, "[image: url ".$design['Coupon']."; width 100%]\n");
        }

        fwrite($file, "[cut]");

        fclose($file);
    }
}

/*
	Get a list of supported input types from "cputil" for a given input type
*/
function getCPSupportedOutputs($input) {
    global $cputilpath;
    $file = popen($cputilpath." mediatypes-mime \"text/vnd.star.markup\"", "r");

    if ($file != FALSE) {
        $output = fread($file, 8192);

        pclose($file);
        return json_decode($output);
    }

    return "";
}

?>
