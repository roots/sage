<?php


if ( !function_exists( 'shoestrap_variables' ) ) :
/*
 * The content below is a copy of bootstrap's variables.less file.
 *
 * Some options are user-configurable and stored as theme mods.
 * We try to minimize the options and simplify the user environment.
 * In order to do that, we 'll have to provide a minimum amount of options
 * and calculate the rest based on the user's selections.
 *
 */
function shoestrap_variables() {


	$font_brand             = shoestrap_process_font( shoestrap_getVariable( 'font_brand', true ) );
	$font_heading           = shoestrap_process_font( shoestrap_getVariable( 'font_heading', true ) );  

	$font_style_base  = $font_base['font-style'];
	$font_weight_base = $font_base['font-weight'];
}
endif;