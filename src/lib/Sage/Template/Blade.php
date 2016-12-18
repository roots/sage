<?php

namespace Roots\Sage\Template;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\ViewFinderInterface;

/**
 * Class BladeProvider
 * @method \Illuminate\View\View file($file, $data = [], $mergeData = [])
 * @method \Illuminate\View\View make($file, $data = [], $mergeData = [])
 */
class Blade
{
    /** @var ContainerContract */
    protected $app;

    public function __construct(FactoryContract $env, ContainerContract $app)
    {
        $this->env = $env;
        $this->app = $app;
    }

    /**
     * Get the compiler
     *
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public function compiler()
    {
        static $engineResolver;
        if (!$engineResolver) {
            $engineResolver = $this->app->make('view.engine.resolver');
        }
        return $engineResolver->resolve('blade')->getCompiler();
    }

    /**
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     * @return string
     */
    public function render($view, $data = [], $mergeData = [])
    {
        /** @var \Illuminate\Contracts\Filesystem\Filesystem $filesystem */
        $filesystem = $this->app['files'];
        return $this->{$filesystem->exists($view) ? 'file' : 'make'}($view, $data, $mergeData)->render();
    }

    /**
     * @param string $file
     * @param array  $data
     * @param array  $mergeData
     * @return string
     */
    public function compiledPath($file, $data = [], $mergeData = [])
    {
        $rendered = $this->file($file, $data, $mergeData);
        /** @var EngineInterface $engine */
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
     * @param string $file
     * @return string
     */
    public function normalizeViewPath($file)
    {
        // Convert `\` to `/`
        $view = str_replace('\\', '/', $file);

        // Add namespace to path if necessary
        $view = $this->applyNamespaceToPath($view);

        // Remove unnecessary parts of the path
        $view = str_replace(array_merge($this->app['config']['view.paths'], ['.blade.php', '.php']), '', $view);

        // Remove superfluous and leading slashes
        return ltrim(preg_replace('%//+%', '/', $view), '/');
    }

    /**
     * Convert path to view namespace
     * @param string $path
     * @return string
     */
    public function applyNamespaceToPath($path)
    {
        /** @var ViewFinderInterface $finder */
        $finder = $this->app['view.finder'];
        if (!method_exists($finder, 'getHints')) {
            return $path;
        }
        $delimiter = $finder::HINT_PATH_DELIMITER;
        $hints = $finder->getHints();
        $view = array_reduce(array_keys($hints), function ($view, $namespace) use ($delimiter, $hints) {
            return str_replace($hints[$namespace], $namespace.$delimiter, $view);
        }, $path);
        return preg_replace("%{$delimiter}[\\/]*%", $delimiter, $view);
    }

    /**
     * Pass any method to the view Factory instance.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->env, $method], $params);
    }
}
