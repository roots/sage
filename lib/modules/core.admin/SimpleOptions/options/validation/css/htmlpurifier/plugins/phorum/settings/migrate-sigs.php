<?php

function phorum_htmlpurifier_migrate_sigs_check() {
    global $PHORUM;
    $offset = 0;
    if (!empty($_POST['migrate-sigs'])) {
        if (!isset($_POST['confirmation']) || strtolower($_POST['confirmation']) !== 'yes') {
            echo 'Invalid confirmation code.';
            exit;
        }
        $PHORUM['mod_htmlpurifier']['migrate-sigs'] = true;
        phorum_db_update_settings(array("mod_htmlpurifier"=>$PHORUM["mod_htmlpurifier"]));
        $offset = 1;
    } elseif (!empty($_GET['migrate-sigs']) && $PHORUM['mod_htmlpurifier']['migrate-sigs']) {
        $offset = (int) $_GET['migrate-sigs'];
    }
    return $offset;
}

function phorum_htmlpurifier_migrate_sigs($offset) {
    global $PHORUM;

    if(!$offset) return; // bail out quick if $offset == 0

    // theoretically, we could get rid of this multi-request
    // doo-hickery if safe mode is off
    @set_time_limit(0); // attempt to let this run
    $increment = $PHORUM['mod_htmlpurifier']['migrate-sigs-increment'];

    require_once(dirname(__FILE__) . '/../migrate.php');
    // migrate signatures
    // do this in batches so we don't run out of time/space
    $end = $offset + $increment;
    $user_ids = array();
    for ($i = $offset; $i < $end; $i++) {
        $user_ids[] = $i;
    }
    $userinfos = phorum_db_user_get_fields($user_ids, 'signature');
    foreach ($userinfos as $i => $user) {
        if (empty($user['signature'])) continue;
        $sig = $user['signature'];
        // perform standard Phorum processing on the sig
        $sig = str_replace(array("&","<",">"), array("&amp;","&lt;","&gt;"), $sig);
        $sig = preg_replace("/<((http|https|ftp):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%]+?)>/i", "$1", $sig);
        // prepare fake data to pass to migration function
        $fake_data = array(array("author"=>"", "email"=>"", "subject"=>"", 'body' => $sig));
        list($fake_message) = phorum_htmlpurifier_migrate($fake_data);
        $user['signature'] = $fake_message['body'];
        if (!phorum_api_user_save($user)) {
            exit('Error while saving user data');
        }
    }
    unset($userinfos); // free up memory

    // query for highest ID in database
    $type = $PHORUM['DBCONFIG']['type'];
    $sql = "select MAX(user_id) from {$PHORUM['user_table']}";
    $row = phorum_db_interact(DB_RETURN_ROW, $sql);
    $top_id = (int) $row[0];

    $offset += $increment;
    if ($offset > $top_id) { // test for end condition
        echo 'Migration finished';
        $PHORUM['mod_htmlpurifier']['migrate-sigs'] = false;
        phorum_htmlpurifier_commit_settings();
        return true;
    }
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'admin.php?module=modsettings&mod=htmlpurifier&migrate-sigs=' . $offset;
    // relies on output buffering to work
    header("Location: http://$host$uri/$extra");
    exit;

}

// vim: et sw=4 sts=4
