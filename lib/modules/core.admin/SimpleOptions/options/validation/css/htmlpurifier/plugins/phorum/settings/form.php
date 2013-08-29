<?php

function phorum_htmlpurifier_show_form() {
    if (phorum_htmlpurifier_config_file_exists()) {
        phorum_htmlpurifier_show_config_info();
        return;
    }

    global $PHORUM;

    $config = phorum_htmlpurifier_get_config();

    $frm = new PhorumInputForm ("", "post", "Save");
    $frm->hidden("module", "modsettings");
    $frm->hidden("mod", "htmlpurifier"); // this is the directory name that the Settings file lives in

    if (!empty($error)){
        echo "$error<br />";
    }

    $frm->addbreak("Edit settings for the HTML Purifier module");

    $frm->addMessage('<p>The box below sets <code>$PHORUM[\'mod_htmlpurifier\'][\'wysiwyg\']</code>.
    When checked, contents sent for edit are now purified and the
    informative message is disabled. If your WYSIWYG editor is disabled for
    admin edits, you can safely keep this unchecked.</p>');
    $frm->addRow('Use WYSIWYG?', $frm->checkbox('wysiwyg', '1', '', $PHORUM['mod_htmlpurifier']['wysiwyg']));

    $frm->addMessage('<p>The box below sets <code>$PHORUM[\'mod_htmlpurifier\'][\'suppress_message\']</code>,
    which removes the big how-to use
    HTML Purifier message.</p>');
    $frm->addRow('Suppress information?', $frm->checkbox('suppress_message', '1', '', $PHORUM['mod_htmlpurifier']['suppress_message']));

    $frm->addMessage('<p>Click on directive links to read what each option does
    (links do not open in new windows).</p>
    <p>For more flexibility (for instance, you want to edit the full
    range of configuration directives), you can create a <tt>config.php</tt>
    file in your <tt>mods/htmlpurifier/</tt> directory. Doing so will,
    however, make the web configuration interface unavailable.</p>');

    require_once 'HTMLPurifier/Printer/ConfigForm.php';
    $htmlpurifier_form = new HTMLPurifier_Printer_ConfigForm('config', 'http://htmlpurifier.org/live/configdoc/plain.html#%s');
    $htmlpurifier_form->setTextareaDimensions(23, 7); // widen a little, since we have space

    $frm->addMessage($htmlpurifier_form->render(
        $config, $PHORUM['mod_htmlpurifier']['directives'], false));

    $frm->addMessage("<strong>Warning: Changing HTML Purifier's configuration will invalidate
      the cache. Expect to see a flurry of database activity after you change
      any of these settings.</strong>");

    $frm->addrow('Reset to defaults:', $frm->checkbox("reset", "1", "", false));

    // hack to include extra styling
    echo '<style type="text/css">' . $htmlpurifier_form->getCSS() . '
    .hp-config {margin-left:auto;margin-right:auto;}
    </style>';
    $js = $htmlpurifier_form->getJavaScript();
    echo '<script type="text/javascript">'."<!--\n$js\n//-->".'</script>';

    $frm->show();
}

function phorum_htmlpurifier_show_config_info() {
    global $PHORUM;

    // update mod_htmlpurifier for housekeeping
    phorum_htmlpurifier_commit_settings();

    // politely tell user how to edit settings manually
?>
        <div class="input-form-td-break">How to edit settings for HTML Purifier module</div>
        <p>
          A <tt>config.php</tt> file exists in your <tt>mods/htmlpurifier/</tt>
          directory. This file contains your custom configuration: in order to
          change it, please navigate to that file and edit it accordingly.
          You can also set <code>$GLOBALS['PHORUM']['mod_htmlpurifier']['wysiwyg']</code>
          or <code>$GLOBALS['PHORUM']['mod_htmlpurifier']['suppress_message']</code>
        </p>
        <p>
          To use the web interface, delete <tt>config.php</tt> (or rename it to
          <tt>config.php.bak</tt>).
        </p>
        <p>
          <strong>Warning: Changing HTML Purifier's configuration will invalidate
          the cache. Expect to see a flurry of database activity after you change
          any of these settings.</strong>
        </p>
<?php

}

// vim: et sw=4 sts=4
