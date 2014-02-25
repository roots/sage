<?php global $ss_framework; ?>
<footer id="page-footer" class="content-info" role="contentinfo">
	<div class="<?php echo apply_filters( 'shoestrap_container_class', 'container' ); ?>">
		<?php echo $ss_framework->makerow( 'div' ); ?>
			<?php shoestrap_footer_content(); ?>
		</div>
	</div>
</footer>