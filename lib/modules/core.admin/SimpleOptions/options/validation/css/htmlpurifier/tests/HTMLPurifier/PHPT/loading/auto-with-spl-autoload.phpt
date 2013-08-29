--TEST--
HTMLPurifier.auto.php using spl_autoload_register with user registration loading test
--SKIPIF--
<?php
if (!function_exists('spl_autoload_register')) {
    echo "skip - spl_autoload_register() not available";
}
--FILE--
<?php
function my_autoload($class) {
    echo "Autoloading $class...
";
    eval("class $class {}");
    return true;
}
class MyClass {
    public static function myAutoload($class) {
        if ($class == 'Foo') {
            echo "Special autoloading Foo...
";
            eval("class $class {}");
        }
    }
}

spl_autoload_register(array('MyClass', 'myAutoload'));
spl_autoload_register('my_autoload');

require '../library/HTMLPurifier.auto.php';
require 'HTMLPurifier/PHPT/loading/_autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!') . "
";

// purposely invoke older autoloads
$foo = new Foo();
$bar = new Bar();

--EXPECT--
<b>Salsa!</b>
Special autoloading Foo...
Autoloading Bar...