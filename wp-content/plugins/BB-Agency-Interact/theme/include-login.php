<?php
	/* Load registration file. */
	require_once( ABSPATH . WPINC . '/registration.php' );
	
	/* Check if users can register. */
	$registration = get_option( 'bb_agencyinteract_options' );
	$bb_agencyinteract_option_registerallow = $registration["bb_agencyinteract_option_registerallow"];
	// Facebook Login Integration
	$bb_agencyinteract_option_fb_registerallow = $registration['bb_agencyinteract_option_fb_registerallow'];
	$bb_agencyinteract_option_fb_app_id = $registration['bb_agencyinteract_option_fb_app_id'];
	$bb_agencyinteract_option_fb_app_secret = $registration['bb_agencyinteract_option_fb_app_secret'];
	$bb_agencyinteract_option_fb_app_uri = $registration['bb_agencyinteract_option_fb_app_uri'];
      $bb_agencyinteract_option_registerallowAgentProducer = $registration['bb_agencyinteract_option_registerallowAgentProducer'];
	if (( current_user_can("create_users") || $bb_agencyinteract_option_registerallow )) {
		$widthClass = "half";
	} else {
		$widthClass = "full";
	}

	// File Path: interact/theme/include-login.php
	// Site Url : /profile-login/

echo "     <div id=\"rbsignin-register\" class=\"rbinteract\">\n";

			if ( $error ) {
			echo "<p class=\"error\">". $error ."</p>\n";
			}
			
	

echo "        <div id=\"rbsign-in\" class=\"inline-block\">\n";
echo "          <h1>". __("Members Sign in", bb_agencyinteract_TEXTDOMAIN). "</h1>\n";
echo "          <form name=\"loginform\" id=\"login\" action=\"". network_site_url("/"). "profile-login/\" method=\"post\">\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"user-name\">". __("Username", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( $_POST['user-name'], 1 ) ."\" id=\"user-name\" />\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"password\">". __("Password", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", bb_agencyinteract_TEXTDOMAIN). "?</a>\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <label><input type=\"checkbox\" name=\"remember-me\" value=\"forever\" /> ". __("Keep me signed in", bb_agencyinteract_TEXTDOMAIN). "</label>\n";
echo "            </div>\n";
echo "            <div class=\"field-row submit-row\">\n";
echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
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

			if (( current_user_can("create_users") || $bb_agencyinteract_option_registerallow == 1)) {

echo "        <div id=\"rbsign-up\" class=\"inline-block\">\n";
echo "          <div id=\"talent-register\" class=\"register\">\n";
echo "            <h1>". __("Not a member", bb_agencyinteract_TEXTDOMAIN). "?</h1>\n";
echo "            <h3>". __("Talent", bb_agencyinteract_TEXTDOMAIN). " - ". __("Register here", bb_agencyinteract_TEXTDOMAIN). "</h3>\n";
echo "            <ul>\n";
echo "              <li>". __("Create your free profile page", bb_agencyinteract_TEXTDOMAIN). "</li>\n";
echo "              <li>". __("Apply to Auditions & Jobs", bb_agencyinteract_TEXTDOMAIN). "</li>\n";
echo "              <li><a href=\"". get_bloginfo("wpurl") ."/profile-register/\" class=\"bb_button\">". __("Register as Talent / Model", bb_agencyinteract_TEXTDOMAIN). "</a></li>\n";
echo "            </ul>\n";
echo "          </div> <!-- talent-register -->\n";
echo "          <div class=\"clear line\"></div>\n";
if($bb_agencyinteract_option_registerallowAgentProducer == 1){
echo "          <div id=\"agent-register\" class=\"register\">\n";
echo "            <h3>". __("Casting Agents & Producers", bb_agencyinteract_TEXTDOMAIN). "</h3>\n";
echo "            <ul>\n";
echo "              <li>". __("List Auditions & Jobs free", bb_agencyinteract_TEXTDOMAIN). "</li>\n";
echo "              <li>". __("Contact People in the Talent Directory", bb_agencyinteract_TEXTDOMAIN). "</li>\n";
echo "              <li><a href=\"". get_bloginfo("wpurl") ."/profile-register/\" class=\"bb_button\">". __("Register as Agent / Producer", bb_agencyinteract_TEXTDOMAIN). "</a></li>\n";
echo "            </ul>\n";
echo "          </div> <!-- talent-register -->\n";
}
echo "        </div> <!-- rbsign-up -->\n";
			}
			
echo "      <div class=\"clear line\"></div>\n";
echo "      </div>\n";
?>