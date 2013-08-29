<?php

class HTMLPurifier_Strategy_Composite_Test
    extends HTMLPurifier_Strategy_Composite
{

    public function __construct(&$strategies) {
        $this->strategies =& $strategies;
    }

}

// doesn't use Strategy harness
class HTMLPurifier_Strategy_CompositeTest extends HTMLPurifier_Harness
{

    function test() {

        generate_mock_once('HTMLPurifier_Strategy');
        generate_mock_once('HTMLPurifier_Config');
        generate_mock_once('HTMLPurifier_Context');

        // setup a bunch of mock strategies to inject into our composite test

        $mock_1 = new HTMLPurifier_StrategyMock();
        $mock_2 = new HTMLPurifier_StrategyMock();
        $mock_3 = new HTMLPurifier_StrategyMock();

        // setup the object

        $strategies = array(&$mock_1, &$mock_2, &$mock_3);
        $composite = new HTMLPurifier_Strategy_Composite_Test($strategies);

        // setup expectations

        $input_1 = 'This is raw data';
        $input_2 = 'Processed by 1';
        $input_3 = 'Processed by 1 and 2';
        $input_4 = 'Processed by 1, 2 and 3'; // expected output

        $config  = new HTMLPurifier_ConfigMock();
        $context = new HTMLPurifier_ContextMock();

        $params_1 = array($input_1, $config, $context);
        $params_2 = array($input_2, $config, $context);
        $params_3 = array($input_3, $config, $context);

        $mock_1->expectOnce('execute', $params_1);
        $mock_1->setReturnValue('execute', $input_2, $params_1);

        $mock_2->expectOnce('execute', $params_2);
        $mock_2->setReturnValue('execute', $input_3, $params_2);

        $mock_3->expectOnce('execute', $params_3);
        $mock_3->setReturnValue('execute', $input_4, $params_3);

        // perform test

        $output = $composite->execute($input_1, $config, $context);
        $this->assertIdentical($input_4, $output);

    }

}

// vim: et sw=4 sts=4
