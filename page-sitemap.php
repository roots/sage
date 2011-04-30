<?php
/*
Template Name: Sitemap
*/
get_header(); ?>
		<div id="content" class="<?php echo roots_container_class; ?>">	
			<div id="main" class="<?php echo of_get_option('roots_main_class'); ?>" role="main">
				<div class="container">
					<?php get_template_part('loop', 'page'); ?>
					<h2>Pages</h2>
					<ul><?php wp_list_pages('sort_column=menu_order&depth=0&title_li='); ?></ul>
					<h2>Posts</h2>
					<ul><?php wp_list_categories('title_li=&hierarchical=0&show_count=1'); ?></ul>
					<h2>Archives</h2>
					<ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul>
				</div>
			</div><!-- /#main -->
			<aside id="sidebar" class="<?php echo of_get_option('roots_sidebar_class'); ?>" role="complementary">
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
			</aside><!-- /#sidebar -->
		</div><!-- /#content -->
<?php get_footer(); ?>
