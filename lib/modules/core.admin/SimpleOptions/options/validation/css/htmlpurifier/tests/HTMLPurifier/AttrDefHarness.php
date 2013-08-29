<?php

class HTMLPurifier_AttrDefHarness extends HTMLPurifier_Harness
{

    protected $def;
    protected $context, $config;

    public function setUp() {
        $this->config = HTMLPurifier_Config::createDefault();
        $this->context = new HTMLPurifier_Context();
    }

    // cannot be used for accumulator
    function assertDef($string, $expect = true) {
        // $expect can be a string or bool
        $result = $this->def->validate($string, $this->config, $this->context);
        if ($expect === true) {
            $this->assertIdentical($string, $result);
        } else {
            $this->assertIdentical($expect, $result);
        }
    }

}

// vim: et sw=4 sts=4
