<?php

global $wpdb;

		echo " <div class=\"profile-manage-menu\">\n";
		echo "   <div id=\"subMenuTab\">\n";
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-left tab-". $tabclass ."\">\n";
		echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("Overview", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/account/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-member/account/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Account", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";
		if ($profiletype == 1) {
			// Agents

						if ( ($_SERVER["REQUEST_URI"]) == "/profile-favorite/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
			echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
			echo " 			<a href=\"\">\n";
			echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("Favorites", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
			echo " 			</a>\n";
			echo " 		</div>\n";
		} else {
			//Models Talent
						if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/manage/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
			echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
			echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-member/manage/\">\n";
			echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Details", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
			echo " 			</a>\n";
			echo " 		</div>\n";
			/*
			 * Set Media to not show to
			 * client/s, agents, producers,
			 */
			$ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
		        $ptype = retrieve_title($ptype);
			$restrict = array('client','clients','agents','producers');
			if(in_array(strtolower($ptype),$restrict)){
				$d = 'display:none;';
			} else {
				$d = '';
			}

			if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/media/") { 
				$tabclass = "active"; 
			} else { 
				$tabclass = "inactive"; 
			}
			echo ' 		<div class="tab-inner tab-'. $tabclass .'" style="'.$d.'">';
			echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/media/\">\n";
			echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Photos", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
			echo " 			</a>\n";
			echo " 		</div>\n";

			if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/availability/") { 
				$tabclass = "active"; 
			} else { 
				$tabclass = "inactive"; 
			}
			echo ' 		<div class="tab-inner tab-'. $tabclass .'" style="'.$d.'">';
			echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/availability/\">\n";
			echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("Availability", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
			echo " 			</a>\n";
			echo " 		</div>\n";
		}
                
		if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/preferences/") { 
			$tabclass = "active"; 
		} else { 
			$tabclass = "inactive"; 
		}
		echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-member/preferences/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("Preferences", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";

		$bb_agencyinteract_options_arr = get_option('bb_agencyinteract_options');
			$bb_agencyinteract_option_subscribeupsell = (int)$bb_agencyinteract_options_arr['bb_agencyinteract_option_subscribeupsell'];

		if ($bb_agencyinteract_option_subscribeupsell) {
			// Is there a subscription?
			$sql = "SELECT `SubscriberDateExpire` FROM ". table_agencyinteract_subscription ." WHERE SubscriberDateExpire >= NOW() AND ProfileID =  ". $current_user->ID ." ORDER BY SubscriberDateExpire DESC LIMIT 1";
			$results = $wpdb->get_results($sql);

			if (!empty($results)) {
			  	foreach ($results as $data) {
					$SubscriberDateExpire = $data->SubscriberDateExpire;
					echo " 		<div class=\"tab-right tab-". $tabclass ."\">\n";
					echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/subscription/\">\n";
					echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Subscription", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
					echo " 			</a>\n";
					echo " 		</div>\n";
			  	} // is there record?
			} else {
				$SubscriberDateExpire = NULL;
				echo " 		<div class=\"tab-right tab-". $tabclass ."\">\n";
				echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/subscription/\">\n";
				echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Subscription", bb_agencyinteract_TEXTDOMAIN) ."</div></div></div>\n";
				echo " 			</a>\n";
				echo " 		</div>\n";
			}
		}
		echo "   </div>\n";
		echo " </div>\n";
?>