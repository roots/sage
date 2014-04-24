<?php

if ( ! class_exists( 'Shoestrap_Background' ) ) {

	/**
	* The "Background" module
	*/
	class Shoestrap_Background {

		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 101 );
			add_action( 'plugins_loaded',     array( $this, 'upgrade_options' ) );
		}

		function css() {
			global $ss_settings;

			$content_opacity = $ss_settings['body_bg_opacity'];
			$bg_color        = $ss_settings['body_bg'];

			if ( isset( $bg_color['background-color'] ) ) {
				$bg_color = $bg_color['background-color'];
			} else {
				$bg_color = '#ffffff';
			}

			// Style defaults to null.
			$style = null;

			// The Content background color
			if ( $content_opacity < 100 ) {

				$content_bg = 'background:' . Shoestrap_Color::get_rgba( $bg_color, $content_opacity ) . ';';
				$style = '.wrap.main-section div.content .bg {' . $content_bg . '}';

			}

			wp_add_inline_style( 'shoestrap_css', $style );
		}
	}
}
