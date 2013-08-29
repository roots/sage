--TEST--
HTMLPurifier.auto.php using spl_autoload_register with __autoload() already defined loading test
--SKIPIF--
<?php
if (!function_exists('spl_autoload_register')) {
    echo "skip - spl_autoload_register() not available";
}
--FILE--
<?php
function __autoload($class) {
    echo "Autoloading $class...
";
    eval("class $class {}");
}

require '../library/HTMLPurifier.auto.php';
require 'HTMLPurifier/PHPT/loading/_autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!') . "
";

// purposely invoke older autoload
$bar = new Bar();

--EXPECT--
<b>Salsa!</b>
Autoloading Bar...