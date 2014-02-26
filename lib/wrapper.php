<?php
/**
 * Theme wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
function shoestrap_template_path() {
	return Shoestrap_Wrapping::$main_template;
}

function shoestrap_sidebar_path() {
	$template = apply_filters( 'shoestrap_sidebar_path', 'templates/sidebar.php' );
	return new Shoestrap_Wrapping( $template );
}

class Shoestrap_Wrapping {
	// Stores the full path to the main template file
	static $main_template;

	// Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	static $base;

	public function __construct( $template = 'base.php' ) {
		$this->slug = basename( $template, '.php' );
		$this->templates = array( $template );

		if ( self::$base ) {
			$str = substr( $template, 0, -4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	public function __toString() {
		$this->templates = apply_filters( 'shoestrap_wrap_' . $this->slug, $this->templates );
		return ss_locate_template( $this->templates );
	}

	static function wrap( $main ) {
		self::$main_template = $main;
		self::$base = basename( self::$main_template, '.php' );

		if ( self::$base === 'index' ) {
			self::$base = false;
		}

		return new Shoestrap_Wrapping();
	}
}
add_filter( 'template_include', array( 'Shoestrap_Wrapping', 'wrap' ), 99 );
