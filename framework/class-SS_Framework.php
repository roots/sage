<?php

if ( ! class_exists( 'SS_Framework' ) ) {

	/**
	* The "Advanced" module
	*/
	class SS_Framework {

		/**
		 * Class constructor
		 */
		function __construct() {
			global $ss_settings;

			require_once dirname( __FILE__ ) . '/core/class-SS_Framework_Core.php';

			if ( ! defined( 'SS_FRAMEWORK' ) ) {
				$active_framework = $ss_settings['framework'];
			} else {
				if ( ! isset( $ss_settings['framework'] ) || SS_FRAMEWORK != $ss_settings['framework'] ) {
					$ss_settings['framework'] = SS_FRAMEWORK;
					update_option( SHOESTRAP_OPT_NAME, $ss_settings );
				}
				$active_framework = SS_FRAMEWORK;
			}

			// Include all frameworks
			$modules_path = new RecursiveDirectoryIterator( dirname( __FILE__ ) );
			$recIterator  = new RecursiveIteratorIterator( $modules_path );
			$regex        = new RegexIterator( $recIterator, '/\/framework.php$/i' );

			foreach( $regex as $item ) {
				require_once $item->getPathname();
			}

			$frameworks = $this->frameworks_list();

			// On Windows servers and XAMPP there seems to be an issue with this
			// and it returns empty. In that case, specify a default array for the
			// Bootstrap Framework.
			if ( empty( $frameworks ) ) {
				$frameworks = array(
					array(
						'shortname' => 'bootstrap',
						'name'      => 'Bootstrap',
						'classname' => 'SS_Framework_Bootstrap',
						'compiler'  => 'less_php'
					),
				);
			}

			$compiler = false;
			// Return the classname of the active framework.
			foreach ( $frameworks as $framework ) {
				if ( $active_framework == $framework['shortname'] ) {
					$active   = $framework['classname'];

					if ( isset( $framework['compiler'] ) ) {
						$compiler = $framework['compiler'];
					}
				}
			}

			// Get the compiler that will be used and initialize it.
			if ( $compiler ) {
				if ( $compiler == 'less_php' ) {
					require_once 'compilers/less-php/class-Shoestrap_Less_php.php';
					$compiler_init = new Shoestrap_Less_PHP();
				} elseif ( $compiler == 'sass_php' ) {
					require_once 'compilers/sass-php/class-Shoestrap_Sass_php.php';
					$compiler_init = new Shoestrap_Sass_PHP();
				}
			}

		}

		/**
		 * Get a list of all the available frameworks.
		 */
		function frameworks_list() {
			$frameworks = apply_filters( 'shoestrap_frameworks_array', array() );

			return $frameworks;
		}

	}
}

$framework = new SS_Framework();
