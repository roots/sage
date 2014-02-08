<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

	<!--[if lt IE 8]>
		<div class="alert alert-warning">
			<?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'shoestrap'); ?>
		</div>
	<![endif]-->

	<?php do_action( 'get_header' ); ?>
	<?php
	if ( !has_action( 'shoestrap_do_navbar' ) )
		get_template_part( 'templates/header-top-navbar' );
	else
		do_action( 'shoestrap_do_navbar' );
	?>
	<?php do_action( 'shoestrap_pre_wrap' ); ?>

	<div class="wrap main-section <?php echo apply_filters( 'shoestrap_container_class', 'container' ); ?>" role="document">

		<?php do_action('shoestrap_pre_content'); ?>

		<div class="content">
			<div class="row bg">

				<?php do_action( 'shoestrap_pre_main' ); ?>

				<main class="main <?php shoestrap_section_class( 'main', true ); ?>" <?php if (is_home()){ echo 'id="home-blog"';} ?> role="main">
					<?php include shoestrap_template_path(); ?>
				</main><!-- /.main -->

				<?php do_action('shoestrap_after_main'); ?>

				<?php if ( shoestrap_display_primary_sidebar() ) : ?>
					<aside class="sidebar <?php shoestrap_section_class( 'primary', true ); ?>" role="complementary">
						<?php if ( !has_action( 'shoestrap_sidebar_override' ) )
							include shoestrap_sidebar_path();
						else
							do_action( 'shoestrap_sidebar_override' ); ?>
					</aside><!-- /.sidebar -->
				<?php endif; ?>

				<?php do_action( 'shoestrap_post_main' ); ?>

				<?php if ( shoestrap_display_secondary_sidebar() ) : ?>
					<aside class="sidebar secondary <?php shoestrap_section_class( 'secondary', true ); ?>" role="complementary">
						<?php dynamic_sidebar( 'sidebar-secondary' ); ?>
					</aside><!-- /.sidebar -->
				<?php endif; ?>
			</div>
		</div><!-- /.content -->
		<?php do_action('shoestrap_after_content'); ?>
	</div><!-- /.wrap -->
	<?php

	do_action('shoestrap_pre_footer');

	if ( !has_action( 'shoestrap_footer_override' ) )
		get_template_part( 'templates/footer' );
	else
		do_action( 'shoestrap_footer_override' );

	do_action( 'shoestrap_after_footer' );

	wp_footer();

	?>
</body>
</html>