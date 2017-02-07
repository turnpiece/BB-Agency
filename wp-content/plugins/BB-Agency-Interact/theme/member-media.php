<?php
/*
Template Name: Member Details
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

$bb_agency_options_arr = get_option('bb_agency_options');
$bb_agency_option_agencyimagemaxheight 	= $bb_agency_options_arr['bb_agency_option_agencyimagemaxheight'];
if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) { $bb_agency_option_agencyimagemaxheight = 800; }
$bb_agency_option_profilenaming 		= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];
$bb_agency_option_locationtimezone 		= (int)$bb_agency_options_arr['bb_agency_option_locationtimezone'];

// Change Title
add_filter('wp_title', 'bb_agencyinteractive_override_title', 10, 2);
	function bb_agencyinteractive_override_title(){
		return "Manage Media";
	}   

// Form Post
if (isset($_POST['action'])) {

	$ProfileID					=$_POST['ProfileID'];
	$ProfileUserLinked			=$_POST['ProfileUserLinked'];
	$ProfileGallery				=$_POST['ProfileGallery'];

    

	// Get Primary Image
	$ProfileMediaPrimaryID		=$_POST['ProfileMediaPrimary'];

	// Error checking
	$error = "";
	$have_error = false;

	// Get Post State
	$action = $_POST['action'];
	switch($action) {

	// *************************************************************************************************** //
	// Edit Record
	case 'editRecord':
		if (!$have_error){
		
        // fixed error of folder is not created 
		$ProfileGallery = bb_agency_createdir($ProfileGallery);  // Check Directory - create directory if does not exist	
		
		// Upload Image & Add to Database
			$i = 1;
			while ($i <= 10) {
				if($_FILES['profileMedia'. $i]['tmp_name'] != ""){
					
					$UploadMedia[] = $_FILES['profileMedia'. $i]['name'];

					$uploadMediaType = $_POST['profileMedia'. $i .'Type'];					
					if ($have_error != true) {

						// Upload if it doesnt exist already
						$path_parts = pathinfo($_FILES['profileMedia'. $i]['name']);
						$safeProfileMediaFilename =  bb_agency_safenames($path_parts['filename'].".".$path_parts['extension']);

						// check if file exists
						$count = $wpdb->get_var("SELECT COUNT(*) FROM " . table_agency_profile_media . " WHERE `ProfileID` = '". $ProfileID ."' AND `ProfileMediaURL` = '".$safeProfileMediaFilename ."'");

						if ($count < 1) {
							if($uploadMediaType == "Image") { 
							    if($_FILES['profileMedia'. $i]['type'] == "image/pjpeg" || $_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/gif" || $_FILES['profileMedia'. $i]['type'] == "image/png"){
							
									$image = new bb_agency_image();
									$image->load($_FILES['profileMedia'. $i]['tmp_name']);
			
									if ($image->getHeight() > $bb_agency_option_agencyimagemaxheight) {
										$image->resizeToHeight($bb_agency_option_agencyimagemaxheight);
									}
									$image->save(bb_agency_UPLOADPATH . $ProfileGallery ."/". $safeProfileMediaFilename);

									// Add to database
									$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
							    }else{
									$error .= "<b><i>".__("Please upload an image file only", bb_agencyinteract_TEXTDOMAIN)."</i></b><br />";
							        $have_error = true;
								}
							}
							else if($uploadMediaType =="Voice Demo"){
								// Add to database
								$MIME = array('audio/mpeg', 'audio/mp3');
								if(in_array($_FILES['profileMedia'. $i]['type'], $MIME)){
									$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
				                 	move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
								}else{
									$error .= "<b><i>".__("Please upload a mp3 file only", bb_agencyinteract_TEXTDOMAIN) ."</i></b><br />";
									$have_error = true;
								}
							}
							else if($uploadMediaType =="Resume"){
								// Add to database
								 if ($_FILES['profileMedia'. $i]['type'] == "application/msword" || $_FILES['profileMedia'. $i]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"  || $_FILES['profileMedia'. $i]['type'] == "application/pdf" || $_FILES['profileMedia'. $i]['type'] == "application/rtf")
								{
								  	$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
				                  	move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
								}else{
								   	$error .= "<b><i>".__("Please upload PDF/MSword/RTF files only", bb_agencyinteract_TEXTDOMAIN) ."</i></b><br />";
							        $have_error = true;	
								}
							}
							else if($uploadMediaType =="Headshot"){
								// Add to database
								if ($_FILES['profileMedia'. $i]['type'] == "application/msword"|| $_FILES['profileMedia'. $i]['type'] == "application/pdf" || $_FILES['profileMedia'. $i]['type'] == "application/rtf" || $_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/gif" || $_FILES['profileMedia'. $i]['type'] == "image/png")
								{
									$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
				                  	move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
								}else{
								   	$error .= "<b><i>".__("Please upload PDF/MSWord/RTF/Image files only", bb_agencyinteract_TEXTDOMAIN) ."</i></b><br />";
							        $have_error = true;	
								}
							}
							else if($uploadMediaType =="CompCard"){
								// Add to database
								 if ($_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/png")
								{
								  $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
				                  move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
								}else{
								   	$error .= "<b><i>".__("Please upload jpeg or png files only", bb_agencyinteract_TEXTDOMAIN) ."</i></b><br />";
									$have_error = true;	
								}
							}else{
								// Add to database
								  if($_FILES['profileMedia'. $i]['type'] == "image/pjpeg" || $_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/gif" || $_FILES['profileMedia'. $i]['type'] == "image/png"){
								  $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
				                 		 move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
								}else{
								   	$error .= "<b><i>".__("Please upload jpeg or png files only", bb_agencyinteract_TEXTDOMAIN) ."</i></b><br />";
									$have_error = true;	
								}
							}							
						} // End count
					} // End have error = false
				} //End:: if profile media is not empty.
				$i++;
			} // endwhile

			// Upload Videos to Database
			if (isset($_POST['profileMediaV1']) && !empty($_POST['profileMediaV1'])) {
				$profileMediaType = $_POST['profileMediaV1Type'];
				$profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV1']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}
			if (isset($_POST['profileMediaV2']) && !empty($_POST['profileMediaV2'])) {
				$profileMediaType	=$_POST['profileMediaV2Type'];
				$profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV2']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}
			if (isset($_POST['profileMediaV3']) && !empty($_POST['profileMediaV3'])) {
				$profileMediaType	=$_POST['profileMediaV3Type'];
				$profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV3']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}

			/* --------------------------------------------------------- CLEAN THIS UP -------------- */
			// Do we have a custom image yet? Lets just set the first one as primary.
			$count = $wpdb->get_var("SELECT COUNT(*) FROM " . table_agency_profile_media . " WHERE `ProfileID` = '". $ProfileID ."' AND `ProfileMediaType` = 'Image' AND `ProfileMediaPrimary` = '1'");

			if ($count < 1) {
			 	$resultsNeedOne = $wpdb->get_row("SELECT * FROM " . table_agency_profile_media . " WHERE `ProfileID` = '". $ProfileID ."' AND `ProfileMediaType` = 'Image' LIMIT 0, 1");
				if (!empty($resultsNeedOne)) {
					$resultsFoundOne = $wpdb->query("UPDATE " . table_agency_profile_media . " SET `ProfileMediaPrimary` = '1' WHERE `ProfileID` = '$ProfileID' AND `ProfileMediaID` = '$resultsNeedOne->ProfileMediaID'");
					break;
				}
			}
	  		if ($ProfileMediaPrimaryID > 0) {
			  	// Update Primary Image
			  	$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='0' WHERE ProfileID=$ProfileID");
			  	$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID=$ProfileID AND ProfileMediaID=$ProfileMediaPrimaryID");
			}

			/* --------------------------------------------------------- CLEAN THIS UP -------------- */
			
			$alerts = "<div id=\"message\" class=\"updated\"><p>". __("Profile updated successfully", bb_agencyinteract_TEXTDOMAIN) ."!</a></p></div>";
		} else {
			$alerts = "<div id=\"message\" class=\"error\"><p>". __("Error updating record, please ensure you have filled out all required fields.", bb_agencyinteract_TEXTDOMAIN) ."</p></div>"; 
		}
		
		if ($have_error != true) {
					// redirect only, if requirement of Redirect page is not  "/profile-member/media/ after successful files upload"

			//wp_redirect( $bb_agencyinteract_WPURL ."/profile-member/media/" );
		
		//exit;
	    }
	break;
	}
}



