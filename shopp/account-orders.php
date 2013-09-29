<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>

<?php if (shopp('purchase','get-id')): ?>
	<?php shopp('purchase','receipt'); ?>
<?php return; endif; ?>

<form action="<?php shopp('customer','action'); ?>" method="post" class="shopp validate" autocomplete="off">

<?php if (shopp('customer','has-purchases')): ?>
	<table cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th scope="col">Date</th>
				<th scope="col">Order</th>
				<th scope="col">Status</th>
				<th scope="col">Total</th>
			</tr>
		</thead>
		<?php while(shopp('customer','purchases')): ?>
		<tr>
			<td><?php shopp('purchase','date'); ?></td>
			<td><?php shopp('purchase','id'); ?></td>
			<td><?php shopp('purchase','status'); ?></td>
			<td><?php shopp('purchase','total'); ?></td>
			<td><a href="<?php shopp('customer','order'); ?>">View Order</a></td>
		</tr>
		<?php endwhile; ?>
	</table>
<?php else: ?>
<p>You have no orders, yet.</p>
<?php endif; // end 'has-purchases' ?>

</form>

<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>
