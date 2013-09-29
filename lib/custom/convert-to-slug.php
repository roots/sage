<?php
/**
 * Convert any string to a lowerclass slug 
 */
function convert_to_slug($string) {
    //lower case everything
    $string = strtolower($string);
    //make alphaunermic
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}