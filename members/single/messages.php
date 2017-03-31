<?php

/**
 * BuddyPress - Users Messages
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>

		<?php bp_get_options_nav(); ?>

	</ul>
</div><!-- .item-list-tabs -->

<?php

	if ( bp_is_current_action( 'compose' ) ) :
		locate_template( array( 'members/single/messages/compose.php' ), true );

	elseif ( bp_is_current_action( 'view' ) ) :
		locate_template( array( 'members/single/messages/single.php' ), true );

	else :
		do_action( 'bp_before_member_messages_content' ); ?>

	<div class="messages" role="main">

		<?php
			if ( bp_is_current_action( 'notices' ) )
				locate_template( array( 'members/single/messages/notices-loop.php' ), true );
			else
				locate_template( array( 'members/single/messages/messages-loop.php' ), true );
		?>

	</div><!-- .messages -->

	<?php do_action( 'bp_after_member_messages_content' ); ?>

<?php endif; ?>
