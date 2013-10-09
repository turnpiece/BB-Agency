<?php
global $wpdb;
$bb_agency_options_arr = get_option('bb_agency_options');
	$bb_agency_option_profilenaming = $bb_agency_options_arr['bb_agency_option_profilenaming'];
	$bb_agency_option_unittype = $bb_agency_options_arr['bb_agency_option_unittype'];

	//Custom fields display options
	$bb_agency_option_customfields_profilepage = $bb_agency_options_arr['bb_agency_option_customfield_profilepage'];
	$bb_agency_option_customfields_searchpage = $bb_agency_options_arr['bb_agency_option_customfield_searchpage'];
	$bb_agency_option_customfields_loggedin_all = $bb_agency_options_arr['bb_agency_option_customfield_loggedin_all'];
	$bb_agency_option_customfields_loggedin_admin = $bb_agency_options_arr['bb_agency_option_customfield_loggedin_admin'];
	
	if (isset($_REQUEST['ProfileType']) && !empty($_REQUEST['ProfileType'])) { $_SESSION['ProfileType'] = $_REQUEST['ProfileType']; }
	if (isset($DataTypeID) && !empty($DataTypeID)) { $_SESSION['ProfileType'] = $DataTypeID; }
	if (isset($_REQUEST['ProfileGender']) && !empty($_REQUEST['ProfileGender'])) {  $_SESSION['ProfileGender'] = $_REQUEST['ProfileGender']; }
	
	// fix advanced search to include
	// custom fields from search fields
	if(isset($_GET[srch])){
	   $profilesearch_layout = "advanced"; 	
	}
	
   	if ($profilesearch_layout == "condensed" || $profilesearch_layout == "simple") {
	
		echo "		<div id=\"profile-search-form-condensed\" class=\"rbsearch-form\">\n";
		echo "        	<form method=\"post\" id=\"search-form-condensed\" action=\"". get_bloginfo("wpurl") ."/profile-search/\">\n";
		echo "        		<div><input type=\"hidden\" name=\"action\" value=\"search\" /></div>\n";

		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileFirstName\">". __("First Name", bb_agency_TEXTDOMAIN) ."</label>\n";
	      echo "		 				<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" />\n";
	      echo "	 				</div>\n";
/*		
		echo "	 				<div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileLastName\">". __("Last Name", bb_agency_TEXTDOMAIN) ."</label>\n";
	      echo "						 <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" />\n";
		echo "					 </div>\n";		
*/		
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileType\">". __("Type", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", bb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileType']) {
													if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
/*
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileGender\">". __("Gender", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("All Gender", bb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = mysql_query($query2);
											while ($dataGender = mysql_fetch_array($results2)) {
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
											}
	      echo "				        </select>\n";
		echo "				    </div>\n";
		
		echo "				    <fieldset class=\"search-field multi\">";
		echo "				        <legend>". __("Age", bb_agency_TEXTDOMAIN) . "</legend>";
		
		echo "				    <div>";
		echo "				        <label for=\"ProfileDateBirth_min\">". __("Min", bb_agency_TEXTDOMAIN) . "</label>";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>";
		echo "				    </fieldset>";
*/                
		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div class=\"search-field submit\">";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/'\" />";
		
		echo "				<input type=\"submit\" name=\"advanced_search\" value=\"". __("Advanced Search", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search/?srch=1'\" />";
		
		
		/* Phel Comment
		echo "					<input type=\"submit\" value=\"". __("Search Profiles", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />";
        echo '					<a href="'. get_bloginfo('siteurl') .'/profile-search/?srch=1">'. __("Advanced Search", bb_agency_TEXTDOMAIN) . '</a>';*/
		echo "				</div>\n";
		echo "        	</form>\n";
		echo "		</div>\n";
   } else {
	   // Advanced
		echo "  <form method=\"post\" id=\"search-form-advanced\" action=\"". get_bloginfo("wpurl") ."/profile-search/\" class=\"rbsearch-form\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"bb_agency_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
		
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileFirstName\">". __("First Name", bb_agency_TEXTDOMAIN) ."</label>\n";
	      echo "		 				<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" />\n";
	      echo "	 				</div>\n";
/*		
		echo "	 				<div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileLastName\">". __("Last Name", bb_agency_TEXTDOMAIN) ."</label>\n";
	      echo "						 <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" />\n";
		echo "					 </div>\n";
*/	
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileType\">". __("Type", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "						<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", bb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".selected($_SESSION['ProfileType'],$dataType["DataTypeID"] ,false).">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>";
		echo "				    </div>\n";
/*		
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileGender\">". __("Gender", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				       <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("All Gender", bb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = mysql_query($query2);
											while ($dataGender = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileGender']) {
													if ($dataGender["GenderTitle"] ==  $_SESSION['ProfileGender']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"] ,false).">". $dataGender["GenderTitle"] ."</option>";
											}
	      echo "				        </select>\n";
		echo "				    </div>\n";
		
		echo "				    <fieldset class=\"search-field multi\">";
		echo "				        <legend>". __("Age", bb_agency_TEXTDOMAIN) . "</legend>";
		
		echo "				    <div>";
		echo "				        <label for=\"ProfileDateBirth_min\">". __("Min", bb_agency_TEXTDOMAIN) . "</label>";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>";
		echo "				    </fieldset>";
*/
		echo "				    <fieldset class=\"search-field multi\">";
		echo "				        <legend>". __("Due date", bb_agency_TEXTDOMAIN) . "</legend>";
		
		echo "				    <div>";
		echo "				        <label for=\"ProfileDateDue_min\">". __("From", bb_agency_TEXTDOMAIN) . "</label>";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateDue_min\" name=\"ProfileDateDue_min\" value=\"". $_SESSION['ProfileDateDue_min'] ."\" /></div>";
		echo "				        <div><label for=\"ProfileDateDue_max\">". __("To", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateDue_max\" name=\"ProfileDateDue_max\" value=\"". $_SESSION['ProfileDateDue_max'] ."\" /></div>";
		echo "				    </fieldset>";							
			
		if($bb_agency_option_customfields_searchpage == 1 || $bb_agency_option_customfield_profilepage == 1 OR $_POST['advanced_search']){ // Show on Search Page or Profile Page

			if(is_user_logged_in()) { // All with loggedin permissions

			 // Show custom fields for admins only.
				if($bb_agency_option_customfields_loggedin_admin == 1 && current_user_can("level_10")) { 
				    //include("include-custom-fields.php");
					//echo "1-";
				}
				// Show custom fields for loggedin members only
				else {
					//include("include-custom-fields.php");
					 //echo "3-";
				}

			} else { // All with non-logged here
				
				// Show custom fields to public
			    if($_REQUEST["action"] != 'search' || $_REQUEST["action"] ==''){
				    	//include("include-custom-fields.php");
						$profilesearch_layout = "";
					}
				}
			
		}
		
		if(isset($_GET['srch'])){
				    	include("include-custom-fields.php");
								echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileCity\">". __("Town", bb_agency_TEXTDOMAIN) ."</label>\n";
	    echo "		 				<input type=\"text\" id=\"ProfileCity\" name=\"ProfileCity\" value=\"". $_SESSION["ProfileCity"] ."\" />\n";
	    echo "	 				</div>\n";

		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileState\">". __("County", bb_agency_TEXTDOMAIN) ."</label>\n";
	    echo "		 				<input type=\"text\" id=\"ProfileState\" name=\"ProfileState\" value=\"". $_SESSION["ProfileState"] ."\" />\n";
	    echo "	 				</div>\n";

		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileZip\">". __("Post code", bb_agency_TEXTDOMAIN) ."</label>\n";
	    echo "		 				<input type=\"text\" id=\"ProfileZip\" name=\"ProfileZip\" value=\"". $_SESSION["ProfileZip"] ."\" />\n";
	    echo "	 				</div>\n";
		}
		
		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div class=\"search-field submit\">";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/'\" />";
		echo '<input type="reset" class=\"button-primary\" value="Empty Form">';
		if(!isset($_GET[srch])){
		echo "				<input type=\"submit\" name=\"advanced_search\" value=\"". __("Advanced Search", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search/?srch=1'\" />";}else{
		
		echo "				<input type=\"submit\" name=\"basic_search\" value=\"". __("Basic Search", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search'\" />";
		}

		echo "				</div>";
		if(isset($_GET['srch'])){ echo'<div></div>'; $style="style='margin-left:0px'"; }else{$style="";}
		
		echo "        	</form>\n";
		
	    	
   }
?>