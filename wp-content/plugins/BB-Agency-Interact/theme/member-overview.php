<?php
/*
Template Name: 	Member Details
 * @name		Member Details
 * @type		PHP page
 * @desc		Member Details
*/

session_start();
header("Cache-control: private"); //IE 6 Fix
global $wpdb;

/* Get User Info ******************************************/ 
global $current_user;
get_currentuserinfo();

// Get Settings
$bb_agency_options_arr 							= get_option('bb_agency_options');
$bb_agency_option_profilenaming 				= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];
$bb_agencyinteract_options_arr 					= get_option('bb_agencyinteract_options');
$bb_agencyinteract_option_registerallow 		= (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallow'];
$bb_agencyinteract_option_overviewpagedetails 	= (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_overviewpagedetails'];

// Check Sidebar
$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
$bb_agencyinteract_option_profilemanage_sidebar = $bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'];
$bb_subscription = $bb_agency_options_arr['bb_agency_option_profilelist_subscription'];

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
if ($profiletype == 1) { $profiletypetext = __("Agent/Producer", bb_agencyinteract_TEXTDOMAIN); } else { $profiletypetext = __("Model/Talent", bb_agencyinteract_TEXTDOMAIN); }

// Change Title
add_filter('wp_title', 'bb_agencyinteractive_override_title', 10, 2);
	function bb_agencyinteractive_override_title(){
		return "Member Overview";
	}

/* Display Page ******************************************/ 
get_header();
	
	echo "	<div id=\"primary\" class=\"col_12 column bb-agency-interact bb-agency-interact-overview\">\n";
	echo "  	<div id=\"content\">\n";

		// get profile Custom fields value
		$bb_agency_new_registeredUser = get_user_meta($current_user->id,'bb_agency_new_registeredUser',true);
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 

                    			/*
			 * Set Media to not show to
			 * client/s, agents, producers,
			 */
            $ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
	        $ptype = retrieve_title($ptype);
			$restrict = array('client','clients','agents','agent','producer','producers');
			if(in_array(strtolower($ptype),$restrict)){
				echo "<div id=\"profile-steps\">Profile Setup: Step 1 of 2</div>\n";
			} else {
				echo "<div id=\"profile-steps\">Profile Setup: Step 1 of 3</div>\n";
			}
                        
			echo "	<div id=\"profile-manage\" class=\"profile-overview\">\n";
				
			/* Check if the user is regsitered *****************************************/ 
			$sql = "SELECT ProfileID FROM ". table_agency_profile ." WHERE ProfileUserLinked =  ". $current_user->ID ."";
			$profileID = $wpdb->get_var($sql);

			if ($profileID) {

				// Menu
				include("include-menu.php"); 	
				echo " <div class=\"manage-overview manage-content\">\n";
					  
				echo "	 <div class=\"manage-section welcome\">\n";			
				echo "	 <h1>". __("Welcome Back", bb_agencyinteract_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";
				// Record Exists
			
				/* Show account information here *****************************************/
				 
				echo " <div class=\"section-content section-account\">\n"; // .account
				echo " 	<ul>\n";
				echo "      <li><a href=\"account/\">Edit Your Account Details</a></li>\n";
				echo "      <li><a href=\"manage/\">Manage Your Profile Information</a></li>\n";
				if (defined('bb_agencyinteract_ALLOW_UPLOADS') && bb_agencyinteract_ALLOW_UPLOADS) {
					echo "      <li><a href=\"media/\">Manage Photos and Media</a></li>\n";
				}
				if ($bb_subscription){
					echo "      <li><a href=\"subscription/\">Manage your Subscription</a></li>\n";
				}
				echo "	</ul>\n";
				echo " </div>\n";
			  	echo " </div>\n"; // .welcome
			  	echo " </div>\n"; // .profile-manage-inner
				  
				// No Record Exists, register them
			} else {
					
				echo "<h1>". __("Welcome", bb_agencyinteract_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";

				if ($profiletype == 1) {
					echo "". __("We have you registered as", bb_agencyinteract_TEXTDOMAIN) ." <strong>". $profiletypetext ."</strong>";
					echo "<h2><a href=\"". $bb_agencyinteract_WPURL ."/profile-search/\">". __("Begin Your Search", bb_agencyinteract_TEXTDOMAIN) ."</a></h2>";
					
					echo " <div id=\"subscription-customtext\">\n";
					$Page = get_page($bb_agencyinteract_option_subscribepagedetails);
					echo apply_filters('the_content', $Page->post_content);
					echo " </div>";

				} else {
				  	if ($bb_agencyinteract_option_registerallow == 1) {

						// Users CAN register themselves
						echo "". __("We have you registered as", bb_agencyinteract_TEXTDOMAIN) ." <strong>". $profiletypetext ."</strong>";
						echo "<h2>". __("Setup Your Profile", bb_agencyinteract_TEXTDOMAIN) ."</h2>";
					
						// Register Profile
						include("include-profileregister.php");						
				  	} else {
					
						// Cant register
						echo "<strong>". __("Self registration is not permitted.", bb_agencyinteract_TEXTDOMAIN) ."</strong>";
				  	}
				}					
			}			
			echo "</div><!-- #profile-manage -->\n";

		} else {

			// Show Login Form
			include("include-login.php"); 	
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #primary -->\n";

// Get Footer
get_footer();
?>