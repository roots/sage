<<<<<<< HEAD
<form role="search" method="get" class="search-form form-inline" action="<?php echo home_url('/'); ?>">
  <div class="input-group">
    <input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" class="search-field form-control" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
    <label class="hide"><?php _e('Search for:', 'roots'); ?></label>
    <span class="input-group-btn">
      <button type="submit" class="search-submit btn btn-default"><?php _e('Search', 'roots'); ?></button>
    </span>
  </div>
</form>
=======
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
<label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
<input type="text" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>" <?php if(is_search()) { ?>value="<?php the_search_query(); ?>" <?php } else { ?>value="Enter keywords &hellip;" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"<?php } ?> /><br />
	
<?php $query_types = get_query_var('post_type'); ?>
    
<input type="checkbox" name="post_type[]" value="post" <?php if (in_array('post', $query_types)) { echo 'checked="checked"'; } ?> /><label>Blog Posts</label>
<input type="checkbox" name="post_type[]" value="brand" <?php if (in_array('brand', $query_types)) { echo 'checked="checked"'; } ?> /><label>Brands</label>
<input type="checkbox" name="post_type[]" value="product" <?php if (in_array('product', $query_types)) { echo 'checked="checked"'; } ?> /><label>Products</label>
<input type="checkbox" name="post_type[]" value="resource" <?php if (in_array('resource', $query_types)) { echo 'checked="checked"'; } ?> /><label>Resources</label>
    
<input type="submit" id="searchsubmit" value="Search" />
</form>
>>>>>>> master
