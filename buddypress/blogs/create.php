<?php do_action( 'bp_before_create_blog_content_template' ); ?>

<?php do_action( 'template_notices' ); ?>

<?php do_action( 'bp_before_create_blog_content' ); ?>

<?php if ( bp_blog_signup_enabled() ) : ?>

	<?php bp_show_blog_signup_form(); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Site registration is currently disabled', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_create_blog_content' ); ?>

<?php do_action( 'bp_after_create_blog_content_template' ); ?>