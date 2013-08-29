<?php

class HTMLPurifier_SimpleTest_TextReporter extends TextReporter {
    protected $verbose = false;
    function __construct($AC) {
        parent::__construct();
        $this->verbose = $AC['verbose'];
    }
    function paintPass($message) {
        parent::paintPass($message);
        if ($this->verbose) {
            print 'Pass ' . $this->getPassCount() . ") $message\n";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print "\tin " . implode("\n\tin ", array_reverse($breadcrumb));
            print "\n";
        }
    }
}

// vim: et sw=4 sts=4
