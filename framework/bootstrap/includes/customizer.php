<?php

/**
 * Build the array of fields
 */
function shoestrap_customizer_fields() {

	$settings = array(
		'section' => array(
			'slug'   => 'background',
			'title'  => __( 'Background', 'shoestrap' ),
			'fields' => array(
				'html_bg'   => array(
					'label' => __( 'General Background Color', 'shoestrap' ),
					'type'  => 'background',
					'style' => 'body',
				),
				'body_bg'   => array(
					'label' => __( 'Content Background', 'shoestrap' ),
					'type'  => 'background',
					'style' => '.wrap.main-section .content .bg',
				),
			),
		),
	);

	return $settings;
}

/*
 * Creates the section, settings and the controls for the customizer
 */
function shoestrap_customizer( $wp_customize ) {
	global $ss_settings;

	$sections = shoestrap_customizer_fields();

	foreach ( $sections as $section ) {

		// Add sections
		$wp_customize->add_section(
			$section['slug'],
			array( 'title' => $section['title'] )
		);

		// Get fields
		$fields = $section['fields'];
		foreach ( $fields as $field => $args ) {

			// Add settings
			$wp_customize->add_setting( $field,
				array(
					'default'    => $ss_settings[$field],
					'type'       => 'theme_mod',
					'capability' => 'edit_theme_options'
				)
			);

			if ( 'background' == $args['type'] ) {
				// Add control
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $field, array(
					'label'    => $args['label'],
					'section'  => $section['slug'],
					'settings' => $field,
				) ) );
			}
		}
	}
}
add_action( 'customize_register', 'shoestrap_customizer' );

/*
 * Applies the customizer styles to the preview screen.
 */
function shoestrap_background_css() {
	$sections = shoestrap_customizer_fields();

	echo '<style>';

	foreach ( $sections as $section ) {

		$fields = $section['fields'];

		foreach ( $fields as $field => $args ) {

			if ( 'background' == $args['type'] ) {

				echo $args['style'] . ' { background: ' . get_theme_mod( $field ) . '; }';

			}

		}

	}

	echo '</style>';
}
add_action( 'wp_head', 'shoestrap_background_css', 210 );

/**
 * Copy theme mods as options in our array
 */
function shoestrap_customizer_copy_options() {
	global $ss_settings;
}
add_action( 'customize_save_after', 'shoestrap_customizer_copy_options', 127 );

/**
 * Trigger the compiler after the customizer is saved
 */
function shoestrap_customizer_trigger_compiler() {

	$compiler = new Shoestrap_Less_PHP();
	add_action( 'redux/options/' . SHOESTRAP_OPT_NAME . '/compiler' , array( $compiler, 'makecss' ) );

}
add_action( 'customize_save_after', 'shoestrap_customizer_trigger_compiler', 130 );
