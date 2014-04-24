<?php


if ( ! class_exists( 'Shoestrap_Jumbotron' ) ) {

	/**
	* The Jumbotron module
	*/
	class Shoestrap_Jumbotron {

		function __construct() {
			add_action( 'widgets_init',       array( $this, 'jumbotron_widgets_init'           ), 20  );
			add_action( 'shoestrap_pre_wrap', array( $this, 'jumbotron_content'                ), 5   );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_css'                    ), 101 );
			add_action( 'wp_footer',          array( $this, 'jumbotron_fittext'                ), 10  );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_fittext_enqueue_script' ), 101 );
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
