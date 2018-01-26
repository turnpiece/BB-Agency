<?php
// *************************************************************************************************** //
// Respond to Login Request
if ( $_SERVER['REQUEST_METHOD'] == "POST" && !empty( $_POST['action'] ) && $_POST['action'] == 'log-in' ) {

	global $error;
	$login = wp_login( $_POST['user-name'], $_POST['password'] );
	$login = wp_signon( array( 'user_login' => $_POST['user-name'], 'user_password' => $_POST['password'], 'remember' => $_POST['remember-me'] ), false );
	
    get_currentuserinfo();
    
	if($login->ID) {
    	wp_set_current_user($login->ID);  // populate
	   	get_user_login_info();
	}		
}

function get_user_login_info(){
    
    $user_ID = get_current_user_id();  
				
	if (!empty($user_ID)) {
		
		$redirect = $_POST["lastviewed"];
		get_currentuserinfo();
		$user_info = get_userdata( $user_ID ); 

		// if there's a redirect to set let's use it
		if (isset($_POST['redirect_to']) && $_POST['redirect_to']) {
			die( $_POST['redirect_to'] );
			wp_redirect($_POST['redirect_to']);
			exit;
		}
			
		// If user_registered date/time is less than 48hrs from now
		if(!empty($redirect)){
			header("Location: ". get_bloginfo("wpurl"). "/profile/".$redirect);
		} else {

			// If Admin, redirect to plugin
			if ( current_user_can( 'manage_options' ) ) {
				//header("Location: ". admin_url("admin.php?page=bb_agency_menu"));
			}

			// Message will show for 48hrs after registration
			elseif( strtotime( $user_info->user_registered ) > ( time() - 172800 ) ) {
				header("Location: ". get_bloginfo("wpurl"). "/profile-member/");
			} else {
				header("Location: ". get_bloginfo("wpurl"). "/profile-member/");
			}
	  	}
	} elseif(empty($_POST['user-name']) || empty($_POST['password']) ){
		// Nothing to show here

	} else {
		// Reload
		die( get_bloginfo("wpurl")."/profile-login/" );
  	    header("Location: ". get_bloginfo("wpurl")."/profile-login/");
	}
	die( $user_ID );
}

// ****************************************************************************************** //
// Already logged in 
	if (is_user_logged_in()) {
	
		global $user_ID; 
		$login = get_userdata( $user_ID );
				 get_user_login_info();	 
			/*
			echo "    <p class=\"alert\">\n";
						printf( __('You have successfully logged in as <a href="%1$s" title="%2$s">%2$s</a>.', bb_agencyinteract_TEXTDOMAIN), "/profile-member/", $login->display_name );
			echo "		 <a href=\"". wp_logout_url( get_permalink() ) ."\" title=\"". __('Log out of this account', bb_agencyinteract_TEXTDOMAIN) ."\">". __('Log out &raquo;', bb_agencyinteract_TEXTDOMAIN) ."</a>\n";
			echo "    </p><!-- .alert -->\n";
			*/
	
// ****************************************************************************************** //
// Not logged in
	} else {

		// *************************************************************************************************** //
		// Prepare Page
		get_header();

		echo "<div id=\"bbcontent\" class=\"bb-interact bb-interact-login\">\n";
		
			// Show Login Form
			$hideregister = true;
			include("include-login.php");

		echo "</div><!-- #bbcontent -->\n";

		// Get Footer
		get_footer();
	
	} // Done
	
?>