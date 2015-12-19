<?php namespace Roots\Sage\Assets;

/**
 * Class JsonManifest
 * @package Roots\Sage
 * @author QWp6t
 */
class JsonManifest implements ManifestInterface {
  /** @var array */
  protected $manifest = [];

  /**
   * JsonManifest constructor
   * @param string $manifestPath Local filesystem path to JSON-encoded manifest
   */
  public function __construct($manifestPath) {
    $this->manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
  }

  /** @inheritdoc */
  public function get($file) {
    return isset($this->manifest[$file]) ? $this->manifest[$file] : $file;
  }

  /** @inheritdoc */
  public function getAll() {
    return $this->manifest;
  }
}
