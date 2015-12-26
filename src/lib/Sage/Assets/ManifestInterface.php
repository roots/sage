<?php namespace Roots\Sage\Assets;

/**
 * Interface ManifestInterface
 * @package Roots\Sage
 * @author QWp6t
 */
interface ManifestInterface {
  /**
   * Get the cache-busted filename
   *
   * If the manifest does not have an entry for $file, then return $file
   *
   * @param string $file The original name of the file before cache-busting
   * @return string
   */
  public function get($file);

  /**
   * Get the asset manifest
   *
   * @return array
   */
  public function getAll();
}
