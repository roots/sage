<?php if (shopp('cart','hasitems')): ?>
<form id="cart" action="<?php shopp('cart','url'); ?>" method="post">
<big>
	<a href="<?php shopp('cart','referrer'); ?>">&laquo; Continue viewing products</a>
	<a href="<?php shopp('checkout','url'); ?>" class="right">Proceed to Request Quote &raquo;</a>
</big>

<?php shopp('cart','function'); ?>
<table class="cart">
	<tr>
		<th scope="col" class="item">Project Items</th>
		<th scope="col">Quantity</th>
		<th scope="col"></th>
		<th scope="col"></th>
	</tr>

	<?php while(shopp('cart','items')): ?>
		<tr>
			<td>
				<a href="<?php shopp('cartitem','url'); ?>"><?php shopp('cartitem','name'); ?></a>
				<?php shopp('cartitem','options'); ?>
				<?php shopp('cartitem','addons-list'); ?>
				<?php shopp('cartitem','inputs-list'); ?>
			</td>
			<td><?php shopp('cartitem','quantity','input=text'); ?>
				<?php shopp('cartitem','remove','input=button'); ?></td>
			<td></td>
			<td></td>
		</tr>
	<?php endwhile; ?>

	<tr class="buttons">
		<td colspan="4"><?php shopp('cart','update-button'); ?></td>
	</tr>
</table>

<big>
	<a href="<?php shopp('cart','referrer'); ?>">&laquo; Continue viewing products</a>
	<a href="<?php shopp('checkout','url'); ?>" class="right">Proceed to Request Quote &raquo;</a>
</big>

</form>

<?php else: ?>
	<p class="warning">There are no items in your current project.</p>
	<p><a href="<?php shopp('catalog','url'); ?>">&laquo; Continue viewing products</a></p>
<?php endif; ?>
