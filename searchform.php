<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
  <label class="hide-text" for="s"><?php _e('Search for:', 'roots'); ?></label>
  <input type="text" value="" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
  <input type="submit" id="searchsubmit" value="<?php _e('Search', 'roots'); ?>" class="btn">
</form>