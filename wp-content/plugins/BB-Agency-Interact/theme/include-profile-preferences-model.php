<form method="post" action="<?php echo get_bloginfo("wpurl") ?>/profile-member/preferences/">
	<input type="hidden" name="user_id" value="<?php echo get_current_user_id() ?>" />

	<fieldset>
		<p>
			<label for="clients">
				<input type="checkbox" name="clients" id="clients" value="1" <?php checked( get_user_meta( get_current_user_id(), 'clients', true ) == 1 ) ?> />
				<?php _e( 'I am happy for you to send my details to clients for shoots and castings (details include pictures, height, age, location and name).' ) ?>
			</label>
		</p>
		<p>
			<label for="marketing">
				<input type="checkbox" name="marketing" id="marketing" value="1" <?php checked( get_user_meta( get_current_user_id(), 'marketing', true ) ) ?> />
				<?php _e( 'I am happy for you to post images from shoots undertaken, or images that I send to you, on your social media sites.' ) ?>
			</label>
		</p>
		<p>
			<label for="newsletter">
				<input type="checkbox" name="newsletter" id="newsletter" value="1" <?php checked( get_user_meta( get_current_user_id(), 'newsletter', true ) ) ?> />
				<?php _e( 'I am happy to receive occasional newsletters from Kiddiwinks, providing information on what we have been up to.' ) ?>
			</label>
		</p>
	</fieldset>

	<input type="submit" name="action" value="update" />
</form>