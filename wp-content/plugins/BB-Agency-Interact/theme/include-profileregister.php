<?php
	global $wpdb;

    // profile type
    $ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
    $ptype = retrieve_title($ptype);
    $ProfileGender = get_user_meta($current_user->id, "bb_agency_interact_pgender", true);
    echo '<input name="ProfileGender" type="hidden" value="'.$ProfileGender.'">'; 

    echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/profile-member/account/\" style=\"width: 400px;\">\n";
	echo "<input type=\"hidden\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"". $current_user->user_email ."\" />\n";
	echo "<input type=\"hidden\" id=\"ProfileUserLinked\" name=\"ProfileUserLinked\" value=\"". $current_user->id ."\" />\n";
    echo "<input type=\"hidden\" id=\"ProfileGender\" name=\"ProfileGender\" value=\"".$ProfileGender ."\" />\n";
	echo "<input type=\"hidden\" id=\"ProfileType\" name=\"ProfileType\" value=\"".get_user_meta($current_user->id, "bb_agency_interact_profiletype", true) ."\" />\n";

	echo " <table class=\"form-table\">\n";
	echo "  <tbody>\n";
	echo "    <tr>\n";
	echo "		<td scope=\"row\" class=\"label\" colspan=\"2\"><h3>". __("Contact Information", bb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("First Name", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $current_user->first_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Last Name", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $current_user->last_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Phone", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td class=\"labelled\">\n";
	echo "			<label>Home:</label> <input type=\"text\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"". $ProfileContactPhoneHome ."\" /><br />\n";
	echo "			<label>Mobile:</label> <input type=\"text\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"". $ProfileContactPhoneCell ."\" /><br />\n";
	echo "			<label>Work:</label> <input type=\"text\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"". $ProfileContactPhoneWork ."\" /><br />\n";
	echo "		</td>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Website", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"". $ProfileContactWebsite ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Public Information
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\" colspan=\"2\"><h3>". __("Public Information", bb_agencyinteract_TEXTDOMAIN) ."</h3>The following information may appear in profile pages.</th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Birth date", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
				  /* Month */ 
				  $monthName = array(1=> "January", "February", "March","April", "May", "June", "July", "August","September", "October", "November", "December"); 
	echo "		  <select name=\"ProfileDateBirth_Month\" id=\"ProfileDateBirth_Month\">\n";
					for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++ ) { 	
	echo "			<option value=\"". $currentMonth ."\">". $monthName[$currentMonth] ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Day */ 
	echo "		  <select name=\"ProfileDateBirth_Day\" id=\"ProfileDateBirth_Day\">\n";
					for ($currentDay = 1; $currentDay <= 31; $currentDay++ ) { 	
	echo "			<option value=\"". $currentDay ."\">". $currentDay ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Year */ 
	echo "		  <select name=\"ProfileDateBirth_Year\" id=\"ProfileDateBirth_Year\">\n";
					for ($currentYear = 1940; $currentYear <= 2010; $currentYear++ ) { 	
	echo "			<option value=\"". $currentYear ."\">". $currentYear ."</option>\n";
					}
	echo "		  </select>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Due date", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
				  /* Month */ 
				  $monthName = array(1=> "January", "February", "March","April", "May", "June", "July", "August","September", "October", "November", "December"); 
	echo "		  <select name=\"ProfileDateDue_Month\" id=\"ProfileDateDue_Month\">\n";
					for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++ ) { 	
	echo "			<option value=\"". $currentMonth ."\">". $monthName[$currentMonth] ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Day */ 
	echo "		  <select name=\"ProfileDateDue_Day\" id=\"ProfileDateDue_Day\">\n";
					for ($currentDay = 1; $currentDay <= 31; $currentDay++ ) { 	
	echo "			<option value=\"". $currentDay ."\">". $currentDay ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Year */ 
	echo "		  <select name=\"ProfileDateDue_Year\" id=\"ProfileDateDue_Year\">\n";
					for ($currentYear = 2013; $currentYear <= 2015; $currentYear++ ) { 	
	echo "			<option value=\"". $currentYear ."\">". $currentYear ."</option>\n";
					}
	echo "		  </select>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Private Information
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\" colspan=\"2\"><h3>". __("Private Information", bb_agencyinteract_TEXTDOMAIN) ."</h3>". __("The following information will NOT appear in public areas and is for administrative use only.", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "	  </tr>\n";

	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Street", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Town", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("County", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationState\" name=\"ProfileLocationState\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Post code", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Country", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCountry\" name=\"ProfileLocationCountry\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";

	/*
	 * Get Private custom Fields Here
	 *
	 */
    $ProfileInformation = "1"; // Private fields only

	$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, 
	                  ProfileCustomShowLogged, ProfileCustomShowAdmin,ProfileCustomShowRegistration FROM "
	         . table_agency_customfields ." WHERE ProfileCustomView = ". $ProfileInformation ." ORDER BY ProfileCustomOrder ASC";
	
	$results1 = $wpdb->get_results($query1);
	$count1 = count($results1);
	$pos = 0;

	foreach ($results1 as $data1) { 
		/*
		* Get Profile Types to
		* filter models from clients
		*/
		$permit_type = false;

		$PID = $data1->ProfileCustomID;

		$get_types = "SELECT ProfileCustomTypes FROM ". table_agency_customfields_types .
		            " WHERE ProfileCustomID = " . $PID;

		$result = $wpdb->get_results($get_types);

		foreach ( $result as $p ){
		    $types = $p->ProfileCustomTypes;			    
		}

		$types = explode(",",$types); 

		if (in_array($ptype,$types)){ 
			$permit_type=true; 
		}
                        
		if ( ($data1->ProfileCustomShowGender == $ProfileGender) || ($data1->ProfileCustomShowGender == 0) 
                              && $permit_type == true )  {

			include("view-custom-fields.php");
		}
	}
	
	$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
	$bb_agencyinteract_option_registerallow = (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallow'];

	
	  if ($bb_agencyinteract_option_registerallow  == 1) {
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Username(cannot be changed.)", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		if(isset($current_user->user_login)){
		echo "			<input type=\"text\" id=\"ProfileUsername\"  disabled=\"disabled\" value=\"".$current_user->user_login."\" />\n";
		echo "                  <input type=\"hidden\" name=\"ProfileUsername\" value=\"".$current_user->user_login."\"  />";
		}else{
		echo "			<input type=\"text\" id=\"ProfileUsername\"  name=\"ProfileUsername\" value=\"\" />\n";	
		}
		echo "		</td>\n";
		echo "	  </tr>\n";
	 }
	
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Password (Leave blank to keep same password)", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"ProfilePassword\" name=\"ProfilePassword\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" class=\"label\">". __("Password (Retype to Confirm)", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"ProfilePasswordConfirm\" name=\"ProfilePasswordConfirm\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", bb_agencyinteract_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	echo "</form>\n";
?>