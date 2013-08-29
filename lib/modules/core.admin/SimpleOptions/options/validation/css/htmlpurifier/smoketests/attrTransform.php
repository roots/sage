<?php

require 'common.php';

?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>HTML Purifier Attribute Transformation Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        div.container {position:relative;height:120px;border:1px solid #CCC;
            margin-bottom:1em; width:225px; float:left; margin-top:1em;
            margin-right:1em;}
        h2 {clear:left;margin-bottom:0;}
        div.container.legend .test {text-align:center;line-height:100px;}
        div.test {width:100px;height:100px;border:1px solid black;
            position:absolute;top:10px;overflow:auto;}
        div.test.html {left:10px;border-right:none;background:#FCC;}
        div.test.css  {left:110px;background:#CFC;}
        img.marked {border:1px solid #000;background:#FFF;}
        table.bright {background-color:#F00;}
        hr.short {width:50px;}
    </style>
</head>
<body>
<h1>HTML Purifier Attribute Transformation Smoketest</h1>
<div class="container legend">
<div class="test html">
    HTML
</div>
<div class="test css">
    CSS
</div>
</div>
<?php

if (version_compare(PHP_VERSION, '5', '<')) exit('<p>Requires PHP 5.</p>');

$xml = simplexml_load_file('attrTransform.xml');

// attr transform enabled HTML Purifier
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);

$title = isset($_GET['title']) ? $_GET['title'] : true;

foreach ($xml->group as $group) {
    echo '<h2>' . $group['title'] . '</h2>';
    foreach ($group->sample as $sample) {
        $sample = (string) $sample;
?>
<div class="container">
<div class="test html">
    <?php echo $sample; ?>
</div>
<div class="test css">
    <?php echo $purifier->purify($sample); ?>
</div>
</div>
<?php
    }
}

?>
</body>
</html>
<?php

// vim: et sw=4 sts=4
