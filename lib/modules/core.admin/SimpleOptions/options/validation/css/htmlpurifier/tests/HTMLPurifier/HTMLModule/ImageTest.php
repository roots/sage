<?php

class HTMLPurifier_HTMLModule_ImageTest extends HTMLPurifier_HTMLModuleHarness
{


    function testNormal() {
        $this->assertResult('<img height="40" width="40" src="" alt="" />');
    }

    function testLengthTooLarge() {
        $this->assertResult(
            '<img height="40000" width="40000" src="" alt="" />',
            '<img height="1200" width="1200" src="" alt="" />'
        );
    }

    function testLengthPercentage() {
        $this->assertResult(
            '<img height="100%" width="100%" src="" alt="" />',
            '<img src="" alt="" />'
        );
    }

    function testLengthCustomMax() {
        $this->config->set('HTML.MaxImgLength', 20);
        $this->assertResult(
            '<img height="30" width="30" src="" alt="" />',
            '<img height="20" width="20" src="" alt="" />'
        );
    }

    function testLengthCrashFixDisabled() {
        $this->config->set('HTML.MaxImgLength', null);
        $this->assertResult(
            '<img height="100%" width="100%" src="" alt="" />'
        );
        $this->assertResult(
            '<img height="40000" width="40000" src="" alt="" />'
        );
    }

    function testLengthTrusted() {
        $this->config->set('HTML.Trusted', true);
        $this->assertResult(
            '<img height="100%" width="100%" src="" alt="" />'
        );
        $this->assertResult(
            '<img height="40000" width="40000" src="" alt="" />'
        );
    }

}

// vim: et sw=4 sts=4
