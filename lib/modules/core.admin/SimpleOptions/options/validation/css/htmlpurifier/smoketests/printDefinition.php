<?php

require_once 'common.php'; // load library

require_once 'HTMLPurifier/Printer/HTMLDefinition.php';
require_once 'HTMLPurifier/Printer/CSSDefinition.php';
require_once 'HTMLPurifier/Printer/ConfigForm.php';

$config = HTMLPurifier_Config::loadArrayFromForm($_GET, 'config', 'HTML');

// you can do custom configuration!
if (file_exists('printDefinition.settings.php')) {
    include 'printDefinition.settings.php';
}

$gen_config = HTMLPurifier_Config::createDefault();
$printer_html_definition = new HTMLPurifier_Printer_HTMLDefinition();
$printer_html_definition->prepareGenerator($gen_config);
$printer_css_definition  = new HTMLPurifier_Printer_CSSDefinition();
$printer_css_definition->prepareGenerator($gen_config);

$printer_config_form = new HTMLPurifier_Printer_ConfigForm(
    'config',
    'http://htmlpurifier.org/live/configdoc/plain.html#%s'
);

echo '<?xml version="1.0" encoding="UTF-8" ?>';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>HTML Purifier Printer Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        .hp-config {margin-left:auto; margin-right:auto;}
        .HTMLPurifier_Printer table {border-collapse:collapse;
            border:1px solid #000; width:600px;
            margin:1em auto;font-family:sans-serif;font-size:75%;}
        .HTMLPurifier_Printer td, .HTMLPurifier_Printer th {padding:3px;
            border:1px solid #000;background:#CCC; vertical-align: baseline;}
        .HTMLPurifier_Printer th {text-align:left;background:#CCF;width:20%;}
        .HTMLPurifier_Printer caption {font-size:1.5em; font-weight:bold;}
        .HTMLPurifier_Printer .heavy {background:#99C;text-align:center;}
        .HTMLPurifier_Printer .unsafe {background:#C99;}
        dt {font-weight:bold;}
    </style>
    <link rel="stylesheet" href="../library/HTMLPurifier/Printer/ConfigForm.css" type="text/css" />
    <script defer="defer" type="text/javascript" src="../library/HTMLPurifier/Printer/ConfigForm.js"></script>
</head>
<body>

<h1>HTML Purifier Printer Smoketest</h1>

<p>HTML Purifier claims to have a robust yet permissive whitelist: this
page will allow you to see precisely what HTML Purifier's internal
whitelist is. You can
also twiddle with the configuration settings to see how a directive
influences the internal workings of the definition objects.</p>

<h2>Modify configuration</h2>

<p>You can specify an array by typing in a comma-separated
list of items, HTML Purifier will take care of the rest (including
transformation into a real array list or a lookup table).</p>

<form method="get" action="" name="hp-configform">
<?php
    echo $printer_config_form->render($config, 'HTML');
?>
<p>* Some configuration directives make a distinction between an empty
variable and a null variable. A whitelist, for example, will take an
empty array as meaning <em>no</em> allowed elements, while checking
Null/Disabled will mean that user whitelisting functionality is disabled.</p>
</form>

<h2>Definitions</h2>

<dl>
    <dt>Parent of Fragment</dt>
    <dd>HTML that HTML Purifier does not live in a void: when it's
        output, it has to be placed in another element by means of
        something like <code>&lt;element&gt; &lt;?php echo $html
        ?&gt; &lt;/element&gt;</code>. The parent in this example
        is <code>element</code>.</dd>
    <dt>Strict mode</dt>
    <dd>Whether or not HTML Purifier's output is Transitional or
        Strict compliant. Non-strict mode still actually a little strict
        and converts many deprecated elements.</dd>
    <dt>#PCDATA</dt>
    <dd>Literally <strong>Parsed Character Data</strong>, it is regular
        text. Tags like <code>ul</code> don't allow text in them, so
        #PCDATA is missing.</dd>
    <dt>Tag transform</dt>
    <dd>A tag transform will change one tag to another. Example: <code>font</code>
        turns into a <code>span</code> tag with appropriate CSS.</dd>
    <dt>Attr Transform</dt>
    <dd>An attribute transform changes a group of attributes based on one
        another. Currently, only <code>lang</code> and <code>xml:lang</code>
        use this hook, to synchronize each other's values. Pre/Post indicates
        whether or not the transform is done before/after validation.</dd>
    <dt>Excludes</dt>
    <dd>Tags that an element excludes are excluded for all descendants of
        that element, and not just the children of them.</dd>
    <dt>Name(Param1, Param2)</dt>
    <dd>Represents an internal data-structure. You'll have to check out
        the corresponding classes in HTML Purifier to find out more.</dd>
</dl>

<h2>HTMLDefinition</h2>
<?php echo $printer_html_definition->render($config) ?>
<h2>CSSDefinition</h2>
<?php echo $printer_css_definition->render($config) ?>
</body>
</html>
<?php

// vim: et sw=4 sts=4
