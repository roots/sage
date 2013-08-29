#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
require_once '../library/HTMLPurifier.auto.php';
assertCli();

if (version_compare(PHP_VERSION, '5.2.2', '<')) {
    echo "This script requires PHP 5.2.2 or later, for tokenizer line numbers.";
    exit(1);
}

/**
 * @file
 * Scans HTML Purifier source code for $config tokens and records the
 * directive being used; configdoc can use this info later.
 *
 * Currently, this just dumps all the info onto the console. Eventually, it
 * will create an XML file that our XSLT transform can use.
 */

$FS = new FSTools();
chdir(dirname(__FILE__) . '/../library/');
$raw_files = $FS->globr('.', '*.php');
$files = array();
foreach ($raw_files as $file) {
    $file = substr($file, 2); // rm leading './'
    if (strncmp('standalone/', $file, 11) === 0) continue; // rm generated files
    if (substr_count($file, '.') > 1) continue; // rm meta files
    $files[] = $file;
}

/**
 * Moves the $i cursor to the next non-whitespace token
 */
function consumeWhitespace($tokens, &$i) {
    do {$i++;} while (is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE);
}

/**
 * Tests whether or not a token is a particular type. There are three run-cases:
 *      - ($token, $expect_token): tests if the token is $expect_token type;
 *      - ($token, $expect_value): tests if the token is the string $expect_value;
 *      - ($token, $expect_token, $expect_value): tests if token is $expect_token type, and
 *        its string representation is $expect_value
 */
function testToken($token, $value_or_token, $value = null) {
    if (is_null($value)) {
        if (is_int($value_or_token)) return is_array($token) && $token[0] === $value_or_token;
        else return $token === $value_or_token;
    } else {
        return is_array($token) && $token[0] === $value_or_token && $token[1] === $value;
    }
}

$counter = 0;
$full_counter = 0;
$tracker = array();

foreach ($files as $file) {
    $tokens = token_get_all(file_get_contents($file));
    $file = str_replace('\\', '/', $file);
    for ($i = 0, $c = count($tokens); $i < $c; $i++) {
        $ok = false;
        // Match $config
        if (!$ok && testToken($tokens[$i], T_VARIABLE, '$config')) $ok = true;
        // Match $this->config
        while (!$ok && testToken($tokens[$i], T_VARIABLE, '$this')) {
            consumeWhitespace($tokens, $i);
            if (!testToken($tokens[$i], T_OBJECT_OPERATOR)) break;
            consumeWhitespace($tokens, $i);
            if (testToken($tokens[$i], T_STRING, 'config')) $ok = true;
            break;
        }
        if (!$ok) continue;

        $ok = false;
        for($i++; $i < $c; $i++) {
            if ($tokens[$i] === ',' || $tokens[$i] === ')' || $tokens[$i] === ';') {
                break;
            }
            if (is_string($tokens[$i])) continue;
            if ($tokens[$i][0] === T_OBJECT_OPERATOR) {
                $ok = true;
                break;
            }
        }
        if (!$ok) continue;

        $line = $tokens[$i][2];

        consumeWhitespace($tokens, $i);
        if (!testToken($tokens[$i], T_STRING, 'get')) continue;

        consumeWhitespace($tokens, $i);
        if (!testToken($tokens[$i], '(')) continue;

        $full_counter++;

        $matched = false;
        do {

            // What we currently don't match are batch retrievals, and
            // wildcard retrievals. This data might be useful in the future,
            // which is why we have a do {} while loop that doesn't actually
            // do anything.

            consumeWhitespace($tokens, $i);
            if (!testToken($tokens[$i], T_CONSTANT_ENCAPSED_STRING)) continue;
            $id = substr($tokens[$i][1], 1, -1);

            $counter++;
            $matched = true;

            if (!isset($tracker[$id])) $tracker[$id] = array();
            if (!isset($tracker[$id][$file])) $tracker[$id][$file] = array();
            $tracker[$id][$file][] = $line;

        } while (0);

        //echo "$file:$line uses $namespace.$directive\n";
    }
}

echo "\n$counter/$full_counter instances of \$config or \$this->config found in source code.\n";

echo "Generating XML... ";

$xw = new XMLWriter();
$xw->openURI('../configdoc/usage.xml');
$xw->setIndent(true);
$xw->startDocument('1.0', 'UTF-8');
$xw->startElement('usage');
foreach ($tracker as $id => $files) {
    $xw->startElement('directive');
    $xw->writeAttribute('id', $id);
    foreach ($files as $file => $lines) {
        $xw->startElement('file');
        $xw->writeAttribute('name', $file);
        foreach ($lines as $line) {
            $xw->writeElement('line', $line);
        }
        $xw->endElement();
    }
    $xw->endElement();
}
$xw->endElement();
$xw->flush();

echo "done!\n";

// vim: et sw=4 sts=4
