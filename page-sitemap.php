<?php
/*
Template Name: Sitemap
*/
get_header(); ?>
	<?php roots_content_before(); ?>
		<div id="content" class="<?php echo $roots_options['container_class']; ?>">	
		<?php roots_main_before(); ?>
			<div id="main" class="<?php echo $roots_options['main_class']; ?>" role="main">
				<div class="container">
					<?php roots_loop_before(); ?>
					<?php get_template_part('loop', 'page'); ?>
					<?php roots_loop_after(); ?>
					<h2><?php _e('Pages', 'roots'); ?></h2>
					<ul><?php wp_list_pages('sort_column=menu_order&depth=0&title_li='); ?></ul>
					<h2><?php _e('Posts', 'roots'); ?></h2>
					<ul><?php wp_list_categories('title_li=&hierarchical=0&show_count=1'); ?></ul>
					<h2><?php _e('Archives', 'roots'); ?></h2>
					<ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul>
				</div>
			</div><!-- /#main -->
		<?php roots_main_after(); ?>
		<?php roots_sidebar_before(); ?>			
			<aside id="sidebar" class="<?php echo $roots_options['sidebar_class']; ?>" role="complementary">
			<?php roots_sidebar_inside_before(); ?>
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
			<?php roots_sidebar_inside_after(); ?>
			</aside><!-- /#sidebar -->		
		<?php roots_sidebar_after(); ?>
		</div><!-- /#content -->
	<?php roots_content_after(); ?>
<?php get_footer(); ?>