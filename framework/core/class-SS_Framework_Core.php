<?php

/**
* The Framework
*/
class SS_Framework_Core {

	var $defines = array(
		// Layout
		'container'  => null,
		'row'        => 'row',
		'col-mobile' => 'small',
		'col-tablet' => 'small',
		'col-medium' => 'medium',
		'col-large'  => 'large',
		// Block Grid not supported

		// Buttons
		'button'         => 'button',
		'button-default' => null,
		'button-primary' => null,
		'button-success' => 'success',
		'button-info'    => 'secondary',
		'button-warning' => 'alert',
		'button-danger'  => 'alert',
		'button-link'    => null,
		'button-disabled'=> 'disabled',

		'button-extra-small' => 'tiny',
		'button-small'       => 'small',
		'button-medium'      => null,
		'button-large'       => 'large',
		'button-extra-large' => 'large',
		'button-block'			 => 'expand',

		// Button-Groups
		'button-group'             => 'button-group',
		'button-group-extra-small' => null,
		'button-group-small'       => null,
		'button-group-default'     => null,
		'button-group-large'       => null,
		'button-group-extra-large' => null,
		// Button Bar not supported

		// Alerts
		'alert'         => 'alert-box',
		'alert-success' => 'success',
		'alert-info'    => 'info',
		'alert-warning' => 'warning',
		'alert-danger'  => 'warning',

		// Miscelaneous
		'clearfix' => '<div class="clearfix"></div>',
	);

	/**
	 * Makes a container
	 */
	public function make_container() { }

	/**
	 * Creates a row using the framework definitions.
	 *
	 * @param string $element         Can be any valid dom element.
	 * @param string $id              The element ID.
	 * @param string $extra_classes   Any extra classes we want to add to the row. extra classes should be separated using a space.
	 * @param string $properties      Can be something like 'name="left_top"'.
	 */
	public function make_row( $element = 'div', $id = null, $extra_classes = null, $properties = null ) {

		$classes = $this->defines['row'];

		if ( !is_null( $id ) ) {
			$id = ' id=' . $id . '"';
		}

		if ( !is_null( $extra_classes ) ) {
			$classes .= ' ' . $extra_classes;
		}

		if ( !is_null( $properties ) ) {
			$properties = ' ' . $properties;
		}

		return '<' . $element . $id . ' class="' . $classes . '"' . $properties . '>';
	}

	/**
	 * Creates a column using the framework definitions.
	 *
	 * @param string $element         Can be any valid dom element.
	 * @param array  $sizes           Format is size => columns. Example: array( 'mobile' => 12, 'tablet' => 12, 'medium' => 6, 'large' => 4 )
	 * @param string $id              The element ID.
	 * @param string $extra_classes   Any extra classes we want to add to the row. extra classes should be separated using a space.
	 * @param string $properties      Can be something like 'name="left_top"'.
	 */
	public function make_col( $element = 'div', $sizes = array( 'medium' => 12 ), $id = null, $extra_classes = null, $properties = null ) {

		// Get the classes based on the $sizes array.
		$classes = $this->column_classes( $sizes );


		// If extra classes are defined, add them to the array of classes.
		if ( !is_null( $extra_classes ) ) {
			$extra_classes = explode( ' ', $extra_classes );

			foreach ( $extra_classes as $extra_class ) {
				$classes[] = $extra_class;
			}
		}

		// build the CSS classes from the array
		$css_classes = implode( ' ', $classes );

		// If an ID has been defined, format it properly.
		if ( !is_null( $id ) ) {
			$id = ' id=' . $id . '"';
		}

		// Are there any extra properties to add?
		if ( !is_null( $properties ) ) {
			$properties = ' ' . $properties;
		}

		return '<' . $element . $id . ' class="' . $css_classes . '"' . $properties . '>';
	}

	/**
	 * Column classes
	 */
	public function column_classes( $sizes = array(), $return = 'array' ) {
		$classes = array();

		// Get the classes based on the $sizes array.
		foreach ( $sizes as $size => $columns ) {
			$classes[] = $this->defines['col-' . $size] . '-' . $columns;
		}
		$classes[] = 'columns';

		if ( $return == 'array' ) {
			return $classes;
		} else {
			return implode( ' ', $classes );
		}

	}

