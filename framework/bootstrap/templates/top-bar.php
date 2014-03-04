<?php
global $ss_settings;

$navbar_toggle = $ss_settings['navbar_toggle'];

if ( $navbar_toggle != 'none' ) {
	if ( ! has_action( 'shoestrap_header_top_navbar_override' ) ) { ?>

		<header id="banner-header" class="banner <?php echo apply_filters( 'shoestrap_navbar_class', 'navbar navbar-default' ); ?>" role="banner">
			<div class="<?php echo apply_filters( 'shoestrap_navbar_container_class', 'container' ); ?>">
				<div class="navbar-header">
					<?php echo apply_filters( 'shoestrap_nav_toggler', '
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-main, .nav-extras">
						<span class="sr-only">' . __( 'Toggle navigation', 'shoestrap' ) . '</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>' ); ?>
					<?php echo apply_filters( 'shoestrap_navbar_brand', '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?>
				</div>
				<?php if ( has_action( 'shoestrap_pre_main_nav' ) ) : ?>
					<div class="nav-extras">
						<?php do_action( 'shoestrap_pre_main_nav' ); ?>
					</div>
				<?php endif; ?>
				<nav class="nav-main navbar-collapse collapse" role="navigation">
					<?php
					do_action( 'shoestrap_inside_nav_begin' );
					if ( has_nav_menu( 'primary_navigation' ) )
						wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => apply_filters( 'shoestrap_nav_class', 'navbar-nav nav' ) ) );

					do_action( 'shoestrap_inside_nav_end' );
					?>
				</nav>
				<?php do_action( 'shoestrap_post_main_nav' ); ?>
			</div>
		</header>
		<?php do_action( 'shoestrap_do_navbar' ); ?>

<?php
	} else {
		do_action( 'shoestrap_header_top_navbar_override' );
	}
} else {
	return '';
}
