--TEST--
Error with zend.ze1_compatibility_mode test
--PRESKIPIF--
<?php
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
    echo 'skip - ze1_compatibility_mode not present in PHP 5.3 or later';
}
--INI--
zend.ze1_compatibility_mode = 1
--FILE--
<?php
require '../library/HTMLPurifier.auto.php';
--EXPECTF--
Fatal error: HTML Purifier is not compatible with zend.ze1_compatibility_mode; please turn it off in %s
