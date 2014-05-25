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
	if ( current_user_can("create_users") || $bb_agencyinteract_option_registerallow ) {
		$widthClass = "half";
	} else {
		$widthClass = "full";
	}

	// File Path: interact/theme/include-login.php
	// Site Url : /profile-login/
?>
<div id="bbsignin-register" class="bbinteract">
<?php if ( isset($error) && $error ) : ?>
	<p class="error"><?php echo $error ?></p>
<?php endif; ?>
	<div id="bbsign-in" class="inline-block">
     	<h1><?php _e("Members Sign in", bb_agencyinteract_TEXTDOMAIN) ?></h1>
     	<form name="loginform" id="login" action="<?php echo network_site_url('/') ?>profile-login/" method="post">
	       	<div class="field-row">
	         	<label for="user-name"><?php _e("Username", bb_agencyinteract_TEXTDOMAIN) ?></label>
	         	<input type="text" name="user-name" value="<?php echo wp_specialchars( $_POST['user-name'], 1 ) ?>" id="user-name" />
	       	</div>
	       	<div class="field-row">
	         	<label for="password">"<?php _e("Password", bb_agencyinteract_TEXTDOMAIN) ?></label>
	         	<input type="password" name="password" value="" id="password" /> <a href="<?php bloginfo('wpurl') ?>/wp-login.php?action=lostpassword"><?php _e("forgot password", bb_agencyinteract_TEXTDOMAIN) ?>?</a>
	       	</div>
	       	<div class="field-row">
	         	<label>
	         		<input type="checkbox" name="remember-me" value="forever" /> <?php _e("Keep me signed in", bb_agencyinteract_TEXTDOMAIN) ?>
	         	</label>
	       	</div>
	       	<div class="field-row submit-row">
	         	<input type="hidden" name="action" value="log-in" />
	         	<input type="submit" value="<?php _e("Sign In", bb_agencyinteract_TEXTDOMAIN) ?>" /><br />
				<?php if ($bb_agencyinteract_option_fb_registerallow == 1) : ?>
				<div class="fb-login-button" scope="email" data-show-faces="false" data-width="200" data-max-rows="1"></div>
				<div id="fb-root"></div>
				<script>
					window.fbAsyncInit = function() {
						FB.init({
							appId 		: '<?php echo $bb_agencyinteract_option_fb_app_id ?>',
							<?php if (empty($bb_agencyinteract_option_fb_app_uri)) :  // set default ?>
							channelUrl 	: '<?php echo network_site_url("/") ?>profile-member/',
							<?php else : ?>
							channelUrl 	: '<?php echo $bb_agencyinteract_option_fb_app_uri ?>', 
							<?php endif; ?>
							status     	: true, // check login status
							cookie     	: true, // enable cookies to allow the server to access the session
							xfbml      	: true  // parse XFBML
						});
					};
			  		// Load the SDK Asynchronously
					(function(d, s, id) {
					  	var js, fjs = d.getElementsByTagName(s)[0];
					  	if (d.getElementById(id)) return;
					  	js = d.createElement(s); js.id = id;
					  	js.src = '//connect.facebook.net/en_US/all.js#xfbml=1&appId=".$bb_agencyinteract_option_fb_app_id."'
					  	fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<?php endif; ?>
	       	</div>
	    </form>
	</div> <!-- bbsign-in -->

	<?php if ( current_user_can("create_users") || $bb_agencyinteract_option_registerallow == 1 ) : ?>
	<div id="bbsign-up" class="inline-block">
	  	<div id="talent-register" class="register">
	    	<h1><?php _e("Not a member", bb_agencyinteract_TEXTDOMAIN) ?>?</h1>
	    	<h3><?php _e("Talent", bb_agencyinteract_TEXTDOMAIN) ?> - <?php _e("Register here", bb_agencyinteract_TEXTDOMAIN) ?></h3>
		    <ul>
		      	<li><?php _e("Create your free profile page", bb_agencyinteract_TEXTDOMAIN) ?></li>
		      	<li><?php _e("Apply for auditions and jobs", bb_agencyinteract_TEXTDOMAIN) ?></li>
		      	<li><a href="<?php bloginfo('wpurl') ?>/profile-register/" class="bb_button"><?php _e("Register as talent / model", bb_agencyinteract_TEXTDOMAIN) ?></a></li>
		    </ul>
	  	</div> <!-- talent-register -->
	  	<div class="clear line"></div>
		<?php if ($bb_agencyinteract_option_registerallowAgentProducer == 1) : ?>
	  	<div id="agent-register" class="register">
	    	<h3><?php _e("Casting Agents & Producers", bb_agencyinteract_TEXTDOMAIN) ?></h3>
	    	<ul>
	      		<li><?php _e("List auditions and jobs", bb_agencyinteract_TEXTDOMAIN) ?></li>
	      		<li><?php _e("Contact people in the talent directory", bb_agencyinteract_TEXTDOMAIN) ?></li>
	      		<li><a href="<?php bloginfo('wpurl') ?>/profile-register/" class="bb_button"><?php _e("Register as agent / producer", bb_agencyinteract_TEXTDOMAIN) ?></a></li>
	    	</ul>
	  	</div> <!-- talent-register -->
	  	<?php endif; ?>
	</div> <!-- bbsign-up -->
	<?php endif; ?>
				
	<div class="clear line"></div>
</div>