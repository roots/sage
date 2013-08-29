#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

/**
 * @file
 * Parses *.ent files into an entity lookup table, and then serializes and
 * writes the whole kaboodle to a file. The resulting file is cached so
 * that this script does not need to be run. This script should rarely,
 * if ever, be run, since HTML's entities are fairly immutable.
 */

// here's where the entity files are located, assuming working directory
// is the same as the location of this PHP file. Needs trailing slash.
$entity_dir = '../docs/entities/';

// defines the output file for the serialized content.
$output_file = '../library/HTMLPurifier/EntityLookup/entities.ser';

// courtesy of a PHP manual comment
function unichr($dec) {
    if ($dec < 128) {
        $utf  = chr($dec);
    } else if ($dec < 2048) {
        $utf  = chr(192 + (($dec - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    } else {
        $utf  = chr(224 + (($dec - ($dec % 4096)) / 4096));
        $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    }
    return $utf;
}

if ( !is_dir($entity_dir) ) exit("Fatal Error: Can't find entity directory.\n");
if ( file_exists($output_file) ) exit("Fatal Error: output file already exists.\n");

$dh = @opendir($entity_dir);
if ( !$dh ) exit("Fatal Error: Cannot read entity directory.\n");

$entity_files = array();
while (($file = readdir($dh)) !== false) {
    if (@$file[0] === '.') continue;
    if (substr(strrchr($file, "."), 1) !== 'ent') continue;
    $entity_files[] = $file;
}
closedir($dh);

if ( !$entity_files ) exit("Fatal Error: No entity files to parse.\n");

$entity_table = array();
$regexp = '/<!ENTITY\s+([A-Za-z0-9]+)\s+"&#(?:38;#)?([0-9]+);">/';

foreach ( $entity_files as $file ) {
    $contents = file_get_contents($entity_dir . $file);
    $matches = array();
    preg_match_all($regexp, $contents, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $entity_table[$match[1]] = unichr($match[2]);
    }
}

$output = serialize($entity_table);

$fh = fopen($output_file, 'w');
fwrite($fh, $output);
fclose($fh);

echo "Completed successfully.";

// vim: et sw=4 sts=4
