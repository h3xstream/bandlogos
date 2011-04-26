<?php
include_once("ImageUtil.class.php");

function arrayToCode(array $array) {
    return "Array(".$array[0].",".$array[1].",".$array[2].")";
}

/**
 * The objective of the this caching is to have quick lookup table
 * for every filter of color. Huge part of the previous processing was
 * taken on apply the filter because that each calculation were repeat
 * for each pixel.
 * Generating the cache at runtime is not perfect since PHP would
 * need to regenerate on every request.
 *
 * @param $functionName Function use to generate the modify color.
 * @param $fileName File use to store the script
 * @param $varName Variable use in the php file
 */
function cacheToFile($functionName,$fileName,$varName) {
    $f = fopen($fileName,"w+");
    fwrite($f,"<?php\n");
    fwrite($f,"global \$$varName;\n");
    fwrite($f,"\$$varName=Array();\n");

    for($i=0;$i<256;$i++) {
        $arr = call_user_func($functionName,array($i,$i,$i));
        fwrite($f,"\$$varName".'['.$i.']='.arrayToCode($arr).";\n");
    }
    fwrite($f,"\n?>");
    //echo file_get_contents();
    fclose($f);
}
//Invert
cacheToFile("ImageUtil::getInvertColor","cache_invert.php","cacheInvert");

//Gray
function get_gray($oldCol) {
    return ImageUtil::getDarkerColor($oldCol,ImageUtil::GRAY_VALUE);
}
cacheToFile("get_gray","cache_gray.php","cacheGray");

//Blue
function get_blue($oldCol) {
    return ImageUtil::changeDarkToSpecColor(
        ImageUtil::getDarkerColor($oldCol,ImageUtil::GRAY_VALUE),array(40,79,148));
}
cacheToFile("get_blue","cache_40+79+148.php","cacheColor");

//Red
function get_red($oldCol) {
    return ImageUtil::changeDarkToSpecColor(
        ImageUtil::getDarkerColor($oldCol,ImageUtil::GRAY_VALUE),array(214,17,2));
}
cacheToFile("get_red","cache_214+17+2.php","cacheColor");

//Orange
function get_orange($oldCol) {
    return ImageUtil::changeDarkToSpecColor(
        ImageUtil::getDarkerColor($oldCol,ImageUtil::GRAY_VALUE),array(222,54,0));
}
cacheToFile("get_orange","cache_222+54+0.php","cacheColor");

//Turquoise
function get_turquoise($oldCol) {
    return ImageUtil::changeDarkToSpecColor(
        ImageUtil::getDarkerColor($oldCol,ImageUtil::GRAY_VALUE),array(69,168,196));
}
cacheToFile("get_turquoise","cache_69+168+196.php","cacheColor");

?>