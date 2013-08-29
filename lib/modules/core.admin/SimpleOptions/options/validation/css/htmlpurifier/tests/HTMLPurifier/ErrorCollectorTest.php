<?php

/**
 * @warning HTML output is in flux, but eventually needs to be stabilized.
 */
class HTMLPurifier_ErrorCollectorTest extends HTMLPurifier_Harness
{

    protected $language, $generator, $line;
    protected $collector;

    public function setup() {
        generate_mock_once('HTMLPurifier_Language');
        generate_mock_once('HTMLPurifier_Generator');
        parent::setup();
        $this->language  = new HTMLPurifier_LanguageMock();
        $this->language->setReturnValue('getErrorName',  'Error',   array(E_ERROR));
        $this->language->setReturnValue('getErrorName',  'Warning', array(E_WARNING));
        $this->language->setReturnValue('getErrorName',  'Notice',  array(E_NOTICE));
        // this might prove to be troublesome if we need to set config
        $this->generator = new HTMLPurifier_Generator($this->config, $this->context);
        $this->line = false;
        $this->context->register('Locale', $this->language);
        $this->context->register('CurrentLine', $this->line);
        $this->context->register('Generator', $this->generator);
        $this->collector = new HTMLPurifier_ErrorCollector($this->context);
    }

    function test() {

        $language = $this->language;
        $language->setReturnValue('getMessage',    'Message 1',   array('message-1'));
        $language->setReturnValue('formatMessage', 'Message 2',   array('message-2', array(1 => 'param')));
        $language->setReturnValue('formatMessage', ' at line 23', array('ErrorCollector: At line', array('line' => 23)));
        $language->setReturnValue('formatMessage', ' at line 3',  array('ErrorCollector: At line', array('line' => 3)));

        $this->line = 23;
        $this->collector->send(E_ERROR, 'message-1');

        $this->line = 3;
        $this->collector->send(E_WARNING, 'message-2', 'param');

        $result = array(
            0 => array(23, E_ERROR, 'Message 1', array()),
            1 => array(3, E_WARNING, 'Message 2', array())
        );

        $this->assertIdentical($this->collector->getRaw(), $result);

        /*
        $formatted_result =
            '<ul><li><strong>Warning</strong>: Message 2 at line 3</li>'.
            '<li><strong>Error</strong>: Message 1 at line 23</li></ul>';

        $this->assertIdentical($this->collector->getHTMLFormatted($this->config), $formatted_result);
        */

    }

    function testNoErrors() {
        $this->language->setReturnValue('getMessage', 'No errors', array('ErrorCollector: No errors'));

        $formatted_result = '<p>No errors</p>';
        $this->assertIdentical(
            $this->collector->getHTMLFormatted($this->config),
            $formatted_result
        );
    }

    function testNoLineNumbers() {
        $this->language->setReturnValue('getMessage', 'Message 1', array('message-1'));
        $this->language->setReturnValue('getMessage', 'Message 2', array('message-2'));

        $this->collector->send(E_ERROR, 'message-1');
        $this->collector->send(E_ERROR, 'message-2');

        $result = array(
            0 => array(false, E_ERROR, 'Message 1', array()),
            1 => array(false, E_ERROR, 'Message 2', array())
        );
        $this->assertIdentical($this->collector->getRaw(), $result);

        /*
        $formatted_result =
            '<ul><li><strong>Error</strong>: Message 1</li>'.
            '<li><strong>Error</strong>: Message 2</li></ul>';
        $this->assertIdentical($this->collector->getHTMLFormatted($this->config), $formatted_result);
        */
    }

    function testContextSubstitutions() {

        $current_token = false;
        $this->context->register('CurrentToken', $current_token);

        // 0
        $current_token = new HTMLPurifier_Token_Start('a', array('href' => 'http://example.com'), 32);
        $this->language->setReturnValue('formatMessage', 'Token message',
          array('message-data-token', array('CurrentToken' => $current_token)));
        $this->collector->send(E_NOTICE, 'message-data-token');

        $current_attr  = 'href';
        $this->language->setReturnValue('formatMessage', '$CurrentAttr.Name => $CurrentAttr.Value',
          array('message-attr', array('CurrentToken' => $current_token)));

        // 1
        $this->collector->send(E_NOTICE, 'message-attr'); // test when context isn't available

        // 2
        $this->context->register('CurrentAttr', $current_attr);
        $this->collector->send(E_NOTICE, 'message-attr');

        $result = array(
            0 => array(32, E_NOTICE, 'Token message', array()),
            1 => array(32, E_NOTICE, '$CurrentAttr.Name => $CurrentAttr.Value', array()),
            2 => array(32, E_NOTICE, 'href => http://example.com', array())
        );
        $this->assertIdentical($this->collector->getRaw(), $result);

    }

    /*
    function testNestedErrors() {
        $this->language->setReturnValue('getMessage', 'Message 1',   array('message-1'));
        $this->language->setReturnValue('getMessage', 'Message 2',   array('message-2'));
        $this->language->setReturnValue('formatMessage', 'End Message', array('end-message', array(1 => 'param')));
        $this->language->setReturnValue('formatMessage', ' at line 4', array('ErrorCollector: At line', array('line' => 4)));

        $this->line = 4;
        $this->collector->start();
        $this->collector->send(E_WARNING, 'message-1');
        $this->collector->send(E_NOTICE,  'message-2');
        $this->collector->end(E_NOTICE, 'end-message', 'param');

        $expect = array(
            0 => array(4, E_NOTICE, 'End Message', array(
                0 => array(4, E_WARNING, 'Message 1', array()),
                1 => array(4, E_NOTICE,  'Message 2', array()),
            )),
        );
        $result = $this->collector->getRaw();
        $this->assertIdentical($result, $expect);

        $formatted_expect =
            '<ul><li><strong>Notice</strong>: End Message at line 4<ul>'.
                '<li><strong>Warning</strong>: Message 1 at line 4</li>'.
                '<li><strong>Notice</strong>: Message 2 at line 4</li></ul>'.
            '</li></ul>';
        $formatted_result = $this->collector->getHTMLFormatted($this->config);
        $this->assertIdentical($formatted_result, $formatted_expect);

    }
    */

}

// vim: et sw=4 sts=4
