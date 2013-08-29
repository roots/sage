<?php

/**
 * Test harness that sets up a filesystem sandbox for file-emulation
 * functions to safely unit test in.
 *
 * @todo Make an automatic FSTools mock or something
 */
class FSTools_FileSystemHarness extends UnitTestCase
{

    protected $dir, $oldDir;

    function __construct() {
        parent::__construct();
        $this->dir = 'tmp/' . md5(uniqid(rand(), true)) . '/';
        mkdir($this->dir);
        $this->oldDir = getcwd();

    }

    function __destruct() {
        FSTools::singleton()->rmdirr($this->dir);
    }

    function setup() {
        chdir($this->dir);
    }

    function tearDown() {
        chdir($this->oldDir);
    }

}

// vim: et sw=4 sts=4
