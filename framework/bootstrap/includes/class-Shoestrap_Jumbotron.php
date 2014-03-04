<?php


if ( ! class_exists( 'Shoestrap_Jumbotron' ) ) {

	/**
	* The Jumbotron module
	*/
	class Shoestrap_Jumbotron {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 90 );
			add_action( 'widgets_init',       array( $this, 'jumbotron_widgets_init'           ), 20  );
			add_action( 'shoestrap_pre_wrap', array( $this, 'jumbotron_content'                ), 5   );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_css'                    ), 101 );
			add_action( 'wp_footer',          array( $this, 'jumbotron_fittext'                ), 10  );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_fittext_enqueue_script' ), 101 );
		}
		/*
		 * The Jumbotron module options.
		 */
		function options( $sections ) {
			global $ss_settings;

			// Jumbotron Options
			$section = array(
				'title' => __( 'Jumbotron', 'shoestrap'),
				'icon'  => 'el-icon-bullhorn'
			);

			$fields[] = array(
				'id'        => 'help8',
				'title'     => __( 'Jumbotron', 'shoestrap'),
				'desc'      => __( "A 'Jumbotron', also known as 'Hero' area, is an area in your site where you can display in a prominent position things that matter to you. This can be a slideshow, some text or whatever else you wish. This area is implemented as a widget area, so in order for something to be displayed you will have to add a widget to it.", 'shoestrap' ),
				'type'      => 'info'
			);

			$fields[] = array(
				'title'       => __( 'Jumbotron Background', 'shoestrap' ),
				'desc'        => __( 'Select the background for your Jumbotron area.', 'shoestrap'),
				'id'          => 'jumbo_bg',
				'default'     => array(
					'background-color'    => isset( $ss_settings['jumbotron_bg'] ) ? $ss_settings['jumbotron_bg'] : '#eeeeee',
					'background-repeat'   => isset( $ss_settings['jumbotron_background_repeat'] ) ? $ss_settings['jumbotron_background_repeat'] : NULL,
					'background-position' => isset( $ss_settings['jumbotron_background_image_position_toggle'] ) ? $ss_settings['jumbotron_background_image_position_toggle'] . ' center' : NULL,
					'background-image'    => isset( $ss_settings['jumbotron_background_image']['url'] ) ? $ss_settings['jumbotron_background_image']['url'] : NULL,
				),
				'compiler'    => true,
				'output'      => '.jumbotron',
				'type'        => 'background',
			);

			$fields[] = array(
				'title'     => __( 'Display Jumbotron only on the Frontpage.', 'shoestrap' ),
				'desc'      => __( 'When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON', 'shoestrap' ),
				'id'        => 'jumbotron_visibility',
				'default'   => 1,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Full-Width', 'shoestrap' ),
				'desc'      => __( 'When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.', 'shoestrap' ),
				'id'        => 'jumbotron_nocontainer',
				'default'   => 1,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Use fittext script for the title.', 'shoestrap' ),
				'desc'      => __( 'Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF', 'shoestrap' ),
				'id'        => 'jumbotron_title_fit',
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __( 'Center-align the content.', 'shoestrap' ),
				'desc'      => __( 'Turn this on to center-align the contents of the Jumbotron area. Default: OFF', 'shoestrap' ),
				'id'        => 'jumbotron_center',
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __( 'Jumbotron Font', 'shoestrap' ),
				'desc'      => __( 'The font used in jumbotron.', 'shoestrap' ),
				'id'        => 'font_jumbotron',
				'compiler'  => true,
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 20,
					'color'       => '#333333',
					'google'      => 'false',
					'units'       => 'px'
				),
				'preview'   => array(
					'text'  => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'size'  => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
			);

			$fields[] = array(
				'title'     => __( 'Jumbotron Header Overrides', 'shoestrap' ),
				'desc'      => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap' ),
				'id'        => 'font_jumbotron_heading_custom',
				'default'   => 0,
				'compiler'  => true,
				'type'      => 'switch',
			);

			$fields[] = array(
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
				'required'  => array( 'font_jumbotron_heading_custom','=',array( '1' ) ),
			);

			$fields[] = array(
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
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_jumbotron_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		/**
		 * Register sidebars and widgets
		 */
		function jumbotron_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Jumbotron', 'shoestrap' ),
				'id'            => 'jumbotron',
				'before_widget' => '<section id="%1$s"><div class="section-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/*
		 * The content of the Jumbotron region
		 * according to what we've entered in the customizer
		 */
		function jumbotron_content() {
			global $ss_settings, $ss_framework;

			$hero         = false;
			$site_style   = $ss_settings['site_style'];
			$visibility   = $ss_settings['jumbotron_visibility'];
			$nocontainer  = $ss_settings['jumbotron_nocontainer'];

			if ( ( ( $visibility == 1 && is_front_page() ) || $visibility != 1 ) && is_active_sidebar( 'jumbotron' ) ) {
				$hero = true;
			}

			if ( $hero ) {
				echo $ss_framework->clearfix();
				echo '<div class="before-main-wrapper">';

				if ( $site_style == 'boxed' && $nocontainer != 1 ) {
					echo '<div class="' . Shoestrap_Layout::container_class() . '">';
				}

				echo '<div class="jumbotron">';

				if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) {
					echo '<div class="' . Shoestrap_Layout::container_class() . '">';
				}

				dynamic_sidebar( 'Jumbotron' );

				if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) {
					echo '</div>';
				}

				echo '</div>';

				if ( $site_style == 'boxed' && $nocontainer != 1 ) {
					echo '</div>';
				}

				echo '</div>';
			}
		}

		/**
		 * Any Jumbotron-specific CSS that can't be added in the .less stylesheet is calculated here.
		 */
		function jumbotron_css() {
			global $ss_settings;

			$center = $ss_settings['jumbotron_center'];
			$border = $ss_settings['jumbotron_border'];

			$style = '';

			if ( $center == 1 ) {
				$style .= 'text-align: center;';
			}

			if ( ! empty( $border ) && $border['border-bottom'] > 0 && ! empty( $border['border-color'] ) ) {
				$style .= 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
			}

			$style .= 'margin-bottom: 0px;';

			$theCSS = '.jumbotron {' . trim( $style ) . '}';

			wp_add_inline_style( 'shoestrap_css', $theCSS );
		}

		/*
		 * Enables the fittext.js for h1 headings
		 */
		function jumbotron_fittext() {
			global $ss_settings;

			$fittext_toggle   = $ss_settings['jumbotron_title_fit'];
			$jumbo_visibility = $ss_settings['jumbotron_visibility'];

			// Should only show on the front page if it's enabled, or site-wide when appropriate
			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
				echo '<script>jQuery(".jumbotron h1").fitText(1.3);</script>';
			}
		}

		/*
		 * Enqueues fittext.js when needed
		 */
		function jumbotron_fittext_enqueue_script() {
			global $ss_settings;

			$fittext_toggle   = $ss_settings['jumbotron_title_fit'];
			$jumbo_visibility = $ss_settings['jumbotron_visibility'];

			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
				wp_register_script( 'fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false );
				wp_enqueue_script( 'fittext' );
			}
		}
	}
}