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
    <a class="button-<?php echo $ConfigID == $id ? 'primary' : 'secondary' ?>" href="<?php echo admin_url("admin.php?page={$page}&ConfigID={$id}") ?>"><?php echo $label ?></a>
<?php endforeach; ?>
</p>
<?php

if (!empty($_REQUEST['action']) ) {
	if ($_REQUEST['action'] == 'douninstall') {
		bb_agency_uninstall();
	}
}

echo "<h2> &raquo; ";
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
echo "</h2>\n";

if ($ConfigID == 0) {
	
// *************************************************************************************************** //
// Overview Page
	// Core Settings
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Configuration", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("The following settings modify the core BB Agency settings.", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Features", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=1\" title=\"". __("Settings", bb_agency_TEXTDOMAIN) . "\">". __("Settings", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Access this area to manage all of the core settings including layout types, privacy settings and more", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Style", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=10\" title=\"". __("Style", bb_agency_TEXTDOMAIN) . "\">". __("Style", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Manage the stylesheet (CSS) controlling the category and profile layouts", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
    echo "</div>\n";
    echo "<hr />\n";

	if (function_exists('bb_agencyinteract_settings')) {
	// RB Agency Interact Settings
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Interactive Settings", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("These settings modify the behavior of the RB Agency Interactive plugin.", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Interactive Settings", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=11\" title=\"". __("Interactive Settings", bb_agency_TEXTDOMAIN) . "\">". __("Settings", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Access this area to manage all of the core settings including layout types, privacy settings and more", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
 
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Subscription Rates", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=12\" title=\"". __("Subscription Rates", bb_agency_TEXTDOMAIN) . "\">". __("Subscription Rates", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Manage the subscription rate tiers and descriptions", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
 
    echo "</div>\n";
    echo "<hr />\n";
	}
	// Drop Down Fields
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Customize Profile Fields", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("You have full control over all drop downs and ability to add new custom fields of your own.", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Profile Categories", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=6\" title=\"". __("Profile Categories", bb_agency_TEXTDOMAIN) . "\">". __("Profile Categories", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Choose custom category types to classify profiles", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Custom Fields", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=7\" title=\"". __("Custom Fields", bb_agency_TEXTDOMAIN) . "\">". __("Custom Fields", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Add public and private custom fields", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Gender", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=5\" title=\"". __("Gender", bb_agency_TEXTDOMAIN) . "\">". __("Gender", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Manage preset Gender choices", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    </div>\n";
    echo "</div>\n";
    echo "<hr />\n";

	// Uninstall
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Uninstall", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("Uninstall BB Agency software and completely remove all data", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "    <div class=\"boxlink\">\n";
    echo "      <a class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=99\" title=\"". __("Uninstall", bb_agency_TEXTDOMAIN) . "\">". __("Uninstall", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "    </div>\n";
    echo "</div>\n";

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
                'bb_agency_option_privacy',
                'bb_agency_option_pregnant',
                'bb_agency_option_layoutprofile'
            ) as $key) {
            $value = isset($options[$key]) ? $options[$key] : 0;
            bb_agency_update_option($key, $value);
        }
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
    echo "<h3>". __("Interactive Settings", bb_agency_TEXTDOMAIN) . "</h3>\n";
		echo "<form method=\"post\" action=\"options.php\">\n";
		settings_fields( 'bb-agencyinteract-settings-group' ); 
		$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
		   // Facebook Connect integration
		   	 $bb_agencyinteract_option_fb_registerallow = $bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_registerallow'];
	       	if (empty($bb_agencyinteract_option_fb_registerallow)) { $bb_agencyinteract_option_fb_registerallow = "1"; }
			
		
				 
		 echo "<table class=\"form-table\">\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Database Version', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"\" value=\"". bb_agencyinteract_VERSION ."\" disabled /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Display', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"bb_agencyinteract_options[bb_agencyinteract_option_profilemanage_sidebar]\" value=\"1\" ".checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_sidebar'], 1,false)."/> ". __("Show Sidebar on Member Management/Login Pages", bb_agency_TEXTDOMAIN) ."<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">&nbsp;</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"bb_agencyinteract_options[bb_agencyinteract_option_profilemanage_toolbar]\" value=\"1\" ".checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_profilemanage_toolbar'], 1,false)."/> ". __("Hide Toolbar on All Pages", bb_agency_TEXTDOMAIN) ."<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Registration Process', bb_agency_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Show User Registration when creating Profiles', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"bb_agencyinteract_options[bb_agencyinteract_option_useraccountcreation]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_useraccountcreation'], 0,false) ."> ". __("Yes, show username and password fields", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_useraccountcreation'], 1,false) ."> ". __("No, do not show username and password fields", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('New Profile Registration', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"bb_agencyinteract_options[bb_agencyinteract_option_registerallow]\" value=\"1\" ".checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallow'], 1,false)."/> Users may register profiles (uncheck to prevent self registration)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Enable registration of Agent/Producer', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 
		 echo "     <select name=\"bb_agencyinteract_options[bb_agencyinteract_option_registerallowAgentProducer]\">\n";
		 echo "       <option value=\"1\" ". ($bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallowAgentProducer'] == 1 ? 'selected="selected"':'') ."> ". __("Show", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"0\" ". ($bb_agencyinteract_options_arr['bb_agencyinteract_option_registerallowAgentProducer'] == 0 ? 'selected="selected"':'') ."> ". __("Hide", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";

		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Email Confirmation', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"bb_agencyinteract_options[bb_agencyinteract_option_registerconfirm]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerconfirm'], 0,false) ."> ". __("Password Auto-Generated (sent via email)", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerconfirm'], 1,false) ."> ". __("Password Self-Generated", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('New Profile Approval', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"bb_agencyinteract_options[bb_agency_option_useraccountcreation]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerapproval'], 0,false) ."> ". __("Manually Approved", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_registerapproval'], 1,false) ."> ". __("Automatically Approved", bb_agency_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Facebook Login/Registration Integration', bb_agency_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Login &amp Registration', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"bb_agencyinteract_options[bb_agencyinteract_option_fb_registerallow]\" value=\"1\" ". ($bb_agencyinteract_options_arr["bb_agencyinteract_option_fb_registerallow"] == 1 ? 'checked=\"checked\"':'') ."/> Users may login/register profiles using facebook (uncheck to disable feature)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Application ID', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"bb_agencyinteract_options[bb_agencyinteract_option_fb_app_id]\" value=\"".$bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_app_id']."\" />";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Application Secret', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"bb_agencyinteract_options[bb_agencyinteract_option_fb_app_secret]\" value=\"".$bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_app_secret']."\" />";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Login Redirect URI', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"bb_agencyinteract_options[bb_agencyinteract_option_fb_app_login_uri]\" value=\"".$bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_app_login_uri']."\" />(default: ".network_site_url("/")."profile-login/ )";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Registration Redirect URI', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"bb_agencyinteract_options[bb_agencyinteract_option_fb_app_register_uri]\" value=\"".$bb_agencyinteract_options_arr['bb_agencyinteract_option_fb_app_register_uri']."\" />(default: ".network_site_url("/")."profile-register/ )";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Membership Subscription', bb_agency_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Notifications', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"bb_agencyinteract_options[bb_agencyinteract_option_subscribeupsell]\" value=\"1\" "; checked((int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribeupsell'], 1,false); echo "/> Display Upsell Messages for Subscription)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Embed Overview Page ID', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"bb_agencyinteract_options[bb_agencyinteract_option_overviewpagedetails]\" value=\"". $bb_agencyinteract_options_arr['bb_agencyinteract_option_overviewpagedetails'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Embed Registration Page ID', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"bb_agencyinteract_options[bb_agencyinteract_option_subscribepagedetails]\" value=\"". $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepagedetails'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('PayPal Email Address', bb_agency_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"bb_agencyinteract_options[bb_agencyinteract_option_subscribepaypalemail]\" value=\"". $bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribepaypalemail'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo "</table>\n";
		 echo "<input type=\"submit\" class=\"button-primary\" value=\"". __('Save Changes') ."\" />\n";
		 
		 echo "</form>\n";
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
			$error .= "<b><i>". __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
		
				// Create Record
				$insert = "INSERT INTO " . table_agencyinteract_subscription_rates . " (SubscriptionRateTitle,SubscriptionRateType,SubscriptionRateText,SubscriptionRatePrice,SubscriptionRateTerm) VALUES ('" . $wpdb->escape($SubscriptionRateTitle) . "','" . $wpdb->escape($SubscriptionRateType) . "','" . $wpdb->escape($SubscriptionRateText) . "','" . $wpdb->escape($SubscriptionRatePrice) . "','" . $wpdb->escape($SubscriptionRateTerm) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
			}
		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
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
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"); 
			}
		break;
		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $SubscriptionRateID) {
			  if (is_numeric($SubscriptionRateID)) {
				// Verify Record
				$queryDelete = "SELECT SubscriptionRateID, SubscriptionRateTitle FROM ". table_agencyinteract_subscription_rates ." WHERE SubscriptionRateID =  \"". $SubscriptionRateID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_agencyinteract_subscription_rates . " WHERE SubscriptionRateID = \"". $SubscriptionRateID ."\"";
					$results = $wpdb->query($delete);
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['SubscriptionRateTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
						
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
		$queryDelete = "SELECT SubscriptionRateID, SubscriptionRateTitle FROM ". table_agencyinteract_subscription_rates ." WHERE SubscriptionRateID =  \"". $SubscriptionRateID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {

	
			// Remove Record
			$delete = "DELETE FROM " . table_agencyinteract_subscription_rates . " WHERE SubscriptionRateID = \"". $SubscriptionRateID ."\"";
			$results = $wpdb->query($delete);
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['SubscriptionRateTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
				
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
		
			echo "<h3 class=\"title\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
	
		} else {
		
			$SubscriptionRateID		= 0;
			$SubscriptionRateTitle	="";
			$SubscriptionRateType	="";
			$SubscriptionRateText	="";
			$SubscriptionRatePrice	= 0;
			$SubscriptionRateTerm	= 1;
			
			
			echo "<h3>". sprintf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
		} // Has Subscription rate or not
  } // Edit record
		
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=". $ConfigID) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Title", bb_agency_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"SubscriptionRateTitle\" name=\"SubscriptionRateTitle\" value=\"". $SubscriptionRateTitle ."\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Type", bb_agency_TEXTDOMAIN) ." *:</th>\n";
	echo "        <td><select id=\"SubscriptionRateType\" name=\"SubscriptionRateType\">\n";
	echo "			  <option value=\"0\"". selected(0, $SubscriptionRateType) .">Standard</option>\n";
	echo "          </select></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Text", bb_agency_TEXTDOMAIN) ." *:</th>\n";
	echo "        <td><textarea id=\"SubscriptionRateText\" name=\"SubscriptionRateText\">". $SubscriptionRateText ."</textarea></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Package Rate", bb_agency_TEXTDOMAIN) ." *:</th>\n";
	echo "        <td><input type=\"text\" id=\"SubscriptionRatePrice\" name=\"SubscriptionRatePrice\" value=\"". $SubscriptionRatePrice ."\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Subscription Duration", bb_agency_TEXTDOMAIN) ." *:</th>\n";
	echo "        <td><select id=\"SubscriptionRateTerm\" name=\"SubscriptionRateTerm\">\n";
	echo "			  <option value=\"1\"". selected(1, $SubscriptionRateTerm) .">1 Month</option>\n";
	echo "			  <option value=\"2\"". selected(2, $SubscriptionRateTerm) .">2 Months</option>\n";
	echo "			  <option value=\"3\"". selected(3, $SubscriptionRateTerm) .">3 Months</option>\n";
	echo "			  <option value=\"6\"". selected(6, $SubscriptionRateTerm) .">6 Months</option>\n";
	echo "			  <option value=\"12\"". selected(12, $SubscriptionRateTerm) .">1 Year</option>\n";
	echo "			  <option value=\"24\"". selected(24, $SubscriptionRateTerm) .">2 Years</option>\n";
	echo "			  <option value=\"36\"". selected(36, $SubscriptionRateTerm) .">3 Years</option>\n";
	echo "          </select></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	if ( $SubscriptionRateID > 0) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"SubscriptionRateID\" value=\"". $SubscriptionRateID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
	echo "  <h3 class=\"title\">". __("All Records", bb_agency_TEXTDOMAIN) ."</h3>\n";
	
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
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=SubscriptionRateTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=SubscriptionRateType&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Type", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=SubscriptionRatePrice&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Rate/Term", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=SubscriptionRateText&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Text", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Type", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Rate/Term", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Price", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_agencyinteract_subscription_rates ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$SubscriptionRateID	=$data['SubscriptionRateID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $SubscriptionRateID ."\" name=\"". $SubscriptionRateID ."\" value=\"". $SubscriptionRateID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['SubscriptionRateTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;SubscriptionRateID=". $SubscriptionRateID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">". __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;SubscriptionRateID=". $SubscriptionRateID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) . ".\'". __("Cancel", bb_agency_TEXTDOMAIN) . "\' ". __("to stop", bb_agency_TEXTDOMAIN) . ", \'". __("OK", bb_agency_TEXTDOMAIN) . "\' ". __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">". __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"column\">"; if ($data['SubscriptionRateType'] == 0) { echo "Standard"; } echo "</td>\n";
		echo "        <td class=\"column\">$". $data['SubscriptionRatePrice'] ." / ". $data['SubscriptionRateTerm'] ." Month Term</td>\n";
		echo "        <td class=\"column\">". $data['SubscriptionRateText'] ."</td>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"5\"><p>". __("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";
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
		echo "<h1>OVERRIDE</h1>";
	$bb_options = bb_agency_get_option();
		if (bb_agency_get_option('bb_agency_option_defaultcss')) { $bb_agency_stylesheet_string = bb_agency_get_option('bb_agency_option_defaultcss'); }
	}
	echo "		<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "		<table class=\"form-table\">\n";
	echo "		<tbody>\n";
	echo "		 <tr valign=\"top\">\n";
	echo "		   <th scope=\"row\">". __('Stylesheet', bb_agency_TEXTDOMAIN); echo "</th>\n";
	echo "		   <td><textarea name=\"bb_agency_stylesheet_string\" style=\"width: 100%; height: 300px;\" />". $bb_agency_stylesheet_string ."</textarea></td>\n";
	echo "		 </tr>\n";
	echo "		</table>\n";
	echo "		<input type=\"hidden\" name=\"action\" value=\"saveChanges\" />\n";
	echo "		<input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "		<input type=\"submit\" class=\"button-primary\" value=\"". __('Save Changes'); echo "\" />\n";
	echo "		</form>\n";
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
			$error .= "<b><i>". __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
		
				// Create Record
				$insert = "INSERT INTO " . table_agency_data_gender . " (GenderTitle) VALUES ('" . $wpdb->escape($GenderTitle) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
			}
		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
				$update = "UPDATE " . table_agency_data_gender . " 
							SET 
								GenderTitle='" . $wpdb->escape($GenderTitle) . "'
							WHERE GenderID='$GenderID'";
				$updated = $wpdb->query($update);
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"); 
			}
		break;
		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $GenderID) {
			  if (is_numeric($GenderID)) {
				// Verify Record
				$queryDelete = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID =  \"". $GenderID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_agency_data_gender . " WHERE GenderID = \"". $GenderID ."\"";
					$results = $wpdb->query($delete);
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['GenderTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
						
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
		$queryDelete = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID =  \"". $GenderID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_agency_data_gender . " WHERE GenderID = \"". $GenderID ."\"";
			$results = $wpdb->query($delete);
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['GenderTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
				
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
		
			echo "<h3 class=\"title\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
		}
  } else {
		
			$GenderID		= 0;
			$GenderTitle	="";
			$GenderTag	="";
			
			echo "<h3>". sprintf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
  }
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Title", bb_agency_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"GenderTitle\" name=\"GenderTitle\" value=\"". $GenderTitle ."\" /></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	if ( $GenderID > 0) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"GenderID\" value=\"". $GenderID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
	echo "  <h3 class=\"title\">". __("All Records", bb_agency_TEXTDOMAIN) ."</h3>\n";
	
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
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=GenderTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_agency_data_gender ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$GenderID	=$data['GenderID'];
			
			
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $GenderID ."\" name=\"". $GenderID ."\" value=\"". $GenderID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['GenderTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;GenderID=". $GenderID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">". __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;GenderID=". $GenderID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) . ".\'". __("Cancel", bb_agency_TEXTDOMAIN) . "\' ". __("to stop", bb_agency_TEXTDOMAIN) . ", \'". __("OK", bb_agency_TEXTDOMAIN) . "\' ". __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">". __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"3\"><p>". __("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";
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
			$error .= "<b><i>". __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
		
				// Create Record
				$insert = "INSERT INTO " . table_agency_data_type . " (DataTypeTitle,DataTypeTag) VALUES ('" . $wpdb->escape($DataTypeTitle) . "','" . $wpdb->escape($DataTypeTag) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
			}
		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
				$update = "UPDATE " . table_agency_data_type . " 
							SET 
								DataTypeTitle='" . $wpdb->escape($DataTypeTitle) . "',
								DataTypeTag='" . $wpdb->escape($DataTypeTag) . "' 
							WHERE DataTypeID='$DataTypeID'";
				$updated = $wpdb->query($update);
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"); 
			}
		break;
		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $DataTypeID) {
			  if (is_numeric($DataTypeID)) {
				// Verify Record
				$queryDelete = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID =  \"". $DataTypeID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_agency_data_type . " WHERE DataTypeID = \"". $DataTypeID ."\"";
					$results = $wpdb->query($delete);
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['DataTypeTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
						
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
		$queryDelete = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID =  \"". $DataTypeID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_agency_data_type . " WHERE DataTypeID = \"". $DataTypeID ."\"";
			$results = $wpdb->query($delete);
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['DataTypeTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
				
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
		
			echo "<h3 class=\"title\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
		}
  } else {
		
			$DataTypeID		= 0;
			$DataTypeTitle	="";
			$DataTypeTag	="";
			
			echo "<h3>". sprintf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></p>\n";
  }
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Title", bb_agency_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"DataTypeTitle\" name=\"DataTypeTitle\" value=\"". $DataTypeTitle ."\" /></td>\n";
	echo "    </tr>\n";
	if ( $DataTypeID > 0) {
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Slug", bb_agency_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"DataTypeTag\" name=\"DataTypeTag\" value=\"". $DataTypeTag ."\" /></td>\n";
	echo "    </tr>\n";
	} 
	echo "  </tbody>\n";
	echo "</table>\n";
	if ( $DataTypeID > 0) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"DataTypeID\" value=\"". $DataTypeID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
	echo "  <h3 class=\"title\">". __("All Records", bb_agency_TEXTDOMAIN) ."</h3>\n";
	
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
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=DataTypeTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=DataTypeTag&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Slug", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Slug", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_agency_data_type ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$DataTypeID	=$data['DataTypeID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $DataTypeID ."\" name=\"". $DataTypeID ."\" value=\"". $DataTypeID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['DataTypeTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;DataTypeID=". $DataTypeID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">". __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;DataTypeID=". $DataTypeID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) . ".\'". __("Cancel", bb_agency_TEXTDOMAIN) . "\' ". __("to stop", bb_agency_TEXTDOMAIN) . ", \'". __("OK", bb_agency_TEXTDOMAIN) . "\' ". __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">". __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"column\">". $data['DataTypeTag'] ."</td>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"3\"><p>". __("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";
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
			$error .= "<b><i>". __(LabelSingular ." name is required", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
				echo "<h3 style=\"width:430px;\">". sprintf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>	";
				echo " <div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
				echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >".sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN)." *</strong></span></h3>";
				echo " <div class=\"inside\"> ";	
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
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", bb_agency_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
				echo "<h3 style=\"width:430px;\">". sprintf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>	";
				echo " <div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
				echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >".sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN)." *</strong></span></h3>";
				echo " <div class=\"inside\"> ";		
			}   
		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", bb_agency_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
				 echo "<h3 style=\"width:430px;\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>
			     		<div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
				 echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></span></h3>";
				 echo" <div class=\"inside\"> ";
			} else {
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
	
                 
				echo "<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", bb_agency_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"; 
                        echo "<h3 style=\"width:430px;\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>
			     		<div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
				 echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></span></h3>";
				 echo" <div class=\"inside\"> ";
			}
		break;
		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $ProfileCustomID) {
			  if (is_numeric($ProfileCustomID)) {
				// Verify Record
				$queryDelete = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomID =  \"". $ProfileCustomID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_agency_customfields . " WHERE ProfileCustomID = \"". $ProfileCustomID ."\"";
					$results = $wpdb->query($delete);
					
					// Rmove from Custom Types
					$delete_sql = "DELETE FROM " . table_agency_customfields_types . 
					" WHERE ProfileCustomID='$ProfileCustomID'";
					$deleted = mysql_query($delete_sql) or die(mysql_error());
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['ProfileCustomTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
	                  
				} // while
			  } // it was numeric
			} // for each
			    		echo "<h3 style=\"width:430px;\">". sprintf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>	";
					echo " <div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
					echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >".sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN)." *</strong></span></h3>";
					echo " <div class=\"inside\"> ";	
		break;
		
		} // Switch
			
  } // Action Post
  elseif (isset($_GET["deleteRecord"])) {
	
	$ProfileCustomID = $_GET['ProfileCustomID'];
	  if (is_numeric($ProfileCustomID)) {
		// Verify Record
		$queryDelete = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomID =  \"". $ProfileCustomID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_agency_customfields . " WHERE ProfileCustomID = \"". $ProfileCustomID ."\"";
			$results = $wpdb->query($delete);

			// Rmove from Custom Types
			$delete_sql = "DELETE FROM " . table_agency_customfields_types . 
			" WHERE ProfileCustomID='$ProfileCustomID'";
			$deleted = mysql_query($delete_sql) or die(mysql_error());
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['ProfileCustomTitle'] ."</strong> deleted successfully", bb_agency_TEXTDOMAIN) ."!</p></div>\n";
			echo "<h3 style=\"width:430px;\">". sprintf(__("Create New  %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>	
			<div class=\"postbox \"  style=\"width:430px;float:left;border:0px solid black;\">
			<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >".sprintf(__("Fill in the form below to add a new record %1$s", bb_agency_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN)." *</strong></span></h3>
			<div class=\"inside\"> ";
		
				
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
		
			echo " 
		<h3 style=\"width:430px;\">". sprintf(__("Edit %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>	
		<div class=\"postbox\"  style=\"width:430px;float:left;border:0px solid black;\">";
		 echo"<h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></span></h3>";
		 echo" <div class=\"inside\"> ";
		
	      
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
			
			echo "\n";
		echo " 
		<h3 style=\"width:430px;\">". sprintf(__("Create New %1$s", bb_agency_TEXTDOMAIN), LabelPlural) ."</h3>
		<div class=\"postbox \"  style=\"width:430px;float:left;border:0px solid black;\">
    		 <h3 class=\"hndle\" style=\"margin:10px;font-size:11px;\"><span >". __("Make changes in the form below to edit a ", bb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", bb_agency_TEXTDOMAIN) ." *</strong></span></h3>
        <div class=\"inside\"> ";	
  	}
	if(isset($_GET["action"]) == "editRecord"){
		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&ProfileCustomID=".$_GET["ProfileCustomID"]."&ConfigID=".$_GET["ConfigID"]."\">\n";
	}else{
		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	}
	echo "<table class=\"form-table\">\n";
		if(!isset($_GET["action"])){  // Create new Field		
			echo " <tr>
                    <td> 
						<tr>
							<td>Type*:</td>
							<td>";
								if(bb_agency_get_option('bb_agency_option_unittype')==1){
										echo"	<select class=\"objtype\" name=\"ProfileCustomType\" id=\"1\">";
								}else{
									echo"	<select class=\"objtype\" name=\"ProfileCustomType\" id=\"0\">";
								}
								echo"<option value=\"\">---</option>
									<option value=\"1\">Single Line Text</option>
									<!--<option value=\"2\">Min/Max textfield</option>-->
									<option value=\"3\">Dropdown</option>
									<option value=\"4\">Textbox</option>
									<option value=\"5\">Checkbox</option>
									<option value=\"6\">Radiobutton</option>";
									if(bb_agency_get_option('bb_agency_option_unittype')==1){
										echo"     <option value=\"7\" id=\"1\">Imperial(ft/in/lb)</option>";
									}else{
										echo"     <option value=\"7\" id=\"0\">Metric(cm/kg)</option>";
									}
									echo "<option value=\"8\">Multiple Options</option>";
								 echo"  </select>";					   
						echo "	</td>
								<td style=\"font-size:13px;\"></td>
								<td style=\"font-size:13px;\"></td>
						</tr>
						<tr>
							<td valign=\"top\">Visibility*:</td>
							<td style=\"font-size:13px;\">
							<input type=\"radio\" name=\"ProfileCustomView\" value=\"0\" checked=\"checked\" />Show Everywhere(Front-end & Back-end)&nbsp;<br/>
							<input type=\"radio\" name=\"ProfileCustomView\" value=\"1\" />Private(Only show in Admin CRM)&nbsp;<br/>
							<input type=\"radio\" name=\"ProfileCustomView\" value=\"2\" />Custom(Used in Custom Views)&nbsp;
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
						</tr>
						<tr>
							<td valign=\"top\">Custom Views:</td>
							<td style=\"font-size:13px;\">
							<input type=\"checkbox\" name=\"ProfileCustomShowProfile\" value=\"1\" checked=\"checked\" /> Manage Profile (Back-end)&nbsp; <br/>
							<input type=\"checkbox\" name=\"ProfileCustomShowSearch\" value=\"1\"  checked=\"checked\" /> Search Form (Back-end)&nbsp; <br/>  
							<input type=\"checkbox\" name=\"ProfileCustomShowRegistration\" value=\"1\"  checked=\"checked\" /> Profile Registration Form 			                                      &nbsp; <br/>
							</td>
					  	</tr>
					  	<tr>
							<td valign=\"top\">Privacy:</td>
							<td style=\"font-size:13px;\">
							<input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"1\"  /> User must be logged in to see It &nbsp;<br/>
							<input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"2\" /> User must be an admin to see It<br/>
							<input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"3\" /> Visible to Public 
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
						</tr>
						<tr>
							<td valign=\"top\">Gender*:</td>
							<td valign=\"top\" style=\"font-size:13px;\">";
							$query = "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
							echo "<select name=\"ProfileCustomShowGender\">";
							echo "<option value=\"\">All Gender</option>";
							$queryShowGender = mysql_query($query);
								 while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
									 if(isset($data1["ProfileCustomShowGender"])){
										echo "<option value=\"".$dataShowGender["GenderID"]."\" selected=\"selected\">".$dataShowGender["GenderTitle"]."</option>";
									 }else{
										echo "<option value=\"".$dataShowGender["GenderID"]."\">".$dataShowGender["GenderTitle"]."</option>";
									 }
								 }
							echo "</select>";
							
							echo " </td>
							<td style=\"font-size:13px;\"></td>
							<td style=\"font-size:13px;\"></td>
						</tr>
						<tr>
						<td valign=\"top\">Profile Type:</td>
						<td style=\"font-size:13px;\">";
						
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
					    echo	   "</td>
									<td style=\"font-size:13px;\">
								   
									</td>
									<td style=\"font-size:13px;\">
								   
									</td>
								</tr>
						<tr>
							<td valign=\"top\">Custom Order:</td>
							<td style=\"font-size:13px;\">
							<input type=\"text\" name=\"ProfileCustomOrder\" value=\"0\" />
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
							<td style=\"font-size:13px;\">
						   
							</td>
						</tr>
					</td>
				</tr>
			</td>
		</tr>
	</table>";
		
		echo " <table>\n";		
			 echo "<tr id=\"objtype_customize\">\n";
				 echo "<td>\n";	
				 echo "</td>\n";	
			echo "</tr>\n";
		echo "</table>\n";		 
		
		
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
									
									  echo "<tr>
											<td>Type*:</td>
											<td>
											<select class=\"objtype\" name=\"ProfileCustomType\">
											<option value=\"\">---</option>
											<option value=\"1\" ". ($data1["ProfileCustomType"] == 1 ? 'selected=\"selected\"':'').">Single Line Text</option>
											
											<option value=\"3\" ". ($data1["ProfileCustomType"] == 3 ? 'selected=\"selected\"':'').">Dropdown</option>
											<option value=\"4\" ". ($data1["ProfileCustomType"] == 4 ? 'selected=\"selected\"':'').">Textbox</option>
											<option value=\"5\" ". ($data1["ProfileCustomType"] == 5 ? 'selected=\"selected\"':'').">Checkbox</option>
											<option value=\"6\" ". ($data1["ProfileCustomType"] == 6 ? 'selected=\"selected\"':'').">Radiobutton</option>";
											if(bb_agency_get_option('bb_agency_option_unittype')==1){
												echo"     <option value=\"7\" ". ($data1["ProfileCustomType"] == 7 ? 'selected=\"selected\"':'').">Imperial (ft/in/lb)</option>";
											}else{
												echo"     <option value=\"7\" ". ($data1["ProfileCustomType"] == 7 ? 'selected=\"selected\"':'').">Metric (cm/kg)</option>";
											}
										  echo"  </select>
										   
											</td>
										 </tr>";
										 //	 <a href=\"javascript:;\"  style=\"font-size:12px;color:#069;". ($data1["ProfileCustomType"] == 3 ? '':'display:none;')."\" class=\"add_more_object\" id=\"add_more_object_show\">add another dropdown list to compare(min/max)</a>
									
										echo "  <tr>
												<td> 
												<tr>
												<td valign=\"top\">Visibility*:</td>
												<td style=\"font-size:13px;\">
												<input type=\"radio\" name=\"ProfileCustomView\" value=\"0\" ". ($data1["ProfileCustomView"] == 0 ? 'checked=\"checked\"':'')." />Show Everywhere(Front-end & Back-end)&nbsp;<br/>
												<input type=\"radio\" name=\"ProfileCustomView\" value=\"1\" ". ($data1["ProfileCustomView"] == 1 ? 'checked=\"checked\"':'')."/>Private(Only show in Admin CRM)&nbsp;<br/>
												<input type=\"radio\" name=\"ProfileCustomView\" value=\"2\" ". ($data1["ProfileCustomView"] == 2 ? 'checked=\"checked\"':'')."/>Custom(Used in Custom Views)&nbsp;
												
												</td>
											
												<td style=\"font-size:13px;\">
											   
												</td>
												<td style=\"font-size:13px;\">
											   
												</td>
												</tr>
												
												<tr>
													<td valign=\"top\">Custom View*:</td>
													<td style=\"font-size:13px;\">
													<input type=\"checkbox\" name=\"ProfileCustomShowProfile\" value=\"1\" ". ($data1["ProfileCustomShowProfile"] == 1 ? 'checked=\"checked\"':'')."/> Manage Profile (Back-end)&nbsp; <br/>
													<input type=\"checkbox\" name=\"ProfileCustomShowSearch\" value=\"1\" ". ($data1["ProfileCustomShowSearch"] == 1 ? 'checked=\"checked\"':'')."/> Search Form (Back-end)&nbsp;  <br/>
													<input type=\"checkbox\" ".($ProfileCustomType==4 ? "" : "")." name=\"ProfileCustomShowRegistration\" value=\"1\" ". ($data1["ProfileCustomShowRegistration"] == 1 ? 'checked=\"checked\"':'')."/> Profile Registration Form &nbsp; <br/> </td>
												</tr>
												
												<tr>
													<td valign=\"top\">Privacy*:</td>
													<td style=\"font-size:13px;\">
													
													
										<input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"1\" ". ($data1["ProfileCustomShowLogged"] == 1 ? 'checked=\"checked\"':'')." /> User must be logged in to see It &nbsp;<br/>
										<input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"2\" ". ($data1["ProfileCustomShowAdmin"] == 1 ? 'checked=\"checked\"':'')."/> User must be an admin to see It<br/>
                                        <input type=\"radio\" name=\"ProfileCustomPrivacy\" value=\"3\" ". ($data1["ProfileCustomShowAdmin"] == 0 && $data1["ProfileCustomShowLogged"] == 0 ? 'checked=\"checked\"':'')." /> Visible to Public 
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
												</tr>
												<tr>
													<td valign=\"top\">Gender*:</td>
													<td valign=\"top\" style=\"font-size:13px;\">";
													
													$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
													echo "<select name=\"ProfileCustomShowGender\">";
													echo "<option value=\"\">All Gender</option>";
													$queryShowGender = mysql_query($query);
														 while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
															
																echo "<option value=\"".$dataShowGender["GenderID"]."\" ". selected($data1["ProfileCustomShowGender"],$dataShowGender["GenderID"],false).">".$dataShowGender["GenderTitle"]."</option>";
															
														 }
													echo "</select>";
													
												echo "						
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
												</tr>

											<tr>
													<td valign=\"top\">Profile Type:</td>
													<td style=\"font-size:13px;\"> ";
											
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
													
											echo "	</td>
													<td style=\"font-size:13px;\">
												   
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
											</tr>
	
												
												<tr>
													<td valign=\"top\">Custom Order*:</td>
													<td style=\"font-size:13px;\" align=\"left\">
													<input type=\"text\" name=\"ProfileCustomOrder\"  value=\"".$data1["ProfileCustomOrder"]."\"/>
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
													<td style=\"font-size:13px;\">
												   
													</td>
												</tr>
												
												</td>
											 </tr>";
									echo "</table>\n";
									echo " <table>\n";		
									 echo "<tr id=\"objtype_customize\">\n";
										 echo "<td>\n";	
										 echo "</td>\n";	
									echo "</tr>\n";
									echo "</table>\n";	
					echo "<div id=\"obj_edit\" class=\"".$data1["ProfileCustomType"]."\">";
				
					
								 if($data1["ProfileCustomType"] == 1){ // text
								 	  echo "<tr>
											<td style=\"width:50px;\">Title:</td>
											<td><input type=\"text\" name=\"ProfileCustomTitle\" value=\"".$data1["ProfileCustomTitle"]."\"/></td>
										</tr>";
									 	echo "
										      <tr>
												 <td align=\"right\" style=\"width:50px;\">Value*:</td>
											     <td><input type=\"text\" name=\"ProfileCustomOptions\" value=\"". $data1["ProfileCustomOptions"] ."\" /></td>
											  
											  </tr>
										
										";
								 }
							
								 elseif($data1["ProfileCustomType"] == 3){	  // Dropdown
								  	 echo "<tr>
											<td style=\"width:40px;\">Title:</td>
											<td><input type=\"text\" name=\"ProfileCustomTitle\" value=\"".$data1["ProfileCustomTitle"]."\" style=\"width:190px;\"/></td>
										</tr>";
								            list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
											
											$data1 = explode("|",$option1);
											$data2 = explode("|",$option2);
											
									echo "<tr>";
											echo "<td>";
											echo "&nbsp;";
											echo "</td>";
											echo "<td>";
											 echo "<br/>";
											//echo "Label:<input type=\"text\" value=\"".current($data1)."\" name=\"option_label\"/><br/>";
											    $pos = 0;
												foreach($data1 as $val1){
													
													if($val1 != end($data1) && $val1 != $data1[0]){
													 $pos++;
													 echo "Option:<input type=\"text\"  value=\"".$val1."\" name=\"option[]\"/>";
														//if($pos==1){
														// echo "<input type=\"checkbox\" ".(end($data1)=="yes" ? "checked=\"checked\"":"")." name=\"option_default_1\"/><span style=\"font-size:11px;\">(set as selected)</span>";	
														// }
													  echo "<br/>";
													}
												}
											echo "<div  id=\"editfield_add_more_options_1\"></div>";
											echo "<br/><a href=\"javascript:;\"  id=\"addmoreoption_1\">add more option[+]</a>";
											echo "<br/>";	
											echo "<br/>";	
											
											if(!empty($data2) && !empty($option2)){
												echo "Labe:<input type=\"text\" name=\"option_label2\" value=\"".current($data2)."\" /><br/>";
											
											 	$pos2 = 0;
											foreach($data2 as $val2){
												  
													if($val2 != end($data2) && $val2 !=  $data2[0]){
														 $pos2++;
													 echo "Option:<input type=\"text\" value=\"".$val2."\"  name=\"option2[]\"/>";
													  if($pos2==1){
														 echo "<input type=\"checkbox\" ".(end($data2)=="yes" ? "checked=\"checked\"":"")." name=\"option_default_2\"/><span style=\"font-size:11px;\">(set as selected)</span>";	
													 	
														
														echo "<a href=\"javascript:;\" id=\"addmoreoption_2\">add more option[+]</a>";	
														
													   }	
													   echo "<br/>";
													}
												}
												
											}
											echo "<div  id=\"editfield_add_more_options_2\"></div><br/>";
											echo "</td>";
									echo "</tr>";		
									
								 }
								  elseif($data1["ProfileCustomType"] == 4){	 //textbox
								   
								      $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
								        echo "<tr>
											<td>Title:</td>
											<td><input type=\"text\" name=\"ProfileCustomTitle\" value=\"".$data1["ProfileCustomTitle"]."\"/></td>
										</tr>";
									  echo "<tr><td><br/></td></tr>";	
								        echo "<tr>
											 <td align=\"right\"  valign=\"top\">Value*:</td>
											 <td><textarea name=\"ProfileCustomOptions\" style=\"width:400px;\"> ". $data1["ProfileCustomOptions"] ."</textarea></td>
										</tr>";    
								
								 }
								  elseif($data1["ProfileCustomType"] == 5){	 //checkbox
								  
								  $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
									   $pos =0;
								     
										 echo "<tr>
											 <td>Title:</td>
											 <td><input type=\"text\" name=\"ProfileCustomTitle\" value=\"".$data1["ProfileCustomTitle"]."\"/></td>
											 </tr>";  
											  echo "
													  <tr>
													      <td>&nbsp;</td>
														   <td valign=\"top\">
														 ";
										 foreach($array_customOptions_values as  $val){
											  echo "<br/>";	
											  echo" &nbsp;Value:<input type=\"text\" name=\"label[]\" value=\"". $val."\" />";
											 
										 }
										echo "<div id=\"addcheckbox_field_1\"></div>";
										echo"<a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;text-align:right;\" onclick=\"add_more_checkbox_field(1);\" >add more[+]</a>";	
										echo " </td>";
										echo" </tr>";
										
								 }
								  elseif($data1["ProfileCustomType"] == 6){	 //radio button
								    $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
									   $pos =0;
								       echo "<tr>
											<td>Title:</td>
											<td><input type=\"text\" name=\"ProfileCustomTitle\" value=\"".$data1["ProfileCustomTitle"]."\"/></td>
										</tr>";
											  
											  echo "
												   <tr>
													      <td>&nbsp;</td>
														<td valign=\"top\">
														 <br/>";
										 foreach($array_customOptions_values as  $val){
											 if(!empty($val)){
											  $pos++;	
											  echo"&nbsp;Value:<input type=\"text\" name=\"label[]\" value=\"". $val."\" />";
											
														 if($pos ==1){
																	echo"<a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;text-align:right;\" onclick=\"add_more_checkbox_field(1);\" >add more[+]</a>";	
														 }
											  echo "<br/>";	
											   }
											 	 
											
										 }
													 echo "</td>";
												 echo "</tr>";
										   echo "<div id=\"addcheckbox_field_1\"></div>";
										  
											 
								
								
								 }
								 elseif ($data1["ProfileCustomType"] == 7) {	 ///metric/imperials
										echo "<tr><td>Title*:<input type='text' name='ProfileCustomTitle' value=\"".$data1["ProfileCustomTitle"]."\"/></td></tr>";
										echo "<tr><td>&nbsp;</td></tr>";
										 
										 if (bb_agency_get_option('bb_agency_option_unittype')==0) { //  Metric (cm/kg)
												echo "<tr><td><input type='radio' name='ProfileUnitType' value='1'  ".checked($data1["ProfileCustomOptions"],1,false)."/>cm</td></tr>";
										     	echo "<tr><td><input type='radio' name='ProfileUnitType' value='2'  ".checked($data1["ProfileCustomOptions"],2,false)." />kg</td></tr>";  
										 } elseif (bb_agency_get_option('bb_agency_option_unittype')==1) { //  Imperial (in/lb)
											
												echo "<tr><td><input type='radio' name='ProfileUnitType' value='1' ".checked($data1["ProfileCustomOptions"],1,false)." />Inches</td></tr>";
										    	echo "<tr><td><input type='radio' name='ProfileUnitType' value='2' ".checked($data1["ProfileCustomOptions"],2,false)."/>Pounds</td></tr>";
												echo "<tr><td><input type='radio' name='ProfileUnitType' value='3' ".checked($data1["ProfileCustomOptions"],3,false)."/>Feet/Inches</td></tr>";
										 } 
										
										
								 }
							 
						  
					echo "</div>";				
					}  //endwhile
	     	
	           
		}	
						
		
	
			
			if ( $ProfileCustomID > 0) {
			echo "<p class=\"submit\">\n";
			echo "     <input type=\"hidden\" name=\"ProfileCustomID\" value=\"". $ProfileCustomID ."\" />\n";
			echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
			echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
			echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
			echo "</p>\n";
			} else {
			echo "<p class=\"submit\">\n";
			echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
			echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
			echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", bb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
			echo "</p>\n";
			} 
			echo "</form>\n";
    ?>
          
            </div>
</div>
<div class="all-custom_fields" style="width:700px;float:left;border:0px solid black;margin-left:15px;">
    <?php
	
	echo "  <h3 class=\"title\">". __("All Records", bb_agency_TEXTDOMAIN) ."</h3>\n";
	
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
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=7\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomType&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Type", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomOptions&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Options", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Visibility", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Custom Order", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomView&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Gender", bb_agency_TEXTDOMAIN) ."</a></th>\n";
	
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Type", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Options", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Visibility", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Custom Order", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Gender", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_agency_customfields ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
           
	     $bb_options = bb_agency_get_option();
		$bb_agency_option_unittype  = bb_agency_get_option('bb_agency_option_unittype');
		
		while ($data = mysql_fetch_array($results)) {
			$ProfileCustomID	=$data['ProfileCustomID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $ProfileCustomID ."\" name=\"". $ProfileCustomID ."\" value=\"". $ProfileCustomID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['ProfileCustomTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;ProfileCustomID=". $ProfileCustomID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">". __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;deleteRecord&amp;ProfileCustomID=". $ProfileCustomID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) . ".\'". __("Cancel", bb_agency_TEXTDOMAIN) . "\' ". __("to stop", bb_agency_TEXTDOMAIN) . ", \'". __("OK", bb_agency_TEXTDOMAIN) . "\' ". __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">". __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"column\">"; if ($data['ProfileCustomType'] == 1 ){ echo "Text"; } elseif ($data['ProfileCustomType'] == 2 ){ echo "Search Layout"; } elseif ($data['ProfileCustomType'] == 5) { echo "Checkbox"; } elseif ($data['ProfileCustomType'] == 6) { echo "Radio"; } elseif ($data['ProfileCustomType'] == 3) { echo "Dropdown"; } elseif ($data['ProfileCustomType'] == 4) { echo "Textarea"; }  elseif ($data['ProfileCustomType'] == 7) { if(bb_agency_get_option('bb_agency_option_unittype')==1){ if($data['ProfileCustomOptions']==1){echo "Imperial(in)";}elseif($data['ProfileCustomOptions']==2){echo "Imperial(lb)";}elseif($data['ProfileCustomOptions']==3){echo "Imperial(in/ft)";} } else{ if($data['ProfileCustomOptions']==2){echo "Metric(cm)";}elseif($data['ProfileCustomOptions']==2){echo "Metric(kg)";}elseif($data['ProfileCustomOptions']==3){echo "Imperial(in/ft)";} } } echo "</td>\n";
                
			
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
		if($data['ProfileCustomType'] == 7 ) {echo " <td class=\"column\">".$measurements_label."</td>\n"; } else{ echo "        <td class=\"column\">".str_replace("|",",",$data['ProfileCustomOptions'])."</td>\n"; }
		echo "        <td class=\"column\">"; if ($data['ProfileCustomView'] == 0) { echo "Public"; } elseif ($data['ProfileCustomView'] == 1) { echo "Private"; } elseif ($data['ProfileCustomView'] == 2) { echo "Custom"; } echo "</td>\n";
		 echo "        <th class=\"column\">".$data['ProfileCustomOrder']."</th>\n";
		 $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=".$data['ProfileCustomShowGender'].""); 
		 $fetchGender = mysql_fetch_assoc($queryGender);
		 $countGender = mysql_num_rows($queryGender);
		 if($countGender > 0){
		 	echo "        <th class=\"column\">".$fetchGender["GenderTitle"]."</th>\n";
		 }else{
			echo "        <th class=\"column\">All Gender</th>\n";
		 }
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"5\"><p>". __("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";
echo "</div>";
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
 echo "<div>\n";
            // Add new Record
		if(isset($_GET["action"]) =="editRecord"){
		 
		 echo "  <h3 class=\"title\">". __("Edit Record", bb_agency_TEXTDOMAIN) ."</h3>\n";
		 
		 $query = "SELECT * FROM ". table_agency_mediacategory ." WHERE MediaCategoryID='".$_GET["MediaCategoryID"]."'";
		 $results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
		 $count = mysql_num_rows($results);
		 $data = mysql_fetch_array($results);
		 echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&ConfigID=8&MediaCategoryID=".$_GET["MediaCategoryID"]."\">\n";
		}else{
             echo "  <h3 class=\"title\">". __("Add New Record", bb_agency_TEXTDOMAIN) ."</h3>\n";
		  echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=8\">\n";
		}
		
		 echo "<table>";
		 echo "<tr>";
		 echo "<td>Title:</td><td><input type=\"text\" name=\"MediaCategoryTitle\" value=\"".$data["MediaCategoryTitle"]."\" style=\"width:500px;\" /></td>\n";
		 echo "</tr>";
		 echo "<tr>";
		 echo "<td>Gender:</td><td>\n";
		 $query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
					echo "<select name=\"MediaCategoryGender\">";
					echo "<option value=\"\">All Gender</option>";
					$queryShowGender = mysql_query($query);
					while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
															
						echo "<option value=\"".$dataShowGender["GenderID"]."\" ". selected($data["MediaCategoryGender"] ,$dataShowGender["GenderID"],false).">".$dataShowGender["GenderTitle"]."</option>";
															
					}
					echo "</select>";
					echo "<br/>";
		 echo "</td>";
		 echo "</tr>";
		 echo "<td>Order:</td><td><input type=\"text\" name=\"MediaCategoryOrder\" value=\"".(0+(int)$data["MediaCategoryOrder"])."\" /></td>\n";
		 echo "<tr>";
		 echo "<td>";
		 echo "<p class=\"submit\">\n";
		 if(isset($_GET["action"]) =="editRecord"){
			echo "    <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		  
		 }else{
			echo "    <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
		 	 
		 }
		 echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Submit", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		 echo "</p>\n";
		 echo "</td>";
		 echo "<tr>";
		 echo "<table>";
		 echo "</form>\n";
		 // All Records
		 echo "  <h3 class=\"title\">". __("All Records", bb_agency_TEXTDOMAIN) ."</h3>\n";
			
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
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;ConfigID=8\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomType&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Gender", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProfileCustomOptions&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Order", bb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Gender", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Order", bb_agency_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_agency_mediacategory ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ).mysql_error());
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$MediaCategoryID	=$data['MediaCategoryID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $MediaCategoryID ."\" name=\"MediaCategoryID[]\" value=\"". $MediaCategoryID ."\" /></th>\n";
		echo "        <th class=\"column\">". stripslashes($data['MediaCategoryTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;MediaCategoryID=". $MediaCategoryID."&amp;ConfigID=8\" title=\"". __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">". __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;deleteRecord&amp;MediaCategoryID=". $MediaCategoryID ."&amp;ConfigID=8\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, bb_agency_TEXTDOMAIN) . ".\'". __("Cancel", bb_agency_TEXTDOMAIN) . "\' ". __("to stop", bb_agency_TEXTDOMAIN) . ", \'". __("OK", bb_agency_TEXTDOMAIN) . "\' ". __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">". __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </th>\n";
		 $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$data['MediaCategoryGender']."'"); 
		 $fetchGender = mysql_fetch_assoc($queryGender);
		 $countGender = mysql_num_rows($queryGender);
		 if($countGender > 0){
		 	echo "        <th class=\"column\">".$fetchGender["GenderTitle"]."</th>\n";
		 }else{
			echo "        <th class=\"column\">All Gender</th>\n";
		 }
		echo "        <th class=\"column\">"; echo $data["MediaCategoryOrder"]; echo "</th>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"5\"><p>". __("There aren't any records loaded yet", bb_agency_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";
 
 echo "</div>\n";
	
}
// End Config == 8	
elseif ($ConfigID == 99) {
	
	echo "    <h3>Uninstall</h3>\n";
	echo "    <div>Are you sure you want to uninstall?</div>\n";
	echo "	<div><a href=\"?page=". $_GET["page"] ."&action=douninstall\">Yes! Uninstall</a></div>\n";
}	 // End	
echo "</div>\n";
?>