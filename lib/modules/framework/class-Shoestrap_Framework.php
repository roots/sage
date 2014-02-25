<?php

if ( !class_exists( 'Shoestrap_Framework' ) ) {

	/**
	* The "Advanced" module
	*/
	class Shoestrap_Framework {

		/**
		 * Class constructor
		 */
		function __construct() {

			$settings         = get_option( SHOESTRAP_OPT_NAME );
			$active_framework = $settings['framework'];

			// Add the frameworks select to redux.
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 75 );

			// Include all frameworks
			$modules_path = new RecursiveDirectoryIterator( SHOESTRAP_MODULES_PATH . '/framework/' );
			$recIterator  = new RecursiveIteratorIterator( $modules_path );
			$regex        = new RegexIterator( $recIterator, '/\/framework.php$/i' );

			foreach( $regex as $item ) {
				require_once $item->getPathname();
			}

			$frameworks       = $this->frameworks_list();

			// Return the classname of the active framework.
			foreach ( $frameworks as $framework ) {
				if ( $active_framework == $framework['shortname'] ) {
					$active = $framework['classname'];
				}
			}

			// If no framework is active, return.
			if ( !isset( $active ) ) {
				return;
			}

			$this->fw = new $active;

			add_action( 'shoestrap_nav', array( $this, 'nav_template' ) );
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

			$frameworks_select = array();
			foreach ( $frameworks as $framework ) {
				$frameworks_select[$framework['shortname']] = $framework['name'];
			}

			// Blog Options
			$section = array(
				'title' => __( 'Framework', 'shoestrap' ),
				'icon'  => 'el-icon-website',
			);

			$fields[] = array(
				'title'     => __( 'Framework Select', 'shoestrap' ),
				'desc'      => __( 'Select a framework.', 'shoestrap' ),
				'id'        => 'framework',
				'default'   => '',
				'type'      => 'select',
				'options'   => $frameworks_select,
				'compiler'  => true,
			);

			$section['fields'] = $fields;

			do_action( 'shoestrap_module_layout_options_modifier' );
			
			$sections[] = $section;
			return $sections;
		}

		/**
		 * Calls the framework-specific make_row() function
		 */
		function make_row( $element = 'div', $id = null, $extra_classes = null, $properties = null ) {
			return $this->fw->make_row( $element, $id, $extra_classes, $properties );
		}

		/**
		 * Calls the framework-specific make_col() function
		 */
		function make_col( $element = 'div', $sizes = array( 'menium' => 12 ), $id = null, $extra_classes = null, $properties = null ) {
			return $this->fw->make_col( $element, $sizes, $id, $extra_classes, $properties );
		}

		/**
		 * Calls the framework-specific column_classes() function
		 */
		function column_classes( $sizes = array(), $return = 'string' ) {
			return $this->fw->column_classes( $sizes, $return );
		}

		/**
		 * Calls the framework-specific button_classes() function
		 */
		function button_classes( $color = 'primary', $size = 'medium', $type = 'normal', $extra = null ) {
			return $this->fw->button_classes( $color, $size, $type, $extra );
		}

		/**
		 * Calls the framework-specific button_group_classes() function
		 */
		function button_group_classes( $size = 'medium', $type = null, $extra_classes = null ) {
			return $this->fw->button_group_classes( $size, $type, $extra_classes );
		}

		/**
		 * Calls the framework-specific clearfix() function
		 */
		function clearfix() {
			return $this->fw->clearfix();
		}

		/**
		 * Calls the framework-specific alert() function
		 */
		function alert( $type = 'info', $content = '', $id = null, $extra_classes = null, $dismiss = false ) {
			$this->fw->alert( $type, $content, $id, $extra_classes, $dismiss );
		}

		function nav_template() {
			$initialize = $this->fw->nav_template();
		}
	}
}

global $ss_framework;
$ss_framework = new Shoestrap_Framework();