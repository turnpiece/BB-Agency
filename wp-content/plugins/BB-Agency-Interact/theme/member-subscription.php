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


// Get Data
$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
	$bb_agencyinteract_option_subscribeupsell = (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribeupsell'];
	$bb_agencyinteract_option_subscribepaypalemail = $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepaypalemail'];
	$bb_agencyinteract_option_subscribepagedetails = $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepagedetails'];

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
if ($profiletype == 1) { $profiletypetext = __("Agent/Producer", bb_agencyinteract_TEXTDOMAIN); } else { $profiletypetext = __("Model/Talent", bb_agencyinteract_TEXTDOMAIN); }


	// Change Title
	add_filter('wp_title', 'bb_agencyinteractive_override_title', 10, 2);
		function bb_agencyinteractive_override_title(){
			return "Manage Subscription";
		}   
	
/* Display Page ******************************************/ 
get_header();
	
	echo "<div id=\"container\" class=\"one-column bb-agency-interact bb-agency-interact-subscribe\">\n";
	echo "  <div id=\"content\">\n";
	
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) {
			
			/// Show registration steps
			echo "<div id=\"profile-steps\">Profile Setup: Step 4 of 4</div>\n";
			
			echo "<div id=\"profile-manage\" class=\"profile-admin account\">\n";
			
			// Menu
			include("include-menu.php"); 	
			echo " <div class=\"manage-subscription manage-content\">\n";

			/* Check if the user is regsitered *****************************************/ 
			// Verify Record
			$sql = "SELECT ProfileID FROM ". table_agency_profile ." WHERE ProfileUserLinked =  ". $current_user->ID ."";
			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			if ($count > 0) {
			  	while ($data = mysql_fetch_array($results)) {
			
					// Is there a subscription?
					if (isset($SubscriberDateExpire)) {
						echo "<h1>". $current_user->first_name .", ". __("enjoying your membership?", bb_agencyinteract_TEXTDOMAIN) ."</h1>";
						echo "<h3>". __("Your membership expires on ", bb_agencyinteract_TEXTDOMAIN) ." ". $SubscriberDateExpire .", renew today!</h3>";
					} else {
						echo "<h1>". $current_user->first_name .", ". __("are you ready to get discovered?", bb_agencyinteract_TEXTDOMAIN) ."</h1>";
						echo "<h3>". __("Subscribe now, and start applying to Casting Calls, join the Talent Directory, and more.", bb_agencyinteract_TEXTDOMAIN) ."</h3>";
					}

					// What are the rates?
					$sql = "SELECT * FROM ". table_agencyinteract_subscription_rates ."";
					$results = mysql_query($sql);
					$count = mysql_num_rows($results);
					if ($count > 0) {
						echo "<div id=\"subscription-wrapper\">";
					  	while ($data = mysql_fetch_array($results)) {
							echo " <div class=\"subscription-rate\">";
							echo "  <div class=\"subscription-rate-title\">". stripslashes($data['SubscriptionRateTitle']) ."</div>\n";
							echo "  <div class=\"subscription-rate-price\">$". $data['SubscriptionRatePrice'] ."</div>\n";
							echo "  <div class=\"subscription-rate-button\">\n";
							echo "    <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
							echo "  	<input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />\n";
							echo "  	<input type=\"hidden\" name=\"business\" value=\"". $bb_agencyinteract_option_subscribepaypalemail ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"item_name\" value=\"". $data['SubscriptionRateTitle'] ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"item_number\" value=\"". $data['SubscriptionRateID'] ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"custom\" value=\"". $current_user->ID ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"amount\" value=\"". $data['SubscriptionRatePrice'] ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"return\" value=\"". get_bloginfo("wpurl") ."/profile-member/subscription/\" />\n";
							echo "  	<input type=\"hidden\" name=\"notify_url\" value=\"". bb_agencyinteract_BASEDIR ."tasks/paypalIPN.php\" />\n";
							echo "  	<input type=\"hidden\" name=\"first_name\" value=\"". $current_user->first_name ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"last_name\" value=\"". $current_user->last_name ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"email\" value=\"". $current_user->user_email ."\" />\n";
							echo "  	<input type=\"hidden\" name=\"button_subtype\" value=\"services\" />\n";
							echo "  	<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_paynow_SM.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\" />\n";
							echo "    </form>\n";
							echo "  </div>\n";
							echo "  <div class=\"subscription-rate-text\">". stripslashes($data['SubscriptionRateText']) ."</div>\n";

							echo " </div>";
					  	} // is there record?
					echo " <div class=\"clear\"></div>";
					echo "</div>";
					}		
						
					echo "  <div id=\"subscription-customtext\">\n";
						$Page = get_page($bb_agencyinteract_option_subscribepagedetails);
						echo apply_filters('the_content', $Page->post_content);
					echo " </div>";
			
		  		} // is there record?

			} else {
				
				// No Record Exists, register them
				echo "". __("Records show you are not currently linked to a model or agency profile.  Lets setup your profile now!", bb_agencyinteract_TEXTDOMAIN) ."";
				
				// Register Profile
				include("include-profileregister.php"); 	
				
			}

			echo " </div>\n"; // .profile-manage-inner
			echo "</div>\n"; // #profile-manage
		} else {
			echo "<p class=\"warning\">\n";
					_e('You must be logged in to edit your profile.', bb_agencyinteract_TEXTDOMAIN);
			echo "</p><!-- .warning -->\n";
			// Show Login Form
			include("include-login.php"); 	
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #container -->\n";
	
// Get Sidebar 
$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
	$bb_agencyinteract_option_profilemanage_sidebar = $bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'];
	$LayoutType = "";
	if ($bb_agencyinteract_option_profilemanage_sidebar) {
		$LayoutType = "profile";
		get_sidebar(); 
	}

// Get Footer
get_footer();
?>
