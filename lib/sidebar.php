<?php
/**
 * Determines whether or not to display the sidebar based on an array of conditional tags or page templates.
 *
 * If any of the is_* conditional tags or is_page_template(template_file) checks return true, the sidebar will NOT be displayed.
 *
 * @param array list of conditional tags (http://codex.wordpress.org/Conditional_Tags) without the 'is_' prefix
 * @param array list of templates without the '.php' extension. These will be checked via is_page_template()
 *
 * @return boolean True will display the sidebar, False will not
 *
 */
class Roots_Sidebar {
  const EXTENSION = '.php';
  private $conditionals;
  private $templates;
  public $display = true;

  function __construct($conditionals = array(), $templates = array()) {
    $this->conditionals = $conditionals;
    $this->templates    = $templates;

    foreach($this->conditionals as $conditional_tag) {
      if ($this->check_conditional_tag($conditional_tag)) {
        $this->display = false;
      }
    }

    foreach($this->templates as $page_template) {
      if ($this->check_page_template($page_template)) {
        $this->display = false;
      }
    }
  }

  private function check_conditional_tag($conditional_tag) {
    $conditional_tag_function = "is_$conditional_tag";
    return $conditional_tag_function();
  }

  private function check_page_template($page_template) {
    return is_page_template($page_template . self::EXTENSION);
  }
}
?>
