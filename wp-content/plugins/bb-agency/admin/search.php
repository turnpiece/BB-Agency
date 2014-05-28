<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php");

    if (!isset($_GET['action']) || $_GET['action'] == '') {
        unset($_SESSION);
    }
    global $wpdb;

    $action = isset($_GET['action']) ? $_GET['action'] : false;

    $cusFields = array("Suit","Bust","Shirt","Dress","Height");  //for custom fields min and max

    $bb_agency_options_arr = bbagency_get_option();
    $bb_agency_option_unittype =  bbagency_get_option('bb_agency_option_unittype');
    $bb_agency_option_persearch = (int)bbagency_get_option('bb_agency_option_persearch');
    $bb_agency_option_agencyemail = (int)bbagency_get_option('bb_agency_option_agencyemail');
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

if ($action) {

    // Protect and defend the cart string!
    $cartString = "";
    
    // Add to Cart
    if ($action == "cartAdd" ) { 
        
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
    
    } elseif ($action == "formEmpty") {  // Handle Form Empty 
        extract($_SESSION);
        foreach($_SESSION as $key=>$value) {
            if (substr($key, 0, 7) == "Profile") {
                unset($_SESSION[$key]);
            }
        }
    } elseif ($action == "cartEmpty") {  // Handle Cart Removal
        // Throw the baby out with the bathwater
        unset($_SESSION['cartArray']);
        
    } elseif (($action == "cartRemove") && (isset($_GET["RemoveID"]))) {
        $cartArray = $_SESSION['cartArray'];
        $cartString = implode(",", $cartArray);
        $cartRemoveID = $_GET["RemoveID"];
        $cartString = str_replace($_GET['RemoveID'] ."", "", $cartString);
        $cartString = bb_agency_cleanString($cartString);
        // Put it back in the array, and wash your hands
        $_SESSION['cartArray'] = array($cartString);
    
    } elseif (($action == "searchSave") && isset($_SESSION['cartArray'])) {
    
        extract($_SESSION);
        foreach($_SESSION as $key => $value) {
            $cartArray[$key] = $value;
        }
        $_SESSION['cartArray'] = $cartArray;
    
    }


// *************************************************************************************************** //
// Get Search Results       

    if ($action == "search") {

        // Sort By
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
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
        $filter = " WHERE profile.`ProfileID` > 0";
        // Name
        if (isset($_GET['ProfileContactName']) && !empty($_GET['ProfileContactName']) ||
            isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst']) || 
            isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])) {
            if (isset($_GET['ProfileContactName']) && !empty($_GET['ProfileContactName'])){
                $ProfileContactName = $_GET['ProfileContactName'];
                // split into words
                $names = explode(' ', $ProfileContactName);

                if (!empty($names)) {
                    $nameFilter = array();

                    foreach ($names AS $name) {
                        if ($name)
                            $nameFilter[] = "profile.`ProfileContactNameFirst` LIKE '%{$name}%' OR profile.`ProfileContactNameLast` LIKE '%{$name}%'";
                    }

                    $filter .= ' AND ('.implode(' OR ', $nameFilter).')';
                }
            }
            if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
                $ProfileContactNameFirst = $_GET['ProfileContactNameFirst'];
                $filter .= " AND profile.`ProfileContactNameFirst` LIKE '". $ProfileContactNameFirst ."%'";
            }
            if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
                $ProfileContactNameLast = $_GET['ProfileContactNameLast'];
                $filter .= " AND profile.`ProfileContactNameLast` LIKE '". $ProfileContactNameLast ."%'";
            }
        }
        // Location
        if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
            $ProfileLocationCity = $_GET['ProfileLocationCity'];
            $filter .= " AND profile.`ProfileLocationCity` = '". $ProfileLocationCity ."'";
        }
        
        // Location state
        if (isset($_GET['ProfileLocationState']) && !empty($_GET['ProfileLocationState'])){
            $ProfileLocationState = $_GET['ProfileLocationState'];
            $filter .= " AND profile.`ProfileLocationState` = '". $ProfileLocationState ."'";
        }

        // Location range search
        if (isset($_GET['ProfileLocation']) && !empty($_GET['ProfileLocation'])) {
            $ProfileLocation = $_GET['ProfileLocation'];

            if ($location = bbagency_geocode($ProfileLocation)) {
                $lat = $location['lat'];
                $lng = $location['lng'];
                $distance = "((ACOS(SIN($lat * PI() / 180) * SIN(`ProfileLocationLatitude` * PI() / 180) + COS($lat * PI() / 180) * COS(`ProfileLocationLatitude` * PI() / 180) * COS(($lng - `ProfileLocationLongitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)";
                $select[] = "$distance AS `distance`";
                $having[] = "`distance` <= 25";
                $sort = '`distance`';
                $sortDirection = "desc";
                $dir = "asc";
            }
        }
        
        // Type
        if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
            $ProfileType = $_GET['ProfileType'];
            $filter .= " AND profile.`ProfileType` like'%". $ProfileType ."%'";
            //$filter .= " AND Find_in_set (". $ProfileType .",profile.ProfileType) ";
        } else {
            $ProfileType = "";
        }   
        if (isset($_GET['ProfileIsActive'])&& $_GET['ProfileIsActive'] !="") {
            $ProfileIsActive = $_GET['ProfileIsActive'];
            $filter .= " AND profile.`ProfileIsActive` = ". $ProfileIsActive ."";       
        }       
        // Set Filter to exclude inactive profiles
        // and pending for approval profiles from
        // search       
        //$filter .= " AND profile.ProfileIsActive!=0 AND profile.ProfileIsActive!=3";
        
        // Gender
        if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])){
            $ProfileGender = $_GET['ProfileGender'];
         
            $filter .= " AND profile.`ProfileGender` = '{$ProfileGender}'";
         
        } else {
            $ProfileGender = "";
        }

        // Age & due date
        $timezone_offset = 0; // GMT
        $dateInMonth = gmdate('d', time() + $timezone_offset * 60 * 60);
        $format = 'Y-m-d';
        $date = gmdate($format, time() + $timezone_offset * 60 * 60);
        
        if (isset($_GET['ProfileDateBirth_min']) && !empty($_GET['ProfileDateBirth_min'])){
            $ProfileDateBirth_min = $_GET['ProfileDateBirth_min'];
            $selectedYearMin = date($format, strtotime('-'. $ProfileDateBirth_min .' year'. $date));
            $filter .= " AND profile.`ProfileDateBirth` <= '$selectedYearMin'";
        }
        
        if (isset($_GET['ProfileDateBirth_max']) && !empty($_GET['ProfileDateBirth_max'])){
            $ProfileDateBirth_max = $_GET['ProfileDateBirth_max'];
            $selectedYearMax = date($format, strtotime('-'. $ProfileDateBirth_max-1 .' year'. $date));
            $filter .= " AND profile.`ProfileDateBirth` >= '$selectedYearMax'";
        }

        // Due date
        if (isset($_GET['ProfileDateDue_min']) && !empty($_GET['ProfileDateDue_min'])){
            $ProfileDateDue_min = $_GET['ProfileDateDue_min'];
            $filter .= " AND profile.`ProfileDateDue` >= '$ProfileDateDue_min'";
        }
        
        if (isset($_GET['ProfileDateDue_max']) && !empty($_GET['ProfileDateDue_max'])){
            $ProfileDateDue_max = $_GET['ProfileDateDue_max'];
            $filter .= " AND profile.`ProfileDateDue` <= '$ProfileDateDue_max'";
        }
        ?>
        <div class="boxblock-holder">
        <?php    
        // Filter Models Already in Cart
        if (isset($_SESSION['cartArray'])) {
            $cartArray = $_SESSION['cartArray'];
            $cartString = implode(",", $cartArray);
            $cartQuery = " AND profile.`ProfileID` NOT IN (". $cartString .")";
        } else {
            $cartQuery = '';
        }
        $filterDropdown = array();
            
        $filter2 = '';
        $filters = array();

        foreach ($_GET as $key => $val) {
                        
            if (substr($key, 0, 15) == "ProfileCustomID") {
                
                if ((!empty($val) AND !is_array($val)) OR (is_array($val) AND count(array_filter($val)) > 0)) {
                   
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
                    
                    // get key id
                    $keyID = substr($key, 15);
                    $keyID = preg_replace("/_\w+$/", '', $keyID);

                    
                    // Have created a holder $filter2 and
                    // create its own filter here and change
                    // AND should be OR
                    
                    if (in_array($ProfileCustomType['ProfileCustomTitle'], $cusFields)) {
    
                        if (in_array($keyID, $filters))
                            continue;

                        // get max and min values
                        $minVal = $_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_min'];
                        $maxVal = $_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_max'];
                        
                        $filters[$keyID] = "cm{$keyID}.ProfileCustomValue BETWEEN '$minVal' AND '$maxVal'";

                        $_SESSION[$key] = $val;
                    } else {

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
                                                         
                            $_SESSION[$key] = $val;

                            // add filter
                            $filters[$keyID] = "cm{$keyID}.ProfileCustomValue LIKE('%".$val."%')";

                        } elseif ($ProfileCustomType["ProfileCustomType"] == 3) { // Dropdown

                            $filters[$keyID] = "cm{$keyID}.ProfileCustomValue IN('$val') AND LOWER(cm{$keyID}.ProfileCustomValue) = LOWER('{$val}')";

                        } elseif ($ProfileCustomType["ProfileCustomType"] == 4) { //Textarea

                            $_SESSION[$key] = $val;
                            $filters[$keyID] = "cm{$keyID}.ProfileCustomValue='$val'";

                        } elseif ($ProfileCustomType["ProfileCustomType"] == 5) { //Checkbox
                            if (!empty($val)) {
                                if (strpos($val, ",") === false) {
                                    $filters[$keyID] = "cm{$keyID}.ProfileCustomValue like('%{$val}%')";
                                } else {            
                                    $likequery = explode(",", $val);
                                    $likecounter = count($likequery);
                                    $i = 1; 
                                    $likedata = '';
                                    foreach ($likequery as $like) {
                                        if ($like == '')
                                            continue;

                                        $likedata .= " cm{$keyID}.ProfileCustomValue like('%{$like}%') ". ($i < ($likecounter - 1) ? " OR " : '');
                                        $i++;
                                    }                                    
                                    
                                    $val = substr($val, 0, -1);

                                    $filters[$keyID] = "($likedata)";
                                }

                                $_SESSION[$key] = $val;
                            } else{
                                $_SESSION[$key] = '';
                            }

                        } elseif ($ProfileCustomType["ProfileCustomType"] == 6) { //Radiobutton 
                            //var_dump($ProfileCustomType["ProfileCustomType"]);
                            $val = implode("','",explode(",",$val));

                            $_SESSION[$key] = $val;
                            
                            $filters[$keyID] = "cm{$keyID}.ProfileCustomValue LIKE('%{$val}%')";   

                        } elseif ($ProfileCustomType["ProfileCustomType"] == 7) { //Measurements 

                            list($Min_val,$Max_val) = explode(",",$val);
                            if (!empty($Min_val) && !empty($Max_val)) {

                                $_SESSION[$key] = $val;

                                $filters[$keyID] = "cm{$keyID}.ProfileCustomValue BETWEEN '{$Min_val}' AND '{$Max_val}'";
                            }
                        }
                    }
                        
                    mysql_free_result($q);
                } // if not empty
            }  // end if
        } // end for each
      
        if (count($filterDropdown) > 0){
           if($filter2==""){
           $filter2 .= " AND ( customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
           } else {
           $filter2 .=" OR customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
           }     
        }

        $joins = array();
        $where = array();

        foreach ($filters as $key => $value) {
            $joins[] = "\nLEFT JOIN ". table_agency_customfield_mux ." AS cm{$key} ON profile.ProfileID = cm{$key}.ProfileID AND cm{$key}.ProfileCustomID = '$key'";
            $where[] = $value;
        }

        /*
         * Refine filter and add the created 
         * holder $filter to $filter if not
         * equals to blanks
         */
        if ($filter2 !=""){
            $filter2 .= " ) ";
            $filter .= $filter2;
        }

        if (!empty($where)) {
            $filter .= ' AND '.implode(" \nAND ", $where);
        }

        // Search Results   
        $query = "
             SELECT 
             profile.*,
             CONCAT(profile.`ProfileContactNameFirst`,' ',profile.`ProfileContactNameLast`) AS `ProfileContactName`,
             profile.ProfileID as pID, 
             customfield_mux.*, ".
             (!empty($select) ? implode(', ', $select).', ' : '')."
                    (
                      SELECT media.ProfileMediaURL 
                              FROM ". table_agency_profile_media ." media 
                      WHERE profile.ProfileID = media.ProfileID 
                            AND 
                            media.ProfileMediaType = \"Image\" 
                            AND 
                            media.ProfileMediaPrimary = 1
                    ) 
                    AS ProfileMediaURL FROM ". table_agency_profile ." profile ".
            (empty($joins) ? '' : implode(' ', $joins))." 
            LEFT JOIN ". table_agency_customfield_mux ." 
                        AS customfield_mux 
                    ON profile.ProfileID = customfield_mux.ProfileID  
                    ".$filter." ".$cartQuery."   
            GROUP BY profile.ProfileID ".
            (empty($having) ? '' : 'HAVING '.implode(' AND ', $having))." 
            ORDER BY $sort $dir $limit";

        // Search Results
        $results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
        ?>
        <h2 class="title">Search Results: <?php echo $count ?></h2>
        <?php
        //echo "<pre>$query</pre>";
        
        if ($count > $bb_agency_option_persearch - 1 && !isset($_GET['limit']) && empty($_GET['limit'])) : ?>
            <div id="message" class="error">
                <p>Search exceeds <?php echo $bb_agency_option_persearch ?> records first <?php echo $bb_agency_option_persearch ?> displayed below.  <a href="<?php echo admin_url('admin.php?page='. $_GET['page'] . '&' . $sessionString . '&limit=none') ?>"><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p>
            </div>
        <?php endif; ?>
        <form method="GET" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
            <input type="hidden" name="page" id="page" value="<?php echo $_GET['page'] ?>" />
            <input type="hidden" name="action" value="cartAdd" />
            <?php if (isset($_SESSION['cartArray'])) : ?>
            <input type="hidden" name="forceCart" value="<?php echo $_SESSION['cartArray'] ?>" />
            <?php endif; ?>
            <table cellspacing="0" class="widefat fixed">
                <thead>
                    <tr class="thead">
                        <th class="manage-column column-cb check-column" id="cb" scope="col">
                            <input type="checkbox" />
                        </th>
                        <th class="column-ProfileID" id="ProfileID" scope="col">
                            <a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&sort=ProfileID&dir='. $sortDirection) ?>"><?php _e("ID", bb_agency_TEXTDOMAIN) ?></a>
                        </th>
                        <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e("Contact Information", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileStats" id="ProfileStats" scope="col"><?php _e("Private Details", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileDetails" id="ProfileDetails" scope="col"><?php _e("Public Details", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileImage" id="ProfileImage" scope="col"><?php _e("Headshot", bb_agency_TEXTDOMAIN) ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="thead">
                        <th class="manage-column column-cb check-column" id="cb" scope="col">
                            <input type="checkbox"/>
                        </th>
                        <th class="column-ProfileID" id="ProfileID" scope="col">
                            <a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&sort=ProfileID&dir='. $sortDirection) ?>"><?php _e("ID", bb_agency_TEXTDOMAIN) ?></a>
                        </th>
                        <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e("Contact Information", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileStats" id="ProfileStats" scope="col"><?php _e("Private Details", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileDetails" id="ProfileDetails" scope="col"><?php _e("Public Details", bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileImage" id="ProfileImage" scope="col"><?php _e("Headshot", bb_agency_TEXTDOMAIN) ?></th>
                    </tr>
                </tfoot>
                <tbody>
                <?php 
                while ($data = mysql_fetch_array($results2)) :
                    $ProfileID = $data['pID'];
                    $isInactive = $data['ProfileIsActive'] == 0;
                    $isInactiveDisable = $data['ProfileIsActive'] ? '' : 'disabled="disabled"';  
                ?>
                    <tr class="<?php echo $isInactive ? 'inactive' : 'active' ?>">
                        <th class="check-column" scope="row" >
                           <input type="checkbox" <?php echo $isInactiveDisable ?> value="<?php $ProfileID ?>" class="administrator" id="ProfileID<?php echo $ProfileID ?>" name="ProfileID[]" />
                        </th>
                        <td class="ProfileID column-ProfileID"><?php echo $ProfileID ?></td>
                        <td class="ProfileContact column-ProfileContact">
                            <div class="title">
                                <h2><?php echo $data['ProfileContactName'] ?></h2>
                            </div>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&amp;action=editRecord&amp;ProfileID='. $ProfileID) ?>" title="Edit this profile"><?php _e('Edit', bb_agency_TEXTDOMAIN) ?></a> | 
                                </span>
                                <span class="review">
                                    <a href="<?php echo bb_agency_PROFILEDIR . $data['ProfileGallery'] ?>" target="_blank"><?php _e('View', bb_agency_TEXTDOMAIN) ?></a> | 
                                </span>
                                <span class="delete">
                                    <a class="submitdelete" title="Remove this profile" href="<?php echo admin_url('admin.php?page=bb_agency_profiles&amp;deleteRecord&amp;ProfileID='. $ProfileID) ?>" onclick="if ( confirm('You are about to delete the model '<?php echo $data['ProfileContactName'] ?>') ) { return true; } return false;"><?php _e('Delete', bb_agency_TEXTDOMAIN) ?></a>
                                </span>
                            </div>
                        <?php if (!empty($isInactiveDisable)) : ?>
                            <div>
                                <strong><?php _e('Profile Status', bb_agency_TEXTDOMAIN) ?>:</strong> <span class="inactive"><?php _e('Inactive', bb_agency_TEXTDOMAIN) ?></span>
                            </div>
                        <?php endif; ?>
                        </td>
                
                       <!-- private info -->
                        <td class="ProfileStats column-ProfileStats">
                        <?php if (!empty($data['ProfileContactEmail'])) : ?>
                            <div>
                                <strong><?php _e('Email', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo $data['ProfileContactEmail'] ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($data['ProfileLocationStreet']) || !empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState']) || !empty($data['ProfileLocationZip'])) : ?>
                            <div>
                                <strong><?php _e('Address', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo implode(', ', array($data['ProfileLocationStreet'], $data['ProfileLocationCity'], $data['ProfileLocationState'], $data['ProfileLocationZip'])) ?>
                            </div>
                        <?php endif; ?>    
                        <?php if (!empty($data['ProfileLocationCountry'])) : ?>
                            <div>
                                <strong><?php _e("Country", bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo $data['ProfileLocationCountry'] ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($data['distance'])) : ?>
                            <div>
                                <strong><?php _e("Distance", bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo number_format((float)$data['distance'], 1, '.', '') ?> miles
                            </div>
                        <?php endif; ?>

                        <?php if (defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID && bb_agency_ismumtobe($data['ProfileType']) && !empty($data['ProfileDateDue'])) : ?>                             
                        <div>
                            <strong><?php _e("Due date", bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo $data['ProfileDateDue'] ?></div>
                        </div>
                        <?php endif;

                        foreach (array(
                            __("Birth date", bb_agency_TEXTDOMAIN) => $data['ProfileDateBirth'],
                            __("Website", bb_agency_TEXTDOMAIN) => $data['ProfileContactWebsite'],
                            __("Phone Home", bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneHome'],
                            __("Phone Cell", bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneCell'],
                            __("Phone Work", bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneWork']
                            ) as $label => $value) : if ($value) : ?>
                            <div>
                                <strong><?php echo $label ?>:</strong> <?php echo $value ?></div>
                            </div>
                        <?php endif; endforeach;

                        // get private custom fields
                        $resultsCustomPrivate =  $wpdb->get_results("SELECT c.`ProfileCustomID`, c.`ProfileCustomTitle`, c.`ProfileCustomOrder`, c.`ProfileCustomView`, cx.`ProfileCustomValue` FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.`ProfileCustomID` = cx.ProfileCustomID WHERE c.`ProfileCustomView` > 0 AND cx.`ProfileID` = ". $ProfileID ." GROUP BY cx.`ProfileCustomID` ORDER BY c.`ProfileCustomOrder` DESC");
                        foreach ($resultsCustomPrivate as $resultCustomPrivate) : ?>
                          <div>
                            <strong><?php echo $resultCustomPrivate->ProfileCustomTitle ?><span class="divider">:</span></strong> <?php echo $resultCustomPrivate->ProfileCustomValue ?>
                          </div>
                        <?php endforeach; ?>
                        </td>
              
                       <!-- public info -->
                        <td class="ProfileDetails column-ProfileDetails">

                        <?php if (!empty($data['ProfileGender'])) : ?>
                            <div>
                                <strong><?php _e("Gender", bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo bb_agency_getGenderTitle($data['ProfileGender']) ?bb_agency_getGenderTitle($data['ProfileGender']) : '--' ?>
                            </div>
                        <?php endif;

                        $resultsCustom = $wpdb->get_results("SELECT c.`ProfileCustomID`, c.`ProfileCustomTitle`, c.`ProfileCustomOrder`, c.`ProfileCustomView`, cx.`ProfileCustomValue` FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.`ProfileCustomID` = cx.`ProfileCustomID` WHERE c.`ProfileCustomView` = 0 AND cx.`ProfileID` = ". $ProfileID ." GROUP BY cx.`ProfileCustomID` ORDER BY c.`ProfileCustomOrder` ASC");
                        foreach  ($resultsCustom as $resultCustom) : if (!empty($resultCustom->ProfileCustomValue)) : ?>
                            <div>
                                <strong><?php echo $resultCustom->ProfileCustomTitle ?><span class="divider">:</span></strong> <?php echo $resultCustom->ProfileCustomID == 5 ? display_height($resultCustom->ProfileCustomValue) : $resultCustom->ProfileCustomValue ?>
                            </div>
                        <?php endif; endforeach; ?>

                        </td>
                        <td class="ProfileImage column-ProfileImage">
                        <?php if (isset($data['ProfileMediaURL']) && !empty($data['ProfileMediaURL'])) : ?>
                            <div class="image">
                                <img style="width: 150px;" src="<?php echo bb_agency_UPLOADDIR . $data['ProfileGallery'] .'/'. $data['ProfileMediaURL'] ?>" />
                            </div>
                        <?php else : ?>
                            <div class="image no-image">NO IMAGE</div>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile;
            
                // clear mysql 
                mysql_free_result($results2);

                // check for no results
                if ($count < 1) : ?>
                    <tr>
                        <th class="check-column" scope="row"></th>
                        <td class="name column-name" colspan="5">
                            <p>
                                <?php 
                                if (isset($filter))
                                    _e("No profiles found.", bb_agency_TEXTDOMAIN);
                                else
                                    _e("There aren't any profiles in the system yet.", bb_agency_TEXTDOMAIN);
                                ?>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
           
            <p>
                <input type="submit" name="CastingCart" value="<?php _e('Add to Casting Cart','bb_agency_search') ?>" class="button-primary" />
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print" class="button-primary"><?php _e("Quick Print", bb_agency_TEXTDOMAIN) ?></a>
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print - Without Details" class="button-primary"><?php _e("Quick Print", bb_agency_TEXTDOMAIN) ?> - <?php _e("Without Details", bb_agency_TEXTDOMAIN) ?></a>
            </p>
        </form>
<?php      
    
    } // end of if action = search

    // display casting cart
    if (($action == "search") || ($action == "cartAdd") || (isset($_SESSION['cartArray']))) {

        echo "<div class=\"boxblock-container left-half\">\n";
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

                    if (defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID && bb_agency_ismumtobe($data['ProfileType']) && !empty($data['ProfileDateDue'])) {
                        echo "<strong>Due date:</strong> ". bb_agency_get_due_date($data['ProfileDateDue']) ."<br />\n";
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
  
    // empty or remove casting cart
    if (isset($cartAction) && ($cartAction == "cartEmpty" || $cartAction == "cartRemove")) : ?>
        <a name="compose">&nbsp;</a> 
        <div class="boxblock">
            <h3><?php _e("Cart Actions", bb_agency_TEXTDOMAIN) ?></h3>
            <div class="inner">
                <a href="<?php echo admin_url('admin.php?page=bb_agency_searchsaved&action=searchSave') ?>" title="<?php _e("Save Search & Email", bb_agency_TEXTDOMAIN) ?>" class="button-primary"><?php _e('Save Search & Email', bb_agency_TEXTDOMAIN) ?></a>
                <a href="<?php echo admin_url('admin.php?page=bb_agency_search&action=massEmail#compose') ?>" title="<?php _e('Mass Email', bb_agency_TEXTDOMAIN) ?>" class="button-primary"><?php _e('Mass Email', bb_agency_TEXTDOMAIN) ?></a>
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=castingCart&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?></a>
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=castingCart&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print - Without Details" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?> - <?php _e('Without Details', bb_agency_TEXTDOMAIN) ?></a>
            </div>
        </div>
    <?php endif; // Is Cart Empty 
    
    $isSent = false;
    
    // send a bulk email
    if (isset($_POST["SendEmail"])){
        
        $bb_agency_options_arr = bbagency_get_option();
        $bb_agency_value_agencyname = bbagency_get_option('bb_agency_option_agencyname');
        $bb_agency_value_agencyemail = bbagency_get_option('bb_agency_option_agencyemail');
        $correspondenceEmail= $bb_agency_value_agencyemail;
        add_filter('wp_mail_content_type','bb_agency_set_content_type');

        function bb_agency_set_content_type($content_type) {
            return 'text/html';
        }
  
        
        
        $MassEmailSubject = $_POST["MassEmailSubject"];
        $MassEmailMessage = $_POST["MassEmailMessage"];
        $MassEmailRecipient = $_POST["MassEmailRecipient"];
        $MassEmailBccRecipient = $_POST["MassEmailBccRecipient"];

        $SearchID               = time(U);
        $SearchMuxHash          = bb_agency_random(8);
        $SearchMuxToName        =$_POST["MassEmailRecipient"];
        $SearchMuxToEmail       =$_POST["MassEmailRecipient"];
        
        $SearchMuxEmailToBcc    =$_POST['MassEmailBccRecipient'];
        $SearchMuxSubject       = $_POST['MassEmailSubject'];
        $SearchMuxMessage       =$_POST['MassEmailMessage'];
        $SearchMuxCustomValue   ='';


                    

        $cartArray = $_SESSION['cartArray'];
        
        $cartString = implode(",", array_unique($cartArray));
        $cartString = bb_agency_cleanString($cartString);
        

        $wpdb->query("INSERT INTO " . table_agency_searchsaved." (SearchProfileID,SearchTitle) VALUES('".$cartString."','".$SearchMuxSubject."')") or die(mysql_error());
        
        $lastid = $wpdb->insert_id;
        
        // Create Record
        $insert = "INSERT INTO " . table_agency_searchsaved_mux ." 
                (
                SearchID,
                SearchMuxHash,
                SearchMuxToName,
                SearchMuxToEmail,
                SearchMuxSubject,
                SearchMuxMessage,
                SearchMuxCustomValue
                )" .
                "VALUES
                (
                '" . $wpdb->escape($lastid) . "',
                '" . $wpdb->escape($SearchMuxHash) . "',
                '" . $wpdb->escape($SearchMuxToName) . "',
                '" . $wpdb->escape($SearchMuxToEmail) . "',
                '" . $wpdb->escape($SearchMuxSubject) . "',
                '" . $wpdb->escape($SearchMuxMessage) . "',
                '" . $wpdb->escape($SearchMuxCustomValue) ."'
                )";
        $results = $wpdb->query($insert);                 
                

        if(!empty($MassEmailBccRecipient)){
            $bccMails = explode(",",$MassEmailBccRecipient);
            foreach($bccMails as $bccEmail){
                $headers[] = 'Bcc: '.$bccEmail;
            }
            }
        
        // Mail it
        $headers[]  = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: '.$bb_agency_value_agencyname.' <'. $correspondenceEmail .'>';
        
        if(!empty($expMail)){
            $expMail = explode(",",$MassEmailRecipient);
            foreach($expMail as $bccEmail){
                $headers[] = 'Bcc: '.$bccEmail;
            }
        }
         //$MassEmailMessage    = str_ireplace("[link-place-holder]",get_bloginfo("url") ."/client-view/".$SearchMuxHash,$MassEmailMessage);

        $MassEmailMessage = str_replace("[link-place-holder]",site_url()."/client-view/".$SearchMuxHash,$MassEmailMessage);

        $isSent = wp_mail($MassEmailRecipient, $MassEmailSubject, $MassEmailMessage, $headers);
    }

    // send bulk email
    if ($action == "massEmail") {
        
        // Filter Models Already in Cart
        if (isset($_SESSION['cartArray'])) {
            $cartArray = $_SESSION['cartArray'];
            $cartString = implode(",", $cartArray);
            $cartQuery =  " AND profile.ProfileContactEmail !='' AND profile.ProfileID IN (". $cartString .")";
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
            if ($count != $pos) {
                $recipient .=", ";     
            }    
        }
        // Email
        $bb_agency_options_arr = bbagency_get_option();
        $bb_agency_value_agencyname = bbagency_get_option('bb_agency_option_agencyname');
        $bb_agency_value_agencyemail = bbagency_get_option('bb_agency_option_agencyemail');
        echo "<form method=\"post\">";
        echo "     <div class=\"boxblock\">\n";
        echo "        <h3>". __("Compose Email", bb_agency_TEXTDOMAIN) ."</h3>\n";
        echo "        <div class=\"inner\">\n";
        if ($isSent) {
            echo "<div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";  
        }
        //Commented to change default recipient to wp-admin
        // echo "<strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$recipient."</textarea><br/>";
        echo "<strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$bb_agency_value_agencyemail."</textarea><br/>";
        //Bcc recipients
        echo "<strong>Bcc:</strong><br/><textarea name=\"MassEmailBccRecipient\" style=\"width:100%;\" placeholder=\"Enter Comma seperated values\">".$recipient."</textarea><br/>";
     
        echo "        <strong>Subject:</strong> <br/><input type=\"text\" name=\"MassEmailSubject\" style=\"width:100%\"/>";
        echo "<br/>";
        /*echo "      <strong>Message:</strong><br/>     <textarea name=\"MassEmailMessage\"  style=\"width:100%;height:300px;\">this message was sent to you by ".$bb_agency_value_agencyname." ".network_site_url( '/' )."</textarea>";*/
        
        $content = "This message was sent to you by ".$bb_agency_value_agencyname." ".network_site_url( '/' )."<br /> [link-place-holder]";
        $editor_id = 'MassEmailMessage';
        wp_editor( $content, $editor_id,array("wpautop"=>false,"tinymce"=>true) );
        echo "<input type=\"submit\" value=\"". __("Send Email", bb_agency_TEXTDOMAIN) . "\" name=\"SendEmail\"class=\"button-primary\" />\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "</form>";
    }
    
    echo "    </div><!-- .container -->\n";
} 

}
?>
    <div class="boxblock-container left-half">
    <div class="boxblock">
        <h3><?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?></h3>
        <div class="inner">
        <form method="GET" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
            <input type="hidden" name="page" id="page" value="bb_agency_search" />
            <input type="hidden" name="action" value="search" />
            <table cellspacing="0" class="widefat fixed">
                <thead>
                    <tr>
                        <th scope="row"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <input type="text" id="ProfileContactName" name="ProfileContactName" value="<?php echo isset($ProfileContactName) ? $ProfileContactName : '' ?>" />               
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e("Classification", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <select name="ProfileType" id="ProfileType">               
                                <option value="">--</option>
                                <?php
                                /* 
                                 * set filter from theis array
                                 * to block the following profile types 
                                 * in search
                                 */
                                
                                $filter = array( 'agents', 'agent', 'producer', 'producers' );
                                 
                                $profileDataTypes = mysql_query("SELECT * FROM ". table_agency_data_type ."");
                                while ($dataType = mysql_fetch_array($profileDataTypes)) : if (!in_array(strtolower($dataType["DataTypeTitle"]), $filter)) : ?>
                                <option value="<?php echo $dataType['DataTypeID'] ?>" <?php selected(isset($_SESSION['ProfileType']) ? $_SESSION['ProfileType'] : false, $dataType["DataTypeID"]) ?>><?php echo $dataType['DataTypeTitle'] ?></option>
                                <?php endif; endwhile; ?>
                                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <select name="ProfileGender" id="ProfileGender">               
                                <option value="">--</option>
                                <?php
                                    $query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
                                    $results2 = mysql_query($query2);
                                    while ($dataGender = mysql_fetch_array($results2)) : ?>
                                <option value="<?php echo $dataGender['GenderID'] ?>" <?php selected(isset($_SESSION['ProfileGender']) ? $_SESSION['ProfileGender'] : 0, $dataGender['GenderID']) ?>><?php echo $dataGender['GenderTitle'] ?></option>
                                    <?php endwhile; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e("Age", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <fieldset>
                                <div>
                                    <label for="ProfileDateBirth_min"><?php _e("Min", bb_agency_TEXTDOMAIN) ?></label>
                                    <input type="text" class="min_max" id="ProfileDateBirth_min" name="ProfileDateBirth_min" />
                                </div>

                                <div>
                                    <label for="ProfileDateBirth_max"><?php _e("Max", bb_agency_TEXTDOMAIN) ?></label>
                                    <input type="text" class="min_max" id="ProfileDateBirth_max" name="ProfileDateBirth_max" />
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    <?php if (defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID) : ?>
                    <tr>
                        <th scope="row"><?php _e("Due date", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <fieldset>
                                <div>
                                    <label for="ProfileDateDue_min"><?php _e("Min", bb_agency_TEXTDOMAIN) ?></label>
                                    <input type="text" class="min_max bbdatepicker" id="ProfileDateDue_min" name="ProfileDateDue_min" />
                                </div>

                                <div>
                                    <label for="ProfileDateDue_max"><?php _e("Max", bb_agency_TEXTDOMAIN) ?></label>
                                    <input type="text" class="min_max bbdatepicker" id="ProfileDateDue_max" name="ProfileDateDue_max" />
                                </div>
                            </fieldset>
                        </td>
                    </tr> 
                    <?php endif; ?>
                    <tr>
                        <th scope="row"><?php _e("Town", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <select name="ProfileLocationCity" id="ProfileLocationCity">               
                                <option value="">--</option>
                                <?php  // get a list of cities 
                                $profilecity = mysql_query('SELECT DISTINCT `ProfileLocationCity` FROM '.table_agency_profile);
                                
                                while ($dataLocation = mysql_fetch_array($profilecity)) : $city = $dataLocation['ProfileLocationCity']; ?>
                                <option value="<?php echo $city ?>" <?php selected(isset($_GET['ProfileLocationCity']) ? $_GET['ProfileLocationCity'] : false, $city) ?>><?php echo bb_agency_strtoproper($city) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e("County", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <select name="ProfileLocationState" id="ProfileLocationState">               
                                <option value="">--</option>
                                <?php  // get a list of states
                                $profilestate = mysql_query('SELECT DISTINCT `ProfileLocationState` FROM '.table_agency_profile);
                                
                                while ($dataLocation = mysql_fetch_array($profilestate)) : $state = $dataLocation['ProfileLocationState']; ?>
                                <option value="<?php echo $state ?>" <?php selected(isset($_GET['ProfileLocationState']) ? $_GET['ProfileLocationState'] : false, $state) ?>><?php echo bb_agency_strtoproper($state) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e("Location", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <input type="text" name="ProfileLocation" value="<?php echo isset($_GET['ProfileLocation']) ? $_GET['ProfileLocation'] : '' ?>" />
                        </td>
                    </tr>
                    <?php
                        //bb_custom_fields(0, $ProfileID, $ProfileGender,false);
                        $query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView IN('0','1')  AND ProfileCustomID != 39 AND ProfileCustomID != 48 ORDER BY ProfileCustomOrder ASC";
                        $results1 = mysql_query($query1);
                        $count1 = mysql_num_rows($results1);
                        $pos = 0;
                        while ($data1 = mysql_fetch_array($results1)) :
                            // set data vars
                            $id = $data1['ProfileCustomID'];
                            $title = $data1['ProfileCustomTitle'];
                            $type = $data1['ProfileCustomType'];
                            $options = $data1['ProfileCustomOptions'];
                            $field = 'ProfileCustomID'.$id;
                    ?>
                    <tr>
                    <?php
                        // SET Label for Measurements
                        // Imperial(in/lb), Metrics(ft/kg)
                        $bb_agency_options_arr = bbagency_get_option();
                        $bb_agency_option_unittype  = bbagency_get_option('bb_agency_option_unittype');
                        //$measurements_label = "";
                        /*
                        0- metric
                        1 - cm
                        2 - kg
                        3 - inches/feet
                        1 - imperials 
                        1 - inches
                        2 - pounds
                        3 - inches/feet
                        */
                        /*
                        if ($type == 7) { //measurements field type
                            switch ($bb_agency_option_unittype) {
                                case 0:
                                switch ($options) {
                                    case 1:
                                    $measurements_label = "<em>(cm)</em>";
                                    break;

                                    case 2:
                                    $measurements_label = "<em>(kg)</em>";
                                    break;

                                    case 3:
                                    $measurements_label = "<em>(m)</em>";
                                    break;
                                }
                                break;

                                case 1:
                                switch ($options) {
                                    case 1:
                                    $measurements_label = "<em>(Inches)</em>";
                                    break;

                                    case 2:
                                    $measurements_label = "<em>(Pounds)</em>";
                                    break;

                                    case 3:
                                    $measurements_label = "<em>(Feet/Inches)</em>";
                                    break;
                                }
                                break;
                            }
                        }
                        */
                        ?>    
                        <th scope="row">
                            <?php if ($type == 7) : ?>
                            <div class="label">
                                <?php echo $title ?>
                            </div>
                            <?php else : ?>
                            <div>
                                <label for="<?php echo $field ?>"><?php echo $title ?></label>
                            </div>
                            <?php endif; ?>
                        </th>
                        <td>
                        <?php    
                        if (in_array($title, $cusFields)) : // use alternative inputs for custom fields defined at top of this page

                            if ($title == 'Height') : ?>
                                <fieldset class="bbselect">
                                    <div>
                                        <label>Min</label>
                                        <select name="<?php echo $field ?>_min">
                                            <option value="">--</option>
                                        <?php for ($i = 12; $i <= 90; $i++) : // display height options ?>
                                            <option value="<?php echo $i ?>" <?php selected(isset($_GET[$field.'_min']) ? $_GET[$field.'_min'] : false, $i) ?>><?php echo display_height($i) ?></option>
                                        <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Max</label>
                                        <select name="<?php echo $field ?>_max">
                                            <option value="">--</option>
                                        <?php for ($i = 12; $i <= 90; $i++) : // display height options ?>
                                            <option value="<?php echo $i ?>" <?php selected(isset($_GET[$field.'_max']) ? $_GET[$field.'_max'] : false, $i) ?>><?php echo display_height($i) ?></option>
                                        <?php endfor; ?>
                                        </select>
                                    </div>
                                </fieldset>
                            <?php else : ?>
                                <fieldset class="bbtext">
                                    <div>
                                        <label for="ProfileCustomLabel_min"><?php _e("Min", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                        <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $ProfileCustomOptions_Min_value ?>" />
                                    </div>
                                    <div>
                                        <label for="ProfileCustomLabel_max"><?php _e("Max", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                        <input class="min_max" type="text" name="<?php echo $field ?>_max" value="<?php echo $ProfileCustomOptions_Max_value ?>" />
                                    </div>
                                </fieldset>
                            <?php endif;

                        else : // standard fields
                            switch ($type) :
                            
                            case 1 : ?>
                            <div>
                                <input type="text" name="<?php echo $field ?>" value="<?php echo isset($_SESSION[$field]) ? $_SESSION[$field] : '' ?>" />
                            </div>
                            <?php break; ?>

                            <?php case 2 : // Min Max ?>

                            <fieldset class="bbtext">
                            <?php   
                                $ProfileCustomOptions_String = str_replace(",", ":", strtok(strtok($options, "}"), "{"));
                                list($ProfileCustomOptions_Min_label, $ProfileCustomOptions_Min_value, $ProfileCustomOptions_Max_label, $ProfileCustomOptions_Max_value) = explode(":", $ProfileCustomOptions_String);
                                                     
                                if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) : ?>
                                <div>
                                    <label for="ProfileCustomLabel_min" style="text-align:right;"><?php _e("Min", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                    <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $ProfileCustomOptions_Min_value ?>" />
                                </div>
                                <div>
                                    <label for="ProfileCustomLabel_max" style="text-align:right;"><?php _e("Max", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                    <input class="min_max" type="text" name="<?php echo $field ?>_max" value="<?php echo $ProfileCustomOptions_Max_value ?>" />
                                </div>
                                <?php else : ?>
                                <div>
                                    <label for="ProfileCustomLabel_min" style="text-align:right;"><?php _e("Min", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                    <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $_SESSION[$field.'_min'] ?>" />
                                </div>
                                <div>
                                    <label for="ProfileCustomLabel_max" style="text-align:right;"><?php _e("Max", bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                    <input class="min_max" type="text" name="<?php echo $field ?>_max" value="<?php echo $_SESSION[$field.'_max'] ?>" />
                                </div>
                                <?php endif; ?>
                            </fieldset>
                            <?php break; ?>
                             
                            <?php case 3 : $data = explode('|', $options); ?>       
                            <div class="bbselect">
                                <div>
                                    <label><?php echo $data[0] ?></label>
                                </div>
                                <div>
                                    <select name="<?php echo $field ?>">
                                        <option value="">--</option>
                                        <?php foreach ($data as $val1) : if ($val1 != end($data) && $val1 != $data[0]) : ?>
                                        <option value="<?php echo $val1 ?>" <?php selected(isset($_SESSION[$field]) ? $_SESSION[$field] : false, $val1) ?>><?php echo $val1 ?></option>
                                        <?php endif; endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php break; ?>

                            <?php case 4 : ?>
                            <div class="bbtextarea">
                                <div>
                                    <textarea name="<?php echo $field ?>"><?php echo isset($_SESSION[$field]) ? $_SESSION[$field] : '' ?></textarea>
                                </div>
                            </div>
                            <?php break; ?>
                             
                            <?php case 5 : ?>
                            <fieldset class="bbcheckbox">
                            <?php
                                $array_customOptions_values = explode("|", $options);

                                if (!empty($array_customOptions_values)) : foreach ($array_customOptions_values as $val) : 
                                    /*
                                     * double check this if this is array and its index 0 is empty
                                     * then force set it to empty so it will not push through
                                     */
                                    if (isset($_SESSION[$field]) && is_array($_SESSION[$field])) { 
                                        if($_SESSION[$field][0] == ''){
                                            $_SESSION[$field] = '';
                                        }
                                    }
                                    
                                    if (isset($_SESSION[$field]) && $_SESSION[$field] != '') : $dataArr = explode(",", implode(",", explode("','", $_SESSION[$field])));

                                    if (in_array($val, $dataArr, true) && $val != '') : ?>
                                <label>
                                    <input type="checkbox" checked="checked" value="<?php echo $val ?>" name="<?php echo $field ?>[]" />&nbsp;
                                    <span><?php echo $val ?></span>
                                </label>
                                <br />
                                    <?php elseif ($val != '') : ?>
                                <label>
                                    <input type="checkbox" value="<?php echo $val ?>" name="<?php echo $field ?>[]" />&nbsp;
                                    <span><?php echo $val ?></span>
                                </label>
                                <br />
                                    <?php endif; ?>
                                    <?php else : ?>
                                    <?php if ($val != '') : ?>  
                                <label>
                                    <input type="checkbox" value="<?php echo $val ?>" name="<?php echo $field ?>[]" />&nbsp;
                                    <span><?php echo $val ?></span>
                                </label>
                                <br />
                                    <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <input type="hidden" value="" name="<?php echo $field ?>[]" />
                                <?php endif; ?>
                            </fieldset>
                            <?php break; ?>
                            
                            <?php case 6 : ?>
                            <fieldset class="bbradio">
                            <?php    
                                $array_customOptions_values = explode("|", $options);
                                   
                                foreach ($array_customOptions_values as $val) : 
                                    /*
                                     * double check this if this is array and its index 0 is empty
                                     * then force set it to empty so it will not push through
                                     */
                                    if (isset($_SESSION[$field]) && is_array($_SESSION[$field]) && $_SESSION[$field][0] == '') {
                                        $_SESSION[$field] = '';
                                    }
                                    
                                    if (isset($_SESSION[$field]) && $_SESSION[$field] != '') : 

                                    $dataArr = explode(",", implode(",", explode("','", $_SESSION[$field])));
                                ?>      
                                <label>
                                    <input type="radio" <?php checked(isset($dataArr) && in_array($val, $dataArr) && $val != '') ?> value="<?php echo $val ?>" name="<?php echo $field ?>[]" />
                                    <?php echo $val ?>
                                </label>
                                <br />
                                <?php endif; endforeach; ?>
                                <input type="hidden" value="" name="<?php echo $field ?>[]" /></div>                                          
                            </fieldset>                                       
                            <?php break; ?>
                            
                            <?php case 7 : 
                                 
                                list($min_val, $max_val) =  @explode(",", $_SESSION[$field]);

                                if ($title == 'Height' && $bb_agency_option_unittype == 1) : ?>
                                    <fieldset class="bbselect">
                                        <div>
                                            <label>Min</label>
                                            <select name="<?php echo $field ?>_min">
                                                <option value="">--</option>
                                    
                                            <?php for ($i = 12; $i <= 90; $i++) : // display height options ?>
                                                <option value="<?php echo $i ?>" <?php echo selected($min_val, $i) ?>><?php echo display_height($i) ?></option>
                                            <?php endfor; ?>
                                            </select>
                                        </div>
                                  
                                        <div>
                                            <label>Max</label>
                                            <select name="<?php echo $field ?>_max">
                                                <option value="">--</option>
                                            <?php for ($i = 12; $i <= 90; $i++) : // display height options ?>
                                                <option value="<?php echo $i ?>" <?php echo selected($max_val, $i) ?>><?php echo display_height($i) ?></option>
                                            <?php endfor; ?>
                                            </select>
                                        </div>
                                    </fieldset>

                                <?php else : ?>
                                    <fieldset class="bbtext">
                                    <?php // for other search ?>
                                        <div>
                                            <label for="<?php echo $field ?>_min">Min</label>
                                            <input value="<?php echo (!is_array($min_val) && $min_val != "Array" ? $min_val : "") ?>" class="stubby" type="text" name="<?php echo $field ?>[]" />
                                        </div>

                                         <div>
                                            <label for="<?php echo $field ?>_max">Max</label>
                                            <input value="<?php echo $max_val ?>" class="stubby" type="text" name="<?php echo $field ?>[]" />
                                        </div>
                                    </fieldset>
                                <?php endif; ?>
                            <?php break;

                            endswitch;    
    
                        endif; // end of if(in_array("$title", $cusFields)) ?>
                        </td>
                    </tr>
        
                        <?php endwhile; //end of while ($data1

                        // status filter
                        ?>
                    <tr>
                        <th scope="row"><?php _e("Status", bb_agency_TEXTDOMAIN) ?>:</th>
                        <td>
                            <select name="ProfileIsActive" id="ProfileIsActive">               
                                <option value="">--</option>
                                <?php
                                $value = isset($_SESSION['ProfileIsActive']) ? $_SESSION['ProfileIsActive'] : false;
                                $options = array(
                                    1 => __("Active", bb_agency_TEXTDOMAIN),
                                    4 => __("Not Visible", bb_agency_TEXTDOMAIN),
                                    0 => __("Inactive", bb_agency_TEXTDOMAIN),
                                    2 => __("Archived", bb_agency_TEXTDOMAIN)
                                );
                                foreach ($options as $id => $label) : ?>
                                <option value="<?php echo $id ?>" <?php selected($value, $id) ?>><?php echo $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </thead>
            </table>
            <p class="submit">
                <input type="submit" value="<?php _e("Search Profiles", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
                <input type="reset" onclick="redirectSearch();" name="reset" value="<?php _e("Reset Form", bb_agency_TEXTDOMAIN) ?>" class="button-secondary" />
            </p>
         <form>
    <div>
</div><!-- .container -->
</div>
 </div>
</div>