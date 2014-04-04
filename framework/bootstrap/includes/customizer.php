<?php

/**
 * Build the array of fields
 */
function shoestrap_customizer_fields() {

	$settings = array(
		'background_section' => array(
			'slug'   => 'background',
			'title'  => __( 'Background', 'shoestrap' ),
			'fields' => array(
				'html_bg' => array(
					'label' => __( 'General Background Color', 'shoestrap' ),
					'type'  => 'background',
					'style' => 'body',
				),
				'body_bg' => array(
					'label' => __( 'Content Background', 'shoestrap' ),
					'type'  => 'background',
					'style' => '.wrap.main-section .content .bg, .form-control, .btn, .panel',
				),
			),
		),
		'branding_section' => array(
			'slug'   => 'branding',
			'title'  => __( 'Branding', 'shoestrap' ),
			'fields' => array(
				'color_brand_primary' => array(
					'label' => __( 'Brand Colors: Primary', 'shoestrap' ),
					'type'  => 'color',
					'style' => 'a',
				),
				'color_brand_success' => array(
					'label' => __( 'Brand Colors: Success', 'shoestrap' ),
					'type'  => 'color',
					'style' => '',
				),
				'color_brand_warning' => array(
					'label' => __( 'Brand Colors: Warning', 'shoestrap' ),
					'type'  => 'color',
					'style' => '',
				),
				'color_brand_danger' => array(
					'label' => __( 'Brand Colors: Danger', 'shoestrap' ),
					'type'  => 'color',
					'style' => '',
				),
				'color_brand_info' => array(
					'label' => __( 'Brand Colors: Info', 'shoestrap' ),
					'type'  => 'color',
					'style' => '',
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

			if ( 'background' == $args['type'] ) { // Background-color setting

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

			} elseif ( 'color' == $args['type'] ) { // Color setting

				// Add settings
				$wp_customize->add_setting( $field,
					array(
						'default'    => $ss_settings[$field],
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

			} elseif ( 'color' == $args['type'] ) {

				// Generic style for all "color" settings
				echo $args['style'] . ' { color: ' . get_theme_mod( $field ) . '; }';

				// Additional styles per setting
				if ( 'color_brand_primary' == $field ) {

					$brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					if ( $brightness < 195 ) {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$text_c = '#fff';
					} else {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$text_c = '#333';
					}

					echo '.btn.btn-primary {
						background-color: ' . get_theme_mod( $field ) . ';
						border-color: ' . $border . ';
						color: ' . $text_c . ';
					}';

				} elseif ( 'color_brand_success' == $field ) {

					$brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					if ( $brightness < 195 ) {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$text_c = '#fff';
					} else {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$text_c = '#333';
					}

					echo '.btn.btn-success {
						background-color: ' . get_theme_mod( $field ) . ';
						border-color: ' . $border . ';
						color: ' . $text_c . ';
					}';

				} elseif ( 'color_brand_warning' == $field ) {

					$brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					if ( $brightness < 195 ) {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$text_c = '#fff';
					} else {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$text_c = '#333';
					}

					echo '.btn.btn-warning {
						background-color: ' . get_theme_mod( $field ) . ';
						border-color: ' . $border . ';
						color: ' . $text_c . ';
					}';

				} elseif ( 'color_brand_danger' == $field ) {

					$brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					if ( $brightness < 195 ) {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$text_c = '#fff';
					} else {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$text_c = '#333';
					}

					echo '.btn.btn-danger {
						background-color: ' . get_theme_mod( $field ) . ';
						border-color: ' . $border . ';
						color: ' . $text_c . ';
					}';

				} elseif ( 'color_brand_info' == $field ) {

					$brightness = Shoestrap_Color::get_brightness( get_theme_mod( $field ) );
					if ( $brightness < 195 ) {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), -20 );
						$text_c = '#fff';
					} else {
						$border = Shoestrap_Color::adjust_brightness( get_theme_mod( $field ), 20 );
						$text_c = '#333';
					}

					echo '.btn.btn-info {
						background-color: ' . get_theme_mod( $field ) . ';
						border-color: ' . $border . ';
						color: ' . $text_c . ';
					}';

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
			} else {
				// Copy the theme_mod to our settings array
				$ss_settings[$field] = get_theme_mod( $field );
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
