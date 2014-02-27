<?php


if ( !class_exists( 'SS_Foundation_Colors' ) ) {

	/**
	* The Branding module
	*/
	class SS_Foundation_Colors {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 50 );
			add_action( 'wp_head',            array( $this, 'icons'            ) );
		}

		/*
		 * The branding core options for the Shoestrap theme
		 */
		function options( $sections ) {
			$fields = array();
			// Branding Options
			$section = array(
				'title' => __( 'Branding', 'shoestrap' ),
				'icon' => 'el-icon-certificate'
			);

			$fields[] = array( 
				'title'       => 'Colors',
				'desc'        => '',
				'id'          => 'help6',
				'default'     => __( 'The primary color you select will also affect other elements on your site,
													such as table borders, widgets colors, input elements, dropdowns etc.
													The branding colors you select will be used throughout the site in various elements.
													One of the most important settings in your branding is your primary color,
													since this will be used more often.', 'shoestrap' ),
				'type'        => 'info'
			);

			$fields[] = array(
				'title'       => __( 'Enable Gradients', 'shoestrap' ),
				'desc'        => __( 'Enable gradients for buttons and the navbar. Default: Off.', 'shoestrap' ),
				'id'          => 'gradients_toggle',
				'default'     => 0,
				'compiler'    => true,
				'type'        => 'switch',
			);

			$fields[] = array( 
				'title'       => __( 'Primary Color', 'shoestrap' ),
				'desc'        => __( 'Select your primary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'shoestrap' ),
				'id'          => 'primary-color',
				'default'     => '#008CBA',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Secondary Color', 'shoestrap' ),
				'desc'        => __( 'Select your primary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'shoestrap' ),
				'id'          => 'secondary-color',
				'default'     => '#e7e7e7',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Alert Color', 'shoestrap' ),
				'desc'        => '',
				'id'          => 'alert-color',
				'default'     => '#f04124',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Success Color', 'shoestrap' ),
				'desc'        => '',
				'id'          => 'success-color',
				'default'     => '#43AC6A',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Warning Color', 'shoestrap' ),
				'desc'        => '',
				'id'          => 'warning-color',
				'default'     => '#f08a24',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Info Color', 'shoestrap' ),
				'desc'        => '',
				'id'          => 'info-color',
				'default'     => '#a0d3e8',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_branding_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;
		}

		function icons() {
			global $ss_settings;

			$favicon_item    = $ss_settings['favicon'];
			$apple_icon_item = $ss_settings['apple_icon'];

			// Add the favicon
			if ( !empty( $favicon_item['url'] ) && $favicon_item['url'] != '' ) {
				$favicon = Shoestrap_Image::_resize( $favicon_item['url'], 32, 32, true, false );

				echo '<link rel="shortcut icon" href="'.$favicon['url'].'" type="image/x-icon" />';
			}

			// Add the apple icons
			if ( !empty( $apple_icon_item['url'] ) ) {
				$iphone_icon        = Shoestrap_Image::_resize( $apple_icon_item['url'], 57, 57, true, false );
				$iphone_icon_retina = Shoestrap_Image::_resize( $apple_icon_item['url'], 57, 57, true, true );
				$ipad_icon          = Shoestrap_Image::_resize( $apple_icon_item['url'], 72, 72, true, false );
				$ipad_icon_retina   = Shoestrap_Image::_resize( $apple_icon_item['url'], 72, 72, true, true );
				?>

				<!-- For iPhone --><link rel="apple-touch-icon-precomposed" href="<?php echo $iphone_icon['url'] ?>">
				<!-- For iPhone 4 Retina display --><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $iphone_icon_retina['url'] ?>">
				<!-- For iPad --><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $ipad_icon['url'] ?>">
				<!-- For iPad Retina display --><link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $ipad_icon_retina['url'] ?>">
				<?php
			}
		}

		/*
		 * The site logo.
		 * If no custom logo is uploaded, use the sitename
		 */
		public static function logo() {
			global $ss_settings;
			$logo  = $ss_settings['logo'];

			if ( !empty( $logo['url'] ) )
				$branding = '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
			else
				$branding = '<span class="sitename">' . get_bloginfo( 'name' ) . '</span>';

			return $branding;
		}
	}
}

$branding = new SS_Foundation_Colors();