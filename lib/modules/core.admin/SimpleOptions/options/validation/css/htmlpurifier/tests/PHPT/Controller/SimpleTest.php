<?php

/**
 * Controller for PHPT that implements the SimpleTest unit-testing interface.
 */
class PHPT_Controller_SimpleTest extends SimpleTestCase
{

    protected $_path;

    public function __construct($path) {
        $this->_path = $path;
        parent::__construct($path);
    }

    public function testPhpt() {
        $suite = new PHPT_Suite(array($this->_path));
        $phpt_reporter = new PHPT_Reporter_SimpleTest($this->reporter);
        $suite->run($phpt_reporter);
    }

}

// vim: et sw=4 sts=4
