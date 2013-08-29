<?php

/**
 * Tests if autoloading for HTML Purifier is enabled. If all tests pass,
 * output is blank.
 */

assert("!in_array(realpath('../library/HTMLPurifier/Filter/YouTube.php'), get_included_files())");
new HTMLPurifier_Filter_YouTube();
assert(" in_array(realpath('../library/HTMLPurifier.autoload.php'), get_included_files())");

// vim: et sw=4 sts=4
