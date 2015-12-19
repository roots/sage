<?php namespace Roots\Sage;

use Roots\Sage\Template\IWrapper;

/**
 * Class Template
 * @package Roots\Sage
 * @author QWp6t
 */
class Template {
  protected static $root = 'templates/';

  /** @var IWrapper[] */
  protected static $wrappers = [];

  protected $templates = [];

  protected $context = [];

  protected $html = '';

  /**
   * @param IWrapper $wrapper
   * @param array $context Variables to pass to wrapper
   * @return static Template instance of wrapper
   */
  public static function wrap(IWrapper $wrapper, $context = []) {
    self::$wrappers[$wrapper->getSlug()] = $wrapper;
    return new static($wrapper->getWrappers(), $context);
  }

  /**
   * @param string $slug
   * @param array $context
   * @return static
   */
  public static function unwrap($slug = '', $context = []) {
    if (!$slug) {
      // If no slug is specified, we grab the most recently wrapped item
      end(self::$wrappers);
      $slug = key(self::$wrappers);
    }
    return new static(self::$wrappers[$slug]->getTemplate(), $context);
  }

  /**
   * Converts a delimeted template file into an array of parts
   *
   * Example:
   *   Template::getConvertedTemplateParts('content-single-audio.php');
   *   => ['content-single-audio.php', 'content-single.php', 'content.php']
   *
   * The returned value can then be passed to WordPress's locate_template.
   *
   * @param string $template
   * @param string $delimeter
   * @return array
   */
  public static function convertParts($template, $delimeter = '-') {
    $templateParts = explode($delimeter, str_replace('.php', '', (string) $template));
    $templates[] = array_shift($templateParts);
    foreach ($templateParts as $i => $templatePart) {
      $templates[] = $templates[$i] . $delimeter . $templatePart;
    }
    return array_reverse($templates);
  }

  /**
   * Template constructor
   * @param string|string[] $template
   * @param array $context
   */
  public function __construct($template, array $context = []) {
    $this->set($template);
    $this->context = $context;
  }

  /**
   * @return string HTML
   * @see get
   */
  public function __toString() {
    return $this->get();
  }

  /**
   * Echoes the output HTML
   * @see get
   */
  public function render() {
    echo $this->get();
  }

  /**
   * @return string HTML
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public function get() {
    /** @noinspection PhpUnusedLocalVariableInspection $context is passed to the included template */
    $context = $this->context;
    extract($this->context);
    ob_start();
    if ($template = $this->locate()) {
      /** @noinspection PhpIncludeInspection */
      include $template;
    }
    $this->html = ob_get_clean() ?: '';
    return $this->html;
  }

  /**
   * @param string[]|string $template
   */
  public function set($template) {
    if (is_array($template)) {
      $this->templates = self::format($template);
      return;
    }
    if (!is_string($template) || !(string) $template) {
      return;
    }
    // At this point, we assume it's something like `content-single.php` or `content-single-audio.php`
    $this->templates = self::format(self::convertParts($template));
  }

  /**
   * Ensures that each template in $this->templates is appended with `.php`
   * @param $templates
   * @return array
   */
  protected static function format($templates) {
    return array_map(function ($template) {
      if (substr($template, -4, 4) === '.php') {
        return $template;
      }
      return $template . '.php';
    }, $templates);
  }

  /**
   * @param string $templateDir Specify a template directory relative to your theme directory; e.g., `templates/`, `templates/partials/`, `woocommerce/`
   * @return string Filename
   */
  public function locate($templateDir = '') {
    $templates = array_map(function ($template) use ($templateDir) {
      return ($templateDir ?: self::$root) . $template;
    }, $this->templates);
    $template = locate_template($templates);
    return apply_filters('sage/locate_template', $template, $templates) ?: $template;
  }
}
