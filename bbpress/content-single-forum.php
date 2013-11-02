<?php

/**
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">
	<?php
		do_action( 'bbp_template_before_single_forum' );

		if ( post_password_required() ) :
			bbp_get_template_part( 'form', 'protected' );
		else :
			bbp_single_forum_description( array( 'before' => '<div class="alert alert-info">', 'after' => '</div>' ) );

			if ( bbp_has_forums() ) :
				bbp_get_template_part( 'loop', 'forums' );
			endif;

			if ( !bbp_is_forum_category() && bbp_has_topics() ) :
				bbp_get_template_part( 'pagination', 'topics'    );
				bbp_get_template_part( 'loop',       'topics'    );
				bbp_get_template_part( 'pagination', 'topics'    );
				bbp_get_template_part( 'form',       'topic'     );
			elseif ( !bbp_is_forum_category() ) :
				bbp_get_template_part( 'feedback',   'no-topics' );
				bbp_get_template_part( 'form',       'topic'     );
			endif;
		endif;

		do_action( 'bbp_template_after_single_forum' );
	?>
</div>
<hr />