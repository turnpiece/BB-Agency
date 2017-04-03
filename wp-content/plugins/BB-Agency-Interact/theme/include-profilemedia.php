<?php if (defined('bb_agencyinteract_ALLOW_UPLOADS') && bb_agencyinteract_ALLOW_UPLOADS) :

	global $wpdb;

	$ProfileID					= $profile->ProfileID;
	$ProfileGallery				= stripslashes($profile->ProfileGallery);

    echo "<form id=\"deletePost\"  name=\"deletePost\" action=\"". get_bloginfo("wpurl") ."/profile-member/media/\" method=\"post\">";
	echo " <input type=\"hidden\" name=\"ProfileID\" value=\"".$ProfileID."\" />";
	echo " <input type=\"hidden\" name=\"targetid\" id=\"targetid\" value=\"\" />";
	echo " <input type=\"hidden\" name=\"actionsub\" value=\"photodelete\" />";
	echo "</form>";
                  
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/profile-member/media/\">\n";

	if ( !empty($ProfileID) && ($ProfileID > 0) ) { // Editing Record
		echo "	<div class=\"manage-section gallery\">\n";

		if (!empty($UploadMedia))
		{
			echo "<div id=\"message\" class=\"uploaded\">";
			foreach ($UploadMedia as $mediaFile) {
				echo "<p>File <strong>".$mediaFile. "</strong> successfully uploaded!</p>";
			}
			echo "<p>You may continue uploading more files. If you are done, please click the EXIT link below to go back to homepage.</p>";
			
			if (count($UploadMedia) > 1)
				echo "<p>Your photos will need to be reviewed by an administrator before they appear on the site.</p>";
			else
				echo "<p>Your photo will need to be reviewed by an administrator before it appears on the site.</p>";

			$back = $bb_agencyinteract_WPURL ."/profile-member/";
			echo '<p><a class="bb_button" href='.$back.'>EXIT</a></p>';
			echo "</div>";

			// send message to admin
			$subject = 'New media uploaded to '.get_bloginfo('name').' by '.$profile->ProfileContactDisplay;
			$body = implode("\n", $UploadMedia);
			$body .= "\n\n" . admin_url( 'admin.php?page=bb_agency_profiles&action=editRecord&ProfileID='.$ProfileID );

			wp_mail( get_bloginfo('admin_email'), $subject, $body );
		}
		
		echo "	<h3>". __("Gallery", bb_agencyinteract_TEXTDOMAIN) ."</h3>\n";
			
		echo "<script type=\"text/javascript\">\n";
		echo "function confirmDelete(delMedia,mediaType) {\n";
		echo "  if (confirm(\"".__("Are you sure you want to delete this", bb_agencyinteract_TEXTDOMAIN) ." \"+mediaType+\"?\")) {\n";
		//echo "         document.getElementById('deletePost').submit();   \n";
		echo "         document.getElementById('targetid').value=delMedia;";
		echo "         document.deletePost.submit();   \n";
		//echo "	document.location = \"?&action=editRecord&ProfileID=". $ProfileID ."&actionsub=photodelete&targetid=\"+delMedia;\n";
		echo "  }\n";
		echo "}\n";
		echo "</script>\n";
		
		// Are we deleting?
		if ($_POST["actionsub"] == "photodelete") {
			$deleteTargetID = $_POST["targetid"];
			
			// Verify Record
			$queryImgConfirm = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaID =  \"". $deleteTargetID ."\"";
			$resultsImgConfirm = $wpdb->get_results($queryImgConfirm);
			$countImgConfirm = count($resultsImgConfirm);

			foreach ($resultsImgConfirm as $profileImgConfirm) {
				$ProfileMediaID 	= $profileImgConfirm->ProfileMediaID;
				$ProfileMediaType 	= $profileImgConfirm->ProfileMediaType;
				$ProfileMediaURL 	= $profileImgConfirm->ProfileMediaURL;
				
				if ($ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Video Monologue" || $ProfileMediaType == "Video Slate") {
						  echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> ". __("successfully removed", bb_agencyinteract_TEXTDOMAIN) .".</p></div>");
				} else {
					// Remove File
					$dirURL = bb_agency_UPLOADPATH . $ProfileGallery; 
					if (!unlink($dirURL ."/". $ProfileMediaURL)) {
					  echo ("<div id=\"message\" class=\"error\"><p>". __("Error removing", bb_agencyinteract_TEXTDOMAIN) ." <strong>". $ProfileMediaURL ."</strong>. ". __("Please try again", bb_agencyinteract_TEXTDOMAIN) .".</p></div>");
					} else {
					  echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> ". __("successfully removed", bb_agencyinteract_TEXTDOMAIN) .".</p></div>");
					}
				}
				// Remove Record
				$wpdb->delete(table_agency_profile_media, array( 'ProfileID' => $ProfileID, 'ProfileMediaID' => $ProfileMediaID));
			} // is there record?
		}
	}
	// Go about our biz-nazz
	$queryImg = "SELECT * FROM ". table_agency_profile_media ." WHERE `ProfileID` = $ProfileID AND `ProfileMediaType` = 'Image' ORDER BY `ProfileMediaPrimary` DESC, `ProfileMediaLive` DESC, `ProfileMediaID` DESC";
	$resultsImg = $wpdb->get_results($queryImg);

	if (count($resultsImg) > 0) {
		foreach ($resultsImg as $profileImg) {

			$class = array( 'profileimage' );
			$class[] = $profileImg->ProfileMediaPrimary ? ' primary-picture' : '';
			$class[] = $profileImg->ProfileMediaLive ? ' live' : '';
			?>
			<div class="<?php echo implode(' ', $class) ?>">
				<input type="hidden" name="pgallery" value="<?php echo $ProfileGallery ?>">			
				<input type="hidden" name="pmedia_url" value="<?php echo $profileImg->ProfileMediaURL ?>">					
				<img src="<?php echo bb_agency_UPLOADDIR . $ProfileGallery ."/". $profileImg->ProfileMediaURL ?>" />
				<?php if ($profileImg->ProfileMediaLive || $profileImg->ProfileMediaPrimary) : ?>
				<div class="bb_button action">
					<label>
						<input type="radio" name="ProfileMediaPrimary" data-profile="<?php echo $ProfileID ?>" value="<?php echo $profileImg->ProfileMediaID ?>" class="button-primary" <?php checked( $profileImg->ProfileMediaPrimary ) ?> /> &nbsp; <?php _e('Primary', bb_agencyinteract_TEXTDOMAIN) ?>
					</label>
				</div>
				<?php else : ?>
				<div class="delete action">
					<a href="#" class="btn-small-red" data-id="<?php echo $profileImg->ProfileMediaID ?>" data-type="<?php echo $profileImg->ProfileMediaType ?>" onclick="confirmDelete(<?php echo $profileImg->ProfileMediaID . ', \'' . trim($profileImg->ProfileMediaType) . '\'' ?>); return false;">
						<span><?php _e('Delete', bb_agencyinteract_TEXTDOMAIN) ?></span> &raquo;
					</a>
				</div>
				<?php endif; ?>
			</div>
			<?php
		}
	} else {
		echo "<p>". __("There are no images loaded for this profile yet.", bb_agencyinteract_TEXTDOMAIN) ."</p>\n";
	}
			
	echo "		</div>\n";

	$queryMedia = "SELECT * FROM ". table_agency_profile_media ." WHERE `ProfileID` = %d AND `ProfileMediaType` <> 'Image' AND `ProfileMediaType` <> 'Private'";

	$media = $wpdb->get_results( $wpdb->prepare( $queryMedia, $ProfileID ) );

	if (!empty($media)) : ?>
	<div class="manage-section media">
		<h3><?php _e("Media", bb_agencyinteract_TEXTDOMAIN) ?></h3>
		<p><?php _e("The following files (pdf, audio file, etc.) are associated with this record", bb_agencyinteract_TEXTDOMAIN) ?></p>
	
	<?php foreach ( $media as $item ) :

			if ($item->ProfileMediaType == "Demo Reel" || 
				$item->ProfileMediaType == "Video Monologue" || 
				$item->ProfileMediaType == "Video Slate") {
				$outVideoMedia .= "<div class=\"media-video\">". $item->ProfileMediaType ."<br />". bb_agency_get_videothumbnail($item->ProfileMediaURL) ."<br /><a href=\"http://www.youtube.com/watch?v=". $item->ProfileMediaURL ."\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			} elseif ($item->ProfileMediaType == "Voice Demo") {
				$outLinkVoiceDemo .= "<div>". $item->ProfileMediaType .": <a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $item->ProfileMediaURL ."\" target=\"_blank\">". $item->ProfileMediaTitle ."</a> [<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			}
			 elseif ($item->ProfileMediaType == "Resume") {
				$outLinkResume .= "<div>". $item->ProfileMediaType .": <a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $item->ProfileMediaURL ."\" target=\"_blank\">". $item->ProfileMediaTitle ."</a> [<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			}
			 elseif ($item->ProfileMediaType == "Headshot") {
				$outLinkHeadShot .= "<div>". $item->ProfileMediaType .": <a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $item->ProfileMediaURL ."\" target=\"_blank\">". $item->ProfileMediaTitle ."</a> [<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			}elseif ($item->ProfileMediaType == "CompCard") {
				$outLinkComCard .= "<div>". $item->ProfileMediaType .": <a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $item->ProfileMediaURL ."\" target=\"_blank\">". $item->ProfileMediaTitle ."</a> [<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			}else{
				$outCustomMediaLink .= "<div>". $item->ProfileMediaType .": <a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $item->ProfileMediaURL ."\" target=\"_blank\">". $item->ProfileMediaTitle ."</a> [<a href=\"javascript:confirmDelete('". $item->ProfileMediaID ."','".$item->ProfileMediaType."')\">DELETE</a>]</div>\n";
			}
		endforeach; ?>
		<ul>
		<?php foreach (array('outLinkVoiceDemo', 'outLinkResume', 'outLinkHeadShot', 'outLinkComCard', 'outCustomMediaLink', 'outVideoMedia') as $type ) :

			if (${$type})
				echo '<li>'.${$type}.'</li>';
		
		endforeach; ?>
		</ul>

	<?php endif;
	/*
	if ($countMedia < 1) {
		echo "<p><em>". __("There are no additional media linked", bb_agencyinteract_TEXTDOMAIN) ."</em></p>\n";
	}
	*/
	echo "		</div>\n";

	if (defined('bb_agencyinteract_ALLOW_UPLOADS') && bb_agencyinteract_ALLOW_UPLOADS) :

		echo "	<div class=\"manage-section upload\">\n";
		echo "		<h3>". __("Upload Media Files", bb_agencyinteract_TEXTDOMAIN) ."</h3>\n";
		echo "		<p>". __("Upload new media using the forms below. The following formats are available: jpg, png, mp3, and pdf. If uploading an mp3 for a voice monolouge, use the  \"Voice Demo\" option. For Resumes, make sure the file is a PDF ", bb_agencyinteract_TEXTDOMAIN) .".</p>\n";
	
		for( $i=1; $i<10; $i++ ) {
			echo "<div><label>Type: </label><select name=\"profileMedia". $i ."Type\"><option value=\"Image\">Image</option><option value=\"Headshot\">Headshot</option><option value=\"CompCard\">Comp Card</option><option>Resume</option><option>Voice Demo</option>"; 
			bb_agency_getMediaCategories($profile->ProfileGender);
			echo "</select><input type='file' id='profileMedia". $i ."' name='profileMedia". $i ."' /></div>\n";
		}

		echo "		<p>". __("Paste the video URL below", bb_agencyinteract_TEXTDOMAIN) .".</p>\n";
	
		echo "<div><label>Type: </label><select name=\"profileMediaV1Type\"><option selected>". __("Video Slate", bb_agencyinteract_TEXTDOMAIN) ."</option><option>". __("Video Monologue", bb_agencyinteract_TEXTDOMAIN) ."</option><option>". __("Demo Reel", bb_agencyinteract_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV1' name='profileMediaV1'></textarea></div>\n";
		echo "<div><label>Type: </label><select name=\"profileMediaV2Type\"><option>". __("Video Slate", bb_agencyinteract_TEXTDOMAIN) ."</option><option selected>". __("Video Monologue", bb_agencyinteract_TEXTDOMAIN) ."</option><option>". __("Demo Reel", bb_agencyinteract_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV2' name='profileMediaV2'></textarea></div>\n";
		echo "<div><label>Type: </label><select name=\"profileMediaV3Type\"><option>". __("Video Slate", bb_agencyinteract_TEXTDOMAIN) ."</option><option>". __("Video Monologue", bb_agencyinteract_TEXTDOMAIN) ."</option><option selected>". __("Demo Reel", bb_agencyinteract_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV3' name='profileMediaV3'></textarea></div>\n";
		
		echo "<p><strong>Press the \"Save and Continue\" button only once</strong>. Depending on the number of files and or your connection speed, it may take a few moments to fully upload your new files/changes. When the page refreshes, you should see your new media.</p>\n";
		echo "		</div>\n";
		echo "<p class=\"submit\">\n";
		echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"". $ProfileID ."\" />\n";
		echo "     <input type=\"hidden\" name=\"ProfileGallery\" value=\"". $ProfileGallery ."\" />\n";
		echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", bb_agencyinteract_TEXTDOMAIN) ."\" class=\"button-primary\" onClick=\"this.value = 'Please Wait...'\"/>\n";
		echo "</p>\n";
	endif;

	echo "</form>\n";

else: ?>
	<p><?php printf( __("Please ensure that your profile is kept up to date with recent pictures by emailing them to <a href=\"%s\">%s</a>", bb_agencyinteract_TEXTDOMAIN), $bb_agencyinteract_EMAIL_PHOTOS, $bb_agencyinteract_EMAIL_PHOTOS ) ?></p>

	<p><?php printf( __("Photos need to be clear and against a plain background, with no hats/sunglasses on. If you would like professional pictures done, please contact <a href=\"%s\">%s</a> for information on our next studio day.", bb_agencyinteract_TEXTDOMAIN), $bb_agencyinteract_EMAIL, $bb_agencyinteract_EMAIL) ?></p>

<?php endif;