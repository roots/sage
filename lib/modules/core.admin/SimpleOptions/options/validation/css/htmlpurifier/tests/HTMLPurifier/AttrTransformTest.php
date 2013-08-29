<?php

Mock::generatePartial(
        'HTMLPurifier_AttrTransform',
        'HTMLPurifier_AttrTransformTestable',
        array('transform'));

class HTMLPurifier_AttrTransformTest extends HTMLPurifier_Harness
{

    function test_prependCSS() {

        $t = new HTMLPurifier_AttrTransformTestable();

        $attr = array();
        $t->prependCSS($attr, 'style:new;');
        $this->assertIdentical(array('style' => 'style:new;'), $attr);

        $attr = array('style' => 'style:original;');
        $t->prependCSS($attr, 'style:new;');
        $this->assertIdentical(array('style' => 'style:new;style:original;'), $attr);

        $attr = array('style' => 'style:original;', 'misc' => 'un-related');
        $t->prependCSS($attr, 'style:new;');
        $this->assertIdentical(array('style' => 'style:new;style:original;', 'misc' => 'un-related'), $attr);

    }

    function test_confiscateAttr() {

        $t = new HTMLPurifier_AttrTransformTestable();

        $attr = array('flavor' => 'sweet');
        $this->assertIdentical('sweet', $t->confiscateAttr($attr, 'flavor'));
        $this->assertIdentical(array(), $attr);

        $attr = array('flavor' => 'sweet');
        $this->assertIdentical(null, $t->confiscateAttr($attr, 'color'));
        $this->assertIdentical(array('flavor' => 'sweet'), $attr);

    }

}

// vim: et sw=4 sts=4
