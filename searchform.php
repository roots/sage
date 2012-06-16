<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
  <input type="text" value="" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
  <input type="submit" id="searchsubmit" value="<?php _e('Search', 'roots'); ?>" class="btn">
</form>