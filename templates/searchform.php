<form role="search" method="get" id="searchform" class="form-search input-group" action="<?php echo home_url('/'); ?>">
  <label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
  <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
  <span class="input-group-btn">
  	<input type="submit" id="searchsubmit" value="<?php _e('Search', 'roots'); ?>" class="btn btn-info">
  </span>
</form>