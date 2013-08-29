<?php

/**
 * Generates XML and HTML documents describing configuration.
 * @note PHP 5.2+ only!
 */

/*
TODO:
- make XML format richer
- extend XSLT transformation (see the corresponding XSLT file)
- allow generation of packaged docs that can be easily moved
- multipage documentation
- determine how to multilingualize
- add blurbs to ToC
*/

if (version_compare(PHP_VERSION, '5.2', '<')) exit('PHP 5.2+ required.');
error_reporting(E_ALL | E_STRICT);

// load dual-libraries
require_once dirname(__FILE__) . '/../extras/HTMLPurifierExtras.auto.php';
require_once dirname(__FILE__) . '/../library/HTMLPurifier.auto.php';

// setup HTML Purifier singleton
HTMLPurifier::getInstance(array(
    'AutoFormat.PurifierLinkify' => true
));

$builder = new HTMLPurifier_ConfigSchema_InterchangeBuilder();
$interchange = new HTMLPurifier_ConfigSchema_Interchange();
$builder->buildDir($interchange);
$loader = dirname(__FILE__) . '/../config-schema.php';
if (file_exists($loader)) include $loader;
$interchange->validate();

$style = 'plain'; // use $_GET in the future, careful to validate!
$configdoc_xml = dirname(__FILE__) . '/configdoc.xml';

$xml_builder = new HTMLPurifier_ConfigSchema_Builder_Xml();
$xml_builder->openURI($configdoc_xml);
$xml_builder->build($interchange);
unset($xml_builder); // free handle

$xslt = new ConfigDoc_HTMLXSLTProcessor();
$xslt->importStylesheet(dirname(__FILE__) . "/styles/$style.xsl");
$output = $xslt->transformToHTML($configdoc_xml);

if (!$output) {
    echo "Error in generating files\n";
    exit(1);
}

// write out
file_put_contents(dirname(__FILE__) . "/$style.html", $output);

if (php_sapi_name() != 'cli') {
    // output (instant feedback if it's a browser)
    echo $output;
} else {
    echo "Files generated successfully.\n";
}

// vim: et sw=4 sts=4
