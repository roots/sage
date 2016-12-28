<?php

namespace Roots\Sage\Template;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\ViewServiceProvider;

/**
 * Class BladeProvider
 */
class BladeProvider extends ViewServiceProvider
{
    /**
     * @param ContainerContract $container
     * @param array             $config
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(ContainerContract $container = null, $config = [])
    {
        /** @noinspection PhpParamsInspection */
        parent::__construct($container ?: Container::getInstance());

        $this->app->bindIf('config', function () use ($config) {
            return $config;
        }, true);
    }

    /**
     * Bind required instances for the service provider.
     */
    public function register()
    {
        $this->registerFilesystem();
        $this->registerEvents();
        $this->registerEngineResolver();
        $this->registerViewFinder();
        $this->registerFactory();
        return $this;
    }

    /**
     * Register Filesystem
     */
    public function registerFilesystem()
    {
        $this->app->bindIf('files', Filesystem::class, true);
        return $this;
    }

    /**
     * Register the events dispatcher
     */
    public function registerEvents()
    {
        $this->app->bindIf('events', Dispatcher::class, true);
        return $this;
    }

    /** @inheritdoc */
    public function registerEngineResolver()
    {
        parent::registerEngineResolver();
        return $this;
    }

    /** @inheritdoc */
    public function registerFactory()
    {
        parent::registerFactory();
        return $this;
    }

    /**
     * Register the view finder implementation.
     */
    public function registerViewFinder()
    {
        $this->app->bindIf('view.finder', function ($app) {
            $config = $this->app['config'];
            $paths = $config['view.paths'];
            $namespaces = $config['view.namespaces'];
            $finder = new FileViewFinder($app['files'], $paths);
            array_map([$finder, 'addNamespace'], array_keys($namespaces), $namespaces);
            return $finder;
        }, true);
        return $this;
    }
}
