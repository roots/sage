<?php

// This file demonstrates basic usage of HTMLPurifier.

// replace this with the path to the HTML Purifier library
require_once '../../library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();

// configuration goes here:
$config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype

$purifier = new HTMLPurifier($config);

// untrusted input HTML
$html = '<b>Simple and short';

$pure_html = $purifier->purify($html);

echo '<pre>' . htmlspecialchars($pure_html) . '</pre>';

// vim: et sw=4 sts=4
