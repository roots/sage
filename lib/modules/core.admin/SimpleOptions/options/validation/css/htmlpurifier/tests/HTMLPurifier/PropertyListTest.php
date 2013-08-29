<?php

class HTMLPurifier_PropertyListTest extends UnitTestCase
{

    function testBasic() {
        $plist = new HTMLPurifier_PropertyList();
        $plist->set('key', 'value');
        $this->assertIdentical($plist->get('key'), 'value');
    }

    function testNotFound() {
        $this->expectException(new HTMLPurifier_Exception("Key 'key' not found"));
        $plist = new HTMLPurifier_PropertyList();
        $plist->get('key');
    }

    function testRecursion() {
        $parent_plist = new HTMLPurifier_PropertyList();
        $parent_plist->set('key', 'value');
        $plist = new HTMLPurifier_PropertyList();
        $plist->setParent($parent_plist);
        $this->assertIdentical($plist->get('key'), 'value');
    }

    function testOverride() {
        $parent_plist = new HTMLPurifier_PropertyList();
        $parent_plist->set('key', 'value');
        $plist = new HTMLPurifier_PropertyList();
        $plist->setParent($parent_plist);
        $plist->set('key',  'value2');
        $this->assertIdentical($plist->get('key'), 'value2');
    }

    function testRecursionNotFound() {
        $this->expectException(new HTMLPurifier_Exception("Key 'key' not found"));
        $parent_plist = new HTMLPurifier_PropertyList();
        $plist = new HTMLPurifier_PropertyList();
        $plist->setParent($parent_plist);
        $this->assertIdentical($plist->get('key'), 'value');
    }

    function testHas() {
        $plist = new HTMLPurifier_PropertyList();
        $this->assertIdentical($plist->has('key'), false);
        $plist->set('key', 'value');
        $this->assertIdentical($plist->has('key'), true);
    }

    function testReset() {
        $plist = new HTMLPurifier_PropertyList();
        $plist->set('key1', 'value');
        $plist->set('key2', 'value');
        $plist->set('key3', 'value');
        $this->assertIdentical($plist->has('key1'), true);
        $this->assertIdentical($plist->has('key2'), true);
        $this->assertIdentical($plist->has('key3'), true);
        $plist->reset('key2');
        $this->assertIdentical($plist->has('key1'), true);
        $this->assertIdentical($plist->has('key2'), false);
        $this->assertIdentical($plist->has('key3'), true);
        $plist->reset();
        $this->assertIdentical($plist->has('key1'), false);
        $this->assertIdentical($plist->has('key2'), false);
        $this->assertIdentical($plist->has('key3'), false);
    }

    function testSquash() {
        $parent = new HTMLPurifier_PropertyList();
        $parent->set('key1', 'hidden');
        $parent->set('key2', 2);
        $plist = new HTMLPurifier_PropertyList($parent);
        $plist->set('key1', 1);
        $plist->set('key3', 3);
        $this->assertIdentical(
            $plist->squash(),
            array('key1' => 1, 'key2' => 2, 'key3' => 3)
        );
        // updates don't show up...
        $plist->set('key2', 22);
        $this->assertIdentical(
            $plist->squash(),
            array('key1' => 1, 'key2' => 2, 'key3' => 3)
        );
        // until you force
        $this->assertIdentical(
            $plist->squash(true),
            array('key1' => 1, 'key2' => 22, 'key3' => 3)
        );
    }
}

// vim: et sw=4 sts=4
