<?php

class HTMLPurifier_AttrDef_CSS_FilterTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_CSS_Filter();

        $this->assertDef('none');

        $this->assertDef('alpha(opacity=0)');
        $this->assertDef('alpha(opacity=100)');
        $this->assertDef('alpha(opacity=50)');
        $this->assertDef('alpha(opacity=342)', 'alpha(opacity=100)');
        $this->assertDef('alpha(opacity=-23)', 'alpha(opacity=0)');

        $this->assertDef('alpha ( opacity = 0 )', 'alpha(opacity=0)');
        $this->assertDef('alpha(opacity=0,opacity=100)', 'alpha(opacity=0)');

        $this->assertDef('progid:DXImageTransform.Microsoft.Alpha(opacity=20)');

        $this->assertDef('progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)', false);

    }

}

// vim: et sw=4 sts=4
