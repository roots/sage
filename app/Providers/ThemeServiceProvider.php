<?php

namespace App\Providers;

class ThemeServiceProvider extends ThemeBaseServiceProvider
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
     * Called before this provider's boot operations which
     * set up the theme based on values in the theme configuration
     * file (config/theme.php).
     *
     * Utilizing `boot` in this class directly will prevent it from
     * loading assets, widget areas, and options.
     *
     * @return void
     */
    public function beforeBoot()
    {
        //
    }

    /**
     * Called after this provider's boot operations which
     * set up the theme based on values in the theme configuration
     * file (config/theme.php).
     *
     * @return void
     */
    public function afterBoot()
    {
        //
    }
}
