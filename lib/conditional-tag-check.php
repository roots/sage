<?php

namespace Roots\Sage;

/**
 * Utility class which takes an array of conditional tags (or any function which returns a boolean)
 * and returns `false` if *any* of them are `true`, and `true` otherwise.
 *
 * @param array list of conditional tags (http://codex.wordpress.org/Conditional_Tags)
 *        or custom function which returns a boolean
 *
 * @return boolean
 */
class ConditionalTagCheck
{

    private $conditionals;

    public $result = true;

    public function __construct($conditionals = [])
    {
        $this->conditionals = $conditionals;

        $conditionals = array_map([$this, 'checkConditionalTag'], $this->conditionals);

        if (in_array(true, $conditionals)) {
            $this->result = false;
        }
    }

    private function checkConditionalTag($conditional)
    {
        if (is_array($conditional)) {
            list($tag, $args) = $conditional;
        } else {
            $tag = $conditional;
            $args = false;
        }

        if (function_exists($tag)) {
            return $args ? $tag($args) : $tag();
        } else {
            return false;
        }
    }
}
