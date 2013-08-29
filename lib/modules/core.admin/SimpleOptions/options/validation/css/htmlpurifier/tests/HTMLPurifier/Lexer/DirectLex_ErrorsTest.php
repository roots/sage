<?php

class HTMLPurifier_Lexer_DirectLex_ErrorsTest extends HTMLPurifier_ErrorsHarness
{

    function invoke($input) {
        $lexer = new HTMLPurifier_Lexer_DirectLex();
        $lexer->tokenizeHTML($input, $this->config, $this->context);
    }

    function invokeAttr($input) {
        $lexer = new HTMLPurifier_Lexer_DirectLex();
        $lexer->parseAttributeString($input, $this->config, $this->context);
    }

    function testExtractBody() {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Extracted body');
        $this->invoke('<body>foo</body>');
    }

    function testUnclosedComment() {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Unclosed comment');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('<!-- >');
    }

    function testUnescapedLt() {
        $this->expectErrorCollection(E_NOTICE, 'Lexer: Unescaped lt');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('< foo>');
    }

    function testMissingGt() {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Missing gt');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('<a href=""');
    }

    // these are sub-errors, will only be thrown in context of collector

    function testMissingAttributeKey1() {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing attribute key');
        $this->invokeAttr('=""');
    }

    function testMissingAttributeKey2() {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing attribute key');
        $this->invokeAttr('foo="bar" =""');
    }

    function testMissingEndQuote() {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing end quote');
        $this->invokeAttr('src="foo');
    }

}

// vim: et sw=4 sts=4
