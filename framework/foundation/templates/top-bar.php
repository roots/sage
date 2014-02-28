<?php global $ss_settings; ?>
<div class="navbar <?php echo apply_filters( 'shoestrap_navbar_class', '' ); ?>">
	<nav class="top-bar" data-topbar data-options="mobile_show_parent_link: true">
		
		<ul class="title-area">
			<?php if ( ! is_null( $ss_settings['nav_brand'] ) & $ss_settings['nav_brand'] == 1 ) : ?>
				<li class="name">
					<h1><?php echo apply_filters( 'shoestrap_navbar_brand', '<a class="text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?></h1>
				</li>
			<?php endif; ?>
			<?php echo apply_filters( 'shoestrap_nav_toggler', '<li class="toggle-topbar menu-icon"><a href="#">' . __( 'Menu', 'shoestrap' ) . '</a></li>' ); ?>
		</ul>

		<?php if ( has_action( 'shoestrap_pre_main_nav' ) ) : ?>
			<div class="nav-extras">
				<?php do_action( 'shoestrap_pre_main_nav' ); ?>
			</div>
		<?php endif; ?>

		<section class="top-bar-section">
		<?php
			do_action( 'shoestrap_inside_nav_begin' );
			if ( has_nav_menu( 'primary_navigation' ) )
				wp_nav_menu( array( 
					'theme_location' => 'primary_navigation', 
					'menu_class' => apply_filters( 'shoestrap_nav_class', 'left' )
				) );
			do_action( 'shoestrap_inside_nav_end' );
		?>
		</section>

		<?php do_action( 'shoestrap_post_main_nav' ); ?>

	</nav>
</div>