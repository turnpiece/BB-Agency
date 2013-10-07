<?php
/*
Plugin Name: Contact Form 7 Honeypot
Plugin URI: http://www.daobydesign.com/free-plugins/honeypot-module-for-contact-form-7-wordpress-plugin
Description: Add honeypot functionality to the popular Contact Form 7 plugin.
Author: Dao By Design
Author URI: http://www.daobydesign.com
Version: 1.1
*/

/*  Copyright 2013  Dao By Design  (email : info@daobydesign.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	---
	
	Thanks to Katz Web Services, Inc. (http://www.katzwebservices.com) for basic plugin structure.
*/

add_action('plugins_loaded', 'wpcf7_honeypot_loader', 10);

function wpcf7_honeypot_loader() {
	global $pagenow;
	if (function_exists('wpcf7_add_shortcode')) {
		wpcf7_add_shortcode( 'honeypot', 'wpcf7_honeypot_shortcode_handler', true );
	} else {
		if ($pagenow != 'plugins.php') { return; }
		add_action('admin_notices', 'cfhiddenfieldserror');
		wp_enqueue_script('thickbox');
		function cfhiddenfieldserror() {
			$out = '<div class="error" id="messages"><p>';
			if(file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php')) {
				$out .= 'The Contact Form 7 is installed, but <strong>you must activate Contact Form 7</strong> below for the Honeypot Module to work.';
			} else {
				$out .= 'The Contact Form 7 plugin must be installed for the Honeypot Module to work. <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins&TB_iframe=true&width=600&height=550').'" class="thickbox" title="Contact Form 7">Install Now.</a>';
			}
			$out .= '</p></div>';	
			echo $out;
		}
	}
}


/**
** A base module for [honeypot]
**/

/* Shortcode handler */
function wpcf7_honeypot_shortcode_handler( $tag ) {
	global $wpcf7_contact_form;
	
	if ( ! is_array( $tag ) )
		return '';

	$type = $tag['type'];
	$name = $tag['name'];

	if ( empty( $name ) )
		return '';

	$validation_error = '';
	if ( is_a( $wpcf7_contact_form, 'WPCF7_ContactForm' ) )
		$validation_error = $wpcf7_contact_form->validation_error( $name );

 	$html = '<label for="email-wpcf7-hp"><small>Leave this field empty.</small></label>
		<input class="wpcf7-text"  type="text" name="email-wpcf7-hp" id="email-wpcf7-hp" value="" size="40" tabindex="3" />';
   
   
	$html = '<span class="wpcf7-form-control-wrap hidden' . $name . '" style="display:none;">' . $html . $validation_error . '</span>';

	return $html;
}


/* honeypot filter */
add_filter( 'wpcf7_validate_honeypot', 'wpcf7_honeypot_filter' ,10,2);

function wpcf7_honeypot_filter ($result, $tag) {
	global $wpcf7_contact_form;
	global $user_ID;

	$type = $tag['type'];
	$name = $tag['name'];


	$honeypot = $_POST['email-wpcf7-hp'];
	if ( $honeypot != '' ) {
		$result['valid'] = false;
		//$result['reason'][$name] = wpcf7_get_message( 'Apologies, there was a problem with your submission.' );
	}

	return $result;
}


/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_honeypot', 35 );

function wpcf7_add_tag_generator_honeypot() {
	if (function_exists('wpcf7_add_tag_generator')) {
		wpcf7_add_tag_generator( 'honeypot', __( 'Honeypot', 'wpcf7' ),	'wpcf7-tg-pane-honeypot', 'wpcf7_tg_pane_honeypot' );
	}
}

function wpcf7_tg_pane_honeypot( &$contact_form ) { ?>
	<div id="wpcf7-tg-pane-honeypot" class="hidden">
		<form action="">
			<table>
				<tr><td>
					<?php echo esc_html( __( 'Name', 'wpcf7' ) ); ?>
					<br /><input type="text" name="name" class="tg-name oneline" />
					<br /><em><small><?php echo esc_html( __( 'For better security, change "honeypot" to something less bot-recognizable.', 'wpcf7' ) ); ?></small></em>
				</td><td></td></tr>
			</table>
			
			<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'wpcf7' ) ); ?><br /><input type="text" name="honeypot" class="tag" readonly="readonly" onfocus="this.select()" /></div>
		</form>
	</div>

<?php }

?>