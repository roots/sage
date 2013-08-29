<?php

function phorum_htmlpurifier_save_settings() {
    global $PHORUM;
    if (phorum_htmlpurifier_config_file_exists()) {
        echo "Cannot update settings, <code>mods/htmlpurifier/config.php</code> already exists. To change
        settings, edit that file. To use the web form, delete that file.<br />";
    } else {
        $config = phorum_htmlpurifier_get_config(true);
        if (!isset($_POST['reset'])) $config->mergeArrayFromForm($_POST, 'config', $PHORUM['mod_htmlpurifier']['directives']);
        $PHORUM['mod_htmlpurifier']['config'] = $config->getAll();
    }
    $PHORUM['mod_htmlpurifier']['wysiwyg'] = !empty($_POST['wysiwyg']);
    $PHORUM['mod_htmlpurifier']['suppress_message'] = !empty($_POST['suppress_message']);
    if(!phorum_htmlpurifier_commit_settings()){
        $error="Database error while updating settings.";
    } else {
        echo "Settings Updated<br />";
    }
}

function phorum_htmlpurifier_commit_settings() {
    global $PHORUM;
    return phorum_db_update_settings(array("mod_htmlpurifier"=>$PHORUM["mod_htmlpurifier"]));
}

// vim: et sw=4 sts=4
