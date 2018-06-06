<?php
/*
Template Name: Member Details
 * @name		Member Details
 * @type		PHP page
 * @desc		Member Details
*/

session_start();

global $wpdb;

/* Get User Info ******************************************/ 
global $current_user;
get_currentuserinfo();

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);

if ($profiletype == 1) { $profiletypetext = __("Agent/Producer", bb_agencyinteract_TEXTDOMAIN); } else { $profiletypetext = __("Model/Talent", bb_agencyinteract_TEXTDOMAIN); }  

// Form Post
if (isset($_POST['action'])) { 

	$ProfileID					=$_POST['ProfileID'];
	$ProfileUserLinked			=$_POST['ProfileUserLinked'];
	

	// Get Post State
	$action = $_POST['action'];
	switch($action) {

	// *************************************************************************************************** //
	// Edit Record
		case 'addBooking':

			// Update Record
			$wpdb->insert( table_agency_booking, array( 'ProfileID' => $ProfileID, 'BookedFrom' => $_POST['BookedFrom'], 'BookedTo' => $_POST['BookedTo'] ) );

			wp_redirect( $bb_agencyinteract_WPURL ."/profile-member/availability/" );
			exit;
			break;

		case 'deleteBookings':

			$ids = $_POST['BookedID'];

			if (!empty($ids))
				foreach ($ids as $id) {
					$wpdb->delete( table_agency_booking, array( 'ProfileID' => $ProfileID, 'BookedID' => $id ) );
				}

			wp_redirect( $bb_agencyinteract_WPURL ."/profile-member/availability/" );
			exit;
			break;

	}

}

/* Display Page ******************************************/ 
get_header();

// Check Sidebar
	?>
	<div id="container" class="<?php echo get_content_class() ?> column bb-agency-interact bb-agency-interact-availability">
		<div id="content">
		<?php
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 

            /*
			 * Set Media to not show to
			 * client/s, agents, producers,
			 */
			$ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
	                $ptype = retrieve_title($ptype);

			echo "<div id=\"profile-steps\">Profile Setup: Availability</div>\n";

			echo "<div id=\"profile-manage\" class=\"overview\">\n";

			// Menu
			include("include-menu.php");
			?>
			<div class="manage-profile manage-content">
				<p><?php _e('Tell us when you\'re booked up or otherwise unavailable', bb_agencyinteract_TEXTDOMAIN) ?></p>
			<?php
			// Show Errors & Alerts
			echo $alerts;

			/* Check if the user is regsitered *****************************************/ 
			// Verify Record
			$sql = "SELECT `ProfileID` FROM ". table_agency_profile ." WHERE `ProfileUserLinked` =  ". $current_user->ID;

			if ($wpdb->get_var($sql)) {
				// Manage Profile
				include("include-profileavailability.php"); 	
			} else {

				// No Record Exists, register them
				echo "<p>". __("Records show you are not currently linked to a model or agency profile.  Let's setup your profile now!", bb_agencyinteract_TEXTDOMAIN) ."</p>";

				// Register Profile
				include("include-profileregister.php");
			}
			echo "</div>\n"; // .profile-manage-inner
			echo "</div>\n"; // #profile-manage
		} else {
			
			// Show Login Form
			include("include-login.php"); 	
		}
	echo "<div style=\"clear: both; \"></div>\n";
	echo "</div><!-- #content -->\n";
	echo "</div><!-- #container -->\n";

	if (is_user_logged_in()) {

		// Get Sidebar 
		$LayoutType = "";
		if ($bb_agencyinteract_option_profilemanage_sidebar) {
			$LayoutType = "profile";
			get_sidebar();
		}
	}

// Get Footer
get_footer();
?>
