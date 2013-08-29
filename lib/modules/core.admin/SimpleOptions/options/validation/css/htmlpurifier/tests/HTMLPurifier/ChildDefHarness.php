<?php

class HTMLPurifier_ChildDefHarness extends HTMLPurifier_ComplexHarness
{

    public function setUp() {
        parent::setUp();
        $this->obj       = null;
        $this->func      = 'validateChildren';
        $this->to_tokens = true;
        $this->to_html   = true;
    }

}

// vim: et sw=4 sts=4
