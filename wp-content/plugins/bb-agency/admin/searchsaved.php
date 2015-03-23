<?php

$siteurl = get_option('siteurl');

global $wpdb;
$bb_options = bb_agency_get_option();

	$bb_agency_option_agencyname		= bb_agency_get_option('bb_agency_option_agencyname');

	$bb_agency_option_agencyemail	= bb_agency_get_option('bb_agency_option_agencyemail');	
    $bb_agency_option_agencyheader	= bb_agency_get_option('bb_agency_option_agencyheader');

	$SearchMuxHash			= $_GET["SearchMuxHash"]; // Set Hash

if (isset($_POST['action'])) {
	$SearchID			= $_POST['SearchID'];

	$SearchTitle		= $_POST['SearchTitle'];

	$SearchType			= $_POST['SearchType'];

	$SearchProfileID	= $_POST['SearchProfileID'];

	$SearchOptions		= $_POST['SearchOptions'];
	$action = $_POST['action'];

	switch($action) {

	// Add

	case 'addRecord':

		if (!empty($SearchTitle)) {
			// Create Record
			$insert = "INSERT INTO " . table_agency_searchsaved .
			" (SearchTitle,SearchType,SearchProfileID,SearchOptions)" .
			"VALUES ('" . $wpdb->escape($SearchTitle) . "','" . $wpdb->escape($SearchType) . "','" . $wpdb->escape($SearchProfileID) . "','" . $wpdb->escape($SearchOptions) . "')";

		    $results = $wpdb->query($insert);

			$lastid = $wpdb->insert_id;
			echo ('<div id="message" class="updated"><p>Search saved successfully! <a href="'. admin_url("admin.php?page=". $_GET['page']) .'&action=emailCompose&amp;SearchID='. $lastid .'">Send Email</a></p></div>'); 

		} else {
       	    echo ('<div id="message" class="error"><p>Error creating record, please ensure you have filled out all required fields.</p></div>'); 
		}

	break;

	// Delete bulk

	case 'deleteRecord':

		foreach ($_POST as $SearchID) {
			mysql_query("DELETE FROM " . table_agency_searchsaved . " WHERE SearchID= $SearchID");
		}

		echo ('<div id="message" class="updated"><p>Profile deleted successfully!</p></div>');

	break;
	// Email

	case 'emailSend':

		if (!empty($SearchID)) :

			$SearchID				= $_GET['SearchID'];

			if (trim($_GET["SearchMuxHash"]) == '') {
				$SearchMuxHash			= bb_agency_random(8);
			}
			
			$SearchMuxToName		= $_POST['SearchMuxToName'];
			$SearchMuxToEmail		= $_POST['SearchMuxToEmail'];
			$SearchMuxSubject		= $_POST['SearchMuxSubject'];
			$SearchMuxMessage		= $_POST['SearchMuxMessage'];
			$SearchMuxCustomValue	= $_POST['SearchMuxCustomValue'];
			
			$link = get_bloginfo("url") ."/client-view/".$SearchMuxHash;
			
            $SearchMuxMessage	= str_ireplace("[link-place-holder]", "<a href='$link'>$link</a>", $SearchMuxMessage);

            // add terms link
            if (bb_agency_TERMS) {
                $SearchMuxMessage .= "\n\r\n\r\n\r" . sprintf(__('Any work undertaken is governed by our <a href="%s">Terms &amp; Conditions</a>', bb_agency_TEXTDOMAIN), bb_agency_TERMS);
            }

			// Create Record

			$insert = "INSERT INTO " . table_agency_searchsaved_mux .
			" (SearchID,SearchMuxHash,SearchMuxToName,SearchMuxToEmail,SearchMuxSubject,SearchMuxMessage,SearchMuxCustomValue)" .
			"VALUES ('" . $wpdb->escape($SearchID) . "','" . $wpdb->escape($SearchMuxHash) . "','" . $wpdb->escape($SearchMuxToName) . "','" . $wpdb->escape($SearchMuxToEmail) . "','" . $wpdb->escape($SearchMuxSubject) . "','" . $wpdb->escape($SearchMuxMessage) . "','" . $wpdb->escape($SearchMuxCustomValue) . "')";

		    $results = $wpdb->query($insert);

			$lastid = $wpdb->insert_id;
			// To send HTML mail, the Content-type header must be set
            
			// Mail it

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $bb_agency_option_agencyname .' <'. $bb_agency_option_agencyemail .'>' . "\r\n";

            // attachments
            $attachments = array();

            require_once(bb_agency_BASEPATH.'/Classes/ModelCard.php');

            $query = "SELECT `SearchProfileID` FROM " . table_agency_searchsaved ." WHERE `SearchID` = ".$SearchID;

            $pID = $wpdb->get_var($query);

            if (empty($pID))
                wp_die('Failed to find that saved search.');
                
            $query = "SELECT * FROM ". table_agency_profile ." WHERE `ProfileID` IN (".$pID.") ORDER BY `ProfileContactNameFirst` ASC";

            $profiles = $wpdb->get_results($query);

            foreach ($profiles AS $profile) {
                $Card = new ModelCard($profile->ProfileGallery);
                $attachments[] = $Card->filepath();
            }

			$isSent = wp_mail(
                bb_agency_SEND_EMAILS ? $SearchMuxToEmail : $bb_agency_option_agencyemail, 
                $SearchMuxSubject, 
                nl2br($SearchMuxMessage), 
                $headers,
                $attachments
            );

			if ($isSent) : ?>   			
                <div style="margin:15px;">
        			<div id="message" class="updated">
        			     Email successfully sent from <strong><?php echo $bb_agency_option_agencyemail ?></strong> to <strong><?php echo (bb_agency_SEND_EMAILS ? $SearchMuxToEmail : $bb_agency_option_agencyemail.'(would have gone to: '.$SearchMuxToEmail.')') ?></strong><br />
        			     Message sent: <p><?php echo $SearchMuxMessage ?></p>
        			</div>
        		</div>				
            <?php endif;
		endif;
	break;

	}
} elseif ($_GET['action'] == "deleteRecord") {

	$SearchID = $_GET['SearchID'];

	// Verify Record

	$queryDelete = "SELECT * FROM ". table_agency_searchsaved ." WHERE SearchID =  \"". $SearchID ."\"";

	$resultsDelete = mysql_query($queryDelete);

	while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		// Remove Casting

		$delete = "DELETE FROM " . table_agency_searchsaved . " WHERE SearchID = \"". $SearchID ."\"";

		$results = $wpdb->query($delete);

		echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
	}
} elseif (($_GET['action'] == "emailCompose") && isset($_GET['SearchID'])) {

	$SearchID = $_GET['SearchID'];
      $querySearch = mysql_query("SELECT * FROM " . table_agency_searchsaved_mux ." WHERE SearchID=".$SearchID." ");

	 $dataSearchSavedMux = mysql_fetch_assoc($querySearch);

	?>
    <div style="width:500px; float:left;">
        <?php if (!bb_agency_SEND_EMAILS) : ?>
        <p class="warning">WARNING: The site is currently in testing mode so any emails will be sent to <?php echo $bb_agency_option_agencyemail ?> rather than to the client.</p>
        <?php endif; ?>
        <h2><?php echo __("Search Saved", bb_agency_TEXTDOMAIN); ?></h2>
        <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page'])."&SearchID=".$_GET['SearchID']."&SearchMuxHash=".$_GET["SearchMuxHash"]; ?>">
      
            <div><label for="SearchMuxToName"><strong>Send to Name:</strong></label><br/><input style="width:300px;" type="text" id="SearchMuxToName" name="SearchMuxToName" value="<?php echo $dataSearchSavedMux["SearchMuxToName"]; ?>" /></div>
            <div><label for="SearchMuxToEmail"><strong>Send to Email:</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxToEmail" name="SearchMuxToEmail" value="<?php echo $dataSearchSavedMux["SearchMuxToEmail"]; ?>" /></div>
            <div><label for="SearchMuxSubject"><strong>Subject:</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxSubject" name="SearchMuxSubject" value="<?php echo $bb_agency_option_agencyname; ?> Casting Cart" /></div>
            <div><label for="SearchMuxMessage"><strong>Message: (copy/paste: [link-place-holder] )</strong></label><br/>
		        <textarea id="SearchMuxMessage" name="SearchMuxMessage" style="width: 500px; height: 300px; "><?php if (!isset($_GET["SearchMuxHash"])){  if (isset($dataSearchSavedMux["SearchMuxMessage"])) echo $dataSearchSavedMux["SearchMuxMessage"];else{echo "Click the following link (or copy and paste it into your browser): [link-place-holder]";}}else{echo "Click the following link (or copy and paste it into your browser): [link-place-holder]";} ?></textarea>
            </div>
            <p class="submit">
                <input type="hidden" name="SearchID" value="<?php echo $SearchID; ?>" />
                <input type="hidden" name="action" value="emailSend" />
                <input type="submit" name="submit" value="Send Email" class="button-primary" />
            </p>
	    </form>
    </div>
    <?php
	 
        $query = "SELECT search.`SearchTitle`, search.`SearchProfileID`, search.`SearchOptions`, searchsent.`SearchMuxHash` FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.`SearchID` = searchsent.`SearchID` WHERE search.`SearchID` = \"". $_GET["SearchID"]."\"";

        $qProfiles =  mysql_query($query);

        $data = mysql_fetch_array($qProfiles);

        if (empty($data) || !$data['SearchProfileID'])
            wp_die('Failed to find that saved search.');
            
        $query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$data['SearchProfileID'].") ORDER BY ProfileContactNameFirst ASC";

        $results = mysql_query($query);

        $count = mysql_num_rows($results);
                              
	?>
    <div style="padding:10px;max-width:580px;float:left;">
        <b>Preview: <?php echo  $count." Profile(s)"; ?></b>
        <div style="height:550px; width:580px; overflow-y:scroll;">
            <?php
            while ($data2 = mysql_fetch_array($results)) : ?>
                <div style="background:black; color:white;float: left; max-width: 100px; height: 180px; margin: 2px; overflow:hidden;">
				    <div style="margin:3px; max-width:250px; max-height:300px; overflow:hidden;">
				        <?php echo stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']); ?>
				        <br />
                        <?php if (bb_agency_EMAIL_CARDS) : ?>
                        <a href="<?php bloginfo('wpurl') ?>/card/<?php echo $data2['ProfileGallery'] ?>.jpg" target="_blank">
                        <?php else : ?>
                        <a href="<?php echo bb_agency_PROFILEDIR . $data2['ProfileGallery'] ?>" target="_blank">
                        <?php endif; ?>
                            <img style="max-width:130px; max-height:150px;" src="<?php echo bb_agency_UPLOADDIR . $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ?>" />
                        </a>
				    </div>
				</div>
            <?php endwhile;
        ?>
        </div>
    </div>
	<?php

} else {
?>

<div style="clear:both"></div>

<div class="wrap" style="min-width: 1020px;">
 <div id="bb-overview-icon" class="icon32"></div>
 <h2>Profile Search</h2>

	<?php
   
   if ($_GET["action"] == "searchSave") { // Add to Cart
		// Set Casting Cart Session

		if (isset($_SESSION['cartArray'])) {

			$cartArray = $_SESSION['cartArray'];
			$cartString = implode(",", array_unique($cartArray));

			?>
           <h3 class="title">Save Search and Email</h3>           
           <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
           <table class="form-table">
           <tbody>
               <tr valign="top">
                   <th scope="row">Group Title:</th>
                   <td>
                       <input type="text" id="SearchTitle" name="SearchTitle" value="<?php echo $CastingCompany; ?>" />
                   </td>
               </tr>
               <tr valign="top">
                   <th scope="row">Profiles:</th>
                   <td>

						<?php
                                   
						$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY ProfileContactNameFirst ASC";

						$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));

						$count = mysql_num_rows($results);

						

						while ($data = mysql_fetch_array($results)) {

							echo " <div style=\"float: left; width: 80px; height: 100px; margin-right: 5px; overflow: hidden; \">". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "<br /><a href=\"". bb_agency_PROFILEDIR . $data['ProfileGallery'] ."/\" target=\"_blank\"><img style=\"width: 80px; \" src=\"". bb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></a></div>\n";

						}

						?>
                   	<input type="hidden" name="SearchProfileID" value="<?php echo $cartString; ?>" />
                   </td>
               </tr>
               </tbody>
           </table>
           <p class="submit">
           	Click Save to get the email code<br />
               <input type="hidden" name="action" value="addRecord" />
               <input type="submit" name="Submit" value="Save Search" class="button-primary" />
           </p>
           </form>

			<hr />

			<?php
       } else {

			echo "Session expired. Please search again.";

		}
   } // End Serach Save

} // End All	?>
  <div style="clear:both"></div>
		<h3 class="title">Recently Saved Searches</h3>

		<?php 
		  $bb_options = bb_agency_get_option();

			$bb_agency_option_locationtimezone 		= (int)bb_agency_get_option('bb_agency_option_locationtimezone');

		// Sort By

		$sort = "";

		if (isset($_GET['sort']) && !empty($_GET['sort'])){

			$sort = $_GET['sort'];

		}

		else {

			$sort = "search.SearchDate";

		}

		// Sort Order

		$dir = "";

		if (isset($_GET['dir']) && !empty($_GET['dir'])){

			$dir = $_GET['dir'];

			if ($dir == "desc" || !isset($dir) || empty($dir)){

			   $sortDirection = "desc";

			   } else {

			   $sortDirection = "desc";

			} 

		} else {

			   $sortDirection = "desc";

			   $dir = "desc";

		}

		// Filter

		$filter = "WHERE search.SearchID > 0 ";

		if (isset($_GET['SearchTitle']) && !empty($_GET['SearchTitle'])){

			$selectedTitle = $_GET['SearchTitle'];

			$query .= "&SearchTitle=". $selectedTitle ."";

			$filter .= " AND search.SearchTitle='". $selectedTitle ."'";

		}

		//Paginate

		$items = mysql_num_rows(mysql_query("SELECT * FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = search.SearchID ". $filter  ."")); // number of total rows in the database

		if ($items > 0) {

			$p = new bb_agency_pagination;

			$p->items($items);

			$p->limit(50); // Limit entries per page

			$p->target("admin.php?page=". $_GET['page'] .$query);

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

			$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

		} else {

			$limit = "";

		}

		?>

		<div class="tablenav">

			<div class='tablenav-pages'>

				<?php

				if ($items > 0) {

					echo $p->show();  // Echo out the list of paging. 

				}

				?>

			</div>

		</div>

		<table cellspacing="0" class="widefat fixed">

			<thead>

				<tr>

					<td style="width: 360px;" nowrap="nowrap">                   

						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">

						 <input type='hidden' name='page_index' id='page_index' value='<?php echo $_GET['page_index']; ?>' />  

						 <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />

						 <input type="hidden" name="type" value="name" />

						 Search by : 

						 Title: <input type="text" name="SearchTitle" value="<?php echo $SearchTitle; ?>" style="width: 100px;" />

							<input type="submit" value="Filter" class="button-primary" />

						</form>

					</td>

					<td style="width: 300px;" nowrap="nowrap">

						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">

						 <input type='hidden' name='page_index' id='page_index' value='<?php echo $_GET['page_index']; ?>' />  

						 <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />

						 <input type="submit" value="Clear Filters" class="button-secondary" />

						</form>

					</td>

					<td>&nbsp;</td>

				</tr>

		</thead>

		</table>

		<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">	

		<table cellspacing="0" class="widefat fixed">

		<thead>

			<tr class="thead">

				<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>

				<th class="column" scope="col" style="width:50px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchID&dir=<?php echo $sortDirection; ?>">ID</a></th>

				<th class="column" scope="col" style="width:200px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchTitle&dir=<?php echo $sortDirection; ?>">Title</a></th>

				<th class="column" scope="col" style="width:80px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchDate&dir=<?php echo $sortDirection; ?>">Profiles</a></th>

				<th class="column" scope="col">History (Sent/To/Link)</th>

			</tr>

		</thead>

		<tfoot>

			<tr class="thead">

				<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>

				<th class="column" scope="col">ID</th>

				<th class="column" scope="col">Title</th>

				<th class="column" scope="col">Profiles</th>

				<th class="column" scope="col">History</th>

			</tr>

		</tfoot>

		<tbody>

		<?php

		$query2 = "SELECT search.SearchID, search.SearchTitle, search.SearchProfileID, search.SearchDate FROM ". table_agency_searchsaved ." search ". $filter  ." ORDER BY $sort $dir $limit";

		//$query2 = "SELECT search.SearchID, search.SearchTitle, search.SearchProfileID, search.SearchOptions, search.SearchDate FROM ". table_agency_searchsaved_mux ." searchsent LEFT JOIN ". table_agency_searchsaved ." search ON searchsent.SearchID = search.SearchID ". $filter  ." ORDER BY $sort $dir $limit";

		$results2 = mysql_query($query2);

		$count2 = mysql_num_rows($results2);

		while ($data2 = mysql_fetch_array($results2)) {
			$SearchID = $data2['SearchID'];

			$SearchTitle = stripslashes($data2['SearchTitle']);

			$SearchProfileID = stripslashes($data2['SearchProfileID']);

			$SearchDate = stripslashes($data2['SearchDate']);
			$query3 = "SELECT SearchID,SearchMuxHash, SearchMuxToName, SearchMuxToEmail, SearchMuxSent FROM ". table_agency_searchsaved_mux ." WHERE SearchID = ". $SearchID;

			$results3 = mysql_query($query3);

			$count3 = mysql_num_rows($results3);

		?>

		<tr<?php echo $rowColor; ?>>

			<th class="check-column" scope="row">
                 

				<input type="checkbox" value="<?php echo $SearchID; ?>" class="administrator" id="<?php echo $SearchID; ?>" name="<?php echo $SearchID; ?>"/>

			</th>

			<td>

				<?php echo $SearchID; ?>

			</td>

			<td>

				<?php echo $SearchTitle; ?>

				<div class="row-actions">
                       <?php

				  if ($count3<=0){

				?>

					<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=emailCompose&SearchID=<?php echo $SearchID."&SearchMuxHash=".bb_agency_random(8); ?>">Create Email</a> | </span>

				<?php

				  }else{

				?>
                       	<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=emailCompose&SearchID=<?php echo $SearchID; ?>">Create Email</a> | </span>

				
                       <?php } ?>
                       	<span class="delete"><a class='submitdelete' title='Delete this Record' href='<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=deleteRecord&amp;SearchID=<?php echo $SearchID; ?>' onclick="if ( confirm('You are about to delete this record\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>

				</div>

			</td>

			<td>

				<?php  // echo $SearchProfileID; ?>

			</td>

			<td>

				<?php

				$pos = 0;

				while ($data3 = mysql_fetch_array($results3)) {

				$pos++;

				     if ($pos == 1){

						echo "Link: <a href=\"". get_bloginfo("url") ."/client-view/". $data3["SearchMuxHash"] ."/\" target=\"_blank\">". get_bloginfo("url") ."/client-view/". $data3["SearchMuxHash"] ."/</a><br />\n";

					} 

					echo "(". bb_agency_makeago(bb_agency_convertdatetime( $data3["SearchMuxSent"]), $bb_agency_option_locationtimezone) .") ";

					echo "<strong>". $data3["SearchMuxToName"]."&lt;".$data3["SearchMuxToEmail"]."&gt;"."</strong> ";

					echo "<br/>";

				}

				//mysql_free_result($results2);

				if ($count3 < 1) {

					echo "Not emailed yet\n";	

				}

				?>

			</td>

		</tr>

		<?php

		}

			mysql_free_result($results2);

			if ($count2 < 1) {

				if (isset($filter)) { 

		?>

		<tr>

			<th class="check-column" scope="row"></th>

			<td class="name column-name" colspan="3">

				<p>No profiles found with this criteria.</p>

			</td>

		</tr>

		<?php

				} else {

		?>

		<tr>

			<th class="check-column" scope="row"></th>

			<td class="name column-name" colspan="3">

				<p>There aren't any Profiles loaded yet!</p>

			</td>

		</tr>

		<?php

					

				}

		?>

		<?php } ?>

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

		<input type="submit" value="<?php echo __('Delete','bb_agency_profiles'); ?>" class="button-primary" name="submit" />		

	</p>
	</form>