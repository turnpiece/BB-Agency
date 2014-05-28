<?php 
session_start();
header("Cache-control: private"); //IE 6 Fix

// Get User Information
	global $wpdb;
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$CurrentUser = $current_user->id;

// Set Values
$bb_agency_options_arr = bbagency_get_option();
$bb_agency_option_privacy = bbagency_get_option('bb_agency_option_privacy');
$bb_agency_option_layoutprofile = (int)bbagency_get_option('bb_agency_option_layoutprofile');
$bb_agency_option_profilenaming = bbagency_get_option('bb_agency_option_profilenaming');
$bb_agency_option_profilelist_sidebar = bbagency_get_option('bb_agency_option_profilelist_sidebar');
$bb_agency_option_agencyemail = bbagency_get_option('bb_agency_option_agencyemail');
$bb_agency_option_showcontactpage = bbagency_get_option('bb_agency_option_showcontactpage');

// Get Profile
$profileURL = get_query_var('target'); //$_REQUEST["profile"];


	$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='$profileURL'";
	$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$ProfileID					=$data['ProfileID'];
		$ProfileUserLinked			=$data['ProfileUserLinked'];
		$ProfileGallery				=stripslashes($data['ProfileGallery']);
		$ProfileContactDisplay		=stripslashes($data['ProfileContactDisplay']);
		$ProfileContactNameFirst	=stripslashes($data['ProfileContactNameFirst']);
		$ProfileContactNameLast		=stripslashes($data['ProfileContactNameLast']);
			if ($bb_agency_option_profilenaming == 0) {
				$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
			} elseif ($bb_agency_option_profilenaming == 1) {
				$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
			} elseif ($bb_agency_option_profilenaming == 3) {
				$ProfileContactDisplay = "ID ". $ProfileID;
			} elseif ($bb_agency_option_profilenaming == 4) {
				$ProfileContactDisplay = $ProfileContactNameFirst;
			} elseif ($bb_agency_option_profilenaming == 5) {
				$ProfileContactDisplay = $ProfileContactNameLast;
			}
		$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
		$ProfileType				=$data['ProfileType'];
		$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
		$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
		$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
		$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
		$ProfileGender    			=stripslashes($data['ProfileGender']);
		$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
		$ProfileAge 				= bb_agency_get_age($ProfileDateBirth);
		$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
		$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
		$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
		$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
		$ProfileStatEthnicity		=stripslashes($data['ProfileStatEthnicity']);
		$ProfileStatSkinColor		=stripslashes($data['ProfileStatSkinColor']);
		$ProfileStatEyeColor		=stripslashes($data['ProfileStatEyeColor']);
		$ProfileStatHairColor		=stripslashes($data['ProfileStatHairColor']);
		$ProfileStatHeight			=stripslashes($data['ProfileStatHeight']);
		$ProfileStatWeight			=stripslashes($data['ProfileStatWeight']);
		$ProfileStatBust	        =stripslashes($data['ProfileStatBust']);
		$ProfileStatWaist	    	=stripslashes($data['ProfileStatWaist']);
		$ProfileStatHip	        	=stripslashes($data['ProfileStatHip']);
		$ProfileStatShoe		    =stripslashes($data['ProfileStatShoe']);
		$ProfileStatDress			=stripslashes($data['ProfileStatDress']);
		$ProfileUnion				=stripslashes($data['ProfileUnion']);
		$ProfileExperience			=stripslashes($data['ProfileExperience']);
		$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
		$ProfileIsActive			=stripslashes($data['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
		$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
		$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
	}


// Process Form
if ($_POST["contact-action"] == "contact") {
	if (!empty($_POST["contact-your-email"])) {
		
		$ContactToName		=$_POST["contact-your-name"];
		$ContactToEmail		=$_POST["contact-your-email"];
		$ContactDate		=$_POST["contact-your-date"];
		$ContactMessage		=$_POST["contact-your-message"];
		$ContactProfile		=$_POST["contact-profileid"];
		$ContactSubject		=$bb_agency_option_agencyname ." contact request for ". $ProfileContactDisplay ." (ID-". $ProfileID .")";

		// Message
		$ContactMessage = '
			<html>
				<head>
				  	<title>'. $ContactSubject .'</title>
				</head>
				<body>
				  	<p>Contact request for <a href="'. get_bloginfo("url") .'/profile/'. $profileURL .'/">'. $ProfileContactDisplay .'/</a></p>
				  	<p>From '. $ContactToName .' ('. $ContactToEmail .')</a> for '. $ContactDate .'</p>
				  	<p>'. $ContactMessage .'</p>
				</body>
			</html>
			';
		// To send HTML mail, the Content-type header must be set
		//$headers .= 'To: '. $SearchMuxToName .' <'. $SearchMuxToEmail .'>' . "\r\n";
		
		add_filter('wp_mail_content_type','bb_agency_set_content_type');
			function bb_agency_set_content_type($content_type){
				return 'text/html';
			}
		
		// Mail it
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		if($bb_agency_option_showcontactpage == 1){
			$headers .= 'BCC: '.$ProfileContactEmail.''. "\r\n";
		}
		$headers .= 'From: '. $ContactToName .' <'. $ContactToEmail .'>' . "\r\n";
		
		wp_mail($bb_agency_option_agencyemail, $ContactSubject, $ContactMessage, $headers);
		
		$alert = "<div id=\"message\" class=\"updated\"><p>Email sent successfully!</p></div>";
	}

}
	
	// Change Title
	add_filter('wp_title', 'bb_agency_override_title', 10, 2);
		function bb_agency_override_title(){
			global $ProfileContactDisplay;
			return bloginfo('name') ." > Contact ". $ProfileContactDisplay ."";
		}
	


   
// GET HEADER  
	get_header();

	if ($bb_agency_option_profilelist_sidebar) {
		echo "	<div id=\"profile-sidebar\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
		echo "	</div>\n";
	}
	
	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	if ($count > 0) {
		if ( ( $bb_agency_option_privacy > 1 && is_user_logged_in() ) || ( $bb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) ) || ($bb_agency_option_privacy == 0) ) { 
			//if (isset($_SESSION['SearchMuxHash'])) { echo "Permission Granted"; }
			
		  	// Ok, but whats the status of the profile?
		  	if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
			  
			  	// Did they submit the form?
			  	if ($_POST) {
					echo $alert;
			  	} else {
				
					include ("include-profile-contact.php");				 
			  	}
			 	
		  	} else {
				echo "". __("Inactive Profile", bb_agency_TEXTDOMAIN) ."\n";
		  	}
		} else {
			// hold last model requested as session so we can return them where we found them 
			$ProfileLastViewed = get_query_var('profile');
			$_SESSION['ProfileLastViewed'] = $ProfileLastViewed;
			include("include-login.php"); 	
		}
	} else {
		// There is no record found.
		echo "". __("Invalid Profile", bb_agency_TEXTDOMAIN) ."\n";
	}
	echo "  </div>\n";
	echo "</div>\n";

	get_footer(); 
?>