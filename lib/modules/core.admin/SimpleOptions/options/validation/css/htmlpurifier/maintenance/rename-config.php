#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
require_once '../library/HTMLPurifier.auto.php';
assertCli();

/**
 * @file
 * Renames a configuration directive.  This involves renaming the file,
 * adding an alias, and then regenerating the cache.  You still have to
 * manually go through and fix any calls to the directive.
 * @warning This script doesn't handle multi-stringhash files.
 */

$argv = $_SERVER['argv'];
if (count($argv) < 3) {
    echo "Usage: {$argv[0]} OldName NewName\n";
    exit(1);
}

chdir('../library/HTMLPurifier/ConfigSchema/schema');

$old = $argv[1];
$new = $argv[2];

if (!file_exists("$old.txt")) {
    echo "Cannot move undefined configuration directive $old\n";
    exit(1);
}

if ($old === $new) {
    echo "Attempting to move to self, aborting\n";
    exit(1);
}

if (file_exists("$new.txt")) {
    echo "Cannot move to already defined directive $new\n";
    exit(1);
}

$file = "$old.txt";
$builder = new HTMLPurifier_ConfigSchema_InterchangeBuilder();
$interchange = new HTMLPurifier_ConfigSchema_Interchange();
$builder->buildFile($interchange, $file);
$contents = file_get_contents($file);

if (strpos($contents, "\r\n") !== false) {
    $nl = "\r\n";
} elseif (strpos($contents, "\r") !== false) {
    $nl = "\r";
} else {
    $nl = "\n";
}

// replace name with new name
$contents = str_replace($old, $new, $contents);

if ($interchange->directives[$old]->aliases) {
    $pos_alias = strpos($contents, 'ALIASES:');
    $pos_ins = strpos($contents, $nl, $pos_alias);
    if ($pos_ins === false) $pos_ins = strlen($contents);
    $contents =
        substr($contents, 0, $pos_ins) . ", $old" . substr($contents, $pos_ins);
    file_put_contents($file, $contents);
} else {
    $lines = explode($nl, $contents);
    $insert = false;
    foreach ($lines as $n => $line) {
        if (strncmp($line, '--', 2) === 0) {
            $insert = $n;
            break;
        }
    }
    if (!$insert) {
        $lines[] = "ALIASES: $old";
    } else {
        array_splice($lines, $insert, 0, "ALIASES: $old");
    }
    file_put_contents($file, implode($nl, $lines));
}

rename("$old.txt", "$new.txt") || exit(1);
