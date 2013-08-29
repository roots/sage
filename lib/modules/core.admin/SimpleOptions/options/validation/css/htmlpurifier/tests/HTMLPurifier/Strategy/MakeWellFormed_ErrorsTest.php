<?php

class HTMLPurifier_Strategy_MakeWellFormed_ErrorsTest extends HTMLPurifier_Strategy_ErrorsHarness
{

    protected function getStrategy() {
        return new HTMLPurifier_Strategy_MakeWellFormed();
    }

    function testUnnecessaryEndTagRemoved() {
        $this->expectErrorCollection(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_End('b', array(), 1, 0));
        $this->invoke('</b>');
    }

    function testUnnecessaryEndTagToText() {
        $this->config->set('Core.EscapeInvalidTags', true);
        $this->expectErrorCollection(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag to text');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_End('b', array(), 1, 0));
        $this->invoke('</b>');
    }

    function testTagAutoclose() {
        $this->expectErrorCollection(E_NOTICE, 'Strategy_MakeWellFormed: Tag auto closed', new HTMLPurifier_Token_Start('p', array(), 1, 0));
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('div', array(), 1, 6));
        $this->invoke('<p>Foo<div>Bar</div>');
    }

    function testTagCarryOver() {
        $b = new HTMLPurifier_Token_Start('b', array(), 1, 0);
        $this->expectErrorCollection(E_NOTICE, 'Strategy_MakeWellFormed: Tag carryover', $b);
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('div', array(), 1, 6));
        $this->invoke('<b>Foo<div>Bar</div>');
    }

    function testStrayEndTagRemoved() {
        $this->expectErrorCollection(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_End('b', array(), 1, 3));
        $this->invoke('<i></b></i>');
    }

    function testStrayEndTagToText() {
        $this->config->set('Core.EscapeInvalidTags', true);
        $this->expectErrorCollection(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag to text');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_End('b', array(), 1, 3));
        $this->invoke('<i></b></i>');
    }

    function testTagClosedByElementEnd() {
        $this->expectErrorCollection(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by element end', new HTMLPurifier_Token_Start('b', array(), 1, 3));
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_End('i', array(), 1, 12));
        $this->invoke('<i><b>Foobar</i>');
    }

    function testTagClosedByDocumentEnd() {
        $this->expectErrorCollection(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by document end', new HTMLPurifier_Token_Start('b', array(), 1, 0));
        $this->invoke('<b>Foobar');
    }

}

// vim: et sw=4 sts=4
