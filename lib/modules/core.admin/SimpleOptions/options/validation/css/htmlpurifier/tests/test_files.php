<?php

if (!defined('HTMLPurifierTest')) exit;

// These arrays are defined by this file and can be relied upon.
$test_files = array();
$test_dirs = array();
$test_dirs_exclude = array();
$vtest_dirs = array();
$htmlt_dirs = array();
$phpt_dirs  = array();

$break = true;
switch ($AC['type']) {
    case '':
        $break = false;
    case 'htmlpurifier':
        $test_dirs[] = 'HTMLPurifier';
        $test_files[] = 'HTMLPurifierTest.php';
        $test_dirs_exclude['HTMLPurifier/Filter/ExtractStyleBlocksTest.php'] = true;
        if ($csstidy_location) {
          $test_files[] = 'HTMLPurifier/Filter/ExtractStyleBlocksTest.php';
        }
        if ($break) break;
    case 'configdoc':
        if (version_compare(PHP_VERSION, '5.2', '>=')) {
            // $test_dirs[] = 'ConfigDoc'; // no test files currently!
        }
        if ($break) break;
    case 'fstools':
        $test_dirs[] = 'FSTools';
    case 'htmlt':
        $htmlt_dirs[] = 'HTMLPurifier/HTMLT';
        if ($break) break;
    case 'vtest':
        $vtest_dirs[] = 'HTMLPurifier/ConfigSchema/Validator';
        if ($break) break;

    case 'phpt':
        if (!$AC['disable-phpt'] && version_compare(PHP_VERSION, '5.2', '>=')) {
            $phpt_dirs[] = 'HTMLPurifier/PHPT';
        }
}

// vim: et sw=4 sts=4
