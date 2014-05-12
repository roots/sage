<?php


if ( ! class_exists( 'Shoestrap_Options' ) ) {

	class Shoestrap_Options {

		public $args     = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {

			if ( ! class_exists( 'ReduxFramework' ) ) {
				return;
			}

			// This is needed. Bah WordPress bugs.  ;)
			if (  true == Redux_Helpers::isTheme( __FILE__ ) ) {
				$this->initSettings();
			} else {
				add_action( 'plugins_loaded', array( $this, 'initSettings' ) );
			}
		}

		public function initSettings() {

			// Set the default arguments
			$this->setArguments();

			// Create the sections and fields
			$this->setSections();

			if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}

			// If Redux is running as a plugin, this will remove the demo notice and links
			add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}

		public function setSections() {

			global $redux;

			$settings = get_option( SHOESTRAP_OPT_NAME );

			// General Settings
			$this->sections[] = array(
				'title' => __( 'General', 'shoestrap' ),
				'icon'  => 'el-icon-website',
				'fields'  => apply_filters( 'shoestrap_module_general_options_modifier', array(
					array(
						'title'     => __( 'Setup Mode', 'shoestrap' ),
						'desc'      => __( 'Select Easy or Advanced setup. Easy mode hides most options and allows for quick customization.', 'shoestrap' ),
						'id'        => 'options_mode',
						'type'      => 'button_set',
						'options'   => array(
							'easy'     => __( 'Easy', 'shoestrap' ),
							'advanced' => __( 'Advanced', 'shoestrap' )
						),
						'default' => 'easy'
					),
					array(
						'title'       => __( 'Logo', 'shoestrap' ),
						'desc'        => __( 'Upload a logo image using the media uploader, or define the URL directly.', 'shoestrap' ),
						'id'          => 'logo',
						'default'     => '',
						'type'        => 'media',
					),
					array(
						'title'       => __( 'Custom Favicon', 'shoestrap' ),
						'desc'        => __( 'Upload a favicon image using the media uploader, or define the URL directly.', 'shoestrap' ),
						'id'          => 'favicon',
						'default'     => '',
						'type'        => 'media',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Apple Icon', 'shoestrap' ),
						'desc'        => __( 'This will create icons for Apple iPhone ( 57px x 57px ), Apple iPhone Retina Version ( 114px x 114px ), Apple iPad ( 72px x 72px ) and Apple iPad Retina ( 144px x 144px ). Please note that for better results the image you upload should be at least 144px x 144px.', 'shoestrap' ),
						'id'          => 'apple_icon',
						'default'     => '',
						'type'        => 'media',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);


			// Colors Settings
			$this->sections[] = array(
				'title'   => __( 'Colors', 'shoestrap' ),
				'icon'    => 'el-icon-certificate',
				'fields'  => apply_filters( 'shoestrap_module_branding_options_modifier', array(
					array(
						'title'       => 'Colors',
						'desc'        => '',
						'id'          => 'help6',
						'default'     => __( 'The primary color you select will also affect other elements on your site, such as table borders, widgets colors, input elements, dropdowns etc. The branding colors you select will be used throughout the site in various elements. One of the most important settings in your branding is your primary color, since this will be used more often.', 'shoestrap' ),
						'type'        => 'info'
					),
					array(
						'title'       => __( 'Enable Gradients', 'shoestrap' ),
						'desc'        => __( 'Enable gradients for buttons and the navbar. Default: Off.', 'shoestrap' ),
						'id'          => 'gradients_toggle',
						'default'     => 0,
						'compiler'    => true,
						'type'        => 'switch',
					),
					array(
						'title'       => __( 'Brand Colors: Primary', 'shoestrap' ),
						'desc'        => __( 'Select your primary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'shoestrap' ),
						'id'          => 'color_brand_primary',
						'default'     => '#428bca',
						'compiler'    => true,
						'transparent' => false,
						'type'        => 'color'
					),
					array(
						'title'       => __( 'Brand Colors: Success', 'shoestrap' ),
						'desc'        => __( 'Select your branding color for success messages etc. Default: #5cb85c.', 'shoestrap' ),
						'id'          => 'color_brand_success',
						'default'     => '#5cb85c',
						'compiler'    => true,
						'transparent' => false,
						'type'        => 'color',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Brand Colors: Warning', 'shoestrap' ),
						'desc'        => __( 'Select your branding color for warning messages etc. Default: #f0ad4e.', 'shoestrap' ),
						'id'          => 'color_brand_warning',
						'default'     => '#f0ad4e',
						'compiler'    => true,
						'type'        => 'color',
						'transparent' => false,
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Brand Colors: Danger', 'shoestrap' ),
						'desc'        => __( 'Select your branding color for success messages etc. Default: #d9534f.', 'shoestrap' ),
						'id'          => 'color_brand_danger',
						'default'     => '#d9534f',
						'compiler'    => true,
						'type'        => 'color',
						'transparent' => false,
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Brand Colors: Info', 'shoestrap' ),
						'desc'        => __( 'Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \'info\' class. Default: #5bc0de.', 'shoestrap' ),
						'id'          => 'color_brand_info',
						'default'     => '#5bc0de',
						'compiler'    => true,
						'type'        => 'color',
						'transparent' => false,
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);


			// Background Settings
			$this->sections[] = array(
				'title'   => __( 'Background', 'shoestrap' ),
				'icon'    => 'el-icon-photo',
				'fields'  => apply_filters( 'shoestrap_module_background_options_modifier', array(
					array(
						'title'       => __( 'General Background Color', 'shoestrap' ),
						'desc'        => __( 'Select a background color for your site. Default: #ffffff.', 'shoestrap' ),
						'id'          => 'html_bg',
						'default'     => array(
							'background-color' => isset( $settings['html_color_bg'] ) ? $settings['html_color_bg'] : '#ffffff',
						),
						'transparent' => false,
						'type'        => 'background',
						'output'      => 'body'
					),
					array(
						'title'       => __( 'Content Background', 'shoestrap' ),
						'desc'        => __( 'Background for the content area. Colors also affect input areas and other colors.', 'shoestrap' ),
						'id'          => 'body_bg',
						'default'     => array(
							'background-color'    => isset( $settings['color_body_bg'] ) ? $settings['color_body_bg'] : '#ffffff',
							'background-repeat'   => isset( $settings['background_repeat'] ) ? $settings['background_repeat'] : NULL,
							'background-position' => isset( $settings['background_position_x'] ) ? $settings['background_position_x'] . ' center' : NULL,
							'background-image'    => isset( $settings['background_image']['url'] ) ? $settings['background_image']['url'] : NULL,
						),
						'compiler'    => true,
						'transparent' => false,
						'type'        => 'background',
						'output'      => '.wrap.main-section .content .bg'
					),
					array(
						'title'   => __( 'Content Background Color Opacity', 'shoestrap' ),
						'desc'    => __( 'Select the opacity of your background color for the main content area so that background images will show through. Please note that if you have added an image for your content background, changing the opacity to something other than 100 will result in your background image not being shown. If you need to add opacity to your content background image, you will need to do it by adding transparency to the PNG background image itself.', 'shoestrap' ),
						'id'      => 'body_bg_opacity',
						'default' => 100,
						'min'     => 0,
						'step'    => 1,
						'max'     => 100,
						'type'    => 'slider',
					),
				) ),
			);


			$this->sections[] = array(
				'title'       => __( 'Layout', 'shoestrap' ),
				'icon'        => 'el-icon-screen',
				'description' => '<p>In this area you can select your site\'s layout, the width of your sidebars, as well as other, more advanced options.</p>',
				'fields'  => apply_filters( 'shoestrap_module_layout_options_modifier', array(
					array(
						'title'     => __( 'Site Style', 'shoestrap' ),
						'desc'      => __( 'Select the default site layout. Defaults to "Wide". Please note that if you select a non-responsive layout, you will have to trigger the compiler so that your changes take effect.', 'shoestrap' ),
						'id'        => 'site_style',
						'default'   => 'wide',
						'type'      => 'select',
						'options'   => array(
							'static'  => __( 'Static (Non-Responsive)', 'shoestrap' ),
							'wide'    => __( 'Wide', 'shoestrap' ),
							'boxed'   => __( 'Boxed', 'shoestrap' ),
							'fluid'   => __( 'Fluid', 'shoestrap' ),
						),
						'compiler'  => true,
					),
					array(
						'title'     => __( 'Layout', 'shoestrap' ),
						'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
						'id'        => 'layout',
						'default'   => 1,
						'type'      => 'image_select',
						'options'   => array(
							0 => ReduxFramework::$_url . '/assets/img/1c.png',
							1 => ReduxFramework::$_url . '/assets/img/2cr.png',
							2 => ReduxFramework::$_url . '/assets/img/2cl.png',
							3 => ReduxFramework::$_url . '/assets/img/3cl.png',
							4 => ReduxFramework::$_url . '/assets/img/3cr.png',
							5 => ReduxFramework::$_url . '/assets/img/3cm.png',
						)
					),
					array(
						'title'     => __( 'Primary Sidebar Width', 'shoestrap' ),
						'desc'      => __( 'Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
						'id'        => 'layout_primary_width',
						'type'      => 'button_set',
						'options'   => array(
							'1' => '1 Column',
							'2' => '2 Columns',
							'3' => '3 Columns',
							'4' => '4 Columns',
							'5' => '5 Columns'
						),
						'default' => '4'
					),
					array(
						'title'     => __( 'Secondary Sidebar Width', 'shoestrap' ),
						'desc'      => __( 'Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
						'id'        => 'layout_secondary_width',
						'type'      => 'button_set',
						'options'   => array(
							'1' => '1 Column',
							'2' => '2 Columns',
							'3' => '3 Columns',
							'4' => '4 Columns',
							'5' => '5 Columns'
						),
						'default' => '3'
					),
					array(
						'title'     => __( 'Show sidebars on the frontpage', 'shoestrap' ),
						'desc'      => __( 'OFF by default. If you want to display the sidebars in your frontpage, turn this ON.', 'shoestrap' ),
						'id'        => 'layout_sidebar_on_front',
						'default'   => 0,
						'type'      => 'switch'
					),
				) ),
			);

			$std = array(
				array(
						'title'     => 'Looking for Advanced Layout Options?',
						'desc'      => '',
						'id'        => 'help632165',
						'default'   => __( 'Advanced layout options are not available when using the Easy setup mode.
							Please switch to Anvanced Setup Mode from the "General" section.', 'shoestrap' ),
						'type'      => 'info',
						'style'     => 'warning',
						'required'  => array( 'options_mode', '=', array( 'easy' ) ),
					),
					array(
						'title'     => __( 'Margin from top ( Works only in \'Boxed\' mode )', 'shoestrap' ),
						'desc'      => __( 'This will add a margin above the navbar. Useful if you\'ve enabled the \'Boxed\' mode above. Default: 0px', 'shoestrap' ),
						'id'        => 'navbar_margin_top',
						'required'  => array('navbar_boxed','=',array('1')),
						'default'   => 0,
						'min'       => 0,
						'step'      => 1,
						'max'       => 120,
						'compiler'  => true,
						'type'      => 'slider',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Widgets mode', 'shoestrap' ),
						'desc'      => __( 'How do you want your widgets to be displayed?', 'shoestrap' ),
						'id'        => 'widgets_mode',
						'default'   => 1,
						'options'   => array(
							0           => __( 'Panel', 'shoestrap' ),
							1           => __( 'Well', 'shoestrap' ),
							2           => __( 'None', 'shoestrap' ),
						),
						'type'      => 'button_set',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Body Top Margin', 'shoestrap' ),
						'desc'      => __( 'Select the top margin of body element in pixels. Default: 0px.', 'shoestrap' ),
						'id'        => 'body_margin_top',
						'default'   => 0,
						'min'       => 0,
						'step'      => 1,
						'max'       => 200,
						'edit'      => 1,
						'type'      => 'slider',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Body Bottom Margin', 'shoestrap' ),
						'desc'      => __( 'Select the bottom margin of body element in pixels. Default: 0px.', 'shoestrap' ),
						'id'        => 'body_margin_bottom',
						'default'   => 0,
						'min'       => 0,
						'step'      => 1,
						'max'       => 200,
						'edit'      => 1,
						'type'      => 'slider',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Custom Grid', 'shoestrap' ),
						'desc'      => '<strong>' . __( 'CAUTION:', 'shoestrap' ) . '</strong> ' . __( 'Only use this if you know what you are doing, as changing these values might break the way your site looks on some devices. The default settings should be fine for the vast majority of sites.', 'shoestrap' ),
						'id'        => 'custom_grid',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Small Screen / Tablet view', 'shoestrap' ),
						'desc'      => __( 'The width of Tablet screens. Default: 768px', 'shoestrap' ),
						'id'        => 'screen_tablet',
						'default'   => 768,
						'min'       => 620,
						'step'      => 2,
						'max'       => 2100,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
						'required'  => array( 
							array ( 'options_mode', '=', array( 'advanced' ) ),
							array ( 'custom_grid',  '=', array( '1' ) ) 
						),
					),
					array(
						'title'     => __( 'Desktop Container Width', 'shoestrap' ),
						'desc'      => __( 'The width of normal screens. Default: 992px', 'shoestrap' ),
						'id'        => 'screen_desktop',
						'default'   => 992,
						'min'       => 620,
						'step'      => 2,
						'max'       => 2100,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
						'required'  => array( 
							array ( 'options_mode', '=', array( 'advanced' ) ),
							array ( 'custom_grid',  '=', array( '1' ) ) 
						),
					),
					array(
						'title'     => __( 'Large Desktop Container Width', 'shoestrap' ),
						'desc'      => __( 'The width of Large Desktop screens. Default: 1200px', 'shoestrap' ),
						'id'        => 'screen_large_desktop',
						'default'   => 1200,
						'min'       => 620,
						'step'      => 2,
						'max'       => 2100,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
						'required'  => array( 
							array ( 'options_mode', '=', array( 'advanced' ) ),
							array ( 'custom_grid',  '=', array( '1' ) ) 
						),
					),
					array(
						'title'     => __( 'Columns Gutter', 'shoestrap' ),
						'desc'      => __( 'The space between the columns in your grid. Default: 30px', 'shoestrap' ),
						'id'        => 'layout_gutter',
						'default'   => 30,
						'min'       => 2,
						'step'      => 2,
						'max'       => 100,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
						'required'  => array( 
							array ( 'options_mode', '=', array( 'advanced' ) ),
							array ( 'custom_grid',  '=', array( '1' ) ) 
						),
					),
					array(
						'title'     => __( 'Custom Layouts per Post Type', 'shoestrap' ),
						'desc'      => __( 'Set a default layout for each post type on your site.', 'shoestrap' ),
						'id'        => 'cpt_layout_toggle',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					)
			);

			// Layout Settings
			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$layout = isset( $ss_settings['layout'] ) ? $ss_settings['layout'] : 1;
			$layout_ppt_fields = array();
			foreach ( $post_types as $post_type ) {
				$layout_ppt_fields[] = array(
					'title'     => __( $post_type . ' Layout', 'shoestrap' ),
					'desc'      => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
					'id'        => $post_type . '_layout',
					'default'   => $layout,
					'type'      => 'image_select',
					'required'  => array( 'cpt_layout_toggle','=',array( '1' ) ),
					'options'   => array(
						0         => ReduxFramework::$_url . '/assets/img/1c.png',
						1         => ReduxFramework::$_url . '/assets/img/2cr.png',
						2         => ReduxFramework::$_url . '/assets/img/2cl.png',
						3         => ReduxFramework::$_url . '/assets/img/3cl.png',
						4         => ReduxFramework::$_url . '/assets/img/3cr.png',
						5         => ReduxFramework::$_url . '/assets/img/3cm.png',
					)
				);
			}

			$this->sections[] = array(
				'title'       => __( 'Advanced Layout', 'shoestrap' ),
				'icon'        => 'el-icon-chevron-right',
				'subsection'  => true,
				'fields'  => apply_filters( 'shoestrap_module_layout_advanced_options_modifier', array_merge($std, $layout_ppt_fields) )
			);

			// Blog Settings

			$screen_large_desktop = isset( $ss_settings['screen_large_desktop'] ) ? filter_var( $ss_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT ) : 1200;

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$post_type_options  = array();
			$post_type_defaults = array();

			foreach ( $post_types as $post_type ) {
				$post_type_options[$post_type]  = $post_type;
				$post_type_defaults[$post_type] = 0;
			}

			$this->sections[] = array(
				'title'   => __( 'Blog', 'shoestrap' ),
				'icon'    => 'el-icon-wordpress',
				'fields'  => apply_filters( 'shoestrap_module_blog_modifier', array(
					array(
						'title'     => __( 'Archives Display Mode', 'shoestrap' ),
						'desc'      => __( 'Display the excerpt or the full post on post archives.', 'shoestrap' ),
						'id'        => 'blog_post_mode',
						'default'   => 'excerpt',
						'type'      => 'button_set',
						'options'   => array(
							'excerpt' => __( 'Excerpt', 'shoestrap' ),
							'full'    => __( 'Full Post', 'shoestrap' ),
						),
					),
					array(
						'id'          => 'shoestrap_entry_meta_config',
						'title'       => __( 'Activate and order Post Meta elements', 'shoestrap' ),
						'options'     => array(
							'post-format'		=> 'Post Format',
							'tags'    			=> 'Tags',
							'date'    			=> 'Date',
							'category'			=> 'Category',
							'author'  			=> 'Author',
							'comment-count'	=> 'Comments',
							'sticky'  			=> 'Sticky'
						),
						'type'        => 'sortable',
						'mode'        => 'checkbox'
					),
					array(
						'title'     => __( 'Switch Date Meta in time_diff mode', 'shoestrap' ),
						'desc'      => __( 'Replace Date Meta element by displaying the difference between post creation timestamp and current timestamp. Default: OFF.', 'shoestrap' ),
						'id'        => 'date_meta_format',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Post excerpt length', 'shoestrap' ),
						'desc'      => __( 'Choose how many words should be used for post excerpt. Default: 40', 'shoestrap' ),
						'id'        => 'post_excerpt_length',
						'default'   => 40,
						'min'       => 10,
						'step'      => 1,
						'max'       => 1000,
						'edit'      => 1,
						'type'      => 'slider',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( '"more" text', 'shoestrap' ),
						'desc'      => __( 'Text to display in case of excerpt too long. Default: Continued', 'shoestrap' ),
						'id'        => 'post_excerpt_link_text',
						'default'   => __( 'Continued', 'shoestrap' ),
						'type'      => 'text',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Show Breadcrumbs', 'shoestrap' ),
						'desc'      => __( 'Display Breadcrumbs. Default: OFF.', 'shoestrap' ),
						'id'        => 'breadcrumbs',
						'default'   => 0,
						'type'      => 'switch',
					),
					array(
						'title'     => __( 'Show Post Meta in single posts', 'shoestrap' ),
						'desc'      => __( 'Toggle Post Meta showing in the footer of single posts. Default: ON.', 'shoestrap' ),
						'id'        => 'single_meta',
						'default'   => 1,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Blog Settings

			$screen_large_desktop = isset( $ss_settings['screen_large_desktop'] ) ? filter_var( $ss_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT ) : 1200;

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$post_type_options  = array();
			$post_type_defaults = array();

			foreach ( $post_types as $post_type ) {
				$post_type_options[$post_type]  = $post_type;
				$post_type_defaults[$post_type] = 0;
			}

			$this->sections[] = array(
				'title'   => __( 'Featured Images', 'shoestrap' ),
				'icon'    => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_featured_images_modifier', array(
					array(
						'id'        => 'help3',
						'title'     => __( 'Featured Images', 'shoestrap' ),
						'desc'      => __( 'Here you can select if you want to display the featured images in post archives and individual posts.
														Please note that these apply to posts, pages, as well as custom post types.
														You can select image sizes independently for archives and individual posts view.', 'shoestrap' ),
						'type'      => 'info',
					),
					array(
						'title'     => __( 'Featured Images on Archives', 'shoestrap' ),
						'desc'      => __( 'Display featured Images on post archives ( such as categories, tags, month view etc ). Default: OFF.', 'shoestrap' ),
						'id'        => 'feat_img_archive',
						'default'   => 0,
						'type'      => 'switch',
					),
					array(
						'title'     => __( 'Width of Featured Images on Archives', 'shoestrap' ),
						'desc'      => __( 'Set dimensions of featured Images on Archives. Default: Full Width', 'shoestrap' ),
						'id'        => 'feat_img_archive_custom_toggle',
						'default'   => 0,
						'required'  => array( 'feat_img_archive','=',array( '1' ) ),
						'off'       => __( 'Full Width', 'shoestrap' ),
						'on'        => __( 'Custom Dimensions', 'shoestrap' ),
						'type'      => 'switch',
					),
					array(
						'title'     => __( 'Archives Featured Image Custom Width', 'shoestrap' ),
						'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
						'id'        => 'feat_img_archive_width',
						'default'   => 550,
						'min'       => 100,
						'step'      => 1,
						'max'       => $screen_large_desktop,
						'required'  => array(
							array( 'feat_img_archive', '=', 1 ),
							array( 'feat_img_archive_custom_toggle', '=', 1 ),
						),
						'edit'      => 1,
						'type'      => 'slider'
					),
					array(
						'title'     => __( 'Archives Featured Image Custom Height', 'shoestrap' ),
						'desc'      => __( 'Select the height of your featured images on post archives. Default: 300px', 'shoestrap' ),
						'id'        => 'feat_img_archive_height',
						'default'   => 300,
						'min'       => 50,
						'step'      => 1,
						'edit'      => 1,
						'max'       => $screen_large_desktop,
						'required'  => array( 'feat_img_archive', '=', 1 ),
						'type'      => 'slider'
					),
					array(
						'title'     => __( 'Featured Images on Posts', 'shoestrap' ),
						'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
						'id'        => 'feat_img_post',
						'default'   => 0,
						'type'      => 'switch',
					),
					array(
						'title'     => __( 'Width of Featured Images on Posts', 'shoestrap' ),
						'desc'      => __( 'Set dimensions of featured Images on Posts. Default: Full Width', 'shoestrap' ),
						'id'        => 'feat_img_post_custom_toggle',
						'default'   => 0,
						'off'       => __( 'Full Width', 'shoestrap' ),
						'on'        => __( 'Custom Dimensions', 'shoestrap' ),
						'type'      => 'switch',
						'required'  => array( 'feat_img_post', '=', 1 ),
					),
					array(
						'title'     => __( 'Posts Featured Image Custom Width', 'shoestrap' ),
						'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
						'id'        => 'feat_img_post_width',
						'default'   => 550,
						'min'       => 100,
						'step'      => 1,
						'max'       => $screen_large_desktop,
						'edit'      => 1,
						'required'  => array(
							array( 'feat_img_post', '=', 1 ),
							array( 'feat_img_post_custom_toggle', '=', 1 ),
						),
						'type'      => 'slider'
					),
					array(
						'title'     => __( 'Posts Featured Image Custom Height', 'shoestrap' ),
						'desc'      => __( 'Select the height of your featured images on single posts. Default: 330px', 'shoestrap' ),
						'id'        => 'feat_img_post_height',
						'default'   => 330,
						'min'       => 50,
						'step'      => 1,
						'max'       => $screen_large_desktop,
						'edit'      => 1,
						'required'  => array( 'feat_img_post', '=', 1 ),
						'type'      => 'slider'
					),
					array(
						'title'     => __( 'Disable featured images on single post types', 'shoestrap' ),
						'id'        => 'feat_img_per_post_type',
						'type'      => 'checkbox',
						'options'   => $post_type_options,
						'default'   => $post_type_defaults,
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Jumbotron Settings
			$this->sections[] = array(
				'title' => __( 'Jumbotron', 'shoestrap'),
				'icon'  => 'el-icon-bullhorn',
				'fields'  => apply_filters( 'shoestrap_module_jumbotron_options_modifier', array(
					array(
						'id'        => 'help8',
						'title'     => __( 'Jumbotron', 'shoestrap'),
						'desc'      => __( "A 'Jumbotron', also known as 'Hero' area, is an area in your site where you can display in a prominent position things that matter to you. This can be a slideshow, some text or whatever else you wish. This area is implemented as a widget area, so in order for something to be displayed you will have to add a widget to it.", 'shoestrap' ),
						'type'      => 'info'
					),
					array(
						'title'       => __( 'Jumbotron Background', 'shoestrap' ),
						'desc'        => __( 'Select the background for your Jumbotron area.', 'shoestrap'),
						'id'          => 'jumbo_bg',
						'default'     => array(
							'background-color'    => isset( $ss_settings['jumbotron_bg'] ) ? Shoestrap_Color::sanitize_hex( $ss_settings['jumbotron_bg'] ) : '#eeeeee',
							'background-repeat'   => isset( $ss_settings['jumbotron_background_repeat'] ) ? $ss_settings['jumbotron_background_repeat'] : NULL,
							'background-position' => isset( $ss_settings['jumbotron_background_image_position_toggle'] ) ? $ss_settings['jumbotron_background_image_position_toggle'] . ' center' : NULL,
							'background-image'    => isset( $ss_settings['jumbotron_background_image']['url'] ) ? $ss_settings['jumbotron_background_image']['url'] : NULL,
						),
						'compiler'    => true,
						'output'      => '.jumbotron',
						'type'        => 'background',
					),
					array(
						'title'     => __( 'Display Jumbotron only on the Frontpage.', 'shoestrap' ),
						'desc'      => __( 'When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON', 'shoestrap' ),
						'id'        => 'jumbotron_visibility',
						'default'   => 1,
						'type'      => 'switch'
					),
					array(
						'title'     => __( 'Full-Width', 'shoestrap' ),
						'desc'      => __( 'When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.', 'shoestrap' ),
						'id'        => 'jumbotron_nocontainer',
						'default'   => 1,
						'type'      => 'switch'
					),
					array(
						'title'     => __( 'Jumbotron Font', 'shoestrap' ),
						'desc'      => __( 'The font used in jumbotron.', 'shoestrap' ),
						'id'        => 'font_jumbotron',
						'compiler'  => true,
						'default'   => array(
							'font-family'   => 'Arial, Helvetica, sans-serif',
							'font-size'     => '20px',
							'google'        => 'false',
							'weight'        => 'inherit',
							'color'         => '#333333',
							'font-style'    => 400,
						),
						'preview'   => array(
							'text'  => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'size'  => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => '.jumbotron',
					),
				) ),
			);

			// Jumbotron Settings
			$this->sections[] = array(
				'title' => __( 'Advanced Jumbotron', 'shoestrap'),
				'icon'  => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_jumbotron_advanced_options_modifier', array(
					array(
						'title'     => __( 'Use fittext script for the title.', 'shoestrap' ),
						'desc'      => __( 'Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF', 'shoestrap' ),
						'id'        => 'jumbotron_title_fit',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Center-align the content.', 'shoestrap' ),
						'desc'      => __( 'Turn this on to center-align the contents of the Jumbotron area. Default: OFF', 'shoestrap' ),
						'id'        => 'jumbotron_center',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Jumbotron Font', 'shoestrap' ),
						'desc'      => __( 'The font used in jumbotron.', 'shoestrap' ),
						'id'        => 'font_jumbotron',
						'compiler'  => true,
						'default'   => array(
							'font-family'   => 'Arial, Helvetica, sans-serif',
							'font-size'     => '20px',
							'google'        => 'false',
							'weight'        => 'inherit',
							'color'         => '#333333',
							'font-style'    => 400,
						),
						'preview'   => array(
							'text'  => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'size'  => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => '.jumbotron',
					),
					array(
						'title'     => __( 'Jumbotron Header Overrides', 'shoestrap' ),
						'desc'      => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap' ),
						'id'        => 'font_jumbotron_heading_custom',
						'default'   => 0,
						'compiler'  => true,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Jumbotron Headers Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_jumbotron_headers',
						'compiler'  => true,
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'color'       => '#333333',
							'google'      => 'false'
						),
						'preview'   => array(
							'text'  => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'size'  => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'required'  => array(
							array( 'font_jumbotron_heading_custom', '=', 1 ),
							array( 'options_mode', '=', array( 'advanced' ) ),
						),
					),
					array(
						'title'     => 'Jumbotron Border',
						'desc'      => __( 'Select the border options for your Jumbotron', 'shoestrap' ),
						'id'        => 'jumbotron_border',
						'type'      => 'border',
						'all'       => false,
						'left'      => false,
						'top'       => false,
						'right'     => false,
						'default'   => array(
							'border-top'      => '0',
							'border-bottom'   => '0',
							'border-style'    => 'solid',
							'border-color'    => '#428bca',
						),
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Menus Settings
			$this->sections[] = array(
				'title' => __( 'Menus', 'shoestrap' ),
				'icon'  => 'el-icon-lines',
				'fields'  => apply_filters( 'shoestrap_module_menus_options_modifier', array(
					array(
						'id'          => 'help7',
						'title'       => __( 'Advanced NavBar Options', 'shoestrap' ),
						'desc'        => __( "You can activate or deactivate your Primary NavBar here, and define its properties. Please note that you might have to manually create a menu if it doesn't already exist.", 'shoestrap' ),
						'type'        => 'info'
					),
					array(
						'title'       => __( 'Type of NavBar', 'shoestrap' ),
						'desc'        => __( 'Choose the type of Navbar you want. Off completely hides the navbar, Alternative uses an alternative walker for the navigation menus. See <a target="_blank"href="https://github.com/twittem/wp-bootstrap-navwalker">here</a> for more details.', 'shoestrap' ) . '<br>' . __( '<strong>WARNING:</strong> The "Static-Left" option is ONLY compatible with fluid layouts. The width of the static-left navbar is controlled by the secondary sidebar width.', 'shoestrap' ),
						'id'          => 'navbar_toggle',
						'default'     => 'normal',
						'options'     => array(
							'none'    => __( 'Off', 'shoestrap' ),
							'normal'  => __( 'Normal', 'shoestrap' ),
							// 'pills'   => __( 'Pills', 'shoestrap' ),
							'full'    => __( 'Full-Width', 'shoestrap' ),
							'left'    => __( 'Static-Left', 'shoestrap' ),
						),
						'type'        => 'button_set'
					),
					array(
						'title'       => __( 'Display Branding ( Sitename or Logo ) on the NavBar', 'shoestrap' ),
						'desc'        => __( 'Default: ON', 'shoestrap' ),
						'id'          => 'navbar_brand',
						'default'     => 1,
						'type'        => 'switch'
					),
					array(
						'title'       => __( 'Use Logo ( if available ) for branding on the NavBar', 'shoestrap' ),
						'desc'        => __( 'If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON', 'shoestrap' ),
						'id'          => 'navbar_logo',
						'default'     => 1,
						'type'        => 'switch'
					),
					array(
						'title'       => __( 'NavBar Positioning', 'shoestrap' ),
						'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'shoestrap' ),
						'id'          => 'navbar_fixed',
						'default'     => 0,
						'on'          => __( 'Fixed', 'shoestrap' ),
						'off'         => __( 'Scroll', 'shoestrap' ),
						'type'        => 'switch'
					),
					array(
						'title'       => __( 'Fixed NavBar Position', 'shoestrap' ),
						'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'shoestrap' ),
						'id'          => 'navbar_fixed_position',
						'required'    => array('navbar_fixed','=',array('1')),
						'default'     => 0,
						'on'          => __( 'Bottom', 'shoestrap' ),
						'off'         => __( 'Top', 'shoestrap' ),
						'type'        => 'switch',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Responsive NavBar Threshold', 'shoestrap' ),
						'desc'      => __( 'Point at which the navbar becomes uncollapsed', 'shoestrap' ),
						'id'        => 'grid_float_breakpoint',
						'type'      => 'button_set',
						'options'   => array(
							'min'           => __( 'Never', 'shoestrap' ),
							'screen_xs_min' => __( 'Extra Small', 'shoestrap' ),
							'screen_sm_min' => __( 'Small', 'shoestrap' ),
							'screen_md_min' => __( 'Desktop', 'shoestrap' ),
							'screen_lg_min' => __( 'Large Desktop', 'shoestrap' ),
							'max'           => __( 'Always', 'shoestrap' ),
						),
						'default'   => 'screen_sm_min',
						'compiler'  => true,
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Display social links in the NavBar.', 'shoestrap' ),
						'desc'        => __( 'Display social links in the NavBar. These can be setup in the \'Social\' section on the left. Default: OFF', 'shoestrap' ),
						'id'          => 'navbar_social',
						'default'     => 0,
						'type'        => 'switch'
					),
					array(
						'title'       => __( 'Display social links as a Dropdown list or an Inline list.', 'shoestrap' ),
						'desc'        => __( 'How to display social links. Default: Dropdown list', 'shoestrap' ),
						'id'          => 'navbar_social_style',
						'default'     => 0,
						'on'          => __( 'Inline', 'shoestrap' ),
						'off'         => __( 'Dropdown', 'shoestrap' ),
						'type'        => 'switch',
						'required'    => array('navbar_social','=',array('1')),
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Search form on the NavBar', 'shoestrap' ),
						'desc'        => __( 'Display a search form in the NavBar. Default: On', 'shoestrap' ),
						'id'          => 'navbar_search',
						'default'     => 1,
						'type'        => 'switch'
					),
					array(
						'title'       => __( 'Float NavBar menu to the right', 'shoestrap' ),
						'desc'        => __( 'Floats the primary navigation to the right. Default: On', 'shoestrap' ),
						'id'          => 'navbar_nav_right',
						'default'     => 1,
						'type'        => 'switch'
					),
				) ),
			);

			// Menus Styling Settings
			$this->sections[] = array(
				'title' => __( 'Menus Styling', 'shoestrap' ),
				'icon'  => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_menus_styling_options_modifier', array(
					array(
						'id'          => 'helpnavbarbg',
						'title'       => __( 'NavBar Styling Options', 'shoestrap' ),
						'desc'   	  => __( 'Customize the look and feel of your navbar below.', 'shoestrap' ),
						'type'        => 'info'
					),
					array(
						'title'       => __( 'NavBar Background Color', 'shoestrap' ),
						'desc'        => __( 'Pick a background color for the NavBar. Default: #eeeeee.', 'shoestrap' ),
						'id'          => 'navbar_bg',
						'default'     => '#f8f8f8',
						'compiler'    => true,
						'transparent' => false,
						'type'        => 'color'
					),
					array(
						'title'       => __( 'NavBar Background Opacity', 'shoestrap' ),
						'desc'        => __( 'Pick a background opacity for the NavBar. Default: 100%.', 'shoestrap' ),
						'id'          => 'navbar_bg_opacity',
						'default'     => 100,
						'min'         => 1,
						'step'        => 1,
						'max'         => 100,
						'type'        => 'slider',
					),
					array(
						'title'       => __( 'NavBar Menu Style', 'shoestrap' ),
						'desc'        => __( 'You can use an alternative menu style for your NavBars.', 'shoestrap' ),
						'id'          => 'navbar_style',
						'default'     => 'default',
						'type'        => 'select',
						'options'     => array(
							'default' => __( 'Default', 'shoestrap' ),
							'style1'  => __( 'Style', 'shoestrap' ) . ' 1',
							'style2'  => __( 'Style', 'shoestrap' ) . ' 2',
							'style3'  => __( 'Style', 'shoestrap' ) . ' 3',
							'style4'  => __( 'Style', 'shoestrap' ) . ' 4',
							'style5'  => __( 'Style', 'shoestrap' ) . ' 5',
							'style6'  => __( 'Style', 'shoestrap' ) . ' 6',
							'metro'   => __( 'Metro', 'shoestrap' ),
						)
					),
					array(
						'title'       => __( 'NavBar Height', 'shoestrap' ),
						'desc'        => __( 'Select the height of the NavBar in pixels. Should be equal or greater than the height of your logo if you\'ve added one.', 'shoestrap' ),
						'id'          => 'navbar_height',
						'default'     => 50,
						'min'         => 38,
						'step'        => 1,
						'max'         => 200,
						'compiler'    => true,
						'type'        => 'slider'
					),
					array(
						'title'       => __( 'Navbar Font', 'shoestrap' ),
						'desc'        => __( 'The font used in navbars.', 'shoestrap' ),
						'id'          => 'font_navbar',
						'compiler'    => true,
						'default'     => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => 14,
							'color'       => '#333333',
							'google'      => 'false',
						),
						'preview'     => array(
							'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'size'    => 30 //this is the text size from preview box
						),
						'type'        => 'typography',
					),
					array(
						'title'       => __( 'Branding Font', 'shoestrap' ),
						'desc'        => __( 'The branding font for your site.', 'shoestrap' ),
						'id'          => 'font_brand',
						'compiler'    => true,
						'default'     => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => 18,
							'google'      => 'false',
							'color'       => '#333333',
						),
						'preview'     => array(
							'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'size'    => 30 //this is the text size from preview box
						),
						'type'        => 'typography',
					),
					array(
						'title'       => __( 'NavBar Margin', 'shoestrap' ),
						'desc'        => __( 'Select the top and bottom margin of the NavBar in pixels. Applies only in static top navbar ( scroll condition ). Default: 0px.', 'shoestrap' ),
						'id'          => 'navbar_margin',
						'default'     => 0,
						'min'         => 0,
						'step'        => 1,
						'max'         => 200,
						'type'        => 'slider',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Secondary Menus Settings
			$this->sections[] = array(
				'title' => __( 'Secondary Navbar', 'shoestrap' ),
				'icon'  => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_menus_secondary_options_modifier', array(
					array(
						'id'          => 'help9',
						'title'       => __( 'Secondary Navbar', 'shoestrap' ),
						'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'shoestrap' ),
						'type'        => 'info',
					),
					array(
						'title'       => __( 'Display social networks in the secondary navigation bar.', 'shoestrap' ),
						'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'shoestrap' ),
						'id'          => 'navbar_secondary_social',
						'default'     => 0,
						'type'        => 'switch',
					),
					array(
						'title'       => __( 'Secondary NavBar Margin', 'shoestrap' ),
						'desc'        => __( 'Select the top and bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
						'id'          => 'secondary_navbar_margin',
						'default'     => 0,
						'min'         => 0,
						'max'         => 200,
						'type'        => 'slider',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Secondary Menus Settings
			$this->sections[] = array(
				'title' => __( 'Sidebar Menus', 'shoestrap' ),
				'icon'  => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_menus_secondary_options_modifier', array(
					array(
						'title'     => 'Looking for Advanced Layout Options?',
						'desc'      => '',
						'id'        => 'help6541128',
						'default'   => __( 'Please switch to Anvanced Setup Mode from the "General" section to see more options.', 'shoestrap' ),
						'type'      => 'info',
						'style'     => 'warning',
						'required'  => array( 'options_mode', '=', array( 'easy' ) ),
					),
					array(
						'id'          => 'helpsidebarmenus',
						'title'       => __( 'Sidebar Menus', 'shoestrap' ),
						'desc'        => __( 'If you\'re using the "Custom Menu" widgets in your sidebars, you can control their styling here', 'shoestrap' ),
						'type'        => 'info',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Color for sidebar menus', 'shoestrap' ),
						'desc'        => __( 'Select a style for menus added to your sidebars using the custom menu widget', 'shoestrap' ),
						'id'          => 'menus_class',
						'default'     => 1,
						'type'        => 'select',
						'options'     => array(
							'default' => __( 'Default', 'shoestrap' ),
							'primary' => __( 'Branding-Primary', 'shoestrap' ),
							'success' => __( 'Branding-Success', 'shoestrap' ),
							'warning' => __( 'Branding-Warning', 'shoestrap' ),
							'info'    => __( 'Branding-Info', 'shoestrap' ),
							'danger'  => __( 'Branding-Danger', 'shoestrap' ),
						),
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Inverse Sidebar_menus.', 'shoestrap' ),
						'desc'        => __( 'Default: OFF. See https://github.com/twittem/wp-bootstrap-navlist-walker for more details', 'shoestrap' ),
						'id'          => 'inverse_navlist',
						'default'     => 0,
						'type'        => 'switch',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Header Settings
			$this->sections[] = array(
				'title' => __( 'Header', 'shoestrap'),
				'icon'  => 'el-icon-eye-open',
				'fields'  => apply_filters( 'shoestrap_module_header_options_modifier', array(
					array(
						'id'          => 'help9',
						'title'       => __( 'Extra Branding Area', 'shoestrap' ),
						'desc'        => __( 'You can enable an extra branding/header area. In this header you can add your logo, and any other widgets you wish.', 'shoestrap' ),
						'type'        => 'info',
					),
					array(
						'title'       => __( 'Display the Header.', 'shoestrap' ),
						'desc'        => __( 'Turn this ON to display the header. Default: OFF', 'shoestrap' ),
						'id'          => 'header_toggle',
						'default'     => 0,
						'type'        => 'switch',
					),
					array(
						'title'       => __( 'Display branding on your Header.', 'shoestrap' ),
						'desc'        => __( 'Turn this ON to display branding ( Sitename or Logo )on your Header. Default: ON', 'shoestrap' ),
						'id'          => 'header_branding',
						'default'     => 1,
						'type'        => 'switch',
						'required'    => array('header_toggle','=',array('1')),
					),
					array(
						'title'       => __( 'Header Background', 'shoestrap' ),
						'desc'        => __( 'Specify the background for your header.', 'shoestrap' ),
						'id'          => 'header_bg',
						'default'     => array(
							'background-color' => '#ffffff'
						),
						'output'      => '.before-main-wrapper .header-boxed, .before-main-wrapper .header-wrapper',
						'type'        => 'background',
						'required'    => array( 'header_toggle','=',array( '1' ) ),
					),
					array(
						'title'       => __( 'Header Background Opacity', 'shoestrap' ),
						'desc'        => __( 'Select the background opacity for your header. Default: 100%.', 'shoestrap' ),
						'id'          => 'header_bg_opacity',
						'default'     => 100,
						'min'         => 0,
						'step'        => 1,
						'max'         => 100,
						'compiler'    => true,
						'type'        => 'slider',
						'required'    => array('header_toggle','=',array('1')),
					),
					array(
						'title'       => __( 'Header Text Color', 'shoestrap' ),
						'desc'        => __( 'Select the text color for your header. Default: #333333.', 'shoestrap' ),
						'id'          => 'header_color',
						'default'     => '#333333',
						'transparent' => false,
						'type'        => 'color',
						'required'    => array('header_toggle','=',array('1')),
					),
					array(
						'title'       => __( 'Header Top Margin', 'shoestrap' ),
						'desc'        => __( 'Select the top margin of header in pixels. Default: 0px.', 'shoestrap' ),
						'id'          => 'header_margin_top',
						'default'     => 0,
						'min'         => 0,
						'max'         => 200,
						'type'        => 'slider',
						'required'    => array( 'header_toggle', '=', array('1') ),
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Header Bottom Margin', 'shoestrap' ),
						'desc'        => __( 'Select the bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
						'id'          => 'header_margin_bottom',
						'default'     => 0,
						'min'         => 0,
						'max'         => 200,
						'type'        => 'slider',
						'required'    => array( 'header_toggle', '=', array('1') ),
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Footer Settings
			$this->sections[] = array(
				'title'   => __( 'Footer', 'shoestrap' ),
				'icon' => 'el-icon-caret-down',
				'fields'  => apply_filters( 'shoestrap_module_footer_options_modifier', array(
					array(
						'title'       => __( 'Footer Background Color', 'shoestrap' ),
						'desc'        => __( 'Select the background color for your footer. Default: #282a2b.', 'shoestrap' ),
						'id'          => 'footer_background',
						'default'     => '#282a2b',
						'transparent' => false,
						'type'        => 'color'
					),
					array(
						'title'       => __( 'Footer Background Opacity', 'shoestrap' ),
						'desc'        => __( 'Select the opacity level for the footer bar. Default: 100%.', 'shoestrap' ),
						'id'          => 'footer_opacity',
						'default'     => 100,
						'min'         => 0,
						'max'         => 100,
						'type'        => 'slider',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Footer Text Color', 'shoestrap' ),
						'desc'        => __( 'Select the text color for your footer. Default: #8C8989.', 'shoestrap' ),
						'id'          => 'footer_color',
						'default'     => '#8C8989',
						'transparent' => false,
						'type'        => 'color'
					),
					array(
						'title'       => __( 'Footer Text', 'shoestrap' ),
						'desc'        => __( 'The text that will be displayed in your footer. You can use [year] and [sitename] and they will be replaced appropriately. Default: &copy; [year] [sitename]', 'shoestrap' ),
						'id'          => 'footer_text',
						'default'     => '&copy; [year] [sitename]',
						'type'        => 'textarea'
					),
					array(
						'title'       => 'Footer Border',
						'desc'        => 'Select the border options for your Footer',
						'id'          => 'footer_border',
						'type'        => 'border',
						'all'         => false,
						'left'        => false,
						'bottom'      => false,
						'right'       => false,
						'default'     => array(
							'border-top'      => '0',
							'border-bottom'   => '0',
							'border-style'    => 'solid',
							'border-color'    => '#4B4C4D',
						),
					),
					array(
						'title'       => __( 'Footer Top Margin', 'shoestrap' ),
						'desc'        => __( 'Select the top margin of footer in pixels. Default: 0px.', 'shoestrap' ),
						'id'          => 'footer_top_margin',
						'default'     => 0,
						'min'         => 0,
						'max'         => 200,
						'type'        => 'slider',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'       => __( 'Show social icons in footer', 'shoestrap' ),
						'desc'        => __( 'Show social icons in the footer. Default: On.', 'shoestrap' ),
						'id'          => 'footer_social_toggle',
						'default'     => 0,
						'type'        => 'switch',
					),
					array(
						'title'       => __( 'Footer social links column width', 'shoestrap' ),
						'desc'        => __( 'You can customize the width of the footer social links area. The footer text width will be adjusted accordingly. Default: 5.', 'shoestrap' ),
						'id'          => 'footer_social_width',
						'required'    => array( 'footer_social_toggle','=',array('1') ),
						'default'     => 6,
						'min'         => 3,
						'step'        => 1,
						'max'         => 10,
						'type'        => 'slider',
					),
					array(
						'title'       => __( 'Footer social icons open new window', 'shoestrap' ),
						'desc'        => __( 'Social icons in footer will open a new window. Default: On.', 'shoestrap' ),
						'id'          => 'footer_social_new_window_toggle',
						'required'    => array( 'footer_social_toggle','=',array('1') ),
						'default'     => 1,
						'type'        => 'switch',
						'required'    => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Typography Settings
			$this->sections[] = array(
				'title'   => __( 'Typography', 'shoestrap' ),
				'icon'    => 'el-icon-font',
				'fields'  => apply_filters( 'shoestrap_module_typography_options_modifier', array(
					array(
						'title'     => __( 'Base Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_base',
						'compiler'  => false,
						'units'     => 'px',
						'default'   => array(
							'font-family'   => 'Arial, Helvetica, sans-serif',
							'font-size'     => '14px',
							'google'        => 'false',
							'weight'        => 'inherit',
							'color'         => '#333333',
							'font-style'    => 400,
							'update_weekly' => true // Enable to force updates of Google Fonts to be weekly
						),
						'preview'   => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'body',
					),
					array(
						'title'     => __( 'H1 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_h1',
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '260%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-style'  => 400,

						),
						'preview'   => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h1, .h1',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'id'        => 'font_h2',
						'title'     => __( 'H2 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '215%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-style'  => 400,
						),
						'preview'   => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h2, .h2',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'id'        => 'font_h3',
						'title'     => __( 'H3 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '170%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-style'  => 400,
						),
						'preview'   => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h3, .h3',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'H4 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_h4',
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '125%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-style'  => 400,
						),
						'preview'   => array(
							'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h4, .h4',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'H5 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_h5',
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '100%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-style'  => 400,
						),
						'preview'       => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h5, .h5',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'H6 Font', 'shoestrap' ),
						'desc'      => __( 'The main font for your site.', 'shoestrap' ),
						'id'        => 'font_h6',
						'compiler'  => false,
						'units'     => '%',
						'default'   => array(
							'font-family' => 'Arial, Helvetica, sans-serif',
							'font-size'   => '85%',
							'color'       => $settings['font_base']['color'],
							'google'      => 'false',
							'font-weight' => 400,
							'font-style'  => 'normal',
						),
						'preview'   => array(
							'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
							'font-size'   => '30px' //this is the text size from preview box
						),
						'type'      => 'typography',
						'output'    => 'h6, .h6',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);

			// Social Settings
			$this->sections[] = array(
				'title'     => __( 'Social', 'shoestrap' ),
				'icon'      => 'el-icon-group',
				'fields'  => apply_filters( 'shoestrap_module_socials_options_modifier', array(
					array(
						'id'        => 'social_sharing_help_1',
						'title'     => __( 'Social Sharing', 'shoestrap' ),
						'type'      => 'info'
					),
					array(
						'title'     => __( 'Button Text', 'shoestrap' ),
						'desc'      => __( 'Select the text for the social sharing button.', 'shoestrap' ),
						'id'        => 'social_sharing_text',
						'default'   => 'Share',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Button Location', 'shoestrap' ),
						'desc'      => __( 'Select between NONE, TOP, BOTTOM & BOTH. For archives, "BOTH" fallbacks to "BOTTOM" only.', 'shoestrap' ),
						'id'        => 'social_sharing_location',
						'default'   => 'top',
						'type'      => 'select',
						'options'   => array(
							'none'    =>'None',
							'top'     =>'Top',
							'bottom'  =>'Bottom',
							'both'    =>'Both',
						)
					),
					array(
						'title'     => __( 'Button Styling', 'shoestrap' ),
						'desc'      => __( 'Select between standard Bootstrap\'s button classes', 'shoestrap' ),
						'id'        => 'social_sharing_button_class',
						'default'   => 'default',
						'type'      => 'select',
						'options'   => array(
							'default'    => 'Default',
							'primary'    => 'Primary',
							'success'    => 'Success',
							'warning'    => 'Warning',
							'danger'     => 'Danger',
						)
					),
					array(
						'title'     => __( 'Show in Posts Archives', 'shoestrap' ),
						'desc'      => __( 'Show the sharing button in posts archives.', 'shoestrap' ),
						'id'        => 'social_sharing_archives',
						'default'   => '',
						'type'      => 'switch'
					),
					array(
						'title'     => __( 'Show in Single Post', 'shoestrap' ),
						'desc'      => __( 'Show the sharing button in single post.', 'shoestrap' ),
						'id'        => 'social_sharing_single_post',
						'default'   => '1',
						'type'      => 'switch'
					),
					array(
						'title'     => __( 'Show in Single Page', 'shoestrap' ),
						'desc'      => __( 'Show the sharing button in single page.', 'shoestrap' ),
						'id'        => 'social_sharing_single_page',
						'default'   => '1',
						'type'      => 'switch'
					),
					array(
						'id'        => 'share_networks',
						'type'      => 'checkbox',
						'title'     => __( 'Social Share Networks', 'shoestrap' ),
						'desc'      => __( 'Select the Social Networks you want to enable for social shares', 'shoestrap' ),

						'options'   => array(
							'fb'    => __( 'Facebook', 'shoestrap' ),
							'gp'    => __( 'Google+', 'shoestrap' ),
							'li'    => __( 'LinkedIn', 'shoestrap' ),
							'pi'    => __( 'Pinterest', 'shoestrap' ),
							'rd'    => __( 'Reddit', 'shoestrap' ),
							'tu'    => __( 'Tumblr', 'shoestrap' ),
							'tw'    => __( 'Twitter', 'shoestrap' ),
							'em'    => __( 'Email', 'shoestrap' ),
						)
					),
				) ),
			);

			// Social Settings
			$this->sections[] = array(
				'title'     => __( 'Social Links', 'shoestrap' ),
				'icon'      => 'el-icon-chevron-right',
				'subsection' => true,
				'fields'  => apply_filters( 'shoestrap_module_social_links_options_modifier', array(
					array(
						'id'        => 'social_sharing_help_3',
						'title'     => __( 'Social Links used in Menus && Footer', 'shoestrap' ),
						'type'      => 'info'
					),
					array(
						'title'     => __( 'Blogger', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'blogger_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'DeviantART', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'deviantart_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Digg', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'digg_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Dribbble', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'dribbble_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Facebook', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'facebook_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Flickr', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'flickr_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'GitHub', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'github_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Google+', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'google_plus_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Instagram', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Instagram icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'instagram_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'LinkedIn', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'linkedin_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'MySpace', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the MySpace icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'myspace_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Pinterest', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'pinterest_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Reddit', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'reddit_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'RSS', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'rss_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Skype', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'skype_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'SoundCloud', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'soundcloud_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Tumblr', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'tumblr_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Twitter', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'twitter_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => __( 'Vimeo', 'shoestrap' ),
						'desc'      => __( 'Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
						'id'        => 'vimeo_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),

					array(
						'title'     => 'Vkontakte',
						'desc'      => 'Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.',
						'id'        => 'vkontakte_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
					array(
						'title'     => 'YouTube Link',
						'desc'      => 'Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.',
						'id'        => 'youtube_link',
						'validate'  => 'url',
						'default'   => '',
						'type'      => 'text'
					),
				) ),
			);

			// Advanced Settings
			$this->sections[] = array(
				'title'   => __( 'Advanced', 'shoestrap' ),
				'icon'    => 'el-icon-cogs',
				'fields'  => apply_filters( 'shoestrap_module_advanced_options_modifier', array(
					array(
						'title'     => __( 'Enable Retina mode', 'shoestrap' ),
						'desc'      => __( 'By enabling your site\'s featured images will be retina ready. Requires images to be uploaded at 2x the typical size desired. Default: On', 'shoestrap' ),
						'id'        => 'retina_toggle',
						'default'   => 1,
						'type'      => 'switch',
					),
					array(
						'title'     => __( 'Google Analytics ID', 'shoestrap' ),
						'desc'      => __( 'Paste your Google Analytics ID here to enable analytics tracking. Only Universal Analytics properties. Your user ID should be in the form of UA-XXXXX-Y.', 'shoestrap' ),
						'id'        => 'analytics_id',
						'default'   => '',
						'type'      => 'text',
					),
					array(
						'title'     => 'Border-Radius and Padding Base',
						'id'        => 'help2',
						'desc'      => __( 'The following settings affect various areas of your site, most notably buttons.', 'shoestrap' ),
						'type'      => 'info',
					),
					array(
						'title'     => __( 'Border-Radius', 'shoestrap' ),
						'desc'      => __( 'You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4', 'shoestrap' ),
						'id'        => 'general_border_radius',
						'default'   => 4,
						'min'       => 0,
						'step'      => 1,
						'max'       => 50,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
					),
					array(
						'title'     => __( 'Padding Base', 'shoestrap' ),
						'desc'      => __( 'You can adjust the padding base. This affects buttons size and lots of other cool stuff too! Default: 8', 'shoestrap' ),
						'id'        => 'padding_base',
						'default'   => 6,
						'min'       => 0,
						'step'      => 1,
						'max'       => 20,
						'advanced'  => true,
						'compiler'  => true,
						'type'      => 'slider',
					),
					array(
						'title'     => __( 'Root Relative URLs', 'shoestrap' ),
						'desc'      => __( 'Return URLs such as <em>/assets/css/style.css</em> instead of <em>http://example.com/assets/css/style.css</em>. Default: ON', 'shoestrap' ),
						'id'        => 'root_relative_urls',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Enable Nice Search', 'shoestrap' ),
						'desc'      => __( 'Redirects /?s=query to /search/query/, convert %20 to +. Default: ON', 'shoestrap' ),
						'id'        => 'nice_search',
						'default'   => 1,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Custom CSS', 'shoestrap' ),
						'desc'      => __( 'You can write your custom CSS here. This code will appear in a script tag appended in the header section of the page.', 'shoestrap' ),
						'id'        => 'user_css',
						'default'   => '',
						'type'      => 'ace_editor',
						'mode'      => 'css',
						'theme'     => 'monokai',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Custom LESS', 'shoestrap' ),
						'desc'      => __( 'You can write your custom LESS here. This code will be compiled with the other LESS files of the theme and be appended to the header.', 'shoestrap' ),
						'id'        => 'user_less',
						'default'   => '',
						'type'      => 'ace_editor',
						'mode'      => 'less',
						'theme'     => 'monokai',
						'compiler'  => true,
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Custom JS', 'shoestrap' ),
						'desc'      => __( 'You can write your custom JavaScript/jQuery here. The code will be included in a script tag appended to the bottom of the page.', 'shoestrap' ),
						'id'        => 'user_js',
						'default'   => '',
						'type'      => 'ace_editor',
						'mode'      => 'javascript',
						'theme'     => 'monokai',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Minimize CSS', 'shoestrap' ),
						'desc'      => __( 'Minimize the genearated CSS. This should be ON for production sites. Default: OFF.', 'shoestrap' ),
						'id'        => 'minimize_css',
						'default'   => 1,
						'compiler'  => true,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Toggle adminbar On/Off', 'shoestrap' ),
						'desc'      => __( 'Turn the admin bar On or Off on the frontend. Default: Off.', 'shoestrap' ),
						'id'        => 'advanced_wordpress_disable_admin_bar_toggle',
						'default'   => 1,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
					array(
						'title'     => __( 'Use Google CDN for jQuery', 'shoestrap' ),
						'desc'      => '',
						'id'        => 'jquery_cdn_toggler',
						'default'   => 0,
						'type'      => 'switch',
						'required'  => array( 'options_mode', '=', array( 'advanced' ) ),
					),
				) ),
			);
		}

		public function setArguments() {

			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'          => SHOESTRAP_OPT_NAME,
				'display_name'      => $theme->get( 'Name' ),
				'display_version'   => $theme->get( 'Version' ),
				'menu_type'         => 'menu',
				'allow_sub_menu'    => true,
				'menu_title'        => __( 'Shoestrap', 'shoestrap'),
				'page_title'        => __('Shoestrap Options', 'shoestrap'),
				'global_variable'   => 'redux',

				'google_api_key'    => 'AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs',

				'admin_bar'         => true,
				'dev_mode'          => false,
				'customizer'        => false,

				// OPTIONAL -> Give you extra features
				'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
				'menu_icon'         => '',                      // Specify a custom URL to an icon
				'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
				'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
				'page_slug'         => SHOESTRAP_OPT_NAME,
				'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
				'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
				'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.

				// CAREFUL -> These options are for advanced use only
				'transient_time'    => 60 * MINUTE_IN_SECONDS,
				'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'system_info'           => false, // REMOVE

				'forced_edd_license' => true,

			);


			// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
			$this->args['share_icons'][] = array(
				'url'   => 'https://github.com/shoestrap/shoestrap',
				'title' => 'Fork Me on GitHub',
				'icon'  => 'el-icon-github'
			);

		}

		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {

			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
				remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

				// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
				remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
			}
		}
	}
}

function shoestrap_init_options(){
	global $ss_options;
	$ss_options = new Shoestrap_Options();
}
add_action( 'init', 'shoestrap_init_options' );

/**
 * Adds tracking parameters for Redux settings. Outside of the main class as the class could also be in use in other plugins.
 *
 * @param array $options
 * @return array
 */
function shoestrap_tracking_additions( $options ) {
	$opt = array();
	// This is a Shoestrap specific key. Please do not remove or use in any product
	// that is not based on Shoestrap.
	$options['3a91ce2622596f6b4c67e27a4a2dc313'] = array( 'title'=>'Shoestrap' );

	return $options;
}
add_filter( 'redux/tracking/developer', 'shoestrap_tracking_additions' );
