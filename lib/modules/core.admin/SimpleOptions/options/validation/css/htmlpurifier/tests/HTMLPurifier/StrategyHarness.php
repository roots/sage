<?php

class HTMLPurifier_StrategyHarness extends HTMLPurifier_ComplexHarness
{

    function setUp() {
        parent::setUp();
        $this->func      = 'execute';
        $this->to_tokens = true;
        $this->to_html   = true;
    }

}

// vim: et sw=4 sts=4
