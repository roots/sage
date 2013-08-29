#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

echo "Please do not run this script. It is here for historical purposes only.";
exit;

/**
 * @file
 * Removes ConfigSchema function calls from source files.
 */

chdir(dirname(__FILE__) . '/../library/');
$FS = new FSTools();

$files = $FS->globr('.', '*.php');
foreach ($files as $file) {
    if (substr_count(basename($file), '.') > 1) continue;
    $old_code = file_get_contents($file);
    $new_code = preg_replace("#^HTMLPurifier_ConfigSchema::.+?\);[\n\r]*#ms", '', $old_code);
    if ($old_code !== $new_code) {
        file_put_contents($file, $new_code);
    }
    if (preg_match('#^\s+HTMLPurifier_ConfigSchema::#m', $new_code)) {
        echo "Indented ConfigSchema call in $file\n";
    }
}

// vim: et sw=4 sts=4
