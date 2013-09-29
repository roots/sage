<ul class="shopp account">
<?php while (shopp('storefront','account-menu')): ?>
	<li>
		<a href="<?php shopp('storefront','account-menuitem','url'); ?>"><?php shopp('storefront','account-menuitem'); ?></a>
	</li>
<?php endwhile; ?>
</ul>
