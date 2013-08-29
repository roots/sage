<?php

/**
 * HTML Purifier Phorum Mod. Filter your HTML the Standards-Compliant Way!
 *
 * This Phorum mod enables users to post raw HTML into Phorum.  But never
 * fear: with the help of HTML Purifier, this HTML will be beat into
 * de-XSSed and standards-compliant form, safe for general consumption.
 * It is not recommended, but possible to run this mod in parallel
 * with other formatters (in short, please DISABLE the BBcode mod).
 *
 * For help migrating from your previous markup language to pure HTML
 * please check the migrate.bbcode.php file.
 *
 * If you'd like to use this with a WYSIWYG editor, make sure that
 * editor sets $PHORUM['mod_htmlpurifier']['wysiwyg'] to true. Otherwise,
 * administrators who need to edit other people's comments may be at
 * risk for some nasty attacks.
 *
 * Tested with Phorum 5.2.11.
 */

// Note: Cache data is base64 encoded because Phorum insists on flinging
// to the user and expecting it to come back unharmed, newlines and
// all, which ain't happening. It's slower, it takes up more space, but
// at least it won't get mutilated

/**
 * Purifies a data array
 */
function phorum_htmlpurifier_format($data)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $purifier =& HTMLPurifier::getInstance();
    $cache_serial = $PHORUM['mod_htmlpurifier']['body_cache_serial'];

    foreach($data as $message_id => $message){
        if(isset($message['body'])) {

            if ($message_id) {
                // we're dealing with a real message, not a fake, so
                // there a number of shortcuts that can be taken

                if (isset($message['meta']['htmlpurifier_light'])) {
                    // format hook was called outside of Phorum's normal
                    // functions, do the abridged purification
                    $data[$message_id]['body'] = $purifier->purify($message['body']);
                    continue;
                }

                if (!empty($PHORUM['args']['purge'])) {
                    // purge the cache, must be below the following if
                    unset($message['meta']['body_cache']);
                }

                if (
                    isset($message['meta']['body_cache']) &&
                    isset($message['meta']['body_cache_serial']) &&
                    $message['meta']['body_cache_serial'] == $cache_serial
                ) {
                    // cached version is present, bail out early
                    $data[$message_id]['body'] = base64_decode($message['meta']['body_cache']);
                    continue;
                }
            }

            // migration might edit this array, that's why it's defined
            // so early
            $updated_message = array();

            // create the $body variable
            if (
                $message_id && // message must be real to migrate
                !isset($message['meta']['body_cache_serial'])
            ) {
                // perform migration
                $fake_data = array();
                list($signature, $edit_message) = phorum_htmlpurifier_remove_sig_and_editmessage($message);
                $fake_data[$message_id] = $message;
                $fake_data = phorum_htmlpurifier_migrate($fake_data);
                $body = $fake_data[$message_id]['body'];
                $body = str_replace("<phorum break>\n", "\n", $body);
                $updated_message['body'] = $body; // save it in
                $body .= $signature . $edit_message; // add it back in
            } else {
                // reverse Phorum's pre-processing
                $body = $message['body'];
                // order is important
                $body = str_replace("<phorum break>\n", "\n", $body);
                $body = str_replace(array('&lt;','&gt;','&amp;', '&quot;'), array('<','>','&','"'), $body);
                if (!$message_id && defined('PHORUM_CONTROL_CENTER')) {
                    // we're in control.php, so it was double-escaped
                    $body = str_replace(array('&lt;','&gt;','&amp;', '&quot;'), array('<','>','&','"'), $body);
                }
            }

            $body = $purifier->purify($body);

            // dynamically update the cache (MUST BE DONE HERE!)
            // this is inefficient because it's one db call per
            // cache miss, but once the cache is in place things are
            // a lot zippier.

            if ($message_id) { // make sure it's not a fake id
                $updated_message['meta'] = $message['meta'];
                $updated_message['meta']['body_cache'] = base64_encode($body);
                $updated_message['meta']['body_cache_serial'] = $cache_serial;
                phorum_db_update_message($message_id, $updated_message);
            }

            // must not get overloaded until after we cache it, otherwise
            // we'll inadvertently change the original text
            $data[$message_id]['body'] = $body;

        }
    }

    return $data;
}

