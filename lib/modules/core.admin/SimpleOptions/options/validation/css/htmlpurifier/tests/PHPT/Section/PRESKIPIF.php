<?php

class PHPT_Section_PRESKIPIF implements PHPT_Section_RunnableBefore
{
    private $_data = null;
    private $_runner_factory = null;
    
    public function __construct($data)
    {
        $this->_data = $data;
        $this->_runner_factory = new PHPT_CodeRunner_Factory();
    }
    
    public function run(PHPT_Case $case)
    {
        // @todo refactor this code into PHPT_Util class as its used in multiple places
        $filename = dirname($case->filename) . '/' . basename($case->filename, '.php') . '.skip.php';
        
        // @todo refactor to PHPT_CodeRunner
        file_put_contents($filename, $this->_data);
        $runner = $this->_runner_factory->factory($case);
        $runner->ini = "";
        $response = $runner->run($filename)->output;
        unlink($filename);
        
        if (preg_match('/^skip( - (.*))?/', $response, $matches)) {
            $message = !empty($matches[2]) ? $matches[2] : '';
            throw new PHPT_Case_VetoException($message);
        }
    }
    
    public function getPriority()
    {
        return -2;
    }
}
