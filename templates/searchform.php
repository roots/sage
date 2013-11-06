<?php
if ( !has_action( 'shoestrap_searchform_override' ) ) : ?>

<form role="search" method="get" id="searchform" class="form-inline" action="<?php echo home_url('/'); ?>">
  <input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
  <label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
  <button type="submit" id="searchsubmit" class="btn btn-default"><i class="el-icon-search"></i></button>
</form>

<?php
else:
  do_action( 'shoestrap_searchform_override' );
endif;
