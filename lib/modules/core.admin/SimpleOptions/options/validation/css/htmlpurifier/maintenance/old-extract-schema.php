#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

echo "Please do not run this script. It is here for historical purposes only.";
exit;

/**
 * @file
 * Extracts all definitions inside a configuration schema
 * (HTMLPurifier_ConfigSchema) and exports them as plain text files.
 *
 * @todo Extract version numbers.
 */

define('HTMLPURIFIER_SCHEMA_STRICT', true); // description data needs to be collected
require_once dirname(__FILE__) . '/../library/HTMLPurifier.auto.php';

// We need includes to ensure all HTMLPurifier_ConfigSchema calls are
// performed.
require_once 'HTMLPurifier.includes.php';

// Also, these extra files will be necessary.
require_once 'HTMLPurifier/Filter/ExtractStyleBlocks.php';

/**
 * Takes a hash and saves its contents to library/HTMLPurifier/ConfigSchema/
 */
function saveHash($hash) {
    if ($hash === false) return;
    $dir = realpath(dirname(__FILE__) . '/../library/HTMLPurifier/ConfigSchema');
    $name = $hash['ID'] . '.txt';
    $file = $dir . '/' . $name;
    if (file_exists($file)) {
        trigger_error("File already exists; skipped $name");
        return;
    }
    $file = new FSTools_File($file);
    $file->open('w');
    $multiline = false;
    foreach ($hash as $key => $value) {
        $multiline = $multiline || (strpos($value, "\n") !== false);
        if ($multiline) {
            $file->put("--$key--" . PHP_EOL);
            $file->put(str_replace("\n", PHP_EOL, $value) . PHP_EOL);
        } else {
            if ($key == 'ID') {
                $file->put("$value" . PHP_EOL);
            } else {
                $file->put("$key: $value" . PHP_EOL);
            }
        }
    }
    $file->close();
}

$schema  = HTMLPurifier_ConfigSchema::instance();
$adapter = new HTMLPurifier_ConfigSchema_StringHashReverseAdapter($schema);

foreach ($schema->info as $ns => $ns_array) {
    saveHash($adapter->get($ns));
    foreach ($ns_array as $dir => $x) {
        saveHash($adapter->get($ns, $dir));
    }
}

// vim: et sw=4 sts=4
