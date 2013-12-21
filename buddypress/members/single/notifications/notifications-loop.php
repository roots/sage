<table class="notifications">
	<thead>
		<tr>
			<th class="icon"></th>
			<th class="title"><?php _e( 'Notification', 'buddypress' ); ?></th>
			<th class="date"><?php _e( 'Date Received', 'buddypress' ); ?></th>
			<th class="actions"><?php _e( 'Actions',    'buddypress' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

			<tr>
				<td></td>
				<td><?php bp_the_notification_description();  ?></td>
				<td><?php bp_the_notification_time_since();   ?></td>
				<td><?php bp_the_notification_action_links(); ?></td>
			</tr>

		<?php endwhile; ?>

	</tbody>
</table>