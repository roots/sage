<form role="search" method="get" id="searchform" class="form-inline" action="<?php echo home_url('/'); ?>">
  <div class="input-group">
    <input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
    <label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
    <span class="input-group-btn">
      <button type="submit" id="searchsubmit" class="btn"><?php _e('Search', 'roots'); ?></button>
    </span>
  </div>
</form>