<?php

/*

Profile View with Scrolling Thumbnails and Primary Image

*/

	# lightbox.	

  	/*//echo "<script type='text/javascript' src='".bb_agency_BASEDIR."js/slimbox2.js'></script>";

	//echo '<link rel="stylesheet" href="'.bb_agency_BASEDIR.'style/slimbox2.css" type="text/css" media="screen" />';*/

	

	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-zero\" class=\"rblayout\">\n";
	echo "	<div id=\"photos\" class=\"col_6 column\">\n";
	echo "	  <div class=\"inner\">\n";

			// images
			global $wpdb;
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE `ProfileID` =  \"". $ProfileID ."\" AND `ProfileMediaType` = \"Image\" ORDER BY $orderBy";
			$resultsImg = $wpdb->get_results($queryImg);
			$countImg = count($resultsImg);
			$path = bb_agency_UPLOADPATH . $ProfileGallery .'/';
			foreach ($resultsImg as $dataImg) : ?>
			<div class="photo">
				<a href="<?php echo bb_agency_UPLOADDIR . $ProfileGallery .'/'. $dataImg->ProfileMediaURL ?>" rel="lightbox" title="<?php $ProfileContactDisplay ?>">
					<img src="<?php echo bb_agency_BASEDIR.'/tasks/timthumb.php?src=' . $path . $dataImg->ProfileMediaURL . '&h=139' ?>" alt="<?php echo $ProfileContactDisplay ?>" />
				</a>
			</div>
			<?php endforeach;

	echo "	  <div class=\"cb\"></div>\n";
	echo "	  </div>\n";
	echo "	</div>\n"; // close #photos

	

	echo "	  <div id=\"stats\" class=\"col_3 column\">\n";



	echo "	  <h2>". $ProfileContactDisplay;

	if (bb_agency_isfamily($ProfileType)) {

		echo __(' and family', bb_agency_TEXTDOMAIN);

	}	

	echo "</h2>\n";



	echo "	  <ul>\n";

	/*

	if (!empty($ProfileGender)) {

		$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");

		$fetchGenderData = mysql_fetch_assoc($queryGenderResult);

		echo "<li><strong>". __("Gender", bb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], bb_agency_TEXTDOMAIN). "</li>\n";

	}

	*/

	if (!empty($ProfileStatHeight)) {

		if ($bb_agency_option_unittype == 0) { // Metric

			echo "<li><strong>". __("Height", bb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", bb_agency_TEXTDOMAIN). "" ."</li>\n";

		} else { // Imperial

			$heightraw = $ProfileStatHeight;

			$heightfeet = floor($heightraw/12);

			$heightinch = $heightraw - floor($heightfeet*12);

			echo "<li><strong>". __("Height", bb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", bb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", bb_agency_TEXTDOMAIN). "" ."</li>\n";

		}

	}

	if (bb_agency_SITETYPE == 'bumps') :

	if (bb_agency_ismumtobe($ProfileType) && !empty($ProfileDateDue)) : // if pregnant display due date ?>
	<li><strong><?php _e("Due date", bb_agency_TEXTDOMAIN) ?><span class="divider">:</span></strong> <?php echo bb_agency_displaydate($ProfileDateDue) ?></li>

	<?php elseif (bb_agency_isbaby($ProfileType) && !empty($ProfileDateBirth)) : // if a family display the baby's date of birth ?>
	<li><strong><?php echo (bb_agency_isfamily($ProfileType) ? __("Baby's date of birth", bb_agency_TEXTDOMAIN) : __("Date of birth", bb_agency_TEXTDOMAIN)) ?><span class="divider">:</span></strong> <?php echo bb_agency_displaydate($ProfileDateBirth) ?></li>
	<?php endif;

	endif;

	// Insert Custom Fields
	bb_agency_getProfileCustomFields($ProfileID, $ProfileGender);

	if($bb_agency_option_showcontactpage==1){

		echo "<li class=\"rel\"><strong>". __("Contact: ", bb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";

	}

	echo "	  </ul>\n"; // Close ul
	echo "	  </div>\n"; // Close Stats	
	echo "		<div id=\"links\" class=\"col_3 column\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";

					 // Social Link
					 bb_agency_getSocialLinks();

	echo "			<ul>\n";
	echo	'<div class="profile-actions-favorited">';		

			$cl1 = ""; $cl2=""; $tl1="Add to Favorites"; $tl2="Add to Casting Cart";

			if(is_permitted("casting")){

					$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID

													 ."'  AND CastingCartProfileID = '".bb_agency_get_current_userid()."'" ) or die("error");

					$count_castingcart = mysql_num_rows($query_castingcart);



					if($count_castingcart>0){ $cl2 = "cart_bg"; $tl2="Remove from Casting Cart"; }

					

					echo '<li><a title="'.$tl2.'" href="javascript:;" class="save_cart '.$cl2.' bb_button" id="'.$ProfileID.'">'.$tl2.'</a></li>';

			}

			
			
			if(is_permitted("favorite")){


					$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID

												  ."'  AND SavedFavoriteProfileID = '".bb_agency_get_current_userid()."'" ) or die("error");

					$count_favorite = mysql_num_rows($query_favorite);

					$datas_favorite = mysql_fetch_assoc($query_favorite);				



					if($count_favorite>0){ $cl1 = "fav_bg"; $tl1="Remove from Favorites"; }

					

					echo '<li class=\"favorite\"><a title="'.$tl1.'" href="javascript:;" id="mycart" class="save_fav '.$cl1.' bb_button">'.$tl1.'</a></li>';

			}

	

	echo '</div>';



						echo '<div id="resultsGoHereAddtoCart"></div>';

						?>

        

        <div id="view_casting_cart" style="<?php if($tl2=="Add to Casting Cart"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="casting"><a class="bb_button" href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", bb_agency_TEXTDOMAIN);?></a></li></div>

    

        

        <div id="view_favorite" style="<?php if($tl1=="Add to Favorites"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="favorite"><a class="bb_button" href="<?php echo get_bloginfo('url')?>/profile-favorite/"><?php echo __("View favorite", bb_agency_TEXTDOMAIN);?></a></li></div>

    <?php



				// Resume

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "<li class=\"item resume\"><a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"bb_button\">Download Resume</a></li>\n";

				  }

				}

			

				// Comp Card

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "<li class=\"item compcard\"><a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"bb_button\">Download Comp Card</a></li>\n";

				  }

				}

				// Headshots

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "<li class=\"item headshot\"><a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"bb_button\">Download Headshot</a></li>\n";

				  }

				}

				

				//Voice Demo

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "<li class=\"item voice\"><a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"bb_button\">Listen to Voice Demo</a></li>\n";

				  }

				}



				//Video Slate

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];

				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"bb_button\">Watch Video Slate</a></li>\n";

				  }

				}



				//Video Monologue

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"bb_button\">Watch Video Monologue</a></li>\n";

				  }

				}



				//Demo Reel

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {

				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"bb_button\">Watch Demo Reel</a></li>\n";

				  }

				}



				// Other Media Type not the 

				// default ones

				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 

				                             AND ProfileMediaType NOT IN ('Image','Resume','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel','Private')

											 ");

				$countMedia = mysql_num_rows($resultsMedia);

				if ($countMedia > 0) {

				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {

                        echo "<li class=\"item video demoreel\"><a href=\"". bb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"bb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";

				  	}

				}

                                

				// Is Logged?

				if (is_user_logged_in()) { 

			

					if(bb_agency_get_option('bb_agency_option_profilelist_castingcart')==1){

			 			if(checkCart(bb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already	?>

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

						 

							

						} else {

				  			# removed as required

							#echo "<li class=\"add to cart\">". __("", bb_agency_TEXTDOMAIN);						  

						  	#echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"bb_button\">". __("View Casting Cart", bb_agency_TEXTDOMAIN)."</a></li>\n";

							

			          	}

					}	//end if(checkCart(bb_agency_get_current_userid()

					# removed as required.

					#echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\" class=\"bb_button\">". __("Access Dashboard", bb_agency_TEXTDOMAIN). "</a></li>\n";

				}

				



	echo "			</ul>\n";

	echo "		</div>\n";  // Close Links

	

	echo "	  <div id=\"experience\" class=\"col_12 column\">\n";

	echo			$ProfileExperience;

	echo "	  </div>\n"; // Close Experience

/*

			if (isset($profileVideoEmbed)) {

	echo "		<div id=\"movie\" class=\"six column\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";

			}

*/	

	

	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All

	echo "  </div>\n";  // Close Profile Zero

	echo "<div style=\"clear: both;\"></div>\n"; // Clear All

	echo "</div>\n";  // Close Profile

?>

<script type="text/javascript">



jQuery(document).ready(function(){



	jQuery(".save_fav").click(function(){



		ajax_submit(jQuery(this),"favorite");



	});



	jQuery(".save_cart").click(function(){

		ajax_submit(jQuery(this),"casting");

		

	});	



    function ajax_submit(Obj,type){

                

				if(type == "favorite"){

					

					var action_function = "bb_agency_save_favorite";

						

				} else if(type == "casting"){

				

					var action_function = "bb_agency_save_castingcart";

					

				

				}

				

				jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',

		

							 data: {action: action_function,  'talentID': <?php echo $ProfileID ?>},

		

						  success: function(results) {  

		

								if(results=='error'){ 

									alert("Error in query. Try again"); 

								}else if(results==-1){ 

									alert("You're not signed in");

								} else { 

		

									  if(type == "favorite"){

							             

										 if(Obj.hasClass('fav_bg')){

	 										 Obj.removeClass('fav_bg');

											 Obj.attr('title','Add to Favorites'); 

											 jQuery(Obj).html("Add to Favorites");	

											document.getElementById('view_favorite').style.display="none";

										 } else {

	 										 Obj.addClass('fav_bg');

											 Obj.attr('title','Remove from Favorites'); 

											 jQuery(Obj).html("Remove from Favorites");	

											 document.getElementById('view_favorite').style.display="block";



										 }

							  

									 } else if(type == "casting") {

										 

										 if(Obj.hasClass('cart_bg')){
											

	 										 Obj.removeClass('cart_bg');

											 Obj.attr('title','Add to Casting Cart'); 

											 jQuery(Obj).html("Add to Casting Cart");	
											 document.getElementById('view_casting_cart').style.display="none";

										 } else {
											
										 	Obj.addClass('cart_bg');

										 	Obj.attr('title','Remove from Casting Cart');

											jQuery(Obj).html("Remove from Casting Cart");

											document.getElementById('view_casting_cart').style.display="block";

										 }

									

									 }

		

									

								}

							}

			   }); // ajax submit

	 } // end function

});

</script>