<?php

if(!defined("PHORUM")) exit;

// default HTML Purifier configuration settings
$config->set('HTML.Allowed',
  // alphabetically sorted
'a[href|title]
abbr[title]
acronym[title]
b
blockquote[cite]
br
caption
cite
code
dd
del
dfn
div
dl
dt
em
i
img[src|alt|title|class]
ins
kbd
li
ol
p
pre
s
strike
strong
sub
sup
table
tbody
td
tfoot
th
thead
tr
tt
u
ul
var');
$config->set('AutoFormat.AutoParagraph', true);
$config->set('AutoFormat.Linkify', true);
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
$config->set('Core.AggressivelyFixLt', true);
$config->set('Core.Encoding', $GLOBALS['PHORUM']['DATA']['CHARSET']); // we'll change this eventually
if (strtolower($GLOBALS['PHORUM']['DATA']['CHARSET']) !== 'utf-8') {
  $config->set('Core.EscapeNonASCIICharacters', true);
}

// vim: et sw=4 sts=4
