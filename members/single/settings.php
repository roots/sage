<?php

/**
 * BuddyPress - Users Settings
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php if ( bp_is_my_profile() ) : ?>
		
			<?php bp_get_options_nav(); ?>
		
		<?php endif; ?>
	</ul>
</div>

<?php

if ( bp_is_current_action( 'notifications' ) ) :
	 locate_template( array( 'members/single/settings/notifications.php' ), true );

elseif ( bp_is_current_action( 'delete-account' ) ) :
	 locate_template( array( 'members/single/settings/delete-account.php' ), true );

elseif ( bp_is_current_action( 'general' ) ) :
	locate_template( array( 'members/single/settings/general.php' ), true );

else :
	locate_template( array( 'members/single/plugins.php' ), true );

endif;

?>
