<?php

global $wpdb;
define("LabelPlural", "Profiles");
define("LabelSingular", "Profile");

$bb_agency_option_agencyimagemaxheight = bb_agency_get_option('bb_agency_option_agencyimagemaxheight');
if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) {
    $bb_agency_option_agencyimagemaxheight = 800;
}

$bb_agency_option_locationtimezone = (int) bb_agency_get_option('bb_agency_option_locationtimezone');

if (function_exists('bb_agencyinteract_approvemembers')) {
    // Load Interact Settings
    $bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
    $bb_agency_option_useraccountcreation = (int) bb_agency_get_option('bb_agency_option_useraccountcreation');
}

$t_profile = table_agency_profile;
$t_media = table_agency_profile_media;
$t_data_type = table_agency_data_type;
$t_data_talent = table_agency_data_talent;
$t_custom = table_agency_customfield_mux;

// *************************************************************************************************** //
// Handle Post Actions

if (isset($_POST['action'])) {
    $ProfileID = $_POST['ProfileID'];
    $ProfileUserLinked = $_POST['ProfileUserLinked'];
    $ProfileContactNameFirst = trim($_POST['ProfileContactNameFirst']);
    $ProfileContactNameLast = trim($_POST['ProfileContactNameLast']);
    $ProfileContactDisplay = trim($_POST['ProfileContactDisplay']);
    if (empty($ProfileContactDisplay)) {  // Probably a new record... 
        $ProfileContactDisplay = $ProfileContactNameFirst . " " . substr($ProfileContactNameLast, 0, 1);
    }

    $ProfileGallery = trim($_POST['ProfileGallery']);
    if (empty($ProfileGallery)) {  // Probably a new record...
        $ProfileGallery = bb_agency_safenames($ProfileContactDisplay);
    }

    $ProfileGender = $_POST['ProfileGender'];
    $ProfileDateBirth = $_POST['ProfileDateBirth'];
    if (bb_agency_SITETYPE == 'bumps') {
        $ProfileDateDue = $_POST['ProfileDateDue'];
    }
    $ProfileContactEmail = $_POST['ProfileContactEmail'];
    $ProfileUsername = $_POST["ProfileUsername"];
    $ProfilePassword = $_POST['ProfilePassword'];
    $ProfileContactWebsite = $_POST['ProfileContactWebsite'];
    $ProfileContactPhoneHome = $_POST['ProfileContactPhoneHome'];
    $ProfileContactPhoneCell = $_POST['ProfileContactPhoneCell'];
    $ProfileContactPhoneWork = $_POST['ProfileContactPhoneWork'];
    $ProfileLocationStreet = $_POST['ProfileLocationStreet'];
    $ProfileLocationCity = bb_agency_strtoproper($_POST['ProfileLocationCity']);
    $ProfileLocationState = $_POST['ProfileLocationState'];
    $ProfileLocationZip = $_POST['ProfileLocationZip'];
    $ProfileLocationCountry = $_POST['ProfileLocationCountry'];
    $ProfileLanguage = $_POST['ProfileLanguage'];
    $ProfileDateUpdated = $_POST['ProfileDateUpdated'];
    $ProfileDateViewLast = $_POST['ProfileDateViewLast'];
    
    // get posted profile types
    $ProfileType = $_POST['ProfileType'];
    if (is_array($ProfileType)) {
        $ProfileType = implode(",", $ProfileType);
    }
    
    // get posted talents
    $ProfileTalent = $_POST['ProfileTalent'];
    if (is_array($ProfileTalent)) {
        $ProfileTalent = implode(",", $ProfileTalent);
    }

    // get posted genres
    $ProfileGenre = $_POST['ProfileGenre'];
    if (is_array($ProfileGenre)) {
        $ProfileGenre = implode(",", $ProfileGenre);
    }
    
    // get posted ability
    $ProfileAbility = $_POST['ProfileAbility'];

    $ProfileIsActive = $_POST['ProfileIsActive']; // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
    $ProfileIsFeatured = $_POST['ProfileIsFeatured'];
    $ProfileIsPromoted = $_POST['ProfileIsPromoted'];
    $ProfileStatHits = $_POST['ProfileStatHits'];

    // Get Primary Image
    $ProfileMediaPrimaryID = $_POST['ProfileMediaPrimary'];

    // Notify User and Admin
    $ProfileNotifyUser = $_POST["ProfileNotifyUser"];

    // Error checking
    $error = '';
    $have_error = false;
    if (trim($ProfileContactNameFirst) == '') {
        $error .= "<b><i>The " . LabelSingular . " must have a name.</i></b><br>";
        $have_error = true;
    }
    if ((isset($_GET["action"]) == "addRecord") && function_exists('bb_agencyinteract_approvemembers')) {
        $userdata = array(
            'user_pass' => esc_attr($ProfilePassword),
            'user_login' => esc_attr($ProfileUsername),
            'first_name' => esc_attr($ProfileContactNameFirst),
            'last_name' => esc_attr($ProfileContactNameLast),
            'user_email' => esc_attr($ProfileContactEmail),
            'role' => get_option('default_role')
        );
        if (empty($userdata['user_login'])) {
            $error .= __("A username is required for registration.<br />", bb_agency_TEXTDOMAIN);
            $have_error = true;
        }
        if (username_exists($userdata['user_login'])) {
            $error .= __("Sorry, that username already exists!<br />", bb_agency_TEXTDOMAIN);
            $have_error = true;
        }
        if (!is_email($userdata['user_email'], true)) {
            $error .= __("You must enter a valid email address.<br />", bb_agency_TEXTDOMAIN);
            $have_error = true;
        }
        if (email_exists($userdata['user_email'])) {
            $error .= __("Sorry, that email address is already used!<br />", bb_agency_TEXTDOMAIN);
            $have_error = true;
        }
        if (!$userdata['user_password'] && count($userdata['user_password']) > 5) {
            $error .= __("A password is required for registration and must have 6 characters.<br />", bb_agency_TEXTDOMAIN);
            $have_error = true;
        }
    }

    // Get Post State
    $action = $_POST['action'];
    switch ($action) {

        // *************************************************************************************************** //
        // Add Record
        case 'addRecord':
            if ($have_error == false) {
                if (function_exists('bb_agencyinteract_approvemembers')) {
                    $new_user = wp_insert_user($userdata);
                }

                $ProfileGallery = bb_agency_checkdir($ProfileGallery);  // Check Directory - create directory if does not exist

                $fields = bb_agency_get_profile_fields();

                // Create insert query
                
                foreach ($fields as $field) {
                    $value = is_array($_POST[$field]) ? implode(',', $_POST[$field]) : $_POST[$field];
                    $sqlData[] = "`{$field}` = \"".$wpdb->escape($value)."\"";

                    if (preg_match("/^ProfileLocation/", $field) && $_POST[$field]) {
                        $arrAddress[] = $value;
                    }
                }

                if (isset($new_user))
                    $sqlData[] = '`ProfileUserLinked` = '.$new_user;

                if ($ProfileGallery)
                    $sqlData[] = "`ProfileGallery` = \"$ProfileGallery\"";

                $sqlData[] = '`ProfileDateUpdated` = NOW()';

                // get latitude and longitude
                if (!empty($arrAddress)) {
                    $address = implode(', ', $arrAddress);

                    if ($location = bb_agency_geocode($address)) {
                        // geocode address
                        $sqlData[] = 'ProfileLocationLatitude = "'.$location['lat'].'"';
                        $sqlData[] = 'ProfileLocationLongitude = "'.$location['lng'].'"';
                    } else {
                        echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of address '%s'", bb_agency_TEXTDOMAIN), $address) . '</p></div>';
                    }
                }

                $insert = "INSERT INTO `$t_profile` SET " . implode(', ', $sqlData);

                $results = $wpdb->query($insert) or die("Add Record: " . mysql_error());
                $ProfileID = $wpdb->insert_id;
			
                // Notify admin and user
                if (isset($ProfileNotifyUser) && $ProfileNotifyUser <> "yes" && isset($new_user) && function_exists('bb_agencyinteract_approvemembers')) {
                    wp_new_user_notification( $new_user, null, 'both' );
                }

                // Set Display Name as Record ID (We have to do this after so we know what record ID to use... right ;)
                if ($bb_agency_option_profilenaming == 3) {
                    $ProfileContactDisplay = "ID-" . $ProfileID;
                    $ProfileGallery = "ID" . $ProfileID;

                    $update = $wpdb->query("UPDATE `$t_profile` SET ProfileContactDisplay='" . $ProfileContactDisplay . "', ProfileGallery='" . $ProfileGallery . "' WHERE ProfileID='" . $ProfileID . "'");
                    $updated = $wpdb->query($update);
                }

                // Add Custom Field Values stored in Mux
                foreach ($_POST as $key => $value) {
                    if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {
                        $ProfileCustomID = substr($key, 15);
                        if (is_array($value)) {
                            $value = implode(",", $value);
                        }
                        $insert1 = "INSERT INTO `$t_custom` (ProfileID,ProfileCustomID,ProfileCustomValue) VALUES ('$ProfileID','$ProfileCustomID','$value')";
                        $results1 = $wpdb->query($insert1);
                    }
                }

                echo ('<div id="message" class="updated"><p>' . __("New Profile added successfully!", bb_agency_TEXTDOMAIN) . ' <a href="' . admin_url("admin.php?page=" . $_GET['page']) . '&action=editRecord&ProfileID=' . $ProfileID . '">' . __("Update and add media", bb_agency_TEXTDOMAIN) . '</a></p></div>');

                bb_display_manage($ProfileID);

                
            } else { ?>
                <div id="message" class="error">
                    <p><?php _e("Error creating record, please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) ?></p>
                </div>
                <div id="message" class="error">
                    <p>
                        <?php echo $error ?>
                        <br/>
                        <a href="javascript:;" onclick="if(document.referrer) {window.open(document.referrer,'_self');} else {history.go(-1);} return false;">&larr;Go back and Edit</a>
                    </p>
                </div>
                <?php
                //bb_display_manage($ProfileID);
            }

            break;

        // *************************************************************************************************** //
        // Edit Record
        case 'editRecord':
            if (!empty($ProfileContactNameFirst) && !empty($ProfileID)) :

                // get WP user
                $ProfileUserLinked = $_REQUEST['wpuserid'];

                $fields = bb_agency_get_profile_fields();

                // Create update query
                
                foreach ($fields as $field) {
                    $value = is_array($_POST[$field]) ? implode(',', $_POST[$field]) : $_POST[$field];
                    $sqlData[] = "`{$field}` = \"".$wpdb->escape($value)."\"";

                    if (preg_match("/^ProfileLocation/", $field) && $_POST[$field]) {
                        $arrAddress[] = $value;
                    }
                }

                if ($ProfileGallery)
                    $sqlData[] = "`ProfileGallery` = \"$ProfileGallery\"";

                $sqlData[] = '`ProfileDateUpdated` = NOW()';

                // get latitude and longitude
                if (!empty($arrAddress)) {
                    $address = implode(', ', $arrAddress);

                    if ($location = bb_agency_geocode($address)) {
                        // geocode address
                        $sqlData[] = '`ProfileLocationLatitude` = "'.$location['lat'].'"';
                        $sqlData[] = '`ProfileLocationLongitude` = "'.$location['lng'].'"';
                    } else {
                        echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of address '%s'", bb_agency_TEXTDOMAIN), $address) . '</p></div>';
                    }
                }

                // create update sql
                $update = "UPDATE `$t_profile` SET ". implode(', ', $sqlData) ." WHERE ProfileID = '$ProfileID'";

                $results = $wpdb->query($update) or wp_die(mysql_error() .': '. $update);

				update_user_meta($ProfileUserLinked, 'bb_agency_interact_profiletype', esc_attr($ProfileType));
                update_user_meta($ProfileUserLinked, 'bb_agency_interact_pgender', esc_attr($ProfileGender));

                bb_agency_debug( $ProfileUserLinked . ' => ' . print_r( $_POST, true ) );

                if ($ProfileUserLinked > 0) {
                    /* Update WordPress user information. */
                    update_user_meta($ProfileUserLinked, 'first_name', esc_attr($ProfileContactNameFirst) );
                    update_user_meta($ProfileUserLinked, 'last_name', esc_attr($ProfileContactNameLast) );
                    update_user_meta($ProfileUserLinked, 'nickname', esc_attr($ProfileContactDisplay) );
                    update_user_meta($ProfileUserLinked, 'display_name', esc_attr($ProfileContactDisplay) );
                    update_user_meta($ProfileUserLinked, 'user_email', esc_attr($ProfileContactEmail) );

                    if (bb_agency_is_client_profiletype( $ProfileUserLinked )) { //client
                        foreach( array( 
                            'email_updates',
                            'newsletter',
                            'postal'
                        ) as $id )
                            update_user_meta( $ProfileUserLinked, $id, isset( $_POST[$id] ) );
                        
                    } else {
                        
                        foreach( array(
                            'clients', 
                            'marketing',
                            'newsletter'
                        ) as $id ) {
                            update_user_meta( $ProfileUserLinked, $id, isset( $_POST[$id] ) );
                        }
                          
                    }
                }

                // Remove Old Custom Field Values
                $delete1 = 'DELETE FROM ' . table_agency_customfield_mux . ' WHERE `ProfileID` = "' . (int)$ProfileID . '"';
                $results1 = $wpdb->query($delete1);

                // Add New Custom Field Values
                foreach ($_POST as $key => $value) {
                    if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {
                        $ProfileCustomID = substr($key, 15);
                        if (is_array($value)) {
                            $value = implode(",", $value);
                        }
                        $insert1 = "INSERT INTO `$t_custom` (ProfileID,ProfileCustomID,ProfileCustomValue)" . "VALUES ('" . $ProfileID . "','" . $ProfileCustomID . "','" . $value . "')";
                        bb_agency_debug( $insert1 );
                        $results1 = $wpdb->query($insert1);
                    }
                }
 
                $ProfileGallery = bb_agency_checkdir($ProfileGallery);  // Check Directory - create directory if does not exist
                
                // Upload Image & Add to Database
                $i = 1;

                while ($i <= 10) {

                    if ($_FILES['profileMedia' . $i]['tmp_name'] != '') {
                        $uploadMediaType = $_POST['profileMedia' . $i . 'Type'];
                        if ($have_error != true) {
                            // Upload if it doesnt exist already
                            $path_parts = pathinfo($_FILES['profileMedia' . $i]['name']);
                            $safeProfileMediaFilename = bb_agency_safenames($path_parts['filename'] . "." . $path_parts['extension']);
                            $results = $wpdb->get_results("SELECT * FROM `$t_media` WHERE ProfileID='" . $ProfileID . "' AND ProfileMediaURL = '" . $safeProfileMediaFilename . "'");
                            $count = count($results);

                            if ($count < 1) {
                                if ($uploadMediaType == "Image") {

                                    if ($_FILES['profileMedia' . $i]['type'] == "image/pjpeg" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {

                                        $image = new bb_agency_image();
                                        $image->load($_FILES['profileMedia' . $i]['tmp_name']);

                                        if ($image->getHeight() > $bb_agency_option_agencyimagemaxheight) {
                                            $image->resizeToHeight($bb_agency_option_agencyimagemaxheight);
                                        }
                                        $image->save(bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);

                                        // Add to database
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                    } else {
                                        $error .= "<b><i>Please upload an image file only</i></b><br />";
                                        $have_error = true;
                                    }
                                } else if ($uploadMediaType == "VoiceDemo") {
                                    // Add to database
                                    $MIME = array('audio/mpeg', 'audio/mp3');
                                    if (in_array($_FILES['profileMedia' . $i]['type'], $MIME)) {
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                    } else {
                                        $error .= "<b><i>Please upload a mp3 file only</i></b><br />";
                                        $have_error = true;
                                    }
                                } else if ($uploadMediaType == "Resume") {
                                    // Add to database
                                    if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf") {
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                    } else {
                                        $error .= "<b><i>Please upload PDF/MSword/RTF files only</i></b><br />";
                                        $have_error = true;
                                    }
                                } else if ($uploadMediaType == "Headshot") {
                                    // Add to database
                                    if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                    } else {
                                        $error .= "<b><i>Please upload PDF/MSWord/RTF/Image files only</i></b><br />";
                                        $have_error = true;
                                    }
                                } else if ($uploadMediaType == "Compcard") {
                                    // Add to database
                                    if ($_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                    } else {
                                        $error .= "<b><i>Please upload jpeg or png files only</i></b><br />";
                                        $have_error = true;
                                    }
                                } else if ($uploadMediaType == 'Private') {
                                    // Private files can be anything
                                    $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                } else {
                                    // Add to database
                                    if ($_FILES['profileMedia' . $i]['type'] == "image/pjpeg" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
                                        $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
                                        move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], bb_agency_UPLOADPATH . $ProfileGallery . '/' . $safeProfileMediaFilename);
                                    } else {
                                        $error .= '<b><i>'. __("Please upload jpeg or png files only", bb_agency_TEXTDOMAIN) . '</i></b><br />';
                                        $have_error = true;
                                    }
                                }
                            } // End count
                        } // End have error = false
                    } //End:: if profile media is not empty.
                    $i++;
                } // endwhile           
                // Upload Videos to Database
                if (isset($_POST['profileMediaV1']) && !empty($_POST['profileMediaV1'])) {
                    $profileMediaType = $_POST['profileMediaV1Type'];
                    $profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV1']);
                    $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaType . "','" . $profileMediaURL . "')");
                }
                if (isset($_POST['profileMediaV2']) && !empty($_POST['profileMediaV2'])) {
                    $profileMediaType = $_POST['profileMediaV2Type'];
                    $profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV2']);
                    $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaType . "','" . $profileMediaURL . "')");
                }
                if (isset($_POST['profileMediaV3']) && !empty($_POST['profileMediaV3'])) {
                    $profileMediaType = $_POST['profileMediaV3Type'];
                    $profileMediaURL = bb_agency_get_VideoFromObject($_POST['profileMediaV3']);
                    $results = $wpdb->query("INSERT INTO `$t_media` (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaType . "','" . $profileMediaURL . "')");
                }

                /* --------------------------------------------------------- CLEAN THIS UP -------------- */
                // Do we have a custom image yet? Lets just set the first one as primary.
                $results = $wpdb->get_results("SELECT * FROM `$t_media` WHERE ProfileID='$ProfileID' AND ProfileMediaType = 'Image' AND ProfileMediaPrimary='1'");
                $count = count($results);
                if ($count < 1) {
                    $resultsNeedOne = $wpdb->get_results("SELECT * FROM `$t_media` WHERE ProfileID='" . $ProfileID . "' AND ProfileMediaType = 'Image' LIMIT 0, 1");
                    foreach ($resultsNeedOne as $dataNeedOne) {
                        $resultsFoundOne = $wpdb->query("UPDATE `$t_media` SET ProfileMediaPrimary='1' WHERE ProfileID='" . $ProfileID . "' AND ProfileMediaID = '" . $dataNeedOne->ProfileMediaID . "'");
                        break;
                    }
                }
                if ($ProfileMediaPrimaryID > 0) {
                    // Update Primary Image
                    $results = $wpdb->query("UPDATE `$t_media` SET `ProfileMediaPrimary` = '0' WHERE `ProfileID` = $ProfileID");
                    $results = $wpdb->query("UPDATE `$t_media` SET `ProfileMediaPrimary` = '1' WHERE `ProfileID` = $ProfileID AND `ProfileMediaID` = $ProfileMediaPrimaryID");
                }

                // save model card
                bb_agency_save_modelcard($ProfileGallery);

                /* --------------------------------------------------------- CLEAN THIS UP -------------- */
                ?>
                <div id="message" class="updated">
                    <p><?php _e("Profile updated successfully", bb_agency_TEXTDOMAIN) ?>! <a href="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>&amp;action=editRecord&amp;ProfileID=<?php echo $ProfileID ?>"><?php _e("Continue editing the record", bb_agency_TEXTDOMAIN) ?>?</a>
                    </p>
                </div>
            <?php else : ?>
                <div id="message" class="error">
                    <p><?php _e("Error updating record, please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) ?></p>
                </div>
            <?php endif;

            bb_display_list();
            exit;
            break;

        // *************************************************************************************************** //
        // Delete bulk
        case 'deleteRecord':
            foreach ($_POST as $ProfileID) {

                // Verify Record
                $queryDelete = "SELECT * FROM `$t_profile` WHERE `ProfileID` = '$ProfileID'";
                $resultsDelete = $wpdb->get_results($queryDelete);

                foreach ($resultsDelete as $dataDelete) {
                    $ProfileGallery = $dataDelete->ProfileGallery;

                    // Remove Profile
                    $wpdb->delete($t_profile, array( 'ProfileID' => $ProfileID ) );

                    // Remove Media
                    $wpdb->delete($t_media, array( 'ProfileID' => $ProfileID ) );

                    if (isset($ProfileGallery)) {
                        // Remove Folder
                        $dir = bb_agency_UPLOADPATH . $ProfileGallery . "/";
                        $mydir = opendir($dir);
                        while (false !== ($file = readdir($mydir))) {
                            if ($file != "." && $file != "..") {
                                unlink($dir . $file) or wp_die('<div id="message" class="error"><p>'. __("Error removing:", bb_agency_TEXTDOMAIN) . $dir . $file . '</p></div>');
                            }
                        }
                        // Remove Directory
                        if (is_dir($dir)) {
                            rmdir($dir) or wp_die('<div id="message" class="error"><p>'. __("Error removing:", bb_agency_TEXTDOMAIN) . $dir . $file . '</p></div>');
                        }
                        closedir($mydir);
                    } else { ?>
                        <div id="message" class="error">
                            <p><?php _e("No Valid Record Found.", bb_agency_TEXTDOMAIN) ?></p>
                        </div>
                    <?php } ?>

                    <div id="message" class="updated">
                        <p><?php _e("Profile deleted successfully!", bb_agency_TEXTDOMAIN) ?></p>
                    </div>
                <?php
                } // is there record?
                //---------- Delete users but re-assign to current user -------------//
                // Gimme an admin:
                $AdminID = get_current_user_id();
 
                // Now delete
                wp_delete_user($dataDelete["ProfileUserLinked"], $AdminID);
            }
            bb_display_list();
            exit;
            break;
    }
}
// *************************************************************************************************** //
// Delete Single
elseif ($_GET['action'] == "deleteRecord" || $_GET['action'] == "deleteDuplicateRecord") {

    $ProfileID = $_GET['ProfileID'];
    // Verify Record
    $queryDelete = "SELECT * FROM `$t_profile` WHERE `ProfileID` =  '$ProfileID'";
    $resultsDelete = $wpdb->get_results($queryDelete);
    foreach ($resultsDelete as $dataDelete) {
        $ProfileGallery = $dataDelete->ProfileGallery;

        // Remove Profile
        $wpdb->delete($t_profile, array( 'ProfileID' => $ProfileID ) );

        // Remove Media
        $wpdb->delete($t_media, array( 'ProfileID' => $ProfileID ) );

        if ($_GET['action'] == "deleteRecord") {
            if (isset($ProfileGallery)) {
                // Remove Folder
                $dir = bb_agency_UPLOADPATH . $ProfileGallery . "/";
                $mydir = @opendir($dir);
                while (false !== ($file = @readdir($mydir))) {
                    if ($file != "." && $file != "..") {
                        @unlink($dir . $file) or DIE("couldn't delete $dir$file<br />");
                    }
                }
                // remove dir
                if (is_dir($dir)) {
                    rmdir($dir) or DIE("couldn't delete $dir$file folder not exist<br />");
                }
                closedir($mydir);
            } else {
                echo __("No valid record found.", bb_agency_TEXTDOMAIN);
            }

            wp_delete_user($dataDelete->ProfileUserLinked);
        }
        echo ('<div id="message" class="updated"><p>' . __("Profile deleted successfully!", bb_agency_TEXTDOMAIN) . '</p></div>');
    } // is there record?
    bb_display_list();
}
// *************************************************************************************************** //
// Show Edit Record
elseif ($_GET['action'] == "editRecord" || $_GET['action'] == "addRecord") {

    $action = $_GET['action'];
    $ProfileID = $_GET['ProfileID'];

    bb_display_manage($ProfileID);
} else {
// *************************************************************************************************** //
// Show List
    bb_display_list();
}

