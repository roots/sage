<?php

if ( !function_exists( 'shoestrap_slidedown_widgets_init' ) ) :
function shoestrap_slidedown_widgets_init() {
	// Register widgetized areas
	register_sidebar( array( 
		'name'          => __( 'Navbar Slide-Down Top', 'shoestrap' ),
		'id'            => 'navbar-slide-down-top',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array( 
		'name'          => __( 'Navbar Slide-Down 1', 'shoestrap' ),
		'id'            => 'navbar-slide-down-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array( 
		'name'          => __( 'Navbar Slide-Down 2', 'shoestrap' ),
		'id'            => 'navbar-slide-down-2',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array( 
		'name'          => __( 'Navbar Slide-Down 3', 'shoestrap' ),
		'id'            => 'navbar-slide-down-3',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array( 
		'name'          => __( 'Navbar Slide-Down 4', 'shoestrap' ),
		'id'            => 'navbar-slide-down-4',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
endif;
add_action( 'widgets_init', 'shoestrap_slidedown_widgets_init', 20 );

if ( !function_exists( 'shoestrap_navbar_widget_area_class' ) ) :
/*
 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
 */
function shoestrap_navbar_widget_area_class() {
	$str = '';
	$str .= ( is_active_sidebar( 'navbar-slide-down-1' ) ) ? '1' : '';
	$str .= ( is_active_sidebar( 'navbar-slide-down-2' ) ) ? '2' : '';
	$str .= ( is_active_sidebar( 'navbar-slide-down-3' ) ) ? '3' : '';
	$str .= ( is_active_sidebar( 'navbar-slide-down-4' ) ) ? '4' : '';

	$strlen = strlen( $str );

	$colwidth = ( $strlen > 0 ) ? 12 / $strlen : 12;

	return $colwidth;
}
endif;

if ( !function_exists( 'shoestrap_navbar_slidedown_content' ) ) :
/*
 * Prints the content of the slide-down widget areas.
 */
function shoestrap_navbar_slidedown_content() {
	if ( is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) || is_active_sidebar( 'navbar-slide-down-top' ) ) : ?>
		<div class="before-main-wrapper">
			<?php $megadrop_class = ( shoestrap_getVariable( 'site_style' ) != 'fluid' ) ? 'top-megamenu container' : 'top-megamenu'; ?>
			<div id="megaDrop" class="' . $megadrop_class . '">
				<?php $widgetareaclass = 'col-sm-' . shoestrap_navbar_widget_area_class(); ?>

				<?php dynamic_sidebar( 'navbar-slide-down-top' ); ?>

				<div class="row">
					<?php if ( is_active_sidebar( 'navbar-slide-down-1' ) ) : ?>
						<div class="<?php echo $widgetareaclass; ?>">
							<?php dynamic_sidebar( 'navbar-slide-down-1' ); ?>
						</div>
					<?php endif; ?>
		
					<?php if ( is_active_sidebar( 'navbar-slide-down-2' ) ) : ?>
						<div class="<?php echo $widgetareaclass; ?>">
							<?php dynamic_sidebar( 'navbar-slide-down-2' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'navbar-slide-down-3' ) ) : ?>
						<div class="<?php echo $widgetareaclass; ?>">
							<?php dynamic_sidebar( 'navbar-slide-down-3' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'navbar-slide-down-4' ) ) : ?>
						<div class="<?php echo $widgetareaclass; ?>">
							<?php dynamic_sidebar( 'navbar-slide-down-4' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif;
}
endif;
add_action( 'shoestrap_do_navbar', 'shoestrap_navbar_slidedown_content', 99 );


if ( !function_exists( 'shoestrap_navbar_slidedown_toggle' ) ) :
function shoestrap_navbar_slidedown_toggle() {
	$navbar_color = shoestrap_getVariable( 'navbar_bg' );
	$navbar_mode  = shoestrap_getVariable( 'navbar_toggle' );
	$trigger = (
		is_active_sidebar( 'navbar-slide-down-top' ) ||
		is_active_sidebar( 'navbar-slide-down-1' ) ||
		is_active_sidebar( 'navbar-slide-down-2' ) ||
		is_active_sidebar( 'navbar-slide-down-3' ) ||
		is_active_sidebar( 'navbar-slide-down-4' ) 
	) ? true : false;
	
	if ( $trigger ) {

		$class = ( $navbar_mode == 'left' ) ? ' static-left' : ' nav-toggle';
		$pre   = ( $navbar_mode != 'left' ) ? '<ul class="nav navbar-nav"><li>' : '';
		$post  = ( $navbar_mode != 'left' ) ? '</li></ul>' : '';

		echo $pre . '<a class="toggle-nav' . $class . '" href="#"><i class="el-icon-chevron-down"></i></a>' . $post;

	}
}
endif;


if ( !function_exists( 'shoestrap_navbar_slidedown_toggle_trigger' ) ) :
function shoestrap_navbar_slidedown_toggle_trigger() {
	$hook = ( shoestrap_getVariable( 'navbar_toggle' ) == 'left' ) ? 'shoestrap_do_navbar' : 'shoestrap_pre_main_nav';
	add_action( $hook, 'shoestrap_navbar_slidedown_toggle' );
}
add_action( 'init', 'shoestrap_navbar_slidedown_toggle_trigger' );
endif;



if ( !function_exists( 'shoestrap_megadrop_script' ) ) :
function shoestrap_megadrop_script() {
	if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
		wp_register_script( 'shoestrap_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
		wp_enqueue_script( 'shoestrap_megadrop' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_megadrop_script', 200 );