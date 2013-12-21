<?php do_action( 'bp_before_member_settings_template' ); ?>

<div id="message" class="info">

	<?php if ( bp_is_my_profile() ) : ?>

		<p><?php _e( 'Deleting your account will delete all of the content you have created. It will be completely irrecoverable.', 'buddypress' ); ?></p>

	<?php else : ?>

		<p><?php _e( 'Deleting this account will delete all of the content it has created. It will be completely irrecoverable.', 'buddypress' ); ?></p>

	<?php endif; ?>

</div>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/delete-account'; ?>" name="account-delete-form" id="account-delete-form" class="standard-form" method="post">

	<?php do_action( 'bp_members_delete_account_before_submit' ); ?>

	<label>
		<input type="checkbox" name="delete-account-understand" id="delete-account-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-account-button').disabled = ''; } else { document.getElementById('delete-account-button').disabled = 'disabled'; }" />
		 <?php _e( 'I understand the consequences.', 'buddypress' ); ?>
	</label>

	<div class="submit">
		<input type="submit" disabled="disabled" value="<?php _e( 'Delete Account', 'buddypress' ); ?>" id="delete-account-button" name="delete-account-button" />
	</div>

	<?php do_action( 'bp_members_delete_account_after_submit' ); ?>

	<?php wp_nonce_field( 'delete-account' ); ?>

</form>

<?php do_action( 'bp_after_member_settings_template' ); ?>
