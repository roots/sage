--TEST--
HTMLPurifier.standalone.php with HTMLPURIFIER_PREFIX loading test
--FILE--
<?php
define('HTMLPURIFIER_PREFIX', realpath('../library'));
require '../library/HTMLPurifier.path.php';
require 'HTMLPurifier.standalone.php';
require 'HTMLPurifier/Filter/YouTube.php';
require 'HTMLPurifier/PHPT/loading/_no-autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');
assert('in_array(realpath("../library/HTMLPurifier/Filter/YouTube.php"), get_included_files())');
--EXPECT--
<b>Salsa!</b>