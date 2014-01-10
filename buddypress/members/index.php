<?php do_action( 'bp_before_directory_members_page' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_members' ); ?>

	<?php do_action( 'bp_before_directory_members_content' ); ?>

	<div id="members-dir-search" class="dir-search" role="search">
		<?php bp_directory_members_search_form(); ?>
	</div><!-- #members-dir-search -->

	<?php do_action( 'bp_before_directory_members_tabs' ); ?>

	<form action="#" method="post" id="members-directory-form" class="dir-form">

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
			<?php bp_get_template_part( 'members/members-loop' ); ?>
		</div><!-- #members-dir-list -->

		<?php do_action( 'bp_directory_members_content' ); ?>

		<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

		<?php do_action( 'bp_after_directory_members_content' ); ?>

	</form><!-- #members-directory-form -->

	<?php do_action( 'bp_after_directory_members' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_members_page' ); ?>