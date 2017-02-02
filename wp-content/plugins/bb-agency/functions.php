<?php
/*
 * Debug Mode
   //$BB_DEBUG_MODE = true;
 */

// *************************************************************************************************** //
/*
 * Header for Administrative Pages
 */

	// Admin Head Section 
	add_action('admin_head', 'bb_agency_admin_head');
		function bb_agency_admin_head() {
			// Ensure we are in the admin section of wordpress
			if ( is_admin() ) {

				// Get Custom Admin Styles
				wp_register_style( 'bbagencyadmin', plugins_url('/style/admin.css', __FILE__) );
				wp_enqueue_style( 'bbagencyadmin' );

				// Load Jquery if not registered
				if ( ! wp_script_is( 'jquery', 'registered' ) )
					wp_register_script( 'jquery', plugins_url( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', __FILE__ ), false, '1.8.3' );

				// Load custom fields javascript
				wp_enqueue_script( 'customfields', plugins_url('js/js-customfields.js', __FILE__) );
			}
		}
	

// *************************************************************************************************** //
/*
 * Header for Public facing Pages
 */

	add_action('wp_head', 'bb_agency_inserthead');
		// Call Custom Code to put in header
		function bb_agency_inserthead() {
			// Ensure we are NOT in the admin section of wordpress
			if ( !is_admin() ) {
				
				// Get Custom Styles
				wp_register_style( 'bbagency-style', plugins_url('/theme/style.css', __FILE__) );
				wp_enqueue_style( 'bbagency-style' );
			}	
		}


// *************************************************************************************************** //
/*
 * Customize WordPress Dashboard
 */

    // Pull User Identified Settings/Options 
	// Can we show the ads? Or keep it clean?
	$bb_agency_option_advertise = bb_agency_get_option('bb_agency_option_advertise');

	if ($bb_agency_option_advertise == 0) {  // Reversed it, now 1 = Hide Advertising

	  add_action('wp_dashboard_setup', 'bb_agency_add_dashboard' );		
		// Hoook into the 'wp_dashboard_setup' action to register our other functions
		function bb_agency_add_dashboard() {

			global $wp_meta_boxes;
		
			// reorder the boxes - first save the left and right columns into variables
			$left_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
			$right_dashboard = $wp_meta_boxes['dashboard']['side']['core']; 
			
			// finally replace the left and right columns with the new reordered versions
			$wp_meta_boxes['dashboard']['normal']['core'] = $left_dashboard; 
			$wp_meta_boxes['dashboard']['side']['core'] = $right_dashboard;
		}
	}


// *************************************************************************************************** //
/*
 * Add Custom Classes to <body>
 */

	add_filter("body_class", "bb_agency_insertbodyclass");
		// Add CSS Class based on URL
		function bb_agency_insertbodyclass($classes) {
			// Remove Blog
			if (substr($_SERVER['REQUEST_URI'], 0, 9) == "/profile/") {
				$classes[] = 'bbagency-profile';
			} elseif (substr($_SERVER['REQUEST_URI'], 0, 11) == "/dashboard/") {
				$classes[] = 'bbagency-dashboard';
			} elseif (substr($_SERVER['REQUEST_URI'], 0, 18) == "/profile-category/") {
				$classes[] = 'bbagency-category';
			} elseif (substr($_SERVER['REQUEST_URI'], 0, 18) == "/profile-register/") {
				$classes[] = 'bbagency-register';
			} elseif (substr($_SERVER['REQUEST_URI'], 0, 17) == "/profile-search/") {
				$classes[] = 'bbagency-search';
			} elseif (substr($_SERVER['REQUEST_URI'], 0, 15) == "/profile-print/") {
				$classes[] = 'bbagency-print';
			} else {
				$classes[] = 'bbagency';
			}
			return $classes;
		}


// *************************************************************************************************** //
/*
 * Add Rewrite Rules based on Path
 */
			
	add_filter('rewrite_rules_array','bb_agency_rewriteRules');
		// Adding a new rule
		function bb_agency_rewriteRules($rules) {
			$newrules = array();
			$newrules['profile-search/([0-9])$'] = 'index.php?type=search&paging=$matches[1]';
			$newrules['profile-search'] = 'index.php?type=search&target=results';
			$newrules['profile-category/(.*)/([0-9])$'] = 'index.php?type=category&target=$matches[1]&paging=$matches[2]';
			$newrules['profile-category/([0-9])$'] = 'index.php?type=category&paging=$matches[1]';
			$newrules['profile-category/(.*)$'] = 'index.php?type=category&target=$matches[1]';
			$newrules['profile-category'] = 'index.php?type=category&target=all';
			$newrules['profile-casting/(.*)$'] = 'index.php?type=casting&target=$matches[1]';
			$newrules['profile-casting'] = 'index.php?type=casting&target=casting'; 
			$newrules['profile-print'] = 'index.php?type=print';
			$newrules['profile-email'] = 'index.php?type=email';
			$newrules['dashboard'] = 'index.php?type=dashboard';
			$newrules['client-view/(.*)$'] = 'index.php?type=profilesecure&target=$matches[1]';
			$newrules['profile/(.*)/contact'] = 'index.php?type=profilecontact&target=$matches[1]';
			$newrules['profile/(.*)$'] = 'index.php?type=profile&target=$matches[1]';
			// model card
			$newrules['card/(.+)\.jpg$'] = 'index.php?type=card&target=$matches[1]';
			$newrules['lbda/(.+)\.jpg$'] = 'index.php?type=lbda&target=$matches[1]';
			
		    $newrules['version-bb-agency'] = 'index.php?type=rbv'; // ping this page for version checker
			
		    
			$bb_agency_option_profilelist_castingcart  = bb_agency_get_option('bb_agency_option_profilelist_castingcart');
			
			$bb_agency_option_profilelist_favorite	 = bb_agency_get_option('bb_agency_option_profilelist_favorite');
			
	        if ($bb_agency_option_profilelist_favorite) {
				$newrules['profile-favorite'] = 'index.php?type=favorite';
		  	}
	        if ($bb_agency_option_profilelist_castingcart) {
				$newrules['profile-casting-cart'] = 'index.php?type=castingcart';
		   	}

			// Set up pages
		   	// get data types
		   	$types = bb_agency_get_datatypes();
			foreach ($types as $type) {
				$newrules[$type->DataTypeTag] = 'index.php?type=models&value='.$type->DataTypeID;
			}

			return $newrules + $rules;
		}

		// *************************************************************************************************** //

		
	// Get Veriables & Identify View Type
	add_action( 'query_vars', 'bb_agency_query_vars' );
		function bb_agency_query_vars( $query_vars ) {
			$query_vars[] = 'type';
			$query_vars[] = 'target';
			$query_vars[] = 'paging';
			$query_vars[] = 'value';
			return $query_vars;
		}
	
	// Set Custom Template
	add_filter('template_include', 'bb_agency_template_include', 1, 1); 
		function bb_agency_template_include( $template ) {
			if ($type = get_query_var( 'type' )) {
				$dir = dirname(__FILE__) . '/theme/';
				switch ($type) {
					case 'search' :
						return $dir.'view-search.php'; 
					case "category" :
						return $dir.'view-category.php'; 
					case "profile" :
						return $dir.'view-profile.php'; 
					case "profilecontact" :
						return $dir.'view-profile-contact.php'; 
					case "profilesecure" :
						return $dir.'view-profilesecure.php'; 
					case "dashboard" :
						return $dir.'view-dashboard.php'; 
					case "print" :
						return $dir.'view-print.php'; 
					case "favorite" :
						return $dir.'view-favorite.php'; 
					case "casting" :
						return $dir.'view-castingcart.php';
					case 'models' :
						return $dir.'view-models.php';
					case 'card' :
						return $dir.'view-model-card.php';
					case 'lbda' :
						return $dir.'view-lbda-card.php';
					case "version-bb-agency" :
						return dirname(__FILE__) . '/rbv.php';
				}			  
			}
			return $template;
		}
	
	// Remember to flush_rules() when adding rules
	add_filter('init', 'bb_agency_flushrules');
		function bb_agency_flushRules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}	



// *************************************************************************************************** //
/*
 *  Errors & Alerts
 */

	// Create Message Wrapper
	function bb_agency_adminmessage_former($message, $errormsg = false) {
		if ($errormsg) {
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}
		echo "<p><strong>$message</strong></p></div>";
	} 

	/** 
	  * Call bb_agency_adminmessage() when showing other admin 
	  * messages. The message only gets shown in the admin
	  * area, but not on the frontend of your WordPress site. 
	  */
	add_action('admin_notices', 'bb_agency_adminmessage'); 
		function bb_agency_adminmessage() {

		    // Are Permalinks Enabled?
		    if ( get_option('permalink_structure') == '' ) {
		    	bb_agency_adminmessage_former('<a href="'. admin_url("options-permalink.php") .'">'. __("Permalinks", bb_agency_TEXTDOMAIN) .'</a> '. __("are not configured.  This will cause BB Agency not to function properly.", bb_agency_TEXTDOMAIN), true);
		    }

		}


// *************************************************************************************************** //
/*
 *  General Functions
 */



   /**
     * Clean String, remove extra quotes
     *
     * @param string $string
     */
	function bb_agency_cleanString($string) {
		// Remove trailing dingleberry
		if (substr($string, -1) == ",") {  $string = substr($string, 0, strlen($string)-1); }
		if (substr($string, 0, 1) == ",") { $string = substr($string, 1, strlen($string)-1); }
		// Just Incase
		$string = str_replace(",,", ",", $string);

		return $string;
	}


   /**
     * Identify Current Langauge
     *
     */
	function bb_agency_getActiveLanguage() {
		if (function_exists('icl_get_languages')) {
			// fetches the list of languages
		  	$languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR');
	
		  	$activeLanguage = 'en';
		
		  	// runs through the languages of the system, finding the active language
		  	foreach ($languages as $language) {

				// tests if the language is the active one
				if ($language['active'] == 1) {
				  	$activeLanguage = $language['language_code'];
				}
			  	return "/". $activeLanguage;
		  	}
		} else {
		  	return "";
		}
	}
	

   /**
     * Generate random number
     *
     */
	function bb_agency_random() {
		return preg_replace("/([0-9])/e","chr((\\1+112))",rand(100000,999999));
	}
	

   /**
     * Get users role
     *
     */
	function bb_agency_get_userrole() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};


   /**
     * Convert Date & time to UnixTimestamp
     *
     * @param string $datetime
     */
	function bb_agency_convertdatetime($datetime) {
		// Convert
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $time);
		
		$UnixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
		return $UnixTimestamp;
	}


   /**
     * Get Profile Name
     *
     * @param string $timestamp (unix timestamp)
     * @param string $offset  (offset from server)
     */
	function bb_agency_makeago($timestamp, $offset = null) {
		// get timezone offset
		if (is_null($offset)) {
			$offset = bb_agency_get_option('bb_agency_option_locationtimezone');
		}

		// Ensure the Timestamp is not null
		if (!empty($timestamp) && ($timestamp <> "0000-00-00 00:00:00") && ($timestamp <> "943920000")) {
			// Offset 
			// TODO: Remove hard coded server time
			//$offset = $offset-5;

			// Offset Math
			$timezone_offset = (int)$offset;
			$time_altered = time() -  $timezone_offset *60 *60;
			$difference = $time_altered - $timestamp;

			// Prepare Text
			// TODO: Add multi lingual
			$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
			$lengths = array("60","60","24","7","4.35","12","10");

			// Logic
			for($j = 0; $difference >= $lengths[$j]; $j++)
			$difference /= $lengths[$j];
			$difference = round($difference);
			if ($difference != 1) $periods[$j].= "s";
			$text = "$difference $periods[$j] ago";
			if ($j > 10) { exit; }

			return $text;
		} else {
			// If timestamp is blank, return non value
			return "--";
		}
	}

   /**
     * Get Profile's Due Date
     *
     * @param string $p_strDate
     */
	function bb_agency_get_due_date($p_strDate) {
		// Just display YYYY-MM-DD
		// TODO - could display date in a different way
		return $p_strDate;
	}

   /**
     * Get Profile's Age
     *
     * @param string $p_strDate
     */
	function bb_agency_get_age($p_strDate) {

		//Get Age Option if it should display with months included
		if (bb_agency_get_option('bb_agency_option_profilelist_bday') && bb_agency_get_option('bb_agency_option_profilelist_bday') == true) {
			
			list($Y,$m,$d) = explode("-",$p_strDate);
			$dob = "$d-$m-$Y";
			$localtime = getdate();
			$today = $localtime['mday']."-".$localtime['mon']."-".$localtime['year'];
			$dob_a = explode("-", $dob);
			$today_a = explode("-", $today);
			$dob_d = $dob_a[0];$dob_m = $dob_a[1];$dob_y = $dob_a[2];
			$today_d = $today_a[0];$today_m = $today_a[1];$today_y = $today_a[2];
			$years = $today_y - $dob_y;
			$months = $today_m - $dob_m;

			if ($today_m.$today_d < $dob_m.$dob_d) {
				$years--;
				$months = 12 + $today_m - $dob_m;
			}
		
			if ($today_d < $dob_d) {
				$months--;
			}

			$firstMonths=array(1,3,5,7,8,10,12);
			$secondMonths=array(4,6,9,11);
			$thirdMonths=array(2);

			if ($today_m - $dob_m == 1) {
				if (in_array($dob_m, $firstMonths)) 
				{
					array_push($firstMonths, 0);
				}
				elseif (in_array($dob_m, $secondMonths)) 
				{
					array_push($secondMonths, 0);
				}
				elseif (in_array($dob_m, $thirdMonths)) 
				{
					array_push($thirdMonths, 0);
				}
			}

			if ($months > 12) {
				$months = $months - 12;
				$years++;
			}
			
			if ($years == 0) {
			   $years = '';	
			} else {
			   $years = $years . " yr(s) "; 	
			}
		
			if ($months == 0) {
			   $months = '';	
			} else {
			   $months = $months . " mo(s) "; 	
			}

			return  $years . $months;		

		// Or just do it the old way
		} else {
				
			 list($Y,$m,$d) = explode("-",$p_strDate);
			 return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
		}

    }


   /**
     * Collapse White Space
     *
     * @param string $string
     */
	function bb_agency_collapseWhiteSpace($string) {
		return preg_replace('/\s+/', ' ', $string);
	}
	

   /**
     * Prepare string to be filename
     *
     * @param string $filename
     */
	function bb_agency_safenames($filename) {
		$filename = bb_agency_collapseWhiteSpace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		return strtolower($filename);
	}


   /**
     * Get Current User ID
     *
     */
	function bb_agency_get_current_userid() {
		global $current_user;
        get_currentuserinfo();
		return $current_user->ID;
	}


   /**
     * Get Filename Extension
     *
     * @param string $filename
     */
	function bb_agency_filenameextension($filename) {
		$pos = strrpos($filename, '.');
		if ($pos===false) {
			return false;
		} else {
			return substr($filename, $pos+1);
		}
	}
	

   /**
     * Format a string in proper case.
     *
     * @param string $string
     */
	function bb_agency_strtoproper($string) {
		return ucwords(strtolower($string));
	}
	

   /**
     * Generate Video Thumbnail
     *
     * @param string $videoID
     */
	function bb_agency_get_videothumbnail($videoID) {
		$videoID = ltrim($videoID);
		if (substr($videoID, 0, 23) == "http://www.youtube.com/") {
			$videoID = bb_agency_get_VideoID($videoID);
		} elseif (substr($videoID, 0, 7) == "<object") {
			$videoID = bb_agency_get_VideoFromObject($videoID);
		}
		$bb_agency_get_videothumbnail = "<img src='http://img.youtube.com/vi/" . $videoID . "/default.jpg' />";
		return $bb_agency_get_videothumbnail;
	}
	

   /**
     * Strip out VideoID from URL
     *
     * @param string $videoURL
     */
	function bb_agency_get_VideoID($videoURL) {
		if (substr($videoURL, 0, 23) == "http://www.youtube.com/") {
			$videoURL = str_replace("http://www.youtube.com/v/", "", $videoURL);
			$videoURL = str_replace("http://www.youtube.com/watch?v=", "", $videoURL);
			$videoURL = str_replace("&feature=search", "", $videoURL);
			$videoURL = str_replace("?fs=1&amp;hl=en_US", "", $videoURL);
			$videoID = $videoURL; // substr($videoURL, 25, 15);
		} else {
			$videoID = $videoURL;
		}
		return $videoID;
	}


   /**
     * Create embed code from URL
     *
     * @param string $videoObject
     */
	function bb_agency_get_VideoFromObject($videoObject) {
		if (substr(strtolower($videoObject), 0, 7) == "<object") {
			$videoObject = strip_tags($videoObject, '<embed>');
			//$videoObject = str_replace('<embed src="', '', $videoObject);
			$videoObject = substr($videoObject, 13);
			$videoObject = str_replace("http://www.youtube.com/v/", "", $videoObject);
			$videoObject_newend = strpos($videoObject, '?');
			$videoObject = substr($videoObject, 0, $videoObject_newend);
		} else {
			$videoObject = bb_agency_get_VideoID($videoObject);
		}
		return $videoObject;
	}


   /**
     * Check the directory (create if it doesnt exist)
     *
     * @param string $ProfileGallery
     */
    function bb_agency_checkdir($ProfileGallery) {

    	bb_agency_debug( __FUNCTION__ . " input $ProfileGallery" );
	      	
		if (! bb_agency_have_profile_dir( $ProfileGallery ) ) {
			bb_agency_create_profile_dir( $ProfileGallery );
		} 

		if (! bb_agency_have_profile_dir( $ProfileGallery ) ) {
			$finished = false;      
			$pos = 0;                 // we're not finished yet (we just started)
			while ( ! $finished ):                   // while not finished
			 	$pos++;
			  	$NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
			  	if ( ! bb_agency_have_profile_dir( $NewProfileGallery ) ) :  // if folder DOES NOT exist...
					bb_agency_create_profile_dir( $NewProfileGallery );
					$ProfileGallery = $NewProfileGallery;  // Set it to the new  thing
					$finished = true;                    // ...we are finished
			  	endif;
			endwhile;
		}

		bb_agency_debug( __FUNCTION__ . " return $ProfileGallery" );

		return $ProfileGallery;			
    }

    /**
     *
     * Do we have a profile gallery?
     *
     * @param string $ProfileGallery
     * @return boolean
     *
     */
	function bb_agency_have_profile_dir( $ProfileGallery ) {
		return is_dir(bb_agency_UPLOADPATH . $ProfileGallery);
	}

    /**
     *
     * Create profile gallery?
     *
     * @param string $ProfileGallery
     *
     */
    function bb_agency_create_profile_dir( $ProfileGallery ) {
    	mkdir(bb_agency_UPLOADPATH . $ProfileGallery, 0755);
		chmod(bb_agency_UPLOADPATH . $ProfileGallery, 0777);
    }

    /**
     * Check directory (do not create, just check)
     *
     * @param string $ProfileGallery
     */
    function bb_agency_createdir($ProfileGallery) {
		if (!is_dir(bb_agency_UPLOADPATH . $ProfileGallery)) {
			mkdir(bb_agency_UPLOADPATH . $ProfileGallery, 0755);
			chmod(bb_agency_UPLOADPATH . $ProfileGallery, 0777);
			// defensive return
			return $ProfileGallery;		
		} else {
			//defensive return
			return $ProfileGallery;		
		}
    }	
  
   /**
     * Generate Folder Name
     *
     * @param $ID - record id, $first = first name, $last - last name, $display - contact display
	 * @return - formatted folder name 
     */		
	function generate_foldername($ID = NULL, $first, $last, $display) {
		return bb_agency_safenames($first . "-". substr($last, 0, 1));
	}

   /**
     * List Categories
     *
     * @param array $atts 
     */
	function bb_agency_categorylist($atts, $content = NULL) {
		/*
		EXAMPLE USAGE: 

		if (function_exists('bb_agency_categorylist')) { 
			$atts = array('profilesearch_layout' => 'advanced');
			bb_agency_categorylist($atts); }

		*/

		// Set It Up	
		global $wp_rewrite, $wpdb;
		extract(shortcode_atts(array(
			"profilesearch_layout" => "advanced"
		), $atts));

		// Query
		$queryList = "SELECT dt.DataTypeID, dt.DataTypeTitle, dt.DataTypeTag, (SELECT COUNT(profile.ProfileID) FROM  ". table_agency_profile ." profile WHERE profile.ProfileIsActive = 1 ) AS CategoryCount FROM ". table_agency_data_type ." dt ORDER BY dt.DataTypeTitle ASC";
		$resultsList = $wpdb->get_results($queryList);
		$countList = count($resultsList);			

		// Loop through Results
		foreach ($resultsList as $row) {
			echo "<div class=\"profile-category\">\n";
			if ($DataTypeID == $row->DataTypeID) {
				echo "  <div class=\"name\"><strong>". $row->DataTypeTitle ."</strong> <span class=\"count\">(". $row->CategoryCount .")</span></div>\n";
			} else {
				echo "  <div class=\"name\"><a href=\"/profile-category/". $row->DataTypeTag ."/\">". $row->DataTypeTitle ."</a> <span class=\"count\">(". $row->CategoryCount .")</span></div>\n";
			}
			echo "</div>\n";
		}
		if ($countList < 1) {
			echo __("No Categories Found", bb_agency_TEXTDOMAIN);
		}
	}


   /**
     * List Profiles
     *
     * @param array $atts 
     */
	function bb_agency_profile_list($atts, $content = null) {

		// Get Preferences
		$bb_agency_option_privacy					 = bb_agency_get_option('bb_agency_option_privacy');
		$bb_agency_option_profilelist_count			 = bb_agency_get_option('bb_agency_option_profilelist_count');
		$bb_agency_option_profilelist_perpage		 = bb_agency_get_option('bb_agency_option_profilelist_perpage');
		$bb_agency_option_profilelist_sortby		 = bb_agency_get_option('bb_agency_option_profilelist_sortby');
		$bb_agency_option_layoutprofilelist		 	 = bb_agency_get_option('bb_agency_option_layoutprofilelist');
		$bb_agency_option_profilelist_expanddetails	 = bb_agency_get_option('bb_agency_option_profilelist_expanddetails');
		$bb_agency_option_locationtimezone 			 = bb_agency_get_option('bb_agency_option_locationtimezone');
		$bb_agency_option_profilelist_favorite		 = bb_agency_get_option('bb_agency_option_profilelist_favorite');
		$bb_agency_option_profilenaming				 = bb_agency_get_option('bb_agency_option_profilenaming');
		$bb_agency_option_profilelist_castingcart 	 = bb_agency_get_option('bb_agency_option_profilelist_castingcart');

		// Set It Up	
		global $wp_rewrite, $wpdb, $bb_agency_CURRENT_TYPE_ID;
	    $cusFields = array("Suit","Bust","Shirt","Dress");  //for custom fields min and max

	    // is this a print or pdf download page?
	    $isDownload = get_query_var('target') == "print" || get_query_var('target') == "pdf";
	    
	    // Exctract from Shortcode
		extract(shortcode_atts(
			array(
				"profileid" => null,
				"fname" => null,
				"lname" => null,
				"profilelocationcity" => null,
				"profiletype" => null,
				"type" => null,
				"profileisactive" => null,
				"profilegender" => null,
				"gender" => null,
				"profilestatheight_min" => null,
				"profilestatheight_max" => null,
				"profilestatweight_min" => null,
				"profilestatweight_max" => null,
				"dob_min" => null,
				"profiledatedue_min" => null,
				"age_from" => null,
				"age_to" => null,
				"dob_max" => null,
				"profiledatedue_max" => null,
				"featured" => null,
				"stars" => null,
				"paging" => null,
				"pagingperpage" => null,
				"override_privacy" => null,
				"profilefavorite" => null,
				"profilecastingcart" => null,
				"getprofile_saved" => null,
				"profilecity" => null,
				"profilestate" => null,
				"profilezip" => null
			), 
			$atts)
		);

		if (bb_agency_SITETYPE == 'bumps' && defined('bb_agency_MUMSTOBE_ID') && defined('bb_agency_BABIES_ID')) {
			// Settings for a Beautiful Bumps type site of pregnant women
			// Sort by due date if a mum to be, by date of birth if a baby, otherwise sort alphabetically
			switch(intval($type)) {
				case bb_agency_MUMSTOBE_ID:
				$sort = "profile.`ProfileDateDue`";
				$dir = "asc";
				break;

				case bb_agency_BABIES_ID:
				$sort = "profile.`ProfileDateBirth`";
				$dir = "desc";
				break;

				default:
				$sort = "profile.`ProfileContactDisplay`";
				$dir = "asc";
				break;
			}	
		} else {
			// Settings for a Kiddiwinks type site
			$sort = "profile.`ProfileDateBirth`";
			$dir = "desc";
		}

		// Should we override the privacy settings?
		if (strpos($pageURL, 'client-view') > 0 && (get_query_var('type') == "profilesecure")) {
			$OverridePrivacy = 1;
		}
	        
	    // Option to show all profiles
		if (isset($OverridePrivacy)) {
			// If sent link, show both hidden and visible
			$filter = "WHERE profile.`ProfileIsActive` IN (1, 4) AND media.`ProfileMediaType` = \"Image\" AND media.`ProfileMediaPrimary` = 1";
		} else {
			$filter = "WHERE profile.`ProfileIsActive` = 1 AND media.`ProfileMediaType` = \"Image\" AND media.`ProfileMediaPrimary` = 1";
		}

		// Legacy Field Names
		if (!empty($type)) { 
			$profiletype = $type; 
		}
		if (!empty($gender)) {  
			$profilegender = $gender; 
		}

		$ProfileID 					= $profileid;
		$ProfileContactNameFirst	= $fname;
		$ProfileContactNameLast    	= $lname;
		$ProfileLocationCity		= $profilelocationcity;
		$ProfileType				= $profiletype;
		$ProfileIsActive			= $profileisactive;
		$ProfileGender    			= $profilegender;
		$ProfileStatHeight_min		= $profilestatheight_min;
		$ProfileStatHeight_max		= $profilestatheight_max;
		$ProfileStatWeight_min		= $profilestatheight_min;
		$ProfileStatWeight_max		= $profilestatheight_max;
		$ProfileDateBirth_min		= $dob_min;
		$ProfileDateBirth_max		= $dob_max;
		$ProfileAge_min				= $age_from;
		$ProfileAge_max				= $age_to;
		$ProfileDateDue_min			= $dd_min;
		$ProfileDateDue_max			= $dd_max;
		$ProfileIsFeatured			= $featured;
		$ProfileIsPromoted			= $stars;
		$OverridePrivacy			= $override_privacy;
	  	$GetProfileSaved			= $getprofile_saved;
		$City						= $profilecity;
		$State						= $profilestate;
		$Zip						= $profilezip;  

		// set type global
		$bb_agency_CURRENT_TYPE_ID = $ProfileType;
	    
	    // ?
	   	$filterDropdown = array();

	   	// Set database tables
		$ProfileTable = table_agency_profile;
		$MediaTable = table_agency_profile_media;
		$CustomTable = table_agency_customfield_mux;

	    // Set CustomFields
	  	if (!empty($atts)) {
			$filter2 = '';

			foreach ($atts as $key => $val) {
				
	            if (substr($key,0,15) == "ProfileCustomID") {
	            		
                  /*
                   *  Check if this is array or not
                   *  because sometimes $val is an array so
                   *  array_filter is not applicable
                   */	
                  if ((!empty($val) AND !is_array($val)) OR (is_array($val) AND count(array_filter($val)) > 0)) {
                       
                        /*
                         * Id like to chop this one out and extract
                         * the array values from here and make it a string with "," or
                         * pass the single value back $val
                         */
                        if (is_array($val)) {
                          
                            if (count(array_filter($val)) > 1) {
                                $ct =1;
                                foreach ($val as $v) {
                                    if ($ct == 1) {
                                        $val = $v;
                                        $ct++;

                                    } else {
                                        $val = $val .",".$v;
                                    }
                                }
                            } else {
                                $val = array_shift(array_values($val));
                            } 
                        }
	    
                        $ProfileCustomType = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ". table_agency_customfields ." WHERE `ProfileCustomID` = %d", substr($key,15) ), ARRAY_A );
	
                            /*
                             * Have created a holder $filter2 and
                             * create its own filter here and change
                             * AND should be OR
                             */
                            if (in_array($ProfileCustomType['ProfileCustomTitle'], $cusFields)) {
                                $minVal=trim($_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_min']);
                                $maxVal=trim($_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_max']);
								if (!empty($minVal) && !empty($maxVal)) {
									if ($filter2 == "") {
										$filter2 .= " AND (( customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."' AND customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
									} else {
										$filter2 .= " OR (customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."' AND customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
									}
								}

                                //echo "-----";
                            } else {

                                /******************
                                1 - Text
                                2 - Min-Max > Removed
                                3 - Dropdown
                                4 - Textbox
                                5 - Checkbox
                                6 - Radiobutton
                                7 - Metrics/Imperials
                                8 - Date
                                *********************/

                                if ($ProfileCustomType["ProfileCustomType"] == 1) { //TEXT
                                    if ($filter2 == "") {
									    $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                    } else {
                                        $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                    }                                                           
                                    $_SESSION[$key] = $val;

                                } elseif ($ProfileCustomType["ProfileCustomType"] == 3) { // Dropdown

								   	if ($filter2=="") {
									 	$filter2 .=" AND (( customfield_mux.ProfileCustomValue IN('".$val."') and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
								   	} else {
								   		$filter2 .=" OR (customfield_mux.ProfileCustomValue IN('".$val."') and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
								   	}
                                           

                                } elseif ($ProfileCustomType["ProfileCustomType"] == 4) { //Textarea
                                    if ($filter2=="") {
                                        $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                    } else {
                                        $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                    } 
                                    $_SESSION[$key] = $val;


                                } elseif ($ProfileCustomType["ProfileCustomType"] == 5) { //Checkbox
                                    if (!empty($val)) {
                                        if (strpos($val,",") === false) {
                                           // $val = implode("','",explode(",",$val));
										  
                                            if ($filter2=="") {
											
                                                    $filter2 .= " AND  ((customfield_mux.ProfileCustomValue like('%".$val."%') and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
                                            } else {
                                                    $filter2 .= " OR  (customfield_mux.ProfileCustomValue like('%".$val."%')   and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
                                            }
                                        } else {
											
											$likequery = explode(",", $val);
											$likecounter = count($likequery);
											$i=1; 
											$likedata = '' ;
											foreach ($likequery as $like) {
												if ($i < ($likecounter-1)) {
													if ($like!="") {
														$likedata.= " customfield_mux.ProfileCustomValue like('%".$like."%')  OR "  ;
													}
													}else{
													if ($like!="") {
															$likedata.= " customfield_mux.ProfileCustomValue like('%".$like."%')  "  ;
													} 
												}
												$i++;
											}
											 
											
											$val = substr($val, 0, -1);
										    if ($filter2=="") {
                                                $filter2 .= " AND  ((( ".$likedata.") and customfield_mux.ProfileCustomID = '".substr($key,15)."' )";
                                            } else {
                                                $filter2 .= " OR  ((".$likedata.") and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                            }
                                        }

                                        $_SESSION[$key] = $val;
                                    } else {
                                        $_SESSION[$key] = '';
                                    }
                                } elseif ($ProfileCustomType["ProfileCustomType"] == 6) { //Radiobutton 
                                        //var_dump($ProfileCustomType["ProfileCustomType"]);
                                          // $val = implode("','",explode(",",$val));
                                    if ($filter2=="") {
                                        $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%')and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                    } else {
                                        $filter2 .= " or (customfield_mux.ProfileCustomValue like('%".$val."%')and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                    } 
                                    $_SESSION[$key] = $val;
                                       

                                }
                                elseif ($ProfileCustomType["ProfileCustomType"] == 7) { //Measurements 

                                	if (substr($key, -3) == 'min') {
                                		$name = str_replace('_min', '', $key);
                                		$id = substr($name, 15);
                                        if ($filter2=="") {
                                            $filter2  .= " AND (( customfield_mux.ProfileCustomValue >= '".$val."' AND customfield_mux.ProfileCustomID = '".$id."' )";
                                        } else {
                                            $filter2  .= " AND (customfield_mux.ProfileCustomValue >= '".$val."' AND customfield_mux.ProfileCustomID = '".$id."') ";

                                        }
                                	}
                                	elseif (substr($key, -3) == 'max') {
                                		$name = str_replace('_max', '', $key);
                                		$id = substr($name, 15);
                                        if ($filter2=="") {
                                            $filter2  .= " AND (( customfield_mux.ProfileCustomValue <= '".$val."' AND customfield_mux.ProfileCustomID = '".$id."' )";
                                        } else {
                                            $filter2  .= " AND (customfield_mux.ProfileCustomValue <= '".$val."' AND customfield_mux.ProfileCustomID = '".$id."') ";

                                        }
                                	}
                                	else {
                                        list($Min_val,$Max_val) = explode(",",$val);
										$Min_val = trim($Min_val);
										$Max_val = trim($Max_val);
                                        if (!empty($Min_val) && !empty($Max_val)) {
                                            if ($filter2=="") {
                                                    $filter2  .= " AND (( customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."' AND customfield_mux.ProfileCustomID = '".substr($key,15)."' )";
                                            } else {
                                                    $filter2  .= " OR (customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."' AND customfield_mux.ProfileCustomID = '".substr($key,15)."') ";

                                            }
                                        	$_SESSION[$key] = $val;
                                        }                                   		
                                	}
                                }
                                elseif ($ProfileCustomType["ProfileCustomType"] == 8) { //Dates

                                    if ($filter2=="") {
                                        $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%')and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                    } else {
                                        $filter2 .= " or (customfield_mux.ProfileCustomValue like('%".$val."%')and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                    } 
                                    $_SESSION[$key] = $val;
                                }
                            }	
							
					} // if not empty
				}  // end if
		    } // end for each
		  	                    
           /*
            * Refine filter and add the created 
            * holder $filter to $filter if not
            * equals to blanks
            */
           	if ($filter2 != "") {
            	$filter2 .= " ) ";
            	$filter .= $filter2;
        	}
		}

	  	// Name
		if (!empty($ProfileContactNameFirst)) {
			$ProfileContactNameFirst = $ProfileContactNameFirst;
			$filter .= " AND profile.ProfileContactNameFirst LIKE '". $ProfileContactNameFirst ."%'";
		}
		if (!empty($ProfileContactNameLast)) {
			$ProfileContactNameLast = $ProfileContactNameLast;
			$filter .= " AND profile.ProfileContactNameLast LIKE '". $ProfileContactNameLast ."%'";
		}
		
		// Type
		if (!empty($ProfileType)) {
			$ProfileType = $ProfileType;
			$filter .= " AND FIND_IN_SET(". $ProfileType .", profile.ProfileType) ";
		} else {
			$ProfileType = '';
		}		
		
		// Profile Search Saved 
		if (!empty($GetProfileSaved)) {
			$filter .= " AND profile.ProfileID IN(".$GetProfileSaved.") ";
		}

		// Gender			
		if (!empty($ProfileGender)) {
			$filter .= " AND profile.ProfileGender='".$ProfileGender."'";
		} else {
			$ProfileGender = '';
		}

		// Age
        if (!empty($ProfileAge_min) || !empty($ProfileAge_max)) {
        	if (!empty($ProfileAge_min)) {
	            $age = str_replace('m', '', $ProfileAge_min);
	            $ym = (strpos($ProfileAge_min, 'm') === false) ? 'YEAR' : 'MONTH';
	            $filter .= " AND profile.`ProfileDateBirth` <= DATE_SUB(NOW(), INTERVAL $age $ym)";
	        }
	        
	        if (!empty($ProfileAge_max)) {
	            $age = str_replace('m', '', $ProfileAge_max);
	            $ym = (strpos($ProfileAge_max, 'm') === false) ? 'YEAR' : 'MONTH';
	            $filter .= " AND profile.`ProfileDateBirth` >= DATE_SUB(NOW(), INTERVAL $age $ym)";
	        }
        	$sort = '`ProfileDateBirth`';
        	$dir = 'ASC';
        }

		$date = gmdate('Y-m-d', time() + $bb_agency_option_locationtimezone *60 *60);
		if (!empty($ProfileDateBirth_min)) {
			$selectedYearMin = date('Y-m-d', strtotime('-'. $ProfileDateBirth_min .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
		}
		if (!empty($ProfileDateBirth_max)) {
			$selectedYearMax = date('Y-m-d', strtotime('-'. $ProfileDateBirth_max - 1 .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
		}
		
		// Date of birth
		if (!empty($ProfileDateBirth_min)) {
			$filter .= " AND profile.ProfileDateBirth >= '$ProfileDateBirth_min'";
		}
		if (!empty($ProfileDateBirth_max)) {
			$filter .= " AND profile.ProfileDateBirth <= '$ProfileDateBirth_max'";
		}

		if (bb_agency_SITETYPE == 'bumps') {
			// Due date
			if (!empty($ProfileDateDue_min)) {
				$filter .= " AND profile.ProfileDateDue >= '$ProfileDateDue_min'";
			}
			if (!empty($ProfileDateDue_max)) {
				$filter .= " AND profile.ProfileDateDue <= '$ProfileDateDue_max'";
			}
		}

		if (isset($ProfileIsFeatured)) {
			$filter .= " AND profile.ProfileIsFeatured = '1' ";
		}		
	    if (isset($ProfileIsPromoted)) {
			$filter .= " AND profile.ProfileIsPromoted = '1' ";
		}
		
		// City
		if (!empty($City)) {
			$filter .= " AND profile.ProfileLocationCity = '". ucfirst($City) ."'";
		}

		// State
		if (!empty($State)) {
			$filter .= " AND profile.ProfileLocationState = '". ucfirst($State) ."'";
		}

		// Zip
		if (!empty($Zip)) {
			$filter .= " AND profile.ProfileLocationZip = '". ucfirst($Zip) ."'";
		}

		// Can we show the profiles?
		// P R I V A C Y FILTER ====================================================
		if (bb_agency_SITETYPE == 'bumps' || is_user_logged_in()) {
		// P R I V A C Y FILTER ====================================================

			if (!$isDownload) {
				
				if (isset($profilecastingcart)) {   // to tell print and pdf generators its for casting cart and new link
					$atts["type"] = "casting";
					$addtionalLink = '&nbsp;|&nbsp;<a id="sendemail" href="javascript:">Email to Admin</a>';
				}
				
				# print, downloads links to be added on top of profile list
				$links='<div class="bblinks">';
				  
		       /*
				* Set Print / PDF in Settings
				*/
				if (get_query_var('target') != "results" && bb_agency_get_option('bb_agency_option_profilelist_printpdf')) {// hide print and download PDF in Search result
					$links.='
					<div class="bbprint-download">
				  		<a target="_blank" href="'.get_bloginfo('wpurl').'/profile-category/print/?gd='.$atts["gender"].'&ast='.$atts["age_from"].'&asp='.$atts["age_to"].'&t='.$atts["type"].'">Print</a></a>&nbsp;|&nbsp;<a target="_blank" href="'.get_bloginfo('wpurl').'/profile-category/pdf/?gd='.$atts["gender"].'&ast='.$atts["age_from"].'&asp='.$atts["age_to"].'&t='.$atts["type"].'">Download PDF</a>'.$addtionalLink.'
				  	</div><!-- .bbprint-download -->';
				}
				  
				$links.='<div class="bbfavorites-castings">';

				if (is_permitted("favorite")) {
					if (bb_agency_get_option('bb_agency_option_profilelist_favorite')==1) {
							$links.='<a href="'.get_bloginfo('siteurl').'/profile-favorite/">'.__("View Favorites", bb_agency_TEXTDOMAIN).'</a>';
					}
				}

				if (is_permitted("casting")) {
					if ($_SERVER['REQUEST_URI']!="/profile-casting/") {
						if (bb_agency_get_option('bb_agency_option_profilelist_castingcart')==1) {
							if (bb_agency_get_option('bb_agency_option_profilelist_favorite')==1) {
								$links.='&nbsp;|&nbsp;';
							}
							$links.='<a href="'.get_bloginfo('siteurl').'/profile-casting/">'.__("Casting Cart", bb_agency_TEXTDOMAIN).'</a>';
						}
					}
				}    
				$links.='</div><!-- .bbfavorites-castings -->
				</div><!-- .bblinks -->';			
			}
		
		  	//remove  if its just for client view of listing via casting email
		 	if (get_query_var('type') == "profilesecure") { 
		 		$links="";
		 	}

			if (get_query_var('type') == "favorite") { 
				$links="";
			} // we dont need print and download pdf in favorites page
			
			echo "<div class=\"bbclear\"></div>\n";
			echo "$links<div id=\"profile-results\">\n";
			
		 	if (!$isDownload) { //if its printing or PDF no need for pagination below
		  
				/*********** Paginate **************/
				$limit = isset($limit) ? $limit : '';
				$sql = <<<EOF
SELECT
profile.`ProfileID` as pID, 
profile.*,
media.`ProfileMediaURL`,
customfield_mux.*  
FROM $ProfileTable AS profile
LEFT JOIN $MediaTable AS media 
ON profile.`ProfileID` = media.`ProfileID` AND media.`ProfileMediaType` = "Image" AND media.`ProfileMediaPrimary` = 1
LEFT JOIN $CustomTable AS customfield_mux 
ON profile.`ProfileID` = customfield_mux.`ProfileID`  
$filter  
GROUP BY profile.`ProfileID` 
ORDER BY $sort $dir $limit
EOF;

				$qItem = $wpdb->get_results($sql, ARRAY_A);
				$items = count($qItem); // number of total rows returned
				$limit = ''; // pagination not working here
	  
	  		}//if (get_query_var('target')!="print" 
					  
			/*
			 * check permissions
			 */
			$sqlFavorite_userID='';
			$sqlCasting_userID='';
            if (is_permitted('casting')) {
                    // Casting Cart 
		      	    $sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID   AND cart.CastingCartProfileID = '".bb_agency_get_current_userid()."'  ";
			} 
            if (is_permitted('favorite')) {
                    // Display Favorites 
		            $sqlFavorite_userID  = " fav.SavedFavoriteTalentID = profile.ProfileID  AND fav.SavedFavoriteProfileID = '".bb_agency_get_current_userid()."' ";
            } 

			/*
			 * Execute the Query
			 */
			if (!empty($profilefavorite)) {
				// Execute query showing favorites
				$queryList = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileDateDue, profile.ProfileLocationState, profile.ProfileID as pID, fav.SavedFavoriteTalentID, fav.SavedFavoriteProfileID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite." fav WHERE $sqlFavorite_userID AND profile.ProfileIsActive = 1 GROUP BY fav.SavedFavoriteTalentID";
				
			} elseif (!empty($profilecastingcart)) {
				// There is a Casting Cart ID present

				// Get User ID
				$user = get_userdata(bb_agency_get_current_userid());  
				
				// check if user is admin, if yes this allow the admin to view other users cart 
				if ($user->user_level==10 AND get_query_var('target')!="casting") {
					$sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID AND cart.CastingCartProfileID = '".get_query_var('target')."' ";
				}

				// Execute the query showing casting cart
				$queryList = "SELECT profile.`ProfileID`, profile.`ProfileGallery`, profile.`ProfileContactDisplay`, profile.`ProfileDateBirth`, profile.`ProfileDateDue`, profile.`ProfileLocationState`, profile.`ProfileID` as pID, cart.`CastingCartTalentID`, (SELECT media.`ProfileMediaURL` FROM ". table_agency_profile_media ." media WHERE profile.`ProfileID` = media.`ProfileID` AND media.`ProfileMediaType` = \"Image\" AND media.`ProfileMediaPrimary` = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_castingcart." cart WHERE $sqlCasting_userID AND `ProfileIsActive` = 1 GROUP BY profile.`ProfileID`";  
				 
			} elseif ($fastload) {
				// Execute Query in slim down mode, only return name, face and link
				$ProfileGivenBirth = defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID ? "IF(DATE(profile.ProfileDateDue) < CURDATE(),'yes','no') AS ProfileGivenBirth," : '';

				$queryList = "
					SELECT 
						profile.ProfileID,
						profile.ProfileID as pID, 
						profile.ProfileGallery,
						profile.ProfileContactDisplay, 
						profile.ProfileType,
						$ProfileGivenBirth
						(   SELECT media.ProfileMediaURL 
							FROM $MediaTable media 
							WHERE profile.ProfileID = media.ProfileID 
								AND media.ProfileMediaType = \"Image\" 
								AND media.ProfileMediaPrimary = 1
						) 
						AS ProfileMediaURL 
					FROM $ProfileTable profile 
					LEFT JOIN $CustomTable 
						AS customfield_mux 
						ON profile.ProfileID = customfield_mux.ProfileID  
						$filter  
					GROUP BY profile.ProfileID 
					ORDER BY $sort $dir $limit";

			} else {
				// Execute Query   removed profile.*,
				$queryList = "
				SELECT 
					profile.ProfileID,
					profile.ProfileID as pID, 
					profile.ProfileGallery,
					profile.ProfileContactDisplay, 
					profile.ProfileDateBirth, 
					profile.ProfileDateDue, 
					profile.ProfileLocationState,
					profile.ProfileType,
					$ProfileGivenBirth
					customfield_mux.ProfileCustomMuxID, customfield_mux.ProfileCustomMuxID, customfield_mux.ProfileCustomID, customfield_mux.ProfileCustomValue,  
					media.ProfileMediaURL
				FROM $ProfileTable profile 
				LEFT JOIN $MediaTable 
					AS media 
					ON profile.ProfileID = media.ProfileID 
				LEFT JOIN $CustomTable 
					AS customfield_mux 
					ON profile.ProfileID = customfield_mux.ProfileID  
				$filter  
				    AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1
				GROUP BY profile.ProfileID 
				ORDER BY $sort $dir $limit";
			}
			
			$bb_agency_option_profilenaming = bb_agency_get_option('bb_agency_option_profilenaming');

			$resultsList = $wpdb->get_results($queryList, ARRAY_A);
			$countList = count($resultsList);
	                
			$bb_user_isLogged = is_user_logged_in();

			if ($countList > 0) {

				// scale images in the browser
			  	$displayHTML ="	<script type='text/javascript' src='".bb_agency_BASEDIR."js/resize.js'></script>";

		        $profileDisplay = 0;
				$countFav = 0;
				foreach ($resultsList as $row) {
					// check due date to make sure she hasn't already given birth

					if (defined('bb_agency_MUMSTOBE_ID') && defined('bb_agency_AFTERBIRTH_ID') && 
						bb_agency_ismumtobe($row['ProfileType']) && $row['ProfileGivenBirth'] === "yes") {

						// switch category
						$ptypes = explode(',', $row['ProfileType']);

						for($i = 0; $i < count($ptypes); $i++) {
							if ($ptypes[$i] == bb_agency_MUMSTOBE_ID)
								$ptypes[$i] = bb_agency_AFTERBIRTH_ID;
						}
						
						// recategorize as family
						bb_agency_recategorize_profile($row['ProfileID'], $ptypes);
						
						if (bb_agency_ismumtobe($type)) {
							continue; // don't display this one as she's nolonger pregnant
						}	
					}
					$profileDisplay++;
					if ($profileDisplay == 1 ) {
						 
						/*********** Show Count/Pages **************/
						 $displayHTML .= "  <div id=\"profile-results-info\" class=\"six column\">\n";
							
						if ($bb_agency_option_profilelist_count) {
							if (empty($profilefavorite) && empty($profilecastingcart)) {  
								$displayHTML .= "    <div id=\"profile-results-info-countrecord\">\n";
								$displayHTML .="    	". __("Displaying", bb_agency_TEXTDOMAIN) ." <strong>". $countList ."</strong> ". __("of", bb_agency_TEXTDOMAIN) ." ". $items ." ". __(" records", bb_agency_TEXTDOMAIN) ."\n";
								$displayHTML .="    </div>\n";
							}				
						}

						$displayHTML.="  </div><!-- #profile-results-info -->\n";
						$displayHTML.="  <div class=\"bbclear\"></div>\n";
					}	           
					
					if ($profileDisplay == 1) {
						$displayHTML.="  <div id=\"profile-list\">\n";
					}
					$displayHTML .= "<div id=\"bbprofile-".$row["ProfileID"]."\" class=\"bbprofile-list profile-list-layout0\" >\n";

					if (isset($row["ProfileMediaURL"]) ) { // && (file_exists(bb_agency_UPLOADDIR ."". $row["ProfileGallery"] ."/". $row["ProfileMediaURL"])) ) {
						
						//dont need other image for hover if its for print or pdf download view and dont use timthubm
						if (!$isDownload) {
									 
							if (bb_agency_get_option('bb_agency_option_profilelist_thumbsslide') == 1) {  //show profile sub thumbs for thumb slide on hover
								$images = getAllImages($row["ProfileID"]);
							    $images = str_replace("{PHOTO_PATH}",bb_agency_UPLOADDIR ."". $row["ProfileGallery"]."/",$images);
							}

							// display thumbnail image
							//$displayHTML .="<div class=\"image\">"."<a href=\"". bb_agency_PROFILEDIR ."". $row["ProfileGallery"] ."/\"><img src=\"".bb_agency_BASEDIR."tasks/timthumb.php?src=".bb_agency_UPLOADDIR ."". $row["ProfileGallery"] ."/". $row["ProfileMediaURL"]."&w=200&h=300&zc=1\" id=\"roll".$row["ProfileID"]."\"  /></a>".$images."</div>\n";

							$displayHTML .= '<div class="image">';
							if (is_user_logged_in() || !bb_agency_datatype_privacy($row['ProfileType'])) {
								$displayHTML .= '<a href="'. bb_agency_PROFILEDIR . $row["ProfileGallery"] .'">';
								$displayHTML .= '<img src="'.bb_agency_get_thumbnail_url($row["ProfileGallery"] .'/'. $row["ProfileMediaURL"], 200).'" id="roll'.$row['ProfileID'].'" />';
								$displayHTML .= '</a>';
								$displayHTML .= $images;
							}
							$displayHTML .= '</div>'."\n";

						} else {
							// a download
							$displayHTML .="<div  class=\"image\">"."<a href=\"". bb_agency_PROFILEDIR ."". $row["ProfileGallery"] ."/\" style=\"background-image: url(".bb_agency_UPLOADDIR ."". $row["ProfileGallery"] ."/". $row["ProfileMediaURL"].")\"></a>".$images."</div>\n";
						}
					
					} else {
					 	$displayHTML .= "  <div class=\"image image-broken\"><a href=\"". bb_agency_PROFILEDIR ."". $row["ProfileGallery"] ."/\">No Image</a></div>\n";
					}
						
					$displayHTML .= "  <div class=\"profile-info\">\n";
					
			
					$displayHTML .= "     <h3 class=\"name\"><a href=\"". bb_agency_PROFILEDIR ."". $row["ProfileGallery"] ."/\" class=\"scroll\">". stripslashes($row["ProfileContactDisplay"]) ."</a></h3>\n";

					if ($bb_agency_option_profilelist_expanddetails) {
						echo "expanded details";
					 	$displayHTML .= "     <div class=\"details\"><span class=\"details-age\">". bb_agency_get_age($row["ProfileDateBirth"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $row["ProfileLocationState"] ."</span></div>\n";
					 	/*
					 	TODO - get due date if applicable
					 	$displayHTML .= "     <div class=\"details\"><span class=\"details-due\">". bb_agency_get_age($row["ProfileDateDue"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $row["ProfileLocationState"] ."</span></div>\n";
					 	*/
					}
					
		         	//echo "loaded: ".microtime()." ms";				
					if ($bb_user_isLogged ) {
					   	//Get Favorite & Casting Cart links
				        $displayHTML .= bb_agency_get_miscellaneousLinks($row["ProfileID"]);
					}

					$displayHTML .=" </div> <!-- .profile-info --> \n";
					$displayHTML .=" </div><!-- .bbprofile-list -->\n";
				}	// endwhile datalist

				$displayHTML .= "  <div class=\"bbclear\"></div>\n";
				$displayHTML .= "  </div><!-- #profile-list -->\n";		
			}	// endif countlist
			else {
				/*
				 *  There are no profiles returned.  Display empty message
				 */
				$displayHTML .= sprintf(__('Sorry, none of our current models matched your search requirements but you can <a href="%s" title="advanced search">click here</a> to search again.', bb_agency_TEXTDOMAIN), get_bloginfo('wpurl').'/search/?srch=1');
				$displayHTML .= sprintf(__(' Alternatively, please <a href="%s">contact us</a> as we are accepting new models onto our books every day.', bb_agency_TEXTDOMAIN), get_bloginfo('wpurl').'/contact');
			}
		
			// Close Formatting
			$displayHTML .= "  <div class=\"bbclear\"></div>\n";
			$displayHTML .= "</div><!-- #profile-results -->\n";
				
		} else {
			if ($bb_agency_option_privacy == 3 && is_user_logged_in() && !is_client_profiletype()) {
				echo "<h2>This is a restricted page for clients only.</h2>";
			} else {
				include("theme/include-login.php"); 	
			} 	
		}
				
	  	echo  $displayHTML;

		// debug mode
		//  bb_agency_checkExecution();
	    //add the thumbs slides on hover of profile listing
		echo "<script type=\"text/javascript\" src=\"". bb_agency_BASEDIR ."js/thumbslide.js\"></script>\n"; 
		echo "<script type=\"text/javascript\" src=\"". bb_agency_BASEDIR ."js/textscroller.js\"></script>\n"; 
		echo "<script type=\"text/javascript\" src=\"". bb_agency_BASEDIR ."js/image-resize.js\"></script>\n";
			   
		//load javascript for add to casting cart	
		if (!$isDownload && get_query_var('type') != "profilesecure" && !isset($profilecastingcart)) : ?>
			<script>
	            function addtoCart(pid) {
					var qString = 'usage=addtocart&pid=' +pid;
					var apid = "addtocart"+pid;
					$.post('<?php echo bb_agency_BASEDIR ?>theme/sub_db_handler.php', qString, processResponseAddtoCart);
					//document.getElementById(pid).style.display="none";
					// document.getElementById(apid).style.backgroundPosition="0 -134px;";
					}
				function processResponseAddtoCart(data) {			
				}
	    	</script>
		<?php endif; //end if (get_query_var
	}
		
	//get profile images
	function getAllImages($profileID) {
		global $wpdb;
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE `ProfileID` =  \"". $profileID ."\" AND `ProfileMediaType` = \"Image\" ORDER BY `ProfileMediaPrimary` DESC LIMIT 0, 7 ";
		$resultsImg = $wpdb->get_results($queryImg);
		if (count($resultsImg)) {
			foreach ($resultsImg as $dataImg) {
			 	$images[] = "<img class=\"roll\" src=\"".bb_agency_BASEDIR."/tasks/timthumb.php?src={PHOTO_PATH}". $dataImg->ProfileMediaURL ."&w=148&zc=2\" alt='' style='width:148px'   />\n";
			}
		}
		return implode("\n", $images);
	}

	/**
	 *
	 * get thumbnail url
	 *
	 * @param string $image
	 * @param int $w width
	 * @param int $h height
	 * @param int $zc zoom/crop setting for timthumb
	 * @return string
	 *
	 */
	function bb_agency_get_thumbnail_url($image, $w = 200, $h = 300, $zc = false)
	{
		$url = bb_agency_BASEDIR . 'tasks/timthumb.php?src=' . bb_agency_UPLOADDIR . $image;
		if ($w)
			$url .= '&w='.$w;

		if ($h)
			$url .= '&h='.$h;

		if ($zc !== false)
			$url .= '&zc='.$zc;

		return $url;
	}
		
		
	// Profile List
	function bb_agency_profilefeatured($atts, $content = NULL) {

		/*
		if (function_exists('bb_agency_profilefeatured')) { 
			$atts = array('count' => 8, 'type' => 0);
			bb_agency_profilefeatured($atts); }
		*/

		// Set It Up	
		global $wp_rewrite, $wpdb;
		extract(shortcode_atts(array(
				"type" => 0,
				"count" => 1
		), $atts));
		if ($type == 1) { // Featured
			$sqlWhere = " AND profile.ProfileIsPromoted=1";
		}
		echo "<div id=\"profile-featured\">\n";
		/*********** Execute Query **************/
		// Execute Query
			$queryList = "SELECT profile.*,

			(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
			 WHERE profile.ProfileID = media.ProfileID 
			 AND media.ProfileMediaType = \"Image\" 
			 AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
			
			 FROM ". table_agency_profile ." profile 
	 		 WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
			 AND profile.ProfileIsFeatured = 1  
			 ORDER BY RAND() LIMIT 0,$count";

		
		$resultsList = $wpdb->get_results($queryList);
		$countList = count($resultsList);
		foreach ($resultsList as $row) {
		    echo "<div class=\"bbprofile-list\">\n";
			if (isset($row->ProfileMediaURL) ) { 
			echo "  <div class=\"image\"><a href=\"". bb_agency_PROFILEDIR ."". $row->ProfileGallery ."/\"><img src=\"". bb_agency_UPLOADDIR ."". $row->ProfileGallery ."/". $row->ProfileMediaURL ."\" /></a></div>\n";
			} else {
			echo "  <div class=\"image image-broken\"><a href=\"". bb_agency_PROFILEDIR ."". $row->ProfileGallery ."/\">No Image</a></div>\n";
			}
			echo "<div class=\"profile-info\">";
			$ProfileContactDisplay = $row->ProfileContactNameFirst . " ". substr($row->ProfileContactNameLast, 0, 1);
				 
			echo "     <h3 class=\"name\"><a href=\"". bb_agency_PROFILEDIR ."". $row->ProfileGallery ."/\">". $ProfileContactDisplay ."</a></h3>\n";
			if (isset($bb_agency_option_profilelist_expanddetails)) {
				echo "<div class=\"details\"><span class=\"details-age\">". bb_agency_get_age($row->ProfileDateBirth) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $row->ProfileLocationState ."</span></div>\n";
			}

			if (is_user_logged_in()) {
				// Add Favorite and Casting Cart links		
				bb_agency_get_miscellaneousLinks($row->ProfileID);
			}
			echo "  </div><!-- .profile-info -->\n";
			echo "</div><!-- .bbprofile-list -->\n";
		}
		if ($countList < 1) {
			echo __("No Featured Profiles", bb_agency_TEXTDOMAIN);
		}
		echo "  <div style=\"clear: both; \"></div>\n";
		echo "</div><!-- #profile-featured -->\n";
	}
		



	// Profile Search
	function bb_agency_profilesearch($atts, $content = NULL) {
		// Get Privacy Information
		
		$bb_agency_option_privacy = bb_agency_get_option('bb_agency_option_privacy');
		// Set It Up	
		global $wp_rewrite;
		extract(shortcode_atts(array(
				"profilesearch_layout" => "advanced"
		), $atts));
		
		if ( ($bb_agency_option_privacy > 1 && is_user_logged_in()) || ($bb_agency_option_privacy < 2) ) {
		 	$isSearchPage = 1;
		 	
			//echo "<div id=\"profile-search-form-embed\">\n";
				include("theme/include-profile-search.php"); 	
			//echo "</div>\n";
			
			//echo '<a href="'.get_bloginfo("wpurl").'/search/">'.__("Search again", bb_agency_TEXTDOMAIN).'</a>';
		} else {
			//include("theme/include-login.php"); 	
		}
	}

	// Display human readable date
	function bb_agency_displaydate($date) {
		$timestamp = strtotime($date);
		return date("jS F Y", $timestamp);
	}

	// has given date already passed?
	function bb_agency_datepassed($date) {
		return strtotime($date) < time();	
	}

	/*
	 * is mum to be?
	 *
	 * Is this model a mum to be?
	 *
	 * @param int $type ProfileType
	 * @return boolean
	 *
	 */
	function bb_agency_ismumtobe($type) {
		$types = explode(',',$type);
		return in_array(bb_agency_MUMSTOBE_ID, $types);
	}

	// Is this model a family?
	function bb_agency_isfamily($type) {
		$types = explode(',',$type);
		return in_array(bb_agency_AFTERBIRTH_ID, $types);
	}

	// Is this model a baby?
	function bb_agency_isbaby($type) {
		$types = explode(',',$type);
		return in_array(bb_agency_BABIES_ID, $types);
	}





// *************************************************************************************************** //
// Image Resizing 
class bb_agency_image {
 
	var $image;
	var $image_type;

	function load($filename) {

		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ( $this->image_type == IMAGETYPE_JPEG ) {

			$this->image = imagecreatefromjpeg($filename);
		} elseif ( $this->image_type == IMAGETYPE_GIF ) {

			$this->image = imagecreatefromgif ($filename);
		} elseif ( $this->image_type == IMAGETYPE_PNG ) {

			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=NULL) {

		if ( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image, $filename, $compression);
		} elseif ( $image_type == IMAGETYPE_GIF ) {

			imagegif ($this->image,$filename);
		} elseif ( $image_type == IMAGETYPE_PNG ) {

			imagepng($this->image,$filename);
		}
		if ( $permissions != NULL) {

			chmod($filename,$permissions);
		}
	}

	function output($image_type=IMAGETYPE_JPEG) {

		if ( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif ( $image_type == IMAGETYPE_GIF ) {

			imagegif ($this->image);
		} elseif ( $image_type == IMAGETYPE_PNG ) {

			imagepng($this->image);
		}
	}

	function getWidth() {

		return imagesx($this->image);
	}

	function getHeight() {

		return imagesy($this->image);
	}

	function resizeToHeight($height) {

		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}

	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width,$height);
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100;
		$this->resize($width,$height);
	}

	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}      

	function orientation() {
		if ($this->getWidth() == $this->getHeight()) {
			return "square";
		} elseif ($this->getWidth() > $this->getHeight()) {
			return "landscape";
		} else {
			return "portrait";
		}
	}
 
 
}

// *************************************************************************************************** //
// Pagination
class bb_agency_pagination {

	/*Default values*/
	var $total_pages = -1; //items
	var $limit = NULL;
	var $target = ''; 
	var $page = 1;
	var $adjacents = 2;
	var $showCounter = false;
	var $className = "bbpagination";
	var $parameterName = "page";
	var $urlF = false;//urlFriendly
	/*Buttons next and previous*/
	var $nextT = "Next";
	var $nextI = "&#187;"; //&#9658;
	var $prevT = "Previous";
	var $prevI = "&#171;"; //&#9668;
	/*****/
	var $calculate = false;
	
	#Total items
	function items($value) {
		$this->total_pages = (int) $value;
	}
	
	#how many items to show per page
	function limit($value) {
		$this->limit = (int) $value;
	}
	
	#Page to sent the page value
	function target($value) {
		$this->target = $value;
	}
	
	#Current page
	function currentPage($value) {
		$this->page = (int) $value;
	}
	
	#How many adjacent pages should be shown on each side of the current page?
	function adjacents($value) {
		$this->adjacents = (int) $value;
	}
	
	#show counter?
	function showCounter($value="") {
		$this->showCounter=($value===true) ? true : false;
	}
	#to change the class name of the pagination div
	function changeClass($value="") {
		$this->className=$value;
	}
	function nextLabel($value) {
		$this->nextT = $value;
	}
	function nextIcon($value) {
		$this->nextI = $value;
	}
	function prevLabel($value) {
		$this->prevT = $value;
	}
	function prevIcon($value) {
		$this->prevI = $value;
	}
	#to change the class name of the pagination div
	function parameterName($value="") {
		$this->parameterName = $value;
	}
	#to change urlFriendly
	function urlFriendly($value="%") {
		if (eregi('^ *$',$value)) {
			$this->urlF=false;
			return false;
		}
		$this->urlF = $value;
	}
	
	var $pagination;
	
	function pagination() {}

	function show() {
		if (!$this->calculate)
			if ($this->calculate())
				echo "<div class=\"$this->className\">$this->pagination</div>\n";
	}

	function getOutput() {
		if (!$this->calculate)
			if ($this->calculate())
				return "<div class=\"$this->className\">$this->pagination</div>\n";
	}

	function get_pagenum_link($id) {
		if (substr($this->target, 0, 9) == "admin.php") {
			// We are in Admin
		
			if (strpos($this->target,'?') === false) {
				if ($this->urlF) {
					return str_replace($this->urlF,$id,$this->target);
				} else {
					return "$this->target?$this->parameterName=$id";
				}
			} else {
					return "$this->target&$this->parameterName=$id";
			}
		
		} else {
			
			// We are in Page		
			preg_match('/[0-9]$/', $this->target, $matches, PREG_OFFSET_CAPTURE);
			if ($matches[0][1] > 0) {
				return substr($this->target, 0, $matches[0][1]) ."/$id/";
			} else {
				return "$this->target/$id/";
			}
			
		} // End Admin/Page Toggle
	}
	
	function calculate() {
		$this->pagination = '';
		$this->calculate == true;
		$error = false;
		if ($this->urlF and $this->urlF != '%' and strpos($this->target,$this->urlF)===false) {
				//Es necesario especificar el comodin para sustituir
				echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
				$error = true;
			}elseif ($this->urlF and $this->urlF == '%' and strpos($this->target,$this->urlF)===false) {
				echo "Es necesario especificar en el target el comodin % para sustituir el número de página<br />";
				$error = true;
			}
		if ($this->total_pages < 0) {
				echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
				$error = true;
			}
		if ($this->limit == NULL) {
				echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
				$error = true;
			}
		if ($error)return false;
		
		$n = trim('<span>'. $this->nextT.'</span> '.$this->nextI);
		$p = trim($this->prevI.' <span>'.$this->prevT .'</span>');
		
		/* Setup vars for query. */
		if ($this->page) 
			$start = ($this->page - 1) * $this->limit;      //first item to display on this page
		else
			$start = 0;                               		//if no page var is given, set start to 0
	
		/* Setup page vars for display. */
		$prev = $this->page - 1;                            //previous page is page - 1
		$next = $this->page + 1;                            //next page is page + 1
		$lastpage = ceil($this->total_pages/$this->limit);  //lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;                        		//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		
		if ($lastpage > 1) {
			if ($this->page) {
				//anterior button
				if ($this->page > 1)
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($prev)."\" class=\"pagedir prev\">$p</a>";
					else
						$this->pagination .= "<span class=\"pagedir disabled\">$p</span>";
			}
			//pages	
			if ($lastpage < 7 + ($this->adjacents * 2)) {//not enough pages to bother breaking it up
				for ($counter = 1; $counter <= $lastpage; $counter++) {
						if ($counter == $this->page)
								$this->pagination .= "<span class=\"pageno current\">$counter</span>";
							else
								$this->pagination .= "<a class=\"pageno\" href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
					}
			}
			elseif ($lastpage > 5 + ($this->adjacents * 2)) {//enough pages to hide some
				//close to beginning; only hide later pages
				if ($this->page < 1 + ($this->adjacents * 2)) {
						for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++) {
								if ($counter == $this->page)
										$this->pagination .= "<span class=\"current\">$counter</span>";
									else
										$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
							}
						$this->pagination .= "...";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
					}
				//in middle; hide some front and some back
				elseif ($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)) {
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
						$this->pagination .= "...";
						for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"current\">$counter</span>";
								else
									$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
						$this->pagination .= "...";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
					}
				//close to end; only hide early pages
				else {
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
						$this->pagination .= "...";
						for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"current\">$counter</span>";
								else
									$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
					}
			}
			if ($this->page) {
				//siguiente button
				if ($this->page < $counter - 1)
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($next)."\" class=\"pagedir next\">$n</a>";
					else
						$this->pagination .= "<span class=\"pagedir disabled\">$n</span>";
					if ($this->showCounter)$this->pagination .= "<div class=\"pagedir pagination_data\">($this->total_pages Pages)</div>";
			}
		}
		return true;
	}
}

// *************************************************************************************************** //
// Custom Fields
function bb_custom_fields($visibility = 0, $ProfileID, $ProfileGender, $ProfileGenderShow = false, $ProfileType = null) {

	global $wpdb;
				
	$query = 'SELECT * FROM '. table_agency_customfields .' WHERE `ProfileCustomView` = '.$visibility;
	/*
	if (!is_null($ProfileType))
		$query .= ' AND (`ProfileCustomType` = '.$ProfileType.' OR `ProfileCustomType` IS NULL)';
	*/
	$query .= ' ORDER BY `ProfileCustomOrder`';

	bb_agency_debug( $query );

	$results = $wpdb->get_results( $query );
	
	if (!empty($results)) {
		foreach ($results as $data) {
			if ($ProfileGenderShow) {
				if ($data->ProfileCustomShowGender == $ProfileGender && $count3 >= 1 ) { // Depends on Current LoggedIn User's Gender
					bb_custom_fields_template($visibility, $ProfileID, $data);
				} elseif (empty($data->ProfileCustomShowGender)) {
					bb_custom_fields_template($visibility, $ProfileID, $data);
				}
			 } else {
				bb_custom_fields_template($visibility, $ProfileID, $data);
			 }
			// END Query2
			echo "    </td>\n";
			echo "  </tr>\n";
		} // End foreach

	} else {
		echo "  <tr valign=\"top\">\n";
		echo "    <th scope=\"row\">". __("There are no custom fields loaded", bb_agency_TEXTDOMAIN) .".  <a href=". admin_url("admin.php?page=bb_agency_settings&ConfigID=7") ."'>". __("Setup Custom Fields", bb_agency_TEXTDOMAIN) ."</a>.</th>\n";
		echo "  </tr>\n";
	}
}

// *************************************************************************************************** //
// Custom Fields TEMPLATE 
/**
 *
 * @param int $visibility
 * @param int $ProfileID
 * @param object $data
 *
 */
function bb_custom_fields_template($visibility = 0, $ProfileID, $data) {

	global $wpdb;

	$bb_agency_option_unittype  		= bb_agency_get_option('bb_agency_option_unittype');
	$bb_agency_option_profilenaming 	= (int)bb_agency_get_option('bb_agency_option_profilenaming');
	$bb_agency_option_locationtimezone 	= (int)bb_agency_get_option('bb_agency_option_locationtimezone');
	
	if ( (!empty($data->ProfileCustomID) || $data->ProfileCustomID !="") ) { 
   
		$row = $wpdb->get_row("SELECT ProfileID,ProfileCustomValue,ProfileCustomID FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = '". $data->ProfileCustomID ."' AND ProfileID = ". $ProfileID);
		
		$ProfileCustomValue = $row->ProfileCustomValue;
		$ProfileCustomTitle = $data->ProfileCustomTitle;
		$ProfileCustomType  = $data->ProfileCustomType;
	
		// SET Label for Measurements
		// Imperial(in/lb), Metrics(ft/kg)
		
		$bb_agency_option_unittype  = bb_agency_get_option('bb_agency_option_unittype');
		$measurements_label = '';
		if ($ProfileCustomType == 7) { //measurements field type
			if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
				if ($data->ProfileCustomOptions == 1) {
					$measurements_label  ="<em>(cm)</em>";
				} elseif ($data->ProfileCustomOptions == 2) {
					$measurements_label  ="<em>(kg)</em>";
				} elseif ($data->ProfileCustomOptions == 3) {
				  	$measurements_label  ="<em>(In Feet/Inches)</em>";
				}
			} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
				if ($data->ProfileCustomOptions == 1) {
					$measurements_label  ="<em>(In Inches)</em>";
				} elseif ($data->ProfileCustomOptions == 2) {
				  	$measurements_label  ="<em>(In Pounds)</em>";
				} elseif ($data->ProfileCustomOptions == 3) {
				  	$measurements_label  ="<em>(In Feet/Inches)</em>";
				}
			}
		}  
		$isTextArea = '';
		if ($ProfileCustomType == 4) {
			$isTextArea ="textarea-field"; 
		}
		echo "  <tr valign=\"top\" class=\"".$isTextArea."\">\n";
		echo "    <th scope=\"row\"><div class=\"box\">". $data->ProfileCustomTitle.$measurements_label."</div></th>\n"; 
		echo "    <td>\n";		  
		  
		if ($ProfileCustomType == 1) { //TEXT
			echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";						
		} elseif ($ProfileCustomType == 2) { // Min Max
		
			$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data->ProfileCustomOptions,"}"),"{"));
			list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
		 
			if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) {
				echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
				echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
			} else {
				echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data->ProfileCustomID]."\" />\n";
				echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data->ProfileCustomID]."\" /><br />\n";
			}
		 
		} elseif ($ProfileCustomType == 3) {  // Drop Down
			
			list($option1,$option2) = explode(":", $data->ProfileCustomOptions);
				
			$options = explode("|", $option1);
			$options2 = explode("|", $option2);
			
			echo "<label class=\"dropdown\">".$options[0]."</label>";
			echo "<select name=\"ProfileCustomID". $data->ProfileCustomID ."[]\">\n";
			echo "<option value=\"\">--</option>";
			$pos = 0;
			foreach ($options as $val1) {
				
				if ($val1 != $data->ProfileID) {
					if ($val1 == $ProfileCustomValue ) {
						$isSelected = "selected=\"selected\"";
						echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
					} else {
						echo "<option value=\"".$val1."\" >".$val1."</option>";
					}					
				}
			}
			echo "</select>\n";
				
			if (!empty($options2) && !empty($option2)) {
				echo "<label class=\"dropdown\">".$data2[0]."</label>";
			
					$pos2 = 0;
					echo "11<select name=\"ProfileCustomID". $data->ProfileCustomID ."[]\">\n";
					echo "<option value=\"\">--</option>";
					foreach ($options2 as $val2) {
							if ($val2 != end($options2) && $val2 !=  $options2[0]) {
								echo "<option value=\"".$val2."\" ". selected($val2, $ProfileCustomValue) ." >".$val2."</option>";
							}
						}
					echo "</select>\n";
			}
		} elseif ($ProfileCustomType == 4) {
			echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data->ProfileCustomID ."\">". $ProfileCustomValue ."</textarea>";
		} elseif ($ProfileCustomType == 5) {
			echo "<fieldset>";
			$array_customOptions_values = explode("|",$data->ProfileCustomOptions);

			foreach ($array_customOptions_values as $val) {
				$xplode = explode(",",$ProfileCustomValue);
				if (!empty($val)) {
					echo "<label class=\"checkbox\"><input type=\"checkbox\" value=\"". $val."\"   "; if (in_array($val,$xplode)) { echo "checked=\"checked\""; } echo" name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" /> ";
					echo "". $val."</label><br />";                               
				}
			}
			echo "</fieldset>";

		} elseif ($ProfileCustomType == 6) {
			
			$array_customOptions_values = explode("|",$data->ProfileCustomOptions);
			
			foreach ($array_customOptions_values as $val) {
				echo "<fieldset>";
					echo "<label><input type=\"radio\" value=\"". $val."\" "; checked($val, $ProfileCustomValue); echo" name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" />";
					echo "". $val."</label><br/>";
				echo "</fieldset>";
			}
		} elseif ($ProfileCustomType == 7) { //Imperial/Metrics 
			$limit = 80; // 
			?>
            <select name="ProfileCustomID<?php echo $data->ProfileCustomID ?>">
                <option value="">--</option>
            	<?php for ($i = 12; $i <= $limit; $i++) : // display height options ?>
                <option value="<?php echo $i ?>" <?php selected($ProfileCustomValue, $i) ?>><?php echo bb_agency_display_height($i) ?></option>
            	<?php endfor; ?>
            </select>
			<?php					
		} elseif ($ProfileCustomType == 8) { //Dates
			echo "<input class=\"stubby bbdatepicker\" type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";	
		}									
	} // End if Empty ProfileCustomID
}

