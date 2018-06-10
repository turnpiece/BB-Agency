<?php 
session_start();
header("Cache-control: private"); //IE 6 Fix

// Get User Information
global $user_ID; 
global $current_user;
global $wpdb;
get_currentuserinfo();
$CurrentUser = $current_user->id;

// Get Profile
$profileURL = get_query_var('target'); //$_REQUEST["profile"];

if (!$profileURL)
	wp_die( 'Invalid page request' );

$bb_agency_option_agencyname = bb_agency_get_option('bb_agency_option_agencyname');
$bb_agency_option_privacy = bb_agency_get_option('bb_agency_option_privacy');
$bb_agency_option_galleryorder = bb_agency_get_option('bb_agency_option_galleryorder');
$bb_agency_option_showcontactpage = bb_agency_get_option('bb_agency_option_showcontactpage');

if ($bb_agency_option_galleryorder == 1) { $orderBy = "ProfileMediaID DESC, ProfileMediaPrimary DESC"; } else { $orderBy = "ProfileMediaID ASC, ProfileMediaPrimary DESC"; }
	$bb_agency_option_layoutprofile = (int)bb_agency_get_option('bb_agency_option_layoutprofile');
	$bb_agency_option_gallerytype = (int)bb_agency_get_option('bb_agency_option_gallerytype');
if ($bb_agency_option_gallerytype == 1) {
	// Slimbox
	$reltype = "rel=\"lightbox-profile\"";
	$reltypev = "target=\"_blank\"";
} elseif ($bb_agency_option_gallerytype == 2) {
	// PrettyBox
	$reltype = "rel=\"prettyPhoto\"";
	$reltypev = "rel=\"prettyPhoto\"";
} else {
	// None
	$reltype = "rel=\"lightbox-profile\"";
	$reltypev = "target=\"_blank\"";
}
$bb_agency_option_agency_urlcontact = bb_agency_get_option('bb_agency_option_agency_urlcontact');
$bb_agency_option_profilenaming = bb_agency_get_option('bb_agency_option_profilenaming');
$bb_agency_option_profilelist_sidebar = bb_agency_get_option('bb_agency_option_profilelist_sidebar');

$t_profile = table_agency_profile;
$t_datatype = table_agency_data_type;
$query = <<<EOF
SELECT p.*, dt.`DataTypePrivacy` AS ProfilePrivacy, dt.`DataTypeTalent` AS ProfileHasTalent
FROM $t_profile AS p
LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
WHERE p.`ProfileGallery` = '%s'
EOF;

$profile = $wpdb->get_row( $wpdb->prepare( $query, $profileURL ) );

if (empty($profile))
	die ( __("Error, query failed", bb_agency_TEXTDOMAIN ) );

$ProfileID					= $profile->ProfileID;
$ProfileUserLinked			= $profile->ProfileUserLinked;
$ProfileGallery				= $profile->ProfileGallery;
$ProfileContactDisplay		= $profile->ProfileContactDisplay;
$ProfileContactNameFirst	= $profile->ProfileContactNameFirst;
$ProfileContactNameLast		= $profile->ProfileContactNameLast;
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
$ProfileContactEmail		= $profile->ProfileContactEmail;
$ProfileType				= $profile->ProfileType;
$ProfileHasTalent			= $profile->ProfileHasTalent;
$ProfileTalent				= $profile->ProfileTalent;
$ProfileGenre				= $profile->ProfileGenre;
$ProfileAbility				= $profile->ProfileAbility;
$ProfileContactWebsite		= $profile->ProfileContactWebsite;
$ProfileContactPhoneHome	= $profile->ProfileContactPhoneHome;
$ProfileContactPhoneCell	= $profile->ProfileContactPhoneCell;
$ProfileContactPhoneWork	= $profile->ProfileContactPhoneWork;
$ProfileGender    			= $profile->ProfileGender;
$ProfileDateBirth	    	= $profile->ProfileDateBirth;
$ProfileDateDue	    		= $profile->ProfileDateDue;
$ProfileAge 				= bb_agency_get_age($ProfileDateBirth);
$ProfileLocationCity		= $profile->ProfileLocationCity;
$ProfileLocationState		= $profile->ProfileLocationState;
$ProfileLocationZip			= $profile->ProfileLocationZip;
$ProfileLocationCountry		= $profile->ProfileLocationCountry;
$ProfileDateUpdated			= $profile->ProfileDateUpdated;
$ProfileIsActive			= $profile->ProfileIsActive; // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
$ProfileStatHits			= $profile->ProfileStatHits;
$ProfileDateViewLast		= $profile->ProfileDateViewLast;
$ProfilePrivacy				= $profile->ProfilePrivacy;

// Update Stats
$updateStats = $wpdb->query("UPDATE $t_profile SET ProfileStatHits = ProfileStatHits + 1, ProfileDateViewLast = NOW() WHERE ProfileID = '{$ProfileID}' LIMIT 1");

