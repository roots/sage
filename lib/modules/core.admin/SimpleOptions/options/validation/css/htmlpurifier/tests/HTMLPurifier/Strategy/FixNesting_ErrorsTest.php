<?php

class HTMLPurifier_Strategy_FixNesting_ErrorsTest extends HTMLPurifier_Strategy_ErrorsHarness
{

    protected function getStrategy() {
        return new HTMLPurifier_Strategy_FixNesting();
    }

    function testNodeRemoved() {
        $this->expectErrorCollection(E_ERROR, 'Strategy_FixNesting: Node removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('ul', array(), 1));
        $this->invoke('<ul></ul>');
    }

    function testNodeExcluded() {
        $this->expectErrorCollection(E_ERROR, 'Strategy_FixNesting: Node excluded');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('a', array(), 2));
        $this->invoke("<a>\n<a></a></a>");
    }

    function testNodeReorganized() {
        $this->expectErrorCollection(E_WARNING, 'Strategy_FixNesting: Node reorganized');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('span', array(), 1));
        $this->invoke("<span>Valid<div>Invalid</div></span>");
    }

    function testNoNodeReorganizedForEmptyNode() {
        $this->expectNoErrorCollection();
        $this->invoke("<span></span>");
    }

    function testNodeContentsRemoved() {
        $this->expectErrorCollection(E_ERROR, 'Strategy_FixNesting: Node contents removed');
        $this->expectContext('CurrentToken', new HTMLPurifier_Token_Start('span', array(), 1));
        $this->invoke("<span><div></div></span>");
    }

}

// vim: et sw=4 sts=4
