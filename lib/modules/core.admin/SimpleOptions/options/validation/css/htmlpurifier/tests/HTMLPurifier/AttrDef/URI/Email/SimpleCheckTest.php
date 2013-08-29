<?php

class HTMLPurifier_AttrDef_URI_Email_SimpleCheckTest
    extends HTMLPurifier_AttrDef_URI_EmailHarness
{

    function setUp() {
        $this->def = new HTMLPurifier_AttrDef_URI_Email_SimpleCheck();
    }

}

// vim: et sw=4 sts=4
