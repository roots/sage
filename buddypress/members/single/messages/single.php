<div id="message-thread" role="main">

	<?php do_action( 'bp_before_message_thread_content' ); ?>

	<?php if ( bp_thread_has_messages() ) : ?>

		<h3 id="message-subject"><?php bp_the_thread_subject(); ?></h3>

		<p id="message-recipients">
			<span class="highlight">

				<?php if ( !bp_get_the_thread_recipients() ) : ?>

					<?php _e( 'You are alone in this conversation.', 'buddypress' ); ?>

				<?php else : ?>

					<?php printf( __( 'Conversation between %s and you.', 'buddypress' ), bp_get_the_thread_recipients() ); ?>

				<?php endif; ?>

			</span>

			<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>" title="<?php _e( "Delete Message", "buddypress" ); ?>"><?php _e( 'Delete', 'buddypress' ); ?></a> &nbsp;
		</p>

		<?php do_action( 'bp_before_message_thread_list' ); ?>

		<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>

			<div class="message-box <?php bp_the_thread_message_alt_class(); ?>">

				<div class="message-metadata">

					<?php do_action( 'bp_before_message_meta' ); ?>

					<?php bp_the_thread_message_sender_avatar( 'type=thumb&width=30&height=30' ); ?>

					<?php if ( bp_get_the_thread_message_sender_link() ) : ?>

						<strong><a href="<?php bp_the_thread_message_sender_link(); ?>" title="<?php bp_the_thread_message_sender_name(); ?>"><?php bp_the_thread_message_sender_name(); ?></a></strong>

					<?php else : ?>

						<strong><?php bp_the_thread_message_sender_name(); ?></strong>

					<?php endif; ?>

					<span class="activity"><?php bp_the_thread_message_time_since(); ?></span>

					<?php do_action( 'bp_after_message_meta' ); ?>

				</div><!-- .message-metadata -->

				<?php do_action( 'bp_before_message_content' ); ?>

				<div class="message-content">

					<?php bp_the_thread_message_content(); ?>

				</div><!-- .message-content -->

				<?php do_action( 'bp_after_message_content' ); ?>

				<div class="clear"></div>

			</div><!-- .message-box -->

		<?php endwhile; ?>

		<?php do_action( 'bp_after_message_thread_list' ); ?>

		<?php do_action( 'bp_before_message_thread_reply' ); ?>

		<form id="send-reply" action="<?php bp_messages_form_action(); ?>" method="post" class="standard-form">

			<div class="message-box">

				<div class="message-metadata">

					<?php do_action( 'bp_before_message_meta' ); ?>

					<div class="avatar-box">
						<?php bp_loggedin_user_avatar( 'type=thumb&height=30&width=30' ); ?>

						<strong><?php _e( 'Send a Reply', 'buddypress' ); ?></strong>
					</div>

					<?php do_action( 'bp_after_message_meta' ); ?>

				</div><!-- .message-metadata -->

				<div class="message-content">

					<?php do_action( 'bp_before_message_reply_box' ); ?>

					<textarea name="content" id="message_content" rows="15" cols="40"></textarea>

					<?php do_action( 'bp_after_message_reply_box' ); ?>

					<div class="submit">
						<input type="submit" name="send" value="<?php _e( 'Send Reply', 'buddypress' ); ?>" id="send_reply_button"/>
					</div>

					<input type="hidden" id="thread_id" name="thread_id" value="<?php bp_the_thread_id(); ?>" />
					<input type="hidden" id="messages_order" name="messages_order" value="<?php bp_thread_messages_order(); ?>" />
					<?php wp_nonce_field( 'messages_send_message', 'send_message_nonce' ); ?>

				</div><!-- .message-content -->

			</div><!-- .message-box -->

		</form><!-- #send-reply -->

		<?php do_action( 'bp_after_message_thread_reply' ); ?>

	<?php endif; ?>

	<?php do_action( 'bp_after_message_thread_content' ); ?>

</div>