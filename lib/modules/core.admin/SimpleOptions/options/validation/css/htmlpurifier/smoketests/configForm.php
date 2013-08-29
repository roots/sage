<?php

require_once 'common.php';

// Setup environment
require_once '../extras/HTMLPurifierExtras.auto.php';
$interchange = HTMLPurifier_ConfigSchema_InterchangeBuilder::buildFromDirectory('test-schema/');
$interchange->validate();

if (isset($_GET['doc'])) {

    // Hijack page generation to supply documentation

    if (file_exists('test-schema.html') && !isset($_GET['purge'])) {
        echo file_get_contents('test-schema.html');
        exit;
    }

    $style = 'plain';
    $configdoc_xml = 'test-schema.xml';

    $xml_builder = new HTMLPurifier_ConfigSchema_Builder_Xml();
    $xml_builder->openURI($configdoc_xml);
    $xml_builder->build($interchange);
    unset($xml_builder); // free handle

    $xslt = new ConfigDoc_HTMLXSLTProcessor();
    $xslt->importStylesheet("../configdoc/styles/$style.xsl");
    $xslt->setParameters(array(
      'css' => '../configdoc/styles/plain.css',
    ));
    $html = $xslt->transformToHTML($configdoc_xml);

    unlink('test-schema.xml');
    file_put_contents('test-schema.html', $html);
    echo $html;

    exit;
}

?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>HTML Purifier Config Form Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="../library/HTMLPurifier/Printer/ConfigForm.css" type="text/css" />
    <script defer="defer" type="text/javascript" src="../library/HTMLPurifier/Printer/ConfigForm.js"></script>
</head>
<body>
<h1>HTML Purifier Config Form Smoketest</h1>
<p>This file outputs the configuration form for every single type
of directive possible.</p>
<form id="htmlpurifier-config" name="htmlpurifier-config" method="get" action=""
style="float:right;">
<?php

$schema_builder = new HTMLPurifier_ConfigSchema_Builder_ConfigSchema();
$schema = $schema_builder->build($interchange);

$config  = HTMLPurifier_Config::loadArrayFromForm($_GET, 'config', true, true, $schema);
$printer = new HTMLPurifier_Printer_ConfigForm('config', '?doc#%s');
echo $printer->render(array(HTMLPurifier_Config::createDefault(), $config));

?>
</form>
<pre>
<?php
echo htmlspecialchars(var_export($config->getAll(), true));
?>
</pre>
</body>
</html>
<?php

// vim: et sw=4 sts=4
