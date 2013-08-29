<?php

/**
 * This file is responsible for migrating from a specific markup language
 * like BBCode or Markdown to HTML. WARNING: THIS PROCESS IS NOT REVERSIBLE
 *
 * Copy this file to 'migrate.php' and it will automatically work for
 * BBCode; you may need to tweak this a little to get it to work for other
 * languages (usually, just replace the include name and the function name).
 *
 * If you do NOT want to have any migration performed (for instance, you
 * are installing the module on a new forum with no posts), simply remove
 * phorum_htmlpurifier_migrate() function. You still need migrate.php
 * present, otherwise the module won't work. This ensures that the user
 * explicitly says, "No, I do not need to migrate."
 */

if(!defined("PHORUM")) exit;

require_once(dirname(__FILE__) . "/../bbcode/bbcode.php");

/**
 * 'format' hook style function that will be called to convert
 * legacy markup into HTML.
 */
function phorum_htmlpurifier_migrate($data) {
    return phorum_mod_bbcode_format($data); // bbcode's 'format' hook
}

// vim: et sw=4 sts=4
