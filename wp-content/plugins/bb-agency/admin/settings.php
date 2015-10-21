<div class="wrap">        
<?php 
    // Include Admin Menu
    include ("admin-menu.php"); 

    global $wpdb;

// *************************************************************************************************** //
// Top Menu

    $menu = array(
        0 => __("Overview", bb_agency_TEXTDOMAIN),
        1 => __("Features", bb_agency_TEXTDOMAIN),
        2 => __("Locations", bb_agency_TEXTDOMAIN),
//        10 => __("Style", bb_agency_TEXTDOMAIN),
//        5 => __("Gender", bb_agency_TEXTDOMAIN),
        6 => __("Profile Types", bb_agency_TEXTDOMAIN),
        7 => __("Custom Fields", bb_agency_TEXTDOMAIN),
//        8 => __("Media Categories", bb_agency_TEXTDOMAIN),
        11 => __("Interactive Settings", bb_agency_TEXTDOMAIN),
    );

    $ConfigID = isset($_REQUEST['ConfigID']) ? $_REQUEST['ConfigID'] : 0;
    $page = $_GET['page'];
?>
<p>
<?php foreach ($menu as $id => $label) : ?>
    <a class="button-<?php echo $ConfigID == $id ? 'primary' : 'secondary' ?>" href="<?php echo admin_url("admin.php?page={$page}&amp;ConfigID={$id}") ?>"><?php echo $label ?></a>
<?php endforeach; ?>
</p>
<?php

if (!empty($_REQUEST['action']) ) {
	if ($_REQUEST['action'] == 'douninstall') {
		bb_agency_uninstall();
	}
}

<h2> &raquo; ";
switch ($ConfigID) {
    case 1 :
    echo "Features";
    break;

    case 2 :
    echo "Locations";
    break;

    case 10 :
    echo "Style";
    break;

    case 5 :
    echo "Gender";
    break;

    case 6 :
    echo "Profile Categories";
    break;

    case 7 :
    echo "Custom Fields";
    break;

    case 8 :
    echo "Media Categories";
    break;

    case 11 :
    echo "Interactive Settings";
    break;
}        
</h2>";

if ($ConfigID == 0) {
	
// *************************************************************************************************** //
// Overview Page
	// Core Settings
    ?>
    <div class="boxlinkgroup">
        <h2><?php _e("Configuration", bb_agency_TEXTDOMAIN) ?></h2>
        <p><?php _e("The following settings modify the core BB Agency settings.", bb_agency_TEXTDOMAIN) ?></p>
        <div class="boxlink">
            <h3><?php _e("Features", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=1=" title=="<?php _e("Settings", bb_agency_TEXTDOMAIN) ?>"><?php _e("Settings", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Access this area to manage all of the core settings including layout types, privacy settings and more", bb_agency_TEXTDOMAIN) ?></p>
        </div>
        <div class="boxlink">
            <h3><?php _e("Style", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=10=" title=="<?php _e("Style", bb_agency_TEXTDOMAIN) ?>"><?php _e("Style", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Manage the stylesheet (CSS) controlling the category and profile layouts", bb_agency_TEXTDOMAIN) ?></p>
        </div>
    </div>
    <hr />

	<?php if (function_exists('bb_agencyinteract_settings')) : ?>
    <div class="boxlinkgroup">
        <h2><?php _e("Interactive Settings", bb_agency_TEXTDOMAIN) ?></h2>
        <p><?php _e("These settings modify the behavior of the RB Agency Interactive plugin.", bb_agency_TEXTDOMAIN) ?></p>
        <div class="boxlink">
            <h3><?php _e("Interactive Settings", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=11=" title=="<?php _e("Interactive Settings", bb_agency_TEXTDOMAIN) ?>"><?php _e("Settings", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Access this area to manage all of the core settings including layout types, privacy settings and more", bb_agency_TEXTDOMAIN) ?></p>
        </div>
 
        <div class="boxlink">
            <h3><?php _e("Subscription Rates", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=12=" title=="<?php _e("Subscription Rates", bb_agency_TEXTDOMAIN) ?>"><?php _e("Subscription Rates", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Manage the subscription rate tiers and descriptions", bb_agency_TEXTDOMAIN) ?></p>
        </div>
 
    </div>
    <hr />
	<?php endif; ?>

	<?php // Drop Down Fields ?>
    <div class="boxlinkgroup">
        <h2><?php _e("Customize Profile Fields", bb_agency_TEXTDOMAIN) ?></h2>
        <p><?php _e("You have full control over all drop downs and ability to add new custom fields of your own.", bb_agency_TEXTDOMAIN) ?></p>
        <div class="boxlink">
            <h3><?php _e("Profile Categories", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=6=" title=="<?php _e("Profile Categories", bb_agency_TEXTDOMAIN) ?>"><?php _e("Profile Categories", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Choose custom category types to classify profiles", bb_agency_TEXTDOMAIN) ?></p>
        </div>
        <div class="boxlink">
            <h3><?php _e("Custom Fields", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=7=" title=="<?php _e("Custom Fields", bb_agency_TEXTDOMAIN) ?>"><?php _e("Custom Fields", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Add public and private custom fields", bb_agency_TEXTDOMAIN) ?></p>
        </div>
        <div class="boxlink">
            <h3><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></h3>
            <a class="button-primary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=5=" title=="<?php _e("Gender", bb_agency_TEXTDOMAIN) ?>"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></a><br />
            <p><?php _e("Manage preset Gender choices", bb_agency_TEXTDOMAIN) ?></p>
        </div>
    </div>
    <hr />

	<?php // Uninstall ?>
    <div class="boxlinkgroup">
        <h2><?php _e("Uninstall", bb_agency_TEXTDOMAIN) ?></h2>
        <p><?php _e("Uninstall BB Agency software and completely remove all data", bb_agency_TEXTDOMAIN) ?></p>
        <div class="boxlink">
            <a class="button-secondary=" href="?page=<?php echo $_GET["page"] ?>&amp;ConfigID=99=" title=="<?php _e("Uninstall", bb_agency_TEXTDOMAIN) ?>"><?php _e("Uninstall", bb_agency_TEXTDOMAIN) ?></a><br />
        </div>
    </div>
<?php
}
elseif ($ConfigID == 1) {
// *************************************************************************************************** //
// Manage Settings
    if (isset($_REQUEST['action'])) {
        // process submitted form
        $options = $_REQUEST['bb_agency_options'];
        foreach (
            array(
                'bb_agency_option_agencyname',
                'bb_agency_option_agencyemail',
                'bb_agency_option_locationcountry',
                'bb_agency_option_profiledeletion',
                'bb_agency_option_profilelist_perpage',
                'bb_agency_option_persearch',
                'bb_agency_option_showcontactpage',
                'bb_agency_option_profilelist_favorite',
                'bb_agency_option_profilelist_castingcart',
                'bb_agency_option_profilelist_printpdf',
                'bb_agency_option_privacy',
                'bb_agency_option_pregnant',
                'bb_agency_option_layoutprofile'
            ) as $key) {
            $value = isset($options[$key]) ? $options[$key] : 0;
            bb_agency_update_option($key, $value);
        }

        // reload options
        global $bb_options;
        $bb_options = bb_agency_reload_options();
    }

    // load form
    include(dirname(__FILE__).'/settings/general.php');

}	
elseif ($ConfigID == 2) {
    $table = table_agency_profile;
    $addressFields = array(                    
        'ProfileLocationStreet',
        'ProfileLocationCity',
        'ProfileLocationState',
        'ProfileLocationZip',
        'ProfileLocationCountry'
    );
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'geocode') {
        // get profile ids
        if (!empty($_REQUEST['ProfileID'])) : ?>
            <ul>
            <?php foreach ($_REQUEST['ProfileID'] as $id) : if ($id) : ?> 
                <li><?php 
                $result = mysql_query("SELECT * FROM $table WHERE `ProfileID` = $id");
                if ($result && mysql_num_rows($result)) {
                    $record = mysql_fetch_array($result);
                    echo '<strong>'.$record['ProfileContactDisplay'].'</strong>: ';
                    // get address to geocode
                    $address = array();
                    foreach ($addressFields as $field) {
                        if ($record[$field] != '')
                            $address[] = $record[$field];
                    }
                    if (empty($address)) {
                        echo 'No address!';
                    } else {
                        $strAddress = implode(', ', $address);
                        echo $strAddress .' => ';
                        if ($location = bb_agency_geocode($strAddress)) {
                            $set = '`ProfileLocationLatitude` = "'.$location['lat'].'", `ProfileLocationLongitude` = "'.$location['lng'].'", `ProfileDateUpdated` = NOW()';
                            $sql = "UPDATE $table SET $set WHERE `ProfileID` = $id";
                            echo '('.$location['lat'].', '.$location['lng'].')';
                            $wpdb->query($sql);
                            if (mysql_error())
                                wp_die("Database Error: $sql - ".mysql_error());
                        } else {
                            echo 'Failed to geocode'; 
                        }
                    }
                } else {
                    echo "failed to get profile $id";
                }
                ?></li>
            <?php endif; endforeach; ?> 
            </ul>
        <?php endif;        
    }

    // get ungeocoded profiles
    $query = <<<EOF
