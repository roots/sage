<?php namespace Roots\Sage\Template;

/**
 * Class Wrapper
 * @package Roots\Sage
 * @author QWp6t
 */
class Wrapper implements WrapperInterface {
  /** @var string Wrapper slug */
  protected $slug;

  /** @var string Template file that is being wrapped */
  protected $template = '';

  /** @var string[] Array of template wrappers; e.g., `base-singular.php`, `base-page.php`, `base.php` */
  protected $wrappers = [];

  /**
   * Wrapper constructor
   *
   * @param string $templateSlug Template slug, typically from Template Heirarchy; e.g., `page`, `single`, `singular`
   * @param string $base Wrapper's base template, this is what will wrap around $template
   */
  public function __construct($templateSlug, $base = 'layouts/base.php') {
    $this->slug = sanitize_title(basename($base, '.php'));
    $this->wrappers = [$base];
    $this->template = $templateSlug;
    $str = substr($base, 0, -4);
    array_unshift($this->wrappers, sprintf($str . '-%s.php', $templateSlug));
  }

  /** {@inheritdoc} */
  public function getWrappers() {
    $this->wrappers = apply_filters('sage/wrap_' . $this->slug, $this->wrappers) ?: $this->wrappers;
    return $this->wrappers;
  }

  /** {@inheritdoc} */
  public function getSlug() {
    return $this->slug;
  }

  /** {@inheritdoc} */
  public function getTemplate() {
    return $this->template;
  }
}
