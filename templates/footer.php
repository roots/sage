<?php global $ss_framework; ?>
<footer id="page-footer" class="content-info" role="contentinfo">
	<?php echo $ss_framework->make_container( 'div' ); ?>
		<?php echo $ss_framework->make_row( 'div' ); ?>
			<?php do_action( 'shoestrap_footer_html' ); ?>
		</div>
	</div>
</footer>