<?php


if ( !class_exists( 'ShoestrapBranding' ) ) {

	/**
	* The Branding module
	*/
	class ShoestrapBranding {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 50 );
			add_action( 'wp_head', array( $this, 'icons' ) );
			add_filter( 'shoestrap_compiler', array( $this, 'variables_filter' ) );
		}

		/*
		 * The branding core options for the Shoestrap theme
		 */
		function options( $sections ) {
			$fields = array();
			// Branding Options
			$section = array(
				'title' => __( 'Branding', 'shoestrap' ),
				'icon' => 'el-icon-certificate icon-large'
			);

			$fields[] = array( 
				'title'       => __( 'Logo', 'shoestrap' ),
				'desc'        => __( 'Upload a logo image using the media uploader, or define the URL directly.', 'shoestrap' ),
				'id'          => 'logo',
				'default'     => '',
				'type'        => 'media',
				'customizer'  => array(),
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
				'customizer'  => array(),
				'compiler'    => true,
				'type'        => 'switch',
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Primary', 'shoestrap' ),
				'desc'        => __( 'Select your primary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'shoestrap' ),
				'id'          => 'color_brand_primary',
				'default'     => '#428bca',
				'compiler'    => true,
				'customizer'  => array(),
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Success', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for success messages etc. Default: #5cb85c.', 'shoestrap' ),
				'id'          => 'color_brand_success',
				'default'     => '#5cb85c',
				'compiler'    => true,
				'customizer'  => array(),
				'transparent' => false,    
				'type'        => 'color',
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Warning', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for warning messages etc. Default: #f0ad4e.', 'shoestrap' ),
				'id'          => 'color_brand_warning',
				'default'     => '#f0ad4e',
				'compiler'    => true,
				'customizer'  => array(),
				'type'        => 'color',
				'transparent' => false,    
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Danger', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for success messages etc. Default: #d9534f.', 'shoestrap' ),
				'id'          => 'color_brand_danger',
				'default'     => '#d9534f',
				'compiler'    => true,
				'customizer'  => array(),
				'type'        => 'color',
				'transparent' => false,    
			);

			$fields[] = array( 
				'title'       => __( 'Brand Colors: Info', 'shoestrap' ),
				'desc'        => __( 'Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \'info\' class. Default: #5bc0de.', 'shoestrap' ),
				'id'          => 'color_brand_info',
				'default'     => '#5bc0de',
				'compiler'    => true,
				'customizer'  => array(),
				'type'        => 'color',
				'transparent' => false,    
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_branding_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;
		}

		function icons() {
			$favicon_item    = shoestrap_getVariable( 'favicon' );
			$apple_icon_item = shoestrap_getVariable( 'apple_icon' );

			// Add the favicon
			if ( !empty( $favicon_item['url'] ) && $favicon_item['url'] != '' ) {
				$favicon = ShoestrapImage::_resize( $favicon_item['url'], 32, 32, true, false );

				echo '<link rel="shortcut icon" href="'.$favicon['url'].'" type="image/x-icon" />';
			}

			// Add the apple icons
			if ( !empty( $apple_icon_item['url'] ) ) {
				$iphone_icon        = ShoestrapImage::_resize( $apple_icon_item['url'], 57, 57, true, false );
				$iphone_icon_retina = ShoestrapImage::_resize( $apple_icon_item['url'], 57, 57, true, true );
				$ipad_icon          = ShoestrapImage::_resize( $apple_icon_item['url'], 72, 72, true, false );
				$ipad_icon_retina   = ShoestrapImage::_resize( $apple_icon_item['url'], 72, 72, true, true );
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
			$logo  = shoestrap_getVariable( 'logo' );

			if ( !empty( $logo['url'] ) )
				$branding = '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
			else
				$branding = '<span class="sitename">' . get_bloginfo( 'name' ) . '</span>';

			return $branding;
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		public static function variables() {
			$brand_primary = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'color_brand_primary', true ) ) );
			$brand_success = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'color_brand_success', true ) ) );
			$brand_warning = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'color_brand_warning', true ) ) );
			$brand_danger  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'color_brand_danger', true ) ) );
			$brand_info    = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'color_brand_info', true ) ) );

			$link_hover_color = ( ShoestrapColor::get_brightness( $brand_primary ) > 50 ) ? 'darken(@link-color, 15%)' : 'lighten(@link-color, 15%)';

			$brand_primary_brightness = ShoestrapColor::get_brightness( $brand_primary );
			$brand_success_brightness = ShoestrapColor::get_brightness( $brand_success );
			$brand_warning_brightness = ShoestrapColor::get_brightness( $brand_warning );
			$brand_danger_brightness  = ShoestrapColor::get_brightness( $brand_danger );
			$brand_info_brightness    = ShoestrapColor::get_brightness( $brand_info );

			// Button text colors
			$btn_primary_color  = $brand_primary_brightness < 195 ? '#fff' : '333';
			$btn_success_color  = $brand_success_brightness < 195 ? '#fff' : '333';
			$btn_warning_color  = $brand_warning_brightness < 195 ? '#fff' : '333';
			$btn_danger_color   = $brand_danger_brightness  < 195 ? '#fff' : '333';
			$btn_info_color     = $brand_info_brightness    < 195 ? '#fff' : '333';

			// Button borders
			$btn_primary_border = $brand_primary_brightness < 195 ? 'darken(@btn-primary-bg, 5%)' : 'lighten(@btn-primary-bg, 5%)';
			$btn_success_border = $brand_success_brightness < 195 ? 'darken(@btn-success-bg, 5%)' : 'lighten(@btn-success-bg, 5%)';
			$btn_warning_border = $brand_warning_brightness < 195 ? 'darken(@btn-warning-bg, 5%)' : 'lighten(@btn-warning-bg, 5%)';
			$btn_danger_border  = $brand_danger_brightness  < 195 ? 'darken(@btn-danger-bg, 5%)'  : 'lighten(@btn-danger-bg, 5%)';
			$btn_info_border    = $brand_info_brightness    < 195 ? 'darken(@btn-info-bg, 5%)'    : 'lighten(@btn-info-bg, 5%)';

			$input_border_focus = ( ShoestrapColor::get_brightness( $brand_primary ) < 195 ) ? 'lighten(@brand-primary, 10%);' : 'darken(@brand-primary, 10%);';
			$navbar_border      = ( ShoestrapColor::get_brightness( $brand_primary ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';


			$variables = '';

			// Branding colors
			$variables .= '@brand-primary: ' . $brand_primary . ';';
			$variables .= '@brand-success: ' . $brand_success . ';';
			$variables .= '@brand-info:    ' . $brand_info . ';';
			$variables .= '@brand-warning: ' . $brand_warning . ';';
			$variables .= '@brand-danger:  ' . $brand_danger . ';';

			// Link-hover
			$variables .= '@link-hover-color: ' . $link_hover_color . ';';

			$variables .= '@btn-default-color:  @gray-dark;';
			$variables .= '@btn-primary-color:  ' . $btn_primary_color . ';';
			$variables .= '@btn-primary-border: ' . $btn_primary_border . ';';
			$variables .= '@btn-success-color:  ' . $btn_success_color . ';';
			$variables .= '@btn-success-border: ' . $btn_success_border . ';';
			$variables .= '@btn-info-color:     ' . $btn_info_color . ';';
			$variables .= '@btn-info-border:    ' . $btn_info_border . ';';
			$variables .= '@btn-warning-color:  ' . $btn_warning_color . ';';
			$variables .= '@btn-warning-border: ' . $btn_warning_border . ';';
			$variables .= '@btn-danger-color:   ' . $btn_danger_color . ';';
			$variables .= '@btn-danger-border:  ' . $btn_danger_border . ';';

			$variables .= '@input-border-focus: ' . $input_border_focus . ';';

			$variables .= '@state-success-text: mix(@gray-darker, @brand-success, 20%);';
			$variables .= '@state-success-bg:   mix(@body-bg, @brand-success, 50%);';

			$variables .= '@state-info-text:    mix(@gray-darker, @brand-info, 20%);';
			$variables .= '@state-info-bg:      mix(@body-bg, @brand-info, 50%);';

			$variables .= '@state-warning-text: mix(@gray-darker, @brand-warning, 20%);';
			$variables .= '@state-warning-bg:   mix(@body-bg, @brand-warning, 50%);';

			$variables .= '@state-danger-text:  mix(@gray-darker, @brand-danger, 20%);';
			$variables .= '@state-danger-bg:    mix(@body-bg, @brand-danger, 50%);';

			return $variables;
		}

		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}
	}
}

$branding = new ShoestrapBranding();