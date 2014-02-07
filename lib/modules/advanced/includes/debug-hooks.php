<?php

if ( !function_exists( 'shoestrap_debug_hooks' ) ) :
function shoestrap_debug_hooks() {
	global $redux;
	if ( current_user_can( 'administrator' ) && shoestrap_getVariable( 'debug_hooks' ) == 1 ) : ?>
		<div class='panel widget-inner clearfix'>
			<div class='panel-heading'>Debug Information</div>
			<ul class='nav nav-tabs' id='debugTabs'>
				<li class='active'><a href='#SMOFData'>SMOF Data</a></li>
				<li><a href='#hooksdebug'>Wordpress Hooks</a></li>
			</ul>
			<div class='tab-content'>
				<div class='tab-pane active' id='SMOFData'>
					<?php
						$redux_r = print_r( $redux, true );
						$redux_r_sans = htmlspecialchars( $redux_r, ENT_QUOTES );
						echo '<pre>'. $redux_r_sans .'<pre>';
					?>
				</div>
				<div class='tab-pane' id='hooksdebug'><?php echo list_hooks(); ?></div>
			</div>
		</div>
		<script>
			/** Fire up jQuery - let's dance! */
			jQuery( document ).ready( function( $ ){
				$( '#debugTabs a' ).click( function ( e ) {
					e.preventDefault();
					$( this ).tab( 'show' );
				})
			})
		</script>
		<?php
	endif;
}
endif;
add_action( 'shoestrap_after_content', 'shoestrap_debug_hooks' );


if ( !function_exists( 'list_hooks' ) ) :
function list_hooks( $filter = false ) {
	global $wp_filter;
	
	$hooks = $wp_filter;
	ksort( $hooks );

	foreach( $hooks as $tag => $hook ) {
		if ( false === $filter || false !== strpos( $tag, $filter ) )
			dump_hook($tag, $hook);
	}
}
endif;

if ( !function_exists( 'list_live_hooks' ) ) :
function list_live_hooks( $hook = false ) {
	if ( false === $hook )
		$hook = 'all';

	add_action( $hook, 'list_hook_details', -1 );
}
endif;

if ( !function_exists( 'list_hook_details' ) ) :
function list_hook_details( $input = NULL ) {
	global $wp_filter;

	$tag = current_filter();

	if( isset( $wp_filter[$tag] ) )
		dump_hook( $tag, $wp_filter[$tag] );

	return $input;
}
endif;

if ( !function_exists( 'dump_hook' ) ) :
function dump_hook( $tag, $hook ) {
	ksort( $hook );
	echo "<pre>&gt;&gt;&gt;&gt;&gt;\t<strong>$tag</strong><br />";
	
	foreach ( $hook as $priority => $functions ) {
		echo $priority;

		foreach ( $functions as $function ) {
			if ( $function['function'] != 'list_hook_details' ) {
				echo "\t";

				if ( is_string( $function['function'] ) )
					echo $function['function'];
				elseif ( is_string( $function['function'][0] ) )
					echo $function['function'][0] . ' -> ' . $function['function'][1];
				elseif ( is_object( $function['function'][0] ) )
					echo "(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1];
				else
					print_r( $function );

				echo ' (' . $function['accepted_args'] . ') <br />';
			}
		}
	}
	echo '</pre>';
}
endif;