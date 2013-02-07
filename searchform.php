<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
  <div class="input-append">
    <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
    <button type="submit" id="searchsubmit" value="<?php _e('Search', 'roots'); ?>" class="btn"><i class="icon-search"></i></button>
  </div>
</form>
