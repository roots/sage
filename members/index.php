<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_members_page' ); ?>

	<?php roots_content_before(); ?>
    <div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>
        <div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">

		<?php do_action( 'bp_before_directory_members' ); ?>

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<h3><?php _e( 'Members Directory', 'buddypress' ); ?></h3>

			<?php do_action( 'bp_before_directory_members_content' ); ?>

			<div id="members-dir-search" class="dir-search" role="search">

				<?php bp_directory_members_search_form(); ?>

			</div><!-- #members-dir-search -->

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_member_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

					<?php do_action( 'bp_members_directory_member_types' ); ?>

				</ul>
			</div><!-- .item-list-tabs -->

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

					<li id="members-order-select" class="last filter">

						<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

							<?php endif; ?>

							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>

			<div id="members-dir-list" class="members dir-list">

				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			</div><!-- #members-dir-list -->

			<?php do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php do_action( 'bp_after_directory_members_content' ); ?>

		</form><!-- #members-directory-form -->

		<?php do_action( 'bp_after_directory_members' ); ?>

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

	<?php do_action( 'bp_after_directory_members_page' ); ?>


<?php get_footer( 'buddypress' ); ?>
