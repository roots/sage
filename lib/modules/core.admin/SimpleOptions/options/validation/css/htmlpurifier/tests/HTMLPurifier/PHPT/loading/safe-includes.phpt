--TEST--
HTMLPurifier.safe-includes.php loading test
--FILE--
<?php
require_once '../library/HTMLPurifier.php'; // Tests for require_once
require_once '../library/HTMLPurifier.safe-includes.php';
require 'HTMLPurifier/PHPT/loading/_no-autoload.inc';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $purifier->purify('<b>Salsa!');
--EXPECT--
<b>Salsa!</b>