<?php

class HTMLPurifier_AttrTransform_NameSyncTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_NameSync();
        $this->accumulator = new HTMLPurifier_IDAccumulator();
        $this->context->register('IDAccumulator', $this->accumulator);
        $this->config->set('Attr.EnableID', true);
    }

    function testEmpty() {
        $this->assertResult( array() );
    }

    function testAllowSame() {
        $this->assertResult(
            array('name' => 'free', 'id' => 'free')
        );
    }

    function testAllowDifferent() {
        $this->assertResult(
            array('name' => 'tryit', 'id' => 'thisgood')
        );
    }

    function testCheckName() {
        $this->accumulator->add('notok');
        $this->assertResult(
            array('name' => 'notok', 'id' => 'ok'),
            array('id' => 'ok')
        );
    }

}

// vim: et sw=4 sts=4
