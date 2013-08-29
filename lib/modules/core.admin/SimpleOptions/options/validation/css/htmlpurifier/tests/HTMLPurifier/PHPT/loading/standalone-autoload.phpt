--TEST--
HTMLPurifier.standalone.php loading test
--FILE--
<?php
require '../library/HTMLPurifier.standalone.php';
require '../library/HTMLPurifier.autoload.php';
require 'HTMLPurifier/PHPT/loading/_autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');
--EXPECT--
<b>Salsa!</b>