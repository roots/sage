	<?php roots_footer_before(); ?>
		<footer id="content-info" class="<?php echo roots_container_class; ?>" role="contentinfo">
			<?php roots_footer_inside(); ?>
			<div class="container">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer") ) : ?>
				<?php endif; ?>
				<p class="copy"><small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small></p>
			</div>	
		</footer>
		<?php roots_footer_after(); ?>	
	</div><!-- /#wrap -->
<?php wp_footer(); ?>
<?php roots_footer(); ?>
</body>
</html>