<?php

/**
 * Search Content Part
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
	<?php
		bbp_set_query_name( 'bbp_search' );
		do_action( 'bbp_template_before_search' );

		if ( bbp_has_search_results() ) :
			bbp_get_template_part( 'pagination', 'search' );
			echo '<hr />';
			bbp_get_template_part( 'loop',       'search' );
			echo '<hr />';
			bbp_get_template_part( 'pagination', 'search' );
		elseif ( bbp_get_search_terms() ) :
			bbp_get_template_part( 'feedback',   'no-search' );
		else :
			bbp_get_template_part( 'form', 'search' );
		endif;

		do_action( 'bbp_template_after_search_results' );
	?>
</div>
<hr />