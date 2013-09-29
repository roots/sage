<h3>Downloads</h3>

<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>
<?php if (shopp('customer','has-downloads')): ?>
<table cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th scope="col">Product</th>
			<th scope="col">Order</th>
			<th scope="col">Amount</th>
		</tr>
	</thead>
	<?php while(shopp('customer','downloads')): ?>
	<tr>
		<td><?php shopp('customer','download','name'); ?> <?php shopp('customer','download','variation'); ?><br />
			<small><a href="<?php shopp('customer','download','url'); ?>">Download File</a> (<?php shopp('customer','download','size'); ?>)</small></td>
		<td><?php shopp('customer','download','purchase'); ?><br />
			<small><?php shopp('customer','download','date'); ?></small></td>
		<td><?php shopp('customer','download','total'); ?><br />
			<small><?php shopp('customer','download','downloads'); ?> Downloads</small></td>
	</tr>
	<?php endwhile; ?>
</table>
<?php else: ?>
<p>You have no digital product downloads available.</p>
<?php endif; // end 'has-downloads' ?>
<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>

