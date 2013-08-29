--TEST--
DirectLex with domxml test
--SKIPIF--
<?php
if (!extension_loaded('dom')) {
    echo "skip - dom not available";
} elseif (!extension_loaded('domxml')) {
    echo "skip - domxml not loaded";
}
--FILE--
<?php
require '../library/HTMLPurifier.auto.php';
echo get_class(HTMLPurifier_Lexer::create(HTMLPurifier_Config::createDefault()));
--EXPECT--
HTMLPurifier_Lexer_DirectLex