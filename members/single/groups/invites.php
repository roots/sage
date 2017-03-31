<?php do_action( 'bp_before_group_invites_content' ) ?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>

	<ul id="group-list" class="invites item-list" role="main">

		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<li>
				<div class="item-avatar">
					<a href="<?php bp_group_permalink() ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ) ?></a>
				</div>

				<h4><a href="<?php bp_group_permalink() ?>"><?php bp_group_name() ?></a><span class="small"> - <?php printf( __( '%s members', 'buddypress' ), bp_group_total_members( false ) ) ?></span></h4>

				<p class="desc">
					<?php bp_group_description_excerpt() ?>
				</p>

				<?php do_action( 'bp_group_invites_item' ) ?>

				<div class="action">
					<a class="button accept" href="<?php bp_group_accept_invite_link() ?>"><?php _e( 'Accept', 'buddypress' ) ?></a> &nbsp;
					<a class="button reject confirm" href="<?php bp_group_reject_invite_link() ?>"><?php _e( 'Reject', 'buddypress' ) ?></a>

					<?php do_action( 'bp_group_invites_item_action' ) ?>

				</div>
			</li>

		<?php endwhile; ?>
	</ul>

<?php else: ?>

	<div id="message" class="info" role="main">
		<p><?php _e( 'You have no outstanding group invites.', 'buddypress' ) ?></p>
	</div>

<?php endif;?>

<?php do_action( 'bp_after_group_invites_content' ) ?>