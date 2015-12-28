<?php namespace Roots\Sage\Template;

/**
 * Class Wrapper
 * @package Roots\Sage
 * @author QWp6t
 */
class WrapperCollection
{
    /** @var $this */
    protected static $instance;
    /** @var WrapperInterface[] $wrappers */
    protected $wrappers = [];

    /** Singleton */
    // @codingStandardsIgnoreStart
    private function __construct() {}
    private function __clone() {}
    // @codingStandardsIgnoreEnd

    /**
     * @return static
     */
    public static function instance()
    {
        isset(self::$instance) || self::$instance = new static;
        return self::$instance;
    }

    /**
     * @param WrapperInterface $wrapper
     * @param string $slug
     * @param bool $overwriteIfExists
     * @return $this
     * @throws \Exception
     */
    public static function add(WrapperInterface $wrapper, $slug = '', $overwriteIfExists = false)
    {
        $slug = $slug ?: $wrapper->getSlug();
        if (self::instance()->exists($slug) && !$overwriteIfExists) {
            throw new \Exception("Wrapper $slug already exists.");
        }
        self::instance()->wrappers[$slug] = $wrapper;
        return self::instance();
    }

    /**
     * @param string $slug
     * @return $this
     */
    public static function remove($slug)
    {
        unset(self::instance()->wrappers[$slug]);
        return self::instance();
    }

    /**
     * @param string $slug
     * @return null|WrapperInterface
     */
    public static function get($slug)
    {
        return isset(self::instance()->wrappers[$slug]) ? self::instance()->wrappers[$slug] : null;
    }

    /**
     * @return string[] Slugs of wrappers in collection
     */
    public static function wrappers()
    {
        return array_keys(self::instance()->wrappers);
    }

    /**
     * @param $slug
     * @return bool
     */
    public static function exists($slug)
    {
        return isset(self::instance()->wrappers[$slug]);
    }
}
