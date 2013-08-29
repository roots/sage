<?php

// based off of BBCode's settings file

/**
 * HTML Purifier Phorum mod settings configuration. This provides
 * a convenient web-interface for editing the most common HTML Purifier
 * configuration directives. You can also specify custom configuration
 * by creating a 'config.php' file.
 */

if(!defined("PHORUM_ADMIN")) exit;

// error reporting is good!
error_reporting(E_ALL ^ E_NOTICE);

// load library and other paraphenalia
require_once './include/admin/PhorumInputForm.php';
require_once (dirname(__FILE__) . '/htmlpurifier/HTMLPurifier.auto.php');
require_once (dirname(__FILE__) . '/init-config.php');
require_once (dirname(__FILE__) . '/settings/migrate-sigs-form.php');
require_once (dirname(__FILE__) . '/settings/migrate-sigs.php');
require_once (dirname(__FILE__) . '/settings/form.php');
require_once (dirname(__FILE__) . '/settings/save.php');

// define friendly configuration directives. you can expand this array
// to get more web-definable directives
$PHORUM['mod_htmlpurifier']['directives'] = array(
    'URI.Host', // auto-detectable
    'URI.DisableExternal',
    'URI.DisableExternalResources',
    'URI.DisableResources',
    'URI.Munge',
    'URI.HostBlacklist',
    'URI.Disable',
    'HTML.TidyLevel',
    'HTML.Doctype', // auto-detectable
    'HTML.Allowed',
    'AutoFormat',
    '-AutoFormat.Custom',
    'AutoFormatParam',
    'Output.TidyFormat',
);

// lower this setting if you're getting time outs/out of memory
$PHORUM['mod_htmlpurifier']['migrate-sigs-increment'] = 100;

if (isset($_POST['reset'])) {
    unset($PHORUM['mod_htmlpurifier']['config']);
}

if ($offset = phorum_htmlpurifier_migrate_sigs_check()) {
    // migrate signatures
    phorum_htmlpurifier_migrate_sigs($offset);
} elseif(!empty($_POST)){
    // save settings
    phorum_htmlpurifier_save_settings();
}

phorum_htmlpurifier_show_migrate_sigs_form();
echo '<br />';
phorum_htmlpurifier_show_form();

// vim: et sw=4 sts=4