SELECT *, CONCAT(`ProfileContactNameFirst`,' ',`ProfileContactNameLast`) AS `ProfileContactName` 
FROM $table
WHERE (`ProfileLocationLatitude` IS NULL OR `ProfileLocationLongitude` IS NULL)
AND ProfileIsActive > 0
AND (`ProfileLocationZip` <> '' OR `ProfileLocationCity` <> '' OR `ProfileLocationStreet` <> '')
ORDER BY `ProfileDateCreated` ASC
LIMIT 100
EOF;

    $results = mysql_query($query);
    $count = mysql_num_rows($results);

    ?>
    <form method="POST" action="">
        <p>The following <?php echo $count ?> profiles have not been geocoded.</p>
        <input type="submit" class="button-primary" value="<?php _e('Geocode Locations', bb_agency_TEXTDOMAIN) ?>" />
        <br />
        <table cellspacing="0" class="widefat fixed">
            <thead>
                <tr class="thead">
                    <th class="manage-column column-cb check-column" id="cb" scope="col">
                        <input type="checkbox"/>
                    </th>
                    <th class="column-ProfileID" id="ProfileID" scope="col"><?php _e("ID", bb_agency_TEXTDOMAIN) ?></th>
                    <th class="column-ProfileName" id="ProfileName" scope="col"><?php _e("Name", bb_agency_TEXTDOMAIN) ?></th>
                    <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e("Location", bb_agency_TEXTDOMAIN) ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr class="thead">
                    <th class="manage-column column-cb check-column" id="cb" scope="col">
                        <input type="checkbox"/>
                    </th>
                    <th class="column-ProfileID" id="ProfileID" scope="col"><?php _e("ID", bb_agency_TEXTDOMAIN) ?></th>
                    <th class="column-ProfileName" id="ProfileName" scope="col"><?php _e("Name", bb_agency_TEXTDOMAIN) ?></th>
                    <th class="column-ProfileContact" id="ProfileContact" scope="col"><?php _e("Location", bb_agency_TEXTDOMAIN) ?></th>
                </tr>
            </tfoot>
            <tbody>
            <?php 
                while ($record = mysql_fetch_array($results)) : 
                    $isInactive = $record['ProfileIsActive'] == 0; 
                    $isInactiveDisable = $record['ProfileIsActive'] ? '' : 'disabled="disabled"'; 
                    $pid = $record['ProfileID']; ?>
                <tr class="<?php echo $isInactive ? 'inactive' : 'active' ?>">
                    <th class="check-column" scope="row" >
                       <input type="checkbox" <?php echo $isInactiveDisable ?> value="<?php echo $pid ?>" class="administrator" id="ProfileID<?php echo $pid ?>" name="ProfileID[]" />
                    </th>
                    <td class="ProfileID column-ProfileID"><?php echo $pid ?></td>
                    <td><?php echo $record['ProfileContactDisplay'] ?></td>
                    <td><?php echo $record['ProfileLocationStreet'].', '.$record['ProfileLocationCity'].', '.$record['ProfileLocationState'],', '.$record['ProfileLocationZip'].', '.$record['ProfileLocationCountry'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <input type="hidden" name="ConfigID" value="2" />
        <input type="hidden" name="action" value="geocode" />
        <input type="submit" class="button-primary" value="<?php _e('Geocode Locations', bb_agency_TEXTDOMAIN) ?>" />
    </form>
    <?php
}
elseif ($ConfigID == 11) {
// *************************************************************************************************** //
// Manage Settings
    ?>
    <h3><?php _e("Interactive Settings", bb_agency_TEXTDOMAIN) ?></h3>
	<form method="post" action="options.php=">
        <?php
    		settings_fields( 'bb-agencyinteract-settings-group' ); 
    		$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
    		// Facebook Connect integration
    		$bb_agencyinteract_option_fb_registerallow = $bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_registerallow'];
    	    if (empty($bb_agencyinteract_option_fb_registerallow)) { 
                $bb_agencyinteract_option_fb_registerallow = "1"; 
            }
    	?>
		<table class="form-table=">
            <tr valign="top">
                <th scope="row"><?php _e('Database Version', bb_agency_TEXTDOMAIN) ?></th>
                <td><input name="=" value="<?php echo  bb_agencyinteract_VERSION  ?>" disabled /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="checkbox" name="bb_agencyinteract_options[bb_agencyinteract_option_profilemanage_sidebar]=" value="1" <?php echo checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'], 1,false)."/> <?php _e("Show Sidebar on Member Management/Login Pages", bb_agency_TEXTDOMAIN) ?><br />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input type="checkbox" name="bb_agencyinteract_options[bb_agencyinteract_option_profilemanage_toolbar]=" value="1" <?php echo checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_toolbar'], 1,false)."/> <?php _e("Hide Toolbar on All Pages", bb_agency_TEXTDOMAIN) ?><br />
                </td>
            </tr>
		
            <tr valign="top">
                <th scope="row" colspan=="2"><h3><?php _e('Registration Process', bb_agency_TEXTDOMAIN) ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Show User Registration when creating Profiles', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <select name="bb_agencyinteract_options[bb_agencyinteract_option_useraccountcreation]=">
                        <option value="0" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_useraccountcreation'], 0,false) ?>> <?php _e("Yes, show username and password fields", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="1" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_useraccountcreation'], 1,false) ?>> <?php _e("No, do not show username and password fields", bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('New Profile Registration', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="checkbox" name="bb_agencyinteract_options[bb_agencyinteract_option_registerallow]=" value="1" <?php echo checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallow'], 1,false) ?> /> Users may register profiles (uncheck to prevent self registration)<br />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable registration of Agent/Producer', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <select name="bb_agencyinteract_options[bb_agencyinteract_option_registerallowAgentProducer]=">
                    <option value="1" <?php ($bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallowAgentProducer'] == 1 ? 'selected="selected"' : '') ?> > <?php _e("Show", bb_agency_TEXTDOMAIN) ?></option>
                    <option value="0" <?php echo ($bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallowAgentProducer'] == 0 ? 'selected="selected"' : '') ?> > <?php _e("Hide", bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Email Confirmation', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <select name="bb_agencyinteract_options[bb_agencyinteract_option_registerconfirm]=">
                        <option value="0" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerconfirm'], 0,false) ?> > <?php _e("Password Auto-Generated (sent via email)", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="1" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerconfirm'], 1,false) ?> > <?php _e("Password Self-Generated", bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('New Profile Approval', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <select name="bb_agencyinteract_options[bb_agency_option_useraccountcreation]=">
                        <option value="0" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerapproval'], 0,false) ?> > <?php _e("Manually Approved", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="1" <?php echo selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerapproval'], 1,false) ?> > <?php _e("Automatically Approved", bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan=="2"><h3><?php _e('Membership Subscription', bb_agency_TEXTDOMAIN) ?></h3></th>
    		</tr>
    		<tr valign="top">
                <th scope="row"><?php _e('Notifications', bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="checkbox" name="bb_agencyinteract_options[bb_agencyinteract_option_subscribeupsell]=" value="1" <?php echo checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribeupsell'], 1,false) ?> /> Display Upsell Messages for Subscription)<br />
                </td>
    		</tr>
    		<tr valign="top">
                <th scope="row"><?php _e('Embed Overview Page ID', bb_agency_TEXTDOMAIN) ?></th>
                <td><input name="bb_agencyinteract_options[bb_agencyinteract_option_overviewpagedetails]=" value="<?php echo $bb_agencyinteract_options_arr['bb_agencyinteract_option_overviewpagedetails'] ?>" /></td>
    		</tr>
    		<tr valign="top">
                <th scope="row"><?php _e('Embed Registration Page ID', bb_agency_TEXTDOMAIN) ?></th>
                <td><input name="bb_agencyinteract_options[bb_agencyinteract_option_subscribepagedetails]" value="<?php echo $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepagedetails'] ?>" /></td>
    		</tr>
    		<tr valign="top">
        		<th scope="row"><?php _e('PayPal Email Address', bb_agency_TEXTDOMAIN) ?></th>
        		<td><input name="bb_agencyinteract_options[bb_agencyinteract_option_subscribepaypalemail]" value="<?php echo $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepaypalemail'] ?>" /></td>
    		</tr>
		</table>
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		
	</form>
    <?php
}	 // End	
// *************************************************************************************************** //
// Setup Custom Fields
elseif ($ConfigID == 12) {
	
    /** Identify Labels **/
    define("LabelPlural", __("Subscription Tiers", bb_agency_TEXTDOMAIN));
    define("LabelSingular", __("Subscription Tier", bb_agency_TEXTDOMAIN));
    /* Initial Registration [RESPOND TO POST] ***********/ 
    if ( isset($_POST['action']) ) {
        $SubscriptionRateID 	= $_POST['SubscriptionRateID'];
		$SubscriptionRateTitle 	= $_POST['SubscriptionRateTitle'];
		$SubscriptionRateType 	= $_POST['SubscriptionRateType'];
		$SubscriptionRateText 	= $_POST['SubscriptionRateText'];
		$SubscriptionRatePrice 	= $_POST['SubscriptionRatePrice'];
		$SubscriptionRateTerm 	= $_POST['SubscriptionRateTerm'];
		// Error checking
		$error = "";
		$have_error = false;
		if(trim($SubscriptionRateTitle) == ""){
			$error .= '<b><i>'. __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) .'</i></b><br>';
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
    	
    		// Add
    		case 'addRecord':
    			if($have_error){
    				?><div id="message" class="error"><p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p><?php echo $error ?></p></div>"); 
    			} else {
    		
    				// Create Record
    				$insert = "INSERT INTO " . table_agencyinteract_subscription_rates . " (SubscriptionRateTitle,SubscriptionRateType,SubscriptionRateText,SubscriptionRatePrice,SubscriptionRateTerm) VALUES ('" . $wpdb->escape($SubscriptionRateTitle) . "','" . $wpdb->escape($SubscriptionRateType) . "','" . $wpdb->escape($SubscriptionRateText) . "','" . $wpdb->escape($SubscriptionRatePrice) . "','" . $wpdb->escape($SubscriptionRateTerm) . "')";
    				$results = $wpdb->query($insert);
    				$lastid = $wpdb->insert_id;
    				
    				?><div id="message" class="updated"><p><?php printf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, "<a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."=">") .".</a></p><p><?php echo $error ?></p></div>"); 
    			}
                break;
	
    		// Manage
    		case 'editRecord':
    			if($have_error){
    				?><div id="message" class="error"><p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p><?php echo $error ?></p></div>"); 
    			} else {
    				$update = "UPDATE " . table_agencyinteract_subscription_rates . " 
    							SET 
    								SubscriptionRateTitle='" . $wpdb->escape($SubscriptionRateTitle) . "',
    								SubscriptionRateType='" . $wpdb->escape($SubscriptionRateType) . "',
    								SubscriptionRateText='" . $wpdb->escape($SubscriptionRateText) . "',
    								SubscriptionRatePrice='" . $wpdb->escape($SubscriptionRatePrice) . "',
    								SubscriptionRateTerm='" . $wpdb->escape($SubscriptionRateTerm) . "' 
    							WHERE SubscriptionRateID='$SubscriptionRateID'";
    				$updated = $wpdb->query($update);
    				?><div id="message" class="updated"><p><?php printf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ."!</p><p><?php echo $error ?></p></div>"); 
    			}
                break;

    		// Delete bulk
    		case 'deleteRecord':
    			foreach($_POST as $SubscriptionRateID) {
        			if (is_numeric($SubscriptionRateID)) {
        				// Verify Record
        				$queryDelete = "SELECT SubscriptionRateID, SubscriptionRateTitle FROM ". table_agencyinteract_subscription_rates ." WHERE SubscriptionRateID =  ="<?php echo  $SubscriptionRateID  ?>"";
        				$resultsDelete = mysql_query($queryDelete);
        				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
        			
        					// Remove Record
        					$delete = "DELETE FROM " . table_agencyinteract_subscription_rates . " WHERE SubscriptionRateID = ="<?php echo  $SubscriptionRateID  ?>"";
        					$results = $wpdb->query($delete);
        					
        					?>
                            <div id="message" class="updated"><p><?php _e(LabelSingular ." <strong>". $dataDelete['SubscriptionRateTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p></div>
        					<?php
        				} // while
        			} // it was numeric
    			} // for each
                break;
		
		} // Switch
		
    } // Action Post
    elseif ($_GET['action'] == "deleteRecord") {
	
        $SubscriptionRateID = $_GET['SubscriptionRateID'];
        if (is_numeric($SubscriptionRateID)) {
            // Verify Record
            $queryDelete = "SELECT SubscriptionRateID, SubscriptionRateTitle FROM ". table_agencyinteract_subscription_rates ." WHERE SubscriptionRateID =  ="<?php echo  $SubscriptionRateID  ?>"";
            $resultsDelete = mysql_query($queryDelete);
            while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
    			// Remove Record
    			$delete = "DELETE FROM " . table_agencyinteract_subscription_rates . " WHERE SubscriptionRateID = ="<?php echo  $SubscriptionRateID  ?>"";
    			$results = $wpdb->query($delete);
    			?>
    			<div id="message" class="updated"><p><?php _e(LabelSingular ." <strong>". $dataDelete['SubscriptionRateTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p></div>
                <?php
				
            } // is there record?
        } // it was numeric
    }
    elseif ($_GET['action'] == "editRecord") {
		$action = $_GET['action'];
		$SubscriptionRateID = $_GET['SubscriptionRateID'];
		
		if ( $SubscriptionRateID > 0) {
			$query = "SELECT * FROM " . table_agencyinteract_subscription_rates . " WHERE SubscriptionRateID='$SubscriptionRateID'";
			$results = mysql_query($query) or die (__('Error, query failed', bb_agency_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$SubscriptionRateID		=$data['SubscriptionRateID'];
				$SubscriptionRateTitle	=stripslashes($data['SubscriptionRateTitle']);
				$SubscriptionRateType	=$data['SubscriptionRateType'];
				$SubscriptionRateText	=$data['SubscriptionRateText'];
				$SubscriptionRatePrice	=$data['SubscriptionRatePrice'];
				$SubscriptionRateTerm	=$data['SubscriptionRateTerm'];
			} 
		    ?>
			<h3 class="title"><?php printf( __("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			<p><?php printf( __("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?><strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong></p>
            <?php

		} else {
		
			$SubscriptionRateID		= 0;
			$SubscriptionRateTitle	="";
			$SubscriptionRateType	="";
			$SubscriptionRateText	="";
			$SubscriptionRatePrice	= 0;
			$SubscriptionRateTerm	= 1;
			
			?>
			<h3><?php printf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			<p><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> ". LabelSingular .". <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong></p>
            <?php
		} // Has Subscription rate or not
  } // Edit record
	?>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin.php?page='.$_GET['page'].'&amp;ConfigID='.$ConfigID) ?>">
	    <table class="form-table">
            <tbody>
                <tr valign="top">
                   <th scope="row"><?php _e("Title", bb_agency_TEXTDOMAIN) ?>:</th>
                   <td><input type="text" id="SubscriptionRateTitle" name="SubscriptionRateTitle" value="<?php echo $SubscriptionRateTitle ?>" /></td>
                </tr>
                <tr valign="top">
                   <th scope="row"><?php _e("Type", bb_agency_TEXTDOMAIN) ?> *:</th>
                   <td><select id="SubscriptionRateType" name="SubscriptionRateType">
                       <option value="0" <?php echo selected(0, $SubscriptionRateType) ?> >Standard</option>
                   </select></td>
                </tr>
                <tr valign="top">
                   <th scope="row"><?php _e("Text", bb_agency_TEXTDOMAIN) ?> *:</th>
                   <td><textarea id="SubscriptionRateText" name="SubscriptionRateText"><?php echo $SubscriptionRateText ?></textarea></td>
                </tr>
                <tr valign="top">
                   <th scope="row"><?php _e("Package Rate", bb_agency_TEXTDOMAIN) ?> *:</th>
                <td><input type="text" id="SubscriptionRatePrice" name="SubscriptionRatePrice" value="<?php echo  $SubscriptionRatePrice  ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e("Subscription Duration", bb_agency_TEXTDOMAIN) ?> *:</th>
                    <td><select id="SubscriptionRateTerm" name="SubscriptionRateTerm">
                        <option value="1" <?php echo selected(1, $SubscriptionRateTerm) ?> >1 Month</option>
                        <option value="2" <?php echo selected(2, $SubscriptionRateTerm) ?> >2 Months</option>
                        <option value="3" <?php echo selected(3, $SubscriptionRateTerm) ?> >3 Months</option>
                        <option value="6" <?php echo selected(6, $SubscriptionRateTerm) ?> >6 Months</option>
                        <option value="12" <?php echo selected(12, $SubscriptionRateTerm) ?> >1 Year</option>
                        <option value="24" <?php echo selected(24, $SubscriptionRateTerm) ?> >2 Years</option>
                        <option value="36" <?php echo selected(36, $SubscriptionRateTerm) ?> >3 Years</option>
                    </select></td>
                </tr>
            </tbody>
        </table>

        <?php if ( $SubscriptionRateID > 0) : ?>
        <p class="submit">
        	<input type="hidden" name="SubscriptionRateID" value="<?php echo  $SubscriptionRateID  ?>" />
        	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
        	<input type="hidden" name="action" value="editRecord" />
        	<input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
        </p>
        <?php else : ?>
        <p class="submit">
        	<input type="hidden" name="action" value="addRecord" />
        	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
        	<input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
        </p>
        <?php endif; ?> 
	</form>
	
	<h3 class="title"><?php _e("All Records", bb_agency_TEXTDOMAIN) ?></h3>
	<?php
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "SubscriptionRatePrice, SubscriptionRateTitle";
		}

		/******** Direction ************/
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
	?>
		<form method="post" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">	
            <table cellspacing="0" class="widefat fixed">
                <thead>
                    <tr class="thead">
                        <th class="manage-column column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
                        <th class="column" scope=="col"><a href="<?php echo admin_url('admin.php?page='. $_GET['page']) .'&amp;sort=SubscriptionRateTitle&amp;dir='. $sortDirection . '&amp;ConfigID=' . $ConfigID ) ?>"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></a></th>
                        <th class="column" scope=="col"><a href="<?php echo admin_url('admin.php?page='. $_GET['page']) .'&amp;sort=SubscriptionRateType&amp;dir='. $sortDirection . '&amp;ConfigID='. $ConfigID ) ?>"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></a></th>
                        <th class="column" scope=="col"><a href="<?php echo admin_url('admin.php?page='. $_GET['page']) .'&amp;sort=SubscriptionRatePrice&dir='. $sortDirection .'&amp;ConfigID='. $ConfigID ) ?>"><?php _e("Rate/Term", bb_agency_TEXTDOMAIN) ?></a></th>
                        <th class="column" scope=="col"><a href="<?php echo admin_url('admin.php?page='. $_GET['page']) .'&amp;sort=SubscriptionRateText&amp;dir='. $sortDirection . '&amp;ConfigID='. $ConfigID ) ?>"><?php _e("Text", bb_agency_TEXTDOMAIN) ?></a></th>
                    </tr>
                </thead>
                <tfoot>
            		<tr class="thead">
                		<th class=" columnmanage-column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
                		<th class="column" scope=="col"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
                		<th class="column" scope=="col"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></th>
                		<th class="column" scope=="col"><?php _e("Rate/Term", bb_agency_TEXTDOMAIN) ?></th>
                		<th class="column" scope=="col"><?php _e("Price", bb_agency_TEXTDOMAIN) ?></th>
                    </tr>
                </tfoot>
                <tbody>
                <?php
		$query = "SELECT * FROM ". table_agencyinteract_subscription_rates ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$SubscriptionRateID	=$data['SubscriptionRateID'];
        ?>
		<tr>
            <th class="check-column" scope="row"><input type="checkbox" class="administrator" id="<?php echo  $SubscriptionRateID ."=" name="". $SubscriptionRateID ."=" value="". $SubscriptionRateID  ?>" /></th>
    		<td class="column">". stripslashes($data['SubscriptionRateTitle']) ."\n";
        		<div class="row-actions=">
        		<span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;SubscriptionRateID=". $SubscriptionRateID ."&amp;ConfigID=". $ConfigID ) ?>" title=="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
        		<span class="delete"><a class="submitdelete" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;SubscriptionRateID=". $SubscriptionRateID ."&amp;ConfigID=". $ConfigID ) ?>" onclick=="if ( confirm('<?php _e("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) ?>.\'<?php _e("Cancel", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, \'<?php _e("OK", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;=" title=="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
        		</div>
    		</td>
    		<td class="column"><?php if ($data['SubscriptionRateType'] == 0) { echo "Standard"; } ?></td>
    		<td class="column">&pound;<?php echo $data['SubscriptionRatePrice'] ." / ". $data['SubscriptionRateTerm'] ?> Month Term</td>
    		<td class="column"><?php echo $data['SubscriptionRateText'] ?></td>
		</tr>
        <?php
		}
		mysql_free_result($results);
		if ($count < 1) : ?>
		<tr>
    		<td class="check-column" scope="row"></th>
    		<td class="column" colspan=="5"><p><?php _e("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) ?>!</p></td>
		</tr>
		<?php endif; ?>
		</tbody>
		</table>
		<p class="submit">
		<input type="hidden" name="action" value="deleteRecord" />
		<input type="submit" name="submit" value="<?php _e("Delete", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
		</p>
		
   		</form>
<?php
}	 // End	
elseif ($ConfigID == 10) {
// *************************************************************************************************** //
// Manage Style
	// Get the file
	$bb_agency_stylesheet = "../". bb_agency_BASEREL ."theme/style.css";
	
	if ($_POST["action"] == "saveChanges") {
		$bb_agency_stylesheet_file = fopen($bb_agency_stylesheet,"w") or exit("<p>Unable to open file to write!  Please edit via FTP</p>");
		$bb_agency_stylesheet_string = stripslashes($_POST["bb_agency_stylesheet_string"]);
		fwrite($bb_agency_stylesheet_file,$bb_agency_stylesheet_string,strlen($bb_agency_stylesheet_string));
	}
	
	if (file_exists($bb_agency_stylesheet)) {
		//echo "File Exists";
	} else { // File Does Not Exist
		$bb_agency_stylesheet = "../". bb_agency_BASEREL ."theme/style_base.css";
		//echo "File Does NOT exist";
	}
		
	$bb_agency_stylesheet_file = fopen($bb_agency_stylesheet,"r") or exit("Unable to open file to read!");
	$bb_agency_stylesheet_string = "";
	while(!feof($bb_agency_stylesheet_file)) {
	  $bb_agency_stylesheet_string .= fgets($bb_agency_stylesheet_file);
	}
	
	// Im done
	fclose($bb_agency_stylesheet_file);
	// Copy style over
	if ($_GET["mode"] == "override") {
		echo '<h1>OVERRIDE</h1>';
        $bb_options = bb_agency_get_option();
		if (bb_agency_get_option('bb_agency_option_defaultcss')) { 
            $bb_agency_stylesheet_string = bb_agency_get_option('bb_agency_option_defaultcss'); 
        }
	}
    ?>
	<form method="post" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
        <table class="form-table=">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Stylesheet', bb_agency_TEXTDOMAIN) ?></th>
                    <td><textarea name="bb_agency_stylesheet_string" style="width: 100%; height: 300px;=" /><?php echo $bb_agency_stylesheet_string ?></textarea></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="saveChanges" />
        <input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
        <input type="submit" class="button-primary=" value="<?php _e('Save Changes') ?>" />
	</form>
    <?php
}	 // End	
// *************************************************************************************************** //
// Setup Gender
elseif ($ConfigID == 5) {
	
	/** Identify Labels **/
	define("LabelPlural", __("Gender Types", bb_agency_TEXTDOMAIN));
	define("LabelSingular", __("Gender Type", bb_agency_TEXTDOMAIN));
    /* Initial Registration [RESPOND TO POST] ***********/ 
    if ( isset($_POST['action']) ) {
	
		$GenderID 	= $_POST['GenderID'];
		$GenderTitle 	= $_POST['GenderTitle'];
		// Error checking
		$error = "";
		$have_error = false;
		if(trim($GenderTitle) == ""){
			$error .= '<b><i>' . __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . '</i></b><br>';
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
    		// Add
    		case 'addRecord':
    			if ($have_error) : ?>
    				<div id="message" class="error">
                        <p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) ?></p>
                        <p><?php echo $error ?></p>
                    </div> 
    			<?php else :
    		
    				// Create Record
    				$insert = "INSERT INTO " . table_agency_data_gender . " (GenderTitle) VALUES ('" . $wpdb->escape($GenderTitle) . "')";
    				$results = $wpdb->query($insert);
    				$lastid = $wpdb->insert_id;
    				?>
    				<div id="message" class="updated">
                        <p><?php printf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, '<a href="'.admin_url("admin.php?page=". $_GET['page']) . '&amp;action=editRecord&amp;LoginTypeID=' . $lastid . '>') ?></a></p>
                        <p><?php echo $error ?></p>
                    </div>

    			<?php endif;
                break;
	
            // Manage
            case 'editRecord':
            	if ($have_error) : ?>
            		<div id="message" class="error">
                        <p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) ?></p>
                        <p><?php echo $error ?></p>
                    </div> 
            	<?php else :
            		$update = "UPDATE " . table_agency_data_gender . " 
            					SET 
            						GenderTitle='" . $wpdb->escape($GenderTitle) . "'
            					WHERE GenderID='$GenderID'";
            		$updated = $wpdb->query($update);
                    ?>
            		<div id="message" class="updated">
                        <p><?php printf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ?>!</p>
                        <p><?php echo $error ?></p>
                    </div>
            	<?php endif;
                break;

    		// Delete bulk
    		case 'deleteRecord':
    			foreach($_POST as $GenderID) {
                    if (is_numeric($GenderID)) {
                        // Verify Record
                        $queryDelete = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID =  ="<?php echo  $GenderID  ?>"";
                        $resultsDelete = mysql_query($queryDelete);
                        while ($dataDelete = mysql_fetch_array($resultsDelete)) {

                        	// Remove Record
                        	$delete = "DELETE FROM " . table_agency_data_gender . " WHERE GenderID = ="<?php echo  $GenderID  ?>"";
                        	$results = $wpdb->query($delete);
                        	?>
                        	<div id="message" class="updated">
                                <p><?php _e(LabelSingular ." <strong>". $dataDelete['GenderTitle'] .'</strong> deleted successfully', bb_agency_TEXTDOMAIN) ?>!</p>
                            </div>
                        	<?php
                        } // while
                    } // it was numeric
    			} // for each
                break;
		
		} // Switch
		
    } // Action Post
    elseif ($_GET['action'] == "deleteRecord") {
	
        $GenderID = $_GET['GenderID'];
        if (is_numeric($GenderID)) {
    		// Verify Record
    		$queryDelete = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID =  ="<?php echo  $GenderID  ?>"";
    		$resultsDelete = mysql_query($queryDelete);
    		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
    	
    			// Remove Record
    			$delete = "DELETE FROM " . table_agency_data_gender . " WHERE GenderID = ="<?php echo  $GenderID  ?>"";
    			$results = $wpdb->query($delete);
    			?>
    			<div id="message" class="updated">
                    <p><?php _e(LabelSingular ." <strong>". $dataDelete['GenderTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p>
                </div>
                <?php
    				
    		} // is there record?
        } // it was numeric
    }
    elseif ($_GET['action'] == "editRecord") {

		$action = $_GET['action'];
		$GenderID = $_GET['GenderID'];
		
		if ( $GenderID > 0) {
			$query = "SELECT * FROM " . table_agency_data_gender . " WHERE GenderID='$GenderID'";
			$results = mysql_query($query) or die (__('Error, query failed', bb_agency_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$GenderID	=$data['GenderID'];
				$GenderTitle	=stripslashes($data['GenderTitle']);
			} 
		      
            ?>
			<h3 class="title"><?php printf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			<p><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?><strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong></p>
            <?php
		}
    } else {
    		
        $GenderID		= 0;
        $GenderTitle	="";
        $GenderTag	="";
        ?>
        <h3><?php printf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
        <p><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong></p>
        <?php
    }
    ?>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
	<table class="form-table=">
	<tbody>
	<tr valign="top">
	<th scope="row"><?php _e("Title", bb_agency_TEXTDOMAIN) ?>:</th>
	<td><input type="text" id="GenderTitle" name="GenderTitle" value="<?php echo  $GenderTitle  ?>" /></td>
	</tr>
	</tbody>
	</table>
	<?php if ( $GenderID > 0) : ?>
	<p class="submit">
	<input type="hidden" name="GenderID" value="<?php echo $GenderID ?>" />
	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
	<input type="hidden" name="action" value="editRecord" />
	<input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
	</p>
	<?php else : ?>
	<p class="submit">
	<input type="hidden" name="action" value="addRecord" />
	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
	<input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
	</p>
	<?php endif; ?>
	</form>
	
	<h3 class="title"><?php _e("All Records", bb_agency_TEXTDOMAIN) ?></h3>
	<?php
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "GenderTitle";
		}
		
		/******** Direction ************/
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
	?>
	<form method="post" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">	
		<table cellspacing=="0" class="widefat fixed=">
    		<thead>
        		<tr class="thead">
            		<th class="manage-column column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
            		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=GenderTitle&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></a></th>
        		</tr>
        	</thead>
        	<tfoot>
        		<tr class="thead">
            		<th class=" columnmanage-column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
            		<th class="column" scope=="col"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
        		</tr>
    		</tfoot>
            <tbody>
	    <?php
		$query = "SELECT * FROM ". table_agency_data_gender ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) :
			$GenderID	=$data['GenderID'];
		?>	
    		<tr>
        		<th class="check-column" scope="row">
                    <input type="checkbox" class="administrator" id="<?php echo $GenderID ?>" name="<?php echo $GenderID ?>" value="<?php echo $GenderID ?>" />
                </th>
        		<td class="column">". stripslashes($data['GenderTitle']) ."\n";
            		<div class="row-actions=">
                		<span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;GenderID=". $GenderID ."&amp;ConfigID=". $ConfigID ) ?>" title=="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
                		<span class="delete"><a class="submitdelete" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;GenderID=". $GenderID ."&amp;ConfigID=". $ConfigID ) ?>"  onclick=="if ( confirm('<?php _e("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) ?>.\'<?php _e("Cancel", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, \'<?php _e("OK", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;=" title=="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
            		</div>
        		</td>
    		</tr>
        <?php endwhile; 
		
		mysql_free_result($results);
		if ($count < 1) : ?>
		<tr>
    		<td class="check-column" scope="row"></th>
    		<td class="column" colspan=="3"><p><?php _e("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) ?>!</p></td>
		</tr>
		<?php endif; ?>
		</tbody>
		</table>
		<p class="submit">
		<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
		<input type="hidden" name="action" value="deleteRecord" />
		<input type="submit" name="submit" value="<?php _e("Delete", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
		</p>
		
   		</form>
    <?php
}	 // End	
// *************************************************************************************************** //
// Setup Profile Categories
elseif ($ConfigID == 6) {
	
	/** Identify Labels **/
	define("LabelPlural", __("Profile Types", bb_agency_TEXTDOMAIN));
	define("LabelSingular", __("Profile Type", bb_agency_TEXTDOMAIN));
    /* Initial Registration [RESPOND TO POST] ***********/ 
    if ( isset($_POST['action']) ) {
	
		$DataTypeID 	= $_POST['DataTypeID'];
		$DataTypeTitle 	= $_POST['DataTypeTitle'];
		$DataTypeTag 	= $_POST['DataTypeTag'];
			if (empty($DataTypeTag)) { $DataTypeTag = bb_agency_safenames($DataTypeTitle); }
		// Error checking
		$error = "";
		$have_error = false;
		if(trim($DataTypeTitle) == ""){
			$error .= '<b><i>' . __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . '</i></b><br />';
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
    		// Add
    		case 'addRecord':
    			if ($have_error) : ?>
    				<div id="message" class="error">
                        <p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) ?>
                        </p>
                        <p><?php echo $error ?></p>
                    </div>
                    
    			<?php else :
    		
    				// Create Record
    				$insert = "INSERT INTO " . table_agency_data_type . " (DataTypeTitle,DataTypeTag) VALUES ('" . $wpdb->escape($DataTypeTitle) . "','" . $wpdb->escape($DataTypeTag) . "')";
    				$results = $wpdb->query($insert);
    				$lastid = $wpdb->insert_id;
    				
    				?>
                    <div id="message" class="updated">
                        <p><?php printf(__("%1$s <strong>added</strong> successfully! You may now %1$s to the record", bb_agency_TEXTDOMAIN), LabelSingular, '<a href="' . admin_url('admin.php?page=' . $_GET['page']) . '&amp;action=editRecord&amp;LoginTypeID=' . $lastid . '">Load Information</a>') ?>
                        </p>
                        <p><?php echo $error ?></p>
                    </div>

                <?php endif; 
    			
                break;
	
		// Manage
		case 'editRecord':
			if ($have_error) :
				?>
                <div id="message" class="error">
                    <p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) ?></p>
                    <p><?php echo $error ?></p>
                </div>

			<?php else :
				$update = "UPDATE " . table_agency_data_type . " 
							SET 
								DataTypeTitle='" . $wpdb->escape($DataTypeTitle) . "',
								DataTypeTag='" . $wpdb->escape($DataTypeTag) . "' 
							WHERE DataTypeID='$DataTypeID'";
				$updated = $wpdb->query($update);
				?>
                <div id="message" class="updated">
                    <p><?php printf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ?>!</p>
                    <p><?php echo $error ?></p>
                </div>
            <?php endif;
			
            break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $DataTypeID) {
    			if (is_numeric($DataTypeID)) {
    				// Verify Record
    				$queryDelete = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID =  ="<?php echo  $DataTypeID  ?>"";
    				$resultsDelete = mysql_query($queryDelete);
    				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
    			
    					// Remove Record
    					$delete = "DELETE FROM " . table_agency_data_type . " WHERE DataTypeID = ="<?php echo  $DataTypeID  ?>"";
    					$results = $wpdb->query($delete);
    					?>
    					<div id="message" class="updated">
                            <p><?php _e(LabelSingular ." <strong>". $dataDelete['DataTypeTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p>
                        </div>
    					<?php
    				} // while
    			 } // it was numeric
			} // for each
            break;
		
		} // Switch
		
    } // Action Post
    elseif ($_GET['action'] == "deleteRecord") {
	
        $DataTypeID = $_GET['DataTypeID'];
        if (is_numeric($DataTypeID)) {
    		// Verify Record
    		$queryDelete = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID =  ="<?php echo  $DataTypeID  ?>"";
    		$resultsDelete = mysql_query($queryDelete);
    		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
    	
    			// Remove Record
    			$delete = "DELETE FROM " . table_agency_data_type . " WHERE DataTypeID = ="<?php echo  $DataTypeID  ?>"";
    			$results = $wpdb->query($delete);
    			?>
    			<div id="message" class="updated">
                    <p><?php _e(LabelSingular ." <strong>". $dataDelete['DataTypeTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p>
                </div>
    			<?php	
    		} // is there record?
        } // it was numeric
    }
    elseif ($_GET['action'] == "editRecord") {
		$action = $_GET['action'];
		$DataTypeID = $_GET['DataTypeID'];
		
		if ( $DataTypeID > 0) {
			$query = "SELECT * FROM " . table_agency_data_type . " WHERE DataTypeID='$DataTypeID'";
			$results = mysql_query($query) or die (__('Error, query failed', bb_agency_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$DataTypeID		=$data['DataTypeID'];
				$DataTypeTitle	=stripslashes($data['DataTypeTitle']);
				$DataTypeTitle = str_replace(' ', '_', $DataTypeTitle);
				$DataTypeTag	=$data['DataTypeTag'];
			} 
            ?>
			<h3 class="title"><?php printf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			<p><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?><strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
            </p>
            <?php
		}
    } else {
		
		$DataTypeID		= 0;
		$DataTypeTitle	="";
		$DataTypeTag	="";
		?>
		<h3><?php printf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
		<p><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
        </p>
        <?php
    }
    ?>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
    	<table class="form-table=">
        	<tbody>
            	<tr valign="top">
            	<th scope="row"><?php _e("Title", bb_agency_TEXTDOMAIN) ?>:</th>
            	<td><input type="text" id="DataTypeTitle" name="DataTypeTitle" value="<?php echo  $DataTypeTitle  ?>" /></td>
            	</tr>
            	<?php if ( $DataTypeID > 0) : ?>
            	<tr valign="top">
            	<th scope="row"><?php _e("Slug", bb_agency_TEXTDOMAIN) ?>:</th>
            	<td><input type="text" id="DataTypeTag" name="DataTypeTag" value="<?php echo  $DataTypeTag  ?>" /></td>
            	</tr>
            	<?php endif; ?> 
        	</tbody>
    	</table>
    	<?php if ( $DataTypeID > 0) : ?>
    	<p class="submit">
    	<input type="hidden" name="DataTypeID" value="<?php echo $DataTypeID ?>" />
    	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
    	<input type="hidden" name="action" value="editRecord" />
    	<input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
    	</p>
    	<?php else : ?>
    	<p class="submit">
    	<input type="hidden" name="action" value="addRecord" />
    	<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
    	<input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
    	</p>
    	<?php endif; ?>
	</form>
	
	<h3 class="title"><?php _e("All Records", bb_agency_TEXTDOMAIN) ?></h3>
	<?php
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "DataTypeTitle";
		}
		
		/******** Direction ************/
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
	?>
		<form method="post" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">	
		<table cellspacing=="0" class="widefat fixed=">
		<thead>
		<tr class="thead">
		<th class="manage-column column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=DataTypeTitle&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=DataTypeTag&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Slug", bb_agency_TEXTDOMAIN) ?></a></th>
		</tr>
		</thead>
		<tfoot>
		<tr class="thead">
		<th class=" columnmanage-column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Slug", bb_agency_TEXTDOMAIN) ?></th>
		</tr>
		</tfoot>
		<tbody>
        <?php
		$query = "SELECT * FROM ". table_agency_data_type ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) :
			$DataTypeID	=$data['DataTypeID'];
        ?>
    		<tr>
                <th class="check-column" scope="row"><input type="checkbox" class="administrator" id="<?php echo $DataTypeID ?>" name="<?php echo $DataTypeID ?>" value="<?php echo $DataTypeID ?>" /></th>
        		<td class="column"><?php echo stripslashes($data['DataTypeTitle']) ?>
            		<div class="row-actions=">
                		<span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;DataTypeID=". $DataTypeID ."&amp;ConfigID=". $ConfigID ) ?>" title=="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
                		<span class="delete"><a class="submitdelete" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;DataTypeID=". $DataTypeID ."&amp;ConfigID=". $ConfigID ) ?>"  onclick=="if ( confirm('<?php _e("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) ?>.\'<?php _e("Cancel", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, \'<?php _e("OK", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;=" title=="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
            		</div>
        		</td>
    		  <td class="column"><?php echo $data['DataTypeTag'] ?></td>
    		</tr>
        <?php endwhile;

		mysql_free_result($results);
		if ($count < 1) : ?>
    		<tr>
                <td class="check-column" scope="row"></th>
                <td class="column" colspan=="3"><p><?php _e("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) ?>!</p></td>
    		</tr>
		<?php endif; ?>
		</tbody>
		</table>
		<p class="submit">
		<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
		<input type="hidden" name="action" value="deleteRecord" />
		<input type="submit" name="submit" value="<?php _e("Delete", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
		</p>
		
   		</form>
    <?php
}	 // End	
// *************************************************************************************************** //
// Setup Custom Fields
elseif ($ConfigID == 7) {
	/** Identify Labels **/
	define("LabelPlural", __("Custom Fields", bb_agency_TEXTDOMAIN));
	define("LabelSingular", __("Custom Field", bb_agency_TEXTDOMAIN));

	$bb_agency_option_unittype  = bb_agency_get_option('bb_agency_option_unittype');
	
    /* Initial Registration [RESPOND TO POST] ***********/ 
    if ( isset($_POST['action']) ) {
	
   
		$ProfileCustomID 			= $_POST['ProfileCustomID'];
		$ProfileCustomTitle 		= $_POST['ProfileCustomTitle'];
		$ProfileCustomType 			= $_POST['ProfileCustomType'];
		$ProfileCustomOptions 		= $_POST['ProfileCustomOptions'];
		$ProfileCustomView 			= (int)$_POST['ProfileCustomView'];
		$ProfileCustomOrder 		= (int)$_POST['ProfileCustomOrder'];
		$ProfileCustomShowGender	= (int)$_POST['ProfileCustomShowGender'];
	    $ProfileCustomShowProfile  	= (int)$_POST['ProfileCustomShowProfile'];
		$ProfileCustomShowSearch  	= (int)$_POST['ProfileCustomShowSearch'];
		$ProfileCustomShowLogged  	= (int)$_POST['ProfileCustomShowLogged'];
		$ProfileCustomShowRegistration= (int)$_POST['ProfileCustomShowRegistration'];
		$ProfileCustomShowAdmin   	= (int)$_POST['ProfileCustomShowAdmin'];
		$ProfileCustomPrivacy   	= (int)$_POST['ProfileCustomPrivacy'];

		/*
		* Set profile types here
		*/
		
		$get_types = "SELECT * FROM ". table_agency_data_type;
						
		$result = mysql_query($get_types);
						
		while ( $typ = mysql_fetch_array($result)){
                  $t = trim($typ['DataTypeTitle']);
				 $t = str_replace(' ', '_', $t);
			     $name = 'ProfileType' . $t; 
				 $$name = (int) $_POST['ProfileType' . $t]; 
		} 		

		//adjustment in making the visibility fields into a checkbox
		if($ProfileCustomPrivacy==3){   
		  	$ProfileCustomShowLogged = "0";  
			$ProfileCustomShowAdmin  = "0";
		}elseif($ProfileCustomPrivacy==2){
			$ProfileCustomShowLogged = "0";
			$ProfileCustomShowAdmin  = "1";
		}else{
			$ProfileCustomShowLogged = "1";
			$ProfileCustomShowAdmin  = "0";			
		}
        $error = "";	
		if($ProfileCustomType == 1){ //Text
			//...
		} elseif ($ProfileCustomType == 3){ //Dropdown
			$label_option ="";
			$option = "";
			$label_option2 = "";
			$option2 = "";
			if(!empty($_POST["option"]) && isset($_POST["option"])){
				foreach($_POST["option"] as $key => $val){
					if(!empty($val)){
						$option .= $val."|";
					}
				}
				$label_option = "".$_POST["option_label"]."|".$option;  //
			}
			if(!empty($_POST["option2"]) && isset($_POST["option2"])){
				foreach($_POST["option2"] as $key2 => $val2){
					if(!empty($val2)){
						$option2 .= $val2."|";
					}
				}
				$label_option2 = ":".$_POST["option_label2"]."|".$option2;  //
			}
			$ProfileCustomOptions = $label_option.$label_option2; 
		}elseif($ProfileCustomType == 4){ //TextArea
		 	$ProfileCustomOptions = $_POST["ProfileCustomOptions"];
		}elseif($ProfileCustomType == 5){ //Checkbox
			$pos = 0;
			foreach($_POST["label"] as $key => $val){
				if(!empty($_POST["label"]) && $_POST["label"] !=""  && !empty($val)){
					$pos++;		
					if($pos!= count($_POST["label"])){ 
						$ProfileCustomOptions .= $val."|";
					}else{
						$ProfileCustomOptions .= $val;
					}
				}
			}
		}elseif($ProfileCustomType == 6){ //RadioButton
			$pos = 0;
				foreach($_POST["label"] as $key => $val){
				if(!empty($_POST["label"])  && $_POST["label"] !="" && !empty($val)){
					$pos++;	
					if($pos!= count($_POST["label"])){ 
						$ProfileCustomOptions .= $val."|";
					}else{
						$ProfileCustomOptions .= $val;
					}
				}
			}
		}elseif($ProfileCustomType == 7){ //Metric & Imperial
		   $ProfileCustomOptions = $_POST["ProfileUnitType"];
		}/*elseif ($ProfileCustomType == 8){ //Dropdown
			$label_option ="";
			$option = "";
			$label_option2 = "";
			$option2 = "";
			if(!empty($_POST["multiple"]) && isset($_POST["multiple"])){
				foreach($_POST["multiple"] as $key => $val){
					if(!empty($val)){
						$option .= $val."|";
					}
				}
				$label_option = "".$_POST["option_label"]."|".$option;  //
			}
			if(!empty($_POST["option2"]) && isset($_POST["option2"])){
				foreach($_POST["option2"] as $key2 => $val2){
					if(!empty($val2)){
						$option2 .= $val2."|";
					}
				}
				$label_option2 = ":".$_POST["option_label2"]."|".$option2;  //
			}
			$ProfileCustomOptions = $label_option.$label_option2; 
		}*/
		// Error checking
	
		$have_error = false;
		if(trim($ProfileCustomTitle) == ""){
			$error .= '<b><i>' . __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . '</i></b><br />'
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				?>
                <div id="message" class="error"><p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .?></p>
                <p><?php echo $error ?></p>
                </div>
				<h3 style="width:430px;"><?php printf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
				<div class="postbox"  style="width:430px;float:left;border:0px solid black;">
				    <h3 class="hndle" style="margin:10px;font-size:11px;">
                        <span><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?>. <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                        </span>
                    </h3>
				<div class="inside">
                <?php
			} else {
		
				// Create Record
				$insert = "INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle,ProfileCustomType,ProfileCustomOptions,ProfileCustomView,ProfileCustomOrder,ProfileCustomShowGender,ProfileCustomShowProfile,ProfileCustomShowSearch,ProfileCustomShowLogged,ProfileCustomShowAdmin,ProfileCustomShowRegistration) VALUES ('" . $wpdb->escape($ProfileCustomTitle) . "','" . $wpdb->escape($ProfileCustomType) . "','" . $wpdb->escape($ProfileCustomOptions) . "','" . $wpdb->escape($ProfileCustomView) . "','" . $wpdb->escape($ProfileCustomOrder ) . "','" . $wpdb->escape($ProfileCustomShowGender ) . "','" . $wpdb->escape($ProfileCustomShowProfile ) . "','" . $wpdb->escape($ProfileCustomShowSearch) . "','" . $wpdb->escape($ProfileCustomShowLogged ) . "','" . $wpdb->escape($ProfileCustomShowAdmin) . "','" . $wpdb->escape($ProfileCustomShowRegistration) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;

				/*
				* Add to Custom Client
				* if the Profile Custom Client is
				* Selected
				*/
				$Types = "";
				
				/*
				* Set Types Here for each Custom fields.
				*/ 
				$get_types = "SELECT * FROM ". table_agency_data_type;
						
				$result = mysql_query($get_types);
						
				while ( $typ = mysql_fetch_array($result)){
				  $profiletyp = 'ProfileType' . trim($typ['DataTypeTitle']);
				   $profiletyp = str_replace(' ', '_', $profiletyp);	
				  if($$profiletyp) { $Types .= trim($typ['DataTypeTitle']) . "," ; }  
		       } 
			
				$Types = rtrim($Types, ",");
				
				if($Types != "" or !empty($Types)){
				
					$check_sql = "SELECT ProfileCustomTypesID FROM " . table_agency_customfields_types . 
		           " WHERE ProfileCustomID= " . $lastid; 
		
					$check_results = mysql_query($check_sql);
					$count_check = mysql_num_rows($check_results);
		
					if($count_check <= 0){
						//create record in Custom Clients
						$insert_client = "INSERT INTO " . table_agency_customfields_types . 
						" (ProfileCustomID,ProfileCustomTitle,ProfileCustomTypes) 
						VALUES (" . $lastid . ",'" 
						         . $wpdb->escape($ProfileCustomTitle) . "','" 
						         . $Types . "')";
						
						$results_client = $wpdb->query($insert_client);
		  			} else {
						//update if already existing 
						$update = "UPDATE " . table_agency_customfields_types . " 
					             SET 
						         ProfileCustomTypes='" . $Types . "' 
					             WHERE ProfileCustomID = ".$lastid;
		               $updated = $wpdb->query($update);
					}
					
				}
				
				?>
                <div id="message" class="updated">
                    <p><?php printf(__("%1$s <strong>added</strong> successfully! You may now %1$s to the record", bb_agency_TEXTDOMAIN), LabelSingular, '<a href="'.admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;LoginTypeID=". $lastid . '">Load Information</a>' ) ?></p>
                    <p><?php echo $error ?></p>
                </div> 
				<h3 style="width:430px;"><?php printf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
				<div class="postbox"  style="width:430px;float:left;border:0px solid black;">
				    <h3 class="hndle" style="margin:10px;font-size:11px;">
                        <span ><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?>.
                            <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                        </span>
                    </h3>
				<div class="inside">
                <?php	
			}   
            break;
	
		// Manage
		case 'editRecord':
			if($have_error) :
				?>
                <div id="message" class="error">
                    <p><?php printf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) ?>.</p>
                    <p><?php echo $error ?></p>
                </div> 
				<h3 style="width:430px;"><?php printf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			    <div class="postbox"  style="width:430px;float:left;border:0px solid black;">
				    <h3 class="hndle" style="margin:10px;font-size:11px;">
                        <span ><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?>.
                        <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                        </span>
                    </h3>
				<div class="inside">
            <?php else :
				$update = "UPDATE " . table_agency_customfields . " 
							SET 
								ProfileCustomTitle='" . $wpdb->escape($ProfileCustomTitle) . "',
								ProfileCustomType='" . $wpdb->escape($ProfileCustomType) . "',
								ProfileCustomOptions='" . $wpdb->escape($ProfileCustomOptions) . "',
								ProfileCustomView=" . $wpdb->escape($ProfileCustomView) . ", 
								ProfileCustomOrder=" . $wpdb->escape($ProfileCustomOrder) . " ,
								ProfileCustomShowGender=" . $wpdb->escape($ProfileCustomShowGender) . ",
								ProfileCustomShowProfile=" . $wpdb->escape($ProfileCustomShowProfile) . " ,
								ProfileCustomShowSearch=" . $wpdb->escape($ProfileCustomShowSearch) . " ,
								ProfileCustomShowLogged=" . $wpdb->escape($ProfileCustomShowLogged) . " ,
								ProfileCustomShowRegistration=" . $wpdb->escape($ProfileCustomShowRegistration) . " ,
								ProfileCustomShowAdmin=" . $wpdb->escape($ProfileCustomShowAdmin) . " 
							WHERE ProfileCustomID='$ProfileCustomID'";
				$updated = mysql_query($update) or die(mysql_error());

				/*
				* Check if There is Custom client
				* to be updated
				*/

				$Types = "";
				
				/*
				* Set Types Here for each Custom fields.
				*/ 
				$get_types = "SELECT * FROM ". table_agency_data_type;
						
				$result = mysql_query($get_types);
						
				while ( $typ = mysql_fetch_array($result)){
                    $t = 'ProfileType' . trim($typ['DataTypeTitle']);$t = str_replace(' ', '_', $t);
                    $n = trim($typ['DataTypeTitle']);
                    $n = str_replace(' ', '_', $n);
                    if($$t) { 
				  	   
                        $$n = true;
                        $Types .= $n . "," ; 
				    
				    } else { 
					  
                        $$n = false;
				    }  
		       } 
				
				$Types = rtrim($Types, ",");
				
				echo '<input type="hidden" name="apstypes" value="'.$Types.'">';
				
				if($Types != "" or !empty($Types)){
				
					$check_sql = "SELECT ProfileCustomTypesID FROM " . table_agency_customfields_types . 
		           " WHERE ProfileCustomID = " . $ProfileCustomID; 
		
					$check_results = mysql_query($check_sql);
		
					$count_check = mysql_num_rows($check_results);
		
					if($count_check <= 0){
						//create record in Custom Clients
						$insert_client = "INSERT INTO " . table_agency_customfields_types . 
						" (ProfileCustomID,ProfileCustomTitle,ProfileCustomTypes) 
						VALUES (" . $ProfileCustomID . ",'" 
						         . $wpdb->escape($ProfileCustomTitle) . "','" 
						         . $Types . "')";
						
						$results_client = $wpdb->query($insert_client);
		  			} else {
						//update if already existing 
						$update = "UPDATE " . table_agency_customfields_types . " 
					             SET 
						         ProfileCustomTypes='" . $Types . "' 
					             WHERE ProfileCustomID = ".$ProfileCustomID;
		               $updated = $wpdb->query($update);
					}
					
				} else {
						
					  /*
						* Delete if there is no selections
						*/
						$delete = "DELETE FROM " . table_agency_customfields_types . " 
					             WHERE ProfileCustomID = ".$ProfileCustomID;
				       $deleted = $wpdb->query($delete);
				}
	
                ?>
				<div id="message" class="updated">
                    <p><?php printf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ?>!</p>
                    <p><?php echo $error ?></p>
                </div> 
                <h3 style="width:430px;"><?php printf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			    <div class="postbox"  style="width:430px;float:left;border:0px solid black;">
				<h3 class="hndle" style="margin:10px;font-size:11px;">
                    <span ><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?>. 
                    <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                    </span>
                </h3>
				<div class="inside">
                <?php
			}
            break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $ProfileCustomID) {
    			if (is_numeric($ProfileCustomID)) {
    				// Verify Record
    				$queryDelete = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomID = $ProfileCustomID";
    				$resultsDelete = mysql_query($queryDelete);
    				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
    			
    					// Remove Record
    					$delete = "DELETE FROM " . table_agency_customfields . " WHERE ProfileCustomID = ="<?php echo  $ProfileCustomID  ?>"";
    					$results = $wpdb->query($delete);
    					
    					// Rmove from Custom Types
    					$delete_sql = "DELETE FROM " . table_agency_customfields_types . 
    					" WHERE ProfileCustomID='$ProfileCustomID'";
    					$deleted = mysql_query($delete_sql) or die(mysql_error());
    					?>
    					<div id="message" class="updated">
                            <p><?php _e(LabelSingular ." <strong>". $dataDelete['ProfileCustomTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p>
                        </div>
    	                <?php  
    				} // while
    			 } // it was numeric
			} // for each
            ?>
			<h3 style="width:430px;"><?php printf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
			<div class="postbox" style="width:430px;float:left;border:0px solid black;">
			<h3 class="hndle" style="margin:10px;font-size:11px;">
                <span><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?>. 
                <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                </span>
            </h3>
			<div class="inside">
            <?php
            break;
		
		} // Switch
			
  } // Action Post
  elseif (isset($_GET["deleteRecord"])) {
	
	$ProfileCustomID = $_GET['ProfileCustomID'];
	  if (is_numeric($ProfileCustomID)) {
		// Verify Record
		$queryDelete = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomID = $ProfileCustomID";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_agency_customfields . " WHERE ProfileCustomID = $ProfileCustomID";
			$results = $wpdb->query($delete);

			// Rmove from Custom Types
			$delete_sql = "DELETE FROM " . table_agency_customfields_types . 
			" WHERE ProfileCustomID='$ProfileCustomID'";
			$deleted = mysql_query($delete_sql) or die(mysql_error());
			?>
			<div id="message" class="updated">
                <p><?php _e(LabelSingular ." <strong>". $dataDelete['ProfileCustomTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ?>!</p>
            </div>
			<h3 style="width:430px;"><?php printf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>	
			<div class="postbox ="  style="width:430px;float:left;border:0px solid black;">
			<h3 class="hndle" style="margin:10px;font-size:11px;">
                <span><?php printf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?>. <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                </span>
            </h3>
			<div class="inside">
		    <?php
				
		} // is there record?
	  } // it was numeric
  }
  elseif ($_GET['action'] == "editRecord") {
		$action = $_GET['action'];
		$ProfileCustomID = $_GET['ProfileCustomID'];
		
		if ( $ProfileCustomID > 0) {
			$query = "SELECT * FROM " . table_agency_customfields . " WHERE ProfileCustomID='$ProfileCustomID'";
			$results = mysql_query($query) or die (__('Error, query failed', bb_agency_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$ProfileCustomID			=	$data['ProfileCustomID'];
				$ProfileCustomTitle		=	stripslashes($data['ProfileCustomTitle']);
				$ProfileCustomType		=	$data['ProfileCustomType'];
				$ProfileCustomOptions		=	$data['ProfileCustomOptions'];
				$ProfileCustomView		=	$data['ProfileCustomView'];
				$ProfileCustomOrder		=	$data['ProfileCustomOrder'];
				$ProfileCustomShowGender	=	$data['ProfileCustomShowGender'];
				$ProfileCustomShowProfile	=	$data['ProfileCustomShowProfile'];
				$ProfileCustomShowSearch	=	$data['ProfileCustomShowSearch'];
				$ProfileCustomShowLogged	=	$data['ProfileCustomShowLogged'];
				$ProfileCustomShowRegistration=	$data['ProfileCustomShowRegistration'];
				$ProfileCustomShowAdmin		=	$data['ProfileCustomShowAdmin'];
			} 
            ?>
			<h3 style="width:430px;"><?php printf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>	
            <div class="postbox" style="width:430px;float:left;border:0px solid black;">
                <h3 class="hndle" style="margin:10px;font-size:11px;">
                    <span ><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?>. 
                    <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                    </span>
                </h3>
                <div class="inside">
		<?php
	      
		}
  } else {
		
			$ProfileCustomID			=	0;
			$ProfileCustomTitle		=	"";
			$ProfileCustomType		=	"";
			$ProfileCustomOptions		=	"";
			$ProfileCustomView		=	0;
			$ProfileCustomOrder		=	0;
			$ProfileCustomShowGender	=	0;
			$ProfileCustomShowProfile	=	0;
			$ProfileCustomShowSearch	=	0;
			$ProfileCustomShowLogged	=	0;
			$ProfileCustomShowRegistration=	0;
			$ProfileCustomShowAdmin		=	0;
			
		?>
		<h3 style="width:430px;"><?php printf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ?></h3>
		<div class="postbox ="  style="width:430px;float:left;border:0px solid black;">
    		<h3 class="hndle" style="margin:10px;font-size:11px;">
                <span ><?php _e("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?>. 
                <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong>
                </span>
            </h3>
        <div class="inside">
        <?php
  	}
	if(isset($_GET["action"]) == "editRecord") : ?>
		<form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&ProfileCustomID=".$_GET["ProfileCustomID"]."&amp;ConfigID=".$_GET["ConfigID"] ?>">
	<?php else : ?>
		<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin.php?page='. $_GET['page']) ?>">
	<?php endif; ?>
	<table class="form-table=">
		<?php if(!isset($_GET["action"])){  // Create new Field	?>	
			<tr>
                    <td> 
						<tr>
							<td>Type*:</td>
							<td>
								<?php if(bb_agency_get_option('bb_agency_option_unittype')==1) : ?>
								<select class="objtype" name="ProfileCustomType" id="1">
								<?php else : ?>
								<select class="objtype" name="ProfileCustomType" id="0">
								<?php endif; ?>
								    <option value="=">---</option>
									<option value="1">Single Line Text</option>
									<!--<option value="2">Min/Max textfield</option>-->
									<option value="3">Dropdown</option>
									<option value="4">Textbox</option>
									<option value="5">Checkbox</option>
									<option value="6">Radiobutton</option>
									<?php if(bb_agency_get_option('bb_agency_option_unittype')==1) : ?>
										<option value="7" id="1">Imperial(ft/in/lb)</option>
									<?php else : ?>
										<option value="7" id="0">Metric(cm/kg)</option>
									<?php endif; ?>
									<option value="8">Multiple Options</option>
                                    <option value="9">Date</option>
								</select>					  
                            </td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td valign="top">Visibility*:</td>
							<td style="font-size:13px;">
							<input type="radio" name="ProfileCustomView" value="0" checked=="checked" />Show Everywhere(Front-end &amp; Back-end)&nbsp;<br/>
							<input type="radio" name="ProfileCustomView" value="1" />Private(Only show in Admin CRM)&nbsp;<br/>
							<input type="radio" name="ProfileCustomView" value="2" />Custom(Used in Custom Views)&nbsp;
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td valign="top">Custom Views:</td>
							<td style="font-size:13px;">
                                <input type="checkbox" name="ProfileCustomShowProfile" value="1" checked=="checked" /> Manage Profile (Back-end)&nbsp; <br/>
                                <input type="checkbox" name="ProfileCustomShowSearch" value="1"  checked=="checked" /> Search Form (Back-end)&nbsp; <br/>  
                                <input type="checkbox" name="ProfileCustomShowRegistration" value="1"  checked=="checked" /> Profile Registration Form 			                                     &nbsp; <br/>
							</td>
					 	</tr>
					 	<tr>
							<td valign="top">Privacy:</td>
							<td style="font-size:13px;">
							<input type="radio" name="ProfileCustomPrivacy" value="1"  /> User must be logged in to see It &nbsp;<br/>
							<input type="radio" name="ProfileCustomPrivacy" value="2" /> User must be an admin to see It<br/>
							<input type="radio" name="ProfileCustomPrivacy" value="3" /> Visible to Public 
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td valign="top">Gender*:</td>
							<td valign="top"	 style="font-size:13px;">
							$query = "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
							<select name="ProfileCustomShowGender">
							<option value="=">All Gender</option>
                            <?php
							$queryShowGender = mysql_query($query);
							while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
								if (isset($data1["ProfileCustomShowGender"])) : ?>
									<option value="<?php echo $dataShowGender['GenderID'] ?>" selected=="selected"><?php echo $dataShowGender["GenderTitle"] ?></option>
								<?php else : ?>
									<option value="<?php echo $dataShowGender['GenderID'] ?>"><?php echo $dataShowGender["GenderTitle"] ?></option>
								<?php endif;
							}
                            ?>
							</select>
							
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
						<td valign="top">Profile Type:</td>
						<td style="font-size:13px;">
						<?php
						/*
						* get the proper fields on
						* profile types here
						*/
						
						$get_types = "SELECT * FROM ". table_agency_data_type;
						
						$result = mysql_query($get_types);
						
						while ( $typ = mysql_fetch_array($result)){
										$t = trim($typ['DataTypeTitle']);
										$t = str_replace(' ', '_', $t);
										echo '<input type="checkbox" name="ProfileType'.$t.'" value="1" ' . 
											($$t == true ? 'checked="checked"':''). '  />&nbsp;'.
											trim($typ['DataTypeTitle'])
											.'&nbsp;<br/>';
						} 
					    ?>
                            </td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td valign="top">Custom Order:</td>
							<td style="font-size:13px;">
                                <input type="text" name="ProfileCustomOrder" value="0" />
							</td>
							<td></td>
							<td></td>
						</tr>
					</td>
				</tr>
			</td>
		</tr>
	</table>
		
		<table>		
			<tr id="objtype_customize">
				<td>	
				</td>	
			</tr>
		</table>		
		
		<?php
		}else{ //Edit/Update Field
			$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions,  ProfileCustomOrder, ProfileCustomView,  ProfileCustomShowGender	, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration  FROM ". table_agency_customfields ." WHERE ProfileCustomID = ".$_GET["ProfileCustomID"];
			$results1 = mysql_query($query1);
			$count1 = mysql_num_rows($results1);
			$pos = 0;
			while ($data1 = mysql_fetch_array($results1)) {
			
				//get record from Clients to edit
				$select_sql = "Select  * FROM " . table_agency_customfields_types . 
				" WHERE ProfileCustomID= " . $data1["ProfileCustomID"];
				
				$select_sql = mysql_query($select_sql) or die(mysql_error());
				
				$fetch_type = mysql_fetch_assoc($select_sql);
				
				$array_type = explode(",",$fetch_type['ProfileCustomTypes']);
				
				$a = array();
				
				foreach($array_type as $t_arr){
					$$t_arr = true;
				}
						
				$pos ++;			
				$query2 = "SELECT * FROM ". table_agency_customfields_mux ." WHERE ProfileCustomID=".$data1["ProfileCustomID"]." AND ProfileID=".$ProfileID."";
				$results2 = mysql_query($query2);
				?>
                <tr>
                	<td>Type*:</td>
                	<td>
                	   <select class="objtype" name="ProfileCustomType">
                        	<option value="">---</option>
                        	<option value="1" <?php echo ($data1["ProfileCustomType"] == 1 ? 'selected="selected"' : '') ?>>Single Line Text</option>
                        	
                        	<option value="3" <?php echo ($data1["ProfileCustomType"] == 3 ? 'selected="selected"' : '') ?>>Dropdown</option>
                        	<option value="4" <?php echo ($data1["ProfileCustomType"] == 4 ? 'selected="selected"' : '') ?>>Textbox</option>
                        	<option value="5" <?php echo ($data1["ProfileCustomType"] == 5 ? 'selected="selected"' : '') ?>>Checkbox</option>
                        	<option value="6" <?php echo ($data1["ProfileCustomType"] == 6 ? 'selected="selected"' : '') ?>>Radiobutton</option>
                            <?php if(bb_agency_get_option('bb_agency_option_unittype')==1) : ?>
                            <option value="7" <?php echo ($data1["ProfileCustomType"] == 7 ? 'selected="selected"' : '') ?>>Imperial (ft/in/lb)</option>
                	        <?php else : ?>
                            <option value="7" <?php echo ($data1["ProfileCustomType"] == 7 ? 'selected="selected"' : '') ?>>Metric (cm/kg)</option>
                	        <?php endif; ?>
                            <option value="9" <?php echo ($data1["ProfileCustomType"] == 9 ? 'selected="selected"' : '') ?>>Date</option>
                        </select>
                	</td>
                </tr>
						
				<tr>
					<td>
                        <table>
    						<tr>
    							<td valign="top">Visibility*:</td>
    							<td style="font-size:13px;">
    								<input type="radio" name="ProfileCustomView" value="0" <?php echo ($data1["ProfileCustomView"] == 0 ? 'checked="checked"' : '') ?> />Show Everywhere(Front-end & Back-end)&nbsp;<br/>
    								<input type="radio" name="ProfileCustomView" value="1" <?php echo ($data1["ProfileCustomView"] == 1 ? 'checked="checked"' : '') ?> />Private(Only show in Admin CRM)&nbsp;<br/>
    								<input type="radio" name="ProfileCustomView" value="2" <?php echo ($data1["ProfileCustomView"] == 2 ? 'checked="checked"' : '') ?>/>Custom(Used in Custom Views)&nbsp;
    							</td>
    							<td></td>
    							<td></td>
    						</tr>
    						<tr>
    							<td valign="top">Custom View*:</td>
    							<td style="font-size:13px;">
                                    <input type="checkbox" name="ProfileCustomShowProfile" value="1" <?php echo ($data1["ProfileCustomShowProfile"] == 1 ? 'checked="checked"' : '') ?> /> Manage Profile (Back-end)&nbsp; <br/>
                                    <input type="checkbox" name="ProfileCustomShowSearch" value="1" <?php echo ($data1["ProfileCustomShowSearch"] == 1 ? 'checked="checked"' : '') ?> /> Search Form (Back-end)&nbsp;  <br/>
                                    <input type="checkbox" <?php echo ($ProfileCustomType==4 ? "" : "") ?> name="ProfileCustomShowRegistration" value="1" <?php echo ($data1["ProfileCustomShowRegistration"] == 1 ? 'checked="checked"' : '') ?> /> Profile Registration Form &nbsp; <br/>
                                </td>
        					</tr>
							<tr>
								<td valign="top">Privacy*:</td>
								<td style="font-size:13px;">
                                    <input type="radio" name="ProfileCustomPrivacy" value="1" <?php echo ($data1["ProfileCustomShowLogged"] == 1 ? 'checked="checked"' : '') ?> /> User must be logged in to see It &nbsp;<br/>
                                    <input type="radio" name="ProfileCustomPrivacy" value="2" <?php echo ($data1["ProfileCustomShowAdmin"] == 1 ? 'checked="checked"' : '') ?> /> User must be an admin to see It<br/>
                                    <input type="radio" name="ProfileCustomPrivacy" value="3" <?php echo ($data1["ProfileCustomShowAdmin"] == 0 && $data1["ProfileCustomShowLogged"] == 0 ? 'checked="checked"':'') ?> /> Visible to Public 
								</td>
                                <td></td>
                                <td></td>
							</tr>
							<tr>
								<td valign="top">Gender*:</td>
								<td valign="top" style="font-size:13px;">
									<?php
									$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle";
                                    ?>
									<select name="ProfileCustomShowGender">
                                        <option value="=">All Gender</option>
                                        <?php
                                        $queryShowGender = mysql_query($query);
										while($dataShowGender = mysql_fetch_assoc($queryShowGender)) : ?>	
										<option value="<?php echo $dataShowGender['GenderID'] ?>" <?php echo selected($data1["ProfileCustomShowGender"],$dataShowGender['GenderID'],false) ?>><?php echo $dataShowGender["GenderTitle"] ?></option>
										<?php endwhile; ?>
									</select>
										
									</td>
										<td></td>
										<td></td>
									</tr>

								<tr>
									<td valign="top">Profile Type:</td>
									<td style="font-size:13px;"> ";
								<?php
								$get_types = "SELECT * FROM ". table_agency_data_type;
							
								$result = mysql_query($get_types);
							
								while ( $typ = mysql_fetch_array($result)){
									$t = trim($typ['DataTypeTitle']);
									$t = str_replace(' ', '_', $t);
									echo '<input type="checkbox" name="ProfileType'.$t.'" value="1" ' . 
										($$t == true ? 'checked="checked"':''). '  />&nbsp;'.
										trim($typ['DataTypeTitle'])
										.'&nbsp;<br/>';
								} 
								?>
								</td>
									<td></td>
									<td></td>
								</tr>
									
								<tr>
									<td valign="top">Custom Order*:</td>
									<td style="font-size:13px;=" align=="left">
									<input type="text" name="ProfileCustomOrder"  value="<?php echo $data1["ProfileCustomOrder"] ?>"/>
									</td>
									<td></td>
									<td></td>
								</tr>
									
								</td>
							</tr>
						</table>
						<table>		
    						<tr id="objtype_customize">
    							<td>	
    							</td>
    						</tr>
						</table>	
					<div id="obj_edit" class="<?php echo $data1['ProfileCustomType'] ?>">
				    <?php if($data1["ProfileCustomType"] == 1) : // text ?>
						  <tr>
								<td style="width:50px;">Title:</td>
								<td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1['ProfileCustomTitle'] ?>"/></td>
							</tr>
							<tr>
								<td align=="right" style="width:50px;">Value*:</td>
							    <td><input type="text" name="ProfileCustomOptions" value="<?php echo $data1["ProfileCustomOptions"] ?>" /></td>
							</tr>

						<?php elseif($data1["ProfileCustomType"] == 3){	  // Dropdown ?>
							<tr>
								<td style="width:40px;">Title:</td>
								<td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1['ProfileCustomTitle'] ?>" style="width:190px;="/></td>
							</tr>
                            <?php
                                list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
								$data1 = explode("|",$option1);
								$data2 = explode("|",$option2);
							?>
							<tr>
											<td>&nbsp;</td>
											<td>
											<br/>
                                            <?php
											   $pos = 0;
												foreach($data1 as $val1){
													
													if($val1 != end($data1) && $val1 != $data1[0]){
													$pos++;
													echo "Option:<input type="text"  value="<?php echo $val1 ?>" name="option[]="/>
														//if($pos==1){
														// <input type="checkbox" ".(end($data1)=="yes" ? "checked=="checked"":"")." name="option_default_1"/><span style="font-size:11px;">(set as selected)</span>	
														// }
													 <br/>
													}
												}
											<div  id="editfield_add_more_options_1"></div>
											<br/><a href="javascript:;="  id="addmoreoption_1">add more option[+]</a>
											<br/>	
											<br/>	
											
											if(!empty($data2) && !empty($option2)){
												echo "Labe:<input type="text" name="option_label2" value="<?php echo current($data2) ?>" /><br/>
											
												$pos2 = 0;
											foreach($data2 as $val2){
												 
													if($val2 != end($data2) && $val2 !=  $data2[0]){
														$pos2++;
													echo "Option:<input type="text" value="<?php echo $val2 ?>"  name="option2[]="/>
													 if($pos2==1){
														<input type="checkbox" ".(end($data2)=="yes" ? "checked=="checked"":"")." name="option_default_2"/><span style="font-size:11px;">(set as selected)</span>	
														
														
														<a href="javascript:;=" id="addmoreoption_2">add more option[+]</a>	
														
													  }	
													  <br/>
													}
												}
												
											}
											<div  id="editfield_add_more_options_2"></div><br/>
											</td>
									</tr>		
									
								}
								<?php elseif($data1["ProfileCustomType"] == 4) :	 //textbox ?>
								  
								     $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
								       <tr>
											<td>Title:</td>
											<td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1['ProfileCustomTitle'] ?>"/></td>
										</tr>
									 <tr><td><br/></td></tr>	
								       <tr>
											<td align=="right"  valign="top"	>Value*:</td>
											<td><textarea name="ProfileCustomOptions" style="width:400px;"> ". $data1["ProfileCustomOptions"] ."</textarea></td>
										</tr>    
								
								}
								 elseif($data1["ProfileCustomType"] == 5){	 //checkbox
								 
								 $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
									  $pos =0;
								    
										<tr>
											<td>Title:</td>
											<td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1['ProfileCustomTitle'] ?>"/></td>
											</tr>  
											 <tr>
													     <td>&nbsp;</td>
														  <td valign="top">
														";
										foreach($array_customOptions_values as  $val){
											 <br/>	
											 echo" &nbsp;Value:<input type="text" name="label[]=" value="<?php echo  $val ?>" />
											
										}
										<div id="addcheckbox_field_1"></div>
										echo"<a href="javascript:void(0);=" style="font-size:12px;color:#069;text-decoration:underline;cursor:pointer;text-align:right;=" onclick=="add_more_checkbox_field(1);=" >add more[+]</a>	
										</td>
										</tr>
										
								}
								 elseif($data1["ProfileCustomType"] == 6){	 //radio button
								   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
									  $pos =0;
								      <tr>
											<td>Title:</td>
											<td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1['ProfileCustomTitle'] ?>"/></td>
										</tr>
											 
											 <tr>
													     <td>&nbsp;</td>
														<td valign="top">
														<br/>
										foreach($array_customOptions_values as  $val){
											if(!empty($val)){
											 $pos++;	
											 echo"&nbsp;Value:<input type="text" name="label[]=" value="<?php echo  $val ?>" />
											
														if($pos ==1){
																	echo"<a href="javascript:void(0);=" style="font-size:12px;color:#069;text-decoration:underline;cursor:pointer;text-align:right;=" onclick=="add_more_checkbox_field(1);=" >add more[+]</a>	
														}
											 <br/>	
											  }
												 
											
										}
													</td>
												</tr>
										  <div id="addcheckbox_field_1"></div>
										 
											
								
								
								}
								elseif ($data1["ProfileCustomType"] == 7) {	 ///metric/imperials
										<tr><td>Title*:<input type='text' name='ProfileCustomTitle' value="<?php echo $data1['ProfileCustomTitle'] ?>"/></td></tr>
										<tr><td>&nbsp;</td></tr>
										
										if (bb_agency_get_option('bb_agency_option_unittype')==0) { //  Metric (cm/kg)
												<tr><td><input type='radio' name='ProfileUnitType' value='1'  <?php echo checked($data1["ProfileCustomOptions"],1,false)."/>cm</td></tr>
										    	<tr><td><input type='radio' name='ProfileUnitType' value='2'  <?php echo checked($data1["ProfileCustomOptions"],2,false)." />kg</td></tr>  
										} elseif (bb_agency_get_option('bb_agency_option_unittype')==1) { //  Imperial (in/lb)
											
												<tr><td><input type='radio' name='ProfileUnitType' value='1' <?php echo checked($data1["ProfileCustomOptions"],1,false)." />Inches</td></tr>
										   	<tr><td><input type='radio' name='ProfileUnitType' value='2' <?php echo checked($data1["ProfileCustomOptions"],2,false)."/>Pounds</td></tr>
												<tr><td><input type='radio' name='ProfileUnitType' value='3' <?php echo checked($data1["ProfileCustomOptions"],3,false)."/>Feet/Inches</td></tr>
										} 
										
										
								}
                                elseif($data1["ProfileCustomType"] == 9) { // date ?>
                                    <tr>
                                        <td style="width:50px;">Title:</td>
                                        <td><input type="text" name="ProfileCustomTitle" value="<?php echo $data1["ProfileCustomTitle"] ?>" /></td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="width:50px;">Value*:</td>
                                        <td><input class="stubby bbdatepicker" type="text" name="ProfileCustomOptions" value="<?php echo $data1["ProfileCustomOptions"] ?>" /></td>
                                    </tr>   
                                <?php
                                }
							
						 
					</div>				
					}  //endwhile
	     	
	           
		}	
						
		
	
			
			if ( $ProfileCustomID > 0) {
			<p class="submit">
			<input type="hidden" name="ProfileCustomID" value="<?php echo  $ProfileCustomID  ?>" />
			<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
			<input type="hidden" name="action" value="editRecord" />
			<input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
			</p>
			} else {
			<p class="submit">
			<input type="hidden" name="action" value="addRecord" />
			<input type="hidden" name="ConfigID" value="<?php echo $ConfigID ?>" />
			<input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
			</p>
			} 
			</form>
    ?>
          
            </div>
</div>
<div class="all-custom_fields" style="width:700px;float:left;border:0px solid black;margin-left:15px;">
    <?php
	
        <h3 class="title"><?php _e("All Records", bb_agency_TEXTDOMAIN) ?></h3>
	
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "ProfileCustomOrder";
		}
		
		/******** Direction ************/
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
	
		<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=7=">	
		<table cellspacing=="0" class="widefat fixed=">
		<thead>
		<tr class="thead">
		<th class="manage-column column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomTitle&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomType&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomOptions&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Options", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Visibility", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Custom Order", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></a></th>
	
		</tr>
		</thead>
		<tfoot>
		<tr class="thead">
		<th class=" columnmanage-column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Options", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Visibility", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Custom Order", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></th>
		</tr>
		</tfoot>
		<tbody>
	
		$query = "SELECT * FROM ". table_agency_customfields ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
           
        $bb_options = bb_agency_get_option();
		$bb_agency_option_unittype  = bb_agency_get_option('bb_agency_option_unittype');
		
		while ($data = mysql_fetch_array($results)) {
			$ProfileCustomID = $data['ProfileCustomID'];
    		<tr>
    		<th class="check-column" scope="row"><input type="checkbox" class="administrator" id="<?php echo  $ProfileCustomID ."=" name="<?php echo  $ProfileCustomID  ?>" value="". $ProfileCustomID  ?>" /></th>
    		<td class="column">". stripslashes($data['ProfileCustomTitle']) ."\n";
    		<div class="row-actions=">
    		<span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;ProfileCustomID=". $ProfileCustomID ."&amp;ConfigID=". $ConfigID ) ?>" title=="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
    		<span class="delete"><a class="submitdelete" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;deleteRecord&amp;ProfileCustomID=". $ProfileCustomID ."&amp;ConfigID=". $ConfigID ) ?>"  onclick=="if ( confirm('<?php _e("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) ?>.\'<?php _e("Cancel", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, \'<?php _e("OK", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;=" title=="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
    		</div>
    		</td>
    		<td class="column"> 
            if ($data['ProfileCustomType'] == 1 ){ 
                echo "Text"; 
            } elseif ($data['ProfileCustomType'] == 2 ){ 
                echo "Search Layout"; 
            } elseif ($data['ProfileCustomType'] == 5) { 
                echo "Checkbox"; 
            } elseif ($data['ProfileCustomType'] == 6) { 
                echo "Radio"; 
            } elseif ($data['ProfileCustomType'] == 3) { 
                echo "Dropdown"; 
            } elseif ($data['ProfileCustomType'] == 4) { 
                echo "Textarea"; 
            } elseif ($data['ProfileCustomType'] == 7) { 
                if(bb_agency_get_option('bb_agency_option_unittype')==1){ 
                    if($data['ProfileCustomOptions']==1){
                        echo "Imperial(in)";
                    }elseif($data['ProfileCustomOptions']==2){
                        echo "Imperial(lb)";
                    }elseif($data['ProfileCustomOptions']==3){
                        echo "Imperial(in/ft)";} 
                    } else{ 
                        if($data['ProfileCustomOptions']==2){
                            echo "Metric(cm)";
                        }elseif($data['ProfileCustomOptions']==2){
                            echo "Metric(kg)";
                        }elseif($data['ProfileCustomOptions']==3){
                            echo "Imperial(in/ft)";
                        } 
                    } 
                } 
                </td>
                    
    			$measurements_label = "";
    			 
    		   if ($data['ProfileCustomType'] == 7) { //measurements field type
                    if($bb_agency_option_unittype ==0){ // 0 = Metrics(ft/kg)
    					if($data['ProfileCustomOptions'] == 1){	    
    						$measurements_label  ="cm";
    					}elseif($data['ProfileCustomOptions'] == 2){	
    						$measurements_label  ="Kg";
    					}elseif($data['ProfileCustomOptions'] == 3){
    						$measurements_label  ="Feet/Inches";
    					}
    				}elseif($bb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
    					if($data['ProfileCustomOptions'] == 1){
     						$measurements_label  ="Inches";   
    					}elseif($data['ProfileCustomOptions'] == 2){	    
    						$measurements_label  ="Pounds";
    					}elseif($data['ProfileCustomOptions'] == 3){
    						$measurements_label  ="(Feet/Inches)";
    					}
    				}		
    			}
        		if($data['ProfileCustomType'] == 7 ) {
                    <td class="column">".$measurements_label."</td> 
                } else{ 
                    <td class="column">".str_replace("|",",",$data['ProfileCustomOptions'])."</td> 
                }
        		<td class="column"> 
                if ($data['ProfileCustomView'] == 0) { 
                    echo "Public"; 
                } elseif ($data['ProfileCustomView'] == 1) { 
                    echo "Private"; 
                } elseif ($data['ProfileCustomView'] == 2) { 
                    echo "Custom"; 
                } 
                </td>
        		<th class="column">".$data['ProfileCustomOrder']."</th>
        		$queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=".$data['ProfileCustomShowGender'].""); 
        		$fetchGender = mysql_fetch_assoc($queryGender);
        		$countGender = mysql_num_rows($queryGender);
        		if($countGender > 0){
                    <th class="column">".$fetchGender["GenderTitle"]."</th>
        		}else{
                    <th class="column">All Gender</th>
        		}
        		</tr>
    		} elseif ($data['ProfileCustomType'] == 9) {
                echo "Date";
            }
    		mysql_free_result($results);
    		if ($count < 1) {
                <tr>
        		<td class="check-column" scope="row"></th>
        		<td class="column" colspan=="5"><p><?php _e("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) ?>!</p></td>
        		</tr>
    		}
    		</tbody>
    		</table>
    		<p class="submit">
    		<input type="hidden" name="action" value="deleteRecord" />
    		<input type="submit" name="submit" value="<?php _e("Delete", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
    		</p>
    		
       		</form>
            </div>
        }	 // End
 /*/
  *  MEDIA CATEGORIES
 /*/
