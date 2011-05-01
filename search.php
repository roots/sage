<?php get_header(); ?>
		<div id="content" class="<?php echo roots_container_class; ?>">	
			<div id="main" class="<?php echo get_option('roots_main_class'); ?>">
				<div class="container">
					<h1><?php _e('Search Results for', 'roots'); ?> <?php echo get_search_query(); ?></h1>				
					<?php get_template_part('loop', 'search'); ?>
				</div>
			</div><!-- /#main -->
			<aside id="sidebar" class="<?php echo get_option('roots_sidebar_class'); ?>" role="complementary">
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
			</aside><!-- /#sidebar -->
		</div><!-- /#content -->
<?php get_footer(); ?>