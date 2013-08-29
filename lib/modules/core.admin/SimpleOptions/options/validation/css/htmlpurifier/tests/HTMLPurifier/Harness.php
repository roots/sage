<?php

/**
 * All-use harness, use this rather than SimpleTest's
 */
class HTMLPurifier_Harness extends UnitTestCase
{

    public function __construct($name = null) {
        parent::__construct($name);
    }

    protected $config, $context, $purifier;

    /**
     * Generates easily accessible default config/context, as well as
     * a convenience purifier for integration testing.
     */
    public function setUp() {
        list($this->config, $this->context) = $this->createCommon();
        $this->config->set('Output.Newline', '
');
        $this->purifier = new HTMLPurifier();
    }

    /**
     * Asserts a purification. Good for integration testing.
     */
    function assertPurification($input, $expect = null) {
        if ($expect === null) $expect = $input;
        $result = $this->purifier->purify($input, $this->config);
        $this->assertIdentical($expect, $result);
    }


    /**
     * Accepts config and context and prepares them into a valid state
     * @param &$config Reference to config variable
     * @param &$context Reference to context variable
     */
    protected function prepareCommon(&$config, &$context) {
        $config = HTMLPurifier_Config::create($config);
        if (!$context) $context = new HTMLPurifier_Context();
    }

    /**
     * Generates default configuration and context objects
     * @return Defaults in form of array($config, $context)
     */
    protected function createCommon() {
        return array(HTMLPurifier_Config::createDefault(), new HTMLPurifier_Context);
    }

    /**
     * Normalizes a string to Unix (\n) endings
     */
    protected function normalize(&$string) {
        $string = str_replace(array("\r\n", "\r"), "\n", $string);
    }

    /**
     * If $expect is false, ignore $result and check if status failed.
     * Otherwise, check if $status if true and $result === $expect.
     * @param $status Boolean status
     * @param $result Mixed result from processing
     * @param $expect Mixed expectation for result
     */
    protected function assertEitherFailOrIdentical($status, $result, $expect) {
        if ($expect === false) {
            $this->assertFalse($status, 'Expected false result, got true');
        } else {
            $this->assertTrue($status, 'Expected true result, got false');
            $this->assertIdentical($result, $expect);
        }
    }

    public function getTests() {
        // __onlytest makes only one test get triggered
        foreach (get_class_methods(get_class($this)) as $method) {
            if (strtolower(substr($method, 0, 10)) == '__onlytest') {
                $this->reporter->paintSkip('All test methods besides ' . $method);
                return array($method);
            }
        }
        return parent::getTests();
    }

}

// vim: et sw=4 sts=4
