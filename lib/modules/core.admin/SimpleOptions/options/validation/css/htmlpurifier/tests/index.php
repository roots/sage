<?php

/** @file
 * Unit tester
 *
 * The heart and soul of HTML Purifier's correctness; anything and everything
 * is tested here! Arguments are specified like --arg=opt, allowed arguments
 * are:
 *   - flush, whether or not to flush definition caches before running
 *   - standalone, whether or not to test the standalone version
 *   - file (f), a single file to test
 *   - xml, whether or not to output XML
 *   - dry, whether or not to do a dry run
 *   - type, the type of tests to run, can be 'htmlpurifier', 'configdoc',
 *     'fstools', 'htmlt', 'vtest' or 'phpt'
 *
 * If you're interested in running the test-cases, mosey over to
 * ../test-settings.sample.php, copy the file to test-settings.php and follow
 * the enclosed instructions.
 *
 * @warning File setup does not exactly match with autoloader; make sure that
 *          non-test classes (i.e. classes that are not retrieved using
 *          $test_files) do not have underscores in their names.
 */

// HTML Purifier runs error free on E_STRICT, so if code reports
// errors, we want to know about it.
error_reporting(E_ALL | E_STRICT);

// Because we always want to know about errors, and because SimpleTest
// will notify us about them, logging the errors to stderr is
// counterproductive and in fact the wrong thing when a test case 
// exercises an error condition to detect for it.
ini_set('log_errors', false);

define('HTMLPurifierTest', 1);
define('HTMLPURIFIER_SCHEMA_STRICT', true); // validate schemas
chdir(dirname(__FILE__));

$php = 'php'; // for safety
ini_set('memory_limit', '64M');

require 'common.php';
$AC = array(); // parameters
$AC['flush'] = false;
$AC['standalone'] = false;
$AC['file'] = '';
$AC['xml']  = false;
$AC['dry']  = false;
$AC['php']  = $php;
$AC['help'] = false;
$AC['verbose'] = false;
$AC['txt'] = false;

$AC['type'] = '';
$AC['disable-phpt'] = false;
$AC['only-phpt'] = false; // alias for --type=phpt

$aliases = array(
    'f' => 'file',
    'h' => 'help',
    'v' => 'verbose',
);

// It's important that this does not call the autoloader. Not a problem
// with a function, but could be if we put this in a class.
htmlpurifier_parse_args($AC, $aliases);

if ($AC['help']) {
?>HTML Purifier test suite
Allowed options:
    --flush
    --standalone
    --file (-f) HTMLPurifier/NameOfTest.php
    --xml
    --txt
    --dry
    --php /path/to/php
    --type ( htmlpurifier | configdoc | fstools | htmlt | vtest | phpt )
    --disable-phpt
    --verbose (-v)
<?php
    exit;
}

// Disable PHPT tests if they're not enabled
if (!$GLOBALS['HTMLPurifierTest']['PHPT']) {
    $AC['disable-phpt'] = true;
} elseif (!$AC['type'] && $AC['only-phpt']) {
    // backwards-compat
    $AC['type'] = 'phpt';
}

if (!SimpleReporter::inCli()) {
    // Undo any dangerous parameters
    $AC['php'] = $php;
}

// initialize and load HTML Purifier
// use ?standalone to load the alterative standalone stub
if ($AC['standalone']) {
    require '../library/HTMLPurifier.standalone.php';
} else {
    require '../library/HTMLPurifier.path.php';
    require 'HTMLPurifier.includes.php';
}
require '../library/HTMLPurifier.autoload.php';
require 'HTMLPurifier/Harness.php';

// immediately load external libraries, so we can bail out early if
// they're bad
if ($GLOBALS['HTMLPurifierTest']['PEAR']) {
    if ($GLOBALS['HTMLPurifierTest']['Net_IDNA2']) {
        require_once 'Net/IDNA2.php';
    }
}

// Shell-script code is executed

if ($AC['xml']) {
    if (!SimpleReporter::inCli()) header('Content-Type: text/xml;charset=UTF-8');
    $reporter = new XmlReporter();
} elseif (SimpleReporter::inCli() || $AC['txt']) {
    if (!SimpleReporter::inCli()) header('Content-Type: text/plain;charset=UTF-8');
    $reporter = new HTMLPurifier_SimpleTest_TextReporter($AC);
} else {
    $reporter = new HTMLPurifier_SimpleTest_Reporter('UTF-8', $AC);
}

if ($AC['flush']) {
    htmlpurifier_flush($AC['php'], $reporter);
}

// Now, userland code begins to be executed

// setup special DefinitionCacheFactory decorator
$factory = HTMLPurifier_DefinitionCacheFactory::instance();
$factory->addDecorator('Memory'); // since we deal with a lot of config objects

if (!$AC['disable-phpt']) {
    $phpt = PHPT_Registry::getInstance();
    $phpt->php = $AC['php'];
}

// load tests
require 'test_files.php';

$FS = new FSTools();

// handle test dirs
foreach ($test_dirs as $dir) {
    $raw_files = $FS->globr($dir, '*Test.php');
    foreach ($raw_files as $file) {
        $file = str_replace('\\', '/', $file);
        if (isset($test_dirs_exclude[$file])) continue;
        $test_files[] = $file;
    }
}

// handle vtest dirs
foreach ($vtest_dirs as $dir) {
    $raw_files = $FS->globr($dir, '*.vtest');
    foreach ($raw_files as $file) {
        $test_files[] = str_replace('\\', '/', $file);
    }
}

// handle phpt files
foreach ($phpt_dirs as $dir) {
    $phpt_files = $FS->globr($dir, '*.phpt');
    foreach ($phpt_files as $file) {
        $test_files[] = str_replace('\\', '/', $file);
    }
}

// handle htmlt dirs
foreach ($htmlt_dirs as $dir) {
    $htmlt_files = $FS->globr($dir, '*.htmlt');
    foreach ($htmlt_files as $file) {
        $test_files[] = str_replace('\\', '/', $file);
    }
}

array_unique($test_files);
sort($test_files); // for the SELECT
$GLOBALS['HTMLPurifierTest']['Files'] = $test_files; // for the reporter
$test_file_lookup = array_flip($test_files);

// determine test file
if ($AC['file']) {
    if (!isset($test_file_lookup[$AC['file']])) {
        echo "Invalid file passed\n";
        exit;
    }
}

if ($AC['file']) {

    $test = new TestSuite($AC['file']);
    htmlpurifier_add_test($test, $AC['file']);

} else {

    $standalone = '';
    if ($AC['standalone']) $standalone = ' (standalone)';
    $test = new TestSuite('All HTML Purifier tests on PHP ' . PHP_VERSION . $standalone);
    foreach ($test_files as $test_file) {
        htmlpurifier_add_test($test, $test_file);
    }

}

if ($AC['dry']) $reporter->makeDry();

$test->run($reporter);

// vim: et sw=4 sts=4
