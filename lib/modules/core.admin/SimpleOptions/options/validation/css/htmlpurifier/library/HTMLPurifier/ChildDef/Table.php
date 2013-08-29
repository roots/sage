<?php

/**
 * Definition for tables.  The general idea is to extract out all of the
 * essential bits, and then reconstruct it later.
 *
 * This is a bit confusing, because the DTDs and the W3C
 * validators seem to disagree on the appropriate definition. The
 * DTD claims:
 *
 *      (CAPTION?, (COL*|COLGROUP*), THEAD?, TFOOT?, TBODY+)
 *
 * But actually, the HTML4 spec then has this to say:
 *
 *      The TBODY start tag is always required except when the table
 *      contains only one table body and no table head or foot sections.
 *      The TBODY end tag may always be safely omitted.
 *
 * So the DTD is kind of wrong.  The validator is, unfortunately, kind
 * of on crack.
 *
 * The definition changed again in XHTML1.1; and in my opinion, this
 * formulation makes the most sense.
 *
 *      caption?, ( col* | colgroup* ), (( thead?, tfoot?, tbody+ ) | ( tr+ ))
 *
 * Essentially, we have two modes: thead/tfoot/tbody mode, and tr mode.
 * If we encounter a thead, tfoot or tbody, we are placed in the former
 * mode, and we *must* wrap any stray tr segments with a tbody. But if
 * we don't run into any of them, just have tr tags is OK.
 */
class HTMLPurifier_ChildDef_Table extends HTMLPurifier_ChildDef
{
    public $allow_empty = false;
    public $type = 'table';
    public $elements = array('tr' => true, 'tbody' => true, 'thead' => true,
        'tfoot' => true, 'caption' => true, 'colgroup' => true, 'col' => true);
    public function __construct() {}
    public function validateChildren($tokens_of_children, $config, $context) {
        if (empty($tokens_of_children)) return false;

        // this ensures that the loop gets run one last time before closing
        // up. It's a little bit of a hack, but it works! Just make sure you
        // get rid of the token later.
        $tokens_of_children[] = false;

        // only one of these elements is allowed in a table
        $caption = false;
        $thead   = false;
        $tfoot   = false;

        // as many of these as you want
        $cols    = array();
        $content = array();

        $nesting = 0; // current depth so we can determine nodes
        $is_collecting = false; // are we globbing together tokens to package
                                // into one of the collectors?
        $collection = array(); // collected nodes
        $tag_index = 0; // the first node might be whitespace,
                            // so this tells us where the start tag is
        $tbody_mode = false; // if true, then we need to wrap any stray
                             // <tr>s with a <tbody>.

        foreach ($tokens_of_children as $token) {
            $is_child = ($nesting == 0);

            if ($token === false) {
                // terminating sequence started
            } elseif ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            // handle node collection
            if ($is_collecting) {
                if ($is_child) {
                    // okay, let's stash the tokens away
                    // first token tells us the type of the collection
                    switch ($collection[$tag_index]->name) {
                        case 'tbody':
                            $tbody_mode = true;
                        case 'tr':
                            $content[] = $collection;
                            break;
                        case 'caption':
                            if ($caption !== false) break;
                            $caption = $collection;
                            break;
                        case 'thead':
                        case 'tfoot':
                            $tbody_mode = true;
                            // XXX This breaks rendering properties with
                            // Firefox, which never floats a <thead> to
                            // the top. Ever. (Our scheme will float the
                            // first <thead> to the top.)  So maybe
                            // <thead>s that are not first should be
                            // turned into <tbody>? Very tricky, indeed.

                            // access the appropriate variable, $thead or $tfoot
                            $var = $collection[$tag_index]->name;
                            if ($$var === false) {
                                $$var = $collection;
                            } else {
                                // Oops, there's a second one! What
                                // should we do?  Current behavior is to
                                // transmutate the first and last entries into
                                // tbody tags, and then put into content.
                                // Maybe a better idea is to *attach
                                // it* to the existing thead or tfoot?
                                // We don't do this, because Firefox
                                // doesn't float an extra tfoot to the
                                // bottom like it does for the first one.
                                $collection[$tag_index]->name = 'tbody';
                                $collection[count($collection)-1]->name = 'tbody';
                                $content[] = $collection;
                            }
                            break;
                         case 'colgroup':
                            $cols[] = $collection;
                            break;
                    }
                    $collection = array();
                    $is_collecting = false;
                    $tag_index = 0;
                } else {
                    // add the node to the collection
                    $collection[] = $token;
                }
            }

            // terminate
            if ($token === false) break;

            if ($is_child) {
                // determine what we're dealing with
                if ($token->name == 'col') {
                    // the only empty tag in the possie, we can handle it
                    // immediately
                    $cols[] = array_merge($collection, array($token));
                    $collection = array();
                    $tag_index = 0;
                    continue;
                }
                switch($token->name) {
                    case 'caption':
                    case 'colgroup':
                    case 'thead':
                    case 'tfoot':
                    case 'tbody':
                    case 'tr':
                        $is_collecting = true;
                        $collection[] = $token;
                        continue;
                    default:
                        if (!empty($token->is_whitespace)) {
                            $collection[] = $token;
                            $tag_index++;
                        }
                        continue;
                }
            }
        }

        if (empty($content)) return false;

        $ret = array();
        if ($caption !== false) $ret = array_merge($ret, $caption);
        if ($cols !== false)    foreach ($cols as $token_array) $ret = array_merge($ret, $token_array);
        if ($thead !== false)   $ret = array_merge($ret, $thead);
        if ($tfoot !== false)   $ret = array_merge($ret, $tfoot);

        if ($tbody_mode) {
            // a little tricky, since the start of the collection may be
            // whitespace
            $inside_tbody = false;
            foreach ($content as $token_array) {
                // find the starting token
                foreach ($token_array as $t) {
                    if ($t->name === 'tr' || $t->name === 'tbody') {
                        break;
                    }
                } // iterator variable carries over
                if ($t->name === 'tr') {
                    if ($inside_tbody) {
                        $ret = array_merge($ret, $token_array);
                    } else {
                        $ret[] = new HTMLPurifier_Token_Start('tbody');
                        $ret = array_merge($ret, $token_array);
                        $inside_tbody = true;
                    }
                } elseif ($t->name === 'tbody') {
                    if ($inside_tbody) {
                        $ret[] = new HTMLPurifier_Token_End('tbody');
                        $inside_tbody = false;
                        $ret = array_merge($ret, $token_array);
                    } else {
                        $ret = array_merge($ret, $token_array);
                    }
                } else {
                    trigger_error("tr/tbody in content invariant failed in Table ChildDef", E_USER_ERROR);
                }
            }
            if ($inside_tbody) {
                $ret[] = new HTMLPurifier_Token_End('tbody');
            }
        } else {
            foreach ($content as $token_array) {
                // invariant: everything in here is <tr>s
                $ret = array_merge($ret, $token_array);
            }
        }

        if (!empty($collection) && $is_collecting == false){
            // grab the trailing space
            $ret = array_merge($ret, $collection);
        }

        array_pop($tokens_of_children); // remove phantom token

        return ($ret === $tokens_of_children) ? true : $ret;

    }
}

// vim: et sw=4 sts=4
