<?php do_action( 'bp_before_member_settings_template' ); ?>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/'; ?>" name="account-capabilities-form" id="account-capabilities-form" class="standard-form" method="post">

	<?php do_action( 'bp_members_capabilities_account_before_submit' ); ?>

	<label>
		<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
		 <?php _e( 'This user is a spammer.', 'buddypress' ); ?>
	</label>

	<div class="submit">
		<input type="submit" value="<?php _e( 'Save', 'buddypress' ); ?>" id="capabilities-submit" name="capabilities-submit" />
	</div>

	<?php do_action( 'bp_members_capabilities_account_after_submit' ); ?>

	<?php wp_nonce_field( 'capabilities' ); ?>

</form>

<?php do_action( 'bp_after_member_settings_template' ); ?>
