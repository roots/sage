<?php

namespace Roots\Sage;

use Roots\Sage\Template\Partial;
use Roots\Sage\Template\WrapperInterface;

class Template
{
    /** @var Template[] */
    public static $instances = [];

    /** @var WrapperInterface */
    protected $wrapper;

    public function __construct(WrapperInterface $wrapper)
    {
        $this->wrapper = $wrapper;
        self::$instances[$wrapper->slug()] = $this;
    }

    /**
     * @return string Layout (FQPN of, e.g., `base-page.php`, `base.php`)
     */
    public function layout()
    {
        return $this->wrapper->wrap();
    }

    /**
     * @return string Main template (FQPN of, e.g., `page.php`, `single.php`, `singular.php`)
     */
    public function main()
    {
        return $this->wrapper->unwrap();
    }

    /**
     * @param string $template Delimited template path
     * @return string Partial template (FQPN of, e.g., `content.php`, `page-header.php`
     */
    public function partial($template)
    {
        return (new Partial($template, $this->main()))->path();
    }
}
