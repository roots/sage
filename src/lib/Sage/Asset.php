<?php namespace Roots\Sage;

use Roots\Sage\Assets\ManifestInterface;

/**
 * Class Template
 * @package Roots\Sage
 * @author QWp6t
 */
class Asset
{
    public static $dist = '/dist';

    /** @var ManifestInterface Currently used manifest */
    protected $manifest;

    protected $asset;

    protected $dir;

    public function __construct($file, ManifestInterface $manifest = null)
    {
        $this->manifest = $manifest;
        $this->dir = dirname($file) != '.' ? dirname($file) : '';
        $this->asset = strpos($this->dir, "/") !== false ? trailingslashit(basename($this->dir)) . basename($file) : basename($file);
    }

    public function __toString()
    {
        return $this->getUri();
    }

    public function getUri()
    {
        $file = ($this->manifest ? $this->manifest->get($this->asset) : $this->asset);
        return get_template_directory_uri() . self::$dist . '/' . $this->dir . '/' . basename($file);
    }
}