/*/
*   ================ Get Profile Gender for each user ===================
*   @returns GenderTitle
/*/   
function bb_agency_getGenderTitle($ProfileGenderID) {
 
 	global $wpdb;
 	
	$query = "SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID='".$ProfileGenderID."'";
	$title = $wpdb->get_var($query);

	if ($title) {
		return $title;
	} else {
		return 0;	 
	}
}

/*/
*   ================ Filters custom fields to show based on assigned gender ===================
*   @returns GenderTitle
/*/
function bb_agency_filterfieldGender($ProfileCustomID, $ProfileGenderID) {

	global $wpdb;

	$query = "SELECT COUNT(*) FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 AND ProfileCustomID ='".$ProfileCustomID."' AND ProfileCustomShowGender IN('".$ProfileGenderID."','') ";

	$count = $wpdb->get_var($query);

	if ($count > 0) {
	 	return true;  
	} else {
	 	return false;
	}
}
 
/*/ // DEPRECATED FUNCTION
* ======================== Get Favorite & Casting Cart Links ===============
* @Returns links
/*/		
function bb_agency_get_miscellaneousLinks($ProfileID = '') {

	global $wpdb;

	$disp = '';
	$disp .= "<div class=\"favorite-casting\">";

	if (is_permitted('favorite')) {
		if (!empty($ProfileID)) {
			$favourite = $wpdb_>get_var("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".bb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = '".$ProfileID."' ");

			if ($favourite) {
					$disp .= "<div class=\"favorite\"><a title=\"Save to Favourites\" rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\"></a></div>\n";
			} else {
					$disp .= "<div class=\"favorite\"><a rel=\"nofollow\" title=\"Remove from Favourites\" href=\"javascript:;\" class=\"favorited\" id=\"".$ProfileID."\"></a></div>\n";
			}					

		}
	}	
	
	if (is_permitted('casting')) {
		if (!empty($ProfileID)) {
			$castingCart = $wpdb->get_var("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".bb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = '".$ProfileID."' ");

			if (!$castingCart) {
				$disp .= "<div class=\"castingcart\"><a title=\"Add to Casting Cart\" href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"save_castingcart\"></a></div></li>";
			} else {
				if (get_query_var('type') == "casting") { //hides profile block when icon is click
					$divHide="onclick=\"javascript:document.getElementById('div$ProfileID').style.display='none';\"";
				}
				$disp .= "<div class=\"castingcart\"><a $divHide href=\"javascript:void(0)\"  id=\"".$ProfileID."\" title=\"Remove from Casting Cart\"  class=\"saved_castingcart\"></a></div>";
			}
		}
	}

	$disp .= "</div><!-- .favorite-casting -->";
 	return $disp; 
}


/*/
* ======================== NEW Get Favorite & Casting Cart Links ===============
* @Returns links
/*/		
function bb_agency_get_new_miscellaneousLinks($ProfileID = '') {

	global $wpdb;
 
//	$bb_options 				= bb_agency_get_option();
	$bb_agency_option_profilelist_favorite		= bb_agency_get_option('bb_agency_option_profilelist_favorite');
	$bb_agency_option_profilelist_castingcart 	= bb_agency_get_option('bb_agency_option_profilelist_castingcart');
	bb_agency_checkExecution();

	if ($bb_agency_option_profilelist_favorite) {
		//Execute query - Favorite Model
		if (!empty($ProfileID)) {
			$favourite = $wpdb->get_var("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".bb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = '".$ProfileID."' ");
		}
	}	
	
	if ($bb_agency_option_profilelist_castingcart) {
      	//Execute query - Casting Cart
		if (!empty($ProfileID)) {
			$castingCart = $wpdb->get_var("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".bb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = '".$ProfileID."' ");
		}
	}

	$disp = '';
	$disp .= "<div class=\"favorite-casting\">";
        
	if ($bb_agency_option_profilelist_castingcart) {
		if (!$castingCart) {
			$disp .= "<div class=\"newcastingcart\"><a title=\"Add to Casting Cart\" href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"save_castingcart\">ADD TO CASTING CART</a></div></li>";
		} else {
			if (get_query_var('type')=="casting") { //hides profile block when icon is click
			 	$divHide="onclick=\"javascript:document.getElementById('div$ProfileID').style.display='none';\"";
			}
			$disp .= "<div class=\"gotocastingcard\"><a $divHide href=\"". get_bloginfo("wpurl") ."/profile-casting/\"  title=\"Go to Casting Cart\">VIEW CASTING CART</a></div>";
	  	}
	}
        
        
	if ($bb_agency_option_profilelist_favorite) {
		
		if (!$favourite) {
			$disp .= "<div class=\"newfavorite\"><a title=\"Save to Favourites\" rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\">SAVE TO FAVORITES</a></div>\n";
		}else{
			$disp .= "<div class=\"viewfavorites\"><a rel=\"nofollow\" title=\"View Favourites\" href=\"".  get_bloginfo("wpurl") ."/profile-favorite/\"/>VIEW FAVORITES</a></div>\n";
		}					
	}
			

	$disp .= "</div><!-- .favorite-casting -->";
 	return $disp; 
}



/*/
* ======================== Get New Custom Fields ===============
* @Returns Custom Fields
/*/
function bb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender, $LabelTag="strong") {

	global $wpdb;
	global $bb_agency_option_unittype;
	
	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
	foreach ($resultsCustom as $resultCustom) { 
            
            if ( $resultCustom->ProfileCustomID != 16 ):
            
		if (!empty($resultCustom->ProfileCustomValue )) {
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(cm)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(kg)";
					}
			 	} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
					if ($resultCustom->ProfileCustomOptions == 1) {
					$label = "(in)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(lbs)";
					} elseif ($resultCustom->ProfileCustomOptions == 3) {
						$label = "(ft/in)";
					}
			 	} 
				$measurements_label = "<span class=\"label\">". $label ."</span>";
			} else {
				$measurements_label = '';
			}

			// Lets not do this...
			$measurements_label = '';
		 
			if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
				if ($resultCustom->ProfileCustomType == 7) { 
					if ($resultCustom->ProfileCustomOptions == 3) { 
					   	echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
					} else {
					   	echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
			   	} else {
					   	echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
			   	}
			  
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7) {
				  	if ($resultCustom->ProfileCustomOptions == 3) {
					   	echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
				  	} else {
						echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
				  	}
			   	} else {
					echo "<li><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label.":</".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
			   	}
			}
		}
             endif;
	}
}




