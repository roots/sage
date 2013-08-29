<?php

/**
 * Decorator/extender XSLT processor specifically for HTML documents.
 */
class ConfigDoc_HTMLXSLTProcessor
{

    /**
     * Instance of XSLTProcessor
     */
    protected $xsltProcessor;

    public function __construct($proc = false) {
        if ($proc === false) $proc = new XSLTProcessor();
        $this->xsltProcessor = $proc;
    }

    /**
     * @note Allows a string $xsl filename to be passed
     */
    public function importStylesheet($xsl) {
        if (is_string($xsl)) {
            $xsl_file = $xsl;
            $xsl = new DOMDocument();
            $xsl->load($xsl_file);
        }
        return $this->xsltProcessor->importStylesheet($xsl);
    }

    /**
     * Transforms an XML file into compatible XHTML based on the stylesheet
     * @param $xml XML DOM tree, or string filename
     * @return string HTML output
     * @todo Rename to transformToXHTML, as transformToHTML is misleading
     */
    public function transformToHTML($xml) {
        if (is_string($xml)) {
            $dom = new DOMDocument();
            $dom->load($xml);
        } else {
            $dom = $xml;
        }
        $out = $this->xsltProcessor->transformToXML($dom);

        // fudges for HTML backwards compatibility
        // assumes that document is XHTML
        $out = str_replace('/>', ' />', $out); // <br /> not <br/>
        $out = str_replace(' xmlns=""', '', $out); // rm unnecessary xmlns

        if (class_exists('Tidy')) {
            // cleanup output
            $config = array(
                'indent'        => true,
                'output-xhtml'  => true,
                'wrap'          => 80
            );
            $tidy = new Tidy;
            $tidy->parseString($out, $config, 'utf8');
            $tidy->cleanRepair();
            $out = (string) $tidy;
        }

        return $out;
    }

    /**
     * Bulk sets parameters for the XSL stylesheet
     * @param array $options Associative array of options to set
     */
    public function setParameters($options) {
        foreach ($options as $name => $value) {
            $this->xsltProcessor->setParameter('', $name, $value);
        }
    }

    /**
     * Forward any other calls to the XSLT processor
     */
    public function __call($name, $arguments) {
        call_user_func_array(array($this->xsltProcessor, $name), $arguments);
    }

}

// vim: et sw=4 sts=4
