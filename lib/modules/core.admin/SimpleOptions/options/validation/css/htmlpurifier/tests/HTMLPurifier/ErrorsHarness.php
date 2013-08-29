<?php

/**
 * @todo Make the callCount variable actually work, so we can precisely
 *       specify what errors we want: no more, no less
 */
class HTMLPurifier_ErrorsHarness extends HTMLPurifier_Harness
{

    protected $config, $context;
    protected $collector, $generator, $callCount;

    public function setup() {
        $this->config = HTMLPurifier_Config::create(array('Core.CollectErrors' => true));
        $this->context = new HTMLPurifier_Context();
        generate_mock_once('HTMLPurifier_ErrorCollector');
        $this->collector = new HTMLPurifier_ErrorCollectorEMock();
        $this->collector->prepare($this->context);
        $this->context->register('ErrorCollector', $this->collector);
        $this->callCount = 0;
    }

    protected function expectNoErrorCollection() {
        $this->collector->expectNever('send');
    }

    protected function expectErrorCollection() {
        $args = func_get_args();
        $this->collector->expectOnce('send', $args);
    }

    protected function expectContext($key, $value) {
        $this->collector->expectContext($key, $value);
    }

}

// vim: et sw=4 sts=4
