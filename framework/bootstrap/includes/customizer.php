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
					'style' => '.wrap.main-section .content .bg, .form-control, .btn, .panel',
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

			if ( 'background' == $args['type'] ) {

				// Add settings
				$wp_customize->add_setting( $field,
					array(
						'default'    => $ss_settings[$field]['background-color'],
						'type'       => 'theme_mod',
						'capability' => 'edit_theme_options'
					)
				);

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

				// Generic style for all "background" settings
				echo $args['style'] . ' { background: ' . get_theme_mod( $field ) . '; }';

				// Additional styles for the content background setting
				if ( 'body_bg' == $field ) {
					$bg_brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					// Set an "accent" color depending on the background's brightness
					if ( $bg_brightness > 50 ) {
						$accent = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -30 );
					} else {
						$accent = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 30 );
					}

					echo '.well { background: ' . $accent . '; border-color: ' . $border . ' }';
				}

			}

		}

	}

	echo '</style>';
}
add_action( 'wp_head', 'shoestrap_background_css', 210 );

/**
 * This function takes care of copying theme mods as options in our array,
 * cleaning up the db from our theme-mods and then triggering the compiler.
 */
function shoestrap_customizer_copy_options() {
	global $ss_settings;

	$sections = shoestrap_customizer_fields();

	foreach ( $sections as $section ) {

		$fields = $section['fields'];

		foreach ( $fields as $field => $args ) {

			// Backgrounds are an array of options, so we have to include each one of them separately
			if ( 'background' == $args['type'] ) {
				// Copy the theme_mod to our settings array
				$ss_settings[$field]['background-color'] = get_theme_mod( $field );
				// Clean up theme mods
				remove_theme_mod( $field );
			}

		}

	}

	update_option( SHOESTRAP_OPT_NAME, $ss_settings );

	$compiler = new Shoestrap_Less_PHP();
	add_action( 'customize_save_after', array( $compiler, 'makecss' ), 77 );

}
add_action( 'customize_save_after', 'shoestrap_customizer_copy_options', 75 );
