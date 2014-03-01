<?php

if ( !class_exists( 'SS_Framework' ) ) {

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

			// Add the frameworks select to redux.
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 1 );

			// Include all frameworks
			$modules_path = new RecursiveDirectoryIterator( dirname( __FILE__ ) );
			$recIterator  = new RecursiveIteratorIterator( $modules_path );
			$regex        = new RegexIterator( $recIterator, '/\/framework.php$/i' );

			foreach( $regex as $item ) {
				require_once $item->getPathname();
			}

			$frameworks = $this->frameworks_list();

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

		/*
		 * Create the framework selector
		 */
		function options( $sections ) {
			global $redux;
			$settings = get_option( SHOESTRAP_OPT_NAME );

			$frameworks = $this->frameworks_list();

			$frameworks_select    = array();
			$frameworks_shortlist = array();

			foreach ( $frameworks as $framework ) {
				$frameworks_select[$framework['shortname']] = $framework['name'];
				$frameworks_shortlist[] = $framework['shortname'];
			}

			$frameworks_shortlist = implode( ', ', $frameworks_shortlist );

			// Blog Options
			$section = array(
				'title' => __( 'General', 'shoestrap' ),
				'icon'  => 'el-icon-website',
			);

			if ( ! defined( 'SS_FRAMEWORK' ) ) {

				$fields[] = array(
					'title'     => __( 'Framework Locking', 'shoestrap' ),
					'desc'      => __( 'You can select a framework here. Keep in mind that if you reset your options, this option will also be reset and you will lose all your settings. When changing frameworks, your settings are also reset.
						<br>If you want to lock your site to a specific framework, then please define it in your wp-config.php file like this:', 'shoestrap' ) . ' <code>define( "SS_Framework", "foundation" );</code><br>' . __( 'Accepted values: ', 'shoestrap' ) . $frameworks_shortlist . '</p>',
					'id'        => 'framework_lock_help',
					'type'      => 'info',
					'options'   => $frameworks_select,
					'compiler'  => false,
				);


				$fields[] = array(
					'title'     => __( 'Framework Select', 'shoestrap' ),
					'desc'      => __( 'Select a framework.', 'shoestrap' ),
					'id'        => 'framework',
					'default'   => 'bootstrap',
					'type'      => 'select',
					'options'   => $frameworks_select,
					'compiler'  => false,
				);
			}

			$fields[] = array( 
				'title'       => __( 'Logo', 'shoestrap' ),
				'desc'        => __( 'Upload a logo image using the media uploader, or define the URL directly.', 'shoestrap' ),
				'id'          => 'logo',
				'default'     => '',
				'type'        => 'media',
			);

			$fields[] = array( 
				'title'       => __( 'Custom Favicon', 'shoestrap' ),
				'desc'        => __( 'Upload a favicon image using the media uploader, or define the URL directly.', 'shoestrap' ),
				'id'          => 'favicon',
				'default'     => '',
				'type'        => 'media',
			);

			$fields[] = array( 
				'title'       => __( 'Apple Icon', 'shoestrap' ),
				'desc'        => __( 'This will create icons for Apple iPhone ( 57px x 57px ), Apple iPhone Retina Version ( 114px x 114px ), Apple iPad ( 72px x 72px ) and Apple iPad Retina ( 144px x 144px ). Please note that for better results the image you upload should be at least 144px x 144px.', 'shoestrap' ),
				'id'          => 'apple_icon',
				'default'     => '',
				'type'        => 'media',
			);

			$section['fields'] = $fields;

			do_action( 'shoestrap_module_layout_options_modifier' );
			
			$sections[] = $section;
			return $sections;
		}

		/*
		 * The site logo.
		 * If no custom logo is uploaded, use the sitename
		 */
		public static function logo() {
			$logo  = shoestrap_getVariable( 'logo' );

			if ( !empty( $logo['url'] ) ) {
				$branding = '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
			} else {
				$branding = '<span class="sitename">' . get_bloginfo( 'name' ) . '</span>';
			}

			return $branding;
		}
	}
}

$framework = new SS_Framework();