--TEST--
HTMLPurifier.auto.php and HTMLPurifier.includes.php loading test
--FILE--
<?php
require '../library/HTMLPurifier.path.php';
require 'HTMLPurifier.includes.php';
require 'HTMLPurifier/PHPT/loading/_no-autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');
--EXPECT--
<b>Salsa!</b>