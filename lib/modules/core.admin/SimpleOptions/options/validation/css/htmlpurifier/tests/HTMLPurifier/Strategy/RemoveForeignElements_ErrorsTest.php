<?php

class HTMLPurifier_Strategy_RemoveForeignElements_ErrorsTest extends HTMLPurifier_Strategy_ErrorsHarness
{

    public function setup() {
        parent::setup();
        $this->config->set('HTML.TidyLevel', 'heavy');
    }

    protected function getStrategy() {
        return new HTMLPurifier_Strategy_RemoveForeignElements();
    }

    function testTagTransform() {
        $this->expectErrorCollection(E_NOTICE, 'Strategy_RemoveForeignElements: Tag transform', 'center');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('div', array('style' => 'text-align:center;'), 1));
        $this->invoke('<center>');
    }

    function testMissingRequiredAttr() {
        // a little fragile, since img has two required attributes
        $this->expectErrorCollection(E_ERROR, 'Strategy_RemoveForeignElements: Missing required attribute', 'alt');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Empty('img', array(), 1));
        $this->invoke('<img />');
    }

    function testForeignElementToText() {
        $this->config->set('Core.EscapeInvalidTags', true);
        $this->expectErrorCollection(E_WARNING, 'Strategy_RemoveForeignElements: Foreign element to text');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('invalid', array(), 1));
        $this->invoke('<invalid>');
    }

    function testForeignElementRemoved() {
        // uses $CurrentToken.Serialized
        $this->expectErrorCollection(E_ERROR, 'Strategy_RemoveForeignElements: Foreign element removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('invalid', array(), 1));
        $this->invoke('<invalid>');
    }

    function testCommentRemoved() {
        $this->expectErrorCollection(E_NOTICE, 'Strategy_RemoveForeignElements: Comment removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Comment(' test ', 1));
        $this->invoke('<!-- test -->');
    }

    function testTrailingHyphenInCommentRemoved() {
        $this->config->set('HTML.Trusted', true);
        $this->expectErrorCollection(E_NOTICE, 'Strategy_RemoveForeignElements: Trailing hyphen in comment removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Comment(' test ', 1));
        $this->invoke('<!-- test ---->');
    }

    function testDoubleHyphenInCommentRemoved() {
        $this->config->set('HTML.Trusted', true);
        $this->expectErrorCollection(E_NOTICE, 'Strategy_RemoveForeignElements: Hyphens in comment collapsed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Comment(' test - test - test ', 1));
        $this->invoke('<!-- test --- test -- test -->');
    }

    function testForeignMetaElementRemoved() {
        $this->collector->expectAt(0, 'send', array(E_ERROR, 'Strategy_RemoveForeignElements: Foreign meta element removed'));
        $this->collector->expectContextAt(0, 'CurrentToken', new HTMLPurifier_Token_Start('script', array(), 1));
        $this->collector->expectAt(1, 'send', array(E_ERROR, 'Strategy_RemoveForeignElements: Token removed to end', 'script'));
        $this->invoke('<script>asdf');
    }

}

// vim: et sw=4 sts=4
