<?php
/*
Template Name: Edit Member Details
* @name		Edit Member Details
* @type		PHP page
* @desc		Edit Member Details
*/
session_start();
header("Cache-control: private"); //IE 6 Fix
global $wpdb;
/* Get User Info ******************************************/ 
global $current_user, $wp_roles;
get_currentuserinfo();
// Get Settings
$bb_agency_options_arr = get_option('bb_agency_options');
$bb_agency_option_profilenaming 		= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];
$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
$bb_agencyinteract_option_registerallow = (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallow'];

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
if ($profiletype == 1) { 
	$profiletypetext = __("Agent/Producer", bb_agencyinteract_TEXTDOMAIN); 
} else { 
	$profiletypetext = __("Model/Talent", bb_agencyinteract_TEXTDOMAIN); 
}

	// Change Title
	add_filter('wp_title', 'bb_agencyinteractive_override_title', 10, 2);
		function bb_agencyinteractive_override_title(){
			return __("Manage Profile", bb_agencyinteract_TEXTDOMAIN);
		}   
	
	/* Load the registration file. */
	require_once( ABSPATH . WPINC . '/registration.php' );
	require_once( ABSPATH . 'wp-admin/includes' . '/template.php' ); // this is only for the selected() function

// Form Post
$user_id = get_current_user_id();

if (isset($_POST['action']) && $user_id == (int)$_POST['user_id']) {

	if ($profiletype == 1) { 

		// update user meta for clients
		update_user_meta( $user_id, 'email_updates', $_POST['email_updates'] );
		update_user_meta( $user_id, 'newsletter', $_POST['newsletter'] );
		update_user_meta( $user_id, 'postal', $_POST['postal'] );

	} else {

		// update user meta for models
		update_user_meta( $user_id, 'clients', $_POST['clients'] );
		update_user_meta( $user_id, 'marketing', $_POST['marketing'] );
	}
}


/* Display Page ******************************************/ 
get_header();

// Check Sidebar
$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
$bb_agencyinteract_option_profilemanage_sidebar = $bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'];
$content_class = "";
if (is_user_logged_in()) {
	$content_class = "eight";
} else {
	$content_class = "twelve";
}

		// get profile Custom fields value
	echo "<div id=\"container\" class=\"".$content_class." column bb-agency-interact-account\">\n";
	echo "  <div id=\"content\">\n";
	echo "<div id=\"profile-steps\">Profile Setup: Preferences</div>\n";
	echo "<div id=\"profile-manage\" class=\"preferences\">\n";

	// Menu
	include("include-menu.php"); 	
	echo " <div class=\"manage-profile manage-content\">\n";
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 
			
			if ($profiletype == 1)
				include 'include-profile-preferences-client.php';
			else
				include 'include-profile-preferences-model.php';

		} else {
			echo "<p class=\"warning\">\n";
					_e('You must be logged in to update your preferences.', bb_agencyinteract_TEXTDOMAIN);
			echo "</p><!-- .warning -->\n";
			// Show Login Form
			include(dirname(__FILE__)."/include-login.php"); 	
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #container -->\n";
	

// Get Footer
get_footer();