// -----------------------------------------------------------------------
// This is fragile code, copied from read.php:596 (Phorum 5.2.6). Please
// keep this code in-sync with Phorum

/**
 * Generates a signature based on a message array
 */
function phorum_htmlpurifier_generate_sig($row) {
    $phorum_sig = '';
    if(isset($row["user"]["signature"])
       && isset($row['meta']['show_signature']) && $row['meta']['show_signature']==1){
           $phorum_sig=trim($row["user"]["signature"]);
           if(!empty($phorum_sig)){
               $phorum_sig="\n\n$phorum_sig";
           }
    }
    return $phorum_sig;
}

/**
 * Generates an edit message based on a message array
 */
function phorum_htmlpurifier_generate_editmessage($row) {
    $PHORUM = $GLOBALS['PHORUM'];
    $editmessage = '';
    if(isset($row['meta']['edit_count']) && $row['meta']['edit_count'] > 0) {
        $editmessage = str_replace ("%count%", $row['meta']['edit_count'], $PHORUM["DATA"]["LANG"]["EditedMessage"]);
        $editmessage = str_replace ("%lastedit%", phorum_date($PHORUM["short_date_time"],$row['meta']['edit_date']),  $editmessage);
        $editmessage = str_replace ("%lastuser%", $row['meta']['edit_username'],  $editmessage);
        $editmessage = "\n\n\n\n$editmessage";
    }
    return $editmessage;
}

// End fragile code
// -----------------------------------------------------------------------

/**
 * Removes the signature and edit message from a message
 * @param $row Message passed by reference
 */
function phorum_htmlpurifier_remove_sig_and_editmessage(&$row) {
    $signature = phorum_htmlpurifier_generate_sig($row);
    $editmessage = phorum_htmlpurifier_generate_editmessage($row);
    $replacements = array();
    // we need to remove add <phorum break> as that is the form these
    // extra bits are in.
    if ($signature) $replacements[str_replace("\n", "<phorum break>\n", $signature)] = '';
    if ($editmessage) $replacements[str_replace("\n", "<phorum break>\n", $editmessage)] = '';
    $row['body'] = strtr($row['body'], $replacements);
    return array($signature, $editmessage);
}

/**
 * Indicate that data is fully HTML and not from migration, invalidate
 * previous caches
 * @note This function could generate the actual cache entries, but
 *       since there's data missing that must be deferred to the first read
 */
function phorum_htmlpurifier_posting($message) {
    $PHORUM = $GLOBALS["PHORUM"];
    unset($message['meta']['body_cache']); // invalidate the cache
    $message['meta']['body_cache_serial'] = $PHORUM['mod_htmlpurifier']['body_cache_serial'];
    return $message;
}

/**
 * Overload quoting mechanism to prevent default, mail-style quote from happening
 */
function phorum_htmlpurifier_quote($array) {
    $PHORUM = $GLOBALS["PHORUM"];
    $purifier =& HTMLPurifier::getInstance();
    $text = $purifier->purify($array[1]);
    $source = htmlspecialchars($array[0]);
    return "<blockquote cite=\"$source\">\n$text\n</blockquote>";
}

/**
 * Ensure that our format hook is processed last. Also, loads the library.
 * @credits <http://secretsauce.phorum.org/snippets/make_bbcode_last_formatter.php.txt>
 */
