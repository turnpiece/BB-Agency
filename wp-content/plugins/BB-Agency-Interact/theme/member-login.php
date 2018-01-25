<?php

bb_agencyinteract_debug( print_r($_REQUEST, true) );

// *************************************************************************************************** //
// Respond to Login Request
	if ( !empty($_POST) && !empty( $_POST['action'] ) && $_POST['action'] == 'log-in' ) {

		bb_agencyinteract_debug( 'logging in user ' . $_POST['user-name'] );

		global $error;

		$user = wp_signon( 
			array( 
				'user_login' => $_POST['user-name'], 
				'user_password' => $_POST['password'], 
				'remember' => $_POST['remember-me'] 
			) 
		);
		
		if (!is_wp_error($user))
	    	wp_set_current_user( $user->ID );
	    
	}

// ****************************************************************************************** //
// Logged in 

	if (is_user_logged_in()) {

		bb_agencyinteract_debug( 'user is logged in' );

		//get_currentuserinfo();
		//$user_info = get_userdata( $user_ID ); 

		// if there's a redirect to set let's use it
		if (isset($_POST['redirect_to']) && $_POST['redirect_to']) {
			bb_agencyinteract_debug( __FUNCTION__ . " redirecting to " . $_POST['redirect_to'] );
			wp_redirect($_POST['redirect_to']);
			exit;
		}

		$redirect = $_POST["lastviewed"];

		// If user_registered date/time is less than 48hrs from now
		if (!empty($redirect)) {
			bb_agencyinteract_debug( __FUNCTION__ . " redirect to " . site_url(). "/profile/".$redirect );
			wp_redirect( site_url(). "/profile/".$redirect );
		} elseif (current_user_can('manage_options')) {
			bb_agencyinteract_debug( __FUNCTION__ . " redirect to admin page " );
			wp_redirect( admin_url( 'admin.php?page=bb_agency_settings' ) );
		} else {
			bb_agencyinteract_debug( __FUNCTION__ . " redirect to profile page " . site_url(). "/profile-member/" );
			wp_redirect( site_url() . '/profile-member/' );
	  	}
	  	exit;
	
	}

// ****************************************************************************************** //
// Not logged in	

	bb_agencyinteract_debug( 'user is not logged in so displaying login page' );

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
	
?>