<div id="receipt" class="shopp">
<table class="transaction">
	<tr><th>Project Num:</th><td><?php shopp('purchase','id'); ?></td></tr>
	<tr><th>Submission Date:</th><td><?php shopp('purchase','date'); ?></td></tr>
	<tr><th>Customer Email:</th><td><?php shopp('purchase','email'); ?></td></tr>
	<tr><th>Customer Phone:</th><td><?php shopp('purchase','phone'); ?></td></tr>
	<tr><th></th><td><?php shopp('purchase','phone'); ?></td></tr>
	<tr><th></th><td><?php shopp('purchase','phone'); ?></td></tr>
	<tr><th></th><td></td></tr>
	<tr><th></th><td><?php shopp('purchase','address'); ?><br/><?php shopp('purchase','city'); ?>, <?php shopp('purchase','state'); ?> <?php shopp('purchase','postcode'); ?><br/><?php shopp('purchase','country'); ?></td></tr>
	
</table>

<?php if (shopp('purchase','hasitems')): ?>
<table class="order widefat">
	<thead>
	<tr>
	 <th scope="col" class="images">Images</th>
   <th scope="col" class="items">Project Items</th>
   <th scope="col" class="quantity">Quantity</th>
   <th scope="col" class="actions">Actions</th>
	</tr>
	</thead>
  <tbody>
	<?php while(shopp('purchase','items')): ?>
		<tr>
			<td class=""></td>
			<td><?php shopp('purchase','item-name'); ?><?php shopp('purchase','item-options','before= – '); ?><br />
				<?php shopp('purchase','item-sku')."<br />"; ?>
				<?php shopp('purchase','item-download'); ?>
				<?php shopp('purchase','item-addons-list'); ?>
				</td>
			<td><?php shopp('purchase','item-quantity'); ?></td>
			<td class=""></td>
		</tr>
		
	<?php endwhile; ?>
  </tbody>
  <tfoot>
    <tr><td><strong><?php shopp('purchase','total-items'); ?></strong> Total Items in this project</td></tr>
  </tfoot>
</table>

<?php if(shopp('purchase','has-data')): ?>
	<ul>
	<?php while(shopp('purchase','orderdata')): ?>
		<?php if (shopp('purchase','get-data') == '') continue; ?>
		<li><strong><?php shopp('purchase','data','name'); ?>:</strong> <?php shopp('purchase','data'); ?></li>
	<?php endwhile; ?>
	</ul>
<?php endif; ?>


<?php else: ?>
	<p class="warning">There were no items found for this purchase.</p>
<?php endif; ?>
</div>
