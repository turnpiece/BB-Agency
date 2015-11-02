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

    $bb_agency_option_unittype =  bb_agency_get_option('bb_agency_option_unittype');
    $bb_agency_option_persearch = (int)bb_agency_get_option('bb_agency_option_persearch');
    $bb_agency_option_agencyemail = (int)bb_agency_get_option('bb_agency_option_agencyemail');
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
        
        if (count($_GET['ProfileID']) > 0) {
            bb_agency_add_to_cart($_GET['ProfileID']);
        }
        $cartString = bb_agency_get_cart_string();
    
    } elseif ($action == "formEmpty") {  // Handle Form Empty 
        extract($_SESSION);
        foreach($_SESSION as $key=>$value) {
            if (substr($key, 0, 7) == "Profile") {
                unset($_SESSION[$key]);
            }
        }
    } elseif ($action == "cartEmpty") {  // Handle Cart Removal
        // Throw the baby out with the bathwater
        bb_agency_empty_cart();
        
    } elseif ($action == "cartRemove" && isset($_GET["RemoveID"])) {

        $cartString = bb_agency_get_cart_string();
        $cartRemoveID = $_GET["RemoveID"];
        $cartString = str_replace($_GET['RemoveID'] ."", "", $cartString);
        $cartString = bb_agency_cleanString($cartString);
        // Put it back in the array, and wash your hands
        $_SESSION['cartArray'] = array($cartString);
    
    } elseif ($action == "searchSave" && isset($_SESSION['cartArray'])) {
    
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

            if ($location = bb_agency_geocode($ProfileLocation)) {
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

        // Talent   
        if (isset($_GET['ProfileTalent']) && !empty($_GET['ProfileTalent'])){
            $ProfileTalent = $_GET['ProfileTalent'];
            $filter .= " AND profile.`ProfileTalent` like'%". $ProfileType ."%'";
            //$filter .= " AND Find_in_set (". $ProfileTalent .",profile.ProfileTalent) ";
        } else {
            $ProfileTalent = "";
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

        if (isset($_GET['ProfileAge_min']) && !empty($_GET['ProfileAge_min'])) {
            $age = str_replace('m', '', $_GET['ProfileAge_min']);
            $ym = (strpos($_GET['ProfileAge_min'], 'm') === false) ? 'YEAR' : 'MONTH';
            $filter .= " AND profile.`ProfileDateBirth` <= DATE_SUB(NOW(), INTERVAL $age $ym)";
        }
        
        if (isset($_GET['ProfileAge_max']) && !empty($_GET['ProfileAge_max'])){
            $age = str_replace('m', '', $_GET['ProfileAge_max']);
            $ym = (strpos($_GET['ProfileAge_max'], 'm') === false) ? 'YEAR' : 'MONTH';
            $filter .= " AND profile.`ProfileDateBirth` >= DATE_SUB(NOW(), INTERVAL $age $ym)";
        }

        if (bb_agency_SITETYPE == 'bumps') {
            // Due date
            if (isset($_GET['ProfileDateDue_min']) && !empty($_GET['ProfileDateDue_min'])){
                $ProfileDateDue_min = $_GET['ProfileDateDue_min'];
                $filter .= " AND profile.`ProfileDateDue` >= '$ProfileDateDue_min'";
            }
            
            if (isset($_GET['ProfileDateDue_max']) && !empty($_GET['ProfileDateDue_max'])){
                $ProfileDateDue_max = $_GET['ProfileDateDue_max'];
                $filter .= " AND profile.`ProfileDateDue` <= '$ProfileDateDue_max'";
            }
        }
        ?>
        <div class="boxblock-holder">
        <?php    
        // Filter Models Already in Cart
        if (bb_agency_have_cart()) {
            $cartString = bb_agency_get_cart_string();
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
                    if (is_array($val)){
                        if (count(array_filter($val)) > 1) {
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
            <?php if (bb_agency_have_cart()) : ?>
            <input type="hidden" name="forceCart" value="<?php bb_agency_the_cart_string() ?>" />
            <?php endif; ?>
            <table cellspacing="0" class="widefat fixed">
                <thead>
                    <tr class="thead">
                        <th class="manage-column column-cb check-column" id="cb" scope="col">
                            <input type="checkbox" />
                        </th>
                        <th class="column-ProfileID" id="ProfileID" scope="col">
                            <a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&sort=ProfileID&dir='. $sortDirection) ?>"><?php _e('ID', bb_agency_TEXTDOMAIN) ?></a>
                        </th>
                        <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e('Contact Information', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileStats" id="ProfileStats" scope="col"><?php _e('Private Details', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileDetails" id="ProfileDetails" scope="col"><?php _e('Public Details', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileImage" id="ProfileImage" scope="col"><?php _e('Headshot', bb_agency_TEXTDOMAIN) ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="thead">
                        <th class="manage-column column-cb check-column" id="cb" scope="col">
                            <input type="checkbox"/>
                        </th>
                        <th class="column-ProfileID" id="ProfileID" scope="col">
                            <a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&sort=ProfileID&dir='. $sortDirection) ?>"><?php _e('ID', bb_agency_TEXTDOMAIN) ?></a>
                        </th>
                        <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e('Contact Information', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileStats" id="ProfileStats" scope="col"><?php _e('Private Details', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileDetails" id="ProfileDetails" scope="col"><?php _e('Public Details', bb_agency_TEXTDOMAIN) ?></th>
                        <th class="column-ProfileImage" id="ProfileImage" scope="col"><?php _e('Headshot', bb_agency_TEXTDOMAIN) ?></th>
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
                           <input type="checkbox" <?php echo $isInactiveDisable ?> value="<?php echo $ProfileID ?>" class="administrator" id="ProfileID<?php echo $ProfileID ?>" name="ProfileID[]" />
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
                                <strong><?php _e('Country', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo $data['ProfileLocationCountry'] ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($data['distance'])) : ?>
                            <div>
                                <strong><?php _e('Distance', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo number_format((float)$data['distance'], 1, '.', '') ?> miles
                            </div>
                        <?php endif; ?>

                        <?php if (bb_agency_SITETYPE == 'bumps' && defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID && bb_agency_ismumtobe($data['ProfileType']) && !empty($data['ProfileDateDue'])) : ?>                             
                        <div>
                            <strong><?php _e('Due date', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo $data['ProfileDateDue'] ?></div>
                        </div>
                        <?php endif;

                        foreach (array(
                            __('Birth date', bb_agency_TEXTDOMAIN) => $data['ProfileDateBirth'],
                            __('Website', bb_agency_TEXTDOMAIN) => $data['ProfileContactWebsite'],
                            __('Phone Home', bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneHome'],
                            __('Phone Cell', bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneCell'],
                            __('Phone Work', bb_agency_TEXTDOMAIN) => $data['ProfileContactPhoneWork']
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
                                <strong><?php _e('Gender', bb_agency_TEXTDOMAIN) ?>:</strong> <?php echo bb_agency_getGenderTitle($data['ProfileGender']) ?bb_agency_getGenderTitle($data['ProfileGender']) : '--' ?>
                            </div>
                        <?php endif;

                        $resultsCustom = $wpdb->get_results("SELECT c.`ProfileCustomID`, c.`ProfileCustomTitle`, c.`ProfileCustomOrder`, c.`ProfileCustomView`, cx.`ProfileCustomValue` FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.`ProfileCustomID` = cx.`ProfileCustomID` WHERE c.`ProfileCustomView` = 0 AND cx.`ProfileID` = ". $ProfileID ." GROUP BY cx.`ProfileCustomID` ORDER BY c.`ProfileCustomOrder` ASC");
                        foreach  ($resultsCustom as $resultCustom) : if (!empty($resultCustom->ProfileCustomValue)) : ?>
                            <div>
                                <strong><?php echo $resultCustom->ProfileCustomTitle ?><span class="divider">:</span></strong> <?php echo $resultCustom->ProfileCustomID == 5 ? bb_agency_display_height($resultCustom->ProfileCustomValue) : $resultCustom->ProfileCustomValue ?>
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
                                    _e('No profiles found.', bb_agency_TEXTDOMAIN);
                                else
                                    _e('There aren\'t any profiles in the system yet.', bb_agency_TEXTDOMAIN);
                                ?>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
           
            <p>
                <input type="submit" name="CastingCart" value="<?php _e('Add to Casting Cart','bb_agency_search') ?>" class="button-primary" />
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?></a>
                <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print - Without Details" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?> - <?php _e('Without Details', bb_agency_TEXTDOMAIN) ?></a>
            </p>
        </form>
<?php      
    
    } // end of if action = search

    // display casting cart
    if ($action == "search" || $action == "cartAdd") { ?>

        <div class="boxblock-container left-half">
            <div class="boxblock">
                <h2><?php _e('Casting Cart', bb_agency_TEXTDOMAIN) ?></h2>
                <div class="casting-cart inner">
        <?php if (bb_agency_have_cart()) :
             
            $cartString = bb_agency_get_cart_string();
            $cartString = bb_agency_cleanString($cartString);

            $t_profile = table_agency_profile;
            $t_media = table_agency_profile_media;
            
            // Show Cart
            $query = <<<EOF
SELECT  profile.*, media.* 
FROM $t_profile profile
LEFT JOIN $t_media media 
ON profile.ProfileID = media.ProfileID 
AND media.ProfileMediaType = "Image" 
AND media.ProfileMediaPrimary = 1
WHERE profile.ProfileID IN ($cartString) 
ORDER BY profile.ProfileContactNameFirst ASC
EOF;

            $results = mysql_query($query) or die('Get casting cart database query failed - '.mysql_error());
            $count = mysql_num_rows($results);
        ?>
            <div class="empty-cart">
                <a href="<?php echo admin_url('admin.php?page='. $_GET['page'] .'&action=cartEmpty') ?>" class="button-secondary empty"><?php _e('Empty Cart', bb_agency_TEXTDOMAIN) ?></a>
            </div>
            <div class="contents-summary"><?php _e('Currently', bb_agency_TEXTDOMAIN) ?> <strong><?php echo $count ?></strong> <?php _e('in cart', bb_agency_TEXTDOMAIN) ?></div>
            <div class="profiles">
            <?php
            if ($count == 1) {
                $cartAction = "cartEmpty";
            } elseif ($count < 1) {
                _e('There are currently no profiles in the casting cart', bb_agency_TEXTDOMAIN) .'.';
                $cartAction = "cartEmpty";
            } else {
                $cartAction = "cartRemove";
            }
            while ($data = mysql_fetch_array($results)) : $ProfileDateUpdated = $data['ProfileDateUpdated']; ?>
                <div class="profile">
                    <h3><?php echo stripslashes($data['ProfileContactDisplay']) ?></h3>
                    <?php if ($data['ProfileMediaURL']) : ?>
                    <div class="image">
                        <img src="<?php echo bb_agency_UPLOADDIR . $data['ProfileGallery'] .'/'. $data['ProfileMediaURL'] ?>">
                    </div>
                    <?php endif; ?>
                    <div class="details">
                    <?php if (!empty($data['ProfileDateBirth']) && substr($data['ProfileDateBirth'], 0, 4) !== '0000') : ?>
                        <strong>Age:</strong> <?php echo bb_agency_get_age($data['ProfileDateBirth']) ?><br />
                    <?php endif; ?>

                    <?php if (bb_agency_SITETYPE == 'bumps' && defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID && bb_agency_ismumtobe($data['ProfileType']) && !empty($data['ProfileDateDue'])) : ?>
                        <strong>Due date:</strong> <?php echo bb_agency_get_due_date($data['ProfileDateDue']) ?><br />
                    <?php endif; ?>
                    </div>
                    <div class="actions">
                        <a href="<?php echo admin_url('admin.php?page='. $_GET['page'] .'&action='. $cartAction .'&RemoveID='. $data['ProfileID']) ?>" title="<?php _e('Remove from Cart', bb_agency_TEXTDOMAIN) ?>">
                            <img class="remove" src="<?php echo bb_agency_BASEDIR ?>style/remove.png" alt="<?php _e('Remove from Cart', bb_agency_TEXTDOMAIN) ?>" />
                        </a>
                    </div>
                    <div style="clear: both; "></div>
                </div>
            <?php endwhile; mysql_free_result($results); ?>
                <div style="clear: both;"></div>
            </div>
            
         <?php else : ?>
            <p>There are no profiles in the casting cart.</p>
         <?php endif; ?>
        </div>
    </div>
    <?php if (isset($cartAction) && ($cartAction == "cartEmpty" || $cartAction == "cartRemove")) : // empty or remove casting cart ?>
    <a name="compose">&nbsp;</a> 
    <div class="boxblock">
        <h3><?php _e('Cart Actions', bb_agency_TEXTDOMAIN) ?></h3>
        <div class="inner">
            <a href="<?php echo admin_url('admin.php?page=bb_agency_searchsaved&action=searchSave') ?>" title="<?php _e('Save Search & Email', bb_agency_TEXTDOMAIN) ?>" class="button-primary"><?php _e('Save Search & Email', bb_agency_TEXTDOMAIN) ?></a>
            <a href="<?php echo admin_url('admin.php?page=bb_agency_search&action=massEmail#compose') ?>" title="<?php _e('Mass Email', bb_agency_TEXTDOMAIN) ?>" class="button-primary"><?php _e('Mass Email', bb_agency_TEXTDOMAIN) ?></a>
            <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=castingCart&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?></a>
            <a href="#" onClick="window.open('<?php bloginfo('url') ?>/profile-print/?action=castingCart&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')" title="Quick Print - Without Details" class="button-primary"><?php _e('Quick Print', bb_agency_TEXTDOMAIN) ?> - <?php _e('Without Details', bb_agency_TEXTDOMAIN) ?></a>
        </div>
    </div>
    <?php endif; // Is Cart Empty 

    }
    
    $isSent = false;
    
    // send a bulk email
    if (isset($_POST["SendEmail"])){
        bb_agency_send_email();
    }

    // send bulk email
    if ($action == "massEmail") :

        // Filter Models Already in Cart
        if (bb_agency_have_cart()) {
            $cartString = bb_agency_get_cart_string();
            $cartQuery = " AND profile.ProfileContactEmail !='' AND profile.ProfileID IN (". $cartString .")";
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
        $bb_agency_value_agencyname = bb_agency_get_option('bb_agency_option_agencyname');
        $bb_agency_value_agencyemail = bb_agency_get_option('bb_agency_option_agencyemail');
        ?>
        <form method="post">
            <div class="boxblock">
                <h3><?php _e('Compose Email', bb_agency_TEXTDOMAIN) ?></h3>
                <div class="inner">
                <?php if ($isSent) : ?>
                <div id="message" class="updated">
                    <p>Email messages successfully sent!</p>
                </div>
                <?php endif; ?>
                <strong>Recipient:</strong><br/>
                <textarea name="MassEmailRecipient" style="width:100%;"><?php echo $bb_agency_value_agencyemail ?></textarea><br/>
                <strong>Bcc:</strong><br/>
                <textarea name="MassEmailBccRecipient" style="width:100%;" placeholder="Enter Comma seperated values"><?php echo $recipient ?></textarea><br/>
                <strong>Subject:</strong> <br/>
                <input type="text" name="MassEmailSubject" style="width:100%"/>
                <br/>
                <?php
                // set content
                $content = "This message was sent to you by ".$bb_agency_value_agencyname." ".network_site_url( '/' )."<br /> [link-place-holder]";
                $editor_id = 'MassEmailMessage';
                wp_editor( $content, $editor_id, array("wpautop" => false, "tinymce" => true) );

                ?>
                <input type="submit" value="<?php _e('Send Email', bb_agency_TEXTDOMAIN) ?>" name="SendEmail" class="button-primary" />
                </div>
            </div>
        </form>
    <?php endif; // end of mass email ?>
    </div><!-- .container -->
<?php 

}
?>
    <div class="boxblock-container left-half">
        <div class="boxblock">
            <h3><?php _e('Advanced Search', bb_agency_TEXTDOMAIN) ?></h3>
            <div class="inner">
            <form method="GET" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
                <input type="hidden" name="page" id="page" value="bb_agency_search" />
                <input type="hidden" name="action" value="search" />
                <table cellspacing="0" class="widefat fixed">
                    <thead>
                        <tr>
                            <th scope="row"><?php _e('Name', bb_agency_TEXTDOMAIN) ?>:</th>
                            <td>
                                <input type="text" id="ProfileContactName" name="ProfileContactName" value="<?php echo isset($ProfileContactName) ? $ProfileContactName : '' ?>" />               
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Classification', bb_agency_TEXTDOMAIN) ?>:</th>
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
                                     
                                    $DataTypes = $wpdb->get_results("SELECT * FROM ". table_agency_data_type);
                                    
                                    foreach ($DataType as $type) : if (!in_array(strtolower($type->DataTypeTitle), $filter)) : ?>
                                    <option value="<?php echo $type->DataTypeID ?>" <?php selected(isset($_SESSION['ProfileType']) ? $_SESSION['ProfileType'] : false, $type->DataTypeID) ?>><?php echo $type->DataTypeTitle ?></option>
                                    <?php endif; endforeach; ?>
                                    
                                </select>
                            </td>
                        </tr>
                        <?php $talents = $wpdb->get_results("SELECT * FROM ". table_agency_data_talent); if (!empty($talents)) : ?>
                        <tr>
                            <th scope="row"><?php _e('Talent', bb_agency_TEXTDOMAIN) ?>:</th>
                            <td>
                                <select name="ProfileTalent" id="ProfileTalent">               
                                    <option value="">--</option>
                                    
                                    <?php foreach ($talents as $talent) : ?>
                                    <option value="<?php echo $talent->DataTalentID ?>" <?php selected(isset($_SESSION['ProfileTalent']) ? $_SESSION['ProfileTalent'] : false, $talent->DataTalentID) ?>><?php echo $talent->DataTalentTitle ?></option>
                                    <?php endforeach; ?>
                                    
                                </select>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th scope="row"><?php _e('Gender', bb_agency_TEXTDOMAIN) ?>:</th>
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
                            <th scope="row"><?php _e('Age', bb_agency_TEXTDOMAIN) ?>:</th>
                            <td>
                                <fieldset>
                                    <div>
                                        <?php echo bb_agency_age_dropdown('ProfileAge_min') ?>
                                    </div>
                                    <div>
                                        <?php echo bb_agency_age_dropdown('ProfileAge_max') ?>
                                    </div>
                                </fieldset>
                            </td>
                        </tr>
                        <?php if (defined('bb_agency_MUMSTOBE_ID') && bb_agency_MUMSTOBE_ID) : ?>
                        <tr>
                            <th scope="row"><?php _e('Due date', bb_agency_TEXTDOMAIN) ?>:</th>
                            <td>
                                <fieldset>
                                    <div>
                                        <label for="ProfileDateDue_min"><?php _e('From', bb_agency_TEXTDOMAIN) ?></label>
                                        <input type="text" class="min_max bbdatepicker" id="ProfileDateDue_min" name="ProfileDateDue_min" />
                                    </div>

                                    <div>
                                        <label for="ProfileDateDue_max"><?php _e('To', bb_agency_TEXTDOMAIN) ?></label>
                                        <input type="text" class="min_max bbdatepicker" id="ProfileDateDue_max" name="ProfileDateDue_max" />
                                    </div>
                                </fieldset>
                            </td>
                        </tr> 
                        <?php endif; ?>
                        <tr>
                            <th scope="row"><?php _e('Town', bb_agency_TEXTDOMAIN) ?>:</th>
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
                            <th scope="row"><?php _e('County', bb_agency_TEXTDOMAIN) ?>:</th>
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
                            <th scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?>:</th>
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

                                if ($title == 'Height') : $limit = (bb_agency_SITETYPE == 'children' ? 60 : 90); ?>
                                    <fieldset class="bbselect">
                                        <div>
                                            <label>Min</label>
                                            <select name="<?php echo $field ?>_min">
                                                <option value="">--</option>
                                            <?php for ($i = 12; $i <= $limit; $i++) : // display height options ?>
                                                <option value="<?php echo $i ?>" <?php selected(isset($_GET[$field.'_min']) ? $_GET[$field.'_min'] : false, $i) ?>><?php echo bb_agency_display_height($i) ?></option>
                                            <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label>Max</label>
                                            <select name="<?php echo $field ?>_max">
                                                <option value="">--</option>
                                            <?php for ($i = 12; $i <= $limit; $i++) : // display height options ?>
                                                <option value="<?php echo $i ?>" <?php selected(isset($_GET[$field.'_max']) ? $_GET[$field.'_max'] : false, $i) ?>><?php echo bb_agency_display_height($i) ?></option>
                                            <?php endfor; ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                <?php else : ?>
                                    <fieldset class="bbtext">
                                        <div>
                                            <label for="ProfileCustomLabel_min"><?php _e('From', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                            <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $ProfileCustomOptions_Min_value ?>" />
                                        </div>
                                        <div>
                                            <label for="ProfileCustomLabel_max"><?php _e('To', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
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
                                        <label for="ProfileCustomLabel_min" style="text-align:right;"><?php _e('From', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                        <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $ProfileCustomOptions_Min_value ?>" />
                                    </div>
                                    <div>
                                        <label for="ProfileCustomLabel_max" style="text-align:right;"><?php _e('To', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                        <input class="min_max" type="text" name="<?php echo $field ?>_max" value="<?php echo $ProfileCustomOptions_Max_value ?>" />
                                    </div>
                                    <?php else : ?>
                                    <div>
                                        <label for="ProfileCustomLabel_min" style="text-align:right;"><?php _e('From', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
                                        <input class="min_max" type="text" name="<?php echo $field ?>_min" value="<?php echo $_SESSION[$field.'_min'] ?>" />
                                    </div>
                                    <div>
                                        <label for="ProfileCustomLabel_max" style="text-align:right;"><?php _e('To', bb_agency_TEXTDOMAIN) ?>&nbsp;&nbsp;</label>
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
                                                    <option value="<?php echo $i ?>" <?php echo selected($min_val, $i) ?>><?php echo bb_agency_display_height($i) ?></option>
                                                <?php endfor; ?>
                                                </select>
                                            </div>
                                      
                                            <div>
                                                <label>Max</label>
                                                <select name="<?php echo $field ?>_max">
                                                    <option value="">--</option>
                                                <?php for ($i = 12; $i <= 90; $i++) : // display height options ?>
                                                    <option value="<?php echo $i ?>" <?php echo selected($max_val, $i) ?>><?php echo bb_agency_display_height($i) ?></option>
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
                            <th scope="row"><?php _e('Status', bb_agency_TEXTDOMAIN) ?>:</th>
                            <td>
                                <select name="ProfileIsActive" id="ProfileIsActive">               
                                    <option value="">--</option>
                                    <?php
                                    $value = isset($_SESSION['ProfileIsActive']) ? $_SESSION['ProfileIsActive'] : false;
                                    $options = array(
                                        1 => __('Active', bb_agency_TEXTDOMAIN),
                                        4 => __('Not Visible', bb_agency_TEXTDOMAIN),
                                        0 => __('Inactive', bb_agency_TEXTDOMAIN),
                                        2 => __('Archived', bb_agency_TEXTDOMAIN)
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
                    <input type="submit" value="<?php _e('Search Profiles', bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
                    <input type="reset" onclick="redirectSearch();" name="reset" value="<?php _e('Reset Form', bb_agency_TEXTDOMAIN) ?>" class="button-secondary" />
                </p>
             <form>
        <div>
    </div><!-- .container -->
</div>
 </div>
</div>