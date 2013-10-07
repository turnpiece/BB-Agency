<?php 
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Admin Panel Icons Options
 * Created by CMSMasters
 * 
 */


function cmsms_options_icon_tabs() {
	$tabs = array();
	
	$tabs['heading'] = __('Heading Icons', 'cmsmasters');
	$tabs['social'] = __('Social Icons', 'cmsmasters');
	
	return $tabs;
}


function cmsms_options_icon_sections() {
	$tab = cmsms_get_the_tab();
	
	switch ($tab) {
	case 'heading':
		$sections = array();
		
		$sections['heading_section'] = __('Heading Icons', 'cmsmasters');
		
		break;
	case 'social':
		$sections = array();
		
		$sections['social_section'] = __('Social Icons', 'cmsmasters');
		
		break;
	}
	
	return $sections;	
} 


function cmsms_options_icon_fields($set_tab = false) {
	if ($set_tab) {
		$tab = $set_tab;
	} else {
		$tab = cmsms_get_the_tab();
	}
	
	$options = array();
	
	switch ($tab) {
	case 'heading':
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => CMSMS_SHORTNAME . '_heading_icons', 
			'title' => __('Heading Icons', 'cmsmasters'), 
			'desc' => '', 
			'type' => 'heading', 
			'std' => '' 
		);
		
		break;
	case 'social':
		$options[] = array( 
			'section' => 'social_section', 
			'id' => CMSMS_SHORTNAME . '_social_icons', 
			'title' => __('Social Icons', 'cmsmasters'), 
			'desc' => '', 
			'type' => 'social', 
			'std' => array( 
				get_template_directory_uri() . '/img/social/vimeo.png|#|true', 
				get_template_directory_uri() . '/img/social/twitter.png|#|true', 
				get_template_directory_uri() . '/img/social/flickr.png|#|true', 
				get_template_directory_uri() . '/img/social/facebook.png|#|true' 
			) 
		);
		
		break;
	}
	
	return $options;	
}

