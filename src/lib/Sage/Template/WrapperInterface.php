<?php

namespace Roots\Sage\Template;

/**
 * Interface WrapperInterface
 * @package Roots\Sage
 * @author QWp6t
 */
interface WrapperInterface
{

    /**
     * Get wrapper template file
     *
     * @return string Wrapper template (FQPN of, e.g., `base-page.php`, `base.php`)
     */
    public function wrap();

    /**
     * @return string Wrapped template (FQPN of, e.g., `page.php`, `single.php`, `singular.php`)
     */
    public function unwrap();

    /**
     * @return string Slug of the WrapperInterface; e.g., `base`
     */
    public function slug();
}
