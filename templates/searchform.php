<form role="search" method="get" class="search-form form-inline" action="<?php echo home_url('/'); ?>">
  <div class="row">
    <div class="small-12 medium-6 columns">
		<div class="row collapse">
			<div class="small-8  columns">
				<input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
			</div>
			<div class="small-4 columns">
				<button type="submit" class="button postfix"><?php _e('Search', 'roots'); ?></button>
			</div>
		</div>
    </div>
  </div>
</form>