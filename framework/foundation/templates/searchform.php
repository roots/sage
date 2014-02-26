<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="row collapse">
		<div class="small-9 columns">
			<label class="sr-only" for="s"><?php _e( 'Search for:', 'shoestrap' ); ?></label>
			<input type="search" value="<?php if ( is_search() ) { echo get_search_query(); } ?>" name="s" id="s" placeholder="<?php _e( 'Search', 'shoestrap' ); ?>">
		</div>
		<div class="small-3 columns">
			<button type="submit" id="searchsubmit" class="postfix"><i class="el-icon-search"></i></button>
		</div>
	</div>
</form>