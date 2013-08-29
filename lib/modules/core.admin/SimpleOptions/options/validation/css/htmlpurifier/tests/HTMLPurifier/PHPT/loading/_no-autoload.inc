<?php

/**
 * Tests if autoloading is off in HTML Purifier. If all tests pass, no output.
 */

if (function_exists('spl_autoload_register')) {
    $__v = spl_autoload_functions();
    assert('$__v == false || !in_array(array("HTMLPurifier_Bootstrap", "autoload"), $__v)');
} else {
    if (function_exists('__autoload')) {
        $__r = new ReflectionFunction('__autoload');
        assert('$__r->getFileName() != realpath("../library/HTMLPurifier.autoload.php")');
    }
}

// vim: et sw=4 sts=4