// *************************************************************************************************** //
// Manage Record
function bb_display_manage($ProfileID) {
    
    bb_agency_debug( __FUNCTION__ . ' ' . $ProfileID );

    global $wpdb;

    // database tables
    $t_profile      = table_agency_profile;
    $t_media        = table_agency_profile_media;
    $t_data_type    = table_agency_data_type;
    $t_data_talent  = table_agency_data_talent;
    $t_data_genre   = table_agency_data_genre;
    $t_data_ability = table_agency_data_ability;
    $t_gender       = table_agency_data_gender;

    $bb_agency_option_agencyimagemaxheight = bb_agency_get_option('bb_agency_option_agencyimagemaxheight');
    if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) {
        $bb_agency_option_agencyimagemaxheight = 800;
    }
//    $bb_agency_option_profilenaming = (int) bb_agency_get_option('bb_agency_option_profilenaming');
    $bb_agency_option_locationcountry = bb_agency_get_option('bb_agency_option_locationcountry');
    ?>
    <div class="wrap">
    <?php
    // Include Admin Menu
    include ("admin-menu.php");

    if (!empty($ProfileID)) {

        $query = "SELECT p.*, dt.`DataTypeTalent` AS HasTalent FROM `$t_profile` p LEFT JOIN `$t_data_type` dt ON dt.`DataTypeID` = p.`ProfileType` WHERE ProfileID = '$ProfileID' LIMIT 1";

        bb_agency_debug( $query );

        $profile = $wpdb->get_row( $query );

        $ProfileID = $profile->ProfileID;
        $ProfileGallery = $profile->ProfileGallery;
        $ProfileType = $profile->ProfileType;
        $ProfileGender = $profile->ProfileGender;
        $ProfileUser = $profile->ProfileUserLinked;

    } else {
        // Set default values for new records
        $ProfilesModelDate = $date;
        $ProfileType = 0;
        $ProfileGender = "Unknown";
        $ProfileIsActive = 1;
        $ProfileUser = null;
        $ProfileLocationCountry = $bb_agency_option_locationcountry;
        ?>
        <h2 class="title">Add New <?php echo LabelSingular ?> <a class="button-primary" href="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>"><?php _e("Back to " . LabelSingular . " List", bb_agency_TEXTDOMAIN) ?></a></h2>
        <p><?php _e("Fill in the form below to add a new", bb_agency_TEXTDOMAIN) ?> <?php echo LabelSingular ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?> *</strong></p>
        <?php
    }

    if ($_GET["action"] == "addRecord") : ?>
        <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>&action=addRecord&ProfileGender=<?php echo $_GET["ProfileGender"] ?>">
    <?php else : ?>
        <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>">
    <?php endif; ?>
    <div class="halfwidth alignleft">
        <table class="form-table">
            <tbody>
            <tr colspan="2">
              <th scope="row"><h3><?php _e("Contact Information", bb_agency_TEXTDOMAIN) ?></h3></th>
            </tr>
            <?php if ((!empty($ProfileID) && ($ProfileID > 0)) || ($bb_agency_option_profilenaming == 2)) : // Editing Record ?>
                <tr valign="top">
                  <th scope="row"><?php _e("Display Name", bb_agency_TEXTDOMAIN) ?></th>
                  <td>
                      <input class="regular-text" type="text" id="ProfileContactDisplay" name="ProfileContactDisplay" value="<?php echo $profile->ProfileContactDisplay ? $profile->ProfileContactDisplay : $profile->ProfileContactNameFirst ?>" />
                  </td>
                </tr>
            <?php endif;

            if (!empty($ProfileID) && ($ProfileID > 0)) : // Editing Record ?>
                <tr valign="top">
                  <th scope="row"><?php _e("Gallery Folder", bb_agency_TEXTDOMAIN) ?></th>
                  <td>
                    <?php if (!empty($ProfileGallery) && is_dir(bb_agency_UPLOADPATH . $ProfileGallery)) : ?>
                    <div>
                        <span class="updated"><?php _e("Folder", bb_agency_TEXTDOMAIN) ?> <strong><?php echo $ProfileGallery ?></strong> <?php _e("Exists", bb_agency_TEXTDOMAIN) ?></span>
                    </div>
                    <input type="hidden" id="ProfileGallery" name="ProfileGallery" value="<?php echo $ProfileGallery ?>" />
                    <?php else : ?>
                    <input type="text" id="ProfileGallery" name="ProfileGallery" value="<?php echo $ProfileGallery ?>" />
                    <div id="message">
                        <span class="error"><?php printf(__("No %s folder exists", bb_agency_TEXTDOMAIN), $ProfileGallery) ?></span>
                    <?php endif; ?>
                    </div>
                  </td>
                </tr>
            <?php endif; ?>

            <tr valign="top" class="required">
              <th scope="row"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?>*</th>
              <td>
                  <input type="text" class="regular-text" id="ProfileContactNameFirst" name="ProfileContactNameFirst" value="<?php echo $profile->ProfileContactNameFirst ?>" />
              </td>
            </tr>
            <tr valign="top" class="required">
              <th scope="row"><?php _e("Last Name", bb_agency_TEXTDOMAIN) ?>*</th>
              <td>
                  <input type="text" class="regular-text" id="ProfileContactNameLast" name="ProfileContactNameLast" value="<?php echo $profile->ProfileContactNameLast ?>" />
              </td>
            </tr>

            <?php
            // password
            if ((isset($_GET["action"]) && $_GET["action"] == "addRecord") && function_exists('bb_agencyinteract_approvemembers')) : ?>
                <tr valign="top">
                  <th scope="row"><?php _e("Username", bb_agency_TEXTDOMAIN) ?>*</th>
                  <td>
                      <input type="text" class="regular-text" id="ProfileUsername" name="ProfileUsername" />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php _e("Password", bb_agency_TEXTDOMAIN) ?>*</th>
                  <td>
                      <input type="text" class="regular-text" id="ProfilePassword" name="ProfilePassword" />
                      <input type="button" onclick="javascript:document.getElementById('ProfilePassword').value=Math.random().toString(36).substr(2,6);" value="Generate Password" name="GeneratePassword" />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php _e("Send Login details?", bb_agency_TEXTDOMAIN) ?></th>
                  <td>
                      <input type="checkbox" name="ProfileNotifyUser" /> Send login details to the new user and admin by email.
                  </td>
                </tr>
            <?php endif;

            if (!is_null($ProfileUser)) { ?>
                <tr valign="top">
                    <th colspan="2"><h3><?php _e( 'Preferences', bb_agency_TEXTDOMAIN ) ?></h3></th>
                </tr>
                <?php if (bb_agency_is_client_profiletype($ProfileUser)) : // clients

                    foreach( array( 
                        'email_updates' => __( 'Can be emailed about castings and shoots' ),
                        'newsletter' => __( 'Wants to receive the newsletter' ),
                        'postal' => __( 'Can be sent cards in the post' ),
                    ) as $id => $label ) : ?>
                <tr valign="top">
                    <td scope="row"><?php echo $label ?></td>
                    <td><input type="checkbox" name="<?php echo $id ?>" value="1" <?php checked( get_user_meta( $ProfileUser, $id, true ) ) ?>" /></td>
                </tr>
                <?php endforeach; else : // models

                    foreach( array(
                        'clients' => __( 'Details can be sent to clients' ),
                        'marketing' => __( 'Images can be used on social media' ),
                        'newsletter' => __( 'Wants to receive the newsletter' )
                    ) as $id => $label ) : ?>
                <tr valign="top">
                    <td scope="row"><?php echo $label ?></td>
                    <td><input type="checkbox" name="<?php echo $id ?>" value="1" <?php checked( get_user_meta( $ProfileUser, $id, true ) ) ?>" /></td>
                </tr>
                <?php endforeach; endif;
            }
            
            // Private Information
            ?>
            <tr valign="top">
              <th scope="row" colspan="2"><h3><?php _e("Private Information", bb_agency_TEXTDOMAIN) ?></h3><?php _e("The following information will NOT appear in public areas and is for administrative use only.", bb_agency_TEXTDOMAIN) ?></th>
            </tr>
            <tr valign="top" class="required">
              <th scope="row"><?php _e("Email Address", bb_agency_TEXTDOMAIN) ?>*</th>
              <td>
                  <input type="text" class="regular-text" id="ProfileContactEmail" name="ProfileContactEmail" value="<?php echo $profile->ProfileContactEmail ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("Website", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" class="regular-text" id="ProfileContactWebsite" name="ProfileContactWebsite" value="<?php echo $profile->ProfileContactWebsite ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("Phone", bb_agency_TEXTDOMAIN) ?></th>
              <td>
              <fieldset>
                  <label>Home:</label><br />
                  <input type="text" id="ProfileContactPhoneHome" name="ProfileContactPhoneHome" value="<?php echo $profile->ProfileContactPhoneHome ?>" /><br />
                  <label>Mobile:</label><br />
                  <input type="text" id="ProfileContactPhoneCell" name="ProfileContactPhoneCell" value="<?php echo $profile->ProfileContactPhoneCell ?>" /><br />
                  <label>Work:</label><br />
                  <input type="text" id="ProfileContactPhoneWork" name="ProfileContactPhoneWork" value="<?php echo $profile->ProfileContactPhoneWork ?>" /><br />
              </fieldset>
              </td>
            </tr>

            <tr valign="top">
              <th scope="row"><?php _e("Street", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" class="regular-text" id="ProfileLocationStreet" name="ProfileLocationStreet" value="<?php echo $profile->ProfileLocationStreet ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("Town", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" class="regular-text" id="ProfileLocationCity" name="ProfileLocationCity" value="<?php echo $profile->ProfileLocationCity ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("County", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" class="regular-text" id="ProfileLocationState" name="ProfileLocationState" value="<?php echo $profile->ProfileLocationState ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("Post code", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" id="ProfileLocationZip" name="ProfileLocationZip" value="<?php echo $profile->ProfileLocationZip ?>" />
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e("Country", bb_agency_TEXTDOMAIN) ?></th>
              <td>
                  <input type="text" class="regular-text" id="ProfileLocationCountry" name="ProfileLocationCountry" value="<?php echo $profile->ProfileLocationCountry ?>" />
              </td>
            </tr>
            <?php
            // display location map
            if ($profile->ProfileLocationLatitude != '' && $profile->ProfileLocationLongitude != '') : ?>
                <tr valign="top">
                    <td scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?></td>
                    <td><?php bb_agency_map($profile->ProfileLocationLatitude, $profile->ProfileLocationLongitude, $profile->ProfileContactDisplay) ?></td>
                </tr>
            <?php endif;

            // Custom Admin Fields
            // ProfileCustomView = 1 , Private
            if (isset($_GET["ProfileGender"])) {
                $ProfileGender = $_GET["ProfileGender"];
                bb_custom_fields(1, 0, $ProfileGender, true);
            } else {
                bb_custom_fields(1, $ProfileID, $ProfileGender, true);
            }

            // Public Information
            ?>
            <tr valign="top">
                <th scope="row" colspan="2">
                    <h3><?php _e("Public Information", bb_agency_TEXTDOMAIN) ?></h3>
                    The following information may appear in profile pages.
                </th>
            </tr>
            <?php
                // get available genders
                $genders = bb_agency_get_genders();

                if (!empty($genders)) : ?>
            <tr valign="top">
                <th scope="row"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <select name="ProfileGender" id="ProfileGender">
                    <?php
                    $ProfileGender1 = get_user_meta($profile->ProfileUserLinked, 'bb_agency_interact_pgender', true);
                   
                	if ($ProfileGender=='') {
                		$ProfileGender = $_GET["ProfileGender"];
                	} elseif ($ProfileGender1 != '') {
                		$ProfileGender = $ProfileGender1;
                	}
                	
                    if (empty($GenderID) || ($GenderID < 1)) {
                        echo '<option value="0" selected>--</option>';
                    }
                    foreach ($genders as $gender) : ?>
                         <option value="<?php echo $gender['GenderID'] ?>" <?php selected($ProfileGender, $gender["GenderID"]) ?>><?php echo $gender['GenderTitle'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endif; // end of genders ?>
            <tr valign="top">
                <th scope="row"><?php _e("Birth date", bb_agency_TEXTDOMAIN) ?> <em>YYYY-MM-DD</em></th>
                <td>
                    <input class="bbdatepicker" type="text" id="ProfileDateBirth" name="ProfileDateBirth" value="<?php echo $profile->ProfileDateBirth ?>" />
                </td>
            </tr>
            <?php if (bb_agency_SITETYPE == 'bumps') : ?>
                <tr valign="top">
                    <th scope="row"><?php _e("Due date", bb_agency_TEXTDOMAIN) ?><em>YYYY-MM-DD</em></th>
                    <td>
                        <input class="bbdatepicker" type="text" id="ProfileDateDue" name="ProfileDateDue" value="<?php echo $profile->ProfileDateDue ?>" />
                    </td>
                </tr>
            <?php endif;

            // Load custom fields , Public  = 0, ProfileCustomGender = true
            // ProfileCustomView = 1 , Private
            if (isset($_GET["ProfileGender"])) {
                $ProfileGender = $_GET["ProfileGender"];
                bb_custom_fields(0, 0, $ProfileGender, true, $ProfileType);
            } else {
                bb_custom_fields(0, $ProfileID, $ProfileGender, true, $ProfileType);
            }
            ?>
            </tbody>
        </table>
    </div>

    <div id="profile-manage-media" class="halfwidth alignright">
        <?php if (!empty($ProfileID)) : // Editing Record ?>
        <h3><?php _e("Gallery", bb_agency_TEXTDOMAIN) ?></h3>
        <script type="text/javascript">
            function confirmDelete(delMedia,mediaType) {
                if (confirm('Are you sure you want to delete this '+mediaType+'?')) {
                    document.location="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>&action=editRecord&ProfileID=<?php echo $ProfileID ?>&actionsub=photodelete&targetid="+delMedia;
                }
            }
        </script>
        <?php
            if (isset($_GET['actionsub'])) { // process submitted form

                switch( $_GET['actionsub'] ) {

                    case 'massphotodelete' :

                        $ids = '';
                        $ids = implode(",", $_GET['targetids']);
                        //get all the images

                        $queryImgConfirm = "SELECT `ProfileMediaID`, `ProfileMediaURL` FROM `$t_media` WHERE `ProfileID` = $ProfileID AND `ProfileMediaID` IN ($ids) AND `ProfileMediaType` = 'Image'";
                        $resultsImgConfirm = $wpdb->get_results($queryImgConfirm);
                        $countImgConfirm = count($resultsImgConfirm);
                        $mass_image_data = array();
                        foreach ($resultsImgConfirm as $dataImgConfirm) {
                            $mass_image_data[$dataImgConfirm->ProfileMediaID] = $dataImgConfirm->ProfileMediaURL;
                        }
                        //delete all the images from database
                        $massmediaids = implode(",", array_keys($mass_image_data));
                        $queryMassImageDelete = "DELETE FROM `$t_media` WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($massmediaids) AND ProfileMediaType = 'Image'";
                        $resultsMassImageDelete = $wpdb->query($queryMassImageDelete);

                        //delete images on the disk
                        $dirURL = bb_agency_UPLOADPATH . $ProfileGallery;
                        foreach ($mass_image_data as $mid => $ProfileMediaURL) {
                            if (!@unlink($dirURL . '/' . $ProfileMediaURL)) : ?>
                                <div id="message" class="error">
                                    <p><?php _e("Error removing", bb_agency_TEXTDOMAIN) ?> <strong><?php echo $ProfileMediaURL ?></strong>: <?php _e("file did not exist.", bb_agency_TEXTDOMAIN) ?>.</p></div>
                            <?php else : ?>
                                <div id="message" class="updated">
                                    <p>File <strong><?php echo $ProfileMediaURL ?></strong> <?php _e("successfully removed", bb_agency_TEXTDOMAIN) ?>.</p>
                                </div>
                            <?php endif;
                        }
                        break;

                    case 'photodelete' :
                        $deleteTargetID = $_GET["targetid"];

                        // Verify Record
                        $queryImgConfirm = "SELECT * FROM `$t_media` WHERE `ProfileID` =  '$ProfileID' AND `ProfileMediaID` =  '$deleteTargetID'";
                        
                        $images = $wpdb->get_results( $queryImgConfirm );

                        if (!empty($images)) : foreach ($images as $image) :

                            // Remove Record
                            $delete = "DELETE FROM `$t_media` WHERE `ProfileID` =  '$ProfileID' AND `ProfileMediaID` = '$image->ProfileMediaID'";
                            $results = $wpdb->query($delete);

                            if ($image->ProfileMediaType == "Demo Reel" || 
                                $image->ProfileMediaType == "Video Monologue" || 
                                $image->ProfileMediaType == "Video Slate") : ?>
                                <div id="message" class="updated"><p>File <strong><?php echo $image->ProfileMediaURL ?></strong> <?php _e("successfully removed", bb_agency_TEXTDOMAIN) ?>.</p></div>
                            <?php else : 
                                // Remove File
                                $dirURL = bb_agency_UPLOADPATH . $ProfileGallery;
                                if (!unlink($dirURL . '/' . $image->ProfileMediaURL)) : ?>
                                    <div id="message" class="error">
                                        <p><?php _e("Error removing", bb_agency_TEXTDOMAIN) ?> <strong><?php echo $image->ProfileMediaURL ?></strong>. <?php _e("File did not exist.", bb_agency_TEXTDOMAIN) ?>.</p>
                                    </div>
                                <?php else : ?>
                                    <div id="message" class="updated">
                                        <p>File <strong><?php echo $image->ProfileMediaURL ?></strong> <?php _e("successfully removed", bb_agency_TEXTDOMAIN) ?>.</p>
                                    </div>
                                <?php endif;
                            endif;
                        endforeach; endif; // is there record?
                        break;

                    case 'massphotoshow' :
                    case 'massphotohide' :

                        $ids = implode(",", $_GET['targetids']);
                        //get all the images

                        $queryImgConfirm = "SELECT `ProfileMediaID`, `ProfileMediaURL` FROM `$t_media` WHERE `ProfileID` = $ProfileID AND `ProfileMediaID` IN ($ids) AND `ProfileMediaType` = 'Image'";

                        $resultsImgConfirm = $wpdb->get_results($queryImgConfirm);
                        $countImgConfirm = count($resultsImgConfirm);
                        $mass_image_data = array();
                        
                        foreach ($resultsImgConfirm as $dataImgConfirm) {
                            $mass_image_data[$dataImgConfirm->ProfileMediaID] = $dataImgConfirm->ProfileMediaURL;
                        }
                        
                        // update database
                        $ids = implode(",", array_keys($mass_image_data));
                        $set = $_GET['actionsub'] == 'massphotoshow' ? 1 : 0;
                        $wpdb->query( "UPDATE `$t_media` SET `ProfileMediaLive` = $set WHERE `ProfileID` = $ProfileID AND `ProfileMediaID` IN ($ids) AND `ProfileMediaType` = 'Image'" );

                        break;
                }
            }

            // Get images
            $queryImg = "SELECT * FROM `$t_media` WHERE `ProfileID` = '$ProfileID' AND `ProfileMediaType` = 'Image' ORDER BY `ProfileMediaPrimary` DESC, `ProfileMediaID` DESC";

            bb_agency_debug($queryImg);

            $resultsImg = $wpdb->get_results($queryImg);
            $countImg = count($resultsImg);
            if (!empty($resultsImg)) :
                foreach ($resultsImg as $dataImg) :
                    $styleClass = '';
                    if ($dataImg->ProfileMediaPrimary) {
                        $styleBackground = "#900000";
                        $isChecked = " checked";
                        $isCheckedText = " Primary";
                        if ($countImg == 1) {
                            $toDelete = '<div class="delete"><a href="javascript:confirmDelete(\'' . $dataImg->ProfileMediaID . '\',\'' . $dataImg->ProfileMediaType . '\')"><span>Delete</span> &raquo;</a></div>';
                        } else {
                            $toDelete = '';
                            $massDelete = '';
                        }
                    } else {
                        $styleBackground = $dataImg->ProfileMediaLive ? "#000000" : "#888888";
                        $isChecked = '';
                        $isCheckedText = __( 'Primary', bb_agency_TEXTDOMAIN );
                        $massSelect = '<input type="checkbox" name="massgalsel" value="' . $dataImg->ProfileMediaID . '"> <span style="color:#FFFFFF">'.__('Select', bb_agency_TEXTDOMAIN).'</span>';
                    }
                    ?>
                    <div class="profileimage" style="background: <?php echo $styleBackground ?>" class="<?php echo $dataImg->ProfileMediaLive ? 'showing' : 'hidden' ?>">
                        <?php echo $toDelete ?>
                        <?php echo $toShowHide ?>
                        <img src="<?php echo bb_agency_UPLOADDIR . $ProfileGallery . '/' . $dataImg->ProfileMediaURL ?>" style="width: 100px; z-index: 1; \" />
                        <div class="primary" style="background: <?php echo $styleBackground ?>">
                            <input type="radio" name="ProfileMediaPrimary" value="<?php echo  $dataImg->ProfileMediaID ?>" <?php echo $isChecked ?> /> <?php echo $isCheckedText ?>
                            <div><?php echo $massSelect ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;

            if ($countImg < 1) : ?>
                <div><?php _e("There are no images loaded for this profile yet.", bb_agency_TEXTDOMAIN) ?></div>
            <?php else : ?>
                <div style="clear: both;"></div>
                <p>
                    <a id="mass_gallery_delete" href="#"><?php _e('Delete Selected Images', bb_agency_TEXTDOMAIN) ?></a>
                </p>
                <p>
                    <a id="mass_gallery_show" href="#"><?php _e('Show Selected Images', bb_agency_TEXTDOMAIN) ?></a>
                </p>
                <p>
                    <a id="mass_gallery_hide" href="#"><?php _e('Hide Selected Images', bb_agency_TEXTDOMAIN) ?></a>
                </p>
                <script language="javascript">

                    jQuery(document).ready(function($) {

                        var pID = <?php echo $ProfileID ?>;
                        var adminUrl = '<?php echo admin_url('admin.php?page='.$_GET['page']) ?>';

                        function getIds() {
                            var ids = '&';
                            $("input:checkbox[name=massgalsel]:checked").each(function() {
                                if(ids != '&'){
                                    ids += '&';
                                }
                                ids += 'targetids[]='+$(this).val();
                            });
                            return ids;
                        }

                        $('#mass_gallery_delete').on( 'click', function() {
                            mass_gallery_action( 'delete' )     
                        })

                        $('#mass_gallery_show').on( 'click', function() {
                            mass_gallery_action( 'show' )  
                        })

                        $('#mass_gallery_hide').on( 'click', function() {
                            mass_gallery_action( 'hide' )  
                        })

                        function mass_gallery_action( type ) {
                            var ids = getIds();

                            if( ids != '&'){
                                if(confirm("Do you want to "+type+" all the selected images?")){
                                    var url = adminUrl + "&action=editRecord&ProfileID=" + pID + "&actionsub=massphoto" + type + ids;
                                    document.location = url;
                                }
                            }
                            else{
                                alert("You have to select images to "+type);
                            }  
                        }
                    })

                </script>
            <?php endif; ?>
            <br /><br />
            <h3><?php _e("Media", bb_agency_TEXTDOMAIN) ?></h3>
            <p><?php _e("The following files (pdf, audio file, etc.) are associated with this record", bb_agency_TEXTDOMAIN) ?>.</p>
            <?php
            $queryMedia = "SELECT * FROM `$t_media` WHERE `ProfileID` =  '$ProfileID' AND `ProfileMediaType` <> 'Image'";
            $resultsMedia = $wpdb->get_results($queryMedia);
            $countMedia = count($resultsMedia);
            foreach ($resultsMedia as $dataMedia) :
                $deleteLink = '<a href="javascript:confirmDelete(\'' . $dataMedia->ProfileMediaID . '\',\'' . ($dataMedia->ProfileMediaType == 'Private' ? 'private file' : strtolower($dataMedia->ProfileMediaType)) . '\')">DELETE</a>';
                $galleryDir = bb_agency_UPLOADDIR . $ProfileGallery . '/' . $dataMedia->ProfileMediaURL;
                if ($dataMedia->ProfileMediaType == "Demo Reel" || 
                    $dataMedia->ProfileMediaType == "Video Monologue" || 
                    $dataMedia->ProfileMediaType == "Video Slate") : ?>
                    <div style="float: left; width: 120px; text-align: center; padding: 10px;">
                        <?php echo $dataMedia->ProfileMediaType ?><br />
                        <?php echo bb_agency_get_videothumbnail($dataMedia->ProfileMediaURL) ?><br />
                        <a href="http://www.youtube.com/watch?v=<?php echo $dataMedia->ProfileMediaURL ?>" target="_blank">Link to Video</a><br />
                        [<?php echo $deleteLink ?>]
                    </div>
                <?php else : ?>
                    <div>
                        <?php echo $dataMedia->ProfileMediaType ?>: <a href="<?php echo $galleryDir ?>" target="_blank"><?php echo $dataMedia->ProfileMediaTitle ?></a> [<?php echo $deleteLink ?>]
                    </div>
                <?php endif;
            endforeach;

        if ($countMedia < 1) : ?>
            <div>
                <em><?php _e("There are no additional media linked", bb_agency_TEXTDOMAIN) ?></em>
            </div>
        <?php endif; ?>
        <div style="clear: both;"></div>

    <?php endif; ?>

    <?php if (defined('bb_agency_ADMIN_MEDIA_UPLOAD') && bb_agency_ADMIN_MEDIA_UPLOAD) : ?>
        <h3><?php _e("Upload", bb_agency_TEXTDOMAIN) ?></h3>
        <p><?php _e("Upload new media using the forms below", bb_agency_TEXTDOMAIN) ?>.</p>

        <?php for ($i = 1; $i < 10; $i++) : ?>
        <div>Type: <select name="profileMedia<?php echo $i ?>Type">
            <option value="Image">Image</option>
            <option value="Headshot">Headshot</option>
            <option value="CompCard">Comp Card</option>
            <option value="Resume">Resume</option>
            <option value="VoiceDemo">Voice Demo</option>
            <option value="Private">Private</option>
            <?php echo bb_agency_getMediaCategories($ProfileGender) ?>
            </select>
            <input type="file" id="profileMedia<?php echo $i ?>" name="profileMedia<?php echo $i ?>" />
        </div>
        <?php endfor; ?>
        <p><?php _e("Paste the video URL below", bb_agency_TEXTDOMAIN) ?>.</p>

        <div>Type: <select name="profileMediaV1Type"><option selected><?php _e("Video Slate", bb_agency_TEXTDOMAIN) ?></option><option><?php _e("Video Monologue", bb_agency_TEXTDOMAIN) ?></option><option><?php _e("Demo Reel", bb_agency_TEXTDOMAIN) ?></option></select><textarea id='profileMediaV1' name='profileMediaV1'></textarea></div>
        <div>Type: <select name="profileMediaV2Type"><option><?php _e("Video Slate", bb_agency_TEXTDOMAIN) ?></option><option selected><?php _e("Video Monologue", bb_agency_TEXTDOMAIN) ?></option><option><?php _e("Demo Reel", bb_agency_TEXTDOMAIN) ?></option></select><textarea id='profileMediaV2' name='profileMediaV2'></textarea></div>
        <div>Type: <select name="profileMediaV3Type"><option><?php _e("Video Slate", bb_agency_TEXTDOMAIN) ?></option><option><?php _e("Video Monologue", bb_agency_TEXTDOMAIN) ?></option><option selected><?php _e("Demo Reel", bb_agency_TEXTDOMAIN) ?></option></select><textarea id='profileMediaV3' name='profileMediaV3'></textarea></div>
    <?php endif; ?>
    
        <div>
            <h3>Model Card</h3>
            <p>
                <a href="/card/<?php echo $ProfileGallery ?>.jpg?<?php echo time() ?>">
                    <img src="/card/<?php echo $ProfileGallery ?>.jpg?<?php echo time() ?>" width="400" alt="<?php echo $ProfileGallery ?> model card" />
                </a>
            </p>
        </div>
    <?php if ($profile->HasTalent && $profile->ProfileTalent) : ?>
        <div>
            <h3>LBDA Card</h3>
            <p>
                <a href="/lbda/<?php echo $ProfileGallery ?>.jpg?<?php echo time() ?>">
                    <img src="/lbda/<?php echo $ProfileGallery ?>.jpg?<?php echo time() ?>" width="400" alt="<?php echo $ProfileGallery ?> LBDA card" />
                </a>
            </p>
        </div>
    <?php endif; ?>

    </div>

    <div style="clear: both; "></div>

    <table class="form-table">
        <tbody>
            <tr valign="top">
              <th scope="row" colspan="2"><h3><?php _e("Classification", bb_agency_TEXTDOMAIN) ?></h3></th>
            </tr>
            <tr valign="top" id="ProfileType">
                <th scope="row"><?php _e("Classification", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <?php $types = $wpdb->get_results("SELECT * FROM `$t_data_type` ORDER BY `DataTypeTitle`");

                    if (!empty($types)) : 

                        $ProfileTypeArray = explode(',', $profile->ProfileType);
                    ?>
                    <fieldset>
                    <?php foreach ($types as $type) : ?>
                        <input type="checkbox" name="ProfileType[]" id="ProfileType_<?php echo $type->DataTypeID ?>" value="<?php echo $type->DataTypeID ?>" class="profile-type <?php if ($type->DataTypeTalent) echo 'talent'; ?>" <?php echo (!empty($ProfileTypeArray) && in_array($type->DataTypeID, $ProfileTypeArray)) ? ' checked="checked"' : '' ?> /><?php echo $type->DataTypeTitle ?><br />
                    <?php endforeach; ?>
                    <script>
                    jQuery(document).ready(function($) {

                        $('#ProfileType').find('input.profile-type').on('change', function() {
                            toggleTalent();
                        });

                        function toggleTalent() {
                            if ($('#ProfileType').find('input.profile-type.talent').prop('checked')) {
                                $('.profile-talent').show();
                            } else {
                                $('.profile-talent').hide();
                            }
                        }

                        toggleTalent();
                        
                    });
                    </script>
                    </fieldset>
                    <?php else : ?>
                        <?php _e("No items to select", bb_agency_TEXTDOMAIN) ?> <a href="<?php echo admin_url("admin.php?page=bb_agency_settings&ConfigID=5") ?>"><?php _e("Setup Options", bb_agency_TEXTDOMAIN) ?></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php $talents = $wpdb->get_results("SELECT * FROM `$t_data_talent` ORDER BY `DataTalentTitle`"); if (!empty($talents)) : ?>
            <tr valign="top" id="ProfileTalent" class="profile-talent">
                <th scope="row"><?php _e("Talent", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <fieldset><?php
                    $ProfileTalentArray = explode(",", $profile->ProfileTalent);

                    foreach ($talents as $talent) : ?>
                        <input type="checkbox" name="ProfileTalent[]" id="ProfileTalent_<?php echo $talent->DataTalentID ?>" value="<?php echo $talent->DataTalentID ?>" <?php echo (!empty($ProfileTalentArray) && in_array($talent->DataTalentID, $ProfileTalentArray)) ? ' checked="checked"' : '' ?> /><?php echo $talent->DataTalentTitle ?><br />
                    <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
            <?php $genres = $wpdb->get_results("SELECT * FROM `$t_data_genre`"); if (!empty($genres)) : ?>
            <tr valign="top" id="ProfileGenre" class="profile-talent">
                <th scope="row"><?php _e("Genre", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <fieldset><?php
                    $ProfileGenreArray = explode(",", $profile->ProfileGenre);

                    foreach ($genres as $genre) : ?>
                        <input type="checkbox" name="ProfileGenre[]" id="ProfileGenre_<?php echo $genre->DataGenreID ?>" value="<?php echo $genre->DataGenreID ?>" <?php echo (!empty($ProfileGenreArray) && in_array($genre->DataGenreID, $ProfileGenreArray)) ? ' checked="checked"' : '' ?> /><?php echo $genre->DataGenreTitle ?><br />
                    <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
            <?php endif; // end of genres ?>
            <?php $abilities = $wpdb->get_results("SELECT * FROM `$t_data_ability`"); if (!empty($abilities)) : ?>
            <tr valign="top" id="ProfileAbility" class="profile-talent">
                <th scope="row"><?php _e("Ability", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <fieldset>
                        <select name="ProfileAbility" size="1">
                            <option value=""> --- Ability --- </option>
                            <?php foreach ($abilities as $ability) : ?>
                            <option id="ProfileAbility_<?php echo $ability->DataAbilityID ?>" value="<?php echo $ability->DataAbilityID ?>" <?php selected($ability->DataAbilityID, $profile->ProfileAbility) ?>><?php echo $ability->DataAbilityTitle ?></option>
                            <?php endforeach; ?>
                        </select>
                    </fieldset>
                </td>
            </tr>
            <?php endif; // end of abilities ?>
            <?php endif; // end of talents ?>
            <tr valign="top">
                <th scope="row"><?php _e("Status", bb_agency_TEXTDOMAIN) ?>:</th>
                <td>
                    <select id="ProfileIsActive" name="ProfileIsActive">
                        <option value="1" <?php selected(1, $profile->ProfileIsActive) ?>><?php _e("Active", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="4" <?php selected(4, $profile->ProfileIsActive) ?>><?php _e("Active - Not Visible On Website", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="0" <?php selected(0, $profile->ProfileIsActive) ?>><?php _e("Inactive", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="2" <?php selected(2, $profile->ProfileIsActive) ?>><?php _e("Archived", bb_agency_TEXTDOMAIN) ?></option>
                        <option value="3" <?php selected(3, $profile->ProfileIsActive) ?>><?php _e("Pending Approval", bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Promotion", bb_agency_TEXTDOMAIN) ?>:</th>
                <td>
                    <input type="checkbox" name="ProfileIsFeatured" id="ProfileIsFeatured" value="1" <?php checked($profile->ProfileIsFeatured, 1, false) ?> /> Featured<br />
                </td>
            </tr>
            <?php if (isset($profile->ProfileUserLinked) && $profile->ProfileUserLinked > 0) : ?>
            <tr valign="top">
                <th scope="row"><?php _e("WordPress User", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <a href="<?php echo admin_url("user-edit.php") ?>?user_id=<?php echo $profile->ProfileUserLinked ?>&wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Dbb_agency_profiles">ID# <?php echo $profile->ProfileUserLinked ?></a>
    		      <input type='hidden' name='wpuserid' value="<?php echo $profile->ProfileUserLinked ?>" />
                </td>
            </tr>
            <?php endif;

            // Hidden Settings
            if ($_GET["mode"] == "override") : ?>
            <tr valign="top">
                <th scope="row"><?php _e("Date Updated", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="text" id="ProfileDateUpdated" name="ProfileDateUpdated" value="<?php echo $profile->ProfileDateUpdated ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Profile Views", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="text" id="ProfileStatHits" name="ProfileStatHits" value="<?php echo $profile->ProfileStatHits ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Profile Viewed Last", bb_agency_TEXTDOMAIN) ?></th>
                <td>
                    <input type="text" id="ProfileDateViewLast" name="ProfileDateViewLast" value="<?php echo $profile->ProfileDateViewLast ?>" />
                </td>
            </tr>
            <?php else : ?>
            <tr valign="top">
                <th scope="row"></th>
                <td>
                    <input type="hidden" id="ProfileDateUpdated" name="ProfileDateUpdated" value="<?php echo $profile->ProfileDateUpdated ?>" />
                    <input type="hidden" id="ProfileStatHits" name="ProfileStatHits" value="<?php echo $profile->ProfileStatHits ?>" />
                    <input type="hidden" id="ProfileDateViewLast" name="ProfileDateViewLast" value="<?php echo $profile->ProfileDateViewLast ?>" />
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($ProfileID) && ($ProfileID > 0)) :
    $t_job = table_agency_job;
    ?>
    <h3>Jobs</h3>
    <?php
    // as client
    $sql = "SELECT * FROM $t_job WHERE `JobClient` = $ProfileID";
    $results = $wpdb->get_results($sql);
    
    if (count($results)) :
        include('job/list_owned.php');
    endif; ?>

    <h4>Bookings</h4>
    <?php
    // booked
    $sql = "SELECT j.*, p.`ProfileContactDisplay` AS ClientName FROM `$t_job` j LEFT JOIN `$t_profile` p ON p.`ProfileID` = j.`JobClient` WHERE FIND_IN_SET($ProfileID, j.`JobModelBooked`)";
    $results = $wpdb->get_results($sql);
    
    if (count($results)) :
        include('job/list_quick.php');
    else : ?>
    <p>No bookings yet.</p>
    <?php endif; ?>

    <h4>Casting Calls</h4>
    <?php
    // castings
    $sql = "SELECT j.*, p.`ProfileContactDisplay` AS ClientName FROM `$t_job` j LEFT JOIN `$t_profile` p ON p.`ProfileID` = j.`JobClient` WHERE FIND_IN_SET($ProfileID, j.`JobModelCasted`)";
    $results = $wpdb->get_results($sql);
    
    if (count($results)) :
        include('job/list_quick.php');
    else : ?>
    <p>No casting calls yet.</p>
    <?php endif; ?>

    
    <?php _e('Last updated on', bb_agency_TEXTDOMAIN) ?> <?php echo bb_agency_human_date($profile->ProfileDateUpdated) ?>
    <p class="submit">
        <input type="hidden" name="ProfileID" value="<?php echo $ProfileID ?>" />
        <input type="hidden" name="action" value="editRecord" />
        <input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
    <?php else : ?>
    <p class="submit">
        <input type="hidden" name="action" value="addRecord" />
        <input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
    <?php endif; ?>
    </form>
    <?php
}

// End Manage



/* List Records **************************************************** */

function bb_display_list() {
    global $wpdb;

    $t_profile = table_agency_profile;
    $t_media = table_agency_profile_media;
    $t_data_type = table_agency_data_type;
    $t_data_talent = table_agency_data_talent;
    ?>
    <div class="wrap">
    <?php
    // Include Admin Menu
    include ("admin-menu.php");

    // Sort By
    $sort = '';
    if (!empty($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = "profile.`ProfileContactNameFirst`";
    }

    // Sort Order
    $dir = '';
    if (!empty($_GET['dir'])) {
        $dir = $_GET['dir'];
        if ($dir == "desc" || !isset($dir) || empty($dir)) {
            $sortDirection = "asc";
        } else {
            $sortDirection = "desc";
        }
    } else {
        $sortDirection = "desc";
        $dir = "asc";
    }

    // Filter
    $filter = "WHERE ";
    if (!empty($_GET['ProfileContactNameFirst']) || !empty($_GET['ProfileContactNameLast'])) {
        if (!empty($_GET['ProfileContactNameFirst'])) {
            $selectedNameFirst = $_GET['ProfileContactNameFirst'];
            $query .= '&ProfileContactNameFirst='. $selectedNameFirst;
        
            if(strpos($filter,'profile') > 0){
                $filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
            } else {
                $filter .= " profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
            }
        }
        if (!empty($_GET['ProfileContactNameLast'])) {
            $selectedNameLast = $_GET['ProfileContactNameLast'];
            $query .= "&ProfileContactNameLast=". $selectedNameLast;
            if(strpos($filter,'profile') > 0){
                $filter .= " AND profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
            } else {
                $filter .= " profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
            }
        }
    }
    if (!empty($_GET['ProfileLocationCity'])) {
        $selectedCity = $_GET['ProfileLocationCity'];
        $query .= "&ProfileLocationCity=". $selectedCity .'';
        if(strpos($filter,'profile') > 0){
            $filter .= " AND profile.ProfileLocationCity='". $selectedCity ."'";
        } else {
            $filter .= " profile.ProfileLocationCity='". $selectedCity ."'";
        }
    }
    if (!empty($_GET['ProfileType'])){
        $selectedType = strtolower($_GET['ProfileType']);
        $query .= "&ProfileType=". $selectedType .'';
        if(strpos($filter,'profile') > 0){
            $filter .= " AND profile.ProfileType = '$selectedType'";
        } else {
            $filter .= " profile.ProfileType = '$selectedType'";
        }
    }
    if (!empty($_GET['ProfileTalent'])){
        $selectedTalent = strtolower($_GET['ProfileTalent']);
        $query .= "&ProfileTalent=". $selectedTalent .'';
        if(strpos($filter,'profile') > 0){
             $filter .= " AND {$selectedTalent} IN (profile.ProfileTalent)";
        } else {
              $filter .= " {$selectedTalent} IN (profile.ProfileTalent)";
        }
    }
    if (isset($_GET['ProfileVisible'])){
        $selectedVisible = $_GET['ProfileVisible'];
        $query .= "&ProfileVisible=". $selectedVisible .'';
        if ($_GET['ProfileVisible'] != ''){
            if(strpos($filter,'profile') > 0){
                    $filter .= " AND profile.ProfileIsActive = '". $selectedVisible ."'" ;
            } else {
                    $filter .= " profile.ProfileIsActive = '". $selectedVisible . "'" ;
            }
        }
    }
    if (!empty($_GET['ProfileGender'])) {
        $ProfileGender = (int)$_GET['ProfileGender'];
        if ($ProfileGender) {
            if(strpos($filter,'profile') > 0){
                $filter .= " AND profile.`ProfileGender` = '$ProfileGender'";
            } else {
                $filter .= " profile.`ProfileGender` = '$ProfileGender'";
            }
        }
    }
    
    /*
     * Trap WHERE 
     */
    if (!strpos($filter, 'profile') > 0) { 
        $filter = '';
    }

    bb_agency_debug( $filter );

    // Paginate
    $rs = $wpdb->get_results("SELECT * FROM `$t_profile` profile LEFT JOIN `$t_data_type` profiletype ON profile.`ProfileType` = profiletype.`DataTypeID` $filter");

    if ($rs) {
        $items = count( $rs ); // number of total rows in the database

        if ($items > 0) {
            $p = new bb_agency_pagination;
            $p->items($items);
            $p->limit(50); // Limit entries per page
            $p->target("admin.php?page=" . $_GET['page'] . $query);
            $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
            $p->calculate(); // Calculates what to show
            $p->parameterName('paging');
            $p->adjacents(1); //No. of page away from the current page

            if (!isset($_GET['paging'])) {
                $p->page = 1;
            } else {
                $p->page = $_GET['paging'];
            }

            //Query for limit paging
            $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;
        } else {
            $limit = '';
        }

    // Add New Records
    
?>
<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder columns-2">
        <div id="postbox-container-1" class="postbox-container" style="width: 29%;">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">
                <div id="dashboard_right_now" class="postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e("Create New Profile", bb_agency_TEXTDOMAIN) ?></span></h3>
                    <div class="inside-x" style="padding: 10px 10px 0px 10px; ">
                        <?php echo sprintf(__("Currently %d Profiles", bb_agency_TEXTDOMAIN), $items) ?><br />
                        <?php
                            $queryGenderResult = $wpdb->get_results( "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender, ARRAY_A );
                            ?>
                            <p>
                            <?php if (!empty($queryGenderResult)) : foreach ($queryGenderResult as $gender) : ?>
                                <a class="button-primary" href="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>&amp;action=addRecord&amp;ProfileGender=<?php echo $fetchGender["GenderID"] ?>"><?php _e("Create New " . ucfirst($gender["GenderTitle"]), bb_agency_TEXTDOMAIN) ?></a>
                            <?php endforeach; ?>
                            </p>
                            <?php else : ?>
                                <p><?php echo sprintf(__('No Gender Found. <a href="%s">Create New Gender</a>', bb_agency_TEXTDOMAIN), admin_url('admin.php?page=bb_agency_settings&ampConfigID=5')) ?></p>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="postbox-container-2" class="postbox-container" style="width: 70%">
            <div id="side-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">
                <div id="dashboard_recent_drafts" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php echo __("Filter Profiles", bb_agency_TEXTDOMAIN ) ?></span></h3>
                    <div class="inside">

                        <form style="display: inline;" method="GET" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
                            <input type="hidden" name="page_index" id="page_index" value="<?php echo  $_GET['page_index'] ?>" />
                            <input type="hidden" name="page" id="page" value="<?php echo  $_GET['page'] ?>" />
                            <input type="hidden" name="type" value="name" />
                            <p id="filter-profiles">
                            <span>
                                <label><?php _e("First Name:", bb_agency_TEXTDOMAIN) ?></label>
                                <input type="text" class="regular-text" name="ProfileContactNameFirst" value="<?php echo $selectedNameFirst ?>" />
                            </span>
                            <span>
                                <label><?php _e("Last Name", bb_agency_TEXTDOMAIN) ?>:</label>
                                <input type="text" class="regular-text" name="ProfileContactNameLast" value="<?php echo $selectedNameLast ?>" />
                            </span>
                            <span>
                                <label><?php _e("Category", bb_agency_TEXTDOMAIN) ?>:</label>
                                <select name="ProfileType">
                                    <option value=""><?php _e("Any Category", bb_agency_TEXTDOMAIN) ?></option>
                                    <?php
                                    $query = "SELECT DataTypeID, DataTypeTitle FROM `$t_data_type` ORDER BY DataTypeTitle ASC";
                                    $results = $wpdb->get_results($query);
                                    $count = count($results);
                                    foreach ($results as $data) : ?>
                                    <option value="<?php echo $data->DataTypeID ?>" <?php selected($_GET['ProfileType'], $data->DataTypeID) ?>><?php echo $data->DataTypeTitle ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                          <span><?php _e("Status", bb_agency_TEXTDOMAIN) ?>:
                          <select name="ProfileVisible">
                            <option value=""><?php _e("Any Status", bb_agency_TEXTDOMAIN) ?></option>
                            <option value="1" <?php selected(1, $selectedVisible) ?>><?php _e("Active", bb_agency_TEXTDOMAIN) ?></option>
                            <option value="4" <?php selected(4, $selectedVisible) ?>><?php _e("Not Visible", bb_agency_TEXTDOMAIN) ?></option>
                            <option value="0" <?php selected(0, $selectedVisible) ?>><?php _e("Inactive", bb_agency_TEXTDOMAIN) ?></option>
                            <option value="2" <?php selected(2, $selectedVisible) ?>><?php _e("Archived", bb_agency_TEXTDOMAIN) ?></option>
                          </select></span>
                          <span><?php _e("Location", bb_agency_TEXTDOMAIN) ?>: 
                            <select name="ProfileLocationCity">
                                <option value=""><?php _e("Any Location", bb_agency_TEXTDOMAIN) ?></option>
                                <?php
                                $query = "SELECT DISTINCT `ProfileLocationCity`, `ProfileLocationState` FROM `$t_profile` ORDER BY `ProfileLocationState`, `ProfileLocationCity` ASC";
                                $results = $wpdb->get_results($query);
                                if (!empty($results)) : foreach ($results as $data) :
                                    if (!empty($data->ProfileLocationCity)) : ?>
                                        <option value="<?php echo $data->ProfileLocationCity ?>" <?php selected($selectedCity, $data->ProfileLocationCity) ?>><?php echo $data->ProfileLocationCity ?></option>
                                    <?php endif;
                                endforeach; endif; ?>
                            </select>
                        </span>
                        <span><?php _e("Gender", bb_agency_TEXTDOMAIN) ?>:
                            <select name="ProfileGender">
                                <option value=""><?php _e("Any Gender", bb_agency_TEXTDOMAIN) ?></option>
                                <?php
                                $query2 = "SELECT `GenderID`, `GenderTitle` FROM " . table_agency_data_gender . " ORDER BY `GenderID`";
                                $results2 = $wpdb->get_results($query2);
                                foreach ($results2 as $dataGender) : ?>
                                <option value="<?php echo  $dataGender->GenderID ?>" <?php selected($_GET['ProfileGender'], $dataGender->GenderID, false) ?>><?php echo $dataGender->GenderTitle ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="submit"><input type="submit" value="<?php _e("Filter", bb_agency_TEXTDOMAIN) ?>" class="button-primary" /></span>
                      </p></form>
                      <form style="display: inline; float: left; margin: 17px 5px 0px 0px;" method="GET" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
                          <input type="hidden" name="page_index" id="page_index" value="<?php echo $_GET['page_index'] ?>" />  
                          <input type="hidden" name="page" id="page" value="<?php echo  $_GET['page'] ?>" />
                          <input type="submit" value="<?php _e("Clear Filters", bb_agency_TEXTDOMAIN) ?>" class="button-secondary" />
                      </form>
                      <a style="display: inline; float: left; margin: 17px 5px 0px 0px;" href="<?php echo admin_url("admin.php?page=bb_agency_search") ?>" class="button-secondary"><?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?></a>

                        </div>
                    </div>

                </div>
            </div>

            <div class="tablenav-pages">
            <?php
            if ($items > 0) {
                echo $p->show();  // Echo out the list of paging. 
            }
            ?>
            </div>  

            <form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
                <table cellspacing="0" class="widefat fixed">
                    <thead>
                        <tr class="thead">
                            <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                            <th class="column-ProfileID" id="ProfileID" scope="col" style="width:50px;"><a href="<?php echo admin_url('admin.php?page=' . $_GET['page'] . '&sort=ProfileID&dir=' . $sortDirection) ?>">ID</a></th>
                            <th class="column-ProfileContactNameFirst" id="ProfileContactNameFirst" scope="col" style="width:150px;"><a href="<?php echo admin_url('admin.php?page=' . $_GET['page'] . '&sort=ProfileContactNameFirst&dir=' . $sortDirection) ?>">First Name</a></th>
                            <?php if (bb_agency_SITETYPE == 'bumps') : ?>
                            <th class="column-ProfilesProfileDate" id="ProfilesProfileDate" scope="col"><a href="<?php echo admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileDateDue&dir=" . $sortDirection) ?>">Due date</a></th>
                            <?php endif; ?>
                            <th class="column-ProfileLocationCity" id="ProfileLocationCity" scope="col"><a href="<?php echo admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationCity&dir=" . $sortDirection) ?>">Town</a></th>
                            <th class="column-ProfileLocationState" id="ProfileLocationState" scope="col"><a href="<?php echo admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationState&dir=" . $sortDirection) ?>">County</a></th>
                            <th class="column-ProfileDetails" id="ProfileDetails" scope="col">Category</th>
                            <th class="column-ProfileDetails" id="ProfileDetails" scope="col">Images</th>
                            <th class="column-ProfileStatHits" id="ProfileStatHits" scope="col">Views</th>
                            <th class="column-ProfileDateViewLast" id="ProfileDateViewLast" scope="col" style="width:125px;">Last Viewed Date</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="thead">
                            <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                            <th class="column" scope="col">ID</th>
                            <th class="column" scope="col">First Name</th>
                            <?php if (bb_agency_SITETYPE == 'bumps') : ?>
                            <th class="column" scope="col">Due date</th>
                            <?php endif; ?>
                            <th class="column" scope="col">Town</th>
                            <th class="column" scope="col">County</th>
                            <th class="column" scope="col">Category</th>
                            <th class="column" scope="col">Images</th>
                            <th class="column" scope="col">Views</th>
                            <th class="column" scope="col">Last Viewed</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM `$t_profile` profile LEFT JOIN `$t_data_type` profiletype ON profile.`ProfileType` = profiletype.`DataTypeID` $filter  ORDER BY $sort $dir $limit";
                    $results2 = $wpdb->get_results($query);
                    $count = count($results2);
                    foreach ($results2 as $data) {

                        $ProfileID = $data->ProfileID;
                        $ProfileGallery = stripslashes($data->ProfileGallery);
                        $ProfileContactNameFirst = stripslashes($data->ProfileContactNameFirst);
                        $ProfileContactNameLast = stripslashes($data->ProfileContactNameLast);
                        $ProfileLocationCity = bb_agency_strtoproper(stripslashes($data->ProfileLocationCity));
                        $ProfileLocationState = stripslashes($data->ProfileLocationState);
                        $ProfileGender = stripslashes($data->ProfileGender);
                        $ProfileDateDue = stripslashes($data->ProfileDateDue);
                        $ProfileDateBirth = stripslashes($data->ProfileDateBirth);
                        $ProfileStatHits = stripslashes($data->ProfileStatHits);
                        $ProfileDateViewLast = stripslashes($data->ProfileDateViewLast);
                        if ($data->ProfileIsActive == 0) {
                            // Inactive
                            $rowColor = ' style="background: #FFEBE8"';
                        } elseif ($data->ProfileIsActive == 1) {
                            // Active
                            $rowColor = '';
                        } elseif ($data->ProfileIsActive == 2) {
                            // Archived
                            $rowColor = ' style="background: #dadada"';
                        } elseif ($data->ProfileIsActive == 3) {
                            // Pending Approval
                            $rowColor = ' style="background: #DD4B39"';
                        }

                        // check if she's given birth
                        if (bb_agency_SITETYPE == 'bumps' && bb_agency_ismumtobe($data->ProfileType) && bb_agency_datepassed($ProfileDateDue)) {

                            // switch category
                            $ptypes = explode(',', $data->ProfileType);
                            for($i = 0; $i < count($ptypes); $i++){
                                if ($ptypes[$i] == bb_agency_MUMSTOBE_ID)
                                    $ptypes[$i] = bb_agency_AFTERBIRTH_ID;
                            }

                            $data->ProfileType = implode(',', $ptypes);
                            
                            // recategorize as family
                            bb_agency_recategorize_profile($data->ProfileID, $data->ProfileType);              
                        }
                        
                        // Get Data Type Title
                        if (strpos($data->ProfileType, ",") > 0){
                            $title = explode(",",$data->ProfileType);
                            $new_title = '';
                            foreach($title as $t){
                                $id = (int)$t;
                                $get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type .  
                                             " WHERE DataTypeID = " . $id;   
                                $title = $wpdb->get_var($get_title); 
                                if ($title) {            
                                    $new_title .= "," . $title; 
                                }
                            }
                            $new_title = substr($new_title,1);
                        } else {
                            $new_title = '';
                            $id = (int)$data->ProfileType;
                            $get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type .  
                                         " WHERE DataTypeID = " . $id;   
                            $title = $wpdb->get_var($get_title);             

                            if ($title){
                                $new_title = $title; 
                            }
                        }
                        
                        $DataTypeTitle = stripslashes($new_title);

                        $profileImageCount = $wpdb->get_var("SELECT COUNT(*) FROM `$t_media` WHERE `ProfileID` = '$ProfileID' AND `ProfileMediaType` = 'Image'");

                        ?>
                        <tr"<?php echo $rowColor ?>">
                            <th class="check-column" scope="row">
                              <input type="checkbox" value="<?php echo $ProfileID ?>" class="administrator" id=\'' . $ProfileID . "\" name=\'' . $ProfileID . "\"/>
                            </th>
                            <td class="ProfileID column-ProfileID"><?php echo $ProfileID ?></td>
                            <td class="ProfileContactNameFirst column-ProfileContactNameFirst">
                              <?php echo $ProfileContactNameFirst ?>
                              <div class="row-actions">
                                <span class="edit"><a href="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>&amp;action=editRecord&amp;ProfileID=<?php echo $ProfileID ?>" title="<?php _e("Edit this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?></a> | </span>
                                <span class="edit"><a href="<?php echo bb_agency_PROFILEDIR . $bb_agency_UPLOADDIR . $ProfileGallery ?>" title="<?php _e("View", bb_agency_TEXTDOMAIN) ?>" target="_blank"><?php _e("View", bb_agency_TEXTDOMAIN) ?></a> | </span>
                                <span class="delete"><a class="submitdelete" href="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>&amp;action=deleteRecord&amp;ProfileID=<?php echo $ProfileID ?>"  onclick="if ( confirm('<?php _e("You are about to delete the profile for ", bb_agency_TEXTDOMAIN) ?> <?php echo $ProfileContactNameFirst . " " . $ProfileContactNameLast ?>. <?php _e("Cancel", bb_agency_TEXTDOMAIN) ?> <?php _e("to stop", bb_agency_TEXTDOMAIN) ?>, <?php _e("OK", bb_agency_TEXTDOMAIN) ?> <?php _e("to delete", bb_agency_TEXTDOMAIN) ?>.') ) { return true;}return false;" title="<?php _e("Delete this Record", bb_agency_TEXTDOMAIN) ?>"><?php _e("Delete", bb_agency_TEXTDOMAIN) ?></a> </span>
                              </div>
                            </td>
                            <?php if (bb_agency_SITETYPE == 'bumps') : ?>
                            <td class="ProfilesProfileDate column-ProfilesProfileDate"><?php echo (is_null($ProfileDateDue) || $ProfileDateDue == '0000-00-00' ? $ProfileDateBirth : $ProfileDateDue) ?></td>
                            <?php endif; ?>
                            <td class="ProfileLocationCity column-ProfileLocationCity"><?php echo $ProfileLocationCity ?></td>
                            <td class="ProfileLocationCity column-ProfileLocationState"><?php echo $ProfileLocationState ?></td>
                            <td class="ProfileDetails column-ProfileDetails"><?php echo $DataTypeTitle ?></td>
                            <td class="ProfileDetails column-ProfileDetails"><?php echo $profileImageCount ?></td>
                            <td class="ProfileStatHits column-ProfileStatHits"><?php echo $ProfileStatHits ?></td>
                            <td class="ProfileDateViewLast column-ProfileDateViewLast"><?php echo bb_agency_makeago(bb_agency_convertdatetime($ProfileDateViewLast), $bb_agency_option_locationtimezone); ?></td>
                        </tr>
                        <?php
                    }

                    if ($count < 1) {
                        if (isset($filter)) : ?>
                            <tr>
                                <th class="check-column" scope="row"></th>
                                <td class="name column-name" colspan="5">
                                   <p>No profiles found with this criteria.</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th class="check-column" scope="row"></th>
                                <td class="name column-name" colspan="5">
                                    <p>There aren't any profiles loaded yet!</p>
                                </td>
                            </tr>
                        <?php endif;
                    }
                    ?>
                    </tbody>
                </table>
                <div class="tablenav">
                    <div class='tablenav-pages'>
                    <?php
                    if ($items > 0) {
                        echo $p->show();  // Echo out the list of paging. 
                    }
                    ?>
                    </div>
                </div>
                <p class="submit">
                    <input type="hidden" value="deleteRecord" name="action" />
                    <input type="submit" value="<?php _e('Delete Profiles') ?>" class="button-primary" name="submit" />   
                </p>
            </form>
    </div>
<?php
    }
}

function bb_agency_get_profile_fields() {
    $fields = array(
        'ProfileContactDisplay',
        'ProfileContactNameFirst',
        'ProfileContactNameLast',
        'ProfileContactEmail',
        'ProfileContactWebsite',
        'ProfileGender',
        'ProfileDateBirth',
        'ProfileLocationStreet',
        'ProfileLocationCity',
        'ProfileLocationState',
        'ProfileLocationZip',
        'ProfileLocationCountry',
        'ProfileContactPhoneHome', 
        'ProfileContactPhoneCell', 
        'ProfileContactPhoneWork',
        'ProfileType',
        'ProfileTalent',
        'ProfileGenre',
        'ProfileAbility',
        'ProfileIsActive',
        'ProfileIsFeatured',
        'ProfileIsPromoted',
        'ProfileStatHits',
        'ProfileDateViewLast'
    );

    if (bb_agency_SITETYPE == 'bumps') {
        $fields[] = 'ProfileDateDue';
    }

    return $fields;
}