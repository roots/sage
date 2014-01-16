<?php do_action( 'bp_before_directory_blogs' ); ?>

<div id="buddypress">

	<h3><?php _e( 'Blogs Directory', 'buddypress' ); ?><?php if ( is_user_logged_in() && bp_blog_signup_enabled() ) : ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . bp_get_blogs_root_slug() . '/create/' ?>"><?php _e( 'Create a Site', 'buddypress' ); ?></a><?php endif; ?></h3>

	<div id="blog-dir-search" class="dir-search" role="search">

		<?php bp_directory_blogs_search_form(); ?>

	</div><!-- #blog-dir-search -->
	
	<form action="" method="post" id="blogs-directory-form" class="dir-form">

		<?php do_action( 'bp_before_directory_blogs_content' ); ?>

		<div id="blogs-dir-list" class="blogs dir-list">

			<?php bp_get_template_part( 'blogs/blogs-loop' ); ?>

		</div><!-- #blogs-dir-list -->

		<?php do_action( 'bp_directory_blogs_content' ); ?>

		<?php wp_nonce_field( 'directory_blogs', '_wpnonce-blogs-filter' ); ?>

		<?php do_action( 'bp_after_directory_blogs_content' ); ?>

	</form><!-- #blogs-directory-form -->

	<?php do_action( 'bp_after_directory_blogs' ); ?>

</div>