<?php

/** @file
 * Multiple PHP Versions test
 *
 * This file tests HTML Purifier in all versions of PHP. Arguments
 * are specified like --arg=opt, allowed arguments are:
 *   - quiet (q), if specified no informative messages are enabled (please use
 *     this if you're outputting XML)
 *   - distro, allowed values 'normal' or 'standalone', by default all
 *     distributions are tested. "--standalone" is a shortcut for
 *     "--distro=standalone".
 *   - quick, run only the most recent versions of each release series
 *   - disable-flush, by default flush is run, this disables it
 *   - file (f), xml, type: these correspond to the parameters in index.php
 *
 * @note
 *   It requires a script called phpv that takes an extra argument (the
 *   version number of PHP) before all other arguments. Contact me if you'd
 *   like to set up a similar script. The name of the script can be
 *   edited with $phpv
 *
 * @note
 *   Also, configuration must be set up with a variable called
 *   $versions_to_test specifying version numbers to pass to $phpv
 */

define('HTMLPurifierTest', 1);
chdir(dirname(__FILE__));
$php = 'php'; // for safety

require_once 'common.php';

if (!SimpleReporter::inCli()) {
    echo 'Multitest only available from command line';
    exit;
}

$AC = array(); // parameters
$AC['file']  = '';
$AC['xml']   = false;
$AC['quiet'] = false;
$AC['php'] = $php;
$AC['disable-phpt'] = false;
$AC['disable-flush'] = false;
$AC['type'] = '';
$AC['distro'] = ''; // valid values are normal/standalone
$AC['quick'] = false; // run the latest version on each release series
$AC['standalone'] = false; // convenience for --distro=standalone
// Legacy parameters
$AC['only-phpt'] = false; // --type=phpt
$AC['exclude-normal'] = false; // --distro=standalone
$AC['exclude-standalone'] = false; // --distro=normal
$AC['verbose'] = false;
$aliases = array(
    'f' => 'file',
    'q' => 'quiet',
    'v' => 'verbose',
);
htmlpurifier_parse_args($AC, $aliases);

// Backwards compat extra parsing
if ($AC['only-phpt']) {
    $AC['type'] = 'phpt';
}
if ($AC['exclude-normal']) $AC['distro'] = 'standalone';
elseif ($AC['exclude-standalone']) $AC['distro'] = 'normal';
elseif ($AC['standalone']) $AC['distro'] = 'standalone';

if ($AC['xml']) {
    $reporter = new XmlReporter();
} else {
    $reporter = new HTMLPurifier_SimpleTest_TextReporter($AC);
}

// Regenerate any necessary files
if (!$AC['disable-flush']) htmlpurifier_flush($AC['php'], $reporter);

$file_arg = '';
require 'test_files.php';
if ($AC['file']) {
    $test_files_lookup = array_flip($test_files);
    if (isset($test_files_lookup[$AC['file']])) {
        $file_arg = '--file=' . $AC['file'];
    } else {
        throw new Exception("Invalid file passed");
    }
}
// This allows us to get out of having to do dry runs.
$size = count($test_files);

$type_arg = '';
if ($AC['type']) $type_arg = '--type=' . $AC['type'];

if ($AC['quick']) {
    $seriesArray = array();
    foreach ($versions_to_test as $version) {
        $series = substr($version, 0, strpos($version, '.', strpos($version, '.') + 1));
        if (!isset($seriesArray[$series])) {
            $seriesArray[$series] = $version;
            continue;
        }
        if (version_compare($version, $seriesArray[$series], '>')) {
            $seriesArray[$series] = $version;
        }
    }
    $versions_to_test = array_values($seriesArray);
}

// Setup the test
$test = new TestSuite('HTML Purifier Multiple Versions Test');
foreach ($versions_to_test as $version) {
    // Support for arbitrarily forcing flushes by wrapping the suspect
    // version name in an array()
    $flush_arg = '';
    if (is_array($version)) {
        $version = $version[0];
        $flush_arg = '--flush';
    }
    if ($AC['type'] !== 'phpt') {
        $break = true;
        switch ($AC['distro']) {
            case '':
                $break = false;
            case 'normal':
                $test->add(
                    new CliTestCase(
                        "$phpv $version index.php --xml $flush_arg $type_arg --disable-phpt $file_arg",
                        $AC['quiet'], $size
                    )
                );
                if ($break) break;
            case 'standalone':
                $test->add(
                    new CliTestCase(
                        "$phpv $version index.php --xml $flush_arg $type_arg --standalone --disable-phpt $file_arg",
                        $AC['quiet'], $size
                    )
                );
                if ($break) break;
        }
    }
    if (!$AC['disable-phpt'] && (!$AC['type'] || $AC['type'] == 'phpt')) {
        $test->add(
            new CliTestCase(
                $AC['php'] . " index.php --xml --php \"$phpv $version\" --type=phpt",
                $AC['quiet'], $size
            )
        );
    }
}

// This is the HTML Purifier website's test XML file. We could
// add more websites, i.e. more configurations to test.
// $test->add(new RemoteTestCase('http://htmlpurifier.org/dev/tests/?xml=1', 'http://htmlpurifier.org/dev/tests/?xml=1&dry=1&flush=1'));

$test->run($reporter);

// vim: et sw=4 sts=4
