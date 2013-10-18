<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

do_action( 'bbp_template_before_pagination_loop' );
?>

<div class="bbp-pagination">
	<div class="row">
		<div class="bbp-pagination-count col-md-8">
			<?php bbp_topic_pagination_count(); ?>
		</div>
		<div class="bbp-pagination-links col-md-4 text-right">
			<?php bbp_topic_pagination_links(); ?>
		</div>
	</div>
</div>
<hr />
<?php
do_action( 'bbp_template_after_pagination_loop' );