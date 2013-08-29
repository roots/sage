<?php

/**
 * Definition for list containers ul and ol.
 */
class HTMLPurifier_ChildDef_List extends HTMLPurifier_ChildDef
{
    public $type = 'list';
    // lying a little bit, so that we can handle ul and ol ourselves
    // XXX: This whole business with 'wrap' is all a bit unsatisfactory
    public $elements = array('li' => true, 'ul' => true, 'ol' => true);
    public function validateChildren($tokens_of_children, $config, $context) {
        // Flag for subclasses
        $this->whitespace = false;

        // if there are no tokens, delete parent node
        if (empty($tokens_of_children)) return false;

        // the new set of children
        $result = array();

        // current depth into the nest
        $nesting = 0;

        // a little sanity check to make sure it's not ALL whitespace
        $all_whitespace = true;

        $seen_li = false;
        $need_close_li = false;

        foreach ($tokens_of_children as $token) {
            if (!empty($token->is_whitespace)) {
                $result[] = $token;
                continue;
            }
            $all_whitespace = false; // phew, we're not talking about whitespace

            if ($nesting == 1 && $need_close_li) {
                $result[] = new HTMLPurifier_Token_End('li');
                $nesting--;
                $need_close_li = false;
            }

            $is_child = ($nesting == 0);

            if ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            if ($is_child) {
                if ($token->name === 'li') {
                    // good
                    $seen_li = true;
                } elseif ($token->name === 'ul' || $token->name === 'ol') {
                    // we want to tuck this into the previous li
                    $need_close_li = true;
                    $nesting++;
                    if (!$seen_li) {
                        // create a new li element
                        $result[] = new HTMLPurifier_Token_Start('li');
                    } else {
                        // backtrack until </li> found
                        while(true) {
                            $t = array_pop($result);
                            if ($t instanceof HTMLPurifier_Token_End) {
                                // XXX actually, these invariants could very plausibly be violated
                                // if we are doing silly things with modifying the set of allowed elements.
                                // FORTUNATELY, it doesn't make a difference, since the allowed
                                // elements are hard-coded here!
                                if ($t->name !== 'li') {
                                    trigger_error("Only li present invariant violated in List ChildDef", E_USER_ERROR);
                                    return false;
                                }
                                break;
                            } elseif ($t instanceof HTMLPurifier_Token_Empty) { // bleagh
                                if ($t->name !== 'li') {
                                    trigger_error("Only li present invariant violated in List ChildDef", E_USER_ERROR);
                                    return false;
                                }
                                // XXX this should have a helper for it...
                                $result[] = new HTMLPurifier_Token_Start('li', $t->attr, $t->line, $t->col, $t->armor);
                                break;
                            } else {
                                if (!$t->is_whitespace) {
                                    trigger_error("Only whitespace present invariant violated in List ChildDef", E_USER_ERROR);
                                    return false;
                                }
                            }
                        }
                    }
                } else {
                    // start wrapping (this doesn't precisely mimic
                    // browser behavior, but what browsers do is kind of
                    // hard to mimic in a standards compliant way
                    // XXX Actually, this has no impact in practice,
                    // because this gets handled earlier. Arguably,
                    // we should rip out all of that processing
                    $result[] = new HTMLPurifier_Token_Start('li');
                    $nesting++;
                    $seen_li = true;
                    $need_close_li = true;
                }
            }
            $result[] = $token;
        }
        if ($need_close_li) {
            $result[] = new HTMLPurifier_Token_End('li');
        }
        if (empty($result)) return false;
        if ($all_whitespace) {
            return false;
        }
        if ($tokens_of_children == $result) return true;
        return $result;
    }
}

// vim: et sw=4 sts=4
