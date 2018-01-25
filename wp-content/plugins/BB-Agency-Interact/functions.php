<?php
// *************************************************************************************************** //
// Admin Head Section 

	add_action('admin_head', 'bb_agencyinteract_admin_head');
		function bb_agencyinteract_admin_head(){
		  if( is_admin() ) {
			echo "<link rel=\"stylesheet\" href=\"". bb_agencyinteract_BASEDIR ."style/admin.css\" type=\"text/css\" media=\"screen\" />\n";
		  }
		}
	
// *************************************************************************************************** //
// Page Head Section

	add_action('wp_head', 'bb_agencyinteract_inserthead');
		// Call Custom Code to put in header
		function bb_agencyinteract_inserthead() {
		  if( !is_admin() ) {
			echo "<link rel=\"stylesheet\" href=\"". bb_agencyinteract_BASEDIR ."style/style.min.css\" type=\"text/css\" media=\"screen\" />\n";
		  }
		  if(!wp_script_is('jquery')) {
			echo "<script type=\"text/javascript\" src=\"". bb_agencyinteract_BASEDIR ."style/jquery.1.8.js\"></script>";
			
			} 
		}

	add_action('wp_enqueue_scripts', 'bb_agencyinteract_scripts');

		function bb_agencyinteract_scripts() {
			if (!is_admin()) {
				wp_enqueue_script( 'bb_agencyinteract_page', bb_agencyinteract_BASEDIR .'jquery-page.js', array('jquery'), null, true );
				wp_localize_script( 'bb_agencyinteract_page', 'page', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			}
		}

// *************************************************************************************************** //
// Handle Folders

	// Adding a new rule
	add_filter('rewrite_rules_array','bb_agencyinteract_rewriteRules');
		function bb_agencyinteract_rewriteRules($rules) {
			$newrules = array();
			$newrules['profile-member/(.*)$'] = 'index.php?type=$matches[1]';
			$newrules['profile-member/(.*)/(.*)$'] = 'index.php?type=$matches[0]';
			$newrules['profile-member'] = 'index.php?type=profileoverview';
			$newrules['profile-register/(.*)$'] = 'index.php?type=profileregister&typeofprofile=$matches[1]';
			$newrules['profile-register'] = 'index.php?type=profileregister';			
			$newrules['profile-login'] = 'index.php?type=profilelogin';
			return $newrules + $rules;
		}
		
	// Get Veriables & Identify View Type
	add_action( 'query_vars', 'bb_agencyinteract_query_vars' );
		function bb_agencyinteract_query_vars( $query_vars ) {
			$query_vars[] = 'type';
			$query_vars[] = 'typeofprofile';
			return $query_vars;
		}
	
	// Set Custom Template
	add_filter('template_include', 'bb_agencyinteract_template_include', 90, 1); 
		function bb_agencyinteract_template_include( $template ) {

			if ( !is_page() && !is_home() || is_admin() || is_user_logged_in() && current_user_can('manage_options'))
				return $template;

			if ($type = get_query_var( 'type' )) {
				bb_agencyinteract_debug( __FUNCTION__ . ' query => ' . $type );

				$theme = dirname(__FILE__) . '/theme/';

				switch( $type ) {
					case 'profileoverview' :
						return $theme . 'member-overview.php';

					case 'subscription' :
						return $theme . 'member-subscription.php';

					case 'availability' :
						return $theme . 'member-availability.php';

					case 'manage' :
						return $theme . 'member-profile.php';

					case 'media' :
						return $theme . 'member-media.php';

					case 'profileregister' :
						//if (!is_user_logged_in())
							return $theme . 'member-register.php';
						break;

					case 'profilelogin' :
						//if (!is_user_logged_in())
							return $theme . 'member-login.php';
						break;
				}
			}
			bb_agencyinteract_debug( __FUNCTION__ . " returning $template" );
			return $template;
		}
	
	
	// Remember to flush_rules() when adding rules
	add_filter('init','bb_agencyinteract_flushrules');
		function bb_agencyinteract_flushrules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

// *************************************************************************************************** //
// Handle Emails

	// Redefine user notification function  
	if ( !function_exists('wp_new_user_notification') ) {  
		function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {  
			$user = new WP_User($user_id);  
	  
			$user_login = stripslashes($user->user_login);  
			$user_email = stripslashes($user->user_email);  
	  
			$message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";  
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";  
			$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";  
	  
			@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);  
	  
			if ( empty($plaintext_pass) )  
				return;  
			$message  = __('Hi there,') . "\r\n\r\n";  
			$message .= sprintf(__("Thanks for joining %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n"; 
			$message .= get_option('home') ."/profile-login/\r\n"; 
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n"; 
			$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n"; 
			$message .= sprintf(__('If you have any problems, please contact us at %s.'), get_option('admin_email')) . "\r\n\r\n"; 
			$message .= __('Regards,')."\r\n";
			$message .= get_option('blogname') . __(' Team') ."\r\n"; 
			$message .= get_option('home') ."\r\n";
			if (bb_agency_TERMS) {
				$message .= "\r\n\r\n";
				$message .= sprintf(__('Any work undertaken is governed by our <a href="%s">Terms &amp; Conditions</a>', bb_agency_TEXTDOMAIN), bb_agency_TERMS);
			}
	 
	 		$headers = 'From: '. get_option('blogname') .' <'. get_option('admin_email') .'>' . "\r\n";
			wp_mail($user_email, sprintf(__('%s Registration Successful! Login Details'), get_option('blogname')), $message, $headers);  
	  
		}  
	}  
	// Make Directory for new profile
     function bb_agencyinteract_checkdir($ProfileGallery){
	      	
			if (!is_dir(bb_agency_UPLOADPATH . $ProfileGallery)) {
				mkdir(bb_agency_UPLOADPATH . $ProfileGallery, 0755);
				chmod(bb_agency_UPLOADPATH . $ProfileGallery, 0777);
			}
			return $ProfileGallery;
     }



// *************************************************************************************************** //
// Functions

	// Move Login Page	
	add_filter("login_init", "bb_agencyinteract_login_movepage", 10, 2);
		function bb_agencyinteract_login_movepage( $url ) {
			global $action;
		
			if (empty($action) || 'login' == $action) {
				wp_safe_redirect(get_bloginfo("wpurl"). "/profile-login/");
				die;
			}
		}

	// Rewrite Login
	add_action( 'init', 'bb_agencyinteract_login_rewrite' );
		function bb_agencyinteract_login_rewrite() {
			add_rewrite_rule(get_bloginfo("wpurl"). "profile-register/?$", 'wp-login.php', 'top');
		}
		

	// Redirect after Login
	add_filter('login_redirect', 'bb_agencyinteract_login_redirect', 10, 3);	
		function bb_agencyinteract_login_redirect() {
			if (isset($_REQUEST['redirect_to']) && $_REQUEST['redirect_to']) {
				wp_redirect($_REQUEST['redirect_to']);
				exit;
			}
			global $user_ID, $current_user, $wp_roles;
			if( $user_ID ) {
				$user_info = get_userdata( $user_ID ); 

				if( current_user_can( 'manage_options' )) {
					header("Location: ". get_bloginfo("wpurl"). "/wp-admin/");
				} elseif ( strtotime( $user_info->user_registered ) > ( time() - 172800 ) ) {
					// If user_registered date/time is less than 48hrs from now
					// Message will show for 48hrs after registration
					header("Location: ". get_bloginfo("wpurl"). "/profile-member/account/");
				} else {
					header("Location: ". get_bloginfo("wpurl"). "/profile-member/");
				}
			}
		}


    // function for checking male and female filter
	if ( !function_exists('gender_filter') ) {  
		function gender_filter($gender=0) {
		    global $wpdb;
			
			$gender = "SELECT GenderTitle FROM bb_agency_data_gender WHERE GenderID = ". $gender ." LIMIT 1";
			$results = $wpdb->get_results($gender);
			
			$gender_title = "";
			foreach($results as $gname){
				$gender_title = strtolower($gname->GenderTitle);
			}
			
			if($gender_title == 'male'){
				return "male_filter";
			}elseif($gender_title == 'female'){
				return "female_filter";
			}else{
				return "";
			}
		}
	}

	// retrieving value of saved fields for edit
	if ( !function_exists('retrieve_datavalue') ) {  
		function retrieve_datavalue($field="",$customID=0,$ID=0,$type="", $val="") {
			global $wpdb;
			/* 
			 *    Get data for displaying and pass to array
			 *    for comparison
			 */
			 if($ID != 0){
					 
				if($type == "dropdown"){
					$result = $wpdb->get_results("SELECT ProfileCustomValue FROM "
							. table_agency_customfield_mux .
							" WHERE ProfileCustomID = ". $customID .
							" AND ProfileCustomValue = '" . $val . "' "
							." AND ProfileID = "
							. $ID);
				} else {
					$result = $wpdb->get_results("SELECT ProfileCustomValue FROM "
							. table_agency_customfield_mux .
							" WHERE ProfileCustomID = ". $customID ." AND ProfileID = "
							. $ID);
				}

				foreach($result as $row){
					if($type == "textbox"){
					 return $row->ProfileCustomValue;
					} elseif($type == "dropdown") {
					 return "selected";
					}
				}

				if($type == "textbox"){
				     return $field;
				} elseif($type == "dropdown") {
				     return "";
				}
					   
			 } else {
			 	
				if($type == "textbox"){
					 return $field;
				} elseif($type == "dropdown") {
					 return "";
				}
				
			 }
		}
	}	

	// retrieving data type title
	if ( !function_exists('retrieve_title') ) {  
		function retrieve_title($id=0) {
		   global $wpdb;
		   
		   /* 
		    * return title
			*/
            $type = $wpdb->get_var( "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID = " . $id );

            if ($type)
            	return $type;

		}
	}

	if (!function_exists('get_content_class')) {
		function get_content_class() {
			$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
			$bb_agencyinteract_option_profilemanage_sidebar = $bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'];

			if (is_user_logged_in()) {
				return "eight";
			} else {
				return "twelve";
			}
		}
	}

	// set primary profile image
	add_action('wp_ajax_set_primary_image', 'set_primary_image');

	if (!function_exists('set_primary_image')) {
		function set_primary_image() {
			global $wpdb;

			$image = filter_input(INPUT_POST, 'image');
			$profile = filter_input(INPUT_POST, 'profile');

			if ($image && $profile) {
				// remove existing primary flag
				$wpdb->update( 
					table_agency_profile_media, 
					array( 'ProfileMediaPrimary' => 0 ), 
					array( 'ProfileID' => $profile, 'ProfileMediaPrimary' => 1 ) 
				);
				// add primary flag to selected image
				$wpdb->update( 
					table_agency_profile_media, 
					array( 'ProfileMediaPrimary' => 1 ), 
					array( 'ProfileID' => $profile, 'ProfileMediaID' => $image ) 
				);
			}
		}
	}

	function bb_agencyinteract_debug( $message ) {
		if (bb_agencyinteract_DEBUG)
			error_log( 'BB Agency Interact: ' . $message );
	}



?>