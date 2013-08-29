#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

/**
 * @file
 * Converts all instances of $config->set and $config->get to the new
 * format, as described by docs/dev-config-bcbreaks.txt
 */

$FS = new FSTools();
chdir(dirname(__FILE__) . '/..');
$raw_files = $FS->globr('.', '*.php');
foreach ($raw_files as $file) {
    $file = substr($file, 2); // rm leading './'
    if (strpos($file, 'library/standalone/') === 0) continue;
    if (strpos($file, 'maintenance/update-config.php') === 0) continue;
    if (strpos($file, 'test-settings.php') === 0) continue;
    if (substr_count($file, '.') > 1) continue; // rm meta files
    // process the file
    $contents = file_get_contents($file);
    $contents = preg_replace(
        "#config->(set|get)\('(.+?)', '(.+?)'#",
        "config->\\1('\\2.\\3'",
        $contents
    );
    if ($contents === '') continue;
    file_put_contents($file, $contents);
}

// vim: et sw=4 sts=4