/*/
* ======================== Get Custom Fields ===============
* @Returns Custom Fields
/*/
function bb_agency_getProfileCustomFields($ProfileID, $ProfileGender) {

	global $wpdb;
	global $bb_agency_option_unittype;

	$resultsCustom = $wpdb->get_results("SELECT c.`ProfileCustomID`, c.`ProfileCustomTitle`, c.`ProfileCustomType`, c.`ProfileCustomOptions`, c.`ProfileCustomOrder`, cx.`ProfileCustomValue` FROM `". table_agency_customfield_mux ."` cx LEFT JOIN `". table_agency_customfields ."` c ON c.`ProfileCustomID` = cx.`ProfileCustomID` WHERE c.`ProfileCustomView` = 0 AND cx.`ProfileID` = $ProfileID GROUP BY cx.`ProfileCustomID` ORDER BY c.`ProfileCustomOrder` ASC");

	foreach ($resultsCustom as $resultCustom) {
		if (!empty($resultCustom->ProfileCustomValue)) {
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(cm)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(kg)";
					}
			 	} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(in)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(lbs)";
					} elseif ($resultCustom->ProfileCustomOptions == 3) {
						$label = "(ft/in)";
					}
			 	}
				$measurements_label = " <span class=\"label\">". $label ."</span>";
			} else {
				$measurements_label = '';
			}

			// Lets not do this...
			$measurements_label = '';
		 
			if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
				if ($resultCustom->ProfileCustomType == 7 || 
					$resultCustom->ProfileCustomType == 3) {
					if ($resultCustom->ProfileCustomTitle == 'Height') {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle.":</strong> ".bb_agency_display_height($resultCustom->ProfileCustomValue);
					} elseif ($resultCustom->ProfileCustomOptions == 3) {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
					} else {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
			   	} else {
					if ($resultCustom->ProfileCustomType == 4) {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong><br/> ". nl2br($resultCustom->ProfileCustomValue) ."</li>\n";
					} else {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
			   	}
			  
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7) {
				  	if ($resultCustom->ProfileCustomOptions == 3) {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
				  	} else {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				  	}
			   	} else {
					echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
			   	}
			}
		}
	}
}

