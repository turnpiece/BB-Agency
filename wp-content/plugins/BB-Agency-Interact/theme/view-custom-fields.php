<?php
			$ProfileCustomTitle = $data->ProfileCustomTitle;
			$ProfileCustomType = $data->ProfileCustomType;
			$ProfileCustomValue = $wpdb->get_var("SELECT `ProfileCustomValue` FROM ".table_agency_customfield_mux." WHERE `ProfileID` = '".$ProfileID."' AND `ProfileCustomID` = '".$data->ProfileCustomID."'");

			 // SET Label for Measurements
			 // Imperial(in/lb), Metrics(ft/kg)
			$bb_agency_options_arr = get_option('bb_agency_options');
			$bb_agency_option_unittype  = $bb_agency_options_arr['bb_agency_option_unittype'];
			$measurements_label = "";
			if ($ProfileCustomType == 7) { //measurements field type
			   	if($bb_agency_option_unittype ==0){ // 0 = Metrics(ft/kg)
					if($data->ProfileCustomOptions == 1){
					  $measurements_label  ="<em>(cm)</em>";
					}elseif($data->ProfileCustomOptions == 2){
					  $measurements_label  ="<em>(kg)</em>";
					}elseif($data->ProfileCustomOptions == 3){
					  $measurements_label  ="<em>(In Inches/Feet)</em>";
					}
				}elseif($bb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
					if($data->ProfileCustomOptions == 1){
					  $measurements_label  ="<em>(In Inches)</em>";
					}elseif($data->ProfileCustomOptions == 2){
					  $measurements_label  ="<em>(In Pounds)</em>";
					}elseif($data->ProfileCustomOptions == 3){
					  $measurements_label  ="<em>(In Inches/Feet)</em>";
					}
				}
			}
			if ($ProfileCustomType == 1) { //TEXT
			 	echo "    <tr valign=\"top\">\n";
			 	echo "		<td scope=\"row\" class=\"label\">";
			 	echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
				echo "		</td>\n";
				echo "	<td>";

						echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
				echo "		</td>\n";
				
			} elseif ($ProfileCustomType == 2) { // Min Max
			 	echo "    <tr valign=\"top\">\n";
			 	echo "		<td scope=\"row\" class=\"label\">";
			 	echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
			 	echo "		</td>\n";
			 	echo "	<td>";

				$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data->ProfileCustomOptions,"}"),"{"));
				list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);

				if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
					    echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
					
				}else{
					    echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data->ProfileCustomID]."\" />\n";
					    echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data->ProfileCustomID]."\" /><br />\n";

				}
				echo "		</td>\n";
			    echo "	</tr>";
			} elseif ($ProfileCustomType == 3) {
			 	echo "    <tr valign=\"top\">\n";
			 	echo "		<td scope=\"row\" class=\"label\">";
			 	echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
			 	echo "		</td>\n";
			 	echo "	<td>";

			  	list($option1, $option2) = explode(":", $data->ProfileCustomOptions);	
				$data1 = explode("|",$option1);
				$data2 = explode("|",$option2);
	            echo "<label>".$data1[0]."</label>";
				echo "<select name=\"ProfileCustomID". $data->ProfileCustomID ."[]\">\n";
				echo "<option value=\"\">--</option>";
			    $pos = 0;
				foreach($data as $val1){
					if($val1 != end($data1) && $val1 != $data1[0]){
						if($val1 == $ProfileCustomValue ){
							$isSelected = "selected=\"selected\"";
							echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
						 } else {
							echo "<option value=\"".$val1."\" >".$val1."</option>";
						 }
					}
				}
			  	echo "</select>\n";
				if(!empty($data2) && !empty($option2)){
					echo "<label>".$data2[0]."</label>";
			 		$pos2 = 0;
					echo "<select name=\"ProfileCustomID". $data->ProfileCustomID ."[]\">\n";
					echo "<option value=\"\">--</option>";
					foreach($data2 as $val2){
						if($val2 != end($data2) && $val2 !=  $data2[0]){
							 if($val2 == $ProfileCustomValue ){
								$isSelected = "selected=\"selected\"";
								echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";
							 }else{
								echo "<option value=\"".$val2."\" >".$val2."</option>";
							 }
						}
					}
					echo "</select>\n";
				
				}
				echo "		</td>\n";
				echo "	</tr>";   
				
			} 
			elseif ($ProfileCustomType == 4) 
			{
				if(is_admin()){
					echo "    <tr valign=\"top\">\n";
					echo "		<td scope=\"row\" class=\"label\">";
					echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
					echo "		</td>\n";
					echo "	<td>";

					echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data->ProfileCustomID ."\">". $ProfileCustomValue ."</textarea>";
					echo "		</td>\n";
					echo "	</tr>";
				}
			}
			elseif ($ProfileCustomType == 5)
			{
				echo "    <tr valign=\"top\">\n";
				echo "		<td scope=\"row\" class=\"label\">";
				echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
				echo "		</td>\n";
				echo "	<td>";

				$array_customOptions_values = explode("|",$data->ProfileCustomOptions);
				echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
				     if(in_array($val,explode(",",$ProfileCustomValue))){
					 	echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" />";
					 	echo "". $val."</label>";
				     }else{
					 	echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" />";
					 	echo "". $val."</label>";
				     }
				}
				echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data->ProfileCustomID ."[]\"/>";
				echo "</div>";
				echo "		</td>\n";
				echo "	</tr>";
			       
			}
			elseif ($ProfileCustomType == 6) {
				echo "    <tr valign=\"top\">\n";
				echo "		<td scope=\"row\" class=\"label\">";
				echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
				echo "		</td>\n";
				echo "	<td>";

				$array_customOptions_values = explode("|",$data->ProfileCustomOptions);
				echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
				     if(in_array($val,explode(",",$ProfileCustomValue))){
						 echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" />";
						 echo "". $val."</label>";
				     }else{
						 echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data->ProfileCustomID ."[]\" />";
						 echo "". $val."</label>";
				     }
				}
				echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data->ProfileCustomID ."[]\"/>";
				echo "</div>";
				echo "		</td>\n";
				echo "	</tr>";

			}			
			elseif ($ProfileCustomType == 7) { // Imperial(in/lb), Metrics(ft/kg)

				echo "    <tr valign=\"top\">\n";
				echo "		<td scope=\"row\" class=\"label\">";
				echo "				        <label for=\"ProfileCustomID". $data->ProfileCustomID ."\">".__($data->ProfileCustomTitle.$measurements_label, bb_agency_TEXTDOMAIN)."</label>\n";
				echo "		</td>\n";
				echo "	<td>";

				echo "<input type=\"text\" name=\"ProfileCustomID". $data->ProfileCustomID ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";

				//  }
				echo "		</td>\n";
				echo "	</tr>";
		
			}
			elseif ($ProfileCustomType == 9) { // date of birth ?>
				<tr valign="top">
					<td scope="row" class="label">
						<label for="ProfileCustomID<?php echo $data->ProfileCustomID ?>"><?php _e($data->ProfileCustomTitle, bb_agency_TEXTDOMAIN) ?></label>
					</td>
					<td>
						<input type="text" name="ProfileCustomID<?php echo $data->ProfileCustomID ?>" class="datepicker" value="<?php echo $ProfileCustomValue ?>" />
					</td>
				</tr>
				<?php
			}
				
		?>			}
