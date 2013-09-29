<?php if (shopp('product','found')): ?>
	<div class="sideproduct">
	<a href="<?php shopp('product','url'); ?>"><?php shopp('product','coverimage','setting=thumbnails'); ?></a>

	<h3><a href="<?php shopp('product','url'); ?>"><?php shopp('product','name'); ?></a></h3>

	</div>
<?php endif; ?>