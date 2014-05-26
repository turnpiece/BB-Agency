<?php
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

    if (!empty($ProfileID) && ($ProfileID > 0)) {

        $query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileID='$ProfileID'";
        $results = mysql_query($query) or die(__("Error, query failed", bb_agency_TEXTDOMAIN));
        $count = mysql_num_rows($results);

        while ($data = mysql_fetch_array($results)) {
            $ProfileID = $data['ProfileID'];
            $ProfileUserLinked = $data['ProfileUserLinked'];
            $ProfileGallery = stripslashes($data['ProfileGallery']);
            $ProfileContactDisplay = stripslashes($data['ProfileContactDisplay']);
            $ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
            $ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
            $ProfileContactEmail = stripslashes($data['ProfileContactEmail']);
            $ProfileContactWebsite = stripslashes($data['ProfileContactWebsite']);
            $ProfileContactLinkFacebook = stripslashes($data['ProfileContactLinkFacebook']);
            $ProfileContactLinkTwitter = stripslashes($data['ProfileContactLinkTwitter']);
            $ProfileContactLinkYouTube = stripslashes($data['ProfileContactLinkYouTube']);
            $ProfileContactLinkFlickr = stripslashes($data['ProfileContactLinkFlickr']);
            $ProfileContactPhoneHome = stripslashes($data['ProfileContactPhoneHome']);
            $ProfileContactPhoneCell = stripslashes($data['ProfileContactPhoneCell']);
            $ProfileContactPhoneWork = stripslashes($data['ProfileContactPhoneWork']);
            $ProfileGender = stripslashes($data['ProfileGender']);
            $ProfileTypeArray = stripslashes($data['ProfileType']);
           
            $ProfileDateBirth = stripslashes($data['ProfileDateBirth']);
            $ProfileDateDue = stripslashes($data['ProfileDateDue']);
            $ProfileLocationStreet = stripslashes($data['ProfileLocationStreet']);
            $ProfileLocationCity = stripslashes($data['ProfileLocationCity']);
            $ProfileLocationState = stripslashes($data['ProfileLocationState']);
            $ProfileLocationZip = stripslashes($data['ProfileLocationZip']);
            $ProfileLocationCountry = stripslashes($data['ProfileLocationCountry']);
            $ProfileLocationLatitude = stripslashes($data['ProfileLocationLatitude']);
            $ProfileLocationLongitude = stripslashes($data['ProfileLocationLongitude']);

            $ProfileDateUpdated = stripslashes($data['ProfileDateUpdated']);
            $ProfileType = stripslashes($data['ProfileType']);
            $ProfileIsActive = stripslashes($data['ProfileIsActive']);
            $ProfileIsFeatured = stripslashes($data['ProfileIsFeatured']);
            $ProfileIsPromoted = stripslashes($data['ProfileIsPromoted']);
            $ProfileStatHits = stripslashes($data['ProfileStatHits']);
            $ProfileDateViewLast = stripslashes($data['ProfileDateViewLast']);

            echo "<h2 class=\"title\">" . __("Edit", bb_agency_TEXTDOMAIN) . " " . LabelSingular . " <a class=\"button-secondary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">" . __("Back to " . LabelSingular . " List", bb_agency_TEXTDOMAIN) . "</a> <a class=\"button-primary\" href=\"" . bb_agency_PROFILEDIR . $bb_agency_UPLOADDIR . $ProfileGallery . "/\" target=\"_blank\">Preview Profile</a></h2>\n";
            echo "<p>" . __("Make changes in the form below to edit a", bb_agency_TEXTDOMAIN) . " " . LabelSingular . ". <strong>" . __("Required fields are marked", bb_agency_TEXTDOMAIN) . "Required fields are marked *</strong></p>\n";
        }
    } else {
        // Set default values for new records
        $ProfilesModelDate = $date;
        $ProfileType = 1;
        $ProfileGender = "Unknown";
        $ProfileIsActive = 1;
        $ProfileLocationCountry = $bb_agency_option_locationcountry;

        echo "<h2 class=\"title\">Add New " . LabelSingular . " <a class=\"button-primary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">" . __("Back to " . LabelSingular . " List", bb_agency_TEXTDOMAIN) . "</a></h2>\n";
        echo "<p>" . __("Fill in the form below to add a new", bb_agency_TEXTDOMAIN) . " " . LabelSingular . ". <strong>" . __("Required fields are marked", bb_agency_TEXTDOMAIN) . " *</strong></p>\n";
    }

    if ($_GET["action"] == "add") {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=add&ProfileGender=" . $_GET["ProfileGender"] . "\">\n";
    } else {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
    }
    echo "<div style=\"float: left; width: 50%; \">\n";
    echo " <table class=\"form-table\">\n";
    echo "  <tbody>\n";
    echo "    <tr colspan=\"2\">\n";
    echo "      <th scope=\"row\"><h3>" . __("Contact Information", bb_agency_TEXTDOMAIN) . "</h3></th>\n";
    echo "    </tr>\n";
    if ((!empty($ProfileID) && ($ProfileID > 0)) || ($bb_agency_option_profilenaming == 2)) { // Editing Record
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Display Name", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfileContactDisplay\" name=\"ProfileContactDisplay\" value=\"" . $ProfileContactDisplay . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    if (!empty($ProfileID) && ($ProfileID > 0)) { // Editing Record
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Gallery Folder", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";

        if (!empty($ProfileGallery) && is_dir(bb_agency_UPLOADPATH . $ProfileGallery)) {
            echo "<div id=\"message\"><span class=\"updated\">" . __("Folder", bb_agency_TEXTDOMAIN) . " <strong>" . $ProfileGallery . "</strong> " . __("Exists", bb_agency_TEXTDOMAIN) . "</span></div>\n";
            echo "<input type=\"hidden\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"" . $ProfileGallery . "\" />\n";
        } else {
            echo "<input type=\"text\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"" . $ProfileGallery . "\" />\n";
            echo "<div id=\"message\"><span class=\"error\">" . __("No Folder Exists", bb_agency_TEXTDOMAIN) . "</span>\n";
        }
        echo "              </div>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
    }
    echo "    <tr valign=\"top\" class=\"required\">\n";
    echo "      <th scope=\"row\">" . __("First Name", bb_agency_TEXTDOMAIN) . "*</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"" . $ProfileContactNameFirst . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\" class=\"required\">\n";
    echo "      <th scope=\"row\">" . __("Last Name", bb_agency_TEXTDOMAIN) . "*</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"" . $ProfileContactNameLast . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // password
    if ((isset($_GET["action"]) && $_GET["action"] == "add") && function_exists('bb_agencyinteract_approvemembers')) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Username", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfileUsername\" name=\"ProfileUsername\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Password", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfilePassword\" name=\"ProfilePassword\" />\n";
        echo "          <input type=\"button\" onclick=\"javascript:document.getElementById('ProfilePassword').value=Math.random().toString(36).substr(2,6);\" value=\"Generate Password\"  name=\"GeneratePassword\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Send Login details?", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"checkbox\"  name=\"ProfileNotifyUser\" /> Send login details to the new user and admin by email.\n";
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
    echo "          <input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"" . $ProfileContactEmail . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Website", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"" . $ProfileContactWebsite . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Phone", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "      <fieldset>\n";
    echo "          <label>Home:</label><br /><input type=\"text\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"" . $ProfileContactPhoneHome . "\" /><br />\n";
    echo "          <label>Mobile:</label><br /><input type=\"text\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"" . $ProfileContactPhoneCell . "\" /><br />\n";
    echo "          <label>Work:</label><br /><input type=\"text\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"" . $ProfileContactPhoneWork . "\" /><br />\n";
    echo "      </fieldset>\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    // Address
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Street", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" value=\"" . $ProfileLocationStreet . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Town", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" value=\"" . $ProfileLocationCity . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("County", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileLocationState\" name=\"ProfileLocationState\" value=\"" . $ProfileLocationState . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Post code", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" value=\"" . $ProfileLocationZip . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Country", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>\n";
    echo "          <input type=\"text\" id=\"ProfileLocationCountry\" name=\"ProfileLocationCountry\" value=\"" . $ProfileLocationCountry . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // display location map
    if ($ProfileLocationLatitude != '' && $ProfileLocationLongitude != '') : ?>
        <tr valign="top">
            <td scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?></td>
            <td><?php bbagency_map($ProfileLocationLatitude, $ProfileLocationLongitude, $ProfileContactDisplay) ?></td>
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
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\" colspan=\"2\"><h3>" . __("Public Information", bb_agency_TEXTDOMAIN) . "</h3>The following information may appear in profile pages.</th>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Gender", bb_agency_TEXTDOMAIN) . "</th>\n";
    echo "      <td>";
    echo "<select name=\"ProfileGender\" id=\"ProfileGender\">\n";
   

    $ProfileGender1 = get_user_meta($ProfileUserLinked, "bb_agency_interact_pgender", true);
   
    if($ProfileGender==""){
        $ProfileGender = $_GET["ProfileGender"];
    }elseif($ProfileGender1!=""){
        $ProfileGender =$ProfileGender1 ;
    }
    
    $query1 = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . "";
    $results1 = mysql_query($query1);
    $count1 = mysql_num_rows($results1);
    if ($count1 > 0) {
        if (empty($GenderID) || ($GenderID < 1)) {
            echo " <option value=\"0\" selected>--</option>\n";
        }
        while ($data1 = mysql_fetch_array($results1)) {
            echo " <option value=\"" . $data1["GenderID"] . "\" " . selected($ProfileGender, $data1["GenderID"]) . ">" . $data1["GenderTitle"] . "</option>\n";
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
    echo "          <input class=\"bbdatepicker\" type=\"text\" id=\"ProfileDateBirth\" name=\"ProfileDateBirth\" value=\"" . $ProfileDateBirth . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "      <th scope=\"row\">" . __("Due date", bb_agency_TEXTDOMAIN) . " <em>YYYY-MM-DD</em></th>\n";
    echo "      <td>\n";
    echo "          <input class=\"bbdatepicker\" type=\"text\" id=\"ProfileDateDue\" name=\"ProfileDateDue\" value=\"" . $ProfileDateDue . "\" />\n";
    echo "      </td>\n";
    echo "    </tr>\n";

    // Load custom fields , Public  = 0, ProfileCustomGender = true
    // ProfileCustomView = 1 , Private
    if (isset($_GET["ProfileGender"])) {
        $ProfileGender = $_GET["ProfileGender"];
        bb_custom_fields(0, 0, $ProfileGender, true);
    } else {
        bb_custom_fields(0, $ProfileID, $ProfileGender, true);
    }

    echo "  </tbody>\n";
    echo " </table>\n";
    echo "</div>\n";

    echo "<div id=\"profile-manage-media\" style=\"float: left; width: 50%; \">\n";

    if (!empty($ProfileID) && ($ProfileID > 0)) { // Editing Record
        echo "      <h3>" . __("Gallery", bb_agency_TEXTDOMAIN) . "</h3>\n";

        echo "<script type='text/javascript'>\n";
        echo "function confirmDelete(delMedia,mediaType) {\n";
        echo "  if (confirm('Are you sure you want to delete this '+mediaType+'?')) {\n";
        echo "  document.location= '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&ProfileID=" . $ProfileID . "&actionsub=photodelete&targetid='+delMedia;";
        echo "  }\n";
        echo "}\n";
        echo "</script>\n";

        //mass delte
        if ($_GET["actionsub"] == "massphotodelete" && is_array($_GET['targetids'])) {
            $massmediaids = '';
            $massmediaids = implode(",", $_GET['targetids']);
            //get all the images

            $queryImgConfirm = "SELECT ProfileMediaID,ProfileMediaURL FROM " . table_agency_profile_media . " WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($massmediaids) AND ProfileMediaType = 'Image'";
            $resultsImgConfirm = mysql_query($queryImgConfirm);
            $countImgConfirm = mysql_num_rows($resultsImgConfirm);
            $mass_image_data = array();
            while ($dataImgConfirm = mysql_fetch_array($resultsImgConfirm)) {
                $mass_image_data[$dataImgConfirm['ProfileMediaID']] = $dataImgConfirm['ProfileMediaURL'];
            }
            //delete all the images from database
            $massmediaids = implode(",", array_keys($mass_image_data));
            $queryMassImageDelete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($massmediaids) AND ProfileMediaType = 'Image'";
            $resultsMassImageDelete = $wpdb->query($queryMassImageDelete);
            //delete images on the disk
            $dirURL = bb_agency_UPLOADPATH . $ProfileGallery;
            foreach ($mass_image_data as $mid => $ProfileMediaURL) {
                if (!unlink($dirURL . "/" . $ProfileMediaURL)) {
                    echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", bb_agency_TEXTDOMAIN) . " <strong>" . $ProfileMediaURL . "</strong>. " . __("File did not exist.", bb_agency_TEXTDOMAIN) . ".</p></div>");
                } else {
                    echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                }
            }
        }

        // Are we deleting?
        if ($_GET["actionsub"] == "photodelete") {
            $deleteTargetID = $_GET["targetid"];

            // Verify Record
            $queryImgConfirm = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaID =  \"" . $deleteTargetID . "\"";
            $resultsImgConfirm = mysql_query($queryImgConfirm);
            $countImgConfirm = mysql_num_rows($resultsImgConfirm);
            while ($dataImgConfirm = mysql_fetch_array($resultsImgConfirm)) {
                $ProfileMediaID = $dataImgConfirm['ProfileMediaID'];
                $ProfileMediaType = $dataImgConfirm['ProfileMediaType'];
                $ProfileMediaURL = $dataImgConfirm['ProfileMediaURL'];

                // Remove Record
                $delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaID=$ProfileMediaID";
                $results = $wpdb->query($delete);

                if ($ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Video Monologue" || $ProfileMediaType == "Video Slate") {
                    echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                } else {
                    // Remove File
                    $dirURL = bb_agency_UPLOADPATH . $ProfileGallery;
                    if (!unlink($dirURL . "/" . $ProfileMediaURL)) {
                        echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", bb_agency_TEXTDOMAIN) . " <strong>" . $ProfileMediaURL . "</strong>. " . __("File did not exist.", bb_agency_TEXTDOMAIN) . ".</p></div>");
                    } else {
                        echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", bb_agency_TEXTDOMAIN) . ".</p></div>");
                    }
                }
            } // is there record?
        }
        // Go about our biz-nazz
        $queryImg = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC";
        $resultsImg = mysql_query($queryImg);
        $countImg = mysql_num_rows($resultsImg);
        while ($dataImg = mysql_fetch_array($resultsImg)) {
            if ($dataImg['ProfileMediaPrimary']) {
                $styleBackground = "#900000";
                $isChecked = " checked";
                $isCheckedText = " Primary";
                if ($countImg == 1) {
                    $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\"><span>Delete</span> &raquo;</a></div>\n";
                } else {
                    $toDelete = "";
                    $massDelete = "";
                }
            } else {
                $styleBackground = "#000000";
                $isChecked = "";
                $isCheckedText = " Select";
                $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\"><span>Delete</span> &raquo;</a></div>\n";
                $massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> <span style="color:#FFFFFF">Delete</span>';
            }
            echo "<div class=\"profileimage\" style=\"background: " . $styleBackground . "; \">\n" . $toDelete . "";
            echo "  <img src=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataImg['ProfileMediaURL'] . "\" style=\"width: 100px; z-index: 1; \" />\n";
            echo "  <div class=\"primary\" style=\"background: " . $styleBackground . "; \"><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"" . $dataImg['ProfileMediaID'] . "\" " . $isChecked . " /> " .
            $isCheckedText . "<div>$massDelete</div></div>\n";

            echo "</div>\n";
        }
        if ($countImg < 1) {
            echo "<div>" . __("There are no images loaded for this profile yet.", bb_agency_TEXTDOMAIN) . "</div>\n";
        }
        ?>
        <div style="clear: both;"></div>
        <a href="javascript:confirm_mass_gallery_delete();">Delete Selected Images</a>
        <script language="javascript">
        function confirm_mass_gallery_delete(){
            jQuery(document).ready(function() {
                var mas_del_ids = '&';
                jQuery("input:checkbox[name=massgaldel]:checked").each(function() {
                    if(mas_del_ids != '&'){
                        mas_del_ids += '&';
                    }
                    mas_del_ids += 'targetids[]='+jQuery(this).val();
                });

                if( mas_del_ids != '&'){
                    if(confirm("Do you want to delete all the selected images?")){
                        urlmassdelete = "<?php echo admin_url('admin.php?page='.$_GET['page']) ?>&action=editRecord&ProfileID=<?php echo $ProfileID ?>&actionsub=massphotodelete" + mas_del_ids;
                        document.location = urlmassdelete;
                    }
                }
                else{
                    alert("You have to select images to delete");
                }
            });

        }
        </script>
        <?php

        echo "      <br><br><h3>" . __("Media", bb_agencyinteract_TEXTDOMAIN) . "</h3>\n";
        echo "      <p>" . __("The following files (pdf, audio file, etc.) are associated with this record", bb_agencyinteract_TEXTDOMAIN) . ".</p>\n";

        $queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType <> \"Image\"";
        $resultsMedia = mysql_query($queryMedia);
        $countMedia = mysql_num_rows($resultsMedia);
        while ($dataMedia = mysql_fetch_array($resultsMedia)) {
            if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
                $outVideoMedia .= "<div style=\"float: left; width: 120px; text-align: center; padding: 10px; \">" . $dataMedia['ProfileMediaType'] . "<br />" . bb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) . "<br /><a href=\"http://www.youtube.com/watch?v=" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
                $outLinkVoiceDemo .= "<div>" . $dataMedia['ProfileMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" . $dataMedia['ProfileMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['ProfileMediaType'] == "Resume") {
                $outLinkResume .= "<div>" . $dataMedia['ProfileMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" . $dataMedia['ProfileMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
                $outLinkHeadShot .= "<div>" . $dataMedia['ProfileMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" . $dataMedia['ProfileMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
            } elseif ($dataMedia['ProfileMediaType'] == "CompCard") {
                $outLinkComCard .= "<div>" . $dataMedia['ProfileMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" . $dataMedia['ProfileMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
            } else {
                $outCustomMediaLink .= "<div>" . $dataMedia['ProfileMediaType'] . ": <a href=\"" . bb_agency_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" . $dataMedia['ProfileMediaTitle'] . "</a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
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
            bb_agency_getMediaCategories($ProfileGender);
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
    $ProfileTypeArray = explode(",", $ProfileTypeArray);
    
    $query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
    $results3 = mysql_query($query3);
    $count3 = mysql_num_rows($results3);
    $action = @$_GET["action"];
    while ($data3 = mysql_fetch_array($results3)) {
        if ($action == "add") {
            echo "<input type=\"checkbox\" name=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\" id=\"ProfileType[]\" /> " . $data3['DataTypeTitle'] . "<br />\n";
        }
        if ($action == "editRecord") {
            echo "<input type=\"checkbox\" name=\"ProfileType[]\" id=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\"";
            if (in_array($data3['DataTypeID'], $ProfileTypeArray) && isset($_GET["action"]) == "editRecord") {
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
    echo "        <td><select id=\"ProfileIsActive\" name=\"ProfileIsActive\">\n";
    echo "            <option value=\"1\"" . selected(1, $ProfileIsActive) . ">" . __("Active", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"4\"" . selected(4, $ProfileIsActive) . ">" . __("Active - Not Visible On Website", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"0\"" . selected(0, $ProfileIsActive) . ">" . __("Inactive", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"2\"" . selected(2, $ProfileIsActive) . ">" . __("Archived", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "            <option value=\"3\"" . selected(3, $ProfileIsActive) . ">" . __("Pending Approval", bb_agency_TEXTDOMAIN) . "</option>\n";
    echo "          </select></td>\n";
    echo "    </tr>\n";
    echo "    <tr valign=\"top\">\n";
    echo "        <th scope=\"row\">" . __("Promotion", bb_agency_TEXTDOMAIN) . ":</th>\n";
    echo "        <td>\n";
    echo "          <input type=\"checkbox\" name=\"ProfileIsFeatured\" id=\"ProfileIsFeatured\" value=\"1\"". checked($ProfileIsFeatured, 1, false) . " /> Featured<br />\n";
    echo "        </td>\n";
    echo "    </tr>\n";
    /*
    if (function_exists('bb_agencyinteract_approvemembers')) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Membership", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"checkbox\" name=\"ProfileIsPromoted\" id=\"ProfileIsPromoted\" value=\"1\"". checked($ProfileIsPromoted, 1, false) ." /> Rising Star<br />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    */

    if (isset($ProfileUserLinked) && $ProfileUserLinked > 0) {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("WordPress User", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "        <a href=\"". admin_url("user-edit.php") ."?user_id=". $ProfileUserLinked ."&wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Dbb_agency_profiles\">ID# ". $ProfileUserLinked ."</a>";
        echo "        <input type='hidden' name='wpuserid' value='".$ProfileUserLinked."' />";
        echo "      </td>\n";
        echo "    </tr>\n";
    }


    // Hidden Settings
    if ($_GET["mode"] == "override") {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Date Updated", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"" . $ProfileDateUpdated . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Profile Views", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"" . $ProfileStatHits . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\">" . __("Profile Viewed Last", bb_agency_TEXTDOMAIN) . "</th>\n";
        echo "      <td>\n";
        echo "          <input type=\"text\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"" . $ProfileDateViewLast . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    } else {
        echo "    <tr valign=\"top\">\n";
        echo "      <th scope=\"row\"></th>\n";
        echo "      <td>\n";
        echo "          <input type=\"hidden\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"" . $ProfileDateUpdated . "\" />\n";
        echo "          <input type=\"hidden\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"" . $ProfileStatHits . "\" />\n";
        echo "          <input type=\"hidden\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"" . $ProfileDateViewLast . "\" />\n";
        echo "      </td>\n";
        echo "    </tr>\n";
    }
    echo "  </tbody>\n";
    echo "</table>\n";

    if (!empty($ProfileID) && ($ProfileID > 0)) {
        echo "" . __("Last updated on", bb_agency_TEXTDOMAIN) . ": " . $ProfileDateUpdated . "\n";

        echo "<p class=\"submit\">\n";
        echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"" . $ProfileID . "\" />\n";
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