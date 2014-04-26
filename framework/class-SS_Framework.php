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
			global $ss_settings, $ss_active_framework;

			require_once dirname( __FILE__ ) . '/core/class-SS_Framework_Core.php';

			do_action( 'shoestrap_include_frameworks' );

			if ( ! defined( 'SS_FRAMEWORK' ) ) {
				$active_framework = 'bootstrap';
			}

			// If the active framework is Bootstrap, include it.
			if ( ( defined( 'SS_FRAMEWORK' ) && 'bootstrap' == SS_FRAMEWORK ) || ! defined( 'SS_FRAMEWORK' ) ) {
				require_once 'bootstrap/framework.php';
			}


			// On Windows servers and XAMPP there seems to be an issue with this
			// and it returns empty. In that case, specify a default array for the
			// Bootstrap Framework.
			if ( ! is_array( $ss_active_framework ) ) {
				$ss_active_framework = array(
					'shortname' => 'bootstrap',
					'name'      => 'Bootstrap',
					'classname' => 'SS_Framework_Bootstrap',
					'compiler'  => 'less_php',
				);
			}

			$compiler = false;
			// Return the classname of the active framework.
			$active   = $ss_active_framework['classname'];

			$compiler = $ss_active_framework['compiler'];

			global $ss_framework;
			$ss_framework = new $active;

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
	}
}

$framework = new SS_Framework();