	/**
	 * Get the button classes
	 *
	 * @param string $color
	 * @param string $size
	 * @param string $type
	 */
	public function button_classes( $color = 'primary', $size = 'medium', $type = null, $extra = null ) {

		$classes = array();

		$classes[] = $this->defines['button'];

		// Should we allow multiple colors?
		// Perhaps we should... you never know.
		if ( !is_null( $color ) ) {
			$colors = explode( ' ', $color );

			foreach ( $colors as $color ) {
				$classes[] = $this->defines['button-' . $color];
			}
		}

		// Get the proper class for button sizing from the framework definitions.
		if ( $size == 'extra-small' ) {
			$classes[] = $this->defines['button-extra-small'];
		} elseif ( $size == 'small' ) {
			$classes[] = $this->defines['button-small'];
		} elseif ( $size == 'medium' ) {
			$classes[] = $this->defines['button-medium'];
		} elseif ( $size == 'large' ) {
			$classes[] = $this->defines['button-large'];
		} elseif ( $size == 'extra-large' ) {
			$classes[] = $this->defines['button-extra-large'];
		} elseif ( $size == 'block' ) {
			$classes[] = $this->defines['button-block'];
		}

		if ( !is_null( $type ) ) {
			$types = explode( ' ', $type );

			foreach ( $types as $type ) {
				$classes[] = $type;
			}
		}

		if ( !is_null( $extra ) ) {
			$extras = explode( ' ', $extra );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
		}

		// build the CSS classes from the array
		$css_classes = implode( ' ', $classes );

		return $css_classes;
	}

	public function button_group_classes( $size = null, $type = null, $extra_classes = null ) {

		$classes = array();

		$classes[] = $this->defines['button-group'];

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
		}

		if ( !is_null( $type ) ) {
			$types = explode( ' ', $type );

			foreach ( $types as $type ) {
				$classes[] = $type;
			}
		}


		$classes = implode( ' ', $classes );

		return $classes;
	}

	/**
	 * The framework's clearfix
	 */
	public function clearfix() {
		return $this->defines['clearfix'];
	}

	public function pagination_ul_class() {
		return 'pagination';
	}

	/**
	 * The framework's alert boxes.
	 */
	public function alert( $type = 'info', $content = '', $id = null, $extra_classes = null, $dismiss = false ) {
		$classes = array();

		$classes[] = $this->defines['alert'];
		$classes[] = $this->defines['alert-' . $type];

		if ( true == $dismiss ) {
			$dismiss = '<a href="#" class="close">&times;</a>';
		} else {
			$dismiss = null;
		}

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
		}

		// If an ID has been defined, format it properly.
		if ( !is_null( $id ) ) {
			$id = ' id=' . $id . '"';
		}

		$classes = implode( ' ', $classes );

		return '<div data-alert class="' . $classes . '"' . $id . '>' . $content . $dismiss . '</div>';
	}

	public function make_panel( $extra_classes = null, $id = null  ) {

		$classes = array();

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
			$classes = ' ' . implode( ' ', $classes );
		} else {
			$classes = null;
		}

		// If an ID has been defined, format it properly.
		if ( !is_null( $id ) ) {
			$id = ' id=' . $id . '"';
		}

		return '<div class="panel ' . $classes . '"' . $id . '>';
	}

	public function make_panel_heading( $extra_classes = null ) {

		$classes = array();

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
			$classes = ' ' . implode( ' ', $classes );
		} else {
			$classes = null;
		}

		return '<div class="panel-heading' . $classes . '">';
	}

	public function make_panel_body( $extra_classes = null ) {
		$classes = array();

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
			$classes = ' ' . implode( ' ', $classes );
		} else {
			$classes = null;
		}

		return '<div class="panel-body' . $classes . '">';
	}

	public function make_panel_footer( $extra_classes = null ) {

		$classes = array();

		if ( !is_null( $extra_classes ) ) {
			$extras = explode( ' ', $extra_classes );

			foreach ( $extras as $extra ) {
				$classes[] = $extra;
			}
			$classes = ' ' . implode( ' ', $classes );
		} else {
			$classes = null;
		}

		return '<div class="panel-footer' . $classes . '">';
	}

	public function include_wrapper() {}

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