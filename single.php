<?php get_header(); ?>
	<?php roots_content_before(); ?>
		<div id="content" class="<?php echo $roots_options['container_class']; ?>">	
		<?php roots_main_before(); ?>
			<div id="main" class="<?php echo $roots_options['main_class']; ?>" role="main">
				<div class="container">
					<?php roots_loop_before(); ?>
					<?php get_template_part('loop', 'single'); ?>
					<?php roots_loop_after(); ?>
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