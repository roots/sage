--TEST--
HTMLPurifier.auto.php without spl_autoload_register but with userland
__autoload() defined test
--SKIPIF--
<?php
if (function_exists('spl_autoload_register')) {
    echo "skip - spl_autoload_register() available";
}
--FILE--
<?php
function __autoload($class) {
    echo "Autoloading $class...
";
    eval("class $class {}");
}
require '../library/HTMLPurifier.auto.php';
require 'HTMLPurifier/PHPT/loading/_no-autoload.inc';
$purifier = new HTMLPurifier();

--EXPECT--
Autoloading HTMLPurifier...