<?php do_action('bc_core_pre_searchform'); ?>
<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
  <label class="hide" for="s"><?php _e('Search for:', 'bc_core'); ?></label>
  <input type="text" value="" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'bc_core'); ?> <?php bloginfo('name'); ?>">
  <input type="submit" id="searchsubmit" value="<?php _e('Search', 'bc_core'); ?>" class="btn">
</form>
<?php do_action('bc_core_after_searchform'); ?>