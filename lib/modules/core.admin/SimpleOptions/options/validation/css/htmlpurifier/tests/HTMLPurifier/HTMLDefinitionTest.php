<?php

class HTMLPurifier_HTMLDefinitionTest extends HTMLPurifier_Harness
{

    function expectError($error = false, $message = '%s') {
        // Because we're testing a definition, it's vital that the cache
        // is turned off for tests that expect errors.
        $this->config->set('Cache.DefinitionImpl', null);
        parent::expectError($error);
    }

    function test_parseTinyMCEAllowedList() {

        $def = new HTMLPurifier_HTMLDefinition();

        // note: this is case-sensitive, but its config schema
        // counterpart is not. This is generally a good thing for users,
        // but it's a slight internal inconsistency

        $this->assertEqual(
            $def->parseTinyMCEAllowedList(''),
            array(array(), array())
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList('a,b,c'),
            array(array('a' => true, 'b' => true, 'c' => true), array())
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList('a[x|y|z]'),
            array(array('a' => true), array('a.x' => true, 'a.y' => true, 'a.z' => true))
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList('*[id]'),
            array(array(), array('*.id' => true))
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList('a[*]'),
            array(array('a' => true), array('a.*' => true))
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList('span[style],strong,a[href|title]'),
            array(array('span' => true, 'strong' => true, 'a' => true),
            array('span.style' => true, 'a.href' => true, 'a.title' => true))
        );

        $this->assertEqual(
            // alternate form:
            $def->parseTinyMCEAllowedList(
'span[style]
strong
a[href|title]
'),
            $val = array(array('span' => true, 'strong' => true, 'a' => true),
            array('span.style' => true, 'a.href' => true, 'a.title' => true))
        );

        $this->assertEqual(
            $def->parseTinyMCEAllowedList(' span [ style ], strong'."\n\t".'a[href | title]'),
            $val
        );

    }

    function test_Allowed() {

        $config1 = HTMLPurifier_Config::create(array(
            'HTML.AllowedElements' => array('b', 'i', 'p', 'a'),
            'HTML.AllowedAttributes' => array('a@href', '*@id')
        ));

        $config2 = HTMLPurifier_Config::create(array(
            'HTML.Allowed' => 'b,i,p,a[href],*[id]'
        ));

        $this->assertEqual($config1->getHTMLDefinition(), $config2->getHTMLDefinition());

    }

    function assertPurification_AllowedElements_p() {
        $this->assertPurification('<p><b>Jelly</b></p>', '<p>Jelly</p>');
    }

    function test_AllowedElements() {
        $this->config->set('HTML.AllowedElements', 'p');
        $this->assertPurification_AllowedElements_p();
    }

    function test_AllowedElements_multiple() {
        $this->config->set('HTML.AllowedElements', 'p,div');
        $this->assertPurification('<div><p><b>Jelly</b></p></div>', '<div><p>Jelly</p></div>');
    }

    function test_AllowedElements_invalidElement() {
        $this->config->set('HTML.AllowedElements', 'obviously_invalid,p');
        $this->expectError(new PatternExpectation("/Element 'obviously_invalid' is not supported/"));
        $this->assertPurification_AllowedElements_p();
    }

    function test_AllowedElements_invalidElement_xssAttempt() {
        $this->config->set('HTML.AllowedElements', '<script>,p');
        $this->expectError(new PatternExpectation("/Element '&lt;script&gt;' is not supported/"));
        $this->assertPurification_AllowedElements_p();
    }

    function test_AllowedElements_multipleInvalidElements() {
        $this->config->set('HTML.AllowedElements', 'dr-wiggles,dr-pepper,p');
        $this->expectError(new PatternExpectation("/Element 'dr-wiggles' is not supported/"));
        $this->expectError(new PatternExpectation("/Element 'dr-pepper' is not supported/"));
        $this->assertPurification_AllowedElements_p();
    }

    function assertPurification_AllowedAttributes_global_style() {
        $this->assertPurification(
            '<p style="font-weight:bold;" class="foo">Jelly</p><br style="clear:both;" />',
            '<p style="font-weight:bold;">Jelly</p><br style="clear:both;" />');
    }

    function test_AllowedAttributes_global_preferredSyntax() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'style');
        $this->assertPurification_AllowedAttributes_global_style();
    }

    function test_AllowedAttributes_global_verboseSyntax() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', '*@style');
        $this->assertPurification_AllowedAttributes_global_style();
    }

    function test_AllowedAttributes_global_discouragedSyntax() {
        // Emit errors eventually
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', '*.style');
        $this->assertPurification_AllowedAttributes_global_style();
    }

    function assertPurification_AllowedAttributes_local_p_style() {
        $this->assertPurification(
            '<p style="font-weight:bold;" class="foo">Jelly</p><br style="clear:both;" />',
            '<p style="font-weight:bold;">Jelly</p><br />');
    }

    function test_AllowedAttributes_local_preferredSyntax() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p@style');
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_AllowedAttributes_local_discouragedSyntax() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p.style');
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_AllowedAttributes_multiple() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p@style,br@class,title');
        $this->assertPurification(
            '<p style="font-weight:bold;" class="foo" title="foo">Jelly</p><br style="clear:both;" class="foo" title="foo" />',
            '<p style="font-weight:bold;" title="foo">Jelly</p><br class="foo" title="foo" />'
        );
    }

    function test_AllowedAttributes_local_invalidAttribute() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', array('p@style', 'p@<foo>'));
        $this->expectError(new PatternExpectation("/Attribute '&lt;foo&gt;' in element 'p' not supported/"));
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_AllowedAttributes_global_invalidAttribute() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', array('style', '<foo>'));
        $this->expectError(new PatternExpectation("/Global attribute '&lt;foo&gt;' is not supported in any elements/"));
        $this->assertPurification_AllowedAttributes_global_style();
    }

    function test_AllowedAttributes_local_invalidAttributeDueToMissingElement() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p.style,foo.style');
        $this->expectError(new PatternExpectation("/Cannot allow attribute 'style' if element 'foo' is not allowed\/supported/"));
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_AllowedAttributes_duplicate() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p.style,p@style');
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_AllowedAttributes_multipleErrors() {
        $this->config->set('HTML.AllowedElements', array('p', 'br'));
        $this->config->set('HTML.AllowedAttributes', 'p.style,foo.style,<foo>');
        $this->expectError(new PatternExpectation("/Cannot allow attribute 'style' if element 'foo' is not allowed\/supported/"));
        $this->expectError(new PatternExpectation("/Global attribute '&lt;foo&gt;' is not supported in any elements/"));
        $this->assertPurification_AllowedAttributes_local_p_style();
    }

    function test_ForbiddenElements() {
        $this->config->set('HTML.ForbiddenElements', 'b');
        $this->assertPurification('<b>b</b><i>i</i>', 'b<i>i</i>');
    }

    function test_ForbiddenElements_invalidElement() {
        $this->config->set('HTML.ForbiddenElements', 'obviously_incorrect');
        // no error!
        $this->assertPurification('<i>i</i>');
    }

    function assertPurification_ForbiddenAttributes_b_style() {
        $this->assertPurification(
            '<b style="float:left;">b</b><i style="float:left;">i</i>',
            '<b>b</b><i style="float:left;">i</i>');
    }

    function test_ForbiddenAttributes() {
        $this->config->set('HTML.ForbiddenAttributes', 'b@style');
        $this->assertPurification_ForbiddenAttributes_b_style();
    }

    function test_ForbiddenAttributes_incorrectSyntax() {
        $this->config->set('HTML.ForbiddenAttributes', 'b.style');
        $this->expectError("Error with b.style: tag.attr syntax not supported for HTML.ForbiddenAttributes; use tag@attr instead");
        $this->assertPurification('<b style="float:left;">Test</b>');
    }

    function test_ForbiddenAttributes_incorrectGlobalSyntax() {
        $this->config->set('HTML.ForbiddenAttributes', '*.style');
        $this->expectError("Error with *.style: *.attr syntax not supported for HTML.ForbiddenAttributes; use attr instead");
        $this->assertPurification('<b style="float:left;">Test</b>');
    }

    function assertPurification_ForbiddenAttributes_style() {
        $this->assertPurification(
            '<b class="foo" style="float:left;">b</b><i style="float:left;">i</i>',
            '<b class="foo">b</b><i>i</i>');
    }

    function test_ForbiddenAttributes_global() {
        $this->config->set('HTML.ForbiddenAttributes', 'style');
        $this->assertPurification_ForbiddenAttributes_style();
    }

    function test_ForbiddenAttributes_globalVerboseFormat() {
        $this->config->set('HTML.ForbiddenAttributes', '*@style');
        $this->assertPurification_ForbiddenAttributes_style();
    }

    function test_addAttribute() {

        $config = HTMLPurifier_Config::createDefault();
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('span', 'custom', 'Enum#attribute');

        $purifier = new HTMLPurifier($config);
        $input = '<span custom="attribute">Custom!</span>';
        $output = $purifier->purify($input);
        $this->assertIdentical($input, $output);

    }

    function test_addAttribute_multiple() {

        $config = HTMLPurifier_Config::createDefault();
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('span', 'custom', 'Enum#attribute');
        $def->addAttribute('span', 'foo', 'Text');

        $purifier = new HTMLPurifier($config);
        $input = '<span custom="attribute" foo="asdf">Custom!</span>';
        $output = $purifier->purify($input);
        $this->assertIdentical($input, $output);

    }

    function test_addElement() {

        $config = HTMLPurifier_Config::createDefault();
        $def = $config->getHTMLDefinition(true);
        $def->addElement('marquee', 'Inline', 'Inline', 'Common', array('width' => 'Length'));

        $purifier = new HTMLPurifier($config);
        $input = '<span><marquee width="50">Foobar</marquee></span>';
        $output = $purifier->purify($input);
        $this->assertIdentical($input, $output);

    }

    function test_injector() {
        generate_mock_once('HTMLPurifier_Injector');
        $injector = new HTMLPurifier_InjectorMock();
        $injector->name = 'MyInjector';
        $injector->setReturnValue('checkNeeded', false);

        $module = $this->config->getHTMLDefinition(true)->getAnonymousModule();
        $module->info_injector[] = $injector;

        $this->assertIdentical($this->config->getHTMLDefinition()->info_injector,
            array(
                'MyInjector' => $injector,
            )
        );
    }

    function test_injectorMissingNeeded() {
        generate_mock_once('HTMLPurifier_Injector');
        $injector = new HTMLPurifier_InjectorMock();
        $injector->name = 'MyInjector';
        $injector->setReturnValue('checkNeeded', 'a');

        $module = $this->config->getHTMLDefinition(true)->getAnonymousModule();
        $module->info_injector[] = $injector;

        $this->assertIdentical($this->config->getHTMLDefinition()->info_injector,
            array()
        );
    }

    function test_injectorIntegration() {
        $module = $this->config->getHTMLDefinition(true)->getAnonymousModule();
        $module->info_injector[] = 'Linkify';

        $this->assertIdentical(
            $this->config->getHTMLDefinition()->info_injector,
            array('Linkify' => new HTMLPurifier_Injector_Linkify())
        );
    }

    function test_injectorIntegrationFail() {
        $this->config->set('HTML.Allowed', 'p');

        $module = $this->config->getHTMLDefinition(true)->getAnonymousModule();
        $module->info_injector[] = 'Linkify';

        $this->assertIdentical(
            $this->config->getHTMLDefinition()->info_injector,
            array()
        );
    }

    function test_notAllowedRequiredAttributeError() {
        $this->expectError("Required attribute 'src' in element 'img' was not allowed, which means 'img' will not be allowed either");
        $this->config->set('HTML.Allowed', 'img[alt]');
        $this->config->getHTMLDefinition();
    }

}

// vim: et sw=4 sts=4
