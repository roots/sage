<?php


if ( !class_exists( 'Shoestrap_Branding' ) ) {

	/**
	* The Branding module
	*/
	class Shoestrap_Branding {
		
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
				'title'       => __( 'Brand Colors: Primary', 'shoestrap' ),
				'desc'        => __( 'Select your primary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'shoestrap' ),
				'id'          => 'color_brand_primary',
				'default'     => '#428bca',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Success', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for success messages etc. Default: #5cb85c.', 'shoestrap' ),
				'id'          => 'color_brand_success',
				'default'     => '#5cb85c',
				'compiler'    => true,
				'transparent' => false,    
				'type'        => 'color',
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Warning', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for warning messages etc. Default: #f0ad4e.', 'shoestrap' ),
				'id'          => 'color_brand_warning',
				'default'     => '#f0ad4e',
				'compiler'    => true,
				'type'        => 'color',
				'transparent' => false,    
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Danger', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for success messages etc. Default: #d9534f.', 'shoestrap' ),
				'id'          => 'color_brand_danger',
				'default'     => '#d9534f',
				'compiler'    => true,
				'type'        => 'color',
				'transparent' => false,    
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Info', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \'info\' class. Default: #5bc0de.', 'shoestrap' ),
				'id'          => 'color_brand_info',
				'default'     => '#5bc0de',
				'compiler'    => true,
				'type'        => 'color',
				'transparent' => false,    
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

$branding = new Shoestrap_Branding();