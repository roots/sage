--TEST--
HTMLPurifier.standalone.php loading test
--FILE--
<?php
require '../library/HTMLPurifier.standalone.php';
require 'HTMLPurifier/Filter/YouTube.php';
require 'HTMLPurifier/PHPT/loading/_no-autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');
assert('in_array(realpath("../library/standalone/HTMLPurifier/Filter/YouTube.php"), get_included_files())');
--EXPECT--
<b>Salsa!</b>