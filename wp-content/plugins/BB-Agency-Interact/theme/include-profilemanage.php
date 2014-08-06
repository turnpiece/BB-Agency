<?php
	global $wpdb;
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$ProfileUserLinked = $current_user->id;
	// Get Settings
	$bb_agency_options_arr = get_option('bb_agency_options');
		$bb_agency_option_unittype  			= $bb_agency_options_arr['bb_agency_option_unittype'];
		$bb_agency_option_locationtimezone 		= (int)$bb_agency_options_arr['bb_agency_option_locationtimezone'];
	// Get Values
	$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileUserLinked='$ProfileUserLinked'";
	$data = $wpdb->get_row($query, ARRAY_A);

    /*
     * Get profile type and Gender
     */
    $ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
	$ptype = retrieve_title($ptype);
    $ProfileGender = get_user_meta($current_user->id, "bb_agency_interact_pgender", true);
    if (empty($ProfileGender)) {
    	update_usermeta($current_user->id, 'bb_agency_interact_pgender', $data['ProfileGender']);
    	$ProfileGender = $data['ProfileGender'];
    }
    $ProfileTypeArray = array();
    $profileType = ""; 
    $ptype1 = get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
    if (empty($ptype)) {
    	// Model or Client
		update_usermeta($current_user->id, 'bb_agency_interact_profiletype', $data['ProfileType']);
		$ptype = $data['ProfileType'];
    }
    $ProfileTypeArray = explode(",", $ptype1);
    $query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
    $results3 = mysql_query($query3);
    $count3 = mysql_num_rows($results3);
	$i=1;
	$ptypes = array();
    while ($data3 = mysql_fetch_array($results3)) {
        if (in_array($data3['DataTypeID'], $ProfileTypeArray)) {
             $ptypes[] =  $data3['DataTypeTitle'];
        }
    }
    $profileType = implode(', ', $ptypes);
                
	$ProfileID					=$data['ProfileID'];
	$ProfileUserLinked			=$data['ProfileUserLinked'];
	$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
	$ProfileType				=stripslashes($data['ProfileType']);
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/profile-member/manage/\">\n";
	echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"". $ProfileID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ProfileType\" value=\"". $ptype1 ."\" />\n";
	

	echo "<p>";
	echo "<label style=\"width:200px; float:left;\" for=\"classification\">". __("Classification:", bb_agencyinteract_TEXTDOMAIN) ."</label>";
	echo "		".$profileType;
	echo "</p>";

	/*
	 *   added this new custom field display 
	 */
	$bb_agency_option_profilenaming 		= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];
		
	$query3 = "SELECT * FROM ". table_agency_customfields ." 
			   WHERE ProfileCustomView = 0 AND ProfileCustomShowRegistration = 1 ORDER BY ProfileCustomOrder";

	$results3 = mysql_query($query3) or die(mysql_error());
	$count3 = mysql_num_rows($results3);
	
	while ($data3 = mysql_fetch_assoc($results3)) {
		/*
                 * Get Profile Types to
                 * filter models from clients
                 */
                $permit_type = false;
		
		$PID = $data3['ProfileCustomID'];
		
		$get_types = "SELECT ProfileCustomTypes FROM ". table_agency_customfields_types .
		             " WHERE ProfileCustomID = " . $PID;
						
		$result = mysql_query($get_types);
		$types = "";				
		while ( $p = mysql_fetch_array($result)){	
			$types = str_replace("_", " ", $p['ProfileCustomTypes']);	    
		}
		
		if ($types != "" || $types != NULL) {
		   	$types = explode(",",$types); 
		   	if(in_array($ptype,$types)){ 
		   		$permit_type=true; 
		   	} 
		} 
		
		echo'<input type="hidden" name="aps12" value="'.$data3["ProfileCustomShowGender"].'" >';
		
		if (($data3["ProfileCustomShowGender"] == $ProfileGender) || ($data3["ProfileCustomShowGender"] == 0) 
		    && $permit_type == true ) {
		
		$ProfileCustomTitle = $data3['ProfileCustomTitle'];
		$ProfileCustomType  = $data3['ProfileCustomType'];
	       
		 //  SET Label for Measurements
		 //  Imperial(in/lb), Metrics(ft/kg)
		 $bb_agency_options_arr = get_option('bb_agency_options');
		 $bb_agency_option_unittype  = $bb_agency_options_arr['bb_agency_option_unittype'];
		 $measurements_label = "";

		 if ($ProfileCustomType == 7) { //measurements field type
	        if($bb_agency_option_unittype ==0){ // 0 = Metrics(ft/kg)	
				if($data3['ProfileCustomOptions'] == 1){
					 $measurements_label  ="<em> (cm)</em>";
				}elseif($data3['ProfileCustomOptions'] == 2){
					 $measurements_label  ="<em> (kg)</em>";
				}elseif($data3['ProfileCustomOptions'] == 3){
				  $measurements_label  ="<em> (In Inches/Feet)</em>";
				}
		    }elseif($bb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
				if($data3['ProfileCustomOptions'] == 1){
					 $measurements_label  ="<em> (In Inches)</em>";
				}elseif($data3['ProfileCustomOptions'] == 2){
				  $measurements_label  ="<em> (In Pounds)</em>";
				}elseif($data3['ProfileCustomOptions'] == 3){
				  $measurements_label  ="<em> (In Inches/Feet)</em>";
				}
			}			
		 }  
		 
		 	 
		 echo "<p class=\"form-".strtolower(trim($data3['ProfileCustomTitle']))." "
		       .gender_filter($data3['ProfileCustomShowGender'])."\">\n"; 
		 
		 echo "<label style='width:200px; float:left;' for=\"".strtolower(trim($data3['ProfileCustomTitle']))."\">"
		       . __( $data3['ProfileCustomTitle'].$measurements_label, bb_agencyinteract_TEXTDOMAIN) 
			   ."</label>\n";		  
		 
		 if ($ProfileCustomType == 1) { //TEXT
			
			echo '<input type="text" name="ProfileCustomID'. $data3['ProfileCustomID'] 
			     .'" value="'. retrieve_datavalue($_REQUEST["ProfileCustomID". $data3['ProfileCustomID']],
				 									$data3['ProfileCustomID'],$ProfileID,"textbox") 
				 .'" /><br />';
			}
			
		elseif ($ProfileCustomType == 2) { // Min Max
			
			$ProfileCustomOptions_String = str_replace(",",":",
			                               strtok(strtok($data3['ProfileCustomOptions'],"}"),"{"));
			
			list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,
			$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) 
			= explode(":", $ProfileCustomOptions_String);
		 
			if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) {
					
				echo "<br /><br /> <label style='width:200px; float:left;' for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">"
				     . __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] 
				     ."\" value=\"". 
					 retrieve_datavalue($ProfileCustomOptions_Min_value,
											$data3['ProfileCustomID'],$ProfileID,"textbox")
					  ."\" />\n";
				echo "<br /><br /><br /><label style='width:200px; float:left;' for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">"
				    . __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\""
				    .  retrieve_datavalue($ProfileCustomOptions_Max_value,
											  $data3['ProfileCustomID'],$ProfileID,"textbox") ."\" /><br />\n";
			
			} else {
				echo "<br /><br />  <label style='width:200px; float:left;' for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">"
				     . __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\""
				     .retrieve_datavalue($_REQUEST["ProfileCustomID". $data3['ProfileCustomID']],
												$data3['ProfileCustomID'],$ProfileID,"textbox") ."\" />\n";
				echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">"
				     . __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
				echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\""
				     .retrieve_datavalue($_REQUEST["ProfileCustomID". $data3['ProfileCustomID']],
												$data3['ProfileCustomID'],$ProfileID,"textbox") ."\" /><br />\n";
			} 
		} 
			
		elseif ($ProfileCustomType == 3) {  // Drop Down
		
				list($option1,$option2) = explode(":",$data3['ProfileCustomOptions']);	
		
				$data = explode("|",$option1);
				$data2 = explode("|",$option2);
		
				echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">\n";
		
						echo "<option value=\"\">--</option>";
				
							$pos = 0;
							foreach($data as $val1){
								if(!empty($val1)){
												echo "<option value=\"".$val1."\" ".
											retrieve_datavalue("",$data3['ProfileCustomID'],$ProfileID,"dropdown",$val1)
												." >".$val1."</option>";
								}
							}
					
				echo "</select>\n";
				
				if (!empty($data2) && !empty($option2)) {

						$pos2 = 0;
						echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
						echo "<option value=\"\">--</option>";
						foreach($data2 as $val2){
								if($val2 != end($data2) && $val2 !=  $data2[0]){
									echo "<option value=\"".$val2."\" ". selected($val2, $_REQUEST["ProfileCustomID"
									     . $data3['ProfileCustomID']]) 
									     ." >".$val2."</option>";
								}
							}
						echo "</select>\n";
				}
				
			} elseif ($ProfileCustomType == 4) {
				echo "<textarea style=\"width: 100%; min-height: 100px;\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">"
				     . retrieve_datavalue($_REQUEST["ProfileCustomID". $data3['ProfileCustomID']],
				 									$data3['ProfileCustomID'],$ProfileID,"textbox") ."</textarea>";

			} elseif ($ProfileCustomType == 5) {

				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
					 $xplode = explode(",",$_REQUEST["ProfileCustomID". $data3['ProfileCustomID']]);
					 echo "<label><input type=\"checkbox\" value=\"". $val."\"   "; 
					 
					 if(in_array($val,$xplode)){ echo "checked=\"checked\""; } 
					 
					 echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 
					 echo "". $val."</label>";
				}    
				
				echo "<br/>";
				echo "</div>";
				   
			} elseif ($ProfileCustomType == 6) {
				
				echo "<fieldset>";
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				
				foreach($array_customOptions_values as $val){
					
					$selected = "";
					 $check = "";
					 $selected = retrieve_datavalue("",$data3['ProfileCustomID'],$ProfileID,"dropdown",$val);
					 
					 if($selected == "selected"){
					 	$check = "checked";	
					 }
					
					 echo "<label><input type=\"radio\" value=\"". $val."\" " . $check .
					      " name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 echo $val."</label><br/>";
				}
				echo "</fieldset>";
				
			}elseif ($ProfileCustomType == 7) { //Imperial/Metrics
			

			    if($data3['ProfileCustomTitle']=="Height" AND $bb_agency_option_unittype==1){
			        
			        echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">\n";
					echo "<option value=\"\">--</option>\n";
		
					$i=36;
						$heightraw = 0;
						$heightfeet = 0;
						$heightinch = 0;
						while($i<=90)  { 
							  $heightraw = $i;
							  $heightfeet = floor($heightraw/12);
							  $heightinch = $heightraw - floor($heightfeet*12);
								echo " <option value=\"". $i ."\" ".
								retrieve_datavalue("",$data3['ProfileCustomID'],$ProfileID,"dropdown",$i)  .">"
									 . $heightfeet ." ft ". $heightinch ." in</option>\n";
							    $i++;
						}
						
		           echo " </select>\n";
		   
		       }else{
			   
			 echo '<input type="text" name="ProfileCustomID'. $data3['ProfileCustomID'] 
			     .'" value="'. retrieve_datavalue($_REQUEST["ProfileCustomID". $data3['ProfileCustomID']],
				 									$data3['ProfileCustomID'],$ProfileID, 'textbox') 
				 .'" /><br />';
			   }
			}
										
	    echo "</p>\n";
	   
	   } // end if
	   	
    }// End while

	echo " <table class=\"form-table\">\n";
	echo "	<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\"><span style=\"width:185px;float:left;\">". __("Last updated ", bb_agencyinteract_TEXTDOMAIN) ." ". bb_agency_makeago(bb_agency_convertdatetime($ProfileDateUpdated), $bb_agency_option_locationtimezone) ."</span></th>\n";
	echo "		<td>\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "			<input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", bb_restaurant_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "	</tbody>\n";
	echo " </table>\n";
	echo "</form>\n";

?>