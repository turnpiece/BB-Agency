<form method="post" action="<?php echo get_bloginfo("wpurl") ?>/profile-member/preferences/">
	<input type="hidden" name="user_id" value="<?php echo get_current_user_id() ?>" />

	<fieldset>
		<p>
			<label for="email_updates">
				<input type="checkbox" name="email_updates" id="email_updates" value="1" <?php checked( get_user_meta( get_current_user_id(), 'email_updates', true ) ) ?> />
				<?php _e( 'I am happy to receive emails from Kiddiwinks Agency with details of models registered with them.' ) ?>
			</label>
		</p>
		<p>
			<label for="newsletter">
				<input type="checkbox" name="newsletter" id="newsletter" value="1" <?php checked( get_user_meta( get_current_user_id(), 'newsletter', true ) ) ?> />
				<?php _e( 'I want to keep receiving the annual Kiddiwinks Newsletter via email.' ) ?>
			</label>
		</p>
		<p>
			<label for="postal">
				<input type="checkbox" name="postal" id="postal" value="1" <?php checked( get_user_meta( get_current_user_id(), 'postal', true ) ) ?> />
				<?php _e( 'I am happy to receive cards through the post, or e-cards on email, from Kiddiwinks for Christmas/Easter/Birthdays.' ) ?>
			</label>
		</p>
	</fieldset>

	<input type="submit" name="action" value="update" />
</form>