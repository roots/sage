<?php

header('Content-type: text/html; charset=UTF-8');

if (!isset($_GET['standalone'])) {
    require_once '../library/HTMLPurifier.auto.php';
} else {
    require_once '../library/HTMLPurifier.standalone.php';
}
error_reporting(E_ALL);

function escapeHTML($string) {
    $string = HTMLPurifier_Encoder::cleanUTF8($string);
    $string = htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    return $string;
}

if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    function fix_magic_quotes(&$array) {
        foreach ($array as $k => $val) {
            if (!is_array($val)) {
                $array[$k] = stripslashes($val);
            } else {
                fix_magic_quotes($array[$k]);
            }
        }
    }

    fix_magic_quotes($_GET);
    fix_magic_quotes($_POST);
    fix_magic_quotes($_COOKIE);
    fix_magic_quotes($_REQUEST);
    fix_magic_quotes($_ENV);
    fix_magic_quotes($_SERVER);
}

// vim: et sw=4 sts=4
