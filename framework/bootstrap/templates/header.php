<header id="banner-header" class="banner <?php echo apply_filters( 'shoestrap_navbar_class', 'navbar navbar-default' ); ?>" role="banner">
	<div class="container">
	<?php echo apply_filters( 'shoestrap_navbar_brand', '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?>
		<nav class="nav-main" role="navigation">
			<?php
				if ( has_nav_menu( 'primary_navigation' ) ) :
					wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => apply_filters( 'shoestrap_nav_class', 'nav nav-pills' ) ) );
				endif;
			?>
		</nav>
	</div>
</header>