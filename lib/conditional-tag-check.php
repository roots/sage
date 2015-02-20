<?php

namespace Roots\Sage;

/**
 * Utility class which takes an array of conditional tags (or any function which returns a boolean)
 * and returns `true` if *any* of them are `true`, and `false` otherwise.
 *
 * @param array list of conditional tags (http://codex.wordpress.org/Conditional_Tags)
 *        or custom function which returns a boolean
 *
 * @return boolean
 */
class ConditionalTagCheck {
  private $conditionals;

  public $result = true;

  public function __construct($conditionals = []) {
    $this->conditionals = $conditionals;

    $conditionals = array_map([$this, 'checkConditionalTag'], $this->conditionals);

    if (in_array(true, $conditionals)) {
      $this->result = false;
    }
  }

  private function checkConditionalTag($conditional_tag) {
    $conditional_arg = is_array($conditional_tag) ? $conditional_tag[1] : false;
    $conditional_tag = $conditional_arg ? $conditional_tag[0] : $conditional_tag;

    if (function_exists($conditional_tag)) {
      return $conditional_arg ? $conditional_tag($conditional_arg) : $conditional_tag();
    } else {
      return false;
    }
  }
}
