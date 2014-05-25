<?php

global $wpdb;
define("LabelPlural", "jobs");
define("LabelSingular", "job");

$bb_agency_options_arr = get_option('bb_agency_options');
$bb_agency_option_unittype = $bb_agency_options_arr['bb_agency_option_unittype'];
$bb_agency_option_showsocial = $bb_agency_options_arr['bb_agency_option_showsocial'];
$bb_agency_option_agencyimagemaxheight = $bb_agency_options_arr['bb_agency_option_agencyimagemaxheight'];
if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) {
    $bb_agency_option_agencyimagemaxheight = 800;
}
$bb_agency_option_profilenaming = (int) $bb_agency_options_arr['bb_agency_option_profilenaming'];
$bb_agency_option_locationtimezone = (int) $bb_agency_options_arr['bb_agency_option_locationtimezone'];

$t_job = table_agency_job;

// *************************************************************************************************** //
// Handle Post Actions

if (isset($_POST['action'])) {

    // Get Post State
    $action = $_POST['action'];
    switch ($action) {

        $error = array();

        // *************************************************************************************************** //
        // Add/Edit Record
        case 'editJob' :
            if (!isset($_POST['JobID']) || !$_POST['JobID']) {
                $error[] = 'No job id was passed.';
            } else {
                $JobID = $_POST['JobID'];
            }

        case 'addJob' :


            // Check for required fields
            $required = array(
                'JobTitle' => 'title',
                'JobClient' => 'client',
                'JobLocation' => 'location',
            );
            foreach ($required as $field => $label) {
                $job = LabelSingular;
                if ($_POST[$field] == '') {
                    $error[] = "The $job must have a $label.";
                }
            }

            if (empty($error)) {

                $fields = array(
                    'JobTitle',
                    'JobClient',
                    'JobRate',
                    'JobPONumber',
                    'JobNotes',
                    'JobLocation',
                    'JobDate'
                );

                // Create insert query
                
                foreach ($fields as $field) {
                    $sqlData[] = $field .' = "'. $wpdb->escape($_POST[$field]) . '"';
                }

                $sqlData[] = 'JobDateUpdated = NOW()';

                // get latitude and longitude
                if (!empty($_POST['JobLocation'])) {

                    if ($location = bbagency_geocode($_POST['JobLocation'])) {
                        // geocode address
                        $sqlData[] = 'JobLocationLatitude = "'.$location['lat'].'"';
                        $sqlData[] = 'JobLocationLongitude = "'.$location['lng'].'"';
                    } else {
                        echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of '%s'", bb_agencyinteract_TEXTDOMAIN), $_POST['JobLocation']) . '</p></div>';
                    }
                }

                if ($action == 'addJob') {
                    $sql = "INSERT INTO $t_job SET ". implode(', ', $sqlData);
                } else {
                    $sql = "UPDATE $t_job SET ". implode(', ', $sqlData) ." WHERE JobID = $JobID";
                }
                
                $results = $wpdb->query($sql) or die(mysql_error());

                echo '<div id="message" class="updated"><p>';
                if ($action == 'addJob') {
                    $JobID = $wpdb->insert_id;
                    _e("New job added successfully.", bb_agency_TEXTDOMAIN);
                } else {
                    _e("Job successfully updated.", bb_agency_TEXTDOMAIN);
                }
                echo '</p></div>';
                
            } else {
                echo ('<div id="message" class="error"><p>' . __("Error. Please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) . '</p><ul><li>'.implode('</li><li>', $error).'</li></ul></div>');
            }

            break;

        // *************************************************************************************************** //
        // Edit Record
        case 'editRecord':
            if (!empty($JobContactNameFirst) && !empty($JobID)) {

                $fields = array(
                    'JobTitle',
                    'JobClient',
                    'JobRate',
                    'JobPONumber',
                    'JobNotes',
                    'JobLocation',
                    'JobDate'
                );

                // Create insert query
                
                foreach ($fields as $field) {
                    $sqlData[] = $field .' = "'. $wpdb->escape($_POST[$field]) . '"';

                    if (preg_match("/^JobLocation/", $field) && $_POST[$field]) {
                        $arrAddress[] = $_POST[$field];
                    }
                }

                $sqlData[] = 'JobDateUpdated = NOW()';

                // get latitude and longitude
                if (!empty($_POST['JobLocation'])) {

                    if ($location = bbagency_geocode($_POST['JobLocation'])) {
                        // geocode address
                        $sqlData[] = 'JobLocationLatitude = "'.$location['lat'].'"';
                        $sqlData[] = 'JobLocationLongitude = "'.$location['lng'].'"';
                    } else {
                        echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of '%s'", bb_agencyinteract_TEXTDOMAIN), $_POST['JobLocation']) . '</p></div>';
                    }
                }

                // create update sql
                $update = "UPDATE $t_job SET ". implode(', ', $sqlData) ." WHERE JobID = $JobID";

                $results = $wpdb->query($update) or die(mysql_error());

            break;

        // *************************************************************************************************** //
        // Delete bulk
        case 'deleteRecord':
            foreach ($_POST as $JobID) {

                // Verify Record
                $queryDelete = "SELECT * FROM " . table_agency_profile . " WHERE JobID =  \"" . $JobID . "\"";
                $resultsDelete = mysql_query($queryDelete);

                while ($dataDelete = mysql_fetch_array($resultsDelete)) {
                    $JobGallery = $dataDelete['JobGallery'];

                    // Remove Job
                    $delete = "DELETE FROM " . table_agency_profile . " WHERE JobID = \"" . $JobID . "\"";
                    $results = $wpdb->query($delete);
                    // Remove Media
                    $delete = "DELETE FROM " . table_agency_profile_media . " WHERE JobID = \"" . $JobID . "\"";
                    $results = $wpdb->query($delete);

                    if (isset($JobGallery)) {
                        // Remove Folder
                        $dir = bb_agency_UPLOADPATH . $JobGallery . "/";
                        $mydir = opendir($dir);
                        while (false !== ($file = readdir($mydir))) {
                            if ($file != "." && $file != "..") {
                                unlink($dir . $file) or DIE("<div id=\"message\" class=\"error\"><p>" . __("Error removing:", bb_agency_TEXTDOMAIN) . $dir . $file . "</p></div>");
                            }
                        }
                        // Remove Directory
                        if (is_dir($dir)) {
                            rmdir($dir) or DIE("<div id=\"message\" class=\"error\"><p>" . __("Error removing:", bb_agency_TEXTDOMAIN) . $dir . $file . "</p></div>");
                        }
                        closedir($mydir);
                    } else {
                        echo ("<div id=\"message\" class=\"error\"><p>" . __("No Valid Record Found.", bb_agency_TEXTDOMAIN) . "</p></div>");
                    }

                    echo ('<div id="message" class="updated"><p>' . __("Job deleted successfully!", bb_agency_TEXTDOMAIN) . '</p></div>');
                } // is there record?
                //---------- Delete users but re-assign to Admin User -------------//
                // Gimme an admin:
                $AdminID = $wpdb->prepare("SELECT $wpdb->users.ID FROM $wpdb->users WHERE user_login = 'admin'");
                if ($AdminID > 0) {
                    
                } else {
                    $AdminID = 1;
                }
                /// Now delete
                wp_delete_user($dataDelete["JobUserLinked"], $AdminID);
            }
            bb_display_list();
            exit;
            break;
    }
}
// *************************************************************************************************** //
// Delete Single
elseif ($_GET['action'] == "deleteRecord") {

    $JobID = $_GET['JobID'];
    // Verify Record
    $queryDelete = "SELECT * FROM " . table_agency_profile . " WHERE JobID =  \"" . $JobID . "\"";
    $resultsDelete = mysql_query($queryDelete);
    while ($dataDelete = mysql_fetch_array($resultsDelete)) {
        $JobGallery = $dataDelete['JobGallery'];

        // Remove Job
        $delete = "DELETE FROM " . table_agency_profile . " WHERE JobID = \"" . $JobID . "\"";
        $results = $wpdb->query($delete);
        // Remove Media
        $delete = "DELETE FROM " . table_agency_profile_media . " WHERE JobID = \"" . $JobID . "\"";
        $results = $wpdb->query($delete);

        if (isset($JobGallery)) {
            // Remove Folder
            $dir = bb_agency_UPLOADPATH . $JobGallery . "/";
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

        wp_delete_user($dataDelete["JobUserLinked"]);
        echo ('<div id="message" class="updated"><p>' . __("Job deleted successfully!", bb_agency_TEXTDOMAIN) . '</p></div>');
    } // is there record?
    bb_display_list();
}
// *************************************************************************************************** //
// Show Edit Record
elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {

    $action = $_GET['action'];
    $JobID = $_GET['JobID'];

    bb_display_manage($JobID);
} else {
// *************************************************************************************************** //
// Show List
    bb_display_list();
}

// *************************************************************************************************** //
// Manage Record
function bb_display_manage($JobID) {
    global $wpdb;
    $bb_agency_options_arr = get_option('bb_agency_options');
    $bb_agency_option_unittype = $bb_agency_options_arr['bb_agency_option_unittype'];
    $bb_agency_option_showsocial = $bb_agency_options_arr['bb_agency_option_showsocial'];
    $bb_agency_option_agencyimagemaxheight = $bb_agency_options_arr['bb_agency_option_agencyimagemaxheight'];
    if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) {
        $bb_agency_option_agencyimagemaxheight = 800;
    }
    $bb_agency_option_profilenaming = (int) $bb_agency_options_arr['bb_agency_option_profilenaming'];
    $bb_agency_option_locationcountry = $bb_agency_options_arr['bb_agency_option_locationcountry'];
    echo "<div class=\"wrap\">\n";
    // Include Admin Menu
    include ("admin-menu.php");

    if (!empty($JobID) && ($JobID > 0)) {

        $query = "SELECT * FROM " . table_agency_profile . " WHERE JobID='$JobID'";
        $results = mysql_query($query) or die(__("Error, query failed", bb_agency_TEXTDOMAIN));
        $count = mysql_num_rows($results);

        while ($data = mysql_fetch_array($results)) {
            $JobID = $data['JobID'];
            $JobUserLinked = $data['JobUserLinked'];
            $JobGallery = stripslashes($data['JobGallery']);
            $JobContactDisplay = stripslashes($data['JobContactDisplay']);
            $JobContactNameFirst = stripslashes($data['JobContactNameFirst']);
            $JobContactNameLast = stripslashes($data['JobContactNameLast']);
            $JobContactEmail = stripslashes($data['JobContactEmail']);
            $JobContactWebsite = stripslashes($data['JobContactWebsite']);
            $JobContactLinkFacebook = stripslashes($data['JobContactLinkFacebook']);
            $JobContactLinkTwitter = stripslashes($data['JobContactLinkTwitter']);
            $JobContactLinkYouTube = stripslashes($data['JobContactLinkYouTube']);
            $JobContactLinkFlickr = stripslashes($data['JobContactLinkFlickr']);
            $JobContactPhoneHome = stripslashes($data['JobContactPhoneHome']);
            $JobContactPhoneCell = stripslashes($data['JobContactPhoneCell']);
            $JobContactPhoneWork = stripslashes($data['JobContactPhoneWork']);
            $JobGender = stripslashes($data['JobGender']);
            $JobTypeArray = stripslashes($data['JobType']);
           
			$JobDateBirth = stripslashes($data['JobDateBirth']);
            $JobDateDue = stripslashes($data['JobDateDue']);
            $JobLocationStreet = stripslashes($data['JobLocationStreet']);
            $JobLocationCity = stripslashes($data['JobLocationCity']);
            $JobLocationState = stripslashes($data['JobLocationState']);
            $JobLocationZip = stripslashes($data['JobLocationZip']);
            $JobLocationCountry = stripslashes($data['JobLocationCountry']);
            $JobLocationLatitude = stripslashes($data['JobLocationLatitude']);
            $JobLocationLongitude = stripslashes($data['JobLocationLongitude']);

            $JobDateUpdated = stripslashes($data['JobDateUpdated']);
            $JobType = stripslashes($data['JobType']);
            $JobIsActive = stripslashes($data['JobIsActive']);
            $JobIsFeatured = stripslashes($data['JobIsFeatured']);
            $JobIsPromoted = stripslashes($data['JobIsPromoted']);
            $JobStatHits = stripslashes($data['JobStatHits']);
            $JobDateViewLast = stripslashes($data['JobDateViewLast']);

            echo "<h2 class=\"title\">" . __("Edit", bb_agency_TEXTDOMAIN) . " " . LabelSingular . " <a class=\"button-secondary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">" . __("Back to " . LabelSingular . " List", bb_agency_TEXTDOMAIN) . "</a> <a class=\"button-primary\" href=\"" . bb_agency_PROFILEDIR . $bb_agency_UPLOADDIR . $JobGallery . "/\" target=\"_blank\">Preview Job</a></h2>\n";
            echo "<p>" . __("Make changes in the form below to edit a", bb_agency_TEXTDOMAIN) . " " . LabelSingular . ". <strong>" . __("Required fields are marked", bb_agency_TEXTDOMAIN) . "Required fields are marked *</strong></p>\n";
        }
    } else {
        // Set default values for new records
        $JobsModelDate = $date;
        $JobType = 1;
        $JobGender = "Unknown";
        $JobIsActive = 1;
        $JobLocationCountry = $bb_agency_option_locationcountry;

        echo "<h2 class=\"title\">Add New " . LabelSingular . " <a class=\"button-primary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">" . __("Back to " . LabelSingular . " List", bb_agency_TEXTDOMAIN) . "</a></h2>\n";
        echo "<p>" . __("Fill in the form below to add a new", bb_agency_TEXTDOMAIN) . " " . LabelSingular . ". <strong>" . __("Required fields are marked", bb_agency_TEXTDOMAIN) . " *</strong></p>\n";
    }

    if ($_GET["action"] == "add") {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=add&JobGender=" . $_GET["JobGender"] . "\">\n";
    } else {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
    }
    echo "<div style=\"float: left; width: 50%; \">\n";
    echo " <table class=\"form-table\">\n";
    echo "  <tbody>\n";
    echo "    <tr colspan=\"2\">\n";
    echo "      <th scope=\"row\"><h3>" . __("Contact Information", bb_agency_TEXTDOMAIN) . "</h3></th>\n";
    echo "    </tr>\n";
    if ((!empty($JobID) && ($JobID > 0)) || ($bb_agency_option_profilenaming == 2)) { // Editing Record
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Display Name", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobContactDisplay\" name=\"JobContactDisplay\" value=\"" . $JobContactDisplay . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    if (!empty($JobID) && ($JobID > 0)) { // Editing Record
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Gallery Folder", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";

        if (!empty($JobGallery) && is_dir(bb_agency_UPLOADPATH . $JobGallery)) {
            echo "<div id=\"message\"><span class=\"updated\">" . __("Folder", bb_agency_TEXTDOMAIN) . " <strong>" . $JobGallery . "</strong> " . __("Exists", bb_agency_TEXTDOMAIN) . "</span></div>\n";
            echo "<input type=\"hidden\" id=\"JobGallery\" name=\"JobGallery\" value=\"" . $JobGallery . "\" />\n";
        } else {
            echo "<input type=\"text\" id=\"JobGallery\" name=\"JobGallery\" value=\"" . $JobGallery . "\" />\n";
            echo "<div id=\"message\"><span class=\"error\">" . __("No Folder Exists", bb_agency_TEXTDOMAIN) . "</span>\n";
        }
        echo "              </div>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
    }
    echo "    <tr valign=\"top\" class=\"required\">\n";
    echo "      <th scope=\"row\">" . __("First Name", bb_agency_TEXTDOMAIN) . "*</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobContactNameFirst\" name=\"JobContactNameFirst\" value=\"" . $JobContactNameFirst . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\" class=\"required\">\n";
    echo "      <th scope=\"row\">" . __("Last Name", bb_agency_TEXTDOMAIN) . "*</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobContactNameLast\" name=\"JobContactNameLast\" value=\"" . $JobContactNameLast . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // password
    if ((isset($_GET["action"]) && $_GET["action"] == "add") && function_exists('bb_agencyinteract_approvemembers')) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Username", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobUsername\" name=\"JobUsername\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Password", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobPassword\" name=\"JobPassword\" />\n";
        echo "          <input type=\"button\" onclick=\"javascript:document.getElementById('JobPassword').value=Math.random().toString(36).substr(2,6);\" value=\"Generate Password\"  name=\"GeneratePassword\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Send Login details?", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"checkbox\"  name=\"JobNotifyUser\" /> Send login details to the new user and admin by email.\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }

    // Private Information
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\" colspan=\"2\"><h3>" . __("Private Information", bb_agency_TEXTDOMAIN) . "</h3>" . __("The following information will NOT appear in public areas and is for administrative use only.", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\" class=\"required\">\n";
    echo "      <th scope=\"row\">" . __("Email Address", bb_agency_TEXTDOMAIN) . "*</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobContactEmail\" name=\"JobContactEmail\" value=\"" . $JobContactEmail . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Website", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobContactWebsite\" name=\"JobContactWebsite\" value=\"" . $JobContactWebsite . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Phone", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "      <fieldset>\n";
    echo "          <label>Home:</label><br /><input type=\"text\" id=\"JobContactPhoneHome\" name=\"JobContactPhoneHome\" value=\"" . $JobContactPhoneHome . "\" /><br />\n";
    echo "          <label>Mobile:</label><br /><input type=\"text\" id=\"JobContactPhoneCell\" name=\"JobContactPhoneCell\" value=\"" . $JobContactPhoneCell . "\" /><br />\n";
    echo "          <label>Work:</label><br /><input type=\"text\" id=\"JobContactPhoneWork\" name=\"JobContactPhoneWork\" value=\"" . $JobContactPhoneWork . "\" /><br />\n";
    echo "      </fieldset>\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    // Address
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Street", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobLocationStreet\" name=\"JobLocationStreet\" value=\"" . $JobLocationStreet . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Town", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobLocationCity\" name=\"JobLocationCity\" value=\"" . $JobLocationCity . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("County", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobLocationState\" name=\"JobLocationState\" value=\"" . $JobLocationState . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Post code", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobLocationZip\" name=\"JobLocationZip\" value=\"" . $JobLocationZip . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Country", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"JobLocationCountry\" name=\"JobLocationCountry\" value=\"" . $JobLocationCountry . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // display location map
    if ($JobLocationLatitude != '' && $JobLocationLongitude != '') : ?>
        <tr valign="top">
            <td scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?></td>
            <td><?php bbagency_map($JobLocationLatitude, $JobLocationLongitude, $JobContactDisplay) ?></td>
        </tr>
    <?php endif;

    // Custom Admin Fields
    // JobCustomView = 1 , Private
    if (isset($_GET["JobGender"])) {
        $JobGender = $_GET["JobGender"];
        bb_custom_fields(1, 0, $JobGender, true);
    } else {
        bb_custom_fields(1, $JobID, $JobGender, true);
    }

    // Public Information
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\" colspan=\"2\"><h3>" . __("Public Information", bb_agency_TEXTDOMAIN) . "</h3>The following information may appear in profile pages.</th>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Gender", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>";
    echo "<select name=\"JobGender\" id=\"JobGender\">\n";
   

    $JobGender1 = get_user_meta($JobUserLinked, "bb_agency_interact_pgender", true);
   
	if($JobGender==""){
		$JobGender = $_GET["JobGender"];
	}elseif($JobGender1!=""){
		$JobGender =$JobGender1 ;
	}
	
    $query1 = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . "";
    $results1 = mysql_query($query1);
    $count1 = mysql_num_rows($results1);
    if ($count1 > 0) {
        if (empty($GenderID) || ($GenderID < 1)) {
            echo " <option value=\"0\" selected>--</option>\n";
        }
        while ($data1 = mysql_fetch_array($results1)) {
            echo " <option value=\"" . $data1["GenderID"] . "\" " . selected($JobGender, $data1["GenderID"]) . ">" . $data1["GenderTitle"] . "</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "" . __("No items to select", bb_restaurant_TEXTDOMAIN) . ".";
    }
    echo "        </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Birth date", bb_agency_TEXTDOMAIN) . " <em>YYYY-MM-DD</em></th>\n";
    echo "      <td>\n";
    echo "          <input class=\"bbdatepicker\" type=\"text\" id=\"JobDateBirth\" name=\"JobDateBirth\" value=\"" . $JobDateBirth . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Due date", bb_agency_TEXTDOMAIN) . " <em>YYYY-MM-DD</em></th>\n";
    echo "      <td>\n";
    echo "          <input class=\"bbdatepicker\" type=\"text\" id=\"JobDateDue\" name=\"JobDateDue\" value=\"" . $JobDateDue . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // Load custom fields , Public  = 0, JobCustomGender = true
    // JobCustomView = 1 , Private
    if (isset($_GET["JobGender"])) {
        $JobGender = $_GET["JobGender"];
        bb_custom_fields(0, 0, $JobGender, true);
    } else {
        bb_custom_fields(0, $JobID, $JobGender, true);
    }

    echo "  </tbody>\n";
    echo " </table>\n";
    echo "</div>\n";

    echo "<div id=\"profile-manage-media\" style=\"float: left; width: 50%; \">\n";

    if (!empty($JobID) && ($JobID > 0)) { // Editing Record
        echo "      <h3>" . __("Gallery", bb_agency_TEXTDOMAIN) . "</h3>\n";

        echo "<script type='text/javascript'>\n";
        echo "function confirmDelete(delMedia,mediaType) {\n";
        echo "  if (confirm('Are you sure you want to delete this '+mediaType+'?')) {\n";
        echo "  document.location= '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&JobID=" . $JobID . "&actionsub=photodelete&targetid='+delMedia;";
        echo "  }\n";
        echo "}\n";
        echo "</script>\n";

        //mass delte
        if ($_GET["actionsub"] == "massphotodelete" && is_array($_GET['targetids'])) {
            $massmediaids = '';
            $massmediaids = implode(",", $_GET['targetids']);
            //get all the images

            $queryImgConfirm = "SELECT JobMediaID,JobMediaURL FROM " . table_agency_profile_media . " WHERE JobID = $JobID AND JobMediaID IN ($massmediaids) AND JobMediaType = 'Image'";
            $resultsImgConfirm = mysql_query($queryImgConfirm);
            $countImgConfirm = mysql_num_rows($resultsImgConfirm);
            $mass_image_data = array();
            while ($dataImgConfirm = mysql_fetch_array($resultsImgConfirm)) {
                $mass_image_data[$dataImgConfirm['JobMediaID']] = $dataImgConfirm['JobMediaURL'];
            }
            //delete all the images from database
            $massmediaids = implode(",", array_keys($mass_image_data));
            $queryMassImageDelete = "DELETE FROM " . table_agency_profile_media . " WHERE JobID = $JobID AND JobMediaID IN ($massmediaids) AND JobMediaType = 'Image'";
            $resultsMassImageDelete = $wpdb->query($queryMassImageDelete);
            //delete images on the disk
            $dirURL = bb_agency_UPLOADPATH . $JobGallery;
            foreach ($mass_image_data as $mid => $JobMediaURL) {
                if (!unlink($dirURL . "/" . $JobMediaURL)) {
                    echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", bb_agency_TEXTDOMAIN) . " <strong>" . $JobMediaURL . "</strong>. " . __("File did not exist.", bb_agency_TEXTDOMAIN) . ".</p></div>");
                } else {
                    echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $JobMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                }
            }
        }

        // Are we deleting?
        if ($_GET["actionsub"] == "photodelete") {
            $deleteTargetID = $_GET["targetid"];

            // Verify Record
            $queryImgConfirm = "SELECT * FROM " . table_agency_profile_media . " WHERE JobID =  \"" . $JobID . "\" AND JobMediaID =  \"" . $deleteTargetID . "\"";
            $resultsImgConfirm = mysql_query($queryImgConfirm);
            $countImgConfirm = mysql_num_rows($resultsImgConfirm);
            while ($dataImgConfirm = mysql_fetch_array($resultsImgConfirm)) {
                $JobMediaID = $dataImgConfirm['JobMediaID'];
                $JobMediaType = $dataImgConfirm['JobMediaType'];
                $JobMediaURL = $dataImgConfirm['JobMediaURL'];

                // Remove Record
                $delete = "DELETE FROM " . table_agency_profile_media . " WHERE JobID =  \"" . $JobID . "\" AND JobMediaID=$JobMediaID";
                $results = $wpdb->query($delete);

                if ($JobMediaType == "Demo Reel" || $JobMediaType == "Video Monologue" || $JobMediaType == "Video Slate") {
                    echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $JobMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                } else {
                    // Remove File
                    $dirURL = bb_agency_UPLOADPATH . $JobGallery;
                    if (!unlink($dirURL . "/" . $JobMediaURL)) {
                        echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", bb_agency_TEXTDOMAIN) . " <strong>" . $JobMediaURL . "</strong>. " . __("File did not exist.", bb_agency_TEXTDOMAIN) . ".</p></div>");
                    } else {
                        echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $JobMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                    }
                }
            } // is there record?
        }
        // Go about our biz-nazz
        $queryImg = "SELECT * FROM " . table_agency_profile_media . " WHERE JobID =  \"" . $JobID . "\" AND JobMediaType = \"Image\" ORDER BY JobMediaPrimary DESC, JobMediaID DESC";
        $resultsImg = mysql_query($queryImg);
        $countImg = mysql_num_rows($resultsImg);
        while ($dataImg = mysql_fetch_array($resultsImg)) {
            if ($dataImg['JobMediaPrimary']) {
                $styleBackground = "#900000";
                $isChecked = " checked";
                $isCheckedText = " Primary";
                if ($countImg == 1) {
                    $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('" . $dataImg['JobMediaID'] . "','" . $dataImg['JobMediaType'] . "')\"><span>Delete</span> &raquo;</a></div>\n";
                } else {
                    $toDelete = "";
                    $massDelete = "";
                }
            } else {
                $styleBackground = "#000000";
                $isChecked = "";
                $isCheckedText = " Select";
                $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('" . $dataImg['JobMediaID'] . "','" . $dataImg['JobMediaType'] . "')\"><span>Delete</span> &raquo;</a></div>\n";
                $massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['JobMediaID'] . '"> <span style="color:#FFFFFF">Delete</span>';
            }
            echo "<div class=\"profileimage\" style=\"background: " . $styleBackground . "; \">\n" . $toDelete . "";
            echo "  <img src=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataImg['JobMediaURL'] . "\" style=\"width: 100px; z-index: 1; \" />\n";
            echo "  <div class=\"primary\" style=\"background: " . $styleBackground . "; \"><input type=\"radio\" name=\"JobMediaPrimary\" value=\"" . $dataImg['JobMediaID'] . "\" " . $isChecked . " /> " .
            $isCheckedText . "<div>$massDelete</div></div>\n";

            echo "</div>\n";
        }
        if ($countImg < 1) {
            echo "<div>" . __("There are no images loaded for this profile yet.", bb_agency_TEXTDOMAIN) . "</div>\n";
        }

        echo "      <div style=\"clear: both;\"></div>\n";
        echo '<a href="javascript:confirm_mass_gallery_delete();">Delete Selected Images</a>';
        echo '<script language="javascript">';
        echo 'function confirm_mass_gallery_delete(){';
        echo 'jQuery(document).ready(function() {';
        echo "var mas_del_ids = '&';";
        echo 'jQuery("input:checkbox[name=massgaldel]:checked").each(function() {';
        echo "if(mas_del_ids != '&'){";
        echo "mas_del_ids += '&';";
        echo '}';

        echo "mas_del_ids += 'targetids[]='+jQuery(this).val();";
        echo "});";

        echo "if( mas_del_ids != '&'){ ";
        echo 'if(confirm("Do you want to delete all the selected images?")){';



        echo "urlmassdelete = '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&JobID=" . $JobID . "&actionsub=massphotodelete' + mas_del_ids;";
        echo 'document.location = urlmassdelete;';
        echo '}
    }
    else{
        alert("You have to select images to delete");
    }
});

}
</script>';



        echo "      <br><br><h3>" . __("Media", bb_agencyinteract_TEXTDOMAIN) . "</h3>\n";
        echo "      <p>" . __("The following files (pdf, audio file, etc.) are associated with this record", bb_agencyinteract_TEXTDOMAIN) . ".</p>\n";

        $queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE JobID =  \"" . $JobID . "\" AND JobMediaType <> \"Image\"";
        $resultsMedia = mysql_query($queryMedia);
        $countMedia = mysql_num_rows($resultsMedia);
        while ($dataMedia = mysql_fetch_array($resultsMedia)) {
            if ($dataMedia['JobMediaType'] == "Demo Reel" || $dataMedia['JobMediaType'] == "Video Monologue" || $dataMedia['JobMediaType'] == "Video Slate") {
                $outVideoMedia .= "<div style=\"float: left; width: 120px; text-align: center; padding: 10px; \">" . $dataMedia['JobMediaType'] . "<br />" . bb_agency_get_videothumbnail($dataMedia['JobMediaURL']) . "<br /><a href=\"http://www.youtube.com/watch?v=" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['JobMediaType'] == "VoiceDemo") {
                $outLinkVoiceDemo .= "<div>" . $dataMedia['JobMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">" . $dataMedia['JobMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['JobMediaType'] == "Resume") {
                $outLinkResume .= "<div>" . $dataMedia['JobMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">" . $dataMedia['JobMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['JobMediaType'] == "Headshot") {
                $outLinkHeadShot .= "<div>" . $dataMedia['JobMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">" . $dataMedia['JobMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['JobMediaType'] == "CompCard") {
                $outLinkComCard .= "<div>" . $dataMedia['JobMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">" . $dataMedia['JobMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            } else {
                $outCustomMediaLink .= "<div>" . $dataMedia['JobMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $JobGallery . "/" . $dataMedia['JobMediaURL'] . "\" target=\"_blank\">" . $dataMedia['JobMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['JobMediaID'] . "','" . $dataMedia['JobMediaType'] . "')\">DELETE</a>]</div>\n";
            }
        }
        echo '<div style=\"width:500px;\">';
        echo $outLinkVoiceDemo;
        echo '</div>';
        echo '<div style=\"width:500px;\">';
        echo $outLinkResume;
        echo '</div>';
        echo '<div style=\"width:500px;\">';
        echo $outLinkHeadShot;
        echo '</div>';
        echo '<div style=\"width:500px;\">';
        echo $outLinkComCard;
        echo '</div>';
        echo '<div style=\"width:500px;\">';
        echo $outCustomMediaLink;
        echo '</div>';
        echo $outVideoMedia;

        if ($countMedia < 1) {
            echo "<div><em>" . __("There are no additional media linked", bb_agencyinteract_TEXTDOMAIN) . "</em></div>\n";
        }
        echo "      <div style=\"clear: both;\"></div>\n";
        echo "      <h3>" . __("Upload", bb_agency_TEXTDOMAIN) . "</h3>\n";
        echo "      <p>" . __("Upload new media using the forms below", bb_agency_TEXTDOMAIN) . ".</p>\n";

        for ($i = 1; $i < 10; $i++) {
            echo "<div>Type: <select name=\"profileMedia" . $i . "Type\"><option value=\"Image\">Image</option><option value=\"Headshot\">Headshot</option><option value=\"CompCard\">Comp Card</option><option value=\"Resume\">Resume</option><option value=\"VoiceDemo\">Voice Demo</option>";
            bb_agency_getMediaCategories($JobGender);
            echo"</select><input type='file' id='profileMedia" . $i . "' name='profileMedia" . $i . "' /></div>\n";
        }
        echo "      <p>" . __("Paste the video URL below", bb_agency_TEXTDOMAIN) . ".</p>\n";

        echo "<div>Type: <select name=\"profileMediaV1Type\"><option selected>" . __("Video Slate", bb_agency_TEXTDOMAIN) . "</option><option>" . __("Video Monologue", bb_agency_TEXTDOMAIN) . "</option><option>" . __("Demo Reel", bb_agency_TEXTDOMAIN) . "</option></select><textarea id='profileMediaV1' name='profileMediaV1'></textarea></div>\n";
        echo "<div>Type: <select name=\"profileMediaV2Type\"><option>" . __("Video Slate", bb_agency_TEXTDOMAIN) . "</option><option selected>" . __("Video Monologue", bb_agency_TEXTDOMAIN) . "</option><option>" . __("Demo Reel", bb_agency_TEXTDOMAIN) . "</option></select><textarea id='profileMediaV2' name='profileMediaV2'></textarea></div>\n";
        echo "<div>Type: <select name=\"profileMediaV3Type\"><option>" . __("Video Slate", bb_agency_TEXTDOMAIN) . "</option><option>" . __("Video Monologue", bb_agency_TEXTDOMAIN) . "</option><option selected>" . __("Demo Reel", bb_agency_TEXTDOMAIN) . "</option></select><textarea id='profileMediaV3' name='profileMediaV3'></textarea></div>\n";
    }
    echo "</div>\n";

    echo "<div style=\"clear: both; \"></div>\n";

    echo "<table class=\"form-table\">\n";
    echo " <tbody>\n";


    // Account Information  
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\" colspan=\"2\"><h3>" . __("Classification", bb_agency_TEXTDOMAIN) . "</h3></th>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Classification", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "      <fieldset>\n";
    $JobTypeArray = explode(",", $JobTypeArray);
	
	$query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
    $results3 = mysql_query($query3);
    $count3 = mysql_num_rows($results3);
    $action = @$_GET["action"];
    while ($data3 = mysql_fetch_array($results3)) {
        if ($action == "add") {
            echo "<input type=\"checkbox\" name=\"JobType[]\" value=\"" . $data3['DataTypeID'] . "\" id=\"JobType[]\" /> " . $data3['DataTypeTitle'] . "<br />\n";
        }
        if ($action == "editRecord") {
            echo "<input type=\"checkbox\" name=\"JobType[]\" id=\"JobType[]\" value=\"" . $data3['DataTypeID'] . "\"";
            if (in_array($data3['DataTypeID'], $JobTypeArray) && isset($_GET["action"]) == "editRecord") {
                echo " checked=\"checked\"";
            } echo "/> " . $data3['DataTypeTitle'] . "<br />\n";
        }
    }
    echo "      </fieldset>\n";
    if ($count3 < 1) {
        echo "" . __("No items to select", bb_agency_TEXTDOMAIN) . ". <a href='" . admin_url("admin.php?page=bb_agency_settings&ConfigID=5") . "'>" . __("Setup Options", bb_agency_TEXTDOMAIN) . "</a>\n";
    }

    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "        <th scope=\"row\">" . __("Status", bb_agency_TEXTDOMAIN) . ":</th>\n";
    echo "        <td><select id=\"JobIsActive\" name=\"JobIsActive\">\n";
    echo "            <option value=\"1\"" . selected(1, $JobIsActive) . ">" . __("Active", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"4\"" . selected(4, $JobIsActive) . ">" . __("Active - Not Visible On Website", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"0\"" . selected(0, $JobIsActive) . ">" . __("Inactive", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"2\"" . selected(2, $JobIsActive) . ">" . __("Archived", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"3\"" . selected(3, $JobIsActive) . ">" . __("Pending Approval", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "          </select></td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "        <th scope=\"row\">" . __("Promotion", bb_agency_TEXTDOMAIN) . ":</th>\n";
    echo "        <td>\n";
    echo "          <input type=\"checkbox\" name=\"JobIsFeatured\" id=\"JobIsFeatured\" value=\"1\"". checked($JobIsFeatured, 1, false) . " /> Featured<br />\n";
    echo "        </td>\n";
    echo "    </tr>\n";
    /*
    if (function_exists('bb_agencyinteract_approvemembers')) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Membership", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"checkbox\" name=\"JobIsPromoted\" id=\"JobIsPromoted\" value=\"1\"". checked($JobIsPromoted, 1, false) ." /> Rising Star<br />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    */

    if (isset($JobUserLinked) && $JobUserLinked > 0) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("WordPress User", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "        <a href=\"". admin_url("user-edit.php") ."?user_id=". $JobUserLinked ."&wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Dbb_agency_profiles\">ID# ". $JobUserLinked ."</a>";
		echo "        <input type='hidden' name='wpuserid' value='".$JobUserLinked."' />";
        echo "      </td>\n";
        echo "    </tr>\n";
    }


    // Hidden Settings
    if ($_GET["mode"] == "override") {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Date Updated", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobDateUpdated\" name=\"JobDateUpdated\" value=\"" . $JobDateUpdated . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Job Views", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobStatHits\" name=\"JobStatHits\" value=\"" . $JobStatHits . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Job Viewed Last", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"JobDateViewLast\" name=\"JobDateViewLast\" value=\"" . $JobDateViewLast . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    } else {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\"></th>\n";
        echo "      <td>\n";
        echo "          <input type=\"hidden\" id=\"JobDateUpdated\" name=\"JobDateUpdated\" value=\"" . $JobDateUpdated . "\" />\n";
        echo "          <input type=\"hidden\" id=\"JobStatHits\" name=\"JobStatHits\" value=\"" . $JobStatHits . "\" />\n";
        echo "          <input type=\"hidden\" id=\"JobDateViewLast\" name=\"JobDateViewLast\" value=\"" . $JobDateViewLast . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    echo "  </tbody>\n";
    echo "</table>\n";

    if (!empty($JobID) && ($JobID > 0)) {
        echo "" . __("Last updated on", bb_agency_TEXTDOMAIN) . ": " . $JobDateUpdated . "\n";

        echo "<p class=\"submit\">\n";
        echo "     <input type=\"hidden\" name=\"JobID\" value=\"" . $JobID . "\" />\n";
        echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
        echo "     <input type=\"submit\" name=\"submit\" value=\"" . __("Update Record", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
        echo "</p>\n";
    } else {
        echo "<p class=\"submit\">\n";
        echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
        echo "     <input type=\"submit\" name=\"submit\" value=\"" . __("Create Record", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
        echo "</p>\n";
    }
    echo "</form>\n";
}

// End Manage



/* List Records **************************************************** */

function bb_display_list() {
    global $wpdb;
    $bb_agency_options_arr = get_option('bb_agency_options');
    $bb_agency_option_locationtimezone = (int) $bb_agency_options_arr['bb_agency_option_locationtimezone'];
    echo "<div class=\"wrap\">\n";
    // Include Admin Menu
    include ("admin-menu.php");

    // Sort By
    $sort = "";
    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = "profile.JobContactNameFirst";
    }

    // Sort Order
    $dir = "";
    if (isset($_GET['dir']) && !empty($_GET['dir'])) {
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
        if ((isset($_GET['JobContactNameFirst']) && !empty($_GET['JobContactNameFirst'])) || isset($_GET['JobContactNameLast']) && !empty($_GET['JobContactNameLast'])){
            if (isset($_GET['JobContactNameFirst']) && !empty($_GET['JobContactNameFirst'])){
            $selectedNameFirst = $_GET['JobContactNameFirst'];
            $query .= "&JobContactNameFirst=". $selectedNameFirst ."";
            
              if(strpos($filter,'profile') > 0){
                    $filter .= " AND profile.JobContactNameFirst LIKE '". $selectedNameFirst ."%'";
              } else {
                    $filter .= " profile.JobContactNameFirst LIKE '". $selectedNameFirst ."%'";
              }
            }
            if (isset($_GET['JobContactNameLast']) && !empty($_GET['JobContactNameLast'])){
            $selectedNameLast = $_GET['JobContactNameLast'];
            $query .= "&JobContactNameLast=". $selectedNameLast ."";
                if(strpos($filter,'profile') > 0){
                       $filter .= " AND profile.JobContactNameLast LIKE '". $selectedNameLast ."%'";
                } else {
                       $filter .= " profile.JobContactNameLast LIKE '". $selectedNameLast ."%'";
                }
            }
        }
        if (isset($_GET['JobLocationCity']) && !empty($_GET['JobLocationCity'])){
            $selectedCity = $_GET['JobLocationCity'];
            $query .= "&JobLocationCity=". $selectedCity ."";
            if(strpos($filter,'profile') > 0){
                    $filter .= " AND profile.JobLocationCity='". $selectedCity ."'";
            } else {
                    $filter .= " profile.JobLocationCity='". $selectedCity ."'";
            }
        }
        if (isset($_GET['JobType']) && !empty($_GET['JobType'])){
            $selectedType = strtolower($_GET['JobType']);
            $query .= "&JobType=". $selectedType ."";
            if(strpos($filter,'profile') > 0){
                 $filter .= " AND profile.JobType LIKE '%". $selectedType ."%'";
            } else {
                  $filter .= " profile.JobType LIKE '%". $selectedType ."%'";
            }
        }
        if (isset($_GET['JobVisible'])){
            $selectedVisible = $_GET['JobVisible'];
            $query .= "&JobVisible=". $selectedVisible ."";
            if($_GET['JobVisible'] != ""){
                    if(strpos($filter,'profile') > 0){
                            $filter .= " AND profile.JobIsActive = '". $selectedVisible ."'" ;
                    } else {
                            $filter .= " profile.JobIsActive = '". $selectedVisible . "'" ;
                    }
            }
        }
        if (isset($_GET['JobGender']) && !empty($_GET['JobGender'])){
            $JobGender = (int)$_GET['JobGender'];
            if($JobGender)
              if(strpos($filter,'profile') > 0){
                    $filter .= " AND profile.JobGender='".$JobGender."'";
              } else {
                    $filter .= " profile.JobGender='".$JobGender."'";
              }
        }
        
        /*
         * Trap WHERE 
         */
        if(!strpos($filter, 'profile') > 0){
                $filter = "";
        }

    
    //Paginate
    $items = mysql_num_rows(mysql_query("SELECT * FROM " . table_agency_profile . " profile LEFT JOIN " . table_agency_data_type . " profiletype ON profile.JobType = profiletype.DataTypeID " . $filter . "")); // number of total rows in the database
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
        $limit = "";
    }

    /*
     * Add New Records
     */
?>

<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder columns-2">

        <div id="postbox-container-1" class="postbox-container" style="width: 29%;">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">

                <div id="dashboard_right_now" class="postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php echo __("Create New Job", bb_agency_TEXTDOMAIN); ?></span></h3>
                    <div class="inside-x" style="padding: 10px 10px 0px 10px; ">
                        <?php echo __("Currently " . $items . " Jobs", bb_agency_TEXTDOMAIN); ?><br />
                        <?php

                            $queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . " ");
                            $queryGenderCount = mysql_num_rows($queryGenderResult);
                            echo "<p>";
                            while ($fetchGender = mysql_fetch_assoc($queryGenderResult)) {
                                echo "<a class=\"button-primary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=add&JobGender=" . $fetchGender["GenderID"] . "\">" . __("Create New " . ucfirst($fetchGender["GenderTitle"]) . "", bb_agency_TEXTDOMAIN) . "</a>\n";
                            }
                            echo "</p>";
                            if ($queryGenderCount < 1) {
                                echo "<p>" . __("No Gender Found. <a href=\"" . admin_url("admin.php?page=bb_agency_settings&ampConfigID=5") . "\">Create New Gender</a>", bb_agency_TEXTDOMAIN) . "</p>\n";
                            }
                        ?>

                    </div>
                </div>

            </div>
        </div>

        <div id="postbox-container-2" class="postbox-container" style="width: 70%">
            <div id="side-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">

                <div id="dashboard_recent_drafts" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php echo __("Filter Jobs", bb_agency_TEXTDOMAIN ) ?></span></h3>
                    <div class="inside">

<?php

    /*
     * Filtering Records
     */

    echo "          <form style=\"display: inline;\" method=\"GET\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
    echo "              <input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"" . $_GET['page_index'] . "\" />\n";
    echo "              <input type=\"hidden\" name=\"page\" id=\"page\" value=\"" . $_GET['page'] . "\" />\n";
    echo "              <input type=\"hidden\" name=\"type\" value=\"name\" />\n";
    echo "              <p id=\"filter-profiles\">\n";
    echo "              <span>" . __("<label>First Name:</label>", bb_agency_TEXTDOMAIN) . "<input type=\"text\" name=\"JobContactNameFirst\" value=\"" . $selectedNameFirst . "\" /></span>\n";
    echo "              <span>" . __("<label>Last Name:</label>", bb_agency_TEXTDOMAIN) . "<input type=\"text\" name=\"JobContactNameLast\" value=\"" . $selectedNameLast . "\" /></span>\n";
    
    echo "              <span>" . __("<label>Category:</label>", bb_agency_TEXTDOMAIN) . "\n";
    echo "              <select name=\"JobType\">\n";
    echo "                <option value=\"\">" . __("Any Category", bb_agency_TEXTDOMAIN) . "</option>";

    $query = "SELECT DataTypeID, DataTypeTitle FROM " . table_agency_data_type . " ORDER BY DataTypeTitle ASC";
    $results = mysql_query($query);
    $count = mysql_num_rows($results);
    while ($data = mysql_fetch_array($results)) {
        echo "<option value=\"" . $data['DataTypeID'] . "\" " . selected($_GET['JobType'], $data["DataTypeID"]) . "\">" . $data['DataTypeTitle'] . "</option>\n";
    }
    echo "              </select></span>\n";
    echo "              <span>" . __("Status", bb_agency_TEXTDOMAIN) . ":\n";
    echo "              <select name=\"JobVisible\">\n";
    echo "                <option value=\"\">" . __("Any Status", bb_agency_TEXTDOMAIN) . "</option>";
    echo "                <option value=\"1\"" . selected(1, $selectedVisible) . ">" . __("Active", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "                <option value=\"4\"" . selected(4, $selectedVisible) . ">" . __("Not Visible", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "                <option value=\"0\"" . selected(0, $selectedVisible) . ">" . __("Inactive", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "                <option value=\"2\"" . selected(2, $selectedVisible) . ">" . __("Archived", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "              </select></span>\n";
    echo "              <span>" . __("Location", bb_agency_TEXTDOMAIN) . ": \n";
    echo "              <select name=\"JobLocationCity\">\n";
    echo "                <option value=\"\">" . __("Any Location", bb_agency_TEXTDOMAIN) . "</option>";

    $query = "SELECT DISTINCT JobLocationCity, JobLocationState FROM " . table_agency_profile . " ORDER BY JobLocationState, JobLocationCity ASC";
    $results = mysql_query($query);
    $count = mysql_num_rows($results);
    while ($data = mysql_fetch_array($results)) {
        if (isset($data['JobLocationCity']) && !empty($data['JobLocationCity'])) {
            echo "<option value=\"" . $data['JobLocationCity'] . "\" " . selected($selectedCity, $data["JobLocationCity"]) . "\">" . $data['JobLocationCity'] . ", " . strtoupper($dataLocation["JobLocationState"]) . "</option>\n";
        }
    }
    echo "              </select></span>\n";
    echo "              <span>" . __("Gender", bb_agency_TEXTDOMAIN) . ":\n";
    echo "              <select name=\"JobGender\">\n";
    echo "                  <option value=\"\">" . __("Any Gender", bb_agency_TEXTDOMAIN) . "</option>\n";
    $query2 = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . " ORDER BY GenderID";
    $results2 = mysql_query($query2);
    while ($dataGender = mysql_fetch_array($results2)) {
        echo "<option value=\"" . $dataGender["GenderID"] . "\"" . selected($_GET["JobGender"], $dataGender["GenderID"], false) . ">" . $dataGender["GenderTitle"] . "</option>";
    }
    echo "              </select></span>\n";
    echo "              <span class=\"submit\"><input type=\"submit\" value=\"" . __("Filter", bb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" /></span>\n";
    echo "          </p></form>\n";
    echo "          <form style=\"display: inline; float: left; margin: 17px 5px 0px 0px;\" method=\"GET\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
    echo "              <input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"" . $_GET['page_index'] . "\" />  \n";
    echo "              <input type=\"hidden\" name=\"page\" id=\"page\" value=\"" . $_GET['page'] . "\" />\n";
    echo "              <input type=\"submit\" value=\"" . __("Clear Filters", bb_agency_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
    echo "          </form>\n";
    echo "          <a  style=\"display: inline; float: left; margin: 17px 5px 0px 0px;\" href=\"" . admin_url("admin.php?page=bb_agency_search") . "\" class=\"button-secondary\">" . __("Advanced Search", bb_agency_TEXTDOMAIN) . "</a>\n";


?>

                    </div>
                </div>

            </div>
        </div>
<?php
    echo "  <div class=\"tablenav-pages\">\n";
    if ($items > 0) {
        echo $p->show();  // Echo out the list of paging. 
    }
    echo "  </div>\n";
                            

    echo "<form method=\"post\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
    echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
    echo " <thead>\n";
    echo "    <tr class=\"thead\">\n";
    echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
    echo "        <th class=\"column-JobID\" id=\"JobID\" scope=\"col\" style=\"width:50px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobID&dir=" . $sortDirection) . "\">ID</a></th>\n";
    echo "        <th class=\"column-JobContactNameFirst\" id=\"JobContactNameFirst\" scope=\"col\" style=\"width:150px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobContactNameFirst&dir=" . $sortDirection) . "\">First Name</a></th>\n";
//    echo "        <th class=\"column-JobContactNameLast\" id=\"JobContactNameLast\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobContactNameLast&dir=" . $sortDirection) . "\">Last Name</a></th>\n";
//    echo "        <th class=\"column-JobGender\" id=\"JobGender\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobGender&dir=" . $sortDirection) . "\">Gender</a></th>\n";
    echo "        <th class=\"column-JobsJobDate\" id=\"JobsJobDate\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobDateDue&dir=" . $sortDirection) . "\">Due date</a></th>\n";
    echo "        <th class=\"column-JobLocationCity\" id=\"JobLocationCity\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobLocationCity&dir=" . $sortDirection) . "\">Town</a></th>\n";
    echo "        <th class=\"column-JobLocationState\" id=\"JobLocationState\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=JobLocationState&dir=" . $sortDirection) . "\">County</a></th>\n";
    echo "        <th class=\"column-JobDetails\" id=\"JobDetails\" scope=\"col\">Category</th>\n";
    echo "        <th class=\"column-JobDetails\" id=\"JobDetails\" scope=\"col\">Images</th>\n";
    echo "        <th class=\"column-JobStatHits\" id=\"JobStatHits\" scope=\"col\">Views</th>\n";
    echo "        <th class=\"column-JobDateViewLast\" id=\"JobDateViewLast\" scope=\"col\" style=\"width:125px;\">Last Viewed Date</th>\n";
    echo "    </tr>\n";
    echo " </thead>\n";
    echo " <tfoot>\n";
    echo "    <tr class=\"thead\">\n";
    echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
    echo "        <th class=\"column\" scope=\"col\">ID</th>\n";
    echo "        <th class=\"column\" scope=\"col\">First Name</th>\n";
//    echo "        <th class=\"column\" scope=\"col\">Last Name</th>\n";
//    echo "        <th class=\"column\" scope=\"col\">Gender</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Due date</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Town</th>\n";
    echo "        <th class=\"column\" scope=\"col\">County</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Category</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Images</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Views</th>\n";
    echo "        <th class=\"column\" scope=\"col\">Last Viewed</th>\n";
    echo "    </tr>\n";
    echo " </tfoot>\n";
    echo " <tbody>\n";

    $query = "SELECT * FROM " . table_agency_profile . " profile LEFT JOIN " . table_agency_data_type . " profiletype ON profile.JobType = profiletype.DataTypeID " . $filter . " ORDER BY $sort $dir $limit";
    $results2 = mysql_query($query);
    $count = mysql_num_rows($results2);
    while ($data = mysql_fetch_array($results2)) {

        $JobID = $data['JobID'];
        $JobGallery = stripslashes($data['JobGallery']);
        $JobContactNameFirst = stripslashes($data['JobContactNameFirst']);
        $JobContactNameLast = stripslashes($data['JobContactNameLast']);
        $JobLocationCity = bb_agency_strtoproper(stripslashes($data['JobLocationCity']));
        $JobLocationState = stripslashes($data['JobLocationState']);
        $JobGender = stripslashes($data['JobGender']);
        $JobDateDue = stripslashes($data['JobDateDue']);
        $JobDateBirth = stripslashes($data['JobDateBirth']);
        $JobStatHits = stripslashes($data['JobStatHits']);
        $JobDateViewLast = stripslashes($data['JobDateViewLast']);
        if ($data['JobIsActive'] == 0) {
            // Inactive
            $rowColor = " style=\"background: #FFEBE8\"";
        } elseif ($data['JobIsActive'] == 1) {
            // Active
            $rowColor = "";
        } elseif ($data['JobIsActive'] == 2) {
            // Archived
            $rowColor = " style=\"background: #dadada\"";
        } elseif ($data['JobIsActive'] == 3) {
            // Pending Approval
            $rowColor = " style=\"background: #DD4B39\"";
        }

        // check if she's given birth
        if (bb_agency_ismumtobe($data['JobType']) && bb_agency_datepassed($JobDateDue)) {
            die("due date $JobDateDue has passed");
            // switch category
            $ptypes = explode(',', $data['JobType']);
            for($i = 0; $i < count($ptypes); $i++){
                if ($ptypes[$i] == bb_agency_MUMSTOBE_ID)
                    $ptypes[$i] = bb_agency_AFTERBIRTH_ID;
            }

            $data['JobType'] = implode(',', $ptypes);
            
            // recategorize as family
            die('recategorize as family');
            $wpdb->update(
                table_agency_profile, 
                array('JobType' => $data['JobType']), 
                array('JobID' => $data['JobID']),
                array('%s'),
                array('%d')
            );               
        }
        
        /*
         * Get Data Type Title
         */
        if(strpos($data['JobType'], ",") > 0){
            $title = explode(",",$data['JobType']);
            $new_title = "";
            foreach($title as $t){
                $id = (int)$t;
                $get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type .  
                             " WHERE DataTypeID = " . $id;   
                $resource = mysql_query($get_title);             
                $get = mysql_fetch_assoc($resource);
                if (mysql_num_rows($resource) > 0 ){
                    $new_title .= "," . $get['DataTypeTitle']; 
                }
            }
            $new_title = substr($new_title,1);
        } else {
            $new_title = "";
            $id = (int)$data['JobType'];
            $get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type .  
                         " WHERE DataTypeID = " . $id;   
            $resource = mysql_query($get_title);             
            $get = mysql_fetch_assoc($resource);
            if (mysql_num_rows($resource) > 0 ){
                $new_title = $get['DataTypeTitle']; 
            }
        }
         
        
        $DataTypeTitle = stripslashes($new_title);

        $resultImageCount = mysql_query("SELECT * FROM " . table_agency_profile_media . " WHERE JobID='" . $JobID . "' AND JobMediaType = 'Image'");
        $profileImageCount = mysql_num_rows($resultImageCount);


//        $resultJobGender = mysql_query("SELECT * FROM " . table_agency_data_gender . " WHERE GenderID = '" . $JobGender . "' ");
//        $fetchJobGender = mysql_fetch_assoc($resultJobGender);
//        $JobGender = $fetchJobGender["GenderTitle"];


        echo "    <tr" . $rowColor . ">\n";
        echo "        <th class=\"check-column\" scope=\"row\">\n";
        echo "          <input type=\"checkbox\" value=\"" . $JobID . "\" class=\"administrator\" id=\"" . $JobID . "\" name=\"" . $JobID . "\"/>\n";
        echo "        </th>\n";
        echo "        <td class=\"JobID column-JobID\">" . $JobID . "</td>\n";
        echo "        <td class=\"JobContactNameFirst column-JobContactNameFirst\">\n";
        echo "          " . $JobContactNameFirst . "\n";
        echo "          <div class=\"row-actions\">\n";
        echo "            <span class=\"edit\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=editRecord&amp;JobID=" . $JobID . "\" title=\"" . __("Edit this Record", bb_agency_TEXTDOMAIN) . "\">" . __("Edit", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
        echo "            <span class=\"edit\"><a href=\"" . bb_agency_PROFILEDIR . $bb_agency_UPLOADDIR . $JobGallery . "/\" title=\"" . __("View", bb_agency_TEXTDOMAIN) . "\" target=\"_blank\">" . __("View", bb_agency_TEXTDOMAIN) . "</a> | </span>\n";
        echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=deleteRecord&amp;JobID=" . $JobID . "\"  onclick=\"if ( confirm('" . __("You are about to delete the profile for ", bb_agency_TEXTDOMAIN) . " " . $JobContactNameFirst . " " . $JobContactNameLast . "'" . __("Cancel", bb_agency_TEXTDOMAIN) . "\' " . __("to stop", bb_agency_TEXTDOMAIN) . ", \'" . __("OK", bb_agency_TEXTDOMAIN) . "\' " . __("to delete", bb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"" . __("Delete this Record", bb_agency_TEXTDOMAIN) . "\">" . __("Delete", bb_agency_TEXTDOMAIN) . "</a> </span>\n";
        echo "          </div>\n";
        echo "        </td>\n";
//     echo "        <td class=\"JobContactNameLast column-JobContactNameLast\">" . $JobContactNameLast . "</td>\n";
//        echo "        <td class=\"JobGender column-JobGender\">" . $JobGender . "</td>\n";
        echo "        <td class=\"JobsJobDate column-JobsJobDate\">" . (is_null($JobDateDue) || $JobDateDue == '0000-00-00' || bb_agency_datepassed($JobDateDue) ? $JobDateBirth : $JobDateDue) . "</td>\n";
        echo "        <td class=\"JobLocationCity column-JobLocationCity\">" . $JobLocationCity . "</td>\n";
        echo "        <td class=\"JobLocationCity column-JobLocationState\">" . $JobLocationState . "</td>\n";
        echo "        <td class=\"JobDetails column-JobDetails\">" . $DataTypeTitle . "</td>\n";
        echo "        <td class=\"JobDetails column-JobDetails\">" . $profileImageCount . "</td>\n";
        echo "        <td class=\"JobStatHits column-JobStatHits\">" . $JobStatHits . "</td>\n";
        echo "        <td class=\"JobDateViewLast column-JobDateViewLast\">\n";
        echo "           " . bb_agency_makeago(bb_agency_convertdatetime($JobDateViewLast), $bb_agency_option_locationtimezone);
        echo "        </td>\n";
        echo "    </tr>\n";
    }
    mysql_free_result($results2);
    if ($count < 1) {
        if (isset($filter)) {
            echo "    <tr>\n";
            echo "        <th class=\"check-column\" scope=\"row\"></th>\n";
            echo "        <td class=\"name column-name\" colspan=\"5\">\n";
            echo "           <p>No profiles found with this criteria.</p>\n";
            echo "        </td>\n";
            echo "    </tr>\n";
        } else {
            echo "    <tr>\n";
            echo "        <th class=\"check-column\" scope=\"row\"></th>\n";
            echo "        <td class=\"name column-name\" colspan=\"5\">\n";
            echo "            <p>There aren't any profiles loaded yet!</p>\n";
            echo "        </td>\n";
            echo "    </tr>\n";
        }
    }
    echo " </tbody>\n";
    echo "</table>\n";
    echo "<div class=\"tablenav\">\n";
    echo "  <div class='tablenav-pages'>\n";

    if ($items > 0) {
        echo $p->show();  // Echo out the list of paging. 
    }

    echo "  </div>\n";
    echo "</div>\n";

    echo "<p class=\"submit\">\n";
    echo "  <input type=\"hidden\" value=\"deleteRecord\" name=\"action\" />\n";
    echo "  <input type=\"submit\" value=\"" . __('Delete Jobs') . "\" class=\"button-primary\" name=\"submit\" />   \n";
    echo "</p>\n";
    echo "</form>\n";
}

echo "</div>\n";
?>