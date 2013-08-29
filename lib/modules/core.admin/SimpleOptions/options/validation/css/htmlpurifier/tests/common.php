<?php

if (!defined('HTMLPurifierTest')) {
    echo "Invalid entry point\n";
    exit;
}

// setup our own autoload, checking for HTMLPurifier library if spl_autoload_register
// is not allowed
function __autoload($class) {
    if (!function_exists('spl_autoload_register')) {
        if (HTMLPurifier_Bootstrap::autoload($class)) return true;
        if (HTMLPurifierExtras::autoload($class)) return true;
    }
    require str_replace('_', '/', $class) . '.php';
    return true;
}
if (function_exists('spl_autoload_register')) {
    spl_autoload_register('__autoload');
}

// default settings (protect against register_globals)
$GLOBALS['HTMLPurifierTest'] = array();
$GLOBALS['HTMLPurifierTest']['PEAR'] = false; // do PEAR tests
$GLOBALS['HTMLPurifierTest']['PHPT'] = true; // do PHPT tests
$GLOBALS['HTMLPurifierTest']['PH5P'] = class_exists('DOMDocument');

// default library settings
$simpletest_location = 'simpletest/'; // reasonable guess
$csstidy_location = false;
$versions_to_test = array();
$php  = 'php';
$phpv = 'phpv';

// load configuration
if (file_exists('../conf/test-settings.php')) include '../conf/test-settings.php';
elseif (file_exists('../test-settings.php')) include '../test-settings.php';
else {
    throw new Exception('Please create a test-settings.php file by copying test-settings.sample.php and configuring accordingly');
}

// load SimpleTest
require_once $simpletest_location . 'unit_tester.php';
require_once $simpletest_location . 'reporter.php';
require_once $simpletest_location . 'mock_objects.php';
require_once $simpletest_location . 'xml.php';
require_once $simpletest_location . 'remote.php';

// load CSS Tidy
if ($csstidy_location !== false) {
    $old = error_reporting(E_ALL);
    require $csstidy_location . 'class.csstidy.php';
    error_reporting($old);
}

// load PEAR to include path
if ( is_string($GLOBALS['HTMLPurifierTest']['PEAR']) ) {
    // if PEAR is true, there's no need to add it to the path
    set_include_path($GLOBALS['HTMLPurifierTest']['PEAR'] . PATH_SEPARATOR .
        get_include_path());
}

// after external libraries are loaded, turn on compile time errors
error_reporting(E_ALL | E_STRICT);

// initialize extra HTML Purifier libraries
require '../extras/HTMLPurifierExtras.auto.php';

// load SimpleTest addon functions
require 'generate_mock_once.func.php';
require 'path2class.func.php';

/**
 * Arguments parser, is cli and web agnostic.
 * @warning
 *   There are some quirks about the argument format:
 *     - Short boolean flags cannot be chained together
 *     - Only strings, integers and booleans are accepted
 * @param $AC
 *   Arguments array to populate. This takes a simple format of 'argument'
 *   => default value. Depending on the type of the default value,
 *   arguments will be typecast accordingly. For example, if
 *   'flag' => false is passed, all arguments for that will be cast to
 *   boolean. Do *not* pass null, as it will not be recognized.
 * @param $aliases
 *
 */
