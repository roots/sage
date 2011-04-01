<?php get_header(); ?>
		<div id="content" class="<?php echo roots_container_class; ?>">	
			<div id="main" class="<?php echo get_option('roots_main_class'); ?>" role="main">
				<div class="container">
					<?php get_template_part('loop', 'single'); ?>		
				</div>	
			</div><!-- /#main -->
			<aside id="sidebar" class="<?php echo get_option('roots_sidebar_class'); ?>" role="complementary">
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
			</aside><!-- /#sidebar -->			
		</div><!-- /#content -->
<?php get_footer(); ?>
