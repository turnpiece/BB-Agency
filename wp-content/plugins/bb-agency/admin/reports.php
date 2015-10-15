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

if (!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])) { 
    $ConfigID = 0;
} else { 
    $ConfigID = $_REQUEST['ConfigID']; 
}

if ($ConfigID == 0) {
	
    ?>
    <div class="boxlinkgroup">
        <h2>Import</h2>
        <div class="boxlink">
            <a class="button-primary" href="?page=<?php echo $_GET['page'] ?>&amp;ConfigID=79" title="<?php _e('Custom Import', bb_agency_TEXTDOMAIN) ?>"><?php _e('Custom Import', bb_agency_TEXTDOMAIN) ?></a>
        </div>
    </div>
    <?php

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
elseif ($ConfigID == 13) {

// *************************************************************************************************** //
// Manage Settings

    echo "<h2>". __("Resize Images", bb_agency_TEXTDOMAIN) . "</h2>\n";
	
	/*********** Max Size *************************************/
	$bb_options = bb_agency_get_option();
		$bb_agency_option_agencyimagemaxheight 	= bb_agency_get_option('bb_agency_option_agencyimagemaxheight');
			if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) { $bb_agency_option_agencyimagemaxheight = 800; }
		$bb_agency_option_agencyimagemaxwidth 	= bb_agency_get_option('bb_agency_option_agencyimagemaxwidth');
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
elseif ($ConfigID == 79)
{
    ?>
    <h2><?php _e('Import from another site', bb_agency_TEXTDOMAIN) ?></h2>
    <?php 
        if ($_POST) {
            bb_import_from_database();
        } 
    ?>
    <form action="" method="post">
        <input type="hidden" name="ConfigID" value="79" />
        <table>
            <tr>
                <td><label for="db_name"><?php _e( "Database name", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_name" value="" /></td>
            </tr>
            <tr>
                <td><label for="db_user"><?php _e( "Database user", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_user" value="" /></td>
            </tr>
            <tr>
                <td><label for="db_pass"><?php _e( "Database password", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="password" name="db_pass" value="" /></td>
            </tr>
            <tr>
                <td><label for="db_host"><?php _e( "Database host", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_host" value="" /></td>
            </tr>
            <tr>
                <td><label for="db_host"><?php _e( "Profile Type ID on external site", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="ExtProfileType" value="" /></td>
            </tr>
            <tr>
                <td><label for="media_url"><?php _e( "Media URL on external site", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="media_url" value="" /></td>
            </tr>
            <?php $dataTypes = bb_agency_get_datatypes(false); if (!empty($dataTypes)) : ?>
            <tr>
                <td><label for="ProfileType"><?php _e( 'ProfileType', bb_agency_TEXTDOMAIN ) ?></label></td>
                <td>
                    <select name="ProfileType" id="ProfileType">               
                        <?php foreach ($dataTypes as $type) : ?>
                        <option value="<?php echo $dataType->DataTypeID ?>" <?php selected($type->DataTypeID, $_SESSION['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <input type="submit" value="Import Now" class="button-primary">
    </form>
    <?php
}
elseif ($ConfigID == 81) 
{
    ?>
    <h2><?php _e("Export Models", bb_agency_TEXTDOMAIN) ?></h2>
    
    <form action="<?php echo bb_agency_BASEDIR ?>tasks/export-Profile-Database.php" method="post">
        <select name="file_type">
            <option value="">Select file format</option>
            <option value="xls">XLS</option>
            <option value="csv">CSV</option>
        </select>
        <?php $dataTypes = bb_agency_get_datatypes(false); if (!empty($dataTypes)) : ?>
        <select name="ProfileType" id="ProfileType">               
            <option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
            <?php foreach ($dataTypes as $type) : ?>
            <option value="<?php echo $dataType->DataTypeID ?>" <?php selected($type->DataTypeID, $_SESSION['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <input type="submit" value="Export Now" class="button-primary">
    </form>
    <?php
}






/******************************************************************************************/


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

/**
 *
 * import profiles from another database
 * ie. from another site
 *
 */
function bb_import_from_database() {

    global $wpdb;

    if (isset($_POST['db_host']) && 
        isset($_POST['db_name']) && 
        isset($_POST['db_user']) && 
        isset($_POST['db_pass'])) {

        $conn = mysql_connect( $_POST['db_host'], $_POST['db_user'], $_POST['db_pass'] );

        if (!$conn)
            die( "Failed to connect to database as ".$_POST['db_user'] );

        mysql_select_db( $_POST['db_name'], $conn ) || die( "Unable to connect to database ". $_POST['db_name'] );

        // we're connected
        
        // get profile type
        $ExtProfileType = isset($_POST['ExtProfileType']) ? $_POST['ExtProfileType'] : 0;
        $ProfileType = isset($_POST['ProfileType']) ? $_POST['ProfileType'] : 0;

        $sql = "SELECT * FROM `".table_agency_profile."`";

        if ($ProfileType > 0)
            $sql .= " WHERE `ProfileType` = $ExtProfileType";

        // get the profile table columns
        $p_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile."`");

        // get media table columns
        $m_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile_media."`");

        // custom fields
        $custom_fields = $wpdb->get_results("SELECT `ProfileCustomID`, `ProfileCustomTitle` FROM ". table_agency_customfields ." ORDER BY `ProfileCustomOrder`");

        $profiles = mysql_query($sql, $conn);

        if (!mysql_num_rows($profiles))
            die( "Failed to get any profiles." );

        while ( $profile = mysql_fetch_array($profiles) ) {

            bb_log_message( $profile['ProfileContactDisplay'] );

            $insert_data = array();
            
            foreach ( $p_cols as $col ) {

                if ($col->Field == 'ProfileID') {
                    $oldID = $profile[$col->Field];
                    continue;
                } elseif ($col->Field == 'ProfileType') {
                    $insert_data[$col->Field] = $ProfileType;
                } else {
                    $insert_data[$col->Field] = $profile[$col->Field];
                }
            }

            $wpdb->insert( table_agency_profile, $insert_data );

            $ProfileID = $wpdb->insert_id;

            // do media
            $media_sql = "SELECT * FROM `".table_agency_profile_media."` WHERE `ProfileID` = $oldID";

            $medias = mysql_query($media_sql, $conn);

            $media_url = $_POST['media_url'] ? trailingslashit($_POST['media_url']) : false;

            if (mysql_num_rows($medias) > 0) {

                while( $media = mysql_fetch_array($medias) ) {

                    $media_data = array( 'ProfileID' => $ProfileID );

                    foreach( $m_cols as $col ) {

                        if ($col->Field == 'ProfileMediaID' || $col->Field == 'ProfileID')
                            continue;

                        $media_data[$col->Field] = $media[$col->Field];
                    }

                    $wpdb->insert( table_agency_profile_media, $media_data );

                    if ($media_url) {
                        // get media and import it
                        $file = $profile['ProfileGallery'] . '/' . $media['ProfileMediaURL'];

                        bb_save_media( $media_url . $file, bb_agency_UPLOADPATH . $file );
                    }
                }
            }

            // now do custom fields
            if (!empty($custom_fields)) {
                foreach( $custom_fields as $field ) {

                    $ext_cf_sql = "SELECT cf.`ProfileCustomID`, cf.`ProfileCustomTitle`, cfm.`ProfileCustomValue` FROM ". table_agency_customfields ." cf, ".table_agency_customfield_mux." cfm WHERE cf.`ProfileCustomID` = cfm.`ProfileCustomID` AND cfm.`ProfileID` = $oldID";

                    $ext_cf = mysql_query($ext_cf_sql, $conn);

                    if (mysql_num_rows($ext_cf) == 0)
                        continue;

                    while( $ext_field = mysql_fetch_array($ext_cf) ) {

                        if ($ext_cf['ProfileCustomTitle'] == $field->ProfileCustomTitle) {

                            $wpdb->insert( 
                                table_agency_customfield_mux, 
                                array(
                                    'ProfileCustomID' => $field->ProfileCustomID,
                                    'ProfileID' => $ProfileID,
                                    'ProfileCustomValue' => $ext_cf['ProfileCustomValue']
                                )
                            );
                        }
                    }  
                }         
            }
        }
    
    } else {
        die( "Did not receive database connection details." );
    }
}

function bb_save_media($url, $saveto){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
}

function bb_log_message($message) {
    echo '<p>' . $message . '</p>';
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