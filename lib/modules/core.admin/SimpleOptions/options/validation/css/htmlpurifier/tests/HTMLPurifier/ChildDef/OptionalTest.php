<?php

class HTMLPurifier_ChildDef_OptionalTest extends HTMLPurifier_ChildDefHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_Optional('b | i');
    }

    function testBasicUsage() {
        $this->assertResult('<b>Bold text</b><img />', '<b>Bold text</b>');
    }

    function testRemoveForbiddenText() {
        $this->assertResult('Not allowed text', '');
    }

    function testEmpty() {
        $this->assertResult('');
    }

    function testWhitespace() {
        $this->assertResult(' ');
    }

    function testMultipleWhitespace() {
        $this->assertResult('    ');
    }

}

// vim: et sw=4 sts=4
