<?php

class HTMLPurifier_AttrValidator_ErrorsTest extends HTMLPurifier_ErrorsHarness
{

    public function setup() {
        parent::setup();
        $config = HTMLPurifier_Config::createDefault();
        $this->language = HTMLPurifier_LanguageFactory::instance()->create($config, $this->context);
        $this->context->register('Locale', $this->language);
        $this->collector = new HTMLPurifier_ErrorCollector($this->context);
        $this->context->register('Generator', new HTMLPurifier_Generator($config, $this->context));
    }

    protected function invoke($input) {
        $validator = new HTMLPurifier_AttrValidator();
        $validator->validateToken($input, $this->config, $this->context);
    }

    function testAttributesTransformedGlobalPre() {
        $def = $this->config->getHTMLDefinition(true);
        generate_mock_once('HTMLPurifier_AttrTransform');
        $transform = new HTMLPurifier_AttrTransformMock();
        $input = array('original' => 'value');
        $output = array('class' => 'value'); // must be valid
        $transform->setReturnValue('transform', $output, array($input, new AnythingExpectation(), new AnythingExpectation()));
        $def->info_attr_transform_pre[] = $transform;

        $token = new HTMLPurifier_Token_Start('span', $input, 1);
        $this->invoke($token);

        $result = $this->collector->getRaw();
        $expect = array(
            array(1, E_NOTICE, 'Attributes on <span> transformed from original to class', array()),
        );
        $this->assertIdentical($result, $expect);
    }

    function testAttributesTransformedLocalPre() {
        $this->config->set('HTML.TidyLevel', 'heavy');
        $input = array('align' => 'right');
        $output = array('style' => 'text-align:right;');
        $token = new HTMLPurifier_Token_Start('p', $input, 1);
        $this->invoke($token);
        $result = $this->collector->getRaw();
        $expect = array(
            array(1, E_NOTICE, 'Attributes on <p> transformed from align to style', array()),
        );
        $this->assertIdentical($result, $expect);
    }

    // too lazy to check for global post and global pre

    function testAttributeRemoved() {
        $token = new HTMLPurifier_Token_Start('p', array('foobar' => 'right'), 1);
        $this->invoke($token);
        $result = $this->collector->getRaw();
        $expect = array(
            array(1, E_ERROR, 'foobar attribute on <p> removed', array()),
        );
        $this->assertIdentical($result, $expect);
    }

}

// vim: et sw=4 sts=4
