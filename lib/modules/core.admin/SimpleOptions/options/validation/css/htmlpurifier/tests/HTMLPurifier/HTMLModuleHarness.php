<?php

class HTMLPurifier_HTMLModuleHarness extends HTMLPurifier_StrategyHarness
{
    function setup() {
        parent::setup();
        $this->obj = new HTMLPurifier_Strategy_Core();
    }
}

// vim: et sw=4 sts=4
