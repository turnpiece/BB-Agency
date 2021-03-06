<?php
	global $user_ID; 
	global $current_user;
	global $wpdb;
	get_currentuserinfo();
	$ProfileUserLinked = $current_user->id;
    $ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
	$ptype = retrieve_title($ptype);
	$ProfileGender  = get_user_meta($current_user->id, "bb_agency_interact_pgender", true);

	// Get Settings
	$bb_agency_options_arr = get_option('bb_agency_options');
	
	$bb_agency_option_showsocial 			= $bb_agency_options_arr['bb_agency_option_showsocial'];
	$bb_agency_option_profilenaming 		= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];
	$bb_agency_option_locationtimezone 		= (int)$bb_agency_options_arr['bb_agency_option_locationtimezone'];
      
	$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
	$bb_agencyinteract_option_registerallow = bb_agencyinteract_ALLOW_REGISTRATION;

	// Get Data
	$results = bb_agencyinteract_get_profile( $ProfileUserLinked );

	$count = count($results);

	foreach ($results as $data) {
		
		// $ProfileGender = $data->ProfileGender;
		$ProfileID					= $data->ProfileID;
		$ProfileUserLinked			= $data->ProfileUserLinked;
		$ProfileGallery				= stripslashes($data->ProfileGallery);
		$ProfileContactDisplay		= stripslashes($data->ProfileContactDisplay);
		$ProfileContactNameFirst	= stripslashes($data->ProfileContactNameFirst);
		$ProfileContactNameLast		= stripslashes($data->ProfileContactNameLast);
		$ProfileContactEmail		= stripslashes($data->ProfileContactEmail);
		$ProfileContactWebsite		= stripslashes($data->ProfileContactWebsite);
		$ProfileContactLinkFacebook	= stripslashes($data->ProfileContactLinkFacebook);
		$ProfileContactLinkTwitter	= stripslashes($data->ProfileContactLinkTwitter);
		$ProfileContactLinkYouTube	= stripslashes($data->ProfileContactLinkYouTube);
		$ProfileContactLinkFlickr	= stripslashes($data->ProfileContactLinkFlickr);
		$ProfileContactPhoneHome	= stripslashes($data->ProfileContactPhoneHome);
		$ProfileContactPhoneCell	= stripslashes($data->ProfileContactPhoneCell);
		$ProfileContactPhoneWork	= stripslashes($data->ProfileContactPhoneWork);

		$ProfileDateBirth	    	= stripslashes($data->ProfileDateBirth);
		$ProfileDateDue	    		= stripslashes($data->ProfileDateDue);
		$ProfileLocationStreet		= stripslashes($data->ProfileLocationStreet);
		$ProfileLocationCity		= stripslashes($data->ProfileLocationCity);
		$ProfileLocationState		= stripslashes($data->ProfileLocationState);
		$ProfileLocationZip			= stripslashes($data->ProfileLocationZip);
		$ProfileLocationCountry		= stripslashes($data->ProfileLocationCountry);
		$ProfileDateUpdated			= $data->ProfileDateUpdated;

		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/profile-member/account/\">\n";
		echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"". $ProfileID ."\" />\n";
		echo " <table class=\"form-table\">\n";
		echo "  <tbody>\n";
		echo "    <tr>\n";
		echo "		<td colspan=\"2\" scope=\"row\"><h3>". __("Contact Information", bb_agencyinteract_TEXTDOMAIN) ."</h3></th>\n";
		echo "	  </tr>\n";
		echo "<input type=\"hidden\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"". $ProfileGallery ."\" />\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("First Name", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $ProfileContactNameFirst ."\" />\n";
		echo "		</td>\n";

		// disable editing of last name
		echo "<input type=\"hidden\" name=\"ProfileContactNameLast\" value=\"". $ProfileContactNameLast ."\" />\n";
		$queryShowGender = $wpdb->get_results("SELECT `GenderID`, `GenderTitle` FROM " .  table_agency_data_gender . " GROUP BY `GenderTitle`");
		if (!empty($queryShowGender)) {
			echo "    <tr valign=\"top\">\n";
			echo "		<td scope=\"row\" class=\"label\">". __("Gender", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
			echo "		<td>";
			
			echo "<select name=\"ProfileGender\">";
			echo "<option value=\"\">---</option>";

			foreach($queryShowGender as $dataShowGender){									
				echo "<option value=\"".$dataShowGender->GenderID."\" ". selected($ProfileGender ,$dataShowGender->GenderID,false).">".$dataShowGender->GenderTitle."</option>";										
			}
			
			echo "</select>";
			echo "		</td>\n";
			echo "	  </tr>\n";
		}
		// Private Information
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\" colspan=\"2\"><h3>". __("Private Information", bb_agencyinteract_TEXTDOMAIN) ."</h3>The following information will appear only in administrative areas.</th>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Email Address", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"". $ProfileContactEmail ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Birth date", bb_agencyinteract_TEXTDOMAIN) ." <em>YYYY-MM-DD</em></th>\n";
		echo "		<td>\n";
		echo "			<input class=\"bbdatepicker\" type=\"text\" id=\"ProfileDateBirth\" name=\"ProfileDateBirth\" value=\"". $ProfileDateBirth ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		if (defined('bb_agency_SITETYPE') && bb_agency_SITETYPE == 'bumps') :
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Due date", bb_agencyinteract_TEXTDOMAIN) ." <em>YYYY-MM-DD</em></th>\n";
		echo "		<td>\n";
		echo "			<input class=\"bbdatepicker\" type=\"text\" id=\"ProfileDateDue\" name=\"ProfileDateDue\" value=\"". $ProfileDateDue ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		endif;
		// Address
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Street", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" value=\"". $ProfileLocationStreet ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Town", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" value=\"". $ProfileLocationCity ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("County", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileLocationState\" name=\"ProfileLocationState\" value=\"". $ProfileLocationState ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Post code", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" value=\"". $ProfileLocationZip ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Country", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileLocationCountry\" name=\"ProfileLocationCountry\" value=\"". $ProfileLocationCountry ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Phone", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td class=\"labelled\">\n";
		echo "			<label>Home:</label> <input type=\"text\" style=\"width: 144px;\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"". $ProfileContactPhoneHome ."\" /><br />\n";
		echo "			<label>Mobile:</label> <input type=\"text\" style=\"width: 144px;\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"". $ProfileContactPhoneCell ."\" /><br />\n";
		echo "			<label>Work:</label> <input type=\"text\" style=\"width: 144px;\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"". $ProfileContactPhoneWork ."\" /><br />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Website", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"". $ProfileContactWebsite ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		
		// Include Profile Customfields
		$ProfileInformation = "1"; // Private fields only

		$query = "SELECT `ProfileCustomID`, `ProfileCustomTitle`, `ProfileCustomType`, `ProfileCustomOptions`, `ProfileCustomOrder`, `ProfileCustomView`, `ProfileCustomShowGender`, `ProfileCustomShowProfile`, `ProfileCustomShowSearch`, `ProfileCustomShowLogged`, `ProfileCustomShowAdmin`, `ProfileCustomShowRegistration` FROM ". table_agency_customfields ." WHERE ProfileCustomView = ". $ProfileInformation ." ORDER BY ProfileCustomOrder ASC";

		$results = $wpdb->get_results($query);
		$count = count($results);
		$pos = 0;
		
		foreach ($results as $data) { 
           /*
            * Get Profile Types to
            * filter models from clients
            */
            $permit_type = false;

            $PID = $data->ProfileCustomID;

            $get_types = "SELECT `ProfileCustomTypes` FROM ". table_agency_customfields_types .
                        " WHERE `ProfileCustomID` = " . $PID;

            $types = $wpdb->get_var($get_types);

            if($types != "" || $types != NULL){
                $types = explode(",",$types); 
                if(in_array($ptype,$types)){ 
                	$permit_type = true; 
                } 
            } 
                            
			if ( ($data->ProfileCustomShowGender == $ProfileGender) || 
				($data->ProfileCustomShowGender == 0) && $permit_type == true )  {
				include("view-custom-fields.php");
			}
		}


		// Show Social Media Links
		if (bb_agency_get_option('bb_agency_options_showsocial')) { 
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\" colspan=\"2\"><h3>". __("Social Media Profiles", bb_agencyinteract_TEXTDOMAIN) ."</h3></th>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Facebook", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactLinkFacebook\" name=\"ProfileContactLinkFacebook\" value=\"". $ProfileContactLinkFacebook ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Twitter", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactLinkTwitter\" name=\"ProfileContactLinkTwitter\" value=\"". $ProfileContactLinkTwitter ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("YouTube", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactLinkYouTube\" name=\"ProfileContactLinkYouTube\" value=\"". $ProfileContactLinkYouTube ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Flickr", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"ProfileContactLinkFlickr\" name=\"ProfileContactLinkFlickr\" value=\"". $ProfileContactLinkFlickr ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		} 
		if ($bb_agencyinteract_option_registerallow  == 1) {
			echo "    <tr valign=\"top\">\n";
			echo "		<td scope=\"row\" class=\"label\">". __("Username(cannot be changed.)", bb_agencyinteract_TEXTDOMAIN) ."</th>\n";
			echo "		<td>\n";
			if(isset($current_user->user_login)){
			echo "			<input type=\"text\" id=\"ProfileUsername\"  name=\"ProfileUsername\" disabled=\"disabled\" value=\"".$current_user->user_login."\" />\n";
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
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\" class=\"label\">". __("Last updated ", bb_agencyinteract_TEXTDOMAIN) ." ". bb_agency_makeago(bb_agency_convertdatetime($ProfileDateUpdated), $bb_agency_option_locationtimezone) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		echo "			<input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", bb_agencyinteract_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "	</tbody>\n";
		echo " </table>\n";
		echo "</form>\n";
	}
?>