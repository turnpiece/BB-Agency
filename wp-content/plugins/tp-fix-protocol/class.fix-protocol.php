<?php

class TP_Fix_Protocol {

	const ID = 'fix_protocol';
	const DEBUGGING = false;

	private $transactions = false; // needs database reload privileges
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );

		if (self::DEBUGGING) {
			error_reporting(E_ALL);
			ini_set('display_errors', true);
		}
	}

	function register_options_page() {
		add_submenu_page( 'tools.php', 'Change inline images from http:// to https://', 'Fix Protocol', 'remove_users', 'fix-protocol', array( $this, 'options_page' ), '', 10 );
	}

	function options_page() {

		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"></div>
			<h2><?php _e('Change http:// to http://') ?></h2>
			<?php

			if ($_POST && $_POST['action'] == self::ID) {

				check_admin_referer('fix-protocol');

				// set script time out to give it a bit of time
				set_time_limit( 300 ); // 5 minutes

				// anonymize email addresses in users table
				$this->fix_protocol();

			} else {
				?>
				<p><?php _e( "This will go through the posts and pages on this site changing the urls of images from http to https in order to avoid the site being punished by mixed content warnings. You should probably backup your database before running this, just in case." ) ?></p>
				<form action="tools.php?page=fix-protocol" method="post" name="fix_protocol">
					<?php echo wp_nonce_field('fix-protocol'); ?>
					<p class="submit">
						<input type="hidden" name="action" value="<?php echo self::ID ?>" />
						<input type="hidden" name="settings" value="run" />
						<input type="submit" name="Submit" value="<?php _e('Run the fixer') ?>" class="button button-primary" />
					</p>
				</form>
				<?php		
			}

			?>

		</div>
		<?php
	}

	/**
	 *
	 * fix protocol http:// => https://
	 *
	 */
	private function fix_protocol() {

		global $wpdb;

		echo '<p>' . __( "Going through your site's posts and pages looking for images with an insecure protocol..." ) . '...</p>';

		$sql = "
			UPDATE {$wpdb->posts} 
			SET `post_content` = ( Replace (`post_content`, '%s', '%s') ) 
			WHERE Instr(post_content, 'jpeg') > 0 
        	OR Instr(post_content, 'jpg') > 0 
        	OR Instr(post_content, 'gif') > 0 
        	OR Instr(post_content, 'png') > 0";

		$this->do_query( $wpdb->prepare( $sql, 'src="http://', 'src="//' ) );

		// catch any single quoted
		$this->do_query( $wpdb->prepare( $sql, "src='http://", "src='//" ) );

		echo '<p>' . __( "All images in this sites posts should now be loaded securely. Check through the site to make sure everything is as it should be." ) . '</p>';
	}

	/**
	 *
	 * do query
	 *
	 * @param string $sql
	 *
	 */
	private function do_query( $sql ) {

		global $wpdb;

		$wpdb->show_errors();

		// run the update
		if ($wpdb->query( $sql ) === false)
			wp_die( $wpdb->last_error );

	}

}