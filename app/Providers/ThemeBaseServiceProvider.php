<?php

namespace App\Providers;

use function Roots\asset;
use function Roots\config;

use Roots\Acorn\ServiceProvider;

class ThemeBaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // hook for consuming provider
        $this->beforeBoot();

        // main
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets'], 100);
        add_action('after_setup_theme', [$this, 'afterSetupTheme'], 20);
        add_action('widgets_init', [$this, 'widgetsInit']);

        // hook for consuming provider
        $this->afterBoot();
    }

    /**
     * Actions on `wp_enqueue_scripts`
     *
     * @return void
     */
    public function enqueueAssets()
    {
        // Enqueue scripts
        collect(config('theme.scripts'))->each(function ($script) {
            wp_enqueue_script(
                $script['handle'],
                asset($script['src'])->uri(),
                $script['dependencies'],
                $script['version'],
                $script['in_footer']
            );
        });

        // Inline vendor scripts
        collect(config('theme.inline_scripts'))->each(function ($script) {
            wp_add_inline_script(
                $script['handle'],
                asset($script['data'])->contents(),
                $script['position']
            );
        });

        // Add comment-reply.js as necessary
        if (config('theme.comment_reply_enabled')==true) {
            if (is_single() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }
        }

        // Enqueue styles
        collect(config('theme.styles'))->each(function ($style) {
            wp_enqueue_style(
                $style['handle'],
                asset($style['src'])->uri(),
                $style['dependencies'],
                $style['version'],
                $style['media'],
            );
        });
    }

    /**
     * Actions on `after_setup_theme`
     *
     * @return void
     */
    public function afterSetupTheme()
    {
        // Add soil features
        collect(config('theme.add_soil_support'))->each(function ($soil_feature) {
            add_theme_support($soil_feature);
        });

        // Add theme supports
        collect(config('theme.add_theme_support'))->each(function ($options, $feature) {
            $options
                ? add_theme_support($feature, $options)
                : add_theme_support($feature);
        });
    }

    /**
     * Actions on `widgets_init`
     *
     * @return void
     */
    public function widgetsInit()
    {
        // register_sidebar calls
        collect(config('theme.widget_areas'))->each(function ($area) {
            register_sidebar([
                'name' => $area['name'],
                'id'   => $area['id'],
            ] + config('theme.widget_config'));
        });
    }

    /**
     * Provides the consuming service provider a way
     * to inject actions to be run before the class'
     * primary methods.
     *
     * @return void
     */
    public function beforeBoot()
    {
        //
    }

    /**
     * Provides the consuming service provider a way
     * to inject actions to be run after the class'
     * primary methods.
     *
     * @return void
     */
    public function afterBoot()
    {
        //
    }
}
