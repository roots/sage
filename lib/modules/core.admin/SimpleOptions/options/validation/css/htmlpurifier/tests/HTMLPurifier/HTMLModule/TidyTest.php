<?php

Mock::generatePartial(
    'HTMLPurifier_HTMLModule_Tidy',
    'HTMLPurifier_HTMLModule_Tidy_TestForConstruct',
    array('makeFixes', 'makeFixesForLevel', 'populate')
);

class HTMLPurifier_HTMLModule_TidyTest extends HTMLPurifier_Harness
{

    function test_getFixesForLevel() {

        $module = new HTMLPurifier_HTMLModule_Tidy();
        $module->fixesForLevel['light'][]  = 'light-fix';
        $module->fixesForLevel['medium'][] = 'medium-fix';
        $module->fixesForLevel['heavy'][]  = 'heavy-fix';

        $this->assertIdentical(
            array(),
            $module->getFixesForLevel('none')
        );
        $this->assertIdentical(
            array('light-fix' => true),
            $module->getFixesForLevel('light')
        );
        $this->assertIdentical(
            array('light-fix' => true, 'medium-fix' => true),
            $module->getFixesForLevel('medium')
        );
        $this->assertIdentical(
            array('light-fix' => true, 'medium-fix' => true, 'heavy-fix' => true),
            $module->getFixesForLevel('heavy')
        );

        $this->expectError('Tidy level turbo not recognized');
        $module->getFixesForLevel('turbo');

    }

    function test_setup() {

        $i = 0; // counter, helps us isolate expectations

        // initialize partial mock
        $module = new HTMLPurifier_HTMLModule_Tidy_TestForConstruct();
        $module->fixesForLevel['light']  = array('light-fix-1', 'light-fix-2');
        $module->fixesForLevel['medium'] = array('medium-fix-1', 'medium-fix-2');
        $module->fixesForLevel['heavy']  = array('heavy-fix-1', 'heavy-fix-2');

        $j = 0;
        $fixes = array(
            'light-fix-1'  => $lf1 = $j++,
            'light-fix-2'  => $lf2 = $j++,
            'medium-fix-1' => $mf1 = $j++,
            'medium-fix-2' => $mf2 = $j++,
            'heavy-fix-1'  => $hf1 = $j++,
            'heavy-fix-2'  => $hf2 = $j++
        );
        $module->setReturnValue('makeFixes', $fixes);

        $config = HTMLPurifier_Config::create(array(
            'HTML.TidyLevel' => 'none'
        ));
        $module->expectAt($i++, 'populate', array(array()));
        $module->setup($config);

        // basic levels

        $config = HTMLPurifier_Config::create(array(
            'HTML.TidyLevel' => 'light'
        ));
        $module->expectAt($i++, 'populate', array(array(
            'light-fix-1' => $lf1,
            'light-fix-2' => $lf2
        )));
        $module->setup($config);

        $config = HTMLPurifier_Config::create(array(
            'HTML.TidyLevel' => 'heavy'
        ));
        $module->expectAt($i++, 'populate', array(array(
            'light-fix-1'  => $lf1,
            'light-fix-2'  => $lf2,
            'medium-fix-1' => $mf1,
            'medium-fix-2' => $mf2,
            'heavy-fix-1'  => $hf1,
            'heavy-fix-2'  => $hf2
        )));
        $module->setup($config);

        // fine grained tuning

        $config = HTMLPurifier_Config::create(array(
            'HTML.TidyLevel' => 'none',
            'HTML.TidyAdd'   => array('light-fix-1', 'medium-fix-1')
        ));
        $module->expectAt($i++, 'populate', array(array(
            'light-fix-1' => $lf1,
            'medium-fix-1' => $mf1
        )));
        $module->setup($config);

        $config = HTMLPurifier_Config::create(array(
            'HTML.TidyLevel' => 'medium',
            'HTML.TidyRemove'   => array('light-fix-1', 'medium-fix-1')
        ));
        $module->expectAt($i++, 'populate', array(array(
            'light-fix-2' => $lf2,
            'medium-fix-2' => $mf2
        )));
        $module->setup($config);

    }

    function test_makeFixesForLevel() {

        $module = new HTMLPurifier_HTMLModule_Tidy();
        $module->defaultLevel = 'heavy';

        $module->makeFixesForLevel(array(
            'fix-1' => 0,
            'fix-2' => 1,
            'fix-3' => 2
        ));

        $this->assertIdentical($module->fixesForLevel['heavy'], array('fix-1', 'fix-2', 'fix-3'));
        $this->assertIdentical($module->fixesForLevel['medium'], array());
        $this->assertIdentical($module->fixesForLevel['light'], array());

    }
    function test_makeFixesForLevel_undefinedLevel() {

        $module = new HTMLPurifier_HTMLModule_Tidy();
        $module->defaultLevel = 'bananas';

        $this->expectError('Default level bananas does not exist');

        $module->makeFixesForLevel(array(
            'fix-1' => 0
        ));

    }

    function test_getFixType() {

        // syntax needs documenting

        $module = new HTMLPurifier_HTMLModule_Tidy();

        $this->assertIdentical(
            $module->getFixType('a'),
            array('tag_transform', array('element' => 'a'))
        );

        $this->assertIdentical(
            $module->getFixType('a@href'),
            $reuse = array('attr_transform_pre', array('element' => 'a', 'attr' => 'href'))
        );

        $this->assertIdentical(
            $module->getFixType('a@href#pre'),
            $reuse
        );

        $this->assertIdentical(
            $module->getFixType('a@href#post'),
            array('attr_transform_post', array('element' => 'a', 'attr' => 'href'))
        );

        $this->assertIdentical(
            $module->getFixType('xml:foo@xml:bar'),
            array('attr_transform_pre', array('element' => 'xml:foo', 'attr' => 'xml:bar'))
        );

        $this->assertIdentical(
            $module->getFixType('blockquote#child'),
            array('child', array('element' => 'blockquote'))
        );

        $this->assertIdentical(
            $module->getFixType('@lang'),
            array('attr_transform_pre', array('attr' => 'lang'))
        );

        $this->assertIdentical(
            $module->getFixType('@lang#post'),
            array('attr_transform_post', array('attr' => 'lang'))
        );

    }

    function test_populate() {

        $i = 0;

        $module = new HTMLPurifier_HTMLModule_Tidy();
        $module->populate(array(
            'element' => $element = $i++,
            'element@attr' => $attr = $i++,
            'element@attr#post' => $attr_post = $i++,
            'element#child' => $child = $i++,
            'element#content_model_type' => $content_model_type = $i++,
            '@attr' => $global_attr = $i++,
            '@attr#post' => $global_attr_post = $i++
        ));

        $module2 = new HTMLPurifier_HTMLModule_Tidy();
        $e = $module2->addBlankElement('element');
        $e->attr_transform_pre['attr'] = $attr;
        $e->attr_transform_post['attr'] = $attr_post;
        $e->child = $child;
        $e->content_model_type = $content_model_type;
        $module2->info_tag_transform['element'] = $element;
        $module2->info_attr_transform_pre['attr'] = $global_attr;
        $module2->info_attr_transform_post['attr'] = $global_attr_post;

        $this->assertEqual($module, $module2);

    }

}

// vim: et sw=4 sts=4