/* Display Page ******************************************/ 
get_header();

// Check Sidebar
	
echo "<div id=\"container\" class=\"".get_content_class()." column bb-agency-interact bb-agency-interact-media\">\n";
echo "  <div id=\"content\">\n";

// ****************************************************************************************** //
// Check if User is Logged in or not
if (is_user_logged_in()) { 
	
	/// Show registration steps
	echo "<div id=\"profile-steps\">Profile Setup: Step 3 of 3</div>\n";
	
	echo "<div id=\"profile-manage\" class=\"profile-media\">\n";
	
	// Menu
	include("include-menu.php"); 	
	echo " <div class=\"manage-media manage-content\">\n";
	
	
	/* Check if the user is regsitered *****************************************/ 
	// Verify Record

	$ProfileUserLinked = $current_user->id;

	$query = "SELECT * FROM " . table_agency_profile . " WHERE `ProfileUserLinked` = $ProfileUserLinked LIMIT 1";

	$profile = $wpdb->get_row( $query );

	if ($profile) {

		// Manage Profile
		include 'include-profilemedia.php'; 	
				
	} else {
		
		// No Record Exists, register them
		echo "<p>".__("Records show you are not currently linked to a model or agency profile. ", bb_agencyinteract_TEXTDOMAIN)."</p>";
		
	}
	echo " </div>\n"; // .profile-manage-inner
	echo "</div>\n"; // #profile-manage
} else {
	
	// Show Login Form
	include("include-login.php"); 	
}
	
echo "  </div><!-- #content -->\n";
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
