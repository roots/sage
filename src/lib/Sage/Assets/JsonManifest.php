<?php

namespace Roots\Sage\Assets;

/**
 * Class JsonManifest
 * @package Roots\Sage
 * @author QWp6t
 */
class JsonManifest implements ManifestInterface
{
    /** @var array */
    public $manifest;

    /** @var string */
    public $dist;

    /**
     * JsonManifest constructor
     *
     * @param string $manifestPath Local filesystem path to JSON-encoded manifest
     * @param string $distUri Remote URI to assets root
     */
    public function __construct($manifestPath, $distUri)
    {
        $this->manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $this->dist = $distUri;
    }

    /** @inheritdoc */
    public function get($asset)
    {
        return isset($this->manifest[$asset]) ? $this->manifest[$asset] : $asset;
    }

    /** @inheritdoc */
    public function getUri($asset)
    {
        return "{$this->dist}/{$this->get($asset)}";
    }
}