// Change Title
if (!function_exists("bb_agency_override_title")){
add_filter('wp_title', 'bb_agency_override_title', 10, 2);
	function bb_agency_override_title(){
		global $ProfileContactDisplay;
		return bloginfo('name') ." > ". $ProfileContactDisplay ."";
	}
}

if (!function_exists("bb_agency_inserthead_profile")){
	add_action('wp_head', 'bb_agency_inserthead_profile');
		// Call Custom Code to put in header
		function bb_agency_inserthead_profile() {
			global $bb_agency_option_layoutprofile;
			
//			if (bb_agency_get_option('bb_agency_option_layoutprofile')) {
				$layouttype = (int)bb_agency_get_option('bb_agency_option_layoutprofile');

				switch ($layouttype) {

					case 99 :
						// Slimbox
						wp_enqueue_script( 'slimbox2', plugins_url('/js/slimbox2.js', __FILE__) );
						wp_register_style( 'slimbox2', plugins_url('/style/slimbox2.css', __FILE__) );
	        			wp_enqueue_style( 'slimbox2' );
						break;
					case 0 :
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/image-resize.js', dirname(__FILE__)) );
						break;	
					case 2 :
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'js-scroller', plugins_url('/js/jquery.mCustomScrollbar.concat.min.js', dirname(__FILE__)) );
						
						wp_enqueue_script( 'jscroller', plugins_url('/js/scroller.js', dirname(__FILE__)), in_footer );
						break;
					case 6 :
						wp_register_style( 'flexslider', plugins_url('/style/flexslider.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'flexslider' );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/jquery.flexslider.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/initflexslider.js', dirname(__FILE__)), in_footer );
						break;
					case 7 :
						wp_register_style( 'flexslider', plugins_url('/style/flexslider.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'flexslider' );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/jquery.flexslider.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/initflexslider.js', dirname(__FILE__)), in_footer );
						break;
					case 8 :
						wp_register_style( 'booklet', plugins_url('/style/booklet.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'booklet' );
						wp_enqueue_script( 'jquerys', plugins_url('/js/booklet-jquery.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/booklet-jquery-ui.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'jquery-easing', plugins_url('/js/booklet-jquery.easing.1.3.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/booklet.min.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/booklet.init.js', dirname(__FILE__)), in_footer );
						break;
					case 9 :
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'js-scroller', plugins_url('/js/jquery.mCustomScrollbar.concat.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'jscroller', plugins_url('/js/scroller.js', dirname(__FILE__)), in_footer );
						break;
					default :
						// Slider Gallery			
						if ($bb_agency_option_layoutprofile == "3") {
							?>
							<script>
							var $tab = jQuery.noConflict();
							$tab(window).load(function() {
								$tab(".maintab").click(function(){
									var idx = this.id;
									var elem = "." + idx;
									var elem_id = "#" + idx;
									if ((idx=="row-all")){
										$tab(".tab").hide();
										$tab(".tab").show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
										$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
									} else {
									  	if(idx=="row-bookings"){					
											var url = "<?php echo get_permalink(get_page_by_title('booking')); ?>";
											window.location = url;
										} else {
											$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
											$tab(".tab").css({ opacity: 1.0 }).stop().animate({ opacity: 0.0 }, 2000).hide();
											$tab(elem).show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
											$tab(elem_id).removeClass("tab-inactive").addClass("tab-active");
										}
									}
								});
							});
							</script>
							<?php
						}
						break;
				} // end switch
//	        } // end if have layout profile
      } // function end
}// if function exist(bb_agency_inserthead_profile)

// GET HEADER  
get_header();
	
?>
<div id="container" <?php if ($bb_agency_option_profilelist_sidebar == 0) { echo "class=\"one-column\""; } ?> >
	<div id="content" role="main" class="transparent">
	<?php
	if (( !$ProfilePrivacy || is_user_logged_in()) && (
			( $bb_agency_option_privacy >= 1 && is_user_logged_in() ) || 
			( $bb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) ) || 
			( $bb_agency_option_privacy == 0 ) )) { 
		//if (isset($_SESSION['SearchMuxHash'])) { echo "Permission Granted"; }
		
	  	// Ok, but whats the status of the profile?
	  	if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
			include ("include-profile-layout.php"); 	
	  	} else {
			/*
			 * display this profile as long as it came
			 * from the page profilesecure else inactive if
	                     * directly viewed.
			 */
			if(strpos($_SERVER['HTTP_REFERER'],'client-view') > 0){
				include ("include-profile-layout.php"); 	
			} else {
				echo __("Inactive Profile", bb_agency_TEXTDOMAIN) ."\n";
			}
	  	}
	} else {
		// hold last model requested as session so we can return them where we found them 
		$ProfileLastViewed = get_query_var('profile');
		$redirect_to = get_bloginfo('url').'/profile/'.get_query_var('target');
		$_SESSION['ProfileLastViewed'] = get_query_var('target');
		include("include-login.php"); 	
	}
	?>
	</div>
</div>
<?php

get_footer(); 

?>