<?php 
/*
  Plugin Name: BB Agency Interact
  Text Domain: bb-agencyinteract-interact
  Plugin URI: http://rbplugin.com/
  Description: Enhancement to the BB Agency software allowing models to manage their own information. Forked from RB Agency Interact plugin.
  Author: Paul Jenkins
  Author URI: http://turnpiece.com/
  Version: 0.0.2
*/
$bb_agencyinteract_VERSION = "0.0.2"; 
if (!session_id())
session_start();
if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8 
	echo "<div class=\"error\"><p>". __("This plugin requires WordPress version 2.8 or newer", bb_agencyinteract_TEXTDOMAIN) .".</p></div>\n";
	return;
}
// *************************************************************************************************** //
// Avoid direct calls to this file, because now WP core and framework has been used
	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}


// Plugin Definitions
	define("bb_agencyinteract_VERSION", $bb_agencyinteract_VERSION); // e.g. 1.0
	define("bb_agencyinteract_BASENAME", plugin_basename(__FILE__) );  // bb-agency/bb-agency.php
	$bb_agencyinteract_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
	$bb_agencyinteract_WPUPLOADARRAY = wp_upload_dir(); // Array  $bb_agencyinteract_WPUPLOADARRAY['baseurl'] $bb_agencyinteract_WPUPLOADARRAY['basedir']
	define("bb_agencyinteract_BASEDIR", get_bloginfo("wpurl") ."/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/bb-agency-interact/
	define("bb_agencyinteract_UPLOADDIR", $bb_agencyinteract_WPUPLOADARRAY['baseurl'] ."/profile-media/" );  // http://domain.com/wordpress/wp-content/uploads/profile-media/
	define("bb_agencyinteract_UPLOADPATH", $bb_agencyinteract_WPUPLOADARRAY['basedir'] ."/profile-media/" ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
	define("bb_agencyinteract_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   bb-agency
// Call Language Options
	add_action('init', 'bb_agencyinteract_loadtranslation');
		function bb_agencyinteract_loadtranslation(){
			load_plugin_textdomain( bb_agencyinteract_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/translation/' ); 
		}
	
// *************************************************************************************************** //
// Set Table Names
	if (!defined("table_agencyinteract_agent"))
		define("table_agencyinteract_agent", "bb_agencyinteract_agent");
	if (!defined("table_agencyinteract_subscription"))
		define("table_agencyinteract_subscription", "bb_agencyinteract_subscription");
	if (!defined("table_agencyinteract_subscription_rates"))
		define("table_agencyinteract_subscription_rates", "bb_agencyinteract_subscription_rates");
// Call default functions
	include_once(dirname(__FILE__).'/functions.php');

// Does it need a diaper change?
	include_once(dirname(__FILE__).'/upgrade.php');

// *************************************************************************************************** //
// Creating tables on plugin activation
	function bb_agencyinteract_install() {
		// Required for all WordPress database manipulations
		global $wpdb, $bb_agencyinteract_options_arr;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		// Update the options in the database
		update_option("bb_agencyinteract_options", $bb_agencyinteract_options_arr);
		// Hold the version in a seprate opgion
		add_option("bb_agencyinteract_version", $bb_agencyinteract_VERSION);
		// Subscriptions
		$sql = "CREATE TABLE ". table_agencyinteract_subscription ." (
			SubscriberID BIGINT(20) NOT NULL AUTO_INCREMENT,
			ProfileID BIGINT(20) NOT NULL DEFAULT '0',
			SubscriberDateStart TIMESTAMP DEFAULT NOW(),
			SubscriberDateExpire DATE,
			SubscriberPurchasePrice DECIMAL(12,2),
			SubscriberPurchaseDetails TEXT,
			PRIMARY KEY (SubscriberID)
			);";
		dbDelta($sql);
		// Subscriptions
		$sql = "CREATE TABLE ". table_agencyinteract_subscription_rates ." (
			SubscriptionRateID BIGINT(20) NOT NULL AUTO_INCREMENT,
			SubscriptionRateTitle VARCHAR(255),
			SubscriptionRateType VARCHAR(255),
			SubscriptionRateText TEXT,
			SubscriptionRateTerm INT(10) NOT NULL DEFAULT '1',
			SubscriptionRatePrice DECIMAL(12,2),
			PRIMARY KEY (SubscriptionRateID)
			);";
		dbDelta($sql);
		
		
	}
//Activate Install Hook
register_activation_hook(__FILE__,'bb_agencyinteract_install');

// *************************************************************************************************** //
// Register Administrative Settings
if ( is_admin() ){
	/****************  Add Options Page Settings Group ***************/
	add_action('admin_init', 'bb_agencyinteract_register_settings');
		// Register our Array of settings
		function bb_agencyinteract_register_settings() {
			register_setting('bb-agencyinteract-settings-group', 'bb_agencyinteract_options'); //, 'bb_agencyinteract_options_validate'
		}
		
		// Validate/Sanitize Data
		function bb_agencyinteract_options_validate($input) {
			// Sanitize Data
		}	
	
	add_action('admin_menu','set_bb_agencyinteract_menu');
		//Create Admin Menu
		function set_bb_agencyinteract_menu(){
			// Add Approve Users
			add_submenu_page('users.php', __('Approve Users', bb_agencyinteract_TEXTDOMAIN), __('Approve Users', bb_agencyinteract_TEXTDOMAIN), 'edit_users', basename(__FILE__), 'bb_agencyinteract_approvemembers');
			//add_filter('plugin_action_links', 'bb_agencyinteract_filter_plugin_actions', 10, 2);
			
		}
		/*
		// Adds the Settings link to the plugin activate/deactivate page
		function bb_agencyinteract_filter_plugin_actions($links, $file) {
			static $this_plugin;
			
			if (!$this_plugin) {
				$this_plugin = plugin_basename(__FILE__);	
			}
			if ($file == $this_plugin){
				$settings_link = '<a href="users.php?page=' . bb_agencyinteract_BASENAME . '">' . __('Settings', bb_agencyinteract_TEXTDOMAIN) . '</a>';
				array_unshift($links, $settings_link); // before other links
			}
			return $links;
		}
		*/
		
	/****************  Activate Admin Menu Hook ***********************/
	
		//Pages
		function bb_agencyinteract_settings(){
			include_once('admin/settings.php');
		}
		function bb_agencyinteract_approvemembers(){
			include_once('admin/profile-approve.php');
		}
	
	
		
}
// *************************************************************************************************** //
// Add Widgets
	// Login / Actions Widget
	add_action('widgets_init', create_function('', 'return register_widget("bb_agencyinteract_widget_loginactions");'));
		class bb_agencyinteract_widget_loginactions extends WP_Widget {
			
			// Setup
			function bb_agencyinteract_widget_loginactions() {
				$widget_ops = array('classname' => 'bb_agencyinteract_widget_profileaction', 'description' => __("Displays profile actions such as login and links to edit", bb_agencyinteract_TEXTDOMAIN) );
				$this->WP_Widget('bb_agencyinteract_widget_profileaction', __("Agency Interact Login", bb_agencyinteract_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				$count = $instance['trendShowCount'];
				# $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				# $entry_title = empty($instance['entry_title']) ? ' ' : apply_filters('widget_entry_title', $instance['entry_title']);
				# $comments_title = empty($instance['comments_title']) ? ' ' : apply_filters('widget_comments_title', $instance['comments_title']);
				
				$atts = array('count' => $count);
 				
				   if(!is_user_logged_in()){
					   
					     
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
							echo " test<form></form>";    
							echo "          <form name=\"loginform\" id=\"login\" action=\"". network_site_url("/") ."profile-login/\" method=\"post\">\n";
							echo "            <div class=\"box\">\n";
							echo "              <label for=\"user-name\">". __("Username", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( $_POST['user-name'], 1 ) ."\" id=\"user-name\" />\n";
							echo "            </div>\n";
							echo "            <div class=\"box\">\n";
							echo "              <label for=\"password\">". __("Password", bb_agencyinteract_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", bb_agencyinteract_TEXTDOMAIN). "?</a>\n";
							echo "            </div>\n";
							echo "            <div class=\"box\">\n";
							echo "              <input type=\"checkbox\" name=\"remember-me\" value=\"forever\" /> ". __("Keep me signed in", bb_agencyinteract_TEXTDOMAIN). "\n";
							echo "            </div>\n";
							echo "            <div class=\"submit-box\">\n";
							echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
							echo "              <input type=\"submit\" value=\"". __("Sign In", bb_agencyinteract_TEXTDOMAIN). "\" /><br />\n";
							echo "            </div>\n";
							echo "          </form>\n";     
						 
					 } else {
						   if(current_user_can('level_10')){
							  if ( !empty( $title ) ) { echo $before_title . "BB Agency Settings" . $after_title; };
							  echo "<ul>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu")."\">Overview</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu_profiles")."\">Manage Profiles</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agencyinteract_menu_approvemembers")."\">Approve Profiles</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu_search")."\">Search Profiles</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu_searchsaved")."\">Saved Searches</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu_reports")."\">Tools &amp; Reports</a></li>";
							  echo "<li><a href=\"".admin_url("admin.php?page=bb_agency_menu_settings")."\">Settings</a></li>";
							  echo "<li><a href=\"/wp-login.php?action=logout&_wpnonce=3bb3c87a3d\">Logout</a></li>";	    
								  echo "</ul>";
							
							 } else{
							  
								bb_agency_profilesearch(array("profilesearch_layout" =>"simple"));   
								   
							 }
							 
					 }
		   
				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['trendShowCount'] = strip_tags($new_instance['trendShowCount']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$trendShowCount = esc_attr($instance['trendShowCount']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('trendShowCount'); ?>"><?php _e('Show Count:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('trendShowCount'); ?>" name="<?php echo $this->get_field_name('trendShowCount'); ?>" type="text" value="<?php echo $trendShowCount; ?>" /></label></p>
				<?php 
			}
		
		} // class
                 
		     
		                
// *************************************************************************************************** //
// Add Short Codes
	add_shortcode("agency_register","bb_agencyinteract_shortcode_agencyregister");
		function bb_agencyinteract_shortcode_agencyregister($atts, $content = null){
			ob_start();
			wp_register_form($atts);
			$output_string=ob_get_contents();
			ob_end_clean();
			return $output_string;
		}

	add_shortcode("profile_register","bb_agencyinteract_shortcode_profileregister");
		function bb_agencyinteract_shortcode_profileregister($atts, $content = null){
			ob_start();
			wp_register_form($atts);
			$output_string=ob_get_contents();
			ob_end_clean();
			return $output_string;
		}

/****************************************************************/
//Uninstall
	function bb_agencyinteract_uninstall() {
		
		register_uninstall_hook(__FILE__, 'bb_agencyinteract_uninstall_action');
		function bb_agencyinteract_uninstall_action() {
			//delete_option('create_my_taxonomies');
		}
	
		// Final Cleanup
		delete_option('bb_agencyinteract_options');
		
		$thepluginfile = "bb-agency/bb-agency.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", bb_agencyinteract_TEXTDOMAIN) ."</p><h1>". __("One More Step", bb_agencyinteract_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", bb_agencyinteract_TEXTDOMAIN) ."</a></h1></div>";
		die;
	}
?>