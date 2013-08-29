--TEST--
Error when registering autoload with non-static autoload already on SPL stack
--SKIPIF--
<?php
if (!function_exists('spl_autoload_register')) {
    echo "skip - spl_autoload_register() not available";
}
if (version_compare(PHP_VERSION, '5.2.11', '>=')) {
    echo "skip - non-buggy version of PHP";
}
--FILE--
<?php
class NotStatic
{
    public function autoload($class) {
        echo "Autoloading... $class" . PHP_EOL;
        eval("class $class {}");
    }
}

$obj = new NotStatic();
spl_autoload_register(array($obj, 'autoload'));

try {
    require '../library/HTMLPurifier.auto.php';
} catch (Exception $e) {
    echo 'Caught error gracefully';
    assert('strpos($e->getMessage(), "44144") !== false');
}

--EXPECT--
Caught error gracefully
