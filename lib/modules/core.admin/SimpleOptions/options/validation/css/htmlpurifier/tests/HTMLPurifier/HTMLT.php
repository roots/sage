<?php

class HTMLPurifier_HTMLT extends HTMLPurifier_Harness
{
    protected $path;

    public function __construct($path) {
        $this->path = $path;
        parent::__construct($path);
    }

    public function testHtmlt() {
        $parser = new HTMLPurifier_StringHashParser();
        $hash = $parser->parseFile($this->path); // assume parser normalizes to "\n"
        if (isset($hash['SKIPIF'])) {
            if (eval($hash['SKIPIF'])) return;
        }
        $this->config->set('Output.Newline', "\n");
        if (isset($hash['INI'])) {
            // there should be a more efficient way than writing another
            // ini file every time... probably means building a parser for
            // ini (check out the yaml implementation we saw somewhere else)
            $ini_file = $this->path . '.ini';
            file_put_contents($ini_file, $hash['INI']);
            $this->config->loadIni($ini_file);
        }
        $expect = isset($hash['EXPECT']) ? $hash['EXPECT'] : $hash['HTML'];
        $this->assertPurification(rtrim($hash['HTML']), rtrim($expect));
        if (isset($hash['INI'])) unlink($ini_file);
    }
}

// vim: et sw=4 sts=4
