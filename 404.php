<?php get_header(); ?>
	<?php roots_content_before(); ?>
		<div id="content" class="<?php echo $roots_options['container_class']; ?>">
		<?php roots_main_before(); ?>
			<div id="main" role="main">
				<div class="container">
					<h1><?php _e('File Not Found', 'roots'); ?></h1>
					<div class="error">
						<p class="bottom"><?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'roots'); ?></p>
					</div>
					<p><?php _e('Please try the following:', 'roots'); ?></p>
					<ul> 
						<li><?php _e('Check your spelling', 'roots'); ?> </li>
						<li><?php printf(__('Return to the <a href="%s">home page</a>', 'roots'), home_url()); ?></li>
						<li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'roots'); ?></li>
					</ul>
				</div>
			</div><!-- /#main -->
		<?php roots_main_after(); ?>
		</div><!-- /#content -->
	<?php roots_content_after(); ?>
<?php get_footer(); ?>