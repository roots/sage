<?php

// mocks
class HTMLPurifier_ContextTest extends HTMLPurifier_Harness
{

    protected $context;

    public function setUp() {
        $this->context = new HTMLPurifier_Context();
    }

    function testStandardUsage() {

        generate_mock_once('HTMLPurifier_IDAccumulator');

        $this->assertFalse($this->context->exists('IDAccumulator'));

        $accumulator = new HTMLPurifier_IDAccumulatorMock();
        $this->context->register('IDAccumulator', $accumulator);
        $this->assertTrue($this->context->exists('IDAccumulator'));

        $accumulator_2 =& $this->context->get('IDAccumulator');
        $this->assertReference($accumulator, $accumulator_2);

        $this->context->destroy('IDAccumulator');
        $this->assertFalse($this->context->exists('IDAccumulator'));

        $this->expectError('Attempted to retrieve non-existent variable IDAccumulator');
        $accumulator_3 =& $this->context->get('IDAccumulator');
        $this->assertNull($accumulator_3);

        $this->expectError('Attempted to destroy non-existent variable IDAccumulator');
        $this->context->destroy('IDAccumulator');

    }

    function testReRegister() {

        $var = true;
        $this->context->register('OnceOnly', $var);

        $this->expectError('Name OnceOnly produces collision, cannot re-register');
        $this->context->register('OnceOnly', $var);

        // destroy it, now registration is okay
        $this->context->destroy('OnceOnly');
        $this->context->register('OnceOnly', $var);

    }

    function test_loadArray() {

        // references can be *really* wonky!

        $context_manual = new HTMLPurifier_Context();
        $context_load   = new HTMLPurifier_Context();

        $var1 = 1;
        $var2 = 2;

        $context_manual->register('var1', $var1);
        $context_manual->register('var2', $var2);

        // you MUST set up the references when constructing the array,
        // otherwise the registered version will be a copy
        $array = array(
            'var1' => &$var1,
            'var2' => &$var2
        );

        $context_load->loadArray($array);
        $this->assertIdentical($context_manual, $context_load);

        $var1 = 10;
        $var2 = 20;

        $this->assertIdentical($context_manual, $context_load);

    }

}

// vim: et sw=4 sts=4
