--TEST--
HTMLPurifier.auto.php using spl_autoload_register default
--SKIPIF--
<?php
if (!function_exists('spl_autoload_register')) {
    echo "skip - spl_autoload_register() not available";
}
--FILE--
<?php
spl_autoload_extensions(".php");
spl_autoload_register();

require '../library/HTMLPurifier.auto.php';
require 'HTMLPurifier/PHPT/loading/_autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!') . "
";

// purposely invoke standard autoload
$test = new default_load();

--EXPECT--
<b>Salsa!</b>
Default loaded
