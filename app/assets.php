<?php

/**
 * This file is just intended to demonstrate
 * the bare necessities of reading from the
 * `@roots/bud-wordpress-manifest` extension's
 * json output. It isn't a substitute for an actual
 * asset management solution.
 *
 * Try [@roots/sage](https://github.com/roots/sage).
 */

namespace App;

use Illuminate\Support\Collection;

/**
 * Get manifest.
 *
 * @return {Collection}
 */
function getManifest(): Collection {
    $path = realpath(get_theme_file_path('dist/entrypoints.json'));
    if (!$path) {
        throw new \WP_Error('Run yarn build');
    }

    return Collection::make(
        json_decode(
            file_get_contents(
                get_theme_file_path('dist/entrypoints.json')
            )
        )
    );
};

/**
 * Do entrypoint.
 *
 * @param  {string} name
 * @param  {string} type
 * @param  {object} entrypoint
 *
 * @return {Collection}
 */
function entrypoint(
    string $name,
    string $type,
    Object $entrypoint
): Collection {
    $entrypoint->modules = Collection::make(
        $entrypoint->$type
    );

    $hasDependencies = $type == 'js' &&
        property_exists($entrypoint, 'dependencies');

    $entrypoint->dependencies = Collection::make(
        $hasDependencies
            ? $entrypoint->dependencies
            : [],
    );

    return $entrypoint->modules->map(
        function ($module, $index)
            use ($type, $name, $entrypoint) {
            $name = "{$type}.{$name}.{$index}";

            $dependencies = $entrypoint->dependencies->all();

            $entrypoint->dependencies->push($name);

            return (object) [
                'name' => $name,
                'uri' => $module,
                'deps' => $dependencies,
            ];
        }
    );
}

/**
 * Enqueue all assets from a bundle key.
 *
 * @param  {string} bundleName
 * @return void
 */
function bundle (string $bundleName): void {
    /**
     * Filter specified bundle
     */
    $filterBundle = function ($_a, $key) use ($bundleName) {
        return $key === $bundleName;
    };

    /**
     * Prepare entrypoints
     */
    $prepEntry = function ($item, $name): object {
        return (object) [
            'js' => entrypoint($name, 'js', $item),
            'css' => entrypoint($name, 'css', $item)
        ];
    };

    /**
     * Filter out HMR assets
     */
    $filterHot = function ($entry): bool {
        return !strpos($entry->uri, 'hot-update');
    };


    /**
     * Manifest source
     */
    getManifest()

        /**
         * Filter for requested bundle
         */
        ->filter($filterBundle)

        /**
         * Prepare entrypoints
         */
        ->map($prepEntry)

        /**
         * Enqueue scripts
         */
        ->each(function ($entrypoint)
            use ($filterHot): void {
            $entrypoint
                ->js
                ->filter($filterHot)
                ->each(function ($entry) {
                    wp_enqueue_script(...[
                        $entry->name,
                        $entry->uri,
                        $entry->deps,
                        null,
                        true,
                    ]);
                });

            $entrypoint
                ->css
                ->filter($filterHot)
                ->each(function ($entry) {
                    wp_enqueue_style(...[
                        $entry->name,
                        $entry->uri,
                        $entry->deps,
                        null,
                    ]);
                });
        });
};
