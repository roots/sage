<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<label class="visuallyhidden" for="s"><?php _e('Search for:', 'roots'); ?></label>
	<input type="text" value="" name="s" id="s" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
	<input type="submit" id="searchsubmit" value="<?php _e('Search', 'roots'); ?>" class="button">
</form>