<?php
echo "	<div id=\"rbsignin-register\" class=\"rbinteract\">\n";

			if ( $error ) {
				echo "<p class=\"error\">". $error ."</p>\n";
			}
			
$profileviewed = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

echo "        <div id=\"rbsign-in\" class=\"inline-block\">\n";
echo "          <h1>". __("Members Sign in", bb_agencyinteract_TEXTDOMAIN). "</h1>\n";
echo "          <form name=\"loginform\" id=\"login\" action=\"". network_site_url("/"). "profile-login/\" method=\"post\">\n";
echo " 			<input type=\"hidden\" name=\"redirect_to\" value=\"".network_site_url("/")."dashboard/\">";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"user-name\">". __("Username", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( $_POST['user-name'], 1 ) ."\" id=\"user-name\" />\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"password\">". __("Password", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", bb_agencyinteract_TEXTDOMAIN). "?</a>\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <input type=\"checkbox\" name=\"remember-me\" value=\"forever\" /> ". __("Keep me signed in", bb_agencyinteract_TEXTDOMAIN). "\n";
echo "            </div>\n";
echo "            <div class=\"field-row submit-row\">\n";
echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
echo "                <input name=\"lastviewed\" value=\"".$profileviewed."\" type=\"hidden\" />";
echo "              <input type=\"submit\" value=\"". __("Sign In", bb_agencyinteract_TEXTDOMAIN). "\" /><br />\n";
		if($bb_agencyinteract_option_fb_registerallow == 1){
				echo " <div class=\"fb-login-button\" scope=\"email\" data-show-faces=\"false\" data-width=\"200\" data-max-rows=\"1\"></div>";
						echo "  <div id=\"fb-root\"></div>
						
							<script>
							window.fbAsyncInit = function() {
							    FB.init({
								appId      : '".$bb_agencyinteract_option_fb_app_id."',  ";
						  if(empty($bb_agencyinteract_option_fb_app_uri)){  // set default
							   echo "\n channelUrl : '".network_site_url("/")."profile-member/', \n";
						   }else{
							  echo "channelUrl : '".$bb_agencyinteract_option_fb_app_uri."',\n"; 
						   }
						 echo "	status     : true, // check login status
								cookie     : true, // enable cookies to allow the server to access the session
								xfbml      : true  // parse XFBML
							    });
							  };
					  		// Load the SDK Asynchronously
							(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1&appId=".$bb_agencyinteract_option_fb_app_id."'
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>";
		}
echo "            </div>\n";
echo "          </form>\n";
echo "        </div> <!-- rbsign-in -->\n";
			
echo "      <div class=\"clear line\"></div>\n";
echo "      </div>\n";
?>