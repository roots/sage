<?php

namespace Roots\Sage\Template;

use Jenssegers\Blade\Blade;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Contracts\Container\Container as ContainerContract;

class BladeProvider extends Blade
{
    /** @var Blade */
    public $blade;

    /** @var string */
    protected $cachePath;

    /**
     * Constructor.
     *
     * @param array             $viewPaths
     * @param string            $cachePath
     * @param ContainerContract $container
     */
    public function __construct($viewPaths, $cachePath, ContainerContract $container = null)
    {
        parent::__construct((array) $viewPaths, $cachePath, $container);
        $this->registerViewFinder();
    }

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {
        return $this->container['view']->make($this->normalizeViewPath($view), $data, $mergeData);
    }

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return string
     */
    public function render($view, $data = [], $mergeData = [])
    {
        return $this->make($view, $data, $mergeData)->render();
    }

    /**
     * @param string $file
     * @param array $data
     * @param array $mergeData
     * @return string
     */
    public function compiledPath($file, $data = [], $mergeData = [])
    {
        $rendered = $this->make($file, $data, $mergeData);
        $engine = $rendered->getEngine();

        if (!($engine instanceof CompilerEngine)) {
            // Using PhpEngine, so just return the file
            return $file;
        }

        $compiler = $engine->getCompiler();
        $compiledPath = $compiler->getCompiledPath($rendered->getPath());
        if ($compiler->isExpired($compiledPath)) {
            $compiler->compile($file);
        }
        return $compiledPath;
    }

    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->container->bind('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * @param string $file
     * @return string
     */
    public function normalizeViewPath($file)
    {
        // Convert `\` to `/`
        $view = str_replace('\\', '/', $file);

        // Remove unnecessary parts of the path
        $remove = array_merge($this->viewPaths, array_map('basename', $this->viewPaths), ['.blade.php', '.php']);
        $view = str_replace($remove, '', $view);

        // Remove leading slashes
        $view = ltrim($view, '/');

        // Convert `/` to `.`
        return str_replace('/', '.', $view);
    }
}
