<?php

class HTMLPurifier_InjectorHarness extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
    }

}

// vim: et sw=4 sts=4
