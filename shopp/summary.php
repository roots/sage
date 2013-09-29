<?php if (shopp('cart','hasitems')): ?>
<div id="cart" class="shopp">
<table class="table ">
  <thead>
	<tr>
	 <th scope="col" class="images">Images</th>
   <th scope="col" class="items">Project Items</th>
   <th scope="col" class="quantity">Quantity</th>
   <th scope="col" class="actions">Actions</th>
	</tr>
  </thead>
  <tbody>
	<?php while(shopp('cart','items')): ?>
		<tr>
		  <td class="images"><a href="<?php shopp('cartitem','url'); ?>" title="<?php shopp('cartitem','name'); ?>"><?php shopp('cartitem','coverimage','size=64'); ?></a></td>
			<td class="items">
				<a href="<?php shopp('cartitem','url'); ?>"><?php shopp('cartitem','name'); ?></a>
				<?php shopp('cartitem','options','show=selected&before= (&after=)'); ?>
				<?php shopp('cartitem','inputs-list'); ?>
				<?php shopp('cartitem','addons-list'); ?>
			</td>
			<td class="quantity"><?php shopp('cartitem','quantity'); ?></td>
			<td class="actions"><?php shopp('cartitem','remove','label=Remove Item&input=button'); ?></td>
		</tr>
	<?php endwhile; ?>
  </tbody>
</table>

<?php if(shopp('checkout','hasdata')): ?>
	<ul>
	<?php while(shopp('checkout','orderdata')): ?>
		<li><strong><?php shopp('checkout','data','name'); ?>:</strong> <?php shopp('checkout','data'); ?></li>
	<?php endwhile; ?>
	</ul>
<?php endif; ?>

</div>
<?php else: ?>
	<p class="warning">There are no items in your current project.</p>
	<p><a href="<?php shopp('catalog','url'); ?>">&laquo; Back to Unistrut Products</a></p>
<?php endif; ?>
