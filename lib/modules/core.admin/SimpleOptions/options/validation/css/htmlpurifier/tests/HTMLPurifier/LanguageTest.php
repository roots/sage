<?php

/**
 * @todo Fix usage of HTMLPurifier_Language->_loaded using something else
 */
class HTMLPurifier_LanguageTest extends HTMLPurifier_Harness
{

    protected $lang;

    protected function generateEnLanguage() {
        $factory = HTMLPurifier_LanguageFactory::instance();
        $config = HTMLPurifier_Config::create(array('Core.Language' => 'en'));
        $context = new HTMLPurifier_Context();
        return $factory->create($config, $context);
    }

    function test_getMessage() {
        $config = HTMLPurifier_Config::createDefault();
        $context = new HTMLPurifier_Context();
        $lang = new HTMLPurifier_Language($config, $context);
        $lang->_loaded = true;
        $lang->messages['HTMLPurifier'] = 'HTML Purifier';
        $this->assertIdentical($lang->getMessage('HTMLPurifier'), 'HTML Purifier');
        $this->assertIdentical($lang->getMessage('LanguageTest: Totally non-existent key'), '[LanguageTest: Totally non-existent key]');
    }

    function test_formatMessage() {
        $config = HTMLPurifier_Config::createDefault();
        $context = new HTMLPurifier_Context();
        $lang = new HTMLPurifier_Language($config, $context);
        $lang->_loaded = true;
        $lang->messages['LanguageTest: Error'] = 'Error is $1 on line $2';
        $this->assertIdentical($lang->formatMessage('LanguageTest: Error', array(1=>'fatal', 32)), 'Error is fatal on line 32');
    }

    function test_formatMessage_tokenParameter() {
        $config = HTMLPurifier_Config::createDefault();
        $context = new HTMLPurifier_Context();
        $generator = new HTMLPurifier_Generator($config, $context); // replace with mock if this gets icky
        $context->register('Generator', $generator);
        $lang = new HTMLPurifier_Language($config, $context);
        $lang->_loaded = true;
        $lang->messages['LanguageTest: Element info'] = 'Element Token: $1.Name, $1.Serialized, $1.Compact, $1.Line';
        $lang->messages['LanguageTest: Data info']    = 'Data Token: $1.Data, $1.Serialized, $1.Compact, $1.Line';
        $this->assertIdentical($lang->formatMessage('LanguageTest: Element info',
            array(1=>new HTMLPurifier_Token_Start('a', array('href'=>'http://example.com'), 18))),
            'Element Token: a, <a href="http://example.com">, <a>, 18');
        $this->assertIdentical($lang->formatMessage('LanguageTest: Data info',
            array(1=>new HTMLPurifier_Token_Text('data>', 23))),
            'Data Token: data>, data&gt;, data&gt;, 23');
    }

    function test_listify() {
        $lang = $this->generateEnLanguage();
        $this->assertEqual($lang->listify(array('Item')), 'Item');
        $this->assertEqual($lang->listify(array('Item', 'Item2')), 'Item and Item2');
        $this->assertEqual($lang->listify(array('Item', 'Item2', 'Item3')), 'Item, Item2 and Item3');
    }

    function test_formatMessage_arrayParameter() {
        $lang = $this->generateEnLanguage();

        $array = array('Item1', 'Item2', 'Item3');
        $this->assertIdentical(
            $lang->formatMessage('LanguageTest: List', array(1=>$array)),
            'Item1, Item2 and Item3'
        );

        $array = array('Key1' => 'Value1', 'Key2' => 'Value2');
        $this->assertIdentical(
            $lang->formatMessage('LanguageTest: Hash', array(1=>$array)),
            'Key1 and Key2; Value1 and Value2'
        );
    }

}

// vim: et sw=4 sts=4