/*/
* ======================== Get Custom Fields ===============
* @Returns Custom Fields excluding a title
* @parm includes an array of title
/*/
function bb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender, $title_to_exclude) {

	global $wpdb;
	global $bb_agency_option_unittype;
	
	$resultsCustom = $wpdb->get_results("SELECT c.`ProfileCustomID`, c.`ProfileCustomTitle`, c.`ProfileCustomType`, c.`ProfileCustomOptions`, c.`ProfileCustomOrder`, cx.`ProfileCustomValue` FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.`ProfileCustomID` = cx.`ProfileCustomID` WHERE c.`ProfileCustomView` = 0 AND cx.`ProfileID` = ". $ProfileID ." GROUP BY cx.`ProfileCustomID` ORDER BY c.`ProfileCustomOrder` ASC");
	foreach ($resultsCustom as $resultCustom) {
		if (!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)) { 	
			if (!empty($resultCustom->ProfileCustomValue )) {
				if ($resultCustom->ProfileCustomType == 7) { //measurements field type
					if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
						if ($resultCustom->ProfileCustomOptions == 1) {
							$label = "(cm)";
						} elseif ($resultCustom->ProfileCustomOptions == 2) {
							$label = "(kg)";
						}
				 	} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
						if ($resultCustom->ProfileCustomOptions == 1) {
							$label = "(in)";
						} elseif ($resultCustom->ProfileCustomOptions == 2) {
							$label = "(lbs)";
						} elseif ($resultCustom->ProfileCustomOptions == 3) {
							$label = "(ft/in)";
						}
					}
				 	$measurements_label = "<span class=\"label\">". $label ."</span>";
				} else {
					$measurements_label = '';
				}

				// Lets not do this...
				$measurements_label = '';
			 
				if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
					if ($resultCustom->ProfileCustomType == 7) {
						if ($resultCustom->ProfileCustomOptions == 3) {
						   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
						} else {
						  	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
				   	} else {
						if ($resultCustom->ProfileCustomType == 4) {
							echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong><br/> ". nl2br($resultCustom->ProfileCustomValue) ."</li>\n";
						} else {
							echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
				   	}
				  
				} elseif ($resultCustom->ProfileCustomView == "2") {
					if ($resultCustom->ProfileCustomType == 7) {
					  	if ($resultCustom->ProfileCustomOptions == 3) {
						   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</li>\n";
					  	} else {
							echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					  	}
				   	} else {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				   	}
				}
			}
		}
	} 
} 
 function bb_agency_getProfileCustomFieldsExperienceDescription($ProfileID, $ProfileGender, $title_to_exclude) {

	global $wpdb;
	global $bb_agency_option_unittype;
	
	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
	foreach ($resultsCustom as $resultCustom) {
		if (!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)) { 	
			if (!empty($resultCustom->ProfileCustomValue )) {
				
				// Lets not do this...
				$measurements_label = '';
			 if ($resultCustom->ProfileCustomTitle == 'Experience(s)') {
				if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
					
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				   	
				  
				}  	
			 }
			}
		}
	} 
} 
function bb_agency_getProfileCustomFieldsEcho($ProfileID, $ProfileGender,$exclude="",$include="") {
	global $wpdb;
	global $bb_agency_option_unittype;
     
	$query="SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ."";
	
	if (!empty($exclude)) {$query.="AND ProfileCustomID IN($exclude)";}
    if (!empty($include)) {$query.="AND ProfileCustomID NOT IN($include)";}

	$query.="GROUP 3 BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC ";
	
	$resultsCustom = $wpdb->get_results($query);
	foreach ($resultsCustom as $resultCustom) {
		if (!empty($resultCustom->ProfileCustomValue )) {
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(cm)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(kg)";
					}
				} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(in)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(lbs)";
					 }elseif ($resultCustom->ProfileCustomOptions == 3) {
						$label = "(ft/in)";
					}
			 	}
				$measurements_label = "<span class=\"label\">". $label ."</span>";
			} else {
			 	$measurements_label = '';
			}

			// Lets not do this...
			$measurements_label = '';
		
			if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
				
				if ($resultCustom->ProfileCustomType == 7) {
					if ($resultCustom->ProfileCustomOptions == 3) {
					   	echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</span></li>\n";
					} else {
					   	echo  "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
					}
			   	} else {
				   if ($echo!="dontecho") {  // so it wont exit if PDF generator request info
					    if ($resultCustom->ProfileCustomTitle.$measurements_label=="Experience") {return "";}
				   	
					echo "<li id='". $resultCustom->ProfileCustomTitle .$measurements_label."'><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			  }
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7) {
				  	if ($resultCustom->ProfileCustomOptions == 3) {
					   	echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</span></li>\n";
				  	} else {
						echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
				  	}
			   	} else {
					echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   }
			}
		}
	}
	if ($echo=="dontecho") {return $return;}else{echo $return;}
}  

