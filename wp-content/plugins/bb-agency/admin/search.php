<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php"); 


if($_GET['action']==""){
	unset($_SESSION);
}
global $wpdb;


$cusFields = array("Suit","Bust","Shirt","Dress");  //for custom fields min and max

$bb_agency_options_arr = get_option('bb_agency_options');
$bb_agency_option_unittype =  $bb_agency_options_arr['bb_agency_option_unittype'];
$bb_agency_option_persearch = (int)$bb_agency_options_arr['bb_agency_option_persearch'];
$bb_agency_option_agencyemail = (int)$bb_agency_options_arr['bb_agency_option_agencyemail'];
if ($bb_agency_option_persearch <= 1) { $bb_agency_option_persearch = 100; }

echo "<script>function redirectSearch(){ window.location.href = 'admin.php?page=bb_agency_search';}</script>"; 

// *************************************************************************************************** //
// Setup Functions 

	$sessionString = "";
	// Gobble Up The Variables, Set em' Sessions
		foreach ($_GET as $key => $value) {
		  	if (substr($key, 0, 9) != "ProfileID") {
				$_SESSION[$key] = $value;  //$$key = $value;
				$sessionString .= $key ."=". $value ."&";
		  	}
		}
		// Clean It!
		$sessionString = bb_agency_cleanString($sessionString);

// *************************************************************************************************** //
// Get Actions 

	// Protect and defend the cart string!
		$cartString = "";
	// Add to Cart
		if ($_GET["action"] == "cartAdd" ) { 
			
			if(count($_GET["ProfileID"])>0){
				foreach($_GET["ProfileID"] as $value) {
					$cartString .= $value .",";
				}
			}
			// Clean It!
			echo $cartString = bb_agency_cleanString($cartString);
			
			if (isset($_SESSION['cartArray'])) {
				$cartArray = $_SESSION['cartArray'];
				array_push($cartArray, $cartString);
			} else {
				$cartArray = array($cartString);
			}
		   
		   $_SESSION['cartArray'] = $cartArray;
		
		} elseif ($_GET["action"] == "formEmpty") {  // Handle Form Empty 
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
			  	if (substr($key, 0, 7) == "Profile") {
					unset($_SESSION[$key]);
			  	}
			}
		} elseif ($_GET["action"] == "cartEmpty") {  // Handle Cart Removal
			// Throw the baby out with the bathwater
			unset($_SESSION['cartArray']);
			
		} elseif (($_GET["action"] == "cartRemove") && (isset($_GET["RemoveID"]))) {
			$cartArray = $_SESSION['cartArray'];
			$cartString = implode(",", $cartArray);
			$cartRemoveID = $_GET["RemoveID"];
			$cartString = str_replace($_GET['RemoveID'] ."", "", $cartString);
			$cartString = bb_agency_cleanString($cartString);
			// Put it back in the array, and wash your hands
			$_SESSION['cartArray'] = array($cartString);
		
		} elseif (($_GET["action"] == "searchSave") && isset($_SESSION['cartArray'])) {
		
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
			}
			$_SESSION['cartArray'] = $cartArray;
		
		}



