<?php
namespace App;

/**
 * Check hot reload status
 *
 * @return boolean
 */
function hmr_enabled(): bool
{
    return env('HMR_ENABLED') ?: !file_exists(get_theme_file_path('/public/manifest.json'));
}

/**
 * Build assets hmr uri
 *
 * @param string $asset
 *
 * @return string
 */
function hmr_assets(string $asset): string
{
    $entrypoint = env('HMR_ENTRYPOINT') ?: 'http://localhost:3000';

    return $entrypoint ? "{$entrypoint}/{$asset}" : asset($asset);
}
