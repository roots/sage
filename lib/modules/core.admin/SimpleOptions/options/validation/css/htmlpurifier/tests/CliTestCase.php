<?php

/**
 * Implements an external test-case like RemoteTestCase that parses its
 * output from XML returned by a command line call
 */
class CliTestCase
{
    public $_command;
    public $_out = false;
    public $_quiet = false;
    public $_errors = array();
    public $_size = false;
    /**
     * @param $command Command to execute to retrieve XML
     * @param $xml Whether or not to suppress error messages
     */
    public function __construct($command, $quiet = false, $size = false) {
        $this->_command = $command;
        $this->_quiet   = $quiet;
        $this->_size    = $size;
    }
    public function getLabel() {
        return $this->_command;
    }
    public function run($reporter) {
        if (!$this->_quiet) $reporter->paintFormattedMessage('Running ['.$this->_command.']');
        return $this->_invokeCommand($this->_command, $reporter);
    }
    public function _invokeCommand($command, $reporter) {
       $xml = shell_exec($command);
        if (! $xml) {
            if (!$this->_quiet) {
                $reporter->paintFail('Command did not have any output [' . $command . ']');
            }
            return false;
        }
        $parser = $this->_createParser($reporter);

        set_error_handler(array($this, '_errorHandler'));
        $status = $parser->parse($xml);
        restore_error_handler();

        if (! $status) {
            if (!$this->_quiet) {
                foreach ($this->_errors as $error) {
                    list($no, $str, $file, $line) = $error;
                    $reporter->paintFail("Error $no: $str on line $line of $file");
                }
                if (strlen($xml) > 120) {
                    $msg = substr($xml, 0, 50) . "...\n\n[snip]\n\n..." . substr($xml, -50);
                } else {
                    $msg = $xml;
                }
                $reporter->paintFail("Command produced malformed XML");
                $reporter->paintFormattedMessage($msg);
            }
            return false;
        }
        return true;
    }
    public function _createParser($reporter) {
        $parser = new SimpleTestXmlParser($reporter);
        return $parser;
    }
    public function getSize() {
        // This code properly does the dry run and allows for proper test
        // case reporting but it's REALLY slow, so I don't recommend it.
        if ($this->_size === false) {
            $reporter = new SimpleReporter();
            $this->_invokeCommand($this->_command . ' --dry', $reporter);
            $this->_size = $reporter->getTestCaseCount();
        }
        return $this->_size;
    }
    public function _errorHandler($a, $b, $c, $d) {
        $this->_errors[] = array($a, $b, $c, $d); // see set_error_handler()
    }
}

// vim: et sw=4 sts=4