function htmlpurifier_parse_args(&$AC, $aliases) {
    if (empty($_GET) && !empty($_SERVER['argv'])) {
        array_shift($_SERVER['argv']);
        $o = false;
        $bool = false;
        $val_is_bool = false;
        foreach ($_SERVER['argv'] as $opt) {
            if ($o !== false) {
                $v = $opt;
            } else {
                if ($opt === '') continue;
                if (strlen($opt) > 2 && strncmp($opt, '--', 2) === 0) {
                    $o = substr($opt, 2);
                } elseif ($opt[0] == '-') {
                    $o = substr($opt, 1);
                } else {
                    $lopt = strtolower($opt);
                    if ($bool !== false && ($opt === '0' || $lopt === 'off' || $lopt === 'no')) {
                        $o = $bool;
                        $v = false;
                        $val_is_bool = true;
                    } elseif (isset($aliases[''])) {
                        $o = $aliases[''];
                    }
                }
                $bool = false;
                if (!isset($AC[$o]) || !is_bool($AC[$o])) {
                    if (strpos($o, '=') === false) {
                        continue;
                    }
                    list($o, $v) = explode('=', $o);
                } elseif (!$val_is_bool) {
                    $v = true;
                    $bool = $o;
                }
                $val_is_bool = false;
            }
            if ($o === false) continue;
            htmlpurifier_args($AC, $aliases, $o, $v);
            $o = false;
        }
    } else {
        foreach ($_GET as $o => $v) {
            if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
                $v = stripslashes($v);
            }
            htmlpurifier_args($AC, $aliases, $o, $v);
        }
    }
}

/**
 * Actually performs assignment to $AC, see htmlpurifier_parse_args()
 * @param $AC Arguments array to write to
 * @param $aliases Aliases for options
 * @param $o Argument name
 * @param $v Argument value
 */
function htmlpurifier_args(&$AC, $aliases, $o, $v) {
    if (isset($aliases[$o])) $o = $aliases[$o];
    if (!isset($AC[$o])) return;
    if (is_string($AC[$o])) $AC[$o] = $v;
    if (is_bool($AC[$o]))   $AC[$o] = ($v === '') ? true :(bool) $v;
    if (is_int($AC[$o]))    $AC[$o] = (int) $v;
}

/**
 * Adds a test-class; we use file extension to determine which class to use.
 */
function htmlpurifier_add_test($test, $test_file, $only_phpt = false) {
    switch (strrchr($test_file, ".")) {
        case '.phpt':
            return $test->add(new PHPT_Controller_SimpleTest($test_file));
        case '.php':
            require_once $test_file;
            return $test->add(path2class($test_file));
        case '.vtest':
            return $test->add(new HTMLPurifier_ConfigSchema_ValidatorTestCase($test_file));
        case '.htmlt':
            return $test->add(new HTMLPurifier_HTMLT($test_file));
        default:
            trigger_error("$test_file is an invalid file for testing", E_USER_ERROR);
    }
}

/**
 * Debugging function that prints tokens in a user-friendly manner.
 */
function printTokens($tokens, $index = null) {
    $string = '<pre>';
    $generator = new HTMLPurifier_Generator(HTMLPurifier_Config::createDefault(), new HTMLPurifier_Context);
    foreach ($tokens as $i => $token) {
        if ($index === $i) $string .= '[<strong>';
        $string .= "<sup>$i</sup>";
        $string .= $generator->escape($generator->generateFromToken($token));
        if ($index === $i) $string .= '</strong>]';
    }
    $string .= '</pre>';
    echo $string;
}

/**
 * Convenient "insta-fail" test-case to add if any outside things fail
 */
class FailedTest extends UnitTestCase {
    protected $msg, $details;
    public function __construct($msg, $details = null) {
        $this->msg = $msg;
        $this->details = $details;
    }
    public function test() {
        $this->fail($this->msg);
        if ($this->details) $this->reporter->paintFormattedMessage($this->details);
    }
}

/**
 * Flushes all caches, and fatally errors out if there's a problem.
 */
function htmlpurifier_flush($php, $reporter) {
    exec($php . ' ../maintenance/flush.php ' . $php . ' 2>&1', $out, $status);
    if ($status) {
        $test = new FailedTest(
            'maintenance/flush.php returned non-zero exit status',
            wordwrap(implode("\n", $out), 80)
        );
        $test->run($reporter);
        exit(1);
    }
}

/**
 * Dumps error queue, useful if there has been a fatal error.
 */
function htmlpurifier_dump_error_queue() {
    $context = SimpleTest::getContext();
    $queue = $context->get('SimpleErrorQueue');
    while (($error = $queue->extract()) !== false) {
        var_dump($error);
    }
}
register_shutdown_function('htmlpurifier_dump_error_queue');

// vim: et sw=4 sts=4
