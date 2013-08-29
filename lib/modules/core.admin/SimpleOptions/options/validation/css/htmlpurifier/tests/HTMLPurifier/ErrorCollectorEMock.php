<?php

generate_mock_once('HTMLPurifier_ErrorCollector');

/**
 * Extended error collector mock that has the ability to expect context
 */
class HTMLPurifier_ErrorCollectorEMock extends HTMLPurifier_ErrorCollectorMock
{

    private $_context;
    private $_expected_context = array();
    private $_expected_context_at = array();

    public function prepare($context) {
        $this->_context = $context;
    }

    public function expectContext($key, $value) {
        $this->_expected_context[$key] = $value;
    }
    public function expectContextAt($step, $key, $value) {
        $this->_expected_context_at[$step][$key] = $value;
    }

    public function send($v1, $v2) {
        // test for context
        $context = SimpleTest::getContext();
        $test = $context->getTest();
        $mock = $this->mock;

        foreach ($this->_expected_context as $key => $value) {
            $test->assertEqual($value, $this->_context->get($key));
        }
        $step = $mock->getCallCount('send');
        if (isset($this->_expected_context_at[$step])) {
            foreach ($this->_expected_context_at[$step] as $key => $value) {
                $test->assertEqual($value, $this->_context->get($key));
            }
        }
        // boilerplate mock code, does not have return value or references
        $args = func_get_args();
        $mock->invoke('send', $args);
    }

}

// vim: et sw=4 sts=4
