<?php

class HTMLPurifier_HTMLModule_ScriptingTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.Trusted', true);
        $this->config->set('Output.CommentScriptContents', false);
    }

    function testDefaultRemoval() {
        $this->config->set('HTML.Trusted', false);
        $this->assertResult(
            '<script type="text/javascript">foo();</script>', ''
        );
    }

    function testPreserve() {
        $this->assertResult(
            '<script type="text/javascript">foo();</script>'
        );
    }

    function testCDATAEnclosure() {
        $this->assertResult(
'<script type="text/javascript">//<![CDATA[
alert("<This is compatible with XHTML>");
//]]></script>'
        );
    }

    function testAllAttributes() {
        $this->assertResult(
            '<script
                defer="defer"
                src="test.js"
                type="text/javascript"
            >PCDATA</script>'
        );
    }

    function testUnsupportedAttributes() {
        $this->assertResult(
            '<script
                type="text/javascript"
                charset="utf-8"
            >PCDATA</script>',
            '<script type="text/javascript">PCDATA</script>'
        );
    }

}

// vim: et sw=4 sts=4
