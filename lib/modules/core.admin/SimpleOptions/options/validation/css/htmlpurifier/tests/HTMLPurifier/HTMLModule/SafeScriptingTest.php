<?php

class HTMLPurifier_HTMLModule_SafeScriptingTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.SafeScripting', array('http://localhost/foo.js'));
    }

    function testMinimal() {
        $this->assertResult(
            '<script></script>',
            ''
        );
    }

    function testGood() {
        $this->assertResult(
            '<script type="text/javascript" src="http://localhost/foo.js" />'
        );
    }

    function testBad() {
        $this->assertResult(
            '<script type="text/javascript" src="http://localhost/foobar.js" />',
            ''
        );
    }

}

// vim: et sw=4 sts=4
