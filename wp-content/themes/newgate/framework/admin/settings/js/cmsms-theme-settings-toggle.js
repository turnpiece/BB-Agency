/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Admin Panel Toggles Scripts
 * Created by CMSMasters
 * 
 */


jQuery(document).ready(function () { 
	/* General '404' Tab Fields Load */
	if (jQuery('#newgate_error_sitemap_button').is(':not(:checked)')) {
		jQuery('#newgate_error_sitemap_link').closest('tr').hide();
	}
	
	/* General '404' Tab Fields Change */
	jQuery('#newgate_error_sitemap_button').bind('change', function () { 
		if (jQuery(this).is(':checked')) {
			jQuery('#newgate_error_sitemap_link').closest('tr').show();
		} else {
			jQuery('#newgate_error_sitemap_link').closest('tr').hide();
		}
	} );
	
	
	
	/* General 'SEO' Tab Fields Load */
	if (jQuery('#newgate_seo').is(':not(:checked)')) {
		jQuery('#newgate_seo_title').closest('tr').hide();
		jQuery('#newgate_seo_description').closest('tr').hide();
		jQuery('#newgate_seo_keywords').closest('tr').hide();
	}
	
	/* General 'SEO' Tab Fields Change */
	jQuery('#newgate_seo').bind('change', function () { 
		if (jQuery(this).is(':checked')) {
			jQuery('#newgate_seo_title').closest('tr').show();
			jQuery('#newgate_seo_description').closest('tr').show();
			jQuery('#newgate_seo_keywords').closest('tr').show();
		} else {
			jQuery('#newgate_seo_title').closest('tr').hide();
			jQuery('#newgate_seo_description').closest('tr').hide();
			jQuery('#newgate_seo_keywords').closest('tr').hide();
		}
	} );
	
	
	
	/* Appearance 'Background' Boxed Version Load */
	if (jQuery('#newgate_boxed_version').is(':not(:checked)')) {
		jQuery('label[for="newgate_bg_col"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_img_enable"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_img"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_rep"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_pos"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_att"]').closest('tr').hide();
	}
	
	
	
	/* Appearance 'Background' Boxed Version Change */
	jQuery('#newgate_boxed_version').bind('change', function () { 
		if (jQuery('#newgate_boxed_version').is(':checked')) {
			jQuery('label[for="newgate_bg_col"]').closest('tr').show();
			jQuery('label[for="newgate_bg_img_enable"]').closest('tr').show();
			jQuery('label[for="newgate_bg_img"]').closest('tr').show();
			jQuery('label[for="newgate_bg_rep"]').closest('tr').show();
			jQuery('label[for="newgate_bg_pos"]').closest('tr').show();
			jQuery('label[for="newgate_bg_att"]').closest('tr').show();
		} else {
			jQuery('label[for="newgate_bg_col"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_img_enable"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_img"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_rep"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_pos"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_att"]').closest('tr').hide();
		}
	} );
	
	
	
	/* Appearance 'Background' Tab Fields Load */
	if (jQuery('#newgate_bg_img_enable').is(':not(:checked)')) {
		jQuery('#newgate_bg_img').closest('tr').hide();
		jQuery('label[for="newgate_bg_rep"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_pos"]').closest('tr').hide();
		jQuery('label[for="newgate_bg_att"]').closest('tr').hide();
	}
	
	/* Appearance 'Background' Tab Fields Change */
	jQuery('#newgate_bg_img_enable').bind('change', function () { 
		if (jQuery('#newgate_bg_img_enable').is(':checked')) {
			jQuery('#newgate_bg_img').closest('tr').show();
			jQuery('label[for="newgate_bg_rep"]').closest('tr').show();
			jQuery('label[for="newgate_bg_pos"]').closest('tr').show();
			jQuery('label[for="newgate_bg_att"]').closest('tr').show();
		} else {
			jQuery('#newgate_bg_img').closest('tr').hide();
			jQuery('label[for="newgate_bg_rep"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_pos"]').closest('tr').hide();
			jQuery('label[for="newgate_bg_att"]').closest('tr').hide();
		}
	} );
	
	
	
	/* Appearance 'Footer' Tab Fields Load */
	if (jQuery('input[name^="cmsms_options_newgate_style_footer"]:checked').val() !== 'text') {
		jQuery('#newgate_footer_html').closest('tr').hide();
	}
	
	/* Appearance 'Footer' Tab Fields Change */
	jQuery('input[name^="cmsms_options_newgate_style_footer"]').bind('change', function () { 
		if (jQuery('input[name^="cmsms_options_newgate_style_footer"]:checked').val() === 'text') {
			jQuery('#newgate_footer_html').closest('tr').show();
		} else {
			jQuery('#newgate_footer_html').closest('tr').hide();
		}
	} );
	
	
	
	/* Header Checkbox Field Load */
	if (jQuery('#newgate_header_custom_html').is(':not(:checked)')) {
		jQuery('#newgate_header_html').closest('tr').hide();
		jQuery('#newgate_header_custom_html_top').closest('tr').hide();
		jQuery('#newgate_header_custom_html_right').closest('tr').hide();
	}
	if (jQuery('#newgate_header_social').closest('tr').hide()) {
		jQuery('#newgate_header_social_top').closest('tr').hide();
		jQuery('#newgate_header_social_right').closest('tr').hide();
	}
	
	/* Header Checkbox Field Change */
	jQuery('#newgate_header_custom_html').bind('change', function () { 
		if (jQuery('#newgate_header_custom_html').is(':not(:checked)')) {
			jQuery('#newgate_header_html').closest('tr').hide();
			jQuery('#newgate_header_custom_html_top').closest('tr').hide();
			jQuery('#newgate_header_custom_html_right').closest('tr').hide();
		} else {
			jQuery('#newgate_header_html').closest('tr').show();
			jQuery('#newgate_header_custom_html_top').closest('tr').show();
			jQuery('#newgate_header_custom_html_right').closest('tr').show();
		}
	} );
	jQuery('#newgate_header_social').bind('change', function () { 
		if (jQuery('#newgate_header_social').is(':not(:checked)')) {
			jQuery('#newgate_header_social_top').closest('tr').hide();
			jQuery('#newgate_header_social_right').closest('tr').hide();
		} else {
			jQuery('#newgate_header_social_top').closest('tr').show();
			jQuery('#newgate_header_social_right').closest('tr').show();
		}
	} );
	
	
	
	/* Logo 'Text Logo' Tab Fields Load */
	if (jQuery('#newgate_text_logo').is(':not(:checked)')) {
		jQuery('#newgate_text_logo_title').closest('tr').hide();
		jQuery('#newgate_text_logo_subtitle').closest('tr').hide();
		jQuery('#newgate_text_logo_subtitle_text').closest('tr').hide();
	} else {
		if (jQuery('#newgate_text_logo_subtitle').is(':not(:checked)')) {
			jQuery('#newgate_text_logo_subtitle_text').closest('tr').hide();
		}
	}
	
	/* Logo 'Text Logo' Tab Fields Change */
	jQuery('#newgate_text_logo').bind('change', function () { 
		if (jQuery(this).is(':checked')) {
			jQuery('#newgate_text_logo_title').closest('tr').show();
			jQuery('#newgate_text_logo_subtitle').closest('tr').show();
			
			if (jQuery('#newgate_text_logo_subtitle').is(':checked')) {
				jQuery('#newgate_text_logo_subtitle_text').closest('tr').show();
			}
		} else {
			jQuery('#newgate_text_logo_title').closest('tr').hide();
			jQuery('#newgate_text_logo_subtitle').closest('tr').hide();
			jQuery('#newgate_text_logo_subtitle_text').closest('tr').hide();
		}
	} );
	
	/* Logo 'Text Logo' Tab 'Logo Subtitle' Field Change */
	jQuery('#newgate_text_logo_subtitle').bind('change', function () { 
		if (jQuery(this).is(':checked')) {
			jQuery('#newgate_text_logo_subtitle_text').closest('tr').show();
		} else {
			jQuery('#newgate_text_logo_subtitle_text').closest('tr').hide();
		}
	} );
	
	
	
	/* Logo 'Favicon' Tab Fields Load */
	if (jQuery('#newgate_favicon').is(':not(:checked)')) {
		jQuery('#newgate_favicon_url').closest('tr').hide();
	}
	
	/* Logo 'Favicon' Tab Fields Change */
	jQuery('#newgate_favicon').bind('change', function () { 
		if (jQuery(this).is(':checked')) {
			jQuery('#newgate_favicon_url').closest('tr').show();
		} else {
			jQuery('#newgate_favicon_url').closest('tr').hide();
		}
	} );
} );

