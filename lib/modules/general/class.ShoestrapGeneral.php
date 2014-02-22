<?php


if( !class_exists( 'ShoestrapGeneral' ) ) {
	/**
	* Build the Shoestrap Footer module class.
	*/
	class ShoestrapGeneral {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 55 ); 
		}

		/*
		 * The footer core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Branding Options
			$section = array(
				'title' => __( 'General', 'shoestrap' ),
				'icon' => 'el-icon-wrench-alt'
			);

			$fields[] = array( 
				'title'       => __( 'Framework Mode', 'shoestrap' ),
				'desc'        => __( 'Choose the framework you want to use', 'shoestrap' ),
				'id'          => 'framework',
				'default'     => 'bootstrap',
				'options'     => array(
					'bootstrap'  => __( 'Bootstrap', 'shoestrap' ),
					'foundation' => __( 'Foundation', 'shoestrap' ),
				),
				'type'        => 'button_set'
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_general_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;
		}
	}
	$general = new ShoestrapGeneral();
}