function bb_agency_getProfileCustomFieldsCustom($ProfileID, $ProfileGender,$echo="") {

	global $wpdb;
	global $bb_agency_option_unittype;

	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");

	foreach ($resultsCustom as $resultCustom) {

		if (!empty($resultCustom->ProfileCustomValue )) {
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if ($bb_agency_option_unittype == 0) { // 0 = Metrics(ft/kg)
					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(cm)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(kg)";
					}
				} elseif ($bb_agency_option_unittype ==1) { //1 = Imperial(in/lb)

					if ($resultCustom->ProfileCustomOptions == 1) {
						$label = "(in)";
					} elseif ($resultCustom->ProfileCustomOptions == 2) {
						$label = "(lbs)";
					} elseif ($resultCustom->ProfileCustomOptions == 3) {
						$label = "(ft/in)";
					}
		 		}

				$measurements_label = "<span class=\"label\">". $label ."</span>";

	 		} else {
		 		$measurements_label = '';
	 		}

		 	// Lets not do this...
			$measurements_label = '';

			if (bb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)) {
				
				if ($resultCustom->ProfileCustomType == 7) {
					if ($resultCustom->ProfileCustomOptions == 3) {
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</span></li>\n";
					} else {
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
					}
			   	} else {
				   	if ($echo!="dontecho") {  // so it wont exit if PDF generator request info
					    if ($resultCustom->ProfileCustomTitle.$measurements_label=="Experience") { return ""; }
				   	}
					$return.="<li id='". $resultCustom->ProfileCustomTitle .$measurements_label."'><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			  
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7) {
				  	if ($resultCustom->ProfileCustomOptions == 3) {
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".bb_agency_get_imperial_height($resultCustom->ProfileCustomValue)."</span></li>\n";
				  	} else {
						$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
				  	}
			   	} else {
					$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			}
		}
	}	
	if ($echo=="dontecho") {return $return;}else{echo $return;}
}
  





		
		$bb_agency_option_profilelist_favorite		 = bb_agency_get_option('bb_agency_option_profilelist_favorite');
			
