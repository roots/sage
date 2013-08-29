<?php

class HTMLPurifier_HTMLModuleTest extends HTMLPurifier_Harness
{

    function test_addElementToContentSet() {

        $module = new HTMLPurifier_HTMLModule();

        $module->addElementToContentSet('b', 'Inline');
        $this->assertIdentical($module->content_sets, array('Inline' => 'b'));

        $module->addElementToContentSet('i', 'Inline');
        $this->assertIdentical($module->content_sets, array('Inline' => 'b | i'));

    }

    function test_addElement() {

        $module = new HTMLPurifier_HTMLModule();
        $def = $module->addElement(
            'a', 'Inline', 'Optional: #PCDATA', array('Common'),
            array(
                'href' => 'URI'
            )
        );

        $module2 = new HTMLPurifier_HTMLModule();
        $def2 = new HTMLPurifier_ElementDef();
        $def2->content_model = '#PCDATA';
        $def2->content_model_type = 'optional';
        $def2->attr = array(
            'href' => 'URI',
            0 => array('Common')
        );
        $module2->info['a'] = $def2;
        $module2->elements = array('a');
        $module2->content_sets['Inline'] = 'a';

        $this->assertIdentical($module, $module2);
        $this->assertIdentical($def, $def2);
        $this->assertReference($def, $module->info['a']);

    }

    function test_parseContents() {

        $module = new HTMLPurifier_HTMLModule();

        // pre-defined templates
        $this->assertIdentical(
            $module->parseContents('Inline'),
            array('optional', 'Inline | #PCDATA')
        );
        $this->assertIdentical(
            $module->parseContents('Flow'),
            array('optional', 'Flow | #PCDATA')
        );
        $this->assertIdentical(
            $module->parseContents('Empty'),
            array('empty', '')
        );

        // normalization procedures
        $this->assertIdentical(
            $module->parseContents('optional: a'),
            array('optional', 'a')
        );
        $this->assertIdentical(
            $module->parseContents('OPTIONAL :a'),
            array('optional', 'a')
        );
        $this->assertIdentical(
            $module->parseContents('Optional: a'),
            array('optional', 'a')
        );

        // others
        $this->assertIdentical(
            $module->parseContents('Optional: a | b | c'),
            array('optional', 'a | b | c')
        );

        // object pass-through
        generate_mock_once('HTMLPurifier_AttrDef');
        $this->assertIdentical(
            $module->parseContents(new HTMLPurifier_AttrDefMock()),
            array(null, null)
        );

    }

    function test_mergeInAttrIncludes() {

        $module = new HTMLPurifier_HTMLModule();

        $attr = array();
        $module->mergeInAttrIncludes($attr, 'Common');
        $this->assertIdentical($attr, array(0 => array('Common')));

        $attr = array('a' => 'b');
        $module->mergeInAttrIncludes($attr, array('Common', 'Good'));
        $this->assertIdentical($attr, array('a' => 'b', 0 => array('Common', 'Good')));

    }

    function test_addBlankElement() {

        $module = new HTMLPurifier_HTMLModule();
        $def = $module->addBlankElement('a');

        $def2 = new HTMLPurifier_ElementDef();
        $def2->standalone = false;

        $this->assertReference($module->info['a'], $def);
        $this->assertIdentical($def, $def2);

    }

    function test_makeLookup() {

        $module = new HTMLPurifier_HTMLModule();

        $this->assertIdentical(
            $module->makeLookup('foo'),
            array('foo' => true)
        );
        $this->assertIdentical(
            $module->makeLookup(array('foo')),
            array('foo' => true)
        );

        $this->assertIdentical(
            $module->makeLookup('foo', 'two'),
            array('foo' => true, 'two' => true)
        );
        $this->assertIdentical(
            $module->makeLookup(array('foo', 'two')),
            array('foo' => true, 'two' => true)
        );

    }

}

// vim: et sw=4 sts=4
