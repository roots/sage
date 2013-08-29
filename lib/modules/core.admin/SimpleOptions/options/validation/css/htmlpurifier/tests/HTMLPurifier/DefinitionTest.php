<?php

class HTMLPurifier_DefinitionTest extends HTMLPurifier_Harness
{
    function test_setup() {
        $def = new HTMLPurifier_DefinitionTestable();
        $config = HTMLPurifier_Config::createDefault();
        $def->expectOnce('doSetup', array($config));
        $def->setup($config);
    }
    function test_setup_redundant() {
        $def = new HTMLPurifier_DefinitionTestable();
        $config = HTMLPurifier_Config::createDefault();
        $def->expectNever('doSetup');
        $def->setup = true;
        $def->setup($config);
    }
}

// vim: et sw=4 sts=4
