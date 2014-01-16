<?php

/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

global $bp;

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary">

	<?php do_action( 'bp_before_activity_post_form' ); ?>

	<?php if ( bp_is_group() ) {
		$whats_new_text = sprintf( __( "What's new in %s, %s?", 'buddypress' ), bp_get_group_name(), bp_get_user_firstname() );
	} else {
		$whats_new_text = sprintf( __( "What's new, %s?", 'buddypress' ), bp_get_user_firstname() );
	} ?>

	<div id="whats-new-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php bp_loggedin_user_avatar( 'width=150px&height=150px'); ?>
		</a>
	</div>

	<div id="whats-new-content">

		<div class="whats-new-row">
			<div id="whats-new-textarea">
				<div class="form-group">
					<label for="whats-new"><?php print $whats_new_text ?></label>
					<textarea name="whats-new" id="whats-new" cols="50" rows="10" placeholder="Use @username to mention a user…"><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_attr( $_GET['r'] ); ?> <?php endif; ?></textarea>
				</div>
			</div>
		</div>

		<div class="whats-new-row" id="whats-new-options">

			<?php if ( bp_is_active( 'groups' ) ) {
				echo '<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />';

				$group_ids = groups_get_user_groups( get_current_user_id() );
				$select_disabled = "";
				$option_arr = array();				
				
				foreach ( $group_ids['groups'] as $group_id ) {
					$group = groups_get_group( array( 'group_id' => $group_id ) );
					$selected = ( bp_is_group_home() && $group->id == $bp->groups->current_group->id ) ? "selected" : "";
					$option_arr[] = sprintf('<option value="%d" %s>%s</option>', $group->id, $selected, $group->name);
				}

				if ( bp_is_group_home() ) {
					$select_disabled = "disabled";
				}

			} else {

				$select_disabled = "disabled";
				$option_arr = array();

			} ?>

			<div id="whats-new-post-in-box">

				<div class="form-group">
					<label for="whats-new-post-in"><?php _e('Post in') ?>:</label>
					<select id="whats-new-post-in" name="whats-new-post-in" <?php echo $select_disabled ?>>
						<option <?php echo ( bp_is_my_profile() ) ? "selected" : "" ?> value="0"><?php _e( 'My Profile', 'buddypress' ); ?></option>
						<?php foreach ($option_arr as $option) { echo $option; } ?>
					</select>

				</div>

			</div>



			<div id="whats-new-submit">
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php _e( 'Post Update', 'buddypress' ); ?>" />
			</div>
	
			<?php do_action( 'bp_activity_post_form_options' ); ?>
	
		</div><!-- #whats-new-options -->

	</div><!-- #whats-new-content -->

<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
<?php do_action( 'bp_after_activity_post_form' ); ?>

</form><!-- #whats-new-form -->
