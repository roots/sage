<?php

namespace Roots\Sage\Sidebar;

/**
 * Determines whether or not to display the sidebar based on an array of conditional tags or page templates.
 *
 * If any of the is_* conditional tags or is_page_template(template_file) checks return true, the sidebar will NOT be displayed.
 *
 * @link http://roots.io/getting-started/theme-sidebar/
 *
 * @param array list of conditional tags (http://codex.wordpress.org/Conditional_Tags)
 * @param array list of page templates. These will be checked via is_page_template()
 *
 * @return boolean True will display the sidebar, False will not
 */
class SageSidebar {
  private $conditionals;
  private $templates;

  public $display = true;

  public function __construct($conditionals = [], $templates = []) {
    $this->conditionals = $conditionals;
    $this->templates    = $templates;

    $conditionals = array_map([$this, 'checkConditionalTag'], $this->conditionals);
    $templates    = array_map([$this, 'checkPageTemplate'], $this->templates);

    if (in_array(true, $conditionals) || in_array(true, $templates)) {
      $this->display = false;
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

  private function checkPageTemplate($page_template) {
    return is_page_template($page_template);
  }
}
