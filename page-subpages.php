<?php
/*
Template Name: List Subpages
*/
get_header(); ?>
		<div id="content" class="<?php echo roots_container_class; ?>">	
			<div id="main" class="<?php echo get_option('roots_main_class'); ?>" role="main">
				<div class="container">
					<?php get_template_part('loop', 'page'); ?>
					<?php
						$children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0');
						if ($children) { ?>
						<ul>
							<?php echo $children; ?>
						</ul>
					<?php } ?>
				</div>
			</div><!-- /#main -->
			<aside id="sidebar" class="<?php echo get_option('roots_sidebar_class'); ?>" role="complementary">
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
			</aside><!-- /#sidebar -->
		</div><!-- /#content -->
<?php get_footer(); ?>