// *************************************************************************************************** //
// Get Search Results

	if ($_GET["action"] == "search") {
		
		// Sort By
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "profile.ProfileContactNameFirst";
		}
	
		// Limit
		if (isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = "";
		} else {
			if ($bb_agency_option_persearch > 1) {
				$limit = " LIMIT 0,". $bb_agency_option_persearch;
			}
		}
	
		// Sort Order
		$dir = "";
		if (isset($_GET['dir']) && !empty($_GET['dir'])){
			$dir = $_GET['dir'];
			if ($dir == "desc" || !isset($dir) || empty($dir)){
			   	$sortDirection = "asc";
		   	} else {
		   		$sortDirection = "desc";
			} 
		} else {
		   	$sortDirection = "desc";
		   	$dir = "asc";
		}
	
		//// Filter
		$filter = " WHERE profile.ProfileID > 0";
		// Name
		if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
				$ProfileContactNameFirst = $_GET['ProfileContactNameFirst'];
				$filter .= " AND profile.ProfileContactNameFirst LIKE '". $ProfileContactNameFirst ."%'";
			}
			if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
				$ProfileContactNameLast = $_GET['ProfileContactNameLast'];
				$filter .= " AND profile.ProfileContactNameLast='". $ProfileContactNameLast ."'";
			}
		}
		// Location
		if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
			$ProfileLocationCity = $_GET['ProfileLocationCity'];
			$filter .= " AND profile.ProfileLocationCity='". $ProfileLocationCity ."'";
		}
        
		// Location state
		if (isset($_GET['ProfileLocationState']) && !empty($_GET['ProfileLocationState'])){
			$ProfileLocationState = $_GET['ProfileLocationState'];
			$filter .= " AND profile.ProfileLocationState='". $ProfileLocationState ."'";
		}

		// Location Zip
		if (isset($_GET['ProfileLocationZip']) && !empty($_GET['ProfileLocationZip'])){
			$ProfileLocationZip = $_GET['ProfileLocationZip'];
			$filter .= " AND profile.ProfileLocationZip='". $ProfileLocationZip ."'";
		}
		
		// Type
		if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
			$ProfileType = $_GET['ProfileType'];
			$filter .= " AND profile.ProfileType like'%". $ProfileType ."%'";
			//$filter .= " AND Find_in_set (". $ProfileType .",profile.ProfileType) ";
		}
             
                else {
			$ProfileType = "";
		}   if (isset($_GET['ProfileIsActive'])&& $_GET['ProfileIsActive'] !=""){
			$ProfileIsActive = $_GET['ProfileIsActive'];
			$filter .= " AND profile.ProfileIsActive=". $ProfileIsActive ."";
			
		} 		
        // Set Filter to exclude inactive profiles
        // and pending for approval profiles from
        // search 		
        //$filter .= " AND profile.ProfileIsActive!=0 AND profile.ProfileIsActive!=3";
		
        // Gender
		if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])){
			$ProfileGender = $_GET['ProfileGender'];
		 
			$filter .= " AND profile.ProfileGender='".$ProfileGender."'";
		 
		} else {
			$ProfileGender = "";
		}

		// Age
		$timezone_offset = -10; // Hawaii Time
		$dateInMonth = gmdate('d', time() + $timezone_offset *60 *60);
		$format = 'Y-m-d';
		$date = gmdate($format, time() + $timezone_offset *60 *60);
		
		if (isset($_GET['ProfileDateBirth_min']) && !empty($_GET['ProfileDateBirth_min'])){
			$ProfileDateBirth_min = $_GET['ProfileDateBirth_min'];
			$selectedYearMin = date($format, strtotime('-'. $ProfileDateBirth_min .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
		}
		
		if (isset($_GET['ProfileDateBirth_max']) && !empty($_GET['ProfileDateBirth_max'])){
			$ProfileDateBirth_max = $_GET['ProfileDateBirth_max'];
			$selectedYearMax = date($format, strtotime('-'. $ProfileDateBirth_max-1 .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
		}

		// Due date
		if (isset($_GET['ProfileDateDue_min']) && !empty($_GET['ProfileDateDue_min'])){
			$ProfileDateDue_min = $_GET['ProfileDateBirth_min'];
			$selectedYearMin = date($format, strtotime('-'. $ProfileDateDue_min .' year'. $date));
			$filter .= " AND profile.ProfileDateDue <= '$selectedYearMin'";
		}
		
		if (isset($_GET['ProfileDateDue_max']) && !empty($_GET['ProfileDateDue_max'])){
			$ProfileDateDue_max = $_GET['ProfileDateDue_max'];
			$selectedYearMax = date($format, strtotime('-'. $ProfileDateDue_max-1 .' year'. $date));
			$filter .= " AND profile.ProfileDateDue >= '$selectedYearMax'";
		}

       echo "  <div class=\"boxblock-holder\">\n";

		// Filter Models Already in Cart
        if (isset($_SESSION['cartArray'])) {
            $cartArray = $_SESSION['cartArray'];
            $cartString = implode(",", $cartArray);
			$cartQuery =  " AND profile.ProfileID NOT IN (". $cartString .")";
		}
		   $filterDropdown = array();
            
        $filter2 = "";  
		foreach($_GET as $key => $val){
                        
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
                                    if(is_array($val)){
                                      
                                            if(count(array_filter($val)) > 1) {
                                                $ct =1;
                                                foreach($val as $v){
                                                    if($ct == 1){
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
				    
                                    $q = mysql_query("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = '".substr($key,15)."' ");
				    $ProfileCustomType = mysql_fetch_assoc($q);
					
				
                                        /*
                                         * Have created a holder $filter2 and
                                         * create its own filter here and change
                                         * AND should be OR
                                         */
                                        if(in_array($ProfileCustomType['ProfileCustomTitle'], $cusFields)) {
                                                        $minVal=$_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_min'];
                                                        $maxVal=$_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_max'];
                                                        if($filter2 == ""){
                                                            $filter2 .= " AND (( customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."'and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
                                                        } else {
                                                            $filter2 .= " OR (customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."'and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";

                                                        }

                                                        //echo "-----";
                                        }else {

                                                        /******************
                                                        1 - Text
                                                        2 - Min-Max > Removed
                                                        3 - Dropdown
                                                        4 - Textbox
                                                        5 - Checkbox
                                                        6 - Radiobutton
                                                        7 - Metrics/Imperials
                                                        *********************/

                                                        if ($ProfileCustomType["ProfileCustomType"] == 1) { //TEXT
                                                                if($filter2 == ""){
																
                                                                    $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                                                } else {
                                                                    $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                }                                                           
                                                                $_SESSION[$key] = $val;

                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 3) { // Dropdown
															if($filter2==""){
               $filter2 .=" AND (( customfield_mux.ProfileCustomValue IN('".$val."') and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
               } else {
               $filter2 .=" OR (customfield_mux.ProfileCustomValue IN('".$val."') and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
               }

                                                                                //$filter.= " AND LOWER(customfield_mux.ProfileCustomValue) = LOWER(\"".$val."\") ";
                                                           // array_push($filterDropdown,$val);


                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 4) { //Textarea
                                                                if($filter2==""){
                                                                    $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                                                } else {
                                                                    $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                } 
                                                                        $_SESSION[$key] = $val;


                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 5) { //Checkbox
                                                                if(!empty($val)){
                                                                    if(strpos($val,",") === false){
                                                                       // $val = implode("','",explode(",",$val));
																	  
                                                                        if($filter2==""){
																		
                                                                                $filter2 .= " AND  ((customfield_mux.ProfileCustomValue like('%".$val."%') and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
                                                                        } else {
                                                                                $filter2 .= " OR  (customfield_mux.ProfileCustomValue like('%".$val."%')   and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";
                                                                        }
                                                                    } else {
																		
																		$likequery = explode(",", $val);
																		$likecounter = count($likequery);
																		$i=1; 
																		$likedata = "" ;
																		foreach($likequery as $like){
																			if($i < ($likecounter-1)){
																				if($like!=""){
																					$likedata.= " customfield_mux.ProfileCustomValue like('%".$like."%')  OR "  ;
																				}
																				}else{
																				if($like!=""){
																						$likedata.= " customfield_mux.ProfileCustomValue like('%".$like."%')  "  ;
																				} 
																			}
																			$i++;
																		}
																		 
																		
																		$val = substr($val, 0, -1);
																	    if($filter2==""){
                                                                            $filter2 .= " AND  ((( ".$likedata.") and customfield_mux.ProfileCustomID = '".substr($key,15)."' )";
                                                                        } else {
                                                                            $filter2 .= " OR  ((".$likedata.") and customfield_mux.ProfileCustomID = '".substr($key,15)."')";
                                                                        }
                                                                    }

                                                                $_SESSION[$key] = $val;
                                                                }else{
                                                                        $_SESSION[$key] = "";
                                                                }
                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 6) { //Radiobutton 
                                                                //var_dump($ProfileCustomType["ProfileCustomType"]);
                                                                   $val = implode("','",explode(",",$val));
                                                                    if($filter2==""){
                                                                        $filter2 .= " AND ( (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                                                    } else {
                                                                        $filter2 .= " or (customfield_mux.ProfileCustomValue like('%".$val."%'))";
                                                                    } 
                                                                    $_SESSION[$key] = $val;
                                                               

                                                        }
                                                        elseif ($ProfileCustomType["ProfileCustomType"] == 7) { //Measurements 

                                                                    list($Min_val,$Max_val) = explode(",",$val);
                                                                        if(!empty($Min_val) && !empty($Max_val)){
                                                                            if($filter2==""){
                                                                                    $filter2  .= " AND (( customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."'and customfield_mux.ProfileCustomID = '".substr($key,15)."' )";
                                                                            } else {
                                                                                    $filter2  .= " OR (customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."'and customfield_mux.ProfileCustomID = '".substr($key,15)."') ";

                                                                            }
                                                                        $_SESSION[$key] = $val;
                                                                        }

                                                        }
                                        }
						
						mysql_free_result($q);
				} // if not empty
			 }  // end if
	       } // end for each
	  
           if(count($filterDropdown) > 0){
               if($filter2==""){
               $filter2 .=" AND ( customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
               } else {
               $filter2 .=" OR customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
               }
               
           }

           /*
            * Refine filter and add the created 
            * holder $filter to $filter if not
            * equals to blanks
            */
           if($filter2!=""){
            $filter2.= " ) ";
            $filter .= $filter2;
           }
     
	// Search Results	
	 $query = "
			 SELECT 
			 profile.*,
			 profile.ProfileGallery,
			 profile.ProfileContactDisplay, 
			 profile.ProfileDateBirth, 
			 profile.ProfileLocationState, 
			 profile.ProfileID as pID, 
			 customfield_mux.*,  
			 		(
					  SELECT media.ProfileMediaURL 
					 	      FROM ". table_agency_profile_media ." media 
					  WHERE profile.ProfileID = media.ProfileID 
					  		AND 
							media.ProfileMediaType = \"Image\" 
							AND 
							media.ProfileMediaPrimary = 1
					) 
					AS ProfileMediaURL FROM ". table_agency_profile ." profile 
			LEFT JOIN ". table_agency_customfield_mux ." 
			            AS customfield_mux 
					ON profile.ProfileID = customfield_mux.ProfileID  
					".$filter." ".$cartQuery."  
			GROUP BY profile.ProfileID 
			ORDER BY $sort $dir  $limit";

		// Search Results	
      //  $query = "SELECT profile.*, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile $filter $cartQuery ORDER BY $sort $dir $limit";
	  // echo $query;
		$results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
        
        echo "<h2 class=\"title\">Search Results: " . $count . "</h2>\n";
        
		if (($count > ($bb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
			echo "<div id=\"message\" class=\"error\"><p>Search exceeds ". $bb_agency_option_persearch ." records first ". $bb_agency_option_persearch ." displayed below.  <a href=". admin_url("admin.php?page=". $_GET['page']) ."&". $sessionString ."&limit=none><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
		}
        echo "       <form method=\"get\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
        echo "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
        echo "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
        //echo "        <input type=\"hidden\" name=\"forceQuery\" value=\"". $cartString ."\" />\n";
        echo "        <input type=\"hidden\" name=\"forceCart\" value=\"". $_SESSION['cartArray'] ."\" />\n";
        echo "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
        echo "        <thead>\n";
        echo "            <tr class=\"thead\">\n";
        echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
        echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=bb_agency_profiles&sort=ProfileID&dir=". $sortDirection ."\">". __("ID", bb_agency_TEXTDOMAIN) ."</a></th>\n";
        echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileImage\" id=\"ProfileImage\" scope=\"col\" style=\"width:150px;\">". __("Headshot", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "            </tr>\n";
        echo "        </thead>\n";
        echo "        <tfoot>\n";
        echo "            <tr class=\"thead\">\n";
        echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
        echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\">". __("ID", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-image\" id=\"col-image\" scope=\"col\">". __("Headshot", bb_agency_TEXTDOMAIN) ."</th>\n";
        echo "            </tr>\n";
        echo "        </tfoot>\n";
        echo "        <tbody>\n";
         
        while ($data = mysql_fetch_array($results2)) {
            $ProfileID = $data['pID'];
			 $isInactive = '';
			 $isInactiveDisable = '';
			 if ($data['ProfileIsActive'] == 0 || empty($data['ProfileIsActive'])){
				$isInactive = 'style="background: #FFEBE8"';
				$isInactiveDisable = "disabled=\"disabled\"";
			 }
        echo "        <tr ". $isInactive.">\n";
        echo "            <th class=\"check-column\" scope=\"row\" >\n";
	  echo "                <input type=\"checkbox\" ". $isInactiveDisable." value=\"". $ProfileID ."\" class=\"administrator\" id=\"ProfileID". $ProfileID ."\" name=\"ProfileID[]\" />\n";
        echo "            </th>\n";
        echo "            <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
        echo "            <td class=\"ProfileContact column-ProfileContact\">\n";
        echo "                <div class=\"detail\">\n";
			
			
	  echo "                </div>\n";
        echo "                <div class=\"title\">\n";
        echo "                	<h2>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h2>\n";
        echo "                </div>\n";
        echo "                <div class=\"row-actions\">\n";
        echo "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=bb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
        echo "                    <span class=\"review\"><a href=\"". bb_agency_PROFILEDIR . $bb_agency_UPLOADDIR . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
        echo "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=bb_agency_profiles&amp;deleteRecord&amp;ProfileID=". $ProfileID ."' onclick=\"if ( confirm('You are about to delete the model \'". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;\">Delete</a></span>\n";
        echo "                </div>\n";
	            if(!empty($isInactiveDisable)){
					echo "<div><strong>Profile Status:</strong> <span style=\"color:red;\">Inactive</span></div>\n";
				}
        echo "            </td>\n";
		
	 // private into 
        echo "            <td class=\"ProfileStats column-ProfileStats\">\n";
			
				if (!empty($data['ProfileContactEmail'])) {
					echo "<div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
				}
				
				if (!empty($data['ProfileLocationStreet'])) {
					echo "<div><strong>Address:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
				}
				if (!empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState'])) {
					echo "<div><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
				}
				if (!empty($data['ProfileLocationCountry'])) {
					echo "<div><strong>". __("Country", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileLocationCountry'] ."</div>\n";
				}
				/*
				if (!empty($data['ProfileDateBirth'])) {
					echo "<div><strong>". __("Age", bb_agency_TEXTDOMAIN) .":</strong> ". bb_agency_get_age($data['ProfileDateBirth']) ."</div>\n";
				}
				*/
				if (!empty($data['ProfileDateBirth'])) {
					echo "<div><strong>". __("Birthdate", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</div>\n";
				}
				if (!empty($data['ProfileDateDue'])) {
					echo "<div><strong>". __("Due date", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateDue'] ."</div>\n";
				}
				if (!empty($data['ProfileContactWebsite'])) {
					echo "<div><strong>". __("Website", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneHome'])) {
					echo "<div><strong>". __("Phone Home", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneHome'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneCell'])) {
					echo "<div><strong>". __("Phone Cell", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneCell'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneWork'])) {
					echo "<div><strong>". __("Phone Work", bb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
				}

						$resultsCustomPrivate =  $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC");
						foreach  ($resultsCustomPrivate as $resultCustomPrivate) {
		echo "				<div><strong>". $resultCustomPrivate->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustomPrivate->ProfileCustomValue ."</div>\n";
						}
       echo "            </td>\n";
	  
	  // public info
        echo "            <td class=\"ProfileDetails column-ProfileDetails\">\n";

				if (!empty($data['ProfileGender'])) {
					if(bb_agency_getGenderTitle($data['ProfileGender'])){
						echo "<div><strong>". __("Gender", bb_agency_TEXTDOMAIN) .":</strong> ".bb_agency_getGenderTitle($data['ProfileGender'])."</div>\n";
					}else{
						echo "<div><strong>". __("Gender", bb_agency_TEXTDOMAIN) .":</strong> --</div>\n";	
					}
				}

				$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
				foreach  ($resultsCustom as $resultCustom) {
					if(!empty($resultCustom->ProfileCustomValue )){
					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
					}
				}
       echo "            </td>\n";
        echo "            <td class=\"ProfileImage column-ProfileImage\">\n";
							if (isset($data['ProfileMediaURL']) && !empty($data['ProfileMediaURL'])) {
		echo "				<div class=\"image\"><img style=\"width: 150px; \" src=\"". bb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
							} else {
		echo "				<div class=\"image no-image\">NO IMAGE</div>\n";
							}
        echo "            </td>\n";
        echo "        </tr>\n";
        }
            mysql_free_result($results2);
            if ($count < 1) {
				if (isset($filter)) { 
        echo "        <tr>\n";
        echo "            <th class=\"check-column\" scope=\"row\"></th>\n";
        echo "            <td class=\"name column-name\" colspan=\"5\">\n";
        echo "                <p>". __("No profiles found with this criteria!", bb_agency_TEXTDOMAIN) .".</p>\n";
        echo "            </td>\n";
        echo "        </tr>\n";
				} else {
        echo "        <tr>\n";
        echo "            <th class=\"check-column\" scope=\"row\"></th>\n";
        echo "            <td class=\"name column-name\" colspan=\"5\">\n";
        echo "                <p>". __("There aren't any Profiles loaded yet!", bb_agency_TEXTDOMAIN) ."</p>\n";
        echo "            </td>\n";
        echo "        </tr>\n";
				} 
        } 
        echo "        </tbody>\n";
        echo "    </table>\n";
   
        echo "     <p>\n";
        echo "      	<input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','bb_agency_search') ."\" class=\"button-primary\" />\n";
		echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", bb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", bb_agency_TEXTDOMAIN) ." - ". __("Without Details", bb_agency_TEXTDOMAIN) ."</a>\n";
		echo "     </p>\n";
		echo "  </form>\n";
      
    
} else {
// Ok.. now what ???
}

if (($_GET["action"] == "search") || ($_GET["action"] == "cartAdd") || (isset($_SESSION['cartArray']))) {

	echo "<div class=\"boxblock-container\" style=\"float: left; width: 49%; min-width: 500px;\">\n";
	echo " <div class=\"boxblock\">\n";
	echo "  <h2>". __("Casting Cart", bb_agency_TEXTDOMAIN) ."</h2>\n";
	echo "    <div class=\"inner\">\n";
        if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {
			 
            $cartArray = $_SESSION['cartArray'];
            	$cartString = implode(",", array_unique($cartArray));
				$cartString = bb_agency_cleanString($cartString);
            
			// Show Cart  
            $query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY profile.ProfileContactNameFirst ASC";
			$results = mysql_query($query) or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", bb_agency_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
            $count = mysql_num_rows($results);
            
            echo "<div style=\"float: right; width: 100px; \"><a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("Empty Cart", bb_agency_TEXTDOMAIN) ."</a></div>";
            echo "<div style=\"float: left; line-height: 22px; font-family:Georgia; font-size:13px; font-style: italic; color: #777777; \">". __("Currently", bb_agency_TEXTDOMAIN) ." <strong>". $count ."</strong> ". __("in Cart", bb_agency_TEXTDOMAIN) ."</div>";
            echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0; \" class=\"profile\">";
			
            if ($count == 1) {
                $cartAction = "cartEmpty";
            } elseif ($count < 1) {
                echo "". __("There are currently no profiles in the casting cart", bb_agency_TEXTDOMAIN) .".";
                $cartAction = "cartEmpty";
            } else {
                $cartAction = "cartRemove";
            }
            while ($data = mysql_fetch_array($results)) {
				
                $ProfileDateUpdated = $data['ProfileDateUpdated'];
				
                echo "<div style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
                echo " <div style=\"text-align: center; \"><h3>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</h3></div>"; 
                echo " <div style=\"float: left; width: 100px; height: 100px; overflow: hidden; margin-top: 2px; \"><img style=\"width: 100px; \" src=\"". bb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
                echo " <div style=\"float: left; width: 100px; height: 100px; overflow: scroll-y; margin-left: 10px; line-height: 11px; font-size: 9px; \">\n";
                
                    if (!empty($data['ProfileDateBirth'])) {
                        echo "<strong>Age:</strong> ". bb_agency_get_age($data['ProfileDateBirth']) ."<br />\n";
                    }
  
                    
                echo " </div>";
                echo " <div style=\"position: absolute; z-index: 20; top: 120px; left: 200px; width: 20px; height: 20px; overflow: hidden; \"><a href=\"?page=". $_GET['page'] ."&action=". $cartAction ."&RemoveID=". $data['ProfileID'] ."\" title=\"". __("Remove from Cart", bb_agency_TEXTDOMAIN) ."\"><img src=\"". bb_agency_BASEDIR ."style/remove.png\" style=\"width: 20px; \" alt=\"". __("Remove from Cart", bb_agency_TEXTDOMAIN) ."\" /></a></div>";
                echo " <div style=\"clear: both; \"></div>";
                echo "</div>";
            }
            mysql_free_result($results);
            echo "<div style=\"clear: both;\"></div>\n";
            echo "</div>";
            
         } else {
            //$cartAction = "cartRemove";
            echo "<p>There are no profiles added to the casting cart.</p>\n";
         }

	echo " </div>\n";
	echo "</div>\n";
  
	 if (($cartAction == "cartEmpty") || ($cartAction == "cartRemove")) {
		echo "<a name=\"compose\">&nbsp;</a>"; 
        echo "     <div class=\"boxblock\">\n";
        echo "        <h3>". __("Cart Actions", bb_agency_TEXTDOMAIN) ."</h3>\n";
        echo "        <div class=\"inner\">\n";
        echo "      	<a href=\"?page=bb_agency_searchsaved&action=searchSave\" title=\"". __("Save Search & Email", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Save Search & Email", bb_agency_TEXTDOMAIN) ."</a>\n";
        echo "      	<a href=\"?page=bb_agency_search&action=massEmail#compose\" title=\"". __("Mass Email", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Mass Email", bb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", bb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", bb_agency_TEXTDOMAIN) ." - ". __("Without Details", bb_agency_TEXTDOMAIN) ."</a>\n";

		echo "        </div>\n";
		echo "     </div>\n";
	} // Is Cart Empty 
     $isSent = false;
	if(isset($_POST["SendEmail"])){
		

		$bb_agency_options_arr = get_option('bb_agency_options');
            $bb_agency_value_agencyname = $bb_agency_options_arr['bb_agency_option_agencyname'];
      	$bb_agency_value_agencyemail = $bb_agency_options_arr['bb_agency_option_agencyemail'];
		
		  
		     add_filter('wp_mail_content_type','bb_agency_set_content_type');
						function bb_agency_set_content_type($content_type){
							return 'text/html';
		}
  
		
			        
					$MassEmailSubject = $_POST["MassEmailSubject"];
					$MassEmailMessage = $_POST["MassEmailMessage"];
					$MassEmailRecipient = $_POST["MassEmailRecipient"];
			      
					
					// Mail it
					$headers[]  = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					$headers[] = 'From: '.$bb_agency_value_agencyname.' <'. $bb_agency_option_agencyemail .'>';
					
					if(!empty($expMail)){
						$expMail = explode(",",$MassEmailRecipient);
						foreach($expMail as $bccEmail){
							$headers[] = 'Bcc: '.$bccEmail;
						}
					}
					
					$isSent = wp_mail($MassEmailRecipient, $MassEmailSubject, $MassEmailMessage, $headers);
						

	}
	if($_GET["action"]== "massEmail"){
		
				// Filter Models Already in Cart
        if (isset($_SESSION['cartArray'])) {
            $cartArray = $_SESSION['cartArray'];
            $cartString = implode(",", $cartArray);
			$cartQuery =  " AND profile.ProfileID IN (". $cartString .")";
		}
		// Search Results	
        $query = "SELECT profile.*  FROM ". table_agency_profile ." profile WHERE profile.ProfileID > 0 ".$cartQuery;
		$results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
      $pos = 0;	
	$recipient = "";			
        while ($data = mysql_fetch_array($results2)) {
		$pos ++;
            $ProfileID = $data['ProfileID'];
           $recipient .=$data['ProfileContactEmail'];
	     if($count != $pos){
		  $recipient .=", ";     
	     }
			
	  }
		// Email
		$bb_agency_options_arr = get_option('bb_agency_options');
            $bb_agency_value_agencyname = $bb_agency_options_arr['bb_agency_option_agencyname'];
      	$bb_agency_value_agencyemail = $bb_agency_options_arr['bb_agency_option_agencyemail'];
		echo "<form method=\"post\">";
		echo "     <div class=\"boxblock\">\n";
        echo "        <h3>". __("Compose Email", bb_agency_TEXTDOMAIN) ."</h3>\n";
        echo "        <div class=\"inner\">\n";
	  if($isSent){
	  echo "<div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";	
	  }
	  echo "          <strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$recipient."</textarea><br/>";
        echo "        <strong>Subject:</strong> <br/><input type=\"text\" name=\"MassEmailSubject\" style=\"width:100%\"/>";
		echo "<br/>";
		echo "      <strong>Message:</strong><br/>     <textarea name=\"MassEmailMessage\"  style=\"width:100%;height:300px;\">this message was sent to you by ".$bb_agency_value_agencyname." ".network_site_url( '/' )."</textarea>";
		echo "				<input type=\"submit\" value=\"". __("Send Email", bb_agency_TEXTDOMAIN) . "\" name=\"SendEmail\"class=\"button-primary\" />\n";
		echo "        </div>\n";
		echo "     </div>\n";
	    echo "</form>";
	}
    
		echo "    </div><!-- .container -->\n";
} 

		echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
		echo "     <div class=\"boxblock\">\n";
		echo "      <h3>". __("Advance Search", bb_agency_TEXTDOMAIN) ."</h3>\n";
		echo "        <div class=\"inner\">\n";
		echo "        	<form method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"bb_agency_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
		echo "				<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "				  <thead>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("First Name", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION['ProfileContactNameFirst'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Last Name", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION['ProfileContactNameLast'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Classification", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "							<option value=\"\">". __("Any Profile Type", bb_agency_TEXTDOMAIN) . "</option>";
		                                /* 
										 * set filter from theis array
										 * to block the following profile types 
										 * in search
										 */
										
										$filter = array( 'agents', 'agent', 'clients', 'client', 'producer', 'producers' );
										 
										$profileDataTypes = mysql_query("SELECT * FROM ". table_agency_data_type ."");
										while ($dataType = mysql_fetch_array($profileDataTypes)) {
											if(!in_array(strtolower($dataType["DataTypeTitle"]),$filter)){
												echo "<option value=\"". $dataType["DataTypeID"] ."\" ".selected($_SESSION['ProfileType'],$dataType["DataTypeID"],false).">". $dataType["DataTypeTitle"] ."</option>";
											}
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Gender", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "						<option value=\"\">". __("Any Gender", bb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = mysql_query($query2);
											while ($dataGender = mysql_fetch_array($results2)) {
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION["ProfileGender"],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
											}
		echo "						</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Age", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";
		echo "				        <fieldset>\n";
		echo "				       <div> <label for=\"ProfileDateBirth_min\">". __("Min", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "						<input type=\"text\" class=\"min_max\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" />";
		echo "						</div>";
		
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", bb_agency_TEXTDOMAIN) . "</label>\n";
		echo "						<input type=\"text\" class=\"min_max\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" />";
		echo "						</div>";
		           
	/*	echo "				        	". __("Minimum", bb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_GET['ProfileDateBirth_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", bb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_GET['ProfileDateBirth_max'] ."\" />\n";
	*/
		echo "				        </fieldset>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
	

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Town", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileLocationCity\" id=\"ProfileLocationCity\">\n";               
		echo "							<option value=\"\">". __("Any town", bb_agency_TEXTDOMAIN) . "</option>";
										  /*
										   * lets get the variables first for use
										   * in city 
										   */
										$profilecity = mysql_query("SELECT DISTINCT ProfileLocationCity FROM ". table_agency_profile ."");
										
										while ($dataLocation = mysql_fetch_array($profilecity)) {
					                          if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity']) && $_SESSION['ProfileLocationCity'] == $dataLocation["ProfileLocationCity"]) {
										  	     echo "<option value=\"". $dataLocation["ProfileLocationCity"] ."\" selected>". bb_agency_strtoproper($dataLocation["ProfileLocationCity"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										      } else {
										   	     echo "<option value=\"". $dataLocation["ProfileLocationCity"] ."\">". bb_agency_strtoproper($dataLocation["ProfileLocationCity"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										      }
										}
										
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("County", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileLocationState\" id=\"ProfileLocationState\">\n";               
		echo "							<option value=\"\">". __("Any county", bb_agency_TEXTDOMAIN) . "</option>";
										  /*
										   * lets get the variables first for use
										   * in state
										   */
										$profilestate = mysql_query("SELECT DISTINCT ProfileLocationState FROM ". table_agency_profile ."");
										
										while ($dataLocation = mysql_fetch_array($profilestate)) {
					                          if (isset($_GET['ProfileLocationState']) && !empty($_GET['ProfileLocationState']) && $_SESSION['ProfileLocationState'] == $dataLocation["ProfileLocationState"]) {
										  	     echo "<option value=\"". $dataLocation["ProfileLocationState"] ."\" selected>". bb_agency_strtoproper($dataLocation["ProfileLocationState"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										      } else {
										   	     echo "<option value=\"". $dataLocation["ProfileLocationState"] ."\">". bb_agency_strtoproper($dataLocation["ProfileLocationState"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										      }
										}
										
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Post code", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileLocationZip\" id=\"ProfileLocationZip\">\n";               
		echo "							<option value=\"\">". __("Any post code", bb_agency_TEXTDOMAIN) . "</option>";
										  /*
										   * lets get the variables first for use
										   * in sip
										   */
										$profilestate = mysql_query("SELECT DISTINCT ProfileLocationZip FROM ". table_agency_profile ."");
										
										while ($dataLocation = mysql_fetch_array($profilestate)) {
					                          if (isset($_GET['ProfileLocationZip']) && !empty($_GET['ProfileLocationZip']) && $_SESSION['ProfileLocationZip'] == $dataLocation["ProfileLocationZip"]) {
										  	     echo "<option value=\"". $dataLocation["ProfileLocationZip"] ."\" selected>". bb_agency_strtoproper($dataLocation["ProfileLocationZip"]) .", ". strtoupper($dataLocation["ProfileLocationZip"]) ."</option>";
										      } else {
										   	     echo "<option value=\"". $dataLocation["ProfileLocationZip"] ."\">". bb_agency_strtoproper($dataLocation["ProfileLocationZip"]) .", ". strtoupper($dataLocation["ProfileLocationZip"]) ."</option>";
										      }
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

			//bb_custom_fields(0, $ProfileID, $ProfileGender,false);
			$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView IN('0','1')  AND ProfileCustomID != 39 AND ProfileCustomID != 48 ORDER BY ProfileCustomOrder ASC";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								$pos = 0;
		while ($data1 = mysql_fetch_array($results1)) { 
			
									$ProfileCustomID = $data1['ProfileCustomID'];
									$ProfileCustomTitle = $data1['ProfileCustomTitle'];
									$ProfileCustomType = $data1['ProfileCustomType'];
									$ProfileCustomValue = $data1['ProfileCustomValue'];
			
  echo "				    <tr>\n";
  echo " 				    \n";
  
  		
			

			 // SET Label for Measurements
			 // Imperial(in/lb), Metrics(ft/kg)
			 $bb_agency_options_arr = get_option('bb_agency_options');
			  $bb_agency_option_unittype  = $bb_agency_options_arr['bb_agency_option_unittype'];
			  $measurements_label = "";
			  /*
			    0- metric
   			      1 - cm
				2- kg
				3 - inches/feet
			    1-imperials	
				1- inches
				2- pounds
				3-inches/feet
				*/
			 if ($ProfileCustomType == 7) { //measurements field type
			           if($bb_agency_option_unittype ==1){ // 1 = Metrics(ft/kg)
						if($data1['ProfileCustomOptions'] == 1){
						    $measurements_label  ="<em>(Inches)</em>";
						}elseif($data1['ProfileCustomOptions'] == 2){
						  	$measurements_label  ="<em>(Pounds)</em>";
						}elseif($data1['ProfileCustomOptions'] == 3){
						  $measurements_label  ="<em>(Feet/Inches)</em>";
						}
					}elseif($bb_agency_option_unittype ==0){ //0 = Imperial(in/lb)
						if($data1['ProfileCustomOptions'] == 1){
					    	$measurements_label  ="<em>(cm)</em>";
						}elseif($data1['ProfileCustomOptions'] == 2){
						    $measurements_label  ="<em>(kg)</em>";
						}elseif($data1['ProfileCustomOptions'] == 3){
						  	$measurements_label  ="<em>(Inches/Feet)</em>";
						}
					}
					
			 }
 	 echo " 				    <th scope=\"row\">\n";
			                                     if($ProfileCustomType==7){

			 echo "				       <div class=\"label\">". $data1['ProfileCustomTitle'].$measurements_label."</div> \n";
									 }else{
			 echo "				       <div><label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label></div> \n";							 
	 
	 								 }
    
    			echo  "			</th>		";
			echo  "			<td>";
			

					if(in_array($data1['ProfileCustomTitle'], $cusFields)) { //used alternative inputs for custom fields defined on top of this page
						echo  "			<fieldset class=\"rbtext\">";
						echo "<div><label for=\"ProfileCustomLabel_min\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input class=\"min_max\" type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."_min\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>\n";
						echo "<div><label for=\"ProfileCustomLabel_min\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input class=\"min_max\"  type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."_max\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>\n";
						echo  "			</fieldset>";
					}else{
			
									if ($ProfileCustomType == 1) { //TEXT
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
							
									 
										   
										
										
									} elseif ($ProfileCustomType == 2) { // Min Max
									echo  "			<fieldset class=\"rbtext\">";
									   
										$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data1['ProfileCustomOptions'],"}"),"{"));
										list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
									   
									 
										if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input class=\"min_max\" type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>\n";
												echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input class=\"min_max\" type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>\n";
									
											
										}else{
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input class=\"min_max\" type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", bb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input class=\"min_max\" type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
									
										   
										}
										echo  "			</fieldset>";
									 
									} elseif ($ProfileCustomType == 3) {
										
									echo  "			<div class=\"rbselect\">";
										
									  list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
											
											$data = explode("|",$option1);
											$data2 = explode("|",$option2);
											
											
								         
											echo "<div><label>".$data[0]."</label></div>";
											
											echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">\n";
											echo "<option value=\"\">Any ".$data1['ProfileCustomTitle']."</option>";
											  
												foreach($data as $val1){
													
													if($val1 != end($data) && $val1 != $data[0]){
													
													     $isSelected = "";
														if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]==$val1){
															$isSelected = "selected=\"selected\"";
															echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
														} else {
															echo "<option value=\"".$val1."\" >".$val1."</option>"; 
														}
												
													}
												}
											  	echo "</select></div>\n";
												
											/*	
											if(!empty($data2) && !empty($option2)){
												       echo "<div><label>".$data2[0]."</label></div>";
											
											 		$pos2 = 0;
													echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
													echo "<option value=\"\">--</option>";
													foreach($data2 as $val2){
														  
															if($val2 != end($data2) && $val2 !=  $data2[0]){
																if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]==$val2){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";
																}else{
																		echo "<option value=\"".$val2."\" >".$val2."</option>"; 
																}
															}
														}
													echo "</select></div>\n";
											
											}
									   */
										
										echo  "			</div>";

									} elseif ($ProfileCustomType == 4) {
										echo  "			<div class=\"rbtextarea\">";
										echo "<div><textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] ."</textarea></div>";
										echo  "			</div>";
									}
									 
									elseif ($ProfileCustomType == 5) {
										echo  "			<fieldset class=\"rbcheckbox\">";

								   		$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);

										foreach($array_customOptions_values as $val){

											/*
											 * double check this if this is array and its index 0 is empty
											 * then force set it to empty so it will not push through
											 */
											if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && is_array($_SESSION["ProfileCustomID". $data1['ProfileCustomID']])){ 
												if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']][0] == ""){
													$_SESSION["ProfileCustomID". $data1['ProfileCustomID']] = "";
												}
											}
											
											if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] != ""){ 

												$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));

												if(in_array($val,$dataArr,true) && $val != ""){
													echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />&nbsp;";
													echo "<span>". $val."</span></label><br />";
												} elseif($val !="") {
													echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />&nbsp;";
													echo "<span>". $val."</span></label><br />";
												}
											} else {
											    if($val !=""){	
														echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />&nbsp;";
														echo "<span>". $val."</span></label><br />";
												}
											}
										}
												  echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";
												  echo "</fieldset>";
									}
									
									elseif ($ProfileCustomType == 6) {
										echo  "			<fieldset class=\"rbradio\">";

									   	$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										   
										foreach($array_customOptions_values as $val){
											
											/*
											 * double check this if this is array and its index 0 is empty
											 * then force set it to empty so it will not push through
											 */
											if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && is_array($_SESSION["ProfileCustomID". $data1['ProfileCustomID']])){ 
												if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']][0] == ""){
													$_SESSION["ProfileCustomID". $data1['ProfileCustomID']] = "";
												}
											}
											
											if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] != ""){ 

												$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));

												if(in_array($val,$dataArr) && $val !=""){
													echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
													echo "". $val."</label><br />";
												} else {
													echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
													echo "". $val."</label><br />";	
												}
											} else {
												echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
												echo "". $val."</label><br />";	
											}
										}
										echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";									       
										echo "</fieldset>";									       
									}
									
									elseif ($ProfileCustomType == 7){
										 
										list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);

										if($data1['ProfileCustomTitle']=="Height" AND $bb_agency_option_unittype==1){
											echo  "			<fieldset class=\"rbselect\">";

		  echo "<div><label>Min</label><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."_min\">\n";
														  if (empty($ProfileCustomValue)) {
															echo "  <option value=\"\">--</option>\n";
														  }
														  // 
														  $i=12;
														  $heightraw = 0;
														  $heightfeet = 0;
														  $heightinch = 0;
														  while($i<=90)  { 
															  $heightraw = $i;
															  $heightfeet = floor($heightraw/12);
															  $heightinch = $heightraw - floor($heightfeet*12);
														  echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
																  $i++;
																}
														  echo " </select></div>\n";
														  
														   echo "<div><label>Max</label><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."_min\">\n";
														  if (empty($ProfileCustomValue)) {
															echo "  <option value=\"\">--</option>\n";
														  }
														  // 
														  $i=12;
														  $heightraw = 0;
														  $heightfeet = 0;
														  $heightinch = 0;
														  while($i<=90)  { 
															  $heightraw = $i;
															  $heightfeet = floor($heightraw/12);
															  $heightinch = $heightraw - floor($heightfeet*12);
														  echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
																  $i++;
																}
														  echo " </select></div>\n";
														  echo  "			</fieldset>";
										} else {
											echo  "			<fieldset class=\"rbtext\">";
											// for other search
											echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
											."_min\">Min</label><input value=\""
											.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
											."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
											.$data1['ProfileCustomID']."[]\" /></div>";

											echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
											."_max\">Max</label><input value=\"".$max_val
											."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
											echo  "			</fieldset>";
										}
									}		
			
					}//end of if(in_array("$data1['ProfileCustomTitle']", $cusFields))
			
			  
			
			
			echo  "			</td>";
			echo  "			</tr>";
				
		} //end of while ($data1
   
        // status filter

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Status", bb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileIsActive\" id=\"ProfileIsActive\">\n";               
		echo "							<option value=\"\">". __("Any Status", bb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"1\"". selected($_SESSION['ProfileIsActive'], 1) .">". __("Active", bb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"4\"". selected($_SESSION['ProfileIsActive'], 4) .">". __("Not Visible", bb_agency_TEXTDOMAIN) . "</option>\n";
        echo "							<option value=\"0\"". selected($_SESSION['ProfileIsActive'], 0) .">". __("Inactive", bb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"2\"". selected($_SESSION['ProfileIsActive'], 2) .">". __("Archived", bb_agency_TEXTDOMAIN) . "</option>\n";
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				  </thead>\n";
		echo "				</table>\n";
		echo "				<p class=\"submit\">\n";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "				<input type=\"reset\" onclick=\"redirectSearch();\" name=\"reset\" value=\"". __("Reset Form", bb_agency_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
		echo "				</p>\n";
		echo "        	<form>\n";

		echo "        	<div>\n";
     
		echo "    </div><!-- .container -->\n";
		echo "  </div>\n";
		echo "</div>\n";
?>
</div>