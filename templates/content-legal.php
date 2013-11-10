<?php if( get_field('legal_text', 'options') ) { ?>
<div class="well legal-text">
	<?php while( has_sub_field('legal', 'options') ) { ?>
		<p class="legal-para"><?php echo the_sub_field('legal', 'options'); ?></p>
		<p class="legal-para"><?php echo the_field('legal', 'options'); ?></p>
	<?php } ?>
</div>
<?php } ?>
