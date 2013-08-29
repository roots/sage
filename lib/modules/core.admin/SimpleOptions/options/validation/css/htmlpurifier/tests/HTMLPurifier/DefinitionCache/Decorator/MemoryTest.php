<?php

generate_mock_once('HTMLPurifier_DefinitionCache');

class HTMLPurifier_DefinitionCache_Decorator_MemoryTest extends HTMLPurifier_DefinitionCache_DecoratorHarness
{

    function setup() {
        $this->cache = new HTMLPurifier_DefinitionCache_Decorator_Memory();
        parent::setup();
    }

    function setupMockForSuccess($op) {
        $this->mock->expectOnce($op, array($this->def, $this->config));
        $this->mock->setReturnValue($op, true, array($this->def, $this->config));
        $this->mock->expectNever('get');
    }

    function setupMockForFailure($op) {
        $this->mock->expectOnce($op, array($this->def, $this->config));
        $this->mock->setReturnValue($op, false, array($this->def, $this->config));
        $this->mock->expectOnce('get', array($this->config));
    }

    function test_get() {
        $this->mock->expectOnce('get', array($this->config)); // only ONE call!
        $this->mock->setReturnValue('get', $this->def, array($this->config));
        $this->assertEqual($this->cache->get($this->config), $this->def);
        $this->assertEqual($this->cache->get($this->config), $this->def);
    }

    function test_set() {
        $this->setupMockForSuccess('set', 'get');
        $this->assertEqual($this->cache->set($this->def, $this->config), true);
        $this->assertEqual($this->cache->get($this->config), $this->def);
    }

    function test_set_failure() {
        $this->setupMockForFailure('set', 'get');
        $this->assertEqual($this->cache->set($this->def, $this->config), false);
        $this->cache->get($this->config);
    }

    function test_replace() {
        $this->setupMockForSuccess('replace', 'get');
        $this->assertEqual($this->cache->replace($this->def, $this->config), true);
        $this->assertEqual($this->cache->get($this->config), $this->def);
    }

    function test_replace_failure() {
        $this->setupMockForFailure('replace', 'get');
        $this->assertEqual($this->cache->replace($this->def, $this->config), false);
        $this->cache->get($this->config);
    }

    function test_add() {
        $this->setupMockForSuccess('add', 'get');
        $this->assertEqual($this->cache->add($this->def, $this->config), true);
        $this->assertEqual($this->cache->get($this->config), $this->def);
    }

    function test_add_failure() {
        $this->setupMockForFailure('add', 'get');
        $this->assertEqual($this->cache->add($this->def, $this->config), false);
        $this->cache->get($this->config);
    }

}

// vim: et sw=4 sts=4
