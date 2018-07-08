<?php 
/*
  Plugin Name: BB Agency
  Text Domain: bb-agency
  Description: Forked from RB Agency plugin and adapted for the Beautiful Bumps agency. With this plugin you can easily manage models' profiles and information.
  Author: Paul Jenkins
  Author URI: http://turnpiece.com/
  Version: 2.0.7
*/

$bb_agency_VERSION = "2.0.7"; // starter

if (!get_option("bb_agency_version")) {  
	add_option("bb_agency_version", $bb_agency_VERSION , '', 'no');  
	update_option("bb_agency_version", $bb_agency_VERSION);
}
	
if (!session_id()) 
	session_start();

// Requires 2.8 or more
if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8
	echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 2.8 or newer.</p></div>";
	return;
}

// Avoid direct calls to this file, because now WP core and framework has been used
	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

// Plugin Definitions
	define('bb_agency_BASENAME', plugin_basename(__FILE__) );  // bb-agency/bb-agency.php
	$bb_agency_WPURL = get_bloginfo('wpurl'); // http://domain.com/wordpress
	$bb_agency_WPUPLOADARRAY = wp_upload_dir(); // Array  $bb_agency_WPUPLOADARRAY['baseurl'] $bb_agency_WPUPLOADARRAY['basedir']

	define('bb_agency_BASEDIR', get_bloginfo('wpurl') .'/'. PLUGINDIR .'/'. dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/bb-agency/
	define('bb_agency_BASEREL', str_replace(get_bloginfo('url'), '', bb_agency_BASEDIR));  // /wordpress/wp-content/uploads/profile-media/
	define('bb_agency_BASEPATH', plugin_dir_path(__FILE__) );  // wordpress/wp-content/plugins/bb-agency/
	define('bb_agency_UPLOADREL', str_replace(get_bloginfo('url'), '', $bb_agency_WPUPLOADARRAY['baseurl']) .'/profile-media/' );  // /wordpress/wp-content/uploads/profile-media/
	define('bb_agency_UPLOADDIR', $bb_agency_WPUPLOADARRAY['baseurl'] .'/profile-media/' );  // http://domain.com/wordpress/wp-content/uploads/profile-media/
	define('bb_agency_UPLOADPATH', $bb_agency_WPUPLOADARRAY['basedir'] .'/profile-media/' ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
	define('bb_agency_ADMIN_MEDIA_UPLOAD', true); // whether to allow administrators to upload media for models
	define('bb_agency_TEXTDOMAIN', basename(dirname( __FILE__ )) ); //   bb-agency
	define('bb_agency_SITETYPE', 'children'); // bumps or children
	define('bb_agency_MAX_AGE', 18);
	define('bb_agency_PLUGIN_TITLE', 'BB Agency'. (bb_agency_SITETYPE == 'children' ? ' (Kiddiwinks)' : ''));
	define('bb_agency_PHONE', '07740 334325');
	define('bb_agency_LOGOPATH', ABSPATH . '/wp-content/uploads/' . (bb_agency_SITETYPE == 'children' ? '2014/07/Kiddiwinks-Logo.png' : '2013/07/Beautiful_Bumps_Logo1.jpg'));
	define('bb_agency_MAX_HEIGHT', 200); // max height in cm
	define('bb_agency_DEBUGGING', false); // debugging
	
	//define('bb_agency_TESTING', true);
	define('bb_agency_TERMS', get_bloginfo('url').'/clients-standard-terms-conditions');

	// email sending
	define('bb_agency_CHARSET', 'utf-8'); // was iso-8859-1
	define('bb_agency_SEND_EMAILS', true); // whether or not to send emails
	define('bb_agency_EMAIL_CARDS', true); // whether or not to email links to cards or profiles
	
	$bb_agency_CURRENT_TYPE_ID = 0; // will contain current type
	// Clean Up:
	$pageURL = '';
 	if ($_SERVER['SERVER_PORT'] != "80") {
  		$pageURL .= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
 	} else {
  		$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 	}

// Call Language Options

	// Check for WPMU installation
	if (!defined ('IS_WPMU')){
		global $wpmu_version;
		$is_wpmu = ((function_exists('is_multisite') and is_multisite()) or $wpmu_version) ? 1 : 0;
		define('IS_WPMU', $is_wpmu);
	}

	add_action('init', 'bb_agency_loadtranslation');
	function bb_agency_loadtranslation(){
		load_plugin_textdomain( bb_agency_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/translation/' ); 
	}
	

// *************************************************************************************************** //

// Set Table Names
	if (!defined("table_agency_casting"))
		define('table_agency_casting', "bb_agency_casting");
	if (!defined("table_agency_profile"))
		define('table_agency_profile', "bb_agency_profile");
	if (!defined("table_agency_job"))
		define('table_agency_job', "bb_agency_job");
	if (!defined("table_agency_booking"))
		define('table_agency_booking', "bb_agency_booking");
	if (!defined("table_agency_profile_media"))
		define('table_agency_profile_media', "bb_agency_profile_media");
	if (!defined("table_agency_data_ethnicity"))
		define('table_agency_data_ethnicity', "bb_agency_data_ethnicity");
	if (!defined("table_agency_data_colorskin"))
		define('table_agency_data_colorskin', "bb_agency_data_colorskin");
	if (!defined("table_agency_data_coloreye"))
		define('table_agency_data_coloreye', "bb_agency_data_coloreye");
	if (!defined("table_agency_data_colorhair"))
		define('table_agency_data_colorhair', "bb_agency_data_colorhair");
	if (!defined("table_agency_data_gender"))
		define('table_agency_data_gender', "bb_agency_data_gender");
	if (!defined("table_agency_rel_taxonomy"))
		define('table_agency_rel_taxonomy', "bb_agency_rel_taxonomy");
	if (!defined("table_agency_data_type"))
		define('table_agency_data_type', "bb_agency_data_type");
	if (!defined("table_agency_data_talent"))
		define('table_agency_data_talent', "bb_agency_data_talent");
	if (!defined("table_agency_data_genre"))
		define('table_agency_data_genre', "bb_agency_data_genre");
	if (!defined("table_agency_data_ability"))
		define('table_agency_data_ability', "bb_agency_data_ability");
	if (!defined("table_agency_customfields"))
		define('table_agency_customfields', "bb_agency_customfields");
	if (!defined("table_agency_customfield_mux"))
		define('table_agency_customfield_mux', "bb_agency_customfield_mux");
	if (!defined("table_agency_searchsaved"))
		define('table_agency_searchsaved', "bb_agency_searchsaved");
	if (!defined("table_agency_searchsaved_mux"))
		define('table_agency_searchsaved_mux', "bb_agency_searchsaved_mux");
	if (!defined("table_agency_savedfavorite"))
		define('table_agency_savedfavorite', "bb_agency_savedfavorite");	
	if (!defined("table_agency_castingcart"))
		define('table_agency_castingcart', "bb_agency_castingcart");
	if (!defined("table_agency_mediacategory"))
		define('table_agency_mediacategory', "bb_agency_mediacategory");
	if (!defined("table_agency_customfields_types"))
	define('table_agency_customfields_types', "bb_agency_customfields_types");				


// Declare Global WordPress Database Access
    global $wpdb;

// Declare Version
	$bb_agency_storedversion = get_option("bb_agency_version");
	$bb_agency_VERSION = get_option("bb_agency_version");	
	define('bb_agency_VERSION', $bb_agency_VERSION); // e.g. 1.0

// Call default functions
	include_once(dirname(__FILE__).'/functions.php');


// Now Call the Lanuage
	define('bb_agency_PROFILEDIR', get_bloginfo('wpurl') . bb_agency_getActiveLanguage() ."/profile/" ); // http://domain.com/wordpress/de/profile/

// Load options from database
	global $bb_options;
	$bb_options = bb_agency_reload_options();


// *************************************************************************************************** //
// Settings for Beautiful Bumps & Kiddiwinks

	if (bb_agency_SITETYPE == 'bumps') {
		define('bb_agency_MUMSTOBE_ID', 1); // id of mums to be data type
		define('bb_agency_AFTERBIRTH_ID', 2); // id of data type to move mums to be to once they've given birth
	}
	define('bb_agency_CLIENTS_ID', bb_agency_client_id()); // id of clients
	//define('bb_agency_BABIES_ID', 8); // id of babies


// *************************************************************************************************** //
// Creating tables on plugin activation

	function bb_agency_install() {
		// Required for all WordPress database manipulations
		global $wpdb, $bb_options;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		// Ensure directory is setup
		if (!is_dir(bb_agency_UPLOADPATH)) {
			@mkdir(bb_agency_UPLOADPATH, 0755);
			@chmod(bb_agency_UPLOADPATH, 0777);
		}
           
		// Update the options in the database
		if(!get_option("bb_agency_options"))
			add_option("bb_agency_options",$bb_options);

		update_option("bb_agency_options",$bb_options);
		
		// Hold the version in a seprate option
		if(!get_option("bb_agency_version"))
			add_option("bb_agency_version", $bb_agency_VERSION);
		
		update_option("bb_agency_version", $bb_agency_VERSION);
		
		/*
		 * Fixed this so that tables will always be
		 * checked if created.
		 */
		// Creating the tables!
			$sql = "CREATE TABLE IF NOT EXISTS " . table_agency_profile . " (
				ProfileID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileUserLinked BIGINT(20) NOT NULL DEFAULT '0',
				ProfileGallery VARCHAR(255),
				ProfileContactDisplay VARCHAR(255),
				ProfileContactNameFirst VARCHAR(255),
				ProfileContactNameLast VARCHAR(255),
				ProfileGender VARCHAR(255),
				ProfileDateBirth DATE,
				ProfileDateDue DATE,
				ProfileLocationStreet VARCHAR(255),
				ProfileLocationCity VARCHAR(255),
				ProfileLocationState VARCHAR(255),
				ProfileLocationZip VARCHAR(255),
				ProfileLocationCountry VARCHAR(255),
				ProfileContactEmail VARCHAR(255),
				ProfileContactWebsite VARCHAR(255),
				ProfileContactPhoneHome VARCHAR(255),
				ProfileContactPhoneCell VARCHAR(255),
				ProfileContactPhoneWork VARCHAR(255),
				ProfileContactLinkTwitter VARCHAR(255),
				ProfileContactLinkFacebook VARCHAR(255),
				ProfileContactLinkYoutube VARCHAR(255),
				ProfileContactLinkFlickr VARCHAR(255),
				ProfileDateCreated TIMESTAMP DEFAULT NOW(),
				ProfileDateUpdated TIMESTAMP,
				ProfileDateViewLast TIMESTAMP,
				ProfileType VARCHAR(255),
				ProfileTalent VARCHAR(255),
				ProfileIsActive INT(10) NOT NULL DEFAULT '0',
				ProfileIsFeatured INT(10) NOT NULL DEFAULT '0',
				ProfileIsPromoted INT(10) NOT NULL DEFAULT '0',
				ProfileStatHits INT(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (ProfileID)
				);";
			dbDelta($sql);
	
			// Setup > Profile Media
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_profile_media." (
				ProfileID INT(10) NOT NULL DEFAULT '0',
				ProfileMediaID INT(10) NOT NULL AUTO_INCREMENT,
				ProfileMediaType VARCHAR(255),
				ProfileMediaTitle VARCHAR(255),
				ProfileMediaText TEXT,
				ProfileMediaURL VARCHAR(255),
				ProfileMediaPrimary INT(10) NOT NULL DEFAULT '0',
				ProfileMediaFeatured INT(10) NOT NULL DEFAULT '0',
				ProfileMediaOrder VARCHAR(55),
				PRIMARY KEY (ProfileMediaID)
				);";
			dbDelta($sql);
	
			// Setup > Classification
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_data_type." (
				DataTypeID INT(10) NOT NULL AUTO_INCREMENT,
				DataTypeTitle VARCHAR(255),
				DataTypeTag VARCHAR(50),
				PRIMARY KEY (DataTypeID)
				);";
			dbDelta($sql);
			$results = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Model')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Talent')");

			// Setup > Talent
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_data_talent." (
				DataTalentID INT(10) NOT NULL AUTO_INCREMENT,
				DataTalentTitle VARCHAR(255),
				DataTalentTag VARCHAR(50),
				PRIMARY KEY (DataTalentID)
				);";
			dbDelta($sql);
			$results = $wpdb->query("INSERT INTO " . table_agency_data_talent . " (DataTypeID, DataTypeTitle) VALUES ('','Dance','dance')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_talent . " (DataTypeID, DataTypeTitle) VALUES ('','Singing','singing')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_talent . " (DataTypeID, DataTypeTitle) VALUES ('','Acting','acting')");

			// Setup > Taxonomy: Gender
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_gender ." (
				GenderID INT(10) NOT NULL AUTO_INCREMENT,
				GenderTitle VARCHAR(255),
				PRIMARY KEY (GenderID)
				);";
			dbDelta($sql);
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Male')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Female')");
	
			// Setup > Taxonomy: Actual Taxonomy
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_rel_taxonomy." (
				ProfileID BIGINT(20) NOT NULL DEFAULT 0,
				term_taxonomy_id BIGINT(20) NOT NULL DEFAULT 0,
				PRIMARY KEY (ProfileID,term_taxonomy_id)
				);";
			dbDelta($sql);
	
			
	      	// Setup > Custom Field Types
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields." (
				ProfileCustomID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomType INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOptions TEXT,
				ProfileCustomView INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOrder INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowRegistration INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1',
				PRIMARY KEY (ProfileCustomID)
				);";
			dbDelta($sql);
	 	// Populate Custom Fields
		$ethnicity = $wpdb->get_var("SELECT ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomTitle = 'Ethnicity'");

		if ($ethnicity) {
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (1, 'Ethnicity', 	3, '|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other|', 0, 1, 0, 1, 1, 0, 1, 0)");
		}
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (2, 'Skin Tone', 	3, '|Fair|Medium|Dark|', 0, 2, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (3, 'Hair Colour', 	3, '|Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn|', 0, 3, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (4, 'Eye Colour', 	3, '|Blue|Brown|Hazel|Green|Black|', 0, 4, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (5, 'Height', 		7, '3', 0, 5, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (6, 'Weight', 		7, '2', 0, 6, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (7, 'Shirt', 		1, '', 0, 8, 1, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (8, 'Waist', 		7, '1', 0, 9, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (9, 'Hips', 		7, '1', 0, 10, 2, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(10, 'Shoe Size', 	7, '1', 0, 11, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(11, 'Suit', 		3, '|36S|37S|38S|39S|40S|41S|42S|43S|44S|45S|46S|36R|38R|40R|42R|44R|46R|48R|50R|52R|54R|40L|42L|44L|46L|48L|50L|52L|54L|', 0, 7, 1, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(12, 'Inseam', 		7, '1', 0, 10, 1, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(13, 'Dress', 		3, '|2|4|6|8|10|12|14|16|18|', 0, 8, 2, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(14, 'Bust', 		3, '|32A|32B|32C|32D|32DD|34A|34B|34C|34D|34DD|36C|36D|36DD|', 0, 7, 2, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(15, 'Union', 		3, '|SAG/AFTRA|SAG ELIG|NON-UNION|', 0, 20, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(16, 'Experience', 	4, '', 0, 13, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(17, 'Language', 	1, '', 0, 14, 0, 1, 1, 0, 1, 0)");
		$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(18, 'Booking', 	4, '', 0, 15, 0, 1, 1, 0, 1, 0)");
	
			// Setup > Custom Field Types > Mux Values
			$sql9mux = "CREATE TABLE IF NOT EXISTS ". table_agency_customfield_mux ." (
				ProfileCustomMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomID BIGINT(20) NOT NULL DEFAULT '0',
				ProfileID BIGINT(20) NOT NULL DEFAULT '0',
				ProfileCustomValue TEXT,
				PRIMARY KEY (ProfileCustomMuxID)
				);";
			dbDelta($sql9mux);
	
			// Setup > Search Saved
			$sql10 = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved ." (
				SearchID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SearchTitle VARCHAR(255),
				SearchType INT(10) NOT NULL DEFAULT '0',
				SearchProfileID TEXT,
				SearchOptions VARCHAR(255),
				SearchDate TIMESTAMP DEFAULT NOW(),
				PRIMARY KEY (SearchID)
				);";
			dbDelta($sql10);

			// Setup > Custom Field Types > Mux Values
			$sql10mux = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved_mux ." (
				SearchMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SearchID BIGINT(20) NOT NULL DEFAULT '0',
				SearchMuxHash VARCHAR(255),
				SearchMuxToName VARCHAR(255),
				SearchMuxToEmail VARCHAR(255),
				SearchMuxSubject VARCHAR(255),
				SearchMuxMessage TEXT,
				SearchMuxCustomValue VARCHAR(255),
				SearchMuxSent TIMESTAMP DEFAULT NOW(),
				PRIMARY KEY (SearchMuxID)
				);";
			dbDelta($sql10mux);
           	// Setup > Save Favorite
			$sql11 = "CREATE TABLE IF NOT EXISTS ". table_agency_savedfavorite." (
				SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SavedFavoriteProfileID VARCHAR(255),
			      SavedFavoriteTalentID VARCHAR(255),
				PRIMARY KEY (SavedFavoriteID)
				);";
			dbDelta($sql11);

			
			// Setup > Add to Casting Cart
			$sql12 = "CREATE TABLE IF NOT EXISTS ". table_agency_castingcart." (
				CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
				CastingCartProfileID VARCHAR(255),
			      CastingCartTalentID VARCHAR(255),
				PRIMARY KEY (CastingCartID)
				);";
			dbDelta($sql12);		
			// Setup > Add to Casting Cart
			$sql13 = "CREATE TABLE IF NOT EXISTS ". table_agency_mediacategory." (
				MediaCategoryID BIGINT(20) NOT NULL AUTO_INCREMENT,
				MediaCategoryTitle VARCHAR(255),
				MediaCategoryGender VARCHAR(255),
			      MediaCategoryOrder VARCHAR(255),
				PRIMARY KEY (MediaCategoryID)
				);";
			dbDelta($sql13);	
		  
		   // Setup > Custom Field Types
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields_types." (
				ProfileCustomTypesID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomID BIGINT(20) NOT NULL,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomTypes VARCHAR(255),
				PRIMARY KEY (ProfileCustomTypesID)
				);";
			dbDelta($sql);		
	}
//Activate Install Hook
register_activation_hook(__FILE__, 'bb_agency_install');



		

// *************************************************************************************************** //
// Register Administrative Settings

if ( is_admin() ){

	// admin only functions
	include(dirname(__FILE__).'/admin.php');
}


// *************************************************************************************************** //
// Scripts

	// Remove All Known Scripts which effect
	add_action( 'wp_print_scripts', 'bb_agency_print_scripts', 100 );
		function bb_agency_print_scripts() {
			//lightbox
			wp_deregister_script('woo-shortcodes');
			//jquery
			wp_deregister_script('woocommerce_plugins');
			wp_deregister_script('woocommerce');
			wp_deregister_script('fancybox');
			wp_deregister_script('jqueryui');
			wp_deregister_script('wc_price_slider');
			wp_deregister_script('widgetSlider');
			wp_deregister_script('woo-feedback');
			wp_deregister_script('prettyPhoto');
			wp_deregister_script('general');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-script', plugins_url('js/jquery-ui-1.10.3.custom.min.js', __FILE__), array('jquery') );
			wp_enqueue_style('jquery-ui-style', plugins_url('js/jquery-ui-1.10.3.custom.min.css', __FILE__) );
		}

	add_action('wp_footer', 'bb_agency_wp_footer');
	add_action( 'admin_print_scripts', 'bb_agency_wp_footer', 100);
		function bb_agency_wp_footer() { ?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$('.bbdatepicker').datepicker({
						dateFormat : 'yy-mm-dd'
					});
				});
			</script>
		<?php }

	add_action( 'admin_print_scripts', 'bb_agency_admin_print_scripts', 100);
		function bb_agency_admin_print_scripts() {
			wp_enqueue_script('jquery-ui-script', plugins_url('js/jquery-ui-1.10.3.custom.min.js', __FILE__), array('jquery') );
		}

	add_action( 'init', 'bb_agency_admin_print_styles', 100);
		function bb_agency_admin_print_styles() {
			wp_enqueue_style('bb-agency-admin', plugins_url('style/admin.css', __FILE__) );
			wp_enqueue_style('jquery-ui-style', plugins_url('js/jquery-ui-1.10.3.custom.min.css', __FILE__) );
		}

// *************************************************************************************************** //
// Add Widgets

	// View Featured
	add_action('widgets_init', create_function('', 'return register_widget("bb_agency_widget_showpromoted");'));
		class bb_agency_widget_showpromoted extends WP_Widget {
			
			// Setup
			function __construct() {
				$widget_ops = array('classname' => 'bb_agency_widget_showpromoted', 'description' => __("Displays promoted profiles", bb_agency_TEXTDOMAIN) );
				$this->WP_Widget('bb_agency_widget_showpromoted', __("BB Agency : Featured", bb_agency_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
				$count = $instance['count'];
					if ( empty( $count ) ) { $count = 1; };		
					
				if (function_exists('bb_agency_profilefeatured')) { 
				  $atts = array('count' => $count);
				  bb_agency_profilefeatured($atts); 
				} else {
					echo "Invalid Function.";
				}
				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['count'] = strip_tags($new_instance['count']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$count = esc_attr($instance['count']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number Shown:'); ?> <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
				<?php 
			}
		
		} // Featured


	// View Topics
	add_action('widgets_init', create_function('', 'return register_widget("bb_agency_widget_showsearch");'));
		class bb_agency_widget_showsearch extends WP_Widget {
			
			// Setup
			function __construct() {
				$widget_ops = array('classname' => 'bb_agency_widget_showsearch', 'description' => __("Displays profile search fields", bb_agency_TEXTDOMAIN) );
				$this->WP_Widget('bb_agency_widget_showsearch', __("BB Agency : Search", bb_agency_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
				$showlayout = $instance['showlayout'];
					if ( empty( $showlayout ) ) { $showlayout = "condensed"; };	
						
				if (function_exists('bb_agency_profilesearch')) { 
					$atts = array('profilesearch_layout' => $showlayout);
					bb_agency_profilesearch($atts);
				} else {
					echo "Invalid Function";
				}
				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['showlayout'] = strip_tags($new_instance['showlayout']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$showlayout = esc_attr($instance['showlayout']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('showlayout'); ?>"><?php _e('Type:'); ?> <select id="<?php echo $this->get_field_id('showlayout'); ?>" name="<?php echo $this->get_field_name('showlayout'); ?>"><option value="advanced" <?php selected($showlayout, "advanced"); ?>>Advanced Search</option><option value="condensed" <?php selected($showlayout, "condensed"); ?>>Condensed Search</option></select></label></p>
				<?php 
			}
		
		} // class




// *************************************************************************************************** //
// Add Short Codes

	add_shortcode("category_list", "bb_agency_shortcode_categorylist");
		function bb_agency_shortcode_categorylist($atts, $content = null){
			ob_start();
			bb_agency_categorylist($atts);
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}

	add_shortcode("profile_list", "bb_agency_shortcode_profilelist");
		function bb_agency_shortcode_profilelist($atts, $content = null){
			$privacy = bb_agency_datatype_privacy($atts['type']);
			ob_start();
			if ($privacy && !is_user_logged_in()) {
				// display login form
				include(dirname(__FILE__).'/theme/include-login.php');
			} else {
				// display the list of profiles
				bb_agency_profile_list($atts);				
			}
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}
	
	add_shortcode("profile_search", "bb_agency_shortcode_profilesearch");
	function bb_agency_shortcode_profilesearch($atts, $content = null){
		ob_start();
		bb_agency_profilesearch($atts);
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}


	// Emails
	function bb_agency_set_content_type($content_type) {
	    return 'text/html';
	}

	add_filter ('wp_mail_from', 'bb_agency_set_mail_from');
	function bb_agency_set_mail_from( $email = null ) {
		return is_null($email) ? bb_agency_get_option('bb_agency_option_agencyemail') : $email;
	}
		
	add_filter ('wp_mail_from_name', 'bb_agency_set_mail_from_name');
	function bb_agency_set_mail_from_name( $name = null ) {
		return is_null($name) ? bb_agency_get_option('bb_agency_option_agencyname') : $name;
	}


/****************************************************************/
//Uninstall
	function bb_agency_uninstall() {
		
		register_uninstall_hook(__FILE__, 'bb_agency_uninstall_action');
		function bb_agency_uninstall_action() {
			//delete_option('create_my_taxonomies');
		}
	
		// Drop the tables
		global $wpdb;	// Required for all WordPress database manipulations
	
		$wpdb->query("DROP TABLE " . table_agency_casting);
		$wpdb->query("DROP TABLE " . table_agency_profile);
		$wpdb->query("DROP TABLE " . table_agency_profile_media);
		$wpdb->query("DROP TABLE " . table_agency_data_gender);
		$wpdb->query("DROP TABLE " . table_agency_rel_taxonomy);
		$wpdb->query("DROP TABLE " . table_agency_data_type);
		$wpdb->query("DROP TABLE " . table_agency_data_talent);
		$wpdb->query("DROP TABLE " . table_agency_data_genre);
		$wpdb->query("DROP TABLE " . table_agency_data_ability);
		$wpdb->query("DROP TABLE " . table_agency_customfields);
		$wpdb->query("DROP TABLE " . table_agency_customfield_mux);
		$wpdb->query("DROP TABLE " . table_agency_searchsaved);
		$wpdb->query("DROP TABLE " . table_agency_searchsaved_mux);

		// Final Cleanup
		delete_option('bb_agency_options');
		
		$thepluginfile = "bb-agency/bb-agency.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", bb_agency_TEXTDOMAIN) ."</p><h1>". __("One More Step", bb_agency_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", bb_agency_TEXTDOMAIN) ."</a></h1></div>";
		die();
	}
?>