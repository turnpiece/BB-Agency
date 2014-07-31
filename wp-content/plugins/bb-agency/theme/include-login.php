<div id="bbsignin-register" class="rbinteract">
	<?php if ( isset($error) && $error ) : ?>
	<p class="error"><?php echo $error ?></p>
	<?php endif; ?>
	<div id="bbsign-in" class="inline-block">
	  	<h1><?php _e("Members sign in", bb_agency_TEXTDOMAIN) ?></h1>
	  	<form name="loginform" id="login" action="<?php echo network_site_url('/') ?>profile-login/" method="post">
	  		<input type="hidden" name="redirect_to" value="<?php echo isset($redirect_to) ? $redirect_to : the_permalink() ?>" />
	    	<div class="field-row">
	      		<label for="user-name"><?php _e("Username", bb_agency_TEXTDOMAIN) ?></label>
	      		<input type="text" name="user-name" value="<?php echo wp_specialchars( $_POST['user-name'], 1 ) ?>" id="user-name" />
	    	</div>
	    	<div class="field-row">
	      		<label for="password"><?php _e("Password", bb_agency_TEXTDOMAIN) ?></label>
	      		<input type="password" name="password" value="" id="password" /> <a href="<?php bloginfo('wpurl') ?>/wp-login.php?action=lostpassword"><?php _e("forgot password", bb_agency_TEXTDOMAIN) ?>?</a>
		    </div>
		    <div class="field-row">
		      	<input type="checkbox" name="remember-me" value="forever" /> <?php _e("Keep me signed in", bb_agency_TEXTDOMAIN) ?>
		    </div>
		    <div class="field-row submit-row">
	      		<input type="hidden" name="action" value="log-in" />
	        	<input name="lastviewed" value="http://<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" type="hidden" />
	      		<input type="submit" value="<?php _e("Sign In", bb_agency_TEXTDOMAIN) ?>" /><br />
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
				
	<div class="clear line"></div>
</div>