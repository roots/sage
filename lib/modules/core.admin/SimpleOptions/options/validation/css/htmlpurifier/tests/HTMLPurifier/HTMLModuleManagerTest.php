<?php

class HTMLPurifier_HTMLModuleManagerTest extends HTMLPurifier_Harness
{

    protected function createManager() {
        $manager = new HTMLPurifier_HTMLModuleManager();

        $this->config->set('HTML.CustomDoctype', 'Blank');
        $manager->doctypes->register('Blank');

        $attrdef_nmtokens = new HTMLPurifier_AttrDef_HTML_Nmtokens();

        generate_mock_once('HTMLPurifier_AttrDef');
        $attrdef = new HTMLPurifier_AttrDefMock();
        $attrdef->setReturnValue('make', $attrdef_nmtokens);
        $manager->attrTypes->set('NMTOKENS', $attrdef);
        return $manager;
    }

    function test_addModule() {

        $manager = $this->createManager();

        // ...but we add user modules

        $common_module = new HTMLPurifier_HTMLModule();
        $common_module->name = 'Common';
        $common_module->attr_collections['Common'] = array('class' => 'NMTOKENS');
        $common_module->content_sets['Flow'] = 'Block | Inline';
        $manager->addModule($common_module);

        $structural_module = new HTMLPurifier_HTMLModule();
        $structural_module->name = 'Structural';
        $structural_module->addElement('p', 'Block', 'Inline', 'Common');
        $manager->addModule($structural_module);

        $formatting_module = new HTMLPurifier_HTMLModule();
        $formatting_module->name = 'Formatting';
        $formatting_module->addElement('em', 'Inline', 'Inline', 'Common');
        $manager->addModule($formatting_module);

        $unsafe_module = new HTMLPurifier_HTMLModule();
        $unsafe_module->name = 'Unsafe';
        $unsafe_module->safe = false;
        $unsafe_module->addElement('div', 'Block', 'Flow');
        $manager->addModule($unsafe_module);

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Trusted', false);
        $config->set('HTML.CustomDoctype', 'Blank');

        $manager->setup($config);

        $attrdef_nmtokens = new HTMLPurifier_AttrDef_HTML_Nmtokens();

        $p = new HTMLPurifier_ElementDef();
        $p->attr['class'] = $attrdef_nmtokens;
        $p->child = new HTMLPurifier_ChildDef_Optional(array('em', '#PCDATA'));
        $p->content_model = 'em | #PCDATA';
        $p->content_model_type = 'optional';
        $p->descendants_are_inline = true;

        $em = new HTMLPurifier_ElementDef();
        $em->attr['class'] = $attrdef_nmtokens;
        $em->child = new HTMLPurifier_ChildDef_Optional(array('em', '#PCDATA'));
        $em->content_model = 'em | #PCDATA';
        $em->content_model_type = 'optional';
        $em->descendants_are_inline = true;

        $this->assertEqual(
            array('p' => $p, 'em' => $em),
            $manager->getElements()
        );

        // test trusted parameter override

        $div = new HTMLPurifier_ElementDef();
        $div->child = new HTMLPurifier_ChildDef_Optional(array('p', 'div', 'em', '#PCDATA'));
        $div->content_model = 'p | div | em | #PCDATA';
        $div->content_model_type = 'optional';
        $div->descendants_are_inline = false;

        $this->assertEqual($div, $manager->getElement('div', true));

    }

    function testAllowedModules() {

        $manager = new HTMLPurifier_HTMLModuleManager();
        $manager->doctypes->register(
            'Fantasy Inventory 1.0', true,
            array('Weapons', 'Magic')
        );

        // register these modules so it doesn't blow up
        $weapons_module = new HTMLPurifier_HTMLModule();
        $weapons_module->name = 'Weapons';
        $manager->registerModule($weapons_module);

        $magic_module = new HTMLPurifier_HTMLModule();
        $magic_module->name = 'Magic';
        $manager->registerModule($magic_module);

        $config = HTMLPurifier_Config::create(array(
            'HTML.CustomDoctype' => 'Fantasy Inventory 1.0',
            'HTML.AllowedModules' => 'Weapons'
        ));
        $manager->setup($config);

        $this->assertTrue( isset($manager->modules['Weapons']));
        $this->assertFalse(isset($manager->modules['Magic']));

    }



}

// vim: et sw=4 sts=4
