<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php"); 

    global $wpdb;

    $arrayProfilesRenamedFolders = array();
    $arraySuggestedFolderNames = array();
    $arrayAllFolderNames = array();
    $arrayDuplicateFolders = array();
    $arrayDuplicateFound = array();
 

if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
	if($_REQUEST['action'] == 'douninstall') {
		modelagency_uninstall();
	}
}

if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID <> 0) { ?>
    <a class="button-primary" href="?page=bb_agency_reports&ConfigID=0" title="Overview">Back to Overview</a>
    <?php
}

if ($ConfigID == 0) {
	
	if (function_exists(bb_agencyinteract_approvemembers)) {
    	// RB Agency Interact Settings
        echo "<div class=\"boxlinkgroup\">\n";
        echo "  <h2>". __("Interactive Reporting", bb_agency_TEXTDOMAIN) . "</h2>\n";
        echo "  <p>". __("Run reports on membership and other usage.", bb_agency_TEXTDOMAIN) . "</p>\n";

        echo "    <div class=\"boxlink\">\n";
        echo "      <h3>". __("Recent Payments", bb_agency_TEXTDOMAIN) . "</h3>\n";
        echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=11\" title=\"". __("Recent Payments", bb_agency_TEXTDOMAIN) . "\">". __("Recent Payments", bb_agency_TEXTDOMAIN) . "</a><br />\n";
        echo "      <p>". __("Payments and membership renewals", bb_agency_TEXTDOMAIN) . "</p>\n";
        echo "    </div>\n";
    echo "</div>\n";
    echo "<hr />\n";
	}

	//
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Initial Setup", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("If you are doing the initial instal of RB Agency you this section will help you get your data inplace", bb_agency_TEXTDOMAIN) . "</p>\n";
    echo "</div>\n";
    echo "<hr />\n";

	//
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Data Integrity", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("Once your data is in place use the tools below to check your records", bb_agency_TEXTDOMAIN) . "</p>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Export Data", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=12\" title=\"". __("Export Data", bb_agency_TEXTDOMAIN) . "\">". __("Export Data", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Export databases", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Check for Abnormalities", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=5\" title=\"". __("Check for Abnormalities", bb_agency_TEXTDOMAIN) . "\">". __("Check for Abnormalities", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Search profile records for fields which seem invalid", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Rename Profile Folder Names", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=8\" title=\"". __("Rename Folders", bb_agency_TEXTDOMAIN) . "\">". __("Rename Folders", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("If you created model profiles while under 'First Last' or 'First L' and wish to switch to Display names or IDs you will have to rename the existing folders so that they do not have the models name in it", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Resize Photos", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=13\" title=\"". __("Resize Photos", bb_agency_TEXTDOMAIN) . "\">". __("Resize Photos", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Ensure files are not larger than approved size", bb_agency_TEXTDOMAIN) . ". (<a href=\"?page=bb_agency_settings&ConfigID=1\" title=\"". __("Configure Sizes", bb_agency_TEXTDOMAIN) . "\">". __("Configure Sizes", bb_agency_TEXTDOMAIN) . "</a>)</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Orphaned Profile Images", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=7\" title=\"". __("Remove Orphan Images From Database", bb_agency_TEXTDOMAIN) . "\">". __("Remove Orphan Images From Database", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("If for any reason you are getting blank images appear it may be because images were added in the database but have been removed via FTP.  Use this tool to remove all images in the databse which do not physically exist.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("FTP Blank Folder Check", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=1\" title=\"". __("Scan for Orphan Folders", bb_agency_TEXTDOMAIN) . "\">". __("Scan for Orphan Folders", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Check for any empty folders which do no have models assigned using this tool", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";
    echo "</div>\n";
    echo "<hr />\n";

	//
    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Profile Management", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("The following reports will help you manage your profile information", bb_agency_TEXTDOMAIN) . "</p>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Inactive Users", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=6\" title=\"". __("Check Inactive Users", bb_agency_TEXTDOMAIN) . "\">". __("Check Inactive Users", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Find profiles who are currently set as inactive.  Use this tool to set multiple users to active", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Profile Search", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=bb_agency_search\" title=\"". __("Profile Search", bb_agency_TEXTDOMAIN) . "\">". __("Profile Search", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("You may search for profiles by using this tool", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Dummy Profiles with Sample Media", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=14\" title=\"". __("Generate Dummy Profiles with Media Content", bb_agency_TEXTDOMAIN) . "\">". __("Generate Dummy Profiles with Media Content", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("You may add dummy profiles by using this tool", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Generate User Logins / Passwords", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=99\" title=\"". __("Generate Logins / Passwords", bb_agency_TEXTDOMAIN) . "\">". __("Generate Logins / Passwords", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("You may generate login and password for profiles which has been uploaded via importer, using this tool", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";
    echo "<hr />\n";

    echo "<div class=\"boxlinkgroup\">\n";
    echo "  <h2>". __("Importing Records", bb_agency_TEXTDOMAIN) . "</h2>\n";
    echo "  <p>". __("The following tools will help import records.  DO NOT USE THESE TOOLS IF YOU ALREADY HAVE DATA LOADED", bb_agency_TEXTDOMAIN) . "</p>\n";

    
/*NK*/
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 1", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=81\" title=\"". __("Export Now", bb_agency_TEXTDOMAIN) . "\">". __("Export Now", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";
    
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 2", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=80\" title=\"". __("Import Data", bb_agency_TEXTDOMAIN) . "\">". __("Import Data", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Upload the model profiles into the database.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";


/*NK*/
/*
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 1", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=51\" title=\"". __("Download Excel Template", bb_agency_TEXTDOMAIN) . "\">". __("Download Excel Template", bb_agency_TEXTDOMAIN) . "</span><br />\n";
    echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 2", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=52\" title=\"". __("Upload to Database", bb_agency_TEXTDOMAIN) . "\">". __("Upload to Database", bb_agency_TEXTDOMAIN) . "</span><br />\n";
    echo "      <p>". __("Upload the model profiles into the database.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";
*/
    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 3", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=53\" title=\"". __("Generate folder names for profiles", bb_agency_TEXTDOMAIN) . "\">". __("Generate folder names for profiles", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Check that all profiles have folder names generated.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 4", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=2\" title=\"". __("Create folders for all profiles", bb_agency_TEXTDOMAIN) . "\">". __("Create folders for all profiles", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Check that all profiles have folders created on the server.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 5", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=3\" title=\"". __("Scan Folders for Images", bb_agency_TEXTDOMAIN) . "\">". __("Scan Folders for Images", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("First upload images directly to folders via FTP then use this tool to sync the images to the database.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "    <div class=\"boxlink\">\n";
    echo "      <h3>". __("Step 6", bb_agency_TEXTDOMAIN) . "</h3>\n";
    echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=4\" title=\"". __("Set Primary Profile Image", bb_agency_TEXTDOMAIN) . "\">". __("Set Primary Profile Image", bb_agency_TEXTDOMAIN) . "</a><br />\n";
    echo "      <p>". __("Identify which image is the primary image for each profile.", bb_agency_TEXTDOMAIN) . ".</p>\n";
    echo "    </div>\n";

    echo "</div>\n";

}
elseif ($ConfigID == 11) {

    echo "  <h2>". __("Recent Payments", bb_agency_TEXTDOMAIN) . "</h2>\n";

	// What are the rates?
	$sql = "SELECT * FROM ". table_agencyinteract_subscription ."";
	$results = mysql_query($sql);
	$count = mysql_num_rows($results);
	if ($count > 0) {
        while ($data = mysql_fetch_array($results)) {
            echo "<div id=\"subscription-activity\">\n";
            echo "  <span>". $data["SubscriberDateStart"] ."</span> <span>". $data["ProfileID"] ."</span>\n";
            echo "</div>\n";
       } // is there record?
	} else {
		echo "Currently no subscriptions.";
	}


}
elseif ($ConfigID == 1) {
//////////////////////////////////////////////////////////////////////////////////// ?>
    <h3>Check Galleries</h3>
    <h3>Folders without Models</h3>
    <p>This will determine if a model's profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
    <?php
    global $wpdb;
    $throw_error = false;
	
    $query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
    $results1 = mysql_query($query1);
    $count1 = mysql_num_rows($results1);
    while ($data1 = mysql_fetch_array($results1)) {
        $dirURL = bb_agency_UPLOADPATH . $data1['ProfileGallery'];
		echo $dirURL;
        echo "<div>\n";
        if (is_dir($dirURL)) {
            //echo "  <span style='width: 240px; color: green;'>" . bb_agency_UPLOADDIR  . $dirURL . "/</span>\n";
        } else {
			$throw_error = true;
            echo "  <span style='width: 240px; color: red;'>". $dirURL ."/</span>\n";
            echo "  <strong>Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is missing folder.</strong>\n";
        }
        echo "</div>\n";
    }
    if ($count1 < 1) {
        echo "There are currently no profile records.";
    } elseif ($throw_error == false) {
        echo "Congrats!  All folders are as they should be!";
    }
    ?>
		  
		  
<?php
}
elseif ($ConfigID == 2) {
//////////////////////////////////////////////////////////////////////////////////// ?>
  <?php
	$arrayProfilesMissingFolders = array();
	$throw_error = false;

	if($_REQUEST['action'] == 'generate') {
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$dirURL = bb_agency_UPLOADPATH. $data1['ProfileGallery'];
			if (isset($data1['ProfileGallery']) && !empty($data1['ProfileGallery']) && is_dir($dirURL)) {
			} else {
				// Create Folders
				mkdir($dirURL, 0755); //700
				chmod($dirURL, 0777);
				echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>". $dirURL ."/</strong> has been created for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
			}
		}
    } else {
    ?>
    <h3>Check Galleries</h3>
    <h3>Check Profiles against Folders</h3>
    <p>This will determine if a profiles profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
    <?php

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$dirURL = bb_agency_UPLOADPATH . $data1['ProfileGallery'];
			echo "<div>\n";
			if (isset($data1['ProfileGallery']) && !empty($data1['ProfileGallery']) && is_dir($dirURL)) {
				echo "  <span style='width: 240px; color: green;'>". $dirURL ."/</span>\n";
			} else {
				// Add Profiles to Array to Create later
				$arrayProfilesMissingFolders[] = $dirURL; 
				$throw_error = true;

				echo "  <span style='width: 240px; color: red;'>". $dirURL ."/</span>\n";
				echo "  <strong>Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is missing folder.</strong>\n";
			}
			echo "</div>\n";
		}
		
		// Errors?
        if ($throw_error == true) { ?>
            <a name="generate"></a>
            <h3>Generate Folders for Profiles</h3>
            <p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
            <p><a class="button-primary" href="?page=bb_agency_reports&ConfigID=2&action=generate" title="Generate Missing Folders for Profiles">Generate Missing Folders for Profiles</a>  Clicking this button will generate folders for the following profiles:<p>
            <?php
            foreach ($arrayProfilesMissingFolders as $profileURL) {
                echo $profileURL.", ";
            }
		} else {
			echo "Good to go! No changes needed!";
		}
			
	} // To Generate or Not to Generate
	  

} // End 2
elseif ($ConfigID == 53) {
//////////////////////////////////////////////////////////////////////////////////// 
	$arrayProfilesMissingFolders = array();
	$throw_error = false;

	if($_REQUEST['action'] == 'generate') {
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID = $data1['ProfileID'];
			$ProfileGallery = bb_agency_safenames($data1['ProfileContactNameFirst'] . "-" . $data1['ProfileContactNameLast']); 
			// Create Folders
			$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGallery ."' WHERE ProfileID = \"". $ProfileID ."\"";
			$renamed = mysql_query($rename);
			echo "  <div id=\"message\" class=\"updated highlight\">Folder name <strong>/" . $ProfileGallery . "/</strong> has been set for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
		}
    } else {
		
		/*
		 * Place sql here to get
		 * generated total count for folders
		 */
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		
		echo "<h3>". __("Generate folder names for profiles", bb_agency_TEXTDOMAIN) . "</h3>\n";
		echo "<p>". __("Check that all profiles have folder names generated.", bb_agency_TEXTDOMAIN) . "</p>\n";
		echo "<p>". __("Total Number of Folders Created: <strong>".$count1."</strong>", bb_agency_TEXTDOMAIN) . "</p>\n";

			while ($data1 = mysql_fetch_array($results1)) {
				$ProfileGallery = $data1['ProfileGallery'];
				$ProfileGallerySafe = bb_agency_safenames($ProfileGallery); 
				echo "<div>\n";
				if (isset($ProfileGallery) && !empty($ProfileGallery)) {
					if ($ProfileGallery == $ProfileGallerySafe) {
						echo "  <span style='width: 240px; color: green;'>". $ProfileGallery ."</span>\n";
					} else {
						// Add Profiles to Array to Create later
						$arrayProfilesMissingFolders[] = $dirURL; 
						$throw_error = true;
						echo "  <span style='width: 240px; color: red;'>". $ProfileGallery ." should be <strong>". $ProfileGallerySafe ."</strong></span>\n";
					}
				} else {
					// Add Profiles to Array to Create later
					$arrayProfilesMissingFolders[] = $dirURL; 
					$throw_error = true;

					echo "  <span style='width: 240px; color: red;'>". $ProfileGallerySafe ." is missing</span>\n";
					echo "  <strong>Folder name for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is blank.</strong>\n";
				}
				echo "</div>\n";
			}
			
			// Errors?
            if ($throw_error == true) { ?>
                <a name="generate"></a>
                <h3>Generate Folders for Profiles</h3>
                <p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
                <p><a class="button-primary" href="?page=bb_agency_reports&ConfigID=<?php echo $ConfigID; ?>&action=generate" title="Generate Missing Folders for Profiles">Generate Missing Folders for Profiles</a>  Clicking this button will generate folders for the following profiles:<p>
                <?php
                foreach ($arrayProfilesMissingFolders as $profileURL) {
                    echo $profileURL.", ";
                }
			} else {
				echo "Good to go! No changes needed!";
			}
			
	} // To Generate or Not to Generate
	  

} // End 2
elseif ($ConfigID == 3) {
//////////////////////////////////////////////////////////////////////////////////// ?>
    <h3>Manage Galleries</h3>
    <h3>Correct Filenames and Add to Database</h3>
    <p>This script corrects all the filenames of the images uploaded removing special characters and spaces and then adds the images to the database.</p>
    <?php
    global $wpdb;

	$query3 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
    $results3 = mysql_query($query3);
    $count3 = mysql_num_rows($results3);
    while ($data3 = mysql_fetch_array($results3)) {
        $dirURL = bb_agency_UPLOADPATH . $data3['ProfileGallery'];
		if (is_dir($dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; \">\n<h3>". $data3['ProfileContactNameFirst'] ." ". $data3['ProfileContactNameLast'] ."</h3>\n";
			if ($handle = opendir($dirURL)) {  //  Open seasame 
				while (false !== ($file = readdir($handle))) {
					if (strtolower($file) == "thumbs.db"  || strtolower($file) == "thumbsdb.jpg" || strtolower($file) == "thumbsdbjpg.jpg" || strtolower($file) == "thumbsdbjpgjpg.jpg") {
						if (!unlink($dirURL ."/". $file)) {
						  echo ("Error deleting $file");
					    } else {
					 	  echo ("Deleted $file");
					    }
					} elseif ($file != "." && $file != "..") {
						$new_file = bb_agency_safenames($file);
						rename($dirURL ."/". $file, $dirURL ."/". $new_file);
						
						$file_ext = bb_agency_filenameextension($new_file);
						if ($file_ext == "jpg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "bmp") {
						
							$query3a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileMediaURL = \"". $new_file ."\"";
							$results3a = mysql_query($query3a);
							$count3a = mysql_num_rows($results3a);
							if ($count3a < 1) {
								if($_GET['action'] == "add") {
								$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $data3['ProfileID'] ."','Image','". $data3['ProfileContactNameFirst'] ."-". $new_file ."','". $new_file ."')");
								$actionText = " and <span style=\"color: green;\">added to database</span>";
								} else {
								$actionText = " and <strong>PENDING ADDITION TO DATABASE</strong>";
								}
							} else {
								$actionText = " and exists in database";
							}
						} else {
								$actionText = " is <span style=\"color: red;\">NOT an allowed file type</span> ";
						}

            		echo "<div style=\"border-color: #E6DB55;\">File: ". $file ." has been renamed <strong>". $new_file ."</strong>". $actionText ."</div>\n";
					}
				}
				closedir($handle);
			}
			echo "</div>\n";
		}
	}
    if ($count3 < 1) {
        echo "There are currently no profile records.";
    }
	echo "<a href='?page=bb_agency_reports&ConfigID=3&action=add'>Add All Pending Changes</a>";




} // End 3
elseif ($ConfigID == 4) {
//////////////////////////////////////////////////////////////////////////////////// 
	
	global $wpdb;

	$stepSize = 100;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ."";
	$results4t = mysql_query($query4t);
	$count4total = mysql_num_rows($results4t);
	
	if (isset($_GET['Step'])) { 
		$currentPage = $_GET['Step']; 
		$step = $currentPage * $stepSize;
	} else { 
		$currentPage = 1; 
		$step = 0;
	}
	
	$totalPages = ceil($count4total/$stepSize);
	  //echo "Total pages:" . $totalPages;
	   if($totalPages >= 1) {
		 for($i = 1; $i <= $totalPages; $i++) {
		   $pageString .= " <a href=\"?page=bb_agency_reports&ConfigID=4&Step={$i}$queryVars\">Page $i</a>";
		   $pageString .= $i != $totalPages ? " | " : "";
		 }
	   }
	echo $pageString;

	if($_POST['action'] == 'update')
	{
	
		extract($_POST);
		foreach($_POST as $key=>$value) {
			if ($key !== "action" && $key !== "Update") {
				
				$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary = 0 WHERE ProfileID = ". $key);
				$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary = 1 WHERE ProfileID = ". $key ." AND ProfileMediaID = ". $value);

			}
		}

		echo "  <div id=\"message\" class=\"updated highlight\">Primary Images Saved!</div>\n";
	} else {
	?>
        <h3>Manage Galleries</h3>
        <h3>Select Primary Profile Photo</h3>
        <p>Select the checkbox for the model desired.</p>
        <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <?php
    		
            $query4 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst LIMIT $step,$stepSize"; //LIMIT $step,100
            $results4 = mysql_query($query4);
            $count4 = mysql_num_rows($results4);
            while ($data4 = mysql_fetch_array($results4)) {
                $dirURL = bb_agency_UPLOADDIR . $data4['ProfileGallery'];
                $profileID = $data4['ProfileID'];

                $query4b = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = $profileID AND ProfileMediaType = 'Image' AND ProfileMediaPrimary = 1";
                $results4b = mysql_query($query4b);
                $count4b = mysql_num_rows($results4b);
    			//echo $query4b ."<br />". $count4b ."<hr />";
                if ($count4b < 1) {

    				echo "<div style=\"background-color: lightYellow; \">\n<h3><a href='?page=bb_agency_profiles&action=editRecord&ProfileID=$profileID' target='_blank'>". $data4['ProfileContactNameFirst'] ." ". $data4['ProfileContactNameLast'] ."</a></h3>\n";
    		
    				$query4a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = $profileID AND ProfileMediaType = 'Image'";
    				$results4a = mysql_query($query4a);
    				$count4a = mysql_num_rows($results4a);
    				if ($count4a < 1) {
    					echo "This profile has no images loaded.";
    				} else {
    					while ($data4a = mysql_fetch_array($results4a)) {
    						echo "<div style=\"width: 150px; float: left; height: 200px; overflow: hidden; margin: 10px; \"><input type=\"radio\" name=\"". $data4a['ProfileID'] ."\" value=\"". $data4a['ProfileMediaID'] ."\" class=\"button-primary\" />Select Primary<br /><img src=\"". $dirURL ."/". $data4a['ProfileMediaURL'] ."\" style=\"width: 150px;\" /></div>\n";
    					}
    					echo "<div style=\"clear: both;\"></div>\n";
    				}
    				echo "</div>\n";
    			} else {
    				// Primary Image Already Set
    			}
            }
            if ($count4 < 1) {
                echo "There are currently no profile records.";
            }
            ?>
            <input type="hidden" value="update" name="action" />
            <input type="submit" value="Submit" class="button-primary" name="Update" />
        </form>
    <?php
	}
} // End 4
elseif ($ConfigID == 5) {
//////////////////////////////////////////////////////////////////////////////////// ?>

    <h3>Check for Abnormalities</h3>
    <p>This will determine if a model's profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
    <?php
    global $wpdb;
    
    $query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
    $results1 = mysql_query($query1);
    $count1 = mysql_num_rows($results1);
    while ($data1 = mysql_fetch_array($results1)) {
        $ProfileDateBirth = $data1['ProfileDateBirth'];
        $ProfileAge = bb_agency_get_age($ProfileDateBirth);
        if ($ProfileDateBirth == "0000-00-00" || !isset($ProfileDateBirth) || empty($ProfileDateBirth)) {
            echo "  <div id=\"message\" class=\"error\">Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> has no age!!</div>\n";
        } elseif ($ProfileAge > 90) {
            echo "  <div id=\"message\" class=\"updated highlight\">Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is really old! .... Like ". $ProfileAge ."</div>\n";
        } elseif ($ProfileAge < 2) {
            if ($ProfileAge < 0) {
            echo "  <div id=\"message\" class=\"updated\">Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> was born in the future... amazing!</div>\n";
            } else {
            echo "  <div id=\"message\" class=\"updated highlight\">Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is really young! .... Like ". $ProfileAge ."</div>\n";
            }
        }
    }
    if ($count1 < 1) {
        echo "There are currently no profile records.";
    }
    ?>


<?php
}	 // End	
elseif ($ConfigID == 6) {
//////////////////////////////////////////////////////////////////////////////////// 
	
	global $wpdb;
	
	if($_POST['action'] == 'update') {
		extract($_POST);
		foreach($_POST as $key=>$value) {
			if ($key !== "action" && $key !== "Update") {
				$results = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileIsActive = 1 WHERE ProfileID = ". $key);
			}
		}
	}
	?>
    <h3>Set Profiles Active</h3>
    <p>Select the checkbox for the model desired to make active.</p>
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <?php
    
    $query6 = "SELECT * FROM ". table_agency_profile ." WHERE ProfileIsActive = '0' ORDER BY ProfileContactNameFirst";
    $results6 = mysql_query($query6);
    $count6 = mysql_num_rows($results6);
    while ($data6 = mysql_fetch_array($results6)) {
		echo "<div><input type=\"checkbox\" name=\"". $data6['ProfileID'] ."\" value=\"". $data6['ProfileID'] ."\" class=\"button-primary\" />". $data6['ProfileContactNameFirst'] ." ". $data6['ProfileContactNameLast'] ."</div>\n";
    }
    if ($count6 < 1) {
        echo "There are currently no inactive profile records.";
    }
    ?>
    <input type="hidden" value="update" name="action" />
    <input type="submit" value="Submit" class="button-primary" name="Update" />
    </form>
    <?php
} // End 6
elseif ($ConfigID == 7) {
//////////////////////////////////////////////////////////////////////////////////// 
	
	global $wpdb;
	?>
    <h3>Remove Orphans from Database</h3>
    <?php
    $query7 = "SELECT ProfileID, ProfileGallery FROM ". table_agency_profile ."";
    $results7 = mysql_query($query7);
    $count7 = mysql_num_rows($results7);
    while ($data7 = mysql_fetch_array($results7)) {
        $ProfileID = $data7['ProfileID'];
        $dirURL = bb_agency_UPLOADPATH . $data7['ProfileGallery'];
		if (is_dir(".." . $dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; margin: 10px; \">\n";
			if ($handle = opendir(".." . $dirURL)) {  //  Open seasame 
			
				$query7a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = ". $ProfileID ." AND ProfileMediaType = 'Image'";
				$results7a = mysql_query($query7a);
				$count7a = mysql_num_rows($results7a);
				while ($data7a = mysql_fetch_array($results7a)) {
					$fileCheck = bb_agency_UPLOADPATH . $data7['ProfileGallery'] ."/". $data7a['ProfileMediaURL'];
					if (file_exists($fileCheck)) {
					echo "<div style=\"color: green;\">". $fileCheck ."</div>\n";
					} else {
						if($_GET['action'] == "delete") {
							$ProfileMediaID = $data7a['ProfileMediaID'];
							// Remove Orphans
							$query7b = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileMediaID = \"". $ProfileMediaID ."\"";
							$results7b = mysql_query($query7b);
							echo $query7b;
							
						echo "<div style=\"color: red;\">". $fileCheck ." DELETED</div>\n";
						} else {
						  echo "<div style=\"color: red;\">". $fileCheck ."</div>\n";
						}
					}
				}
			}
			echo "</div>\n";
		}
	}
	echo "<a href='?page=bb_agency_reports&ConfigID=". $ConfigID ."&action=delete'>Remove Orphans</a>";


	?>


    <?php
} // End 6
elseif ($ConfigID == 8) {
//////////////////////////////////////////////////////////////////////////////////// ?>
  <?php

	
	global $wpdb;
	$bb_agency_options_arr = get_option('bb_agency_options');
		$bb_agency_option_profilenaming 		= (int)$bb_agency_options_arr['bb_agency_option_profilenaming'];

		echo "Current Naming Convention:";
		if ($bb_agency_option_profilenaming == 0) {
			echo "<h2>First Last</h2>";
		} elseif ($bb_agency_option_profilenaming == 1) {
			echo "<h2>First L</h2>";
		} elseif ($bb_agency_option_profilenaming == 2) {
			echo "<h2>Display Name</h2>";
		} elseif ($bb_agency_option_profilenaming == 3) {
			echo "<h2>Autogenerated ID</h2>";
		}


	if($_REQUEST['action'] == 'generate') {

		// LETS DO IT!
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		$arrayReservedFoldername = array();
		$pos = 0;
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
				if ($bb_agency_option_profilenaming == 0) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($bb_agency_option_profilenaming == 1) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($bb_agency_option_profilenaming == 2) {
					$ProfileGalleryFixed = $ProfileContactDisplay;
				} elseif ($bb_agency_option_profilenaming == 3) {
					$ProfileGalleryFixed = "ID ";
				}
				$ProfileGalleryFixed = bb_agency_safenames($ProfileGalleryFixed); 
			
			  if(in_array($ProfileGallery,$arrayReservedFoldername)){
				$ProfileGalleryFixed = bb_agency_set_directory($ProfileGalleryFixed);
				$arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
			 }
				
				if ($ProfileGallery == $ProfileGalleryFixed ) {
			     				$ProfileGalleryFixed = $ProfileGallery;
				} else {
					      	$ProfileGalleryFixed  = bb_agency_set_directory($ProfileGalleryFixed);
				}

			if ($ProfileGallery == $ProfileGalleryFixed) {
			} else {
				// Folder Exist?
				if (is_dir(bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed)) {
					$finished = false;                       // we're not finished yet (we just started)
					while ( ! $finished ):                   // while not finished
                        $ProfileGalleryFixed = $ProfileGalleryFixed .$ProfileID;   // output folder name
                        if ( ! is_dir(bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed) ):        // if folder DOES NOT exist...
                            rename(bb_agency_UPLOADPATH ."/". $ProfileGallery, bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed);

                            if (is_dir(bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed)) {
                            	$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
                            	$renamed = mysql_query($rename);
                                echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
                            } else {
                                echo "  <div id=\"message\" class=\"error\">Error renaming <strong>/" . $ProfileGalleryFixed . "/</strong> for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
                            }

                            $finished = true; // ...we are finished
                        endif;
					endwhile;

				} else {
					
					// Create Folders
					rename(bb_agency_UPLOADPATH ."/". $ProfileGallery, bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed);
					if (is_dir(bb_agency_UPLOADPATH ."/". $ProfileGalleryFixed) ) { // if folder DOES NOT exist...
						$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
						$renamed = mysql_query($rename);
		                echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					} else {
                        echo "  <div id=\"message\" class=\"error\">Error renaming <strong>/" . $ProfileGalleryFixed . "/</strong> for <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					}
				}
			}
			$pos++;
		}

    } else {

    	echo "<h3>Hide Profile Identity</h3>\n";
    	echo "<p>If you created model profiles while under \"First Last\" or \"First L\" and wish to switch to Display names or IDs you will have to rename the existing folders so that they do not have the models name in it.</p>\n";
	
	
    	/*
    	echo "<br />";
    	var_dump(is_dir(bb_agency_UPLOADPATH . "/john-doe/"));
    	echo "<br />";
    	echo bb_agency_UPLOADREL;
    	// Open a known directory, and proceed to read its contents
    	$dir = bb_agency_UPLOADPATH;
    	if (is_dir($dir)) {
    		if ($dh = opendir($dir)) {
    			while (($file = readdir($dh)) !== false) {
    				echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
    			}
    			closedir($dh);
    		}
    	}
    	*/

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		
		$pos = 0;
		$pos_suggested = 0;
		$arrayReservedFoldername = array();
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
			$arrayAllFolderNames[$pos] = $ProfileGallery;
			$pos++; // array position start = 0	
			
			if ($bb_agency_option_profilenaming == 0) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
			} elseif ($bb_agency_option_profilenaming == 1) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
			} elseif ($bb_agency_option_profilenaming == 2) {
				$ProfileGalleryFixed = $ProfileContactDisplay;
			} elseif ($bb_agency_option_profilenaming == 3) {
				$ProfileGalleryFixed = "ID ". $ProfileID;
			}
			$ProfileGalleryFixed = bb_agency_safenames($ProfileGalleryFixed); 
		
		    if(in_array($ProfileGallery,$arrayReservedFoldername)){
			    $ProfileGalleryFixed = bb_agency_just_checkdir($ProfileGalleryFixed);
			    $arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
	        }
			echo "<div>\n";
			// Check for duplicate
			$query_duplicate = "SELECT ProfileGallery, count(ProfileGallery) as cnt FROM ". table_agency_profile ." WHERE ProfileGallery='".$ProfileGallery."' GROUP BY ProfileGallery   HAVING cnt > 1";
			$rs = mysql_query($query_duplicate);
			$count  = mysql_num_rows($rs);
			if($count > 0){
					
				// Add Profiles to Array to Create later
				$throw_error = true;
				$ProfileGalleryFixed =  bb_agency_set_directory($ProfileGalleryFixed);
				echo "  <span style='width: 240px; color: red;'>". bb_agency_UPLOADDIR  . $ProfileGallery ."/</span>\n";
				echo "  <strong>Profile <a href='admin.php?page=bb_agency_profiles&action=editRecord&ProfileID=". $data1['ProfileID'] ."'>". $data1['ProfileContactNameFirst'] ." ". $data1['ProfileContactNameLast'] ."</a></strong>\n";
				echo "  Should be renamed to /<span style='width: 240px; color: red;'>". $ProfileGalleryFixed ."/</span>\n";

			} elseif ($ProfileGallery == $ProfileGalleryFixed ) {
				echo "  <span style='width: 240px; color: green;'>". bb_agency_UPLOADDIR  . $ProfileGallery ."/</span>\n";
			}
			$pos++;
		}//endwhile
			
			echo "</div>\n";
		if ($count1 < 1) {
				
            echo "There are currently no profile records.";
				
		} elseif ($throw_error == true) { ?>
            <a name="generate"></a>
            <h3>Generate Folders for Profiles</h3>
            <p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
            <p><a class="button-primary" href="?page=bb_agency_reports&ConfigID=<?php echo $ConfigID; ?>&action=generate" title="Generate Missing Folders for Profiles">Rename Profiles to match Privacy Settings</a>  Clicking this button will rename folders for the above profiles<p>
            <?php
		}
	} // To Generate or Not to Generate
	  
   
		
}
elseif ($ConfigID == 13) {

// *************************************************************************************************** //
// Manage Settings

    echo "<h2>". __("Resize Images", bb_agency_TEXTDOMAIN) . "</h2>\n";
	
	/*********** Max Size *************************************/
	$bb_agency_options_arr = get_option('bb_agency_options');
		$bb_agency_option_agencyimagemaxheight 	= $bb_agency_options_arr['bb_agency_option_agencyimagemaxheight'];
			if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) { $bb_agency_option_agencyimagemaxheight = 800; }
		$bb_agency_option_agencyimagemaxwidth 	= $bb_agency_options_arr['bb_agency_option_agencyimagemaxwidth'];
			if (empty($bb_agency_option_agencyimagemaxwidth) || $bb_agency_option_agencyimagemaxwidth < 500) { $bb_agency_option_agencyimagemaxwidth = 1000; }
	
	/*********** Step Size *************************************/
	$stepSize = 20;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ."";
	$results4t = mysql_query($query4t);
	$count4total = mysql_num_rows($results4t);
	
	if (isset($_GET['Step'])) { 
		$currentPage = $_GET['Step']; 
		$step = $currentPage * $stepSize;
	} else { 
		$currentPage = 1; 
		$step = 0;
	}
	
	$totalPages = ceil($count4total/$stepSize);
        //echo "Total pages:" . $totalPages;
        if($totalPages >= 1) {
            for($i = 1; $i <= $totalPages; $i++) {
    	        $pageString .= " <a href=\"?page=bb_agency_reports&ConfigID=13&Step={$i}$queryVars\">Page $i</a>";
                $pageString .= $i != $totalPages ? " | " : "";
    		}
        }
	echo "<div>". $pageString ."</div>\n";


	/*********** Query Database *************************************/
	
        $query = "SELECT ProfileID, ProfileContactNameFirst, ProfileContactNameLast, ProfileGallery FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst LIMIT $step,$stepSize"; //LIMIT $step,100
        $results = mysql_query($query);
        $count = mysql_num_rows($results);
        while ($data = mysql_fetch_array($results)) {
			
			echo "<div>\n";
			echo "<h3>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h3>\n";
            $ProfileGallery = $data['ProfileGallery'];
            $ProfileID = $data['ProfileID'];


            $queryImg = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  $ProfileID AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC";
            $resultsImg = mysql_query($queryImg);
            $countImg = mysql_num_rows($resultsImg);
			echo "<div><strong>$countImg total</strong></div>\n";
            while ($dataImg = mysql_fetch_array($resultsImg)) {
				$filename = bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
				
				$image = new bb_agency_image();
				$image->load($filename);
				echo "<div style=\"float: left; width: 110px;\">\n";
				
				if ($image->orientation() == "landscape") {
					if ($image->getWidth() > $bb_agency_option_agencyimagemaxwidth) {
						$image->resizeToWidth($bb_agency_option_agencyimagemaxwidth);
						echo "RESIZED LANDSCAPE<br />\n";
						$image->save(bb_agency_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
					}
				} else {
					if ($image->getHeight() > $bb_agency_option_agencyimagemaxheight) {
						$image->resizeToHeight($bb_agency_option_agencyimagemaxheight);
						echo "RESIZED PORTRAIT<br />\n";
						$image->save(bb_agency_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
					}
				}
				echo "  <img src=\"". $filename ."\" style=\"width: 100px; z-index: 1; \" />\n";
				echo "W: ". $image->getWidth() ."; H: ". $image->getHeight() ."<br />\n";
				echo "</div>\n";
            }
			if ($countImg < 1) {
				echo "<div>There are no images loaded for this profile yet.</div>\n";
			}
			echo "<div style=\"clear: both; \"></div>\n";
			echo "</div>\n";

		}

} // End 11
elseif ($ConfigID == 12) {
//Export database
// *************************************************************************************************** //
// Manage Settings

    echo "<h2>". __("Export Database", bb_agency_TEXTDOMAIN) . "</h2>\n";

	echo "<a href=\"". bb_agency_BASEDIR ."tasks/exportDatabase.php\">Export Database</a>\n";

}
elseif ($ConfigID == 81) 
{
    echo "<h2>". __(" Export Database", bb_agency_TEXTDOMAIN) . "</h2>\n";
    
    echo " <form action=\"".bb_agency_BASEDIR."tasks/export-Profile-Database.php\" method=\"post\">";
    echo "      <select name=\"file_type\">";
    echo "          <option value=\"\">Select file format</option>";
    echo "          <option value=\"xls\">XLS</option>";
    echo "          <option value=\"csv\">CSV</option>";
    echo "      </select>";
    echo "      <input type=\"submit\" value=\"Export Now\" class=\"button-primary\">";
    echo "  </form>";    
}
elseif ($ConfigID == 80) {

// *************************************************************************************************** //
// Import CSV or XLS files (NK)

    $obj_csv = new bbagencyCSVXLSImpoterPlugin();
    $error_message = ""; 
    $form_display_flag = true;

    global $wpdb;

    $custom_fields_bb_agency = $wpdb->get_results("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder", ARRAY_A);
    $fields_array = array( 0 => array('ProfileContactDisplay','ProfileContactNameFirst','ProfileContactNameLast','ProfileGender','ProfileDateBirth','ProfileContactEmail','ProfileContactWebsite','ProfileContactPhoneHome','ProfileContactPhoneCell','ProfileContactPhoneWork','ProfileLocationStreet','ProfileLocationCity','ProfileLocationState','ProfileLocationZip','ProfileLocationCountry','ProfileType','ProfileIsActive'));

    $count = count($fields_array[0]);
    foreach ($custom_fields_bb_agency as $key => $c_field) 
    {
        $fields_array[0][$count] = 'Client'.str_replace(' ', '',$c_field['ProfileCustomTitle']);
        $count++;
    }

    $target_path = WP_CONTENT_DIR.'/FORMAT.csv';

    $csv_format = fopen($target_path,'w');
    fputcsv($csv_format, $fields_array[0]);  
            
//    foreach ($fields_array as $key => $value) 
//    {
//        fputcsv($csv_format, $value);
//    }
    fclose($csv_format);
    chmod($target_path, 0777);
    

    if(isset($_POST['submit_importer']))
    {   
        /*Reading a file type to confirm input of CSV, XLS or XLSX file*/
        if($_FILES['source_file']['name'] == "")
        {
            $error_message = "Empty file!";
        }
        //echo $_FILES['source_file']['type'];die;
        if($_FILES['source_file']['type'] == 'application/octet-stream' || $_FILES['source_file']['type'] == 'application/vnd.ms-excel' || $_FILES['source_file']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') /*CSV and Excel files*/
        {
            $return_result = $obj_csv->match_column_and_table(); /*Display colunm head*/
            if( $return_result == 0)
            {
                $error_message = "Empty header!";
                $form_display_flag = true;
            }
            else
            {
                $form_display_flag = false;
            }
        }
        else
        {
            $error_message = 'Incorrect file format. Only CSV, XLS and XLSX file formats are allowed!';
            $form_display_flag = true;
        }
    }
    else if($_POST['submit_importer_to_db'])
    {
        $obj_csv->import_to_db();   /*Store profile data*/
        $form_display_flag = true;
    }
    
    if( $form_display_flag == true )
    {
        echo "<h2>". __("Import CSV / XLS", bb_agency_TEXTDOMAIN) . "</h2>\n";
        
        /*File error message*/
        echo "<span class=\"error-message\">$error_message</span> <br>";
        
        /*Form for file selection*/
        echo "  <form action=\"\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "      <label>Select File</label>";
        echo "      <input type=\"file\" name=\"source_file\">  <br>";
        echo "      <input type=\"submit\" id=\"submit\" class=\"button-primary\" value=\"Read Column Head\" name=\"submit_importer\">";
        echo "  </form>";
    }
    
}
// Install Dummy Accounts/Profiles with Media Content bb-agency/task/installDummy.php
elseif ($ConfigID == 14) {

		$trackDummies = array();
		$sample_url = bb_agency_BASEPATH."tasks/samples"; // Samples' folder
		
		$userProfileNames = array(
		"Arvay" 	=> "Steven",
		"Bailey"	=>"Victor",
		"Barr"		=>"Ann",
		"Benton"	=>"Jared",
		"Bousfield"	=>"Joanne",
		"Brading"	=>"Eric",
		"Brading"	=>"Richard",
		"Brading"	=>"Monique",
		"Bradley"	=>"John",
		"Champ"		=>"Camba",
		"Kristel" 	=> "Cuadra",
		"Fhil" 		=>"Barrion",
		"Anne" 		=> "Panlilio",
		"Childs"	=>"Trevor",
		"Cowal"		=>"Randy",
		"Curtis"	=>"Bradley",
		"Dales"     =>"Otmar",
		"Dickson"   =>"Elizabeth",
		"Downs"     =>"Ricardo",
		"Ellis"     =>"Carlita",
		"Ezeard"=>"Theresa",
		"Fields"=>"Grace",
		"Fields"=>"Marvette",
		"Goutouski"=>"Geoffrey",
		"Gullis"=>"Nancy",
		"Hastman"=>"Joanne",
		"Hearns"=>"Joyce",
		"Holmes"=>"Bernice",
		"Holmes"=>"Myra",
		"Holmes"=>"Kelly",
		"Ingles"=>"Patrick",
		"Jackson"=>"Jeffrey",
		"Jesch"=>"Kay ",
		"Johnson"=>"Jeanette",
		"Johnson"=>"David",
		"Johnson"=>"Leonard",
		"Jones"=>"Ford",
		"Jones"=>"Ford",
		"Kunick"=>"Samuel",
		"Ladell"=>"Jillian",
		"Lazenby"=>"Andrew",
		"Leonard"=>"Heather",
		"Leonard"=>"Heather",
		"Leslie"=>"Sharon",
		"Marr"=>"Edward",
		"McCarron"=>"Elijah",
		"McKinnon"=>"Jeanette",
		"McKinnon"=>"Adele",
		"Muir"=>"Caitlin",
		"Newell"=>"Samantha",
		"Onstein"=>"Ethel",
		"Onstein"=>"Lee",
		"Page"=>"Inez",
		"Ralph"=>"Debra",
		"Riaz	"=>"Carl",
		"Robertson"=>"Ethel",
		"Rouse"=>"Wycliffe",
		"Rouse"=>"Marco",
		"Schmitz"=>"Joseph",
		"Shannon"=>"Barbara",
		"Smith"=>"Jason",
		"Smith"=>"Carrie",
		"Smith"=>"Margaret",
		"Smith"=>"Margaret",
		"Smith"=>"Bradley",
		"Stanfield"=>"Joyce",
		"Sutherland"=>"Louise",
		"Sutherland"=>"Lynn",
		"Taylor"=>"Valerie",
		"Tallyn"=>"Glenn",
		"Taylor"=>"Eleanor",
		"Taylor"=>"Ann",
		"Taylor"=>"Valerie",
		"Timber"=>"Doris",
		"Valdez"=>"Karen",
		"Wickson"=>"Jean",
		"Wilson"=>"Millicent",
		"Wood"=>"Drucylla",
		"Taylor"=>"Valerie",
		"Wood"=>"Janice",
		"Woodley"=>"Bartholomew",
		"Wright"=>"Andre",
		"Wright"=>"Brenda"
		);
		
		$userMediaVideo = array(
			"http://www.youtube.com/watch?v=0hMzBRM96gk",
			"http://www.youtube.com/watch?v=xNiSREeN-rk",
			"http://www.youtube.com/watch?v=c8YZIL8JZfg",
			"http://www.youtube.com/watch?v=Mx3wQGU862E",
			"http://www.youtube.com/watch?v=p8DYtnBa4a8",
			"http://www.youtube.com/watch?v=y58mkKh_0Gw"
		);

		$userMediaImagesM = array("male_model-01.jpg","male_model-02.jpg","male_model-03.jpg","male_model-04.jpg","male_model-05.jpg","male_model-06.jpg","male_model-07.jpg","male_model-08.jpg","male_model-09.jpg");
		$userMediaImagesF = array("female_model-01.jpg","female_model-02.jpg","female_model-03.jpg","female_model-04.jpg","female_model-05.jpg","female_model-06.jpg","female_model-07.jpg","female_model-08.jpg","female_model-09.jpg");
		$userMediaVideoType = array("Demo Reel","Video Monologue","Video Slate");
		$userMediaHeadshot = array("headshot.jpg","headshot-2.jpg");
		$userMediaResume = array("resume.docx","resume_PDF.pdf");
		$userMediaCompcard = array("comp-card.jpg");
		$userMediaVoicedemo = array("voice-demo.mp3");


		#========== Register dummies to track===
		foreach($userProfileNames as $ProfileContactNameFirst => $ProfileContactNameLast):	
			$ProfileContactDisplay = "";
			$ProfileGallery = "";
						
			if (empty($ProfileContactDisplay)) {  // Probably a new record... 
				if ($bb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($bb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($bb_agency_option_profilenaming == 2) {
					$error .= "<b><i>". __(LabelSingular ." must have a display name identified", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
					$have_error = true;
				} elseif ($bb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID ". $ProfileID;
				}
			  }
		
			  if (empty($ProfileGallery)) {  // Probably a new record... 
				$ProfileGallery = bb_agency_safenames($ProfileContactDisplay); 
				}
				
			$ProfileGallery = bb_agency_just_checkdir($ProfileGallery);
			#DEBBUG echo $ProfileGallery ."<Br/>";
			array_push($trackDummies,$ProfileGallery);
		endforeach;
		 
		$trackDummies_text = implode(",",$trackDummies);
		
		echo "<form method=\"post\" action=\"options.php\">\n";
		echo "<br/><br/>";    
			settings_fields( 'bb-agency-dummy-settings-group' ); 
	     	      $bb_agency_dummy_options_arr = get_option('bb_agency_dummy_options');
			
			if (empty($bb_agency_dummy_options_installdummy)) { $bb_agency_dummy_options_installdummy =""; }
			$bb_agency_dummy_options_installdummy = $bb_agency_dummy_options_arr['bb_agency_dummy_options_installdummy'];
			
			
		if(empty($bb_agency_dummy_options_installdummy)){	
			echo "<input type=\"hidden\" name=\"bb_agency_dummy_options[bb_agency_dummy_options_installdummy]\" value=\"".$trackDummies_text."\" />\n";
			echo "<input type=\"submit\" name=\"generate\" value=\"Generate Dummies Now!\" />\n";
			$_SESSION["trackDummies_text"] = $trackDummies_text;
		}else{
			echo "<input type=\"submit\" name=\"remove\" value=\"Remove All ".count($userProfileNames)." Dummy Accounts generated\" />\n";	
			echo "<input type=\"hidden\" name=\"bb_agency_dummy_options[bb_agency_dummy_options_installdummy]\" value=\"\" />\n";
			
		}
		echo "</form>\n";
		#END========== Register dummies to track===

		if(isset($_GET["settings-updated"]) && empty($bb_agency_dummy_options_installdummy)){
				
			  echo "<h2>". __("Removing Dummy Profiles...", bb_agency_TEXTDOMAIN) . "</h2>\n";
			  echo "<br/>Succesfully removed...";
			  echo "<br/>";
			  $trackDummies = explode(",",$_SESSION["trackDummies_text"]);
			 
				 // Track dummies to pull out
				 foreach($trackDummies as $gallery){
					 echo "<strong>/".$gallery."/</strong> linked directory removed.<br/>";
				  $qID = mysql_query("SELECT ProfileID,ProfileGallery FROM ".table_agency_profile ." WHERE ProfileGallery = '".$gallery."' ") or die("1".mysql_error());
				  $fID = mysql_fetch_assoc($qID);
				 
				  mysql_query("DELETE FROM ".table_agency_profile ." WHERE ProfileID = '".$fID["ProfileID"]."' ") or die("2".mysql_error());
				  mysql_query("DELETE FROM ".table_agency_profile_media ." WHERE ProfileID = '".$fID["ProfileID"]."' ") or die("3".mysql_error());
				  
				  uninstall_dummy_profile($gallery);
				 }
					
				unset($_SESSION["trackDummies_text"]); 
		}
					
				
		if(isset($_GET["settings-updated"]) && !empty($bb_agency_dummy_options_installdummy) && isset($_SESSION["trackDummies_text"])){	
				echo "<h2>". __("Installing Dummies...", bb_agency_TEXTDOMAIN) . "</h2>\n";
				echo "<br/>";  
			  	echo "Succesfully created ".count($userProfileNames)." dummy profiles..<br/>";
			
						 foreach($userProfileNames as $ProfileContactNameLast => $ProfileContactNameFirst){
							  
							  $ProfileContactDisplay = "";
							  $ProfileGallery = "";
							  $userCategory = "";
							  $userGender ="";
							
							  $queryGender = mysql_query("SELECT * FROM ".table_agency_data_gender."  WHERE GenderID >= (SELECT FLOOR( MAX(GenderID) * RAND()) FROM ".table_agency_data_gender." ) ORDER BY RAND() LIMIT 1");
							  $userGender = mysql_fetch_assoc($queryGender);
							  
							  $queryCategory = mysql_query("SELECT * FROM ".table_agency_data_type."  WHERE DataTypeID >= (SELECT FLOOR( MAX(DataTypeID) * RAND()) FROM ".table_agency_data_type." ) ORDER BY  RAND() LIMIT 1");
							  $userCategory = mysql_fetch_assoc($queryCategory);
							  mysql_free_result($queryGender);
							  mysql_free_result($queryCategory);
							   
							   
							  echo $ProfileContactNameFirst." ".$ProfileContactNameLast."<br/>";
							  
							 if (empty($ProfileContactDisplay)) {  // Probably a new record... 
								if ($bb_agency_option_profilenaming == 0) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
								} elseif ($bb_agency_option_profilenaming == 1) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
								} elseif ($bb_agency_option_profilenaming == 2) {
									$error .= "<b><i>". __(LabelSingular ." must have a display name identified", bb_agency_TEXTDOMAIN) . ".</i></b><br>";
									$have_error = true;
								} elseif ($bb_agency_option_profilenaming == 3) {
									$ProfileContactDisplay = "ID ". $ProfileID;
								}
							}
						
							if (empty($ProfileGallery)) {  // Probably a new record... 
								$ProfileGallery = bb_agency_safenames($ProfileContactDisplay); 
							}
								
							  $ProfileGallery = bb_agency_checkdir($ProfileGallery);
								
							  $insert = "INSERT INTO " . table_agency_profile . "(
    								  ProfileGallery,
    								  ProfileContactDisplay,
    								  ProfileContactNameFirst,
    								  ProfileContactNameLast,
    								  ProfileIsActive,
    								  ProfileGender,
    								  ProfileType,
                                      ProfileDateBirth,
                                      ProfileDateDue, 
    							  ) VALUES (
    								  '".$ProfileGallery."',
    								  '".trim($ProfileContactDisplay)."',
    								  '".trim($ProfileContactNameFirst)."',
    								  '".trim($ProfileContactNameLast)."',
    								  1,
    								  '".$userGender["GenderID"]."',
    								  '".$userCategory["DataTypeID"]."',
    								  '".date('Y-m-d', strtotime(mt_rand(1970,2010).'-'.mt_rand(1,12)."-".mt_rand(1,30)))."',
                                      '".date('Y-m-d', strtotime(mt_rand(2013,2016).'-'.mt_rand(1,12)."-".mt_rand(1,30)))."'
    							 );"; 
							  
							$results = $wpdb->query($insert) or die(mysql_error());
							$ProfileID = $wpdb->insert_id;
								
							$rand = rand(0,1); // 2
							$randTo6 = rand(0,5); //6
							$randTo4 = rand(0,4); // 5
							$randTo8 = rand(0,8); // 5
						   
							  for($a=0; $a<=3; $a++){
								  
								  // Copy images
								  if($a<=3){
									if ($userGender["GenderID"] % 2 != 0) {
									 if(!copy(bb_chmod_file_display($sample_url."/".$userMediaImagesM[$a]),bb_chmod_file_display(bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a]))){
										echo $sample_url."/".$userMediaImagesM[$a]."<br/>".bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a];
										echo "<br/>";
										die("Failed to Copy files... <br/>".phpinfo());
									 }
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$a] ."','". $userMediaImagesM[$a] ."')");
									} else {
									 if(!copy(bb_chmod_file_display($sample_url."/".$userMediaImagesF[$a]),bb_chmod_file_display(bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a]))){
										echo $sample_url."/".$userMediaImagesF[$a]."<br/>".bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a];
										echo "<br/>";
										die("Failed to Copy files... <br/>".phpinfo());
									 }
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImages[$a] ."','". $userMediaImages[$a] ."')");
									}
								  }
								  if($a<=3){
									$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $userMediaVideoType[$a]."','".bb_agency_get_VideoFromObject($userMediaVideo[$randTo6]) ."','". bb_agency_get_VideoFromObject($userMediaVideo[$randTo6])  ."')");
								  }
								  if($a==1){ 
									if ($userGender["GenderID"] % 2 != 0) {
									 copy(bb_chmod_file_display($sample_url."/".$userMediaImagesM[$randTo8]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$randTo8]);
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$randTo8]."','". $userMediaImagesM[$randTo8] ."',1)") or die(mysql_error());
									} else {
									 copy(bb_chmod_file_display($sample_url."/".$userMediaImagesF[$randTo8]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$randTo8]);
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesF[$randTo8]."','". $userMediaImagesF[$randTo8] ."',1)") or die(mysql_error());
									}
									copy(bb_chmod_file_display($sample_url."/".$userMediaHeadshot[$rand]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaHeadshot[$rand]);
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Headshot','". $userMediaHeadshot[$rand]."','". $userMediaHeadshot[$rand] ."')");
									
									copy(bb_chmod_file_display($sample_url."/".$userMediaVoicedemo[0]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaVoicedemo[0]);
									 $results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','VoiceDemo','". $userMediaVoicedemo[0] ."','".  $userMediaVoicedemo[0] ."')");
												 
									copy(bb_chmod_file_display($sample_url."/".$userMediaCompcard[0]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaCompcard[0]);
									$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','CompCard','".$userMediaCompcard[0] ."','". $userMediaCompcard[0]."')");
									
									copy(bb_chmod_file_display($sample_url."/".$userMediaResume[$rand]),bb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaResume[$rand]);
									$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Resume','". $userMediaResume[$rand]."','".$userMediaResume[$rand]."')");
												 
								  }
								
							  }
						   } // End foreach
						   
			 unset($_SESSION["trackDummies_text"]);
		} // if option is empty
		
		
			
		if (isset($_GET["a"])){
				unset($_SESSION["trackDummies_text"]); 
				uninstall_allprofile();
		}
} //END $ConfigID == 14
elseif($ConfigID == '99'){

    echo "<h2>". __("Generate Login / Passwords", bb_agency_TEXTDOMAIN) . "</h2>\n";

    bb_display_profile_list();

    
}




/******************************************************************************************/



function uninstall_dummy_profile($profile){
	
	
	 $dir  = bb_agency_UPLOADPATH .$profile;  
	 foreach (scandir($dir) as $item) {
 			if ($item == '.' || $item == '..') continue;
  	 		 unlink($dir.DIRECTORY_SEPARATOR.$item);
	 }
	 rmdir($dir);
}

function uninstall_allprofile(){
	
	  mysql_query("TRUNCATE TABLE ".table_agency_profile ."");
	  mysql_query("TRUNCATE TABLE ".table_agency_profile_media ."");
	 $dir  = bb_agency_UPLOADPATH."/";  
	 foreach (scandir($dir) as $item) {
 			if ($item == '.' || $item == '..') continue;
  	 		 unlink($dir.DIRECTORY_SEPARATOR.$item);
	 }
	
}
// just check directory existence no creation
function bb_agency_just_checkdir($ProfileGallery){
	      	
		
	$finished = false;      
	$pos = 0;                 // we're not finished yet (we just started)
	while ( ! $finished ):                   // while not finished
	 $pos++;
	  $NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
	  if ( ! is_dir(bb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
		  if(($pos-1) <=0){
			$ProfileGallery = $ProfileGallery;  // Set it to the new  thing
		}else{
			$ProfileGallery = $ProfileGallery ."-".($pos-1);  // Set it to the new  thing
		}
		$finished = true;                    // ...we are finished
	  endif;
	endwhile;
	
	return $ProfileGallery;

			
			
}
 
 
function bb_agency_set_directory($ProfileGallery){
	 
			   $finished = false;      
				$pos = 0;                 // we're not finished yet (we just started)
				while ( ! $finished ):                   // while not finished
				 $pos++;
				  $NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
				  if ( ! is_dir(bb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
				      if(($pos-1) <=0){
						$ProfileGallery = $ProfileGallery;  // Set it to the new  thing
					}else{
						$ProfileGallery = $ProfileGallery ."-".($pos);  // Set it to the new  thing
					}
					$finished = true;                    // ...we are finished
				  endif;
				endwhile;
				
				return $ProfileGallery;
}


function bb_chmod_file_display($file){
    @chmod($file,0755);	
    return $file;
}


/*Naresh Kumar @ Matrix Infologics*/

class bbagencyCSVXLSImpoterPlugin {
    var $log = array();
    /**
     * give the absolute path to the file
     *
     * @return file path
     */

    public function __construct()
    {
        define('WP_CSV_TO_DB_FOLDER', dirname(plugin_basename(__FILE__)));
        define('WP_CSV_TO_DB_URL', plugins_url('',__FILE__));
    }
   
    function csv_to_db_get_abs_path_from_src_file($src_file){
        if(preg_match("/http/",$src_file)){
            $path = parse_url($src_file, PHP_URL_PATH);
            $abs_path = $_SERVER['DOCUMENT_ROOT'].$path;
            $abs_path = realpath($abs_path);
            if(empty($abs_path)){
                $wpurl = get_bloginfo('wpurl');
                $abs_path = str_replace($wpurl,ABSPATH,$src_file);
                $abs_path = realpath($abs_path);            
            }
        }
        else{
            $relative_path = $src_file;
            $abs_path = realpath($relative_path);
        }
        return $abs_path;
    }
    /**
     * Match CSV Columns and Custom Field ID
     *
     * @return void
     */

    function match_column_and_table(){
        global $wpdb;
        
        //$get_ext =  explode('.', $_FILES['source_file']['name']);
        $get_ext = pathinfo($_FILES['source_file']['name'], PATHINFO_EXTENSION);
        $target_path = WP_CONTENT_DIR.'/plugins/bb-agency/file_upload/';
        $target_path = $target_path . basename( $_FILES['source_file']['name']);
        
        if( strtolower($get_ext) == 'csv' )  /*If uploaded file is a CSV*/
        {
            if(move_uploaded_file($_FILES['source_file']['tmp_name'], $target_path))
            {
                $file_name = WP_CONTENT_DIR.'/plugins/bb-agency/file_upload/'.basename( $_FILES['source_file']['name']);
                update_option('wp_csvtodb_input_file_url', $file_name);
            }
            else
            {
                echo "error uploading the file";
            }
        }
        else    /*If uploaded file is excel*/
        {
            if( strtolower($get_ext[1]) == 'xls' )
            {
                $inputFileType = 'Excel5';  /*XLS File type*/
            } 
            else
            {
                $inputFileType = 'Excel2007';  /*XLS File type*/  
            }
            include WP_CONTENT_DIR.'/plugins/bb-agency/Classes/PHPExcel/IOFactory.php';
            $f_name = date('d_M_Y_h_i_s');
            
            move_uploaded_file($_FILES['source_file']['tmp_name'], $target_path);
            
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($target_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $t_file = date('d_M_Y_h_i_s');
            $csv_file = fopen($target_path.$t_file.'(1).csv','w');
            
            foreach ($sheetData as $key => $value) 
            {
                fputcsv($csv_file, $value);
            }
            fclose($csv_file);
            $file_name = $target_path.$t_file.'(1).csv';
            $clone = $file_name;
        }
        
        $file_path = $this->csv_to_db_get_abs_path_from_src_file($file_name);   
        $handle = fopen($file_path ,"r");       
        $header=fgetcsv($handle, 4096, ",");
        $total_header = count($header);

        $custom_header = $total_header - 17;//17 are the number of column for the personal profile table
        
        if( $custom_header <= 0 ) return 0; /*If no custom field found*/

        
        /*Column head form*/
        echo "<div class=\"wrap\">";
        echo "<h2>Import CSV</h2>";
        echo "<form  method=\"post\" action=\"\">";
        
        echo '<input type="hidden" value ="'.$custom_header.'" name="custom_header">
              <input type="hidden" value ="'.$total_header.'" name="total_header">
              <input type="hidden" value ="'.$file_path.'" name="file_path">
              <input type="hidden" value ="'.$clone.'" name="clone">';
        $default = 1;
        $heads = 17;
        $t_head = $custom_header;
        $custom_fields = $wpdb->get_results("Select ProfileCustomID,ProfileCustomTitle from bb_agency_customfields ORDER BY ProfileCustomID ASC");
        echo "<table class=\"form-table\">";
        echo "<tbody>";
        for($i = 0; $i < $t_head; $i++){
            echo '<tr><th><label>'.$header[$heads].'</label></th>';
            $custom_fields = $wpdb->get_results("Select ProfileCustomID,ProfileCustomTitle from bb_agency_customfields ORDER BY ProfileCustomID ASC");
            echo '<td><select name = "select'.$default.'" id="select'.$default.'">';
            foreach ($custom_fields as $custom_fields_result) {
                $custom_field_id = intval($custom_fields_result->ProfileCustomID);
                $custom_field_title = $custom_fields_result->ProfileCustomTitle;
                if($custom_field_id==$default){
                    $is_default = ' selected="selected" ';
                }
                else{
                    $is_default =''; 
                }
                echo '<option value="'.$custom_field_id.'"'.$is_default.'>'.$custom_field_title.'</option>';
            }
            echo '</select>';
            echo '</td></tr>';
            //$custom_header++;
            $heads++;
            $default++;
        }
        echo "<tbody>";
        echo "<table>";
        echo "<div style=\"clear:both\"></div>";
        echo "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary\" name=\"submit_importer_to_db\" value=\" Import Data \" /></p>";
        echo "</form>";
        echo "</div>";
        return 1;
    }
    /**
     * Insert the data into the database
     *
     * @return void
     */
    function import_to_db(){
        $p_table_fields = "ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive";
        $c_table_fields = "ProfileCustomID,ProfileID,ProfileCustomValue";       
        set_time_limit(0);
        $path_to_file = $_REQUEST['file_path'];
        $handle = fopen($path_to_file ,"r");
        fgets($handle);//read and ignore the first line
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $ctrl_start = 17;
            $ctrl_end = $_REQUEST['total_header'];
            $incre = 1;
            global $wpdb;
			if($data[3]!="" &&  $data[5]!=""){
				$queryGenderResult = $wpdb->get_row("SELECT GenderID FROM ".table_agency_data_gender." WHERE GenderTitle ='".$data[3]."'", ARRAY_A);
				$ProfileContactDisplay = $wpdb->get_row("SELECT ProfileID FROM ".bb_agency_profile." WHERE ProfileContactEmail ='".mysql_real_escape_string($data[5])."'", ARRAY_A);
				if(!isset($ProfileContactDisplay['ProfileID']) ||  $ProfileContactDisplay['ProfileID'] ==""){
					
						$add_to_p_table="INSERT into bb_agency_profile($p_table_fields)values('$data[0]','$data[1]','$data[2]','".$queryGenderResult['GenderID']."','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]','$data[13]','$data[14]','$data[15]','$data[16]')";
						mysql_query($add_to_p_table) or die(mysql_error());

						$last_inserted_mysql_id = mysql_insert_id();
						if($last_inserted_mysql_id){
							while($ctrl_start < $ctrl_end){
								$select_id =  mysql_real_escape_string($_REQUEST['select'.$incre]);
								if(strpos($data[$ctrl_start], ' ft ') !== FALSE){
									$cal_height = 0;
									$height = explode(' ', $data[$ctrl_start]);
									$cal_height = ($height[0] * 12) + $height[2];
									$data[$ctrl_start]  = $cal_height;
									
								}
								
								$add_to_c_table="INSERT into bb_agency_customfield_mux($c_table_fields)values('".$select_id."','".$last_inserted_mysql_id."','".mysql_real_escape_string($data[$ctrl_start])."')";
								mysql_query($add_to_c_table) or die(mysql_error());
								$ctrl_start++;
								$incre++;
							
							}
						
						}
					 echo "<div class='wrap' style='color:#008000'><ul><li> User Name:- ".$data[0]." & Email:- ".$data[5]."  <b>Successfully Imported Records</b></li></ul></div>";
				}else{
					 echo "<div class='wrap' style='color:#FF0000'><ul><li> User Name:- ".$data[0]." & Email:- ".$data[5]."  <b>Successfully Not Imported. Email Already Used on site.</b></li></ul></div>";
				}
			}
        }
        if($_REQUEST['clone'] != "") unlink($_REQUEST['clone']);

    }
    /**
     * Upload Form
     *
     * @return void
     */
    function form() {
        if(isset($_POST['read'])){
            $this->match_column_and_table();
        }
        else
        {
        if(isset($_POST['import_to_db'])){
            $this->import_to_db();
        }
?>
            <div style="clear:both"></div>
            <div class="wrap">
                <h2>Import CSV</h2>
                <form class="add:the-list: validate" method="post" enctype="multipart/form-data" action="">
                    <p><label for="csv_import">Only CSV Files are accepted</label><br/></p>
                    <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
                    <p class="submit"><input type="submit" class="button" name="read" value=" Read Column Headings " /></p>
                </form>
            </div>
<?php
        }
    }
    /**
     * Plugin's interface
     *
     * @return void
     */
    function print_messages() {
        if (!empty($this->log)) {
    // messages HTML {{{
?>
            <div class="wrap">
                <?php if (!empty($this->log['error'])): ?>
                <div class="error">
                    <?php foreach ($this->log['error'] as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($this->log['notice'])): ?>
                <div class="updated fade">
                    <?php foreach ($this->log['notice'] as $notice): ?>
                        <p><?php echo $notice; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
<?php
    // end messages HTML }}}
            $this->log = array();
        }
    }
    /**
     * Format Date
     *
     * @return Y-m-d H:i:s
     */
    function parse_date($data) {
        $timestamp = strtotime($data);
        if (false === $timestamp) {
            return '';
        } else {
            return date('Y-m-d H:i:s', $timestamp);
        }
    }    
}
?>
</div>