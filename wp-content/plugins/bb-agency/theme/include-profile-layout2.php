<?php
/*
Profile View with Sliding Thumbnails and Primary Image
*/
echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-two\" class=\"rblayout\">\n";

echo "  		<div class=\"col_7 column\">\n";
echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
							// Image Slider
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							while ($dataImg = mysql_fetch_array($resultsImg)) {
						    	if ($countImg > 1) { 
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
							  	} else {
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
							  	}
							}
echo "					</div><!-- #photo-scroller -->"; //
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"cb\"></div>\n";

echo "				<div id=\"info\">\n";
echo "	  				<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";

						// Social Link
						rb_agency_getSocialLinks();
 
echo "	  				<div class=\"col_6 column\">\n";
echo "	  					<div id=\"stats\">\n";
	echo "	  					<ul>\n";

									if (!empty($ProfileGender)) {
										$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
										$count = mysql_num_rows($queryGenderResult);
										if($count > 0){
											$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
											echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
										}
									}

									// Insert Custom Fields
									rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);    
echo "	  						</ul>\n";
echo "	  					</div>\n"; // #stats
echo "	  				</div>\n"; // .col_6
// add to fave script
?><script type="text/javascript">
			function addtofv(ids){
					jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',
									 data: {action: 'rb_agency_save_favorite',  talentID: ids},
								  success: function(results) {  
										if(results=='error'){ 
											alert("Error in query. Try again"); 
										}else if(results==-1){ 
											alert("You're not signed in");
										} else { 
												 if(jQuery("#save_fav_li").text() == 'Add to Favorites'){
													jQuery("#save_fav_li").text('<?php echo __("Remove from Favorites", rb_agency_TEXTDOMAIN); ?>');
												 } else {
													jQuery("#save_fav_li").text('<?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN); ?>');
											    }
										}
									}
					   }); // ajax submit
			}
</script><?php
echo "					<div class=\"col_6 column\">\n";
echo "						<div id=\"links\">\n";
echo "							<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
echo "							<ul>\n";

									// Resume
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Print Resume</a></li>\n";
									  	}
									}		
									// Comp Card
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Compcard\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Comp Card</a></li>\n";
									  	}
									}
									// Headshots
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Headshot</a></li>\n";
									  	}
									}			
									//Voice Demo
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Listen to Voice Demo</a></li>\n";
									  	}
									}
									//Video Slate
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
											echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." class=\"rb_button\">Watch Video Slate</a></li>\n";
									  	}
									}
									//Video Monologue
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." class=\"rb_button\">Watch Video Monologue</a></li>\n";
									  	}
									}
									//Demo Reel
									$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
									$countMedia = mysql_num_rows($resultsMedia);
									if ($countMedia > 0) {
									  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
											echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." class=\"rb_button\">Watch Demo Reel</a></li>\n";
									  	}
									}
									//Contact Profile
									if($bb_agency_option_showcontactpage==1){
							    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
									}
						            // Other links - Favorite, Casting cart...
								    // does not need this anymore
									//rb_agency_get_miscellaneousLinks($ProfileID);
									
									// Is Logged?
									if (is_user_logged_in()) { 

										if(!is_permitted('casting')){
											if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already
											?>
												<script>
										            function addtoCart(pid){
														var qString = 'usage=addtocart&pid=' +pid;
													
												     	$.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/bb-agency/theme/sub_db_handler.php', qString, processResponseAddtoCart);
										             	// alert(qString);
													}				 
													function processResponseAddtoCart(data) {
														document.getElementById('resultsGoHereAddtoCart').style.display="block";
														document.getElementById('view_casting_cart').style.display="block";
														document.getElementById('resultsGoHereAddtoCart').textContent=data;
														setTimeout('document.getElementById(\'resultsGoHereAddtoCart\').style.display="none";',3000); 
														//setTimeout('document.getElementById(\'view_casting_cart\').style.display="none";',3000);
														setTimeout('document.getElementById(\'casting_cart_li\').style.display="none";',3000);					
													}
													
									            </script>
									            <?php
												echo "<li id=\"casting_cart_li\" class=\"item cart\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Add to Casting Cart", rb_agency_TEXTDOMAIN). "</a></li>\n";
											} else {
												echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
												echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"rb_button\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";						
										    }
										}	//end if(checkCart(rb_agency_get_current_userid()
																				
										// add save to favorites
										$bb_agency_option_profilelist_favorite	= isset($bb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$bb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;

										if(!is_permitted('favorite')){
												$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
			                             						 ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
												
												$count_favorite = mysql_num_rows($query_favorite);				 
												
												if($count_favorite>0){
													echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
													echo " <a id='save_fav_li' onclick=\"javascript:addtofv('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Remove from Favorites", rb_agency_TEXTDOMAIN)."</a></li>\n";						
												} else {
													echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
													echo " <a id='save_fav_li' onclick=\"javascript:addtofv('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Add to Favorites", rb_agency_TEXTDOMAIN)."</a></li>\n";						
												}				
										}

									}
echo "								<li id=\"resultsGoHereAddtoCart\"></li>";
echo "								<li id=\"view_casting_cart\" style=\"display:none;\"><a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"rb_button\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>";
echo "							</ul>\n";	
echo "						</div>\n";// #links
echo "					</div>\n";// .col_6 ?>


<?php
//Experience
echo "		  			<div id=\"experience\" class=\"col_12 column\">\n";
echo						$ProfileExperience;
echo "		  			</div>\n";
echo "					<div class=\"cb\"></div>\n"; // Clear All					
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";

echo "  		<div class=\"col_5 column\">\n";
echo "				<div id=\"profile-picture\">\n";

		                // images
		                $queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
		                $resultsImg = mysql_query($queryImg);
		                $countImg = mysql_num_rows($resultsImg);
		                while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
		                }

echo "				</div> <!-- #profile-picture -->\n";
echo "			</div>\n"; // .col_5
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"cb;\"></div>\n"; // Clear All
?>