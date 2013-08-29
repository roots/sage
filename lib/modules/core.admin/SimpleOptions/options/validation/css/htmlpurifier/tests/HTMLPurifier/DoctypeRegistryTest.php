<?php

class HTMLPurifier_DoctypeRegistryTest extends HTMLPurifier_Harness
{

    function test_register() {

        $registry = new HTMLPurifier_DoctypeRegistry();

        $d = $registry->register(
            $name = 'XHTML 1.0 Transitional',
            $xml = true,
            $modules = array('module-one', 'module-two'),
            $tidyModules = array('lenient-module'),
            $aliases = array('X10T')
        );

        $d2 = new HTMLPurifier_Doctype($name, $xml, $modules, $tidyModules, $aliases);

        $this->assertIdentical($d, $d2);
        $this->assertSame($d, $registry->get('XHTML 1.0 Transitional'));

        // test shorthand
        $d = $registry->register(
            $name = 'XHTML 1.0 Strict', true, 'module', 'Tidy', 'X10S'
        );
        $d2 = new HTMLPurifier_Doctype($name, true, array('module'), array('Tidy'), array('X10S'));

        $this->assertIdentical($d, $d2);

    }

    function test_get() {

        // see also alias and register tests

        $registry = new HTMLPurifier_DoctypeRegistry();

        $this->expectError('Doctype XHTML 2.0 does not exist');
        $registry->get('XHTML 2.0');

        // prevent XSS
        $this->expectError('Doctype &lt;foo&gt; does not exist');
        $registry->get('<foo>');

    }

    function testAliases() {

        $registry = new HTMLPurifier_DoctypeRegistry();

        $d1 = $registry->register('Doc1', true, array(), array(), array('1'));

        $this->assertSame($d1, $registry->get('Doc1'));
        $this->assertSame($d1, $registry->get('1'));

        $d2 = $registry->register('Doc2', true, array(), array(), array('2'));

        $this->assertSame($d2, $registry->get('Doc2'));
        $this->assertSame($d2, $registry->get('2'));

        $d3 = $registry->register('1', true, array(), array(), array());

        // literal name overrides alias
        $this->assertSame($d3, $registry->get('1'));

        $d4 = $registry->register('One', true, array(), array(), array('1'));

        $this->assertSame($d4, $registry->get('One'));
        // still it overrides
        $this->assertSame($d3, $registry->get('1'));

    }

}

// vim: et sw=4 sts=4
