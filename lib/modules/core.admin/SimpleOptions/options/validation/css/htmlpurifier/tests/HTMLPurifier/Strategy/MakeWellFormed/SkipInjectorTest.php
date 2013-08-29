<?php

class HTMLPurifier_Strategy_MakeWellFormed_SkipInjectorTest extends HTMLPurifier_StrategyHarness
{
    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_SkipInjector()
        ));
    }
    function testEmpty() {
        $this->assertResult('');
    }
    function testMultiply() {
        $this->assertResult('<br />', '<br /><br />');
    }
    function testMultiplyMultiply() {
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_SkipInjector(),
            new HTMLPurifier_Strategy_MakeWellFormed_SkipInjector()
        ));
        $this->assertResult('<br />', '<br /><br /><br /><br />');
    }
}

// vim: et sw=4 sts=4
