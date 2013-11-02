<?php

/**
 * Archive Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">
	<?php if ( bbp_allow_search() ) : ?>
		<div class="bbp-search-form">
			<?php bbp_get_template_part( 'form', 'search' ); ?>
		</div>
	<?php endif; ?>

	<hr />

	<?php do_action( 'bbp_template_before_forums_index' ); ?>

	<?php
		if ( bbp_has_forums() ) :
			bbp_get_template_part( 'loop',     'forums'    );
		else :
			bbp_get_template_part( 'feedback', 'no-forums' );
		endif;

		do_action( 'bbp_template_after_forums_index' );
	?>

</div>
<hr />