//****************************************************************************************************//
// Add / Handles Ajax Request ====== ADD To Favorites		

	function bb_agency_save_favorite() {
		
		global $wpdb;

		if (is_user_logged_in()) {	
			if (isset($_POST["talentID"])) {
				 $favourite = $wpdb->get_row("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$_POST["talentID"]."'  AND SavedFavoriteProfileID = '".bb_agency_get_current_userid()."'" );
				 
				 if ($favourite) { //if not exist insert favourite!
					 
					   $wpdb->query("INSERT INTO ".table_agency_savedfavorite."(SavedFavoriteID,SavedFavoriteProfileID,SavedFavoriteTalentID) VALUES('','".bb_agency_get_current_userid()."','".$_POST["talentID"]."')");
					 
				 }else{ // favourite model exist, now delete!
					 
					  $wpdb->query("DELETE FROM  ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$_POST["talentID"]."'  AND SavedFavoriteProfileID = '".bb_agency_get_current_userid()."'");
				 }				
			}			
		}
		else {
			echo "not_logged";
		}
		die();
	}
		
	function bb_agency_save_favorite_javascript() {
	?>

		 <!--BB Agency Favorite -->           
		<script type="text/javascript" >jQuery(document).ready(function() { 
			jQuery(".favorite a:first, .favorite a").click(function() {
		
				var Obj = jQuery(this);
				jQuery.ajax({type: 'POST',url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {action: 'bb_agency_save_favorite',  'talentID': jQuery(this).attr("id")},
		
				success: function(results) {
				if (results=='error') { 
					Obj.fadeOut().empty().html("Error in query. Try again").fadeIn(); 
				} else if (results==-1) { 
					Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo get_bloginfo("wpurl"); ?>/profile-member/\">Sign In</a>.").fadeIn();
					setTimeout(function() { 
						if (Obj.attr("class")=="save_favorite") { 
								
							Obj.fadeOut().empty().html("").fadeIn();  
							Obj.attr('title', 'Save to Favorites'); 
						} else { 
							Obj.fadeOut().empty().html("Favorited").fadeIn();  
							Obj.attr('title', 'Remove from Favorites');  
						} 
					}, 2000);  
				} else { 
					if (Obj.attr("class")=="save_favorite") { 
						Obj.empty().fadeOut().empty().html("").fadeIn(); 
						Obj.attr("class","favorited"); 
						Obj.attr('title', 'Remove from Favorites') 
					}else{ 
						Obj.empty().fadeOut().empty().html("").fadeIn();  
						Obj.attr('title', 'Save to Favorites'); 
						jQuery(this).find("a[class=view_all_favorite]").remove(); 
						Obj.attr("class","save_favorite");
						<?php  if (get_query_var( 'type' )=="favorite" || get_query_var( 'type' )=="castingcart") { 
						
						$bb_agency_option_layoutprofilelist = bb_agency_get_option('bb_agency_option_layoutprofilelist');  ?> 
						if (jQuery("input[type=hidden][name=favorite]").val() == 1) { 
							Obj.closest("div[class=profile-list-layout0]").fadeOut();} <?php }?>
					}
				}}})});});
		</script>
		<!--END BB Agency Favorite -->   

<!-- [class=profile-list-layout<?php echo (int)$bb_agency_option_layoutprofilelist; ?>]-->
		<?php
	}

	if ($bb_agency_option_profilelist_favorite) {
		 add_action('wp_footer', 'bb_agency_save_favorite_javascript');
	 	 add_action('wp_ajax_bb_agency_save_favorite', 'bb_agency_save_favorite');
	}
//****************************************************************************************************//
// Add / Handles Ajax Request ===== Add To Casting Cart
		    
$bb_agency_option_profilelist_castingcart  = bb_agency_get_option('bb_agency_option_profilelist_castingcart');
	
function bb_agency_save_castingcart() {
	global $wpdb;

	if (is_user_logged_in()) { 
		if (isset($_POST["talentID"])) { 
			$castingCart = $wpdb->get_row("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."'  AND CastingCartProfileID = '".bb_agency_get_current_userid()."'" ) or die("error");
			 
			if ($castingCart) { //if not exist insert favorite!
                                        
                $wpdb->insert(table_agency_castingcart, array('CastingCartProfileID'=>bb_agency_get_current_userid(), 'CastingCartTalentID'=>$_POST["talentID"]));
                                                    
			} else { // favourite model exists, now delete!
				 
				$wpdb->query("DELETE FROM  ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."'  AND CastingCartProfileID = '".bb_agency_get_current_userid()."'");							 
			}						
		}					
	}
	else {
		echo "not_logged";
	}
	die();
}	  
		
function bb_agency_save_castingcart_javascript() {
?>
		<!--BB Agency CastingCart -->
		<script type="text/javascript" >
			jQuery(document).ready(function($) { 
				$("div[class=castingcart] a").click(function() {
					var Obj = $(this);jQuery.ajax({type: 'POST',url: '<?php echo admin_url('admin-ajax.php'); ?>',data: {action: 'bb_agency_save_castingcart',  'talentID': $(this).attr("id")},
						success: function(results) {   
							if (results=='error') { Obj.fadeOut().empty().html("Error in query. Try again").fadeIn();  } 
							else if (results==-1) { Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo get_bloginfo("wpurl"); ?>/profile-member/\">Sign In</a>.").fadeIn();  
									setTimeout(function() {   
										if (Obj.attr("class")=="save_castingcart") {  
											Obj.fadeOut().empty().html("").fadeIn();
										}else{  
											Obj.fadeOut().empty().html("").fadeIn();  } 
									 }, 2000);  }
							else{ if (Obj.attr("class")=="save_castingcart") {
								Obj.empty().fadeOut().html("").fadeIn();  
								Obj.attr("class","saved_castingcart"); 
								Obj.attr('title', 'Remove from Casting Cart'); 
							} else { 
							Obj.empty().fadeOut().html("").fadeIn();  
							Obj.attr("class","save_castingcart"); 
							Obj.attr('title', 'Add to Casting Cart');   
							$(this).find("a[class=view_all_castingcart]").remove();  
							<?php  if (get_query_var( 'type' )=="favorite" || get_query_var( 'type' )=="castingcart") {  
										 
										$bb_agency_option_layoutprofilelist = bb_agency_get_option('bb_agency_option_layoutprofilelist'); ?> 
										if ($("input[type=hidden][name=castingcart]").val() == 1) {
											Obj.closest("div[class=profile-list-layout0]").fadeOut();  } <?php } ?> } }}}) });});</script>
		 <!--END BB Agency CastingCart -->

   <?php
}

if (isset($bb_agency_option_profilelist_castingcart)) {
  	add_action('wp_ajax_bb_agency_save_castingcart', 'bb_agency_save_castingcart');
  	add_action('wp_footer', 'bb_agency_save_castingcart_javascript');
}

/*/
* ======================== Get ProfileID by UserLinkedID ===============
* @Returns ProfileID
/*/
function bb_agency_getProfileIDByUserLinked($ProfileUserLinked) {

	global $wpdb;
  
   	if (!empty($ProfileUserLinked)) {
      	$row = $wpdb->get_row("SELECT `ProfileID`, `ProfileUserLinked` FROM ".table_agency_profile." WHERE ProfileUserLinked = ".$ProfileUserLinked." ");

		return $row->ProfileID;
   	}
}

/*/
* ======================== Get Media Categories===============
* @Returns Media Categories
/*/
function bb_agency_getMediaCategories($GenderID) {

	global $wpdb;
	
	$results = $wpdb->get_results("SELECT `MediaCategoryID`, `MediaCategoryTitle`, `MediaCategoryGender`, `MediaCategoryOrder` FROM  ".table_agency_mediacategory." ORDER BY `MediaCategoryOrder`");

	if ($results) {
		foreach ($results as $f) {
			if ($f->MediaCategoryGender == $GenderID || $f->MediaCategoryGender == 0) {
				echo "<option value=\"".$f->MediaCategoryTitle."\">".$f->MediaCategoryTitle."</option>";	 
			}
		}
	}
}

/*/
* ======================== Show/Hide Admin Toolbar===============
* 
/*/
function bb_agency_disableAdminToolbar() {
	add_filter('show_admin_bar', '__return_false');
}

$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
$bb_agencyinteract_option_profilemanage_toolbar =isset($bb_agencyinteract_options_arr["bb_agencyinteract_option_profilemanage_toolbar"]) ? (int)$bb_agencyinteract_options_arr["bb_agencyinteract_option_profilemanage_toolbar"] : 0;

if ($bb_agencyinteract_option_profilemanage_toolbar==1) {
  	bb_agency_disableAdminToolbar(); 
}

/*/
* ======================== Edit Text/Label/Header ===============
* 
/*/
//add_filter( 'gettext', 'bb_agency_editTitleText', 10, 3 );
function bb_agency_editTitleText($string) {
	return "<span>".$string."<a href=\"javascript:;\" style=\"font-size:11px;color:blue;text-decoration:underline;\">Edit</a></span>";  
}

/*/
*================ Add toolbar menu ==============================
*
/*/ 
function bb_agency_callafter_setup() {

	if (current_user_can('level_10') && !is_admin()) {
		function bb_agency_add_toolbar($wp_toolbar) {
			$wp_toolbar->add_node(array(
				'id' => 'bb-agency-toolbar-settings',
				'title' => 'BB Agency Settings',
				'href' =>  get_admin_url().'admin.php?page=bb_agency_settings',
				'meta' => array('target' => 'bb-agency-toolbar-settings')
			));
		}
	  	add_action('admin_bar_menu', 'bb_agency_add_toolbar', 999);
	}
}
add_action( 'after_setup_theme',"bb_agency_callafter_setup");

/*/
*  PHP Profiler DEBUG MODE
/*/ 
function bb_agency_checkExecution() {

	global $BB_DEBUG_MODE;

	if ($BB_DEBUG_MODE == true) {

		$start = microtime();
		echo "<div style=\"float:left;border:1px solid #ccc;background:#ccc !important; color:black!important;\">";
		echo "<pre >";
		for($i=100;$i>0;$i--) {
			echo $i;
			echo "\n";
		}

		$end = microtime();
		$parseTime = $end-$start;
		
		echo "-DEBUG MODE- Time Execution";
		echo "\n\n";
		echo $parseTime;
		echo "</pre>";
		
		$trace = debug_backtrace();
		$file   = $trace[$level]['file'];
		$line   = $trace[$level]['line'];
		$object = $trace[$level]['object'];
		
      	if (is_object($object)) { $object = get_class($object); }
		$result = var_export( $var, true );
        
        echo "\n<pre>Dump: $result</pre>";		
		echo "\n<pre>";

		debug_print_backtrace();

		echo "\n\nWhere called: line $line of $object \n(in $file)";
		echo "</pre>";
		echo "</div>";
	}
}

/*/
 *   Profile Extend Social Links
/*/ 
function bb_agency_getSocialLinks() {

	
	$bb_agency_option_showsocial = bb_agency_get_option('bb_agency_option_showsocial');

	if ($bb_agency_option_showsocial) {
		echo "		<div class=\"social addthis_toolbox addthis_default_style\">\n";
		echo "			<a href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c4d7ce67dde9ce7\" class=\"addthis_button_compact\">". __("Share", bb_agency_TEXTDOMAIN). "</a>\n";
		echo "			<span class=\"addthis_separator\">|</span>\n";
		echo "			<a class=\"addthis_button_facebook\"></a>\n";
		echo "			<a class=\"addthis_button_myspace\"></a>\n";
		echo "			<a class=\"addthis_button_google\"></a>\n";
		echo "			<a class=\"addthis_button_twitter\"></a>\n";
		echo "		</div><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c4d7ce67dde9ce7\"></script>\n";
	}
}

//get previous and next profile link
function linkPrevNext($ppage, $nextprev, $type="", $division="") {

	global $wpdb;

	if ($nextprev=="next") { 
		$nPid = $pid+1; 
	}
	else { 
		$nPid = $pid-1; 
	}
	
	$sql = "SELECT `ProfileGallery` FROM ".table_agency_profile." WHERE 1 AND `ProfileGender` ='$type' AND `ProfileType` = '1' ";
	
	//filter division 
	if ($division=="/women/") {
		$ageStart = 17;
		$ageLimit = 99;
	}
	elseif ($division=="/men/") {
		$ageStart = 17;
		$ageLimit = 99;
	}
	elseif ($division=="/teen-girls/") {
		$ageStart = 12;
		$ageLimit = 27;
	}
	elseif ($division=="/teen-boys/") {
		$ageStart = 12;
		$ageLimit = 17;
	}
	elseif ($division=="/girls/") { 
		$ageStart = 1;
		$ageLimit = 12;
	}
	elseif ($division=="/boys/") {
		$ageStart = 1;
		$ageLimit = 12;
	}
 	$sql.="	AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth)), '%Y')+0 > $ageStart
        	AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth)), '%Y')+0 <=$ageLimit
			AND ProfileIsActive = 1
	";
	$tempSql = $sql;
	
	//end filter 
	if ($nextprev=="next") { 
		$sql.=" AND  ProfileGallery > '$ppage' ORDER BY ProfileContactNameFirst ASC"; // to get next record
	} else {
		$sql.=" AND  ProfileGallery < '$ppage' ORDER BY ProfileContactNameFirst DESC"; // to get next
	}
  
	$sql.=" LIMIT 0,1 ";

	$gallery = $wpdb->get_var($sql); 
  
  	if (empty($gallery)) { //make sure it wont send empty url

	  	if ($nextprev=="next") { 
  		 	$sql = $tempSql."  ORDER BY ProfileContactNameFirst ASC"; // to get next record
 	  	}else{
  	     	$sql = $tempSql."  ORDER BY ProfileContactNameFirst DESC"; // to get next
      	}
	  
     	$sql .= " LIMIT 0,1 ";

	 	$gallery = $wpdb->get_var($sql);
  	}
		 
	return  $gallery;
}

function getExperience($pid) { 
	global $wpdb;

	return $wpdb->get_var("SELECT ProfileCustomValue FROM ".table_agency_customfield_mux." WHERE ProfileID = '".$pid."' AND ProfileCustomID ='16' ");
}

function checkCart($currentUserID,$pid) {
	global $wpdb;
  	
  	$query = "SELECT COUNT(*) FROM  ".table_agency_castingcart." WHERE CastingCartProfileID='".$currentUserID."' AND CastingCartTalentID='".$pid."' ";
	
	return $wpdb->get_var($query);
}

/* function that lists users for generating login/password */
function bb_display_profile_list() {  
    global $wpdb;

    $t_profile = table_agency_profile;
    $t_data_type = table_agency_data_type;
    
    $bb_agency_option_locationtimezone 		= (int)bb_agency_get_option('bb_agency_option_locationtimezone');

    echo "<div class=\"wrap\">\n";
    echo "  <h3 class=\"title\">". __("Profiles List", bb_agency_TEXTDOMAIN) ."</h3>\n";
		
    // Sort By
    $sort = '';
    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        $sort = $_GET['sort'];
    }
    else {
        $sort = "profile.ProfileContactNameFirst";
    }
		
    // Sort Order
    $dir = '';
    if (isset($_GET['dir']) && !empty($_GET['dir'])) {
        $dir = $_GET['dir'];
        if ($dir == "desc" || !isset($dir) || empty($dir)) {
            $sortDirection = "asc";
        } else {
            $sortDirection = "desc";
        } 
    } else {
       $sortDirection = "desc";
       $dir = "asc";
    }
  	
    // Filter
    $filter = "WHERE profile.ProfileIsActive IN (0,1,4) ";
    if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])) {
        if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) {
                $selectedNameFirst = $_GET['ProfileContactNameFirst'];
                $query .= "&ProfileContactNameFirst=". $selectedNameFirst ."";
                $filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
        }
        if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])) {
                $selectedNameLast = $_GET['ProfileContactNameLast'];
                $query .= "&ProfileContactNameLast=". $selectedNameLast ."";
                $filter .= " AND profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
        }
    }
    if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])) {
            $selectedCity = $_GET['ProfileLocationCity'];
            $query .= "&ProfileLocationCity=". $selectedCity ."";
            $filter .= " AND profile.ProfileLocationCity='". $selectedCity ."'";
    }
    if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])) {
            $selectedType = $_GET['ProfileType'];
            $query .= "&ProfileType=". $selectedType ."";
            $filter .= " AND profiletype.DataTypeID='". $selectedType ."'";
    }
    if (isset($_GET['ProfileVisible']) && !empty($_GET['ProfileVisible'])) {
            $selectedVisible = $_GET['ProfileVisible'];
            $query .= "&ProfileVisible=". $selectedVisible ."";
            $filter .= " AND profile.ProfileIsActive='". $selectedVisible ."'";
    }
    if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])) {
            $ProfileGender = (int)$_GET['ProfileGender'];
            if ($ProfileGender)
                    $filter .= " AND profile.ProfileGender='".$ProfileGender."'";
    }
		
    // Paginate
    $sql = "SELECT COUNT(*) FROM $t_profile profile LEFT JOIN $t_data_type AS profiletype ON profile.`ProfileType` = profiletype.`DataTypeID` ". $filter;
    $items = $wpdb->get_var($sql); // number of total rows in the database
    if ($items > 0) {
        $p = new bb_agency_pagination;
        $p->items($items);
        $p->limit(50); // Limit entries per page
        $p->target("admin.php?page=". $_GET['page'] . '&ConfigID=99' . $query);
        $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
        $p->calculate(); // Calculates what to show
        $p->parameterName('paging');
        $p->adjacents(1); //No. of page away from the current page

        if (!isset($_GET['paging'])) {
            $p->page = 0;
        } else {
            $p->page = $_GET['paging'];
        }

        //Query for limit paging
        $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
    } else {
        $limit = '';
    }
    
    /* Top pagination */
    echo "<div class=\"tablenav\">\n";
    echo "  <div class=\"tablenav-pages\">\n";
    
    if ($items > 0) {
        echo $p->show();  // Echo out the list of paging. 
    }
    echo "  </div>\n";
    echo "</div>\n";
    /* End Top pagination */
     
    /* Table Content */
    include_once('admin/profile_list_table.php');
    /* End Table Content */

    /* Bottom pagination */
    echo "<div class=\"tablenav\">\n";
    echo "  <div class='tablenav-pages'>\n";

    if ($items > 0) {
            echo $p->show();  // Echo out the list of paging. 
    }

    echo "  </div>\n";
    echo "</div>\n";
    /*End Bottom pagination */
    
    echo "</div>";

}

add_action('wp_ajax_send_mail', 'register_and_send_email');

function register_and_send_email() { 
    global $wpdb;
    $profileid = (int)$_POST['profileid'];
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // getting required fileds from bb_agency_profile
    $profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM bb_agency_profile WHERE ProfileID = '" . $profileid . "'" );

    // creating new user
    $user_id = username_exists( $profile_row[0]->ProfileContactDisplay );
    if ( !$user_id and email_exists($user_email) == false ) {
        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $user_id = wp_create_user( $login, $random_password, $email ); 
        if (is_numeric($user_id)) {
            wp_set_password( $password, $user_id );

            // updating some information we have in wp_users
            $wpdb->update( 
                    'wp_users', 
                    array( 'display_name' => $profile_row[0]->ProfileContactDisplay ), 
                    array( 'ID' => $user_id ), 
                    array( '%s' ), 
                    array( '%d' ) 
            );

            // inserting some information we have in wp_usermeta
            update_user_meta( $user_id, 'first_name', $profile_row[0]->ProfileContactNameFirst );
            update_user_meta( $user_id, 'last_name', $profile_row[0]->ProfileContactNameLast );
			
			// linking the user ID with profile ID
			$wpdb->update( 
				'bb_agency_profile',
				array( 'ProfileUserLinked' => $user_id ),
				array( 'ProfileID' => $profile_row[0]->ProfileID ),
				array( '%d' ),
				array( '%d' )
			);

            send_email_lp($login, $password, $email);

            echo 'SUCCESS';
        } else {
            echo $user_id->errors['existing_user_login'][0];
        }

    } else {
        echo 'The user is already registrated!';
    }
    
    die;
}

add_action('wp_ajax_send_bulk_mail', 'bulk_register_and_send_email');

function bulk_register_and_send_email() {
    global $wpdb;
    
    $users_lp = $_POST['users_pl'];
    //echo '<pre>'; print_r($users_lp);
    
    $success = FALSE;
    
    foreach ($users_lp as $user_lp) { 
        $profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM bb_agency_profile WHERE ProfileID = '" . $user_lp['pid'] . "'" );
        
        $user_id = username_exists( $profile_row[0]->ProfileContactDisplay );
        if ( !$user_id and email_exists($user_email) == false ) { 
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = wp_create_user( $user_lp['login'], $random_password, $user_lp['email'] ); 
            if (is_numeric($user_id)) { 
                wp_set_password( $user_lp['password'], $user_id );

                // updating some information we have in wp_users
                $wpdb->update( 
                        'wp_users', 
                        array( 'display_name' => $profile_row[0]->ProfileContactDisplay ), 
                        array( 'ID' => $user_id ), 
                        array( '%s' ), 
                        array( '%d' ) 
                );

                // inserting some information we have in wp_usermeta
                update_user_meta( $user_id, 'first_name', $profile_row[0]->ProfileContactNameFirst );
                update_user_meta( $user_id, 'last_name', $profile_row[0]->ProfileContactNameLast );
				
				// linking the user ID with profile ID
				$wpdb->update( 
					'bb_agency_profile',
					array( 'ProfileUserLinked' => $user_id ),
					array( 'ProfileID' => $profile_row[0]->ProfileID ),
					array( '%d' ),
					array( '%d' )
				);
                
                send_email_lp($user_lp['login'], $user_lp['password'], $user_lp['email']);
                
                $success = TRUE;
                
            } else {
                //print_r($user_id);
            }
        }        
    }
    
    if ($success) {
        echo 'SUCCESS';
    }
    
    die;    
}

function send_email_lp($login, $password, $email) {
    $admin_email = get_bloginfo('admin_email');

    $headers = 'From: BB Agency <' . $admin_email . '>\r\n';

    $subject = 'Your new Login and Password';
    
    $message = read_email_content(true);
    if ($message == 'empty') {
        $message = 'Hello, we generated new login and password for you at BB Agency\n\n[login]\n[password]\n\nYou can login [url]\n\nThanks.';
    }

    $message = str_replace('[login]', 'Login: <strong>' . $login . '</strong>', $message);
    $message = str_replace('[password]', 'Password: <strong>' . $password . '</strong>', $message);
    $message = str_replace('[url]', '<a href="' . site_url('profile-login') . '">login</a>', $message);
    
    $message = nl2br($message);

    add_filter( 'wp_mail_content_type', 'bb_agency_set_content_type' );
    wp_mail($email, $subject, $message, $headers);
    remove_filter( 'wp_mail_content_type', 'bb_agency_set_content_type' );
    
}

add_action('wp_ajax_write_email_cnt', 'write_email_content');

function write_email_content() {
    $email_message = $_POST['email_message'];
    update_option( 'bb_email_content', $email_message );
    die;
}

add_action('wp_ajax_read_email_cnt', 'read_email_content');

function read_email_content($ret = false) { 
    if ($ret) {
        return $email_message = get_option( 'bb_email_content', 'empty' );
    }
    else {
        echo $email_message = get_option( 'bb_email_content', 'empty' );
    }
    
    die;
}

/*
 * Function to retrieve
 * featured widget profile
 *
 * @parm: none
 * @return:  		
 * Profile Name = array[0];
 * Gender = array[1];
 * Custom Fields = array[2];
 * Gallery Folder = array[3];
 * Profile Pic URL = array[4];
 */
function featured_homepage() {
	            
	global $wpdb;
	
	/*
	 * Get details for profile
	 * featured
	 */
	$count = 1;
	 
	$q = "SELECT profile.*,

	(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
	 WHERE profile.ProfileID = media.ProfileID 
	 AND media.ProfileMediaType = \"Image\" 
	 AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
	
	 FROM ". table_agency_profile ." profile 
	 WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
	 AND profile.ProfileIsFeatured = 1  
	 ORDER BY RAND() LIMIT 0,$count";						

	$rs = $wpdb->get_results($q);
	
	$countList = count($rs);
	
	$array_data = array();
	
	foreach ($rs as $row) {
		
		/*
		 * Get From Custom Fields
		 * per profile
		 */
		$get_custom = 'SELECT * FROM ' . table_agency_customfield_mux .
					  ' WHERE ProfileID = ' . $row->ProfileID; 
					  
		$result = $wpdb->get_results($get_custom);
		
		$desc_list = array('shoes', 'eyes', 'shoes', 'skin');
		
		$a_male = array('height','weight','waist', 'skin tone',
						'eye color',  'shoe size', 'shirt');
		
		$array_male = array();
		$array_female = array();

		$a_female = array('bust', 'waist', 'hips', 'dress',
							  'shoe size','hair', 'eye color');
        
		$name = ucfirst($row->ProfileContactNameFirst) ." ". strtoupper($row->ProfileContactNameLast); ;
		
		foreach ($result as $custom) {
             
			 $get_title = 'SELECT ProfileCustomTitle FROM ' . table_agency_customfields .
			 ' WHERE ProfileCustomID = ' . $custom->ProfileCustomID; 
			 
			 $custom2 = $wpdb->get_row($get_title);
			 
			 if (strtolower(bb_agency_getGenderTitle($row->ProfileGender)) == "male") {
				 
				 if (in_array(strtolower($custom2->ProfileCustomTitle), $a_male)) {
					 $array_male[$custom2->ProfileCustomTitle] = $custom->ProfileCustomValue;
				 }
			 
			 } else if (strtolower(bb_agency_getGenderTitle($row->ProfileGender)) == "female") {
				 
				 if (in_array(strtolower($custom2->ProfileCustomTitle), $a_female)) {
					 $array_female[$custom2->ProfileCustomTitle] = $custom->ProfileCustomValue;
				 }				 
			 }
		
		}
		
		if (strtolower(bb_agency_getGenderTitle($row->ProfileGender)) == "male") {
			
			$array_data = array($name, 'male', $array_male,$row->ProfileGallery, $row->ProfileMediaURL);
			
		} else if (strtolower(bb_agency_getGenderTitle($row->ProfileGender)) == "female") {
			
			$array_data = array($name, 'female', $array_female, $row->ProfileGallery, $row->ProfileMediaURL);
					 
		}
		
	}
	
	return $array_data;
	
}

// *************************************************************************************************** //
/*
 *  Shortcodes
 */
	// Search Form
	function bb_agency_searchform($DataTypeID) {
		$profilesearch_layout = "simple";
		include("theme/include-profile-search.php"); 	
	}
/*
// 5/15/2013 sverma@ Home page
function featured_homepage_profile($count) {
	global $wpdb;
	$row = array();
	$query = "SELECT profile.*,
	(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
	 WHERE profile.ProfileID = media.ProfileID 
	 AND media.ProfileMediaType = \"Image\" 
	 AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 

	 FROM ". table_agency_profile ." profile 
	 WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
	 AND profile.ProfileIsFeatured = 1  
	 ORDER BY RAND() LIMIT 0,".$count;						

	$result = $wpdb->get_results($query);
	$i = 0;
	foreach ($result as $row) {
		$row[$i] = $row ;
		$i++;
	}
	return $row ;

}
*/
function primary_class() {
	return $class = "col_8";
}

function secondary_class() {
	return $class = "col_4";
}

function fullwidth_class() {
	return $class = "col_12";
}


/*
 * Check casting cart / add fav if permitted to be displayed
 */
function is_permitted($type) {
    
                
                $bb_agency_option_privacy = bb_agency_get_option('bb_agency_option_privacy');
                $bb_agency_option_profilelist_castingcart  = bb_agency_get_option('bb_agency_option_profilelist_castingcart');
				$bb_agency_option_profilelist_favorite	 = bb_agency_get_option('bb_agency_option_profilelist_favorite');
              	
			  	if ($type=="casting" && !$bb_agency_option_profilelist_castingcart) return false;
                if ($type=="favorite" && !$bb_agency_option_profilelist_favorite) return false;
                if (!is_user_logged_in()) return false;
				
                if ($type == "casting" || $type == "favorite" ) {
                        
                     if ( ($bb_agency_option_privacy == 2) || 
			 
                           // Model list public. Must be logged to view profile information
                           ($bb_agency_option_privacy == 1) ||
							
							// Model list public and information
                           ($bb_agency_option_privacy == 0) ||
			 				
							//admin users
							(current_user_can( 'manage_options' )) ||
			 
							//  Must be logged as "Client" to view model list and profile information
							($bb_agency_option_privacy == 3 && is_client_profiletype()) ) {
                        
                         return true;
                       }
                 }
        return false;
}

/*
 * Check if profilet type ID is "Client" type
 */
function is_client_profiletype() {
	global $wpdb;

	$query = "SELECT `ProfileType` FROM ". table_agency_profile ." WHERE `ProfileUserLinked` = ". bb_agency_get_current_userid();
	
	$type = $wpdb->get_var($query);
	$title = $wpdb->get_var("SELECT `DataTypeTitle` FROM ". table_agency_data_type ." WHERE DataTypeID = ". $type);

	return (strtolower($title) == "client");
}

/**
 *
 * get height in cms
 *
 * @param int $i
 * @return int
 *
 */
function bb_agency_get_height($i) {
	$i = intval($i);
	$units =  (int)bb_agency_get_option('bb_agency_option_unittype');

	switch ($units) {
		case 1 :
			return $i;

		case 0 :
			return intval(2.54 * $i);
	}
}

/**
 *
 * convert inches to feet and inches or inches to cms
 *
 * @param int $i
 * @return string
 *
 */
function bb_agency_display_height($i) {

	if (!is_numeric($i) && preg_match('/cm$/', $i))
		return $i; // fix when height is set directly as 123cm

	$i = intval($i);

	$units =  (int)bb_agency_get_option('bb_agency_option_unittype');

	switch ($units) {
		case 1 :
			$feet = floor($i/12);
			$inches = $i % 12;
			return "$feet ft $inches in";

		case 0 :
			$cms = intval(2.54 * $i);
			return "$cms cm";
	}
}

/*
 * Self Delete Process for 
 * Users
 */

$bb_profile_delete = bb_agency_get_option('bb_agency_option_profiledeletion') ? bb_agency_get_option('bb_agency_option_profiledeletion') : 1;
 
if ($bb_profile_delete == 2 || $bb_profile_delete == 3) {
		
		add_action('admin_menu', 'Delete_Owner');	
		
		add_action('wp_before_admin_bar_render', 'self_delete');
		if (is_admin()) {
			add_action( 'admin_print_footer_scripts', 'delete_script' );
		} else {
			add_action('wp_footer', 'delete_script');
		}
}

function Delete_Owner() {
	
	$page_title = 'RB Account';
 	$menu_title = 'Account';
	$capability = 'subscriber';
	$menu_slug = 'delete_profile';

	add_object_page( $page_title, 
	                 $menu_title, 
			 $capability, 
			 $menu_slug,
			 'Profile_Account');
	
}

function Profile_Account() {  
    global $bb_profile_delete;
    echo "<h2>Account Settings</h2><br/>";
	echo "<input type='hidden' id='delete_opt' value='".$bb_profile_delete."'>";
    echo "<input id='self_del' type='button' name='remove' value='Remove My Profile' class='btn-primary'>";
	
}

function delete_script() {?>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery("#self_del").click(function() {

                var continue_delete = confirm("Are you sure you want to delete your profile?");

                if (continue_delete) {	
                        // ajax delete
					alert(jQuery('#delete_opt').val());	
                    jQuery.ajax({
                        type: "POST",
                        url: '<?php echo plugins_url( 'bb-agency/tasks/userdelete.php' , dirname(__FILE__) ); ?>',
                        dataType: "html",
                        data: { ID : "<?php echo bb_agency_get_current_userid(); ?>", OPT: jQuery('#delete_opt').val() },

                        beforeSend: function() {
                        },

                        error: function() {
                            setTimeout(function() {
                            alert("Process Failed. Please try again later.");	
                            }, 1000);
                        },	

                        success: function(data) {
                            if (data != "") {
                                setTimeout(function() {
                                	alert(data);
									//alert("Deletion success! You will now be redirected to our homepage.");
                                    window.location.href = "<?php echo get_bloginfo('wpurl'); ?>";
                                }, 1000);
                            } else {
                                setTimeout(function() {
                                    alert("Failed. Please try again later.");
                                }, 1000);
                            }
                        }
                    });
                }
            });	
        });
    </script>		

<?php
}
function self_delete() {

    global $wp_admin_bar;

    $href = get_bloginfo('wpurl');
    $title = '<div>' . '<div class="ab-item">User Profile</div></div>';
    $prof_href = $href . '/wp-admin/profile.php'; 
	$account = $href . '/wp-admin/admin.php?page=delete_profile';
	
    $wp_admin_bar->add_menu( array(
        'parent' => false,
        'id' => 'self_delete',
        'title' => __($title)
    ));
	
    $wp_admin_bar->add_menu(array(
        'parent' => 'self_delete',
        'id' => 'profile_manage',
        'title' => __('<a class="ab-item" href="'.$prof_href.'">Manage Profile</a>'),
    )); 
   $wp_admin_bar->add_menu(array(
        'parent' => 'self_delete',
        'id' => 'actual_delete',
        'title' => __('<a class="ab-item"  href="'.$account.'">Account Settings</a>'),
    ));

}

/**
 *
 * geocode an address
 *
 * @param string $address
 * @return array (lat,lng)
 *
 */
function bb_agency_geocode($address) {
	require_once(dirname(__FILE__).'/Classes/class.geocoder.php');

	return Geocoder::getLocation(urlencode($address));	
}

function bb_agency_map($lat, $lng, $name) {
	require_once(dirname(__FILE__).'/Classes/class.geocoder.php');

	Geocoder::getMap($lat, $lng, $name);
}

/**
 * bbagency get option
 *
 * @param string $name
 * @return mixed
 *
 */
function bb_agency_get_option($name = null, $reload = false) {
	global $bb_options;

	if (empty($bb_options) || $reload) {
		$bb_options = get_option('bb_agency_options');
	}

	if (!is_null($name)) {
		if (isset($bb_options[$name]))
			return $bb_options[$name];
		else
			return 0;
	} 
	else
		return $bb_options;
}

/**
 * bbagency update option
 *
 * @param string $key
 * @param string $value
 * @return boolean
 */
function bb_agency_update_option($name, $value) {
	global $bb_options;

	if (!$bb_options) {
		$bb_options = get_option('bb_agency_options');
	}

	$bb_options[$name] = $value;

	return update_option('bb_agency_options', $bb_options);
}

function bb_agency_reload_options() {
	return bb_agency_get_option(null, true);
}

function bb_agency_datatype_privacy($id) {
	global $wpdb;
	$table = table_agency_data_type;
	if ($id)
		return $wpdb->get_var("SELECT `DataTypePrivacy` FROM $table WHERE `DataTypeID` = $id");
}

function bb_agency_posted_value($field, $db = null) {
	echo bb_agency_get_posted_value($field, $db);
}

function bb_agency_get_posted_value($field, $db = null, $array = false) {
	if (isset($_REQUEST[$field]))
		return $_REQUEST[$field];

	elseif (!is_null($db) && isset($db[$field]))
		return $array ? explode(',', $db[$field]) : $db[$field];
}

/**
 *
 * get models
 *
 * @param int $type
 * @param int $output OBJECT | ARRAY_A
 * @return array (of objects)
 *
 */
function bb_agency_get_models($type = null, $output = OBJECT) {
	global $wpdb;

	// get models
    $t_profiles = table_agency_profile;
    
    $sql = "SELECT p.`ProfileID` AS ID, IF(p.`ProfileContactDisplay`, p.`ProfileContactDisplay`, CONCAT(p.`ProfileContactNameFirst`, ' ', p.`ProfileContactNameLast`)) AS name, p.* FROM $t_profiles p WHERE `ProfileType` <> " . bb_agency_CLIENTS_ID;
    
    if (!is_null($type))
    	$sql .= " AND p.`ProfileType` = '$type'";

    $sql .= " ORDER BY p.`ProfileContactDisplay` ASC";

    return $wpdb->get_results($sql, $output);
}

/**
 *
 * recategorize profile
 * when a mum has given birth
 *
 * @param int $id
 * @param mixed string|array $type
 *
 */
function bb_agency_recategorize_profile($id, $type) {
	global $wpdb;

	if (is_array($type))
		$type = implode(',', $type);

	$wpdb->update(
		table_agency_profile, 
		array('ProfileType' => $type), 
		array('ProfileID' => $id),
		array('%s'),
		array('%d')
	);
}

/**
 *
 * get data types
 */
function bb_agency_get_datatypes($public = true) {
	global $wpdb;
	return $wpdb->get_results('SELECT * FROM '. table_agency_data_type . ($public ? ' WHERE `DataTypeID` <> '.bb_agency_CLIENTS_ID : '').' ORDER BY `DataTypeTitle` ASC');
}

/**
 *
 * get talents
 */
function bb_agency_get_talents() {
	$talents = array();

	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM '. table_agency_data_talent);

	foreach ( $results as $result ) {
		$talents[ $result->DataTalentID ] = $result->DataTalentTitle;
	}

	return $talents;
}

/**
 *
 * get genres
 */
function bb_agency_get_genres() {
	$talents = array();

	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM '. table_agency_data_genre);

	foreach ( $results as $result ) {
		$talents[ $result->DataGenreID ] = $result->DataGenreTitle;
	}

	return $talents;
}

/**
 *
 * get abilities
 */
function bb_agency_get_abilities() {
	$talents = array();

	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM '. table_agency_data_ability);

	foreach ( $results as $result ) {
		$talents[ $result->DataAbilityID ] = $result->DataAbilityTitle;
	}

	return $talents;
}

/**
 *
 * save model card
 *
 * @param string $gallery
 * @param bool $lbda
 * @return string file path
 *
 */
function bb_agency_save_modelcard( $gallery, $lbda = false ) {

	if ($lbda)
		return bb_agency_save_lbda_modelcard( $gallery ); // do an LBDA model card

	require_once(bb_agency_BASEPATH.'/Classes/ModelCard.php');

	// check gallery directory exists
    $gallery = bb_agency_checkdir($gallery);

    // instantiate model card class
    $Card = new ModelCard($gallery);
    
    if ($Card->save(true))
        return $Card->filepath();
    
    bb_agency_adminmessage_former('Error saving model card: '.$Card->get_error(), true);
}

/**
 *
 * get imperial height
 *
 * @param int $height
 * @return string
 *
 */
function bb_agency_get_imperial_height( $height ) {
   	$heightfeet = floor($height/12); 
   	$heightinch = $height - floor($heightfeet*12);

   	return "$heightfeet ft $heightinch in";
}

/**
 *
 * delete job
 *
 * @param int $id
 * @return void
 *
 */
function bb_agency_delete_job( $id ) {

	global $wpdb;

    // delete job record
    $wpdb->delete( table_agency_job, array( `JobID` => $id ) );

    // delete booking records
    $wpdb->delete( table_agency_booking, array( 'JobID' => $id ) );
}

/**
 *
 * save LBDA model card
 *
 * @param string $gallery
 * @return string file path
 *
 */
function bb_agency_save_lbda_modelcard( $gallery ) {
	require_once(bb_agency_BASEPATH.'/Classes/LBDA_ModelCard.php');

	// check gallery directory exists
    $gallery = bb_agency_checkdir($gallery);

    // instantiate model card class
    $Card = new LBDA_ModelCard($gallery);
    
    if ($Card->save(true))
        return $Card->filepath();
    
    bb_agency_adminmessage_former('Error saving LBDA model card: '.$Card->get_error(), true);
}

/**
 *
 * debug
 *
 * @param string $message
 *
 */
function bb_agency_debug( $message ) {
	if (bb_agency_DEBUGGING)
		error_log( "DEBUG: $message" );
}
