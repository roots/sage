<?php get_header( 'buddypress' ); ?>

	<?php roots_content_before(); ?>
    <div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>
        <div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">

		<?php do_action( 'bp_before_activation_page' ) ?>

		<div class="page" id="activate-page">

			<?php if ( bp_account_was_activated() ) : ?>

				<h2 class="widgettitle"><?php _e( 'Account Activated', 'buddypress' ) ?></h2>

				<?php do_action( 'bp_before_activate_content' ) ?>

				<?php if ( isset( $_GET['e'] ) ) : ?>
					<p><?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', 'buddypress' ) ?></p>
				<?php else : ?>
					<p><?php _e( 'Your account was activated successfully! You can now log in with the username and password you provided when you signed up.', 'buddypress' ) ?></p>
				<?php endif; ?>

			<?php else : ?>

				<h3><?php _e( 'Activate your Account', 'buddypress' ) ?></h3>

				<?php do_action( 'bp_before_activate_content' ) ?>

				<p><?php _e( 'Please provide a valid activation key.', 'buddypress' ) ?></p>

				<form action="" method="get" class="standard-form" id="activation-form">

					<label for="key"><?php _e( 'Activation Key:', 'buddypress' ) ?></label>
					<input type="text" name="key" id="key" value="" />

					<p class="submit">
						<input type="submit" name="submit" value="<?php _e( 'Activate', 'buddypress' ) ?>" />
					</p>

				</form>

			<?php endif; ?>

			<?php do_action( 'bp_after_activate_content' ) ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_activation_page' ) ?>

		</div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
      <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <?php get_sidebar( 'buddypress' ); ?>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>

<?php get_footer( 'buddypress' ); ?>
