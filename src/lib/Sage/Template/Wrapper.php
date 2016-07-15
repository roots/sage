<?php namespace Roots\Sage\Template;

/**
 * Class Wrapper
 * @package Roots\Sage
 * @author QWp6t
 */
class Wrapper implements WrapperInterface
{
    /** @var string Wrapper slug */
    protected $slug;

    /** @var string Template file that is being wrapped */
    protected $template = '';

    /** @var string[] Array of template wrappers; e.g., `base-singular.php`, `base-page.php`, `base.php` */
    protected $wrapper = [];

    /**
     * Wrapper constructor
     *
     * @param string $template Template file, as from Template Heirarchy; e.g., `page.php`, `single.php`, `singular.php`
     * @param string $base Wrapper's base template, this is what will wrap around $template
     */
    public function __construct($template, $base = 'layouts/base.php')
    {
        $this->slug = sanitize_title(basename($base, '.php'));
        $this->wrapper = [$base];
        $this->template = $template;
        $str = substr($base, 0, -4);
        array_unshift($this->wrapper, sprintf($str . '-%s.php', basename($template, '.php')));
    }

    /**
     * @return string
     * @see getTemplate
     */
    public function __toString()
    {
        return $this->getTemplate();
    }

    /** {@inheritdoc} */
    public function getWrapper()
    {
        $wrappers = apply_filters('sage/wrap_' . $this->slug, $this->wrapper) ?: $this->wrapper;
        return locate_template($wrappers);
    }

    /** {@inheritdoc} */
    public function getSlug()
    {
        return $this->slug;
    }

    /** {@inheritdoc} */
    public function getTemplate()
    {
        $template = apply_filters('sage/unwrap_' . $this->slug, $this->template) ?: $this->template;
        return locate_template($template) ?: $template;
    }
}
