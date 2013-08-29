--TEST--
UTF-8 smoketest
--FILE--
<?php
require '../library/HTMLPurifier.auto.php';
$purifier = new HTMLPurifier();
echo $purifier->purify('太極拳, ЊЎЖ, لمنس');
--EXPECT--
太極拳, ЊЎЖ, لمنس