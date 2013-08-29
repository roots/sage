<?php

class HTMLPurifier_HTMLModule_ProprietaryTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.Proprietary', true);
    }

    function testMarquee() {
        $this->assertResult(
            '<span><marquee
                width="20%"
                height="34"
                direction="left"
                behavior="alternate"
                scrolldelay="3"
                scrollamount="5"
                loop="4"
                bgcolor="#FF0000"
                hspace="5"
                vspace="3"
                ><div>Block</div><span>Inline</span>Text</marquee></span>'
        );
    }

}

// vim: et sw=4 sts=4