function phorum_htmlpurifier_common() {

    require_once(dirname(__FILE__).'/htmlpurifier/HTMLPurifier.auto.php');
    require(dirname(__FILE__).'/init-config.php');

    $config = phorum_htmlpurifier_get_config();
    HTMLPurifier::getInstance($config);

    // increment revision.txt if you want to invalidate the cache
    $GLOBALS['PHORUM']['mod_htmlpurifier']['body_cache_serial'] = $config->getSerial();

    // load migration
    if (file_exists(dirname(__FILE__) . '/migrate.php')) {
        include(dirname(__FILE__) . '/migrate.php');
    } else {
        echo '<strong>Error:</strong> No migration path specified for HTML Purifier, please check
        <tt>modes/htmlpurifier/migrate.bbcode.php</tt> for instructions on
        how to migrate from your previous markup language.';
        exit;
    }

    if (!function_exists('phorum_htmlpurifier_migrate')) {
        // Dummy function
        function phorum_htmlpurifier_migrate($data) {return $data;}
    }

}

/**
 * Pre-emptively performs purification if it looks like a WYSIWYG editor
 * is being used
 */
function phorum_htmlpurifier_before_editor($message) {
    if (!empty($GLOBALS['PHORUM']['mod_htmlpurifier']['wysiwyg'])) {
        if (!empty($message['body'])) {
            $body = $message['body'];
            // de-entity-ize contents
            $body = str_replace(array('&lt;','&gt;','&amp;'), array('<','>','&'), $body);
            $purifier =& HTMLPurifier::getInstance();
            $body = $purifier->purify($body);
            // re-entity-ize contents
            $body = htmlspecialchars($body, ENT_QUOTES, $GLOBALS['PHORUM']['DATA']['CHARSET']);
            $message['body'] = $body;
        }
    }
    return $message;
}

function phorum_htmlpurifier_editor_after_subject() {
    // don't show this message if it's a WYSIWYG editor, since it will
    // then be handled automatically
    if (!empty($GLOBALS['PHORUM']['mod_htmlpurifier']['wysiwyg'])) {
        $i = $GLOBALS['PHORUM']['DATA']['MODE'];
        if ($i == 'quote' || $i == 'edit' || $i == 'moderation') {
          ?>
          <div>
            <p>
              <strong>Notice:</strong> HTML has been scrubbed for your safety.
              If you would like to see the original, turn off WYSIWYG mode
              (consult your administrator for details.)
            </p>
          </div>
          <?php
        }
        return;
    }
    if (!empty($GLOBALS['PHORUM']['mod_htmlpurifier']['suppress_message'])) return;
    ?><div class="htmlpurifier-help">
    <p>
        <strong>HTML input</strong> is enabled. Make sure you escape all HTML and
        angled brackets with <code>&amp;lt;</code> and <code>&amp;gt;</code>.
    </p><?php
            $purifier =& HTMLPurifier::getInstance();
            $config = $purifier->config;
            if ($config->get('AutoFormat.AutoParagraph')) {
                ?><p>
                    <strong>Auto-paragraphing</strong> is enabled. Double
                    newlines will be converted to paragraphs; for single
                    newlines, use the <code>pre</code> tag.
                </p><?php
            }
            $html_definition = $config->getDefinition('HTML');
            $allowed = array();
            foreach ($html_definition->info as $name => $x) $allowed[] = "<code>$name</code>";
            sort($allowed);
            $allowed_text = implode(', ', $allowed);
            ?><p><strong>Allowed tags:</strong> <?php
            echo $allowed_text;
            ?>.</p><?php
        ?>
    </p>
    <p>
        For inputting literal code such as HTML and PHP for display, use
        CDATA tags to auto-escape your angled brackets, and <code>pre</code>
        to preserve newlines:
    </p>
    <pre>&lt;pre&gt;&lt;![CDATA[
<em>Place code here</em>
]]&gt;&lt;/pre&gt;</pre>
    <p>
        Power users, you can hide this notice with:
        <pre>.htmlpurifier-help {display:none;}</pre>
    </p>
    </div><?php
}

// vim: et sw=4 sts=4
