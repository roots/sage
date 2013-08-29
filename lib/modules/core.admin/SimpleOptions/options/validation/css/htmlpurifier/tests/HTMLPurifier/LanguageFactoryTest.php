<?php

class HTMLPurifier_LanguageFactoryTest extends HTMLPurifier_Harness
{

    /**
     * Protected reference of global factory we're testing.
     */
    protected $factory;

    public function setUp() {
        $this->factory = HTMLPurifier_LanguageFactory::instance();
        parent::setUp();
    }

    function test() {

        $this->config->set('Core.Language', 'en');
        $language = $this->factory->create($this->config, $this->context);

        $this->assertIsA($language, 'HTMLPurifier_Language');
        $this->assertIdentical($language->code, 'en');

        // lazy loading test
        $this->assertIdentical(count($language->messages), 0);
        $language->load();
        $this->assertNotEqual(count($language->messages), 0);

    }

    function testFallback() {

        $this->config->set('Core.Language', 'en-x-test');
        $language = $this->factory->create($this->config, $this->context);

        $this->assertIsA($language, 'HTMLPurifier_Language_en_x_test');
        $this->assertIdentical($language->code, 'en-x-test');

        $language->load();

        // test overloaded message
        $this->assertIdentical($language->getMessage('HTMLPurifier'), 'HTML Purifier X');

        // test inherited message
        $this->assertIdentical($language->getMessage('LanguageFactoryTest: Pizza'), 'Pizza');

    }

    function testFallbackWithNoClass() {
        $this->config->set('Core.Language', 'en-x-testmini');
        $language = $this->factory->create($this->config, $this->context);
        $this->assertIsA($language, 'HTMLPurifier_Language');
        $this->assertIdentical($language->code, 'en-x-testmini');
        $language->load();
        $this->assertIdentical($language->getMessage('HTMLPurifier'), 'HTML Purifier XNone');
        $this->assertIdentical($language->getMessage('LanguageFactoryTest: Pizza'), 'Pizza');
        $this->assertIdentical($language->error, false);
    }

    function testNoSuchLanguage() {
        $this->config->set('Core.Language', 'en-x-testnone');
        $language = $this->factory->create($this->config, $this->context);
        $this->assertIsA($language, 'HTMLPurifier_Language');
        $this->assertIdentical($language->code, 'en-x-testnone');
        $this->assertIdentical($language->error, true);
    }

}

// vim: et sw=4 sts=4