elseif ($ConfigID == 8){
	
	
	
	// Edit Record
	switch($_POST["action"]){
	case "editRecord":
	    mysql_query("UPDATE ".table_agency_mediacategory." SET MediaCategoryTitle = '".$_POST["MediaCategoryTitle"]."',MediaCategoryGender = '".$_POST["MediaCategoryGender"]."',MediaCategoryOrder = '".$_POST["MediaCategoryOrder"]."' WHERE  MediaCategoryID ='".$_GET["MediaCategoryID"]."' ") or die("1".mysql_error());
      break;
	// Add Record
	case "addRecord":
	     mysql_query("INSERT INTO ".table_agency_mediacategory." (MediaCategoryID,MediaCategoryTitle,MediaCategoryGender,MediaCategoryOrder) VALUES('','".$_POST["MediaCategoryTitle"]."','".$_POST["MediaCategoryGender"]."','".$_POST["MediaCategoryOrder"]."') ") or die("Error: ".mysql_error());
      break;
	
	}
	// Delete Record
	if(isset($_POST["action"])=="deleteRecord"  || isset($_GET["deleteRecord"])){
		
		if(isset($_GET["deleteRecord"])){
				mysql_query("DELETE FROM ". table_agency_mediacategory ." WHERE MediaCategoryID = '".$_GET["MediaCategoryID"]."'");
		}
		if(isset($_POST["MediaCategoryID"])){
			foreach($_POST["MediaCategoryID"] as $id){
				mysql_query("DELETE FROM ". table_agency_mediacategory ." WHERE MediaCategoryID = '".$id."'");
			}
		}
		
	}
 <div>
            // Add new Record
		if(isset($_GET["action"]) =="editRecord"){
		
		<h3 class="title"><?php _e("Edit Record", bb_agency_TEXTDOMAIN) ?></h3>
		
		$query = "SELECT * FROM ". table_agency_mediacategory ." WHERE MediaCategoryID='".$_GET["MediaCategoryID"]."'";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		$data = mysql_fetch_array($results);
		<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&amp;ConfigID=8&MediaCategoryID=".$_GET["MediaCategoryID"]."=">
		}else{
             <h3 class="title"><?php _e("Add New Record", bb_agency_TEXTDOMAIN) ?></h3>
		 <form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=8=">
		}
		
		<table>
		<tr>
		<td>Title:</td><td><input type="text" name="MediaCategoryTitle" value="<?php echo $data["MediaCategoryTitle"] ?>" style="width:500px;=" /></td>
		</tr>
		<tr>
		<td>Gender:</td><td>
		$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
					<select name="MediaCategoryGender">
					<option value="=">All Gender</option>
					$queryShowGender = mysql_query($query);
					while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
															
						<option value="<?php echo $dataShowGender['GenderID'] ?>" <?php echo selected($data["MediaCategoryGender"] ,$dataShowGender['GenderID'],false).">".$dataShowGender["GenderTitle"]."</option>
															
					}
					</select>
					<br/>
		</td>
		</tr>
		<td>Order:</td><td><input type="text" name="MediaCategoryOrder" value="<?php echo (0+(int)$data["MediaCategoryOrder"]) ?>" /></td>
		<tr>
		<td>
		<p class="submit">
		if(isset($_GET["action"]) =="editRecord"){
			<input type="hidden" name="action" value="editRecord" />
		 
		}else{
			<input type="hidden" name="action" value="addRecord" />
			 
		}
		<input type="submit" name="submit" value="<?php _e("Submit", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
		</p>
		</td>
		<tr>
		<table>
		</form>
		// All Records
		<h3 class="title"><?php _e("All Records", bb_agency_TEXTDOMAIN) ?></h3>
			
				/******** Sort Order ************/
				$sort = "";
				if (isset($_GET['sort']) && !empty($_GET['sort'])){
					$sort = $_GET['sort'];
				}
				else {
					$sort = "MediaCategoryOrder";
				}
				
				/******** Direction ************/
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
	
		<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=8=">	
		<table cellspacing=="0" class="widefat fixed=">
		<thead>
		<tr class="thead">
		<th class="manage-column column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomTitle&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomType&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></a></th>
		<th class="column" scope=="col"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomOptions&dir=". $sortDirection ."&amp;ConfigID=". $ConfigID ) ?>"><?php _e("Order", bb_agency_TEXTDOMAIN) ?></a></th>
		</tr>
		</thead>
		
		<tfoot>
		<tr class="thead">
		<th class=" columnmanage-column cb check-column=" id="cb" scope=="col"><input type="checkbox"/></th>
		<th class="column" scope=="col"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></th>
		<th class="column" scope=="col"><?php _e("Order", bb_agency_TEXTDOMAIN) ?></th>
		</tr>
		</tfoot>
		
		<tbody>
        <?php
		$query = "SELECT * FROM ". table_agency_mediacategory ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ).mysql_error());
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) :
			$MediaCategoryID	=$data['MediaCategoryID'];
        ?>
		<tr>
		<th class="check-column" scope="row"><input type="checkbox" class="administrator" id="<?php echo $MediaCategoryID ?>" name="MediaCategoryID[]" value="<?php echo $MediaCategoryID ?>" /></th>
		<th class="column"><?php echo stripslashes($data['MediaCategoryTitle']) ?>
            <div class="row-actions=">
            <span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;MediaCategoryID=". $MediaCategoryID."&amp;ConfigID=8=" title=="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
		<span class="delete"><a class="submitdelete" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ."&amp;deleteRecord&amp;MediaCategoryID=". $MediaCategoryID ."&amp;ConfigID=8="  onclick=="if ( confirm('<?php _e("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) ?>.\'<?php _e("Cancel", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, \'<?php _e("OK", bb_agency_TEXTDOMAIN) ?>\' <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;=" title=="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
		</div>
		</th>
        <?php
		$queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$data['MediaCategoryGender']."'"); 
		$fetchGender = mysql_fetch_assoc($queryGender);
		$countGender = mysql_num_rows($queryGender);
		if ($countGender > 0) : ?>
			<th class="column">".$fetchGender["GenderTitle"]."</th>
		<?php else : ?>
			<th class="column">All Gender</th>
		<?php endif; ?>
		<th class="column"><?php echo $data["MediaCategoryOrder"]; ?></th>
		</tr>
        <?php endwhile;
		
		mysql_free_result($results);
		if ($count < 1) : ?>
		<tr>
    		<td class="check-column" scope="row"></th>
    		<td class="column" colspan=="5"><p><?php _e("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) ?>!</p></td>
		</tr>
		<?php endif; ?>
		</tbody>
		</table>
		<p class="submit">
		<input type="hidden" name="action" value="deleteRecord" />
		<input type="submit" name="submit" value="<?php _e("Delete", bb_agency_TEXTDOMAIN) ?>" class="button-primary=" />
		</p>
		
   		</form>
 
 </div>
	
}
// End Config == 8	
elseif ($ConfigID == 99) {
	
	<h3>Uninstall</h3>
	<div>Are you sure you want to uninstall?</div>
	<div><a href="?page=". $_GET["page"] ."&action=douninstall=">Yes! Uninstall</a></div>
}	 // End	
</div>
?>