<?php

class HTMLPurifier_AttrDef_CSS_FontTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $config = HTMLPurifier_Config::createDefault();
        $this->def = new HTMLPurifier_AttrDef_CSS_Font($config);

        // hodgepodge of usage cases from W3C spec, but " -> '
        $this->assertDef('12px/14px sans-serif');
        $this->assertDef('80% sans-serif');
        $this->assertDef("x-large/110% 'New Century Schoolbook', serif");
        $this->assertDef('bold italic large Palatino, serif');
        $this->assertDef('normal small-caps 120%/120% fantasy');
        $this->assertDef("300 italic 1.3em/1.7em 'FB Armada', sans-serif");
        $this->assertDef('600 9px Charcoal');
        $this->assertDef('600 9px/ 12px Charcoal', '600 9px/12px Charcoal');

        // spacing
        $this->assertDef('12px / 14px sans-serif', '12px/14px sans-serif');

        // system fonts
        $this->assertDef('menu');

        $this->assertDef('800', false);
        $this->assertDef('600 9px//12px Charcoal', false);

    }

}

// vim: et sw=4 sts=4
