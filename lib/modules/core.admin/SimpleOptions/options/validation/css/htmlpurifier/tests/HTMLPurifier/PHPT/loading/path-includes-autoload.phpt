--TEST--
HTMLPurifier.path.php, HTMLPurifier.includes.php and HTMLPurifier.autoload.php loading test
--FILE--
<?php
require '../library/HTMLPurifier.path.php';
require 'HTMLPurifier.includes.php';
require 'HTMLPurifier.autoload.php';
require 'HTMLPurifier/PHPT/loading/_autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');

--EXPECT--
<b>Salsa!</b>