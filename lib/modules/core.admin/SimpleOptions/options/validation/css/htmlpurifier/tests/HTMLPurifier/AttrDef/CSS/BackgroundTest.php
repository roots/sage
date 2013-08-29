<?php

class HTMLPurifier_AttrDef_CSS_BackgroundTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $config = HTMLPurifier_Config::createDefault();
        $this->def = new HTMLPurifier_AttrDef_CSS_Background($config);

        $valid = '#333 url("chess.png") repeat fixed 50% top';
        $this->assertDef($valid);
        $this->assertDef('url(\'chess.png\') #333 50% top repeat fixed', $valid);
        $this->assertDef(
            'rgb(34, 56, 33) url(chess.png) repeat fixed top',
            'rgb(34,56,33) url("chess.png") repeat fixed top'
        );

    }

}

// vim: et sw=4 sts=4
