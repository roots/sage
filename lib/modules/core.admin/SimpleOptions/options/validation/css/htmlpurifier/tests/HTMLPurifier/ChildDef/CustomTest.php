<?php

class HTMLPurifier_ChildDef_CustomTest extends HTMLPurifier_ChildDefHarness
{

    function test() {

        $this->obj = new HTMLPurifier_ChildDef_Custom('(a,b?,c*,d+,(a,b)*)');

        $this->assertEqual($this->obj->elements, array('a' => true,
            'b' => true, 'c' => true, 'd' => true));

        $this->assertResult('', false);
        $this->assertResult('<a /><a />', false);

        $this->assertResult('<a /><b /><c /><d /><a /><b />');
        $this->assertResult('<a /><d>Dob</d><a /><b>foo</b>'.
          '<a href="moo" /><b>foo</b>');

    }

    function testNesting() {
        $this->obj = new HTMLPurifier_ChildDef_Custom('(a,b,(c|d))+');
        $this->assertEqual($this->obj->elements, array('a' => true,
            'b' => true, 'c' => true, 'd' => true));
        $this->assertResult('', false);
        $this->assertResult('<a /><b /><c /><a /><b /><d />');
        $this->assertResult('<a /><b /><c /><d />', false);
    }

    function testNestedEitherOr() {
        $this->obj = new HTMLPurifier_ChildDef_Custom('b,(a|(c|d))+');
        $this->assertEqual($this->obj->elements, array('a' => true,
            'b' => true, 'c' => true, 'd' => true));
        $this->assertResult('', false);
        $this->assertResult('<b /><a /><c /><d />');
        $this->assertResult('<b /><d /><a /><a />');
        $this->assertResult('<b /><a />');
        $this->assertResult('<acd />', false);
    }

    function testNestedQuantifier() {
        $this->obj = new HTMLPurifier_ChildDef_Custom('(b,c+)*');
        $this->assertEqual($this->obj->elements, array('b' => true, 'c' => true));
        $this->assertResult('');
        $this->assertResult('<b /><c />');
        $this->assertResult('<b /><c /><c /><c />');
        $this->assertResult('<b /><c /><b /><c />');
        $this->assertResult('<b /><c /><b />', false);
    }

    function testEitherOr() {

        $this->obj = new HTMLPurifier_ChildDef_Custom('a|b');
        $this->assertEqual($this->obj->elements, array('a' => true, 'b' => true));
        $this->assertResult('', false);
        $this->assertResult('<a />');
        $this->assertResult('<b />');
        $this->assertResult('<a /><b />', false);

    }

    function testCommafication() {

        $this->obj = new HTMLPurifier_ChildDef_Custom('a,b');
        $this->assertEqual($this->obj->elements, array('a' => true, 'b' => true));
        $this->assertResult('<a /><b />');
        $this->assertResult('<ab />', false);

    }

    function testPcdata() {
        $this->obj = new HTMLPurifier_ChildDef_Custom('#PCDATA,a');
        $this->assertEqual($this->obj->elements, array('#PCDATA' => true, 'a' => true));
        $this->assertResult('foo<a />');
        $this->assertResult('<a />', false);
    }

    function testWhitespace() {
        $this->obj = new HTMLPurifier_ChildDef_Custom('a');
        $this->assertEqual($this->obj->elements, array('a' => true));
        $this->assertResult('foo<a />', false);
        $this->assertResult('<a />');
        $this->assertResult('   <a />');
    }

}

// vim: et sw=4 sts=4
