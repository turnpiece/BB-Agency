/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Theme Customizer Scripts
 * Created by CMSMasters
 * 
 */


(function ($) { 
	// General Options Theme Color
	wp.customize('cmsms_options_newgate_general[newgate_theme_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_theme_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_theme_color"> ' + 
				'.cmsms_price, ' + 
				'a.logo:hover, ' + 
				'.color_3 {color:' + newval + ';} ' + 
				'#slide_top:hover, ' + 
				'q:before, ' + 
				'blockquote:before, ' + 
				'.percent_item_colored, ' + 
				'.table thead th, ' + 
				'span.dropcap2, ' + 
				'.post .cmsms_post_format_img, ' + 
				'.project_navi a[rel="prev"]:hover:before, ' + 
				'.project_navi a[rel="next"]:hover:before, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.related_posts > ul li a:hover, ' + 
				'.related_posts > ul li a.current, ' + 
				'.tabs li a:hover, ' + 
				'.tabs li.current a, ' + 
				'.related_posts > ul li a.current, ' + 
				'#navigation > li.current_page_item > a:before, ' + 
				'#navigation > li.current-menu-ancestor > a:before, ' + 
				'#navigation > li.dropdown:hover > a:before, ' + 
				'#navigation > li.menu-item-type-custom:hover > a:before, ' + 
				'.tour > li:hover a, ' + 
				'.tour > li.current a, ' + 
				'.accordion .tog:hover, ' + 
				'.accordion .tog.current, ' + 
				'.togg .tog:hover, ' + 
				'.togg .tog.current, ' + 
				'.wrap_project .portfolio_rollover, ' + 
				'.tl-content:before, ' + 
				'.responsive_nav, ' + 
				'.tparrows.default:hover, ' + 
				'.ls-newgate .ls-nav-prev:hover, ' + 
				'.ls-newgate .ls-nav-next:hover, ' + 
				'.tp-bullets.round .bullet:hover, ' + 
				'.tp-bullets.round .bullet.selected, ' + 
				'.ls-newgate .ls-bottom-slidebuttons a.ls-nav-active, ' + 
				'.ls-newgate .ls-bottom-slidebuttons a:hover, ' + 
				'.cmsms_content_prev_slide:hover span, ' + 
				'.cmsms_content_next_slide:hover span, ' + 
				'.blog .post .cmsms_content_prev_slide:hover span, ' + 
				'.blog .post .cmsms_content_next_slide:hover span, ' + 
				'.cmsms_content_slider_parent ul.cmsms_slides_nav li.active a, ' + 
				'.cmsms_content_slider_parent ul.cmsms_slides_nav li:hover a {background-color:' + newval + ';} ' + 
				'@media only screen and (max-width: 1024px) {' + 
					'#navigation li:hover > a, ' + 
					'#navigation li > a:hover > span:after, ' + 
					'#navigation li > a:hover > span:before, ' + 
					'#navigation li.menu-item-type-custom:hover > a > span:after, ' + 
					'#navigation li.menu-item-type-custom:hover > a > span:before, ' + 
					'#navigation li.dropdown:hover > a > span:after, ' + 
					'#navigation li.dropdown:hover > a > span:before {background-color:' + newval + ';} ' + 
				'}' + 
				'.pj_sort a:hover:before, ' + 
				'.pj_filter_container:hover a.pj_cat_filter:before, ' + 
				'.pj_sort a.current:before { ' + 
					'background-color:' + newval + '; ' + 
					'border-color:' + newval + ';' + 
				'} ' + 
				'@media only screen and (max-width: 1024px) {' + 
					'#navigation li:hover > a:before, ' + 
					'#navigation li:hover > a, ' + 
					'#navigation li.menu-item-type-custom.current_page_item:hover > a:before, ' + 
					'#navigation li.menu-item-type-custom.current-menu-ancestor:hover > a:before, ' + 
					'#navigation li.menu-item-type-custom.current_page_item > a:before, ' + 
					'#navigation li.menu-item-type-custom.current-menu-ancestor > a:before, ' + 
					'#navigation li.dropdown.current_page_item:hover > a:before, ' + 
					'#navigation li.dropdown.current-menu-ancestor:hover > a:before, ' + 
					'#navigation li.dropdown.current_page_item > a:before, ' + 
					'#navigation li.dropdown.current-menu-ancestor > a:before { ' + 
						'background-color:' + newval + '; ' + 
						'border-color:' + newval + '; ' + 
					'}' + 
				'}' + 
				'code, ' + 
				'.cmsms_pricing_table .title {border-top-color:' + newval + ';} ' + 
				'.cmsmsLike.active:after, ' + 
				'#bottom input:focus, ' + 
				'#bottom textarea:focus, ' + 
				'#bottom select:focus {border-color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Header Options Header Height
	wp.customize('cmsms_options_newgate_style_header[newgate_header_height]', function (value) { 
		value.bind(function (newval) { 
			$('#header > .header_inner').css('height', newval + 'px');
		} );
	} );
	
	// Header Options Header Navigation Top Position
	wp.customize('cmsms_options_newgate_style_header[newgate_header_nav_top]', function (value) { 
		value.bind(function (newval) { 
			$('#header nav').css('top', newval + 'px');
		} );
	} );
	
	// Header Options Header Navigation Right Position
	wp.customize('cmsms_options_newgate_style_header[newgate_header_nav_right]', function (value) { 
		value.bind(function (newval) { 
			$('#header nav').css('right', newval + 'px');
		} );
	} );
	
	
	
	// Background Options Background Color
	wp.customize('cmsms_options_newgate_style_bg[newgate_bg_col]', function (value) { 
		value.bind(function (newval) { 
			$('body').css('background-color', newval);
		} );
	} );
	
	// Background Options Background Image
	wp.customize('cmsms_options_newgate_style_bg[newgate_bg_img]', function (value) { 
		value.bind(function (newval) { 
			$('body').css('background-image', 'url(' + newval + ')');
		} );
	} );
	
	// Background Options Background Repeat
	wp.customize('cmsms_options_newgate_style_bg[newgate_bg_rep]', function (value) { 
		value.bind(function (newval) { 
			$('body').css('background-repeat', newval);
		} );
	} );
	
	// Background Options Background Position
	wp.customize('cmsms_options_newgate_style_bg[newgate_bg_pos]', function (value) { 
		value.bind(function (newval) { 
			$('body').css('background-position', newval);
		} );
	} );
	
	// Background Options Background Attachment
	wp.customize('cmsms_options_newgate_style_bg[newgate_bg_att]', function (value) { 
		value.bind(function (newval) { 
			$('body').css('background-attachment', newval);
		} );
	} );
	
	
	
	// Logo Options Custom Logo Image Width
	wp.customize('cmsms_options_newgate_logo_image[newgate_logo_width]', function (value) { 
		value.bind(function (newval) { 
			$('#page .header_inner > a.logo, #page .header_inner > a.logo > img').css('width', newval + 'px');
		} );
	} );
	
	// Logo Options Custom Logo Image Height
	wp.customize('cmsms_options_newgate_logo_image[newgate_logo_height]', function (value) { 
		value.bind(function (newval) { 
			$('#page .header_inner > a.logo, #page .header_inner > a.logo > img').css('height', newval + 'px');
		} );
	} );
	
	// Logo Options Custom Logo Top Position
	wp.customize('cmsms_options_newgate_logo_image[newgate_logo_top]', function (value) { 
		value.bind(function (newval) { 
			$('#page .header_inner > a.logo').css('top', newval + 'px');
		} );
	} );
	
	// Logo Options Custom Logo Left Position
	wp.customize('cmsms_options_newgate_logo_image[newgate_logo_left]', function (value) { 
		value.bind(function (newval) { 
			$('#page .header_inner > a.logo').css('left', newval + 'px');
		} );
	} );
	
	
	
	// Content Font Options System Font
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_system_font"> ' + 
				'body, ' + 
				'li p, ' + 
				'.user_name, ' + 
				'.cmsms_post_full_date, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li, ' + 
				'.cmsms_comments, ' + 
				'.comment-reply-link, ' + 
				'#cancel-comment-reply-link, ' + 
				'input[type="submit"], ' + 
				'table.table th {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Google Font
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_content_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_google_font"> ' + 
				'body, ' + 
				'li p, ' + 
				'.user_name, ' + 
				'.cmsms_post_full_date, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li, ' + 
				'.cmsms_comments, ' + 
				'.comment-reply-link, ' + 
				'#cancel-comment-reply-link, ' + 
				'input[type="submit"], ' + 
				'table.table th {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Font Color
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_font_color"> ' + 
				'body, ' + 
				'.user_name, ' + 
				'.tour > li a, ' + 
				'.tog, ' + 
				'.cmsms_comments, ' + 
				'.cmsms_tl_cat a, ' + 
				'.tl_comments, ' + 
				'.heading_subtitle, ' + 
				'.cmsmsLike {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Font Size
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_font_size"> ' + 
				'body, ' + 
				'li p, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Line Height
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_line_height"> ' + 
				'body, ' + 
				'li p, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_font_weight"> ' + 
				'body, ' + 
				'li p, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Content Font Options Font Style
	wp.customize('cmsms_options_newgate_font_content[newgate_content_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_content_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_content_font_font_style"> ' + 
				'body, ' + 
				'li p, ' + 
				'.blog.opened-article .cmsms_comments_wrap, ' + 
				'.widget_custom_posts_tabs_entries li {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Link Font Options System Font
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_system_font"> ' + 
				'a {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Google Font
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_link_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_google_font"> ' + 
				"a {font-family:'" + newvalResult + "';} " + 
			'</style>');
		} );
	} );
	
	// Link Font Options Font Color
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_font_color"> ' + 
				'a, ' + 
				'.cmsmsLike.active, ' + 
				'.entry-title a:hover, ' + 
				'.cmsms_tl_cat a:hover, ' + 
				'.tl_comments:hover, ' + 
				'.post .entry-title a:hover, ' + 
				'.cmsms_tags a:hover, ' + 
				'.user_name a:hover, ' + 
				'.cmsms_category a:hover, ' + 
				'.more_button:hover, ' + 
				'.cmsms_comments:hover, ' + 
				'.cmsms_info .cmsms_comments:hover, ' + 
				'.jp-playlist-item:hover, ' + 
				'.testimonial .tl_author:hover, ' + 
				'.jta-tweet-timestamp-link:hover, ' + 
				'div.jp-playlist li a.jp-playlist-current {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Font Hover Color
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_hover]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_hover').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_hover"> ' + 
				'a:hover, ' + 
				'.cmsms_tags a, ' + 
				'.user_name a, ' + 
				'.cmsms_category a, ' + 
				'.jta-tweet-timestamp-link {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Font Size
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_font_size"> ' + 
				'a {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Line Height
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_line_height"> ' + 
				'a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_font_weight"> ' + 
				'a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Link Font Options Font Style
	wp.customize('cmsms_options_newgate_font_link[newgate_link_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_link_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_link_font_font_style"> ' + 
				'a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Navigation Title Font Options System Font
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_system_font"> ' + 
				'#navigation > li > a {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Google Font
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_nav_title_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_google_font"> ' + 
				"#navigation > li > a {font-family:'" + newvalResult + "';} " + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Font Color
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_font_color"> ' + 
				'#navigation > li > a {color:' + newval + ';} ' + 
				'@media only screen and (max-width: 1024px) {' + 
					'#navigation ul li a, ' + 
					'#navigation li a {color:' + newval + ';}' + 
				'}' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Font Hover Color
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_hover]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_hover').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_hover"> ' + 
				'#navigation > li.current_page_item > a, ' + 
				'#navigation > li.current-menu-ancestor > a, ' + 
				'#navigation > li:hover > a, ' + 
				'#navigation > li > a:hover {color:' + newval + ';} ' + 
				'@media only screen and (max-width: 1024px) {' + 
					'#navigation li.current_page_item > a, ' + 
					'#navigation li.current-menu-ancestor > a, ' + 
					'#navigation ul li.current_page_item > a, ' + 
					'#navigation ul li.current-menu-ancestor > a {color:' + newval + ';}' + 
				'}' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Font Size
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_font_size"> ' + 
				'#navigation > li > a {font-size:' + newval + 'px;} ' + 
				'#navigation > li.current_page_item > a, ' + 
				'#navigation > li.current-menu-ancestor > a, ' + 
				'#navigation li.current_page_item > a, ' + 
				'#navigation li.current-menu-ancestor > a, ' + 
				'#navigation > li:hover > a, ' + 
				'#navigation > li > a:hover {font-size:' + newval + 'px;} ' + 
				'@media only screen and (max-width: 1024px) { ' + 
				'#navigation > li.current_page_item > a, ' + 
				'#navigation > li.current-menu-ancestor > a, ' + 
				'#navigation li.current_page_item > a, ' + 
				'#navigation li.current-menu-ancestor > a, ' + 
				'#navigation > li:hover > a, ' + 
				'#navigation > li > a:hover {font-size:' + newval + 'px;} ' + 
				'}' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Line Height
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_line_height"> ' + 
				'#navigation > li > a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_font_weight"> ' + 
				'#navigation > li > a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Title Font Options Font Style
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_title_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_title_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_title_font_font_style"> ' + 
				'#navigation > li > a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Navigation Dropdown Font Options System Font
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_system_font"> ' + 
				'#navigation ul li a {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Google Font
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_nav_dropdown_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_google_font"> ' + 
				"#navigation ul li a {font-family:'" + newvalResult + "';} " + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Font Color
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_font_color"> ' + 
				'#navigation ul li > a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Font Hover Color
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_hover]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_hover').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_hover"> ' + 
				'#navigation ul li.current_page_item > a, ' + 
				'#navigation ul li.current-menu-ancestor > a, ' + 
				'#navigation ul li:hover > a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Font Size
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_font_size"> ' + 
				'#navigation ul li a {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Line Height
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_line_height"> ' + 
				'#navigation ul li a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_font_weight"> ' + 
				'#navigation ul li a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Navigation Dropdown Font Options Font Style
	wp.customize('cmsms_options_newgate_font_nav[newgate_nav_dropdown_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_nav_dropdown_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_nav_dropdown_font_font_style"> ' + 
				'#navigation ul li a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H1 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_system_font"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments, ' + 
				'.cmsms_info .cmsms_comments_wrap, ' + 
				'.blog .post .published, ' + 
				'.cmsms_price_outer, ' + 
				'.more_button, ' + 
				'.pj_sort a[name="pj_name"], ' + 
				'.pj_sort a[name="pj_date"], ' + 
				'a.pj_cat_filter {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h1_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_google_font"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments, ' + 
				'.cmsms_info .cmsms_comments_wrap, ' + 
				'.blog .post .published, ' + 
				'.cmsms_price_outer, ' + 
				'.more_button {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_font_color"> ' + 
				'h1, ' + 
				'.logo, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_font_size"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_line_height"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_font_weight"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H1 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h1[newgate_h1_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h1_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h1_font_font_style"> ' + 
				'h1, ' + 
				'h1 a, ' + 
				'.logo .title, ' + 
				'.cmsms_page_month, ' + 
				'.cmsms_page_day, ' + 
				'.cmsms_post_month, ' + 
				'.cmsms_post_day, ' + 
				'.cmsms_info .cmsms_comments {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H2 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_system_font"> ' + 
				'h2, ' + 
				'h2 a {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h2_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_google_font"> ' + 
				'h2, ' + 
				'h2 a {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_font_color"> ' + 
				'h2, ' + 
				'h2 a, ' + 
				'.post .entry-title a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_font_size"> ' + 
				'h2, ' + 
				'h2 a {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_line_height"> ' + 
				'h2, ' + 
				'h2 a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_font_weight"> ' + 
				'h2, ' + 
				'h2 a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H2 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h2[newgate_h2_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h2_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h2_font_font_style"> ' + 
				'h2, ' + 
				'h2 a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H3 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_system_font"> ' + 
				'h3, ' + 
				'h3 a, ' + 
				'.colored_button {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h3_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_google_font"> ' + 
				'h3, ' + 
				'h3 a {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_font_color"> ' + 
				'h3, ' + 
				'h3 a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_font_size"> ' + 
				'h3, ' + 
				'h3 a {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_line_height"> ' + 
				'h3, ' + 
				'h3 a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_font_weight"> ' + 
				'h3, ' + 
				'h3 a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H3 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h3[newgate_h3_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h3_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h3_font_font_style"> ' + 
				'h3, ' + 
				'h3 a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H4 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_system_font"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h4_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_google_font"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_font_color"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.project .entry-title a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_font_size"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_line_height"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_font_weight"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H4 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h4[newgate_h4_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h4_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h4_font_font_style"> ' + 
				'h4, ' + 
				'h4 a, ' + 
				'.cmsms_sitemap > li > a, ' + 
				'.cmsms_page_year, ' + 
				'.cmsms_post_year {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H5 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_system_font"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h5_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_google_font"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_font_color"> ' + 
				'h5, ' + 
				'h5 a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_font_size"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_line_height"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_font_weight"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H5 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h5[newgate_h5_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h5_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h5_font_font_style"> ' + 
				'h5, ' + 
				'h5 a, ' + 
				'.button, ' + 
				'.pricing_button, ' + 
				'.button_small, ' + 
				'.button_medium, ' + 
				'.button_large, ' + 
				'input[type="submit"], ' + 
				'.cmsms_period {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// H6 Heading Font Options System Font
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_system_font"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Google Font
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_h6_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_google_font"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Font Color
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_font_color"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_currency, ' + 
				'.cmsms_coins, ' + 
				'.cmsms_period, ' + 
				'.person_subtitle, ' + 
				'.testimonial .tl_author, ' + 
				'.testimonial .tl_company, ' + 
				'#cmsms_latest_bottom_tweets, ' + 
				'.project_navi a:hover, ' + 
				'.cmsms_share:hover, ' + 
				'.comment-reply-link:hover, ' + 
				'#cancel-comment-reply-link:hover, ' + 
				'.comment-edit-link:hover, ' + 
				'#cmsms_latest_bottom_tweets a:hover {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Font Size
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_font_size"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Line Height
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_line_height"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_font_weight"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// H6 Heading Font Options Font Style
	wp.customize('cmsms_options_newgate_font_h6[newgate_h6_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_h6_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_h6_font_font_style"> ' + 
				'h6, ' + 
				'h6 a, ' + 
				'.cmsms_date_title, ' + 
				'.testimonial .tl_author, ' + 
				'.cmsms_info .cmsms_comments_wrap {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Blockquote Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_system_font"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_quote_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_google_font"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_font_color"> ' + 
				'.color_2, ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_font_size"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_line_height"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_font_weight"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Blockquote Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_quote_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_quote_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_quote_font_font_style"> ' + 
				'blockquote, ' + 
				'.post.format-aside .entry-header > .entry-header-inner, ' + 
				'q {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Dropcap Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_system_font"> ' + 
				'span.dropcap {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_dropcap_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_google_font"> ' + 
				'span.dropcap {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_font_color"> ' + 
				'span.dropcap {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_font_size"> ' + 
				'span.dropcap {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_line_height"> ' + 
				'span.dropcap {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_font_weight"> ' + 
				'span.dropcap {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Dropcap Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_dropcap_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_dropcap_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_dropcap_font_font_style"> ' + 
				'span.dropcap {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Code Tag Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_system_font"> ' + 
				'code {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_code_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_google_font"> ' + 
				'code {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_font_color"> ' + 
				'code {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_font_size"> ' + 
				'code {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_line_height"> ' + 
				'code {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_font_weight"> ' + 
				'code {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Code Tag Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_code_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_code_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_code_font_font_style"> ' + 
				'code {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Small Tag Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_system_font"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_small_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_google_font"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_font_color"> ' + 
				'small {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_font_size"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_line_height"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_font_weight"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Small Tag Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_small_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_small_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_small_font_font_style"> ' + 
				'small, ' + 
				'small a, ' + 
				'.cmsms_breadcrumbs, ' + 
				'.cmsms_breadcrumbs a, ' + 
				'.user_name, ' + 
				'.cmsms_category, ' + 
				'.cmsms_details li, ' + 
				'.cmsms_tl_cat, ' + 
				'tl_comments_wrap, ' + 
				'.testimonial .published, ' + 
				'.cmsms_portfolio_full_date abbr, ' + 
				'.post_type_shortcode .entry-header .published, ' + 
				'.widget_custom_posts_tabs_entries .tabs > li a span, ' + 
				'.widget_custom_recent_testimonials_entries .tl_author, ' + 
				'.portfolio_page .cmsms_portfolio_full_date, ' + 
				'.portfolio_page .entry-meta .cmsms_category, ' + 
				'.portfolio_page .entry-meta .cmsms_category a, ' + 
				'.table td {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Text Fields Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_system_font"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_input_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_google_font"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_font_color"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select, ' + 
				'select option {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_font_size"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_line_height"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_font_weight"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Text Fields Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_input_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_input_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_input_font_font_style"> ' + 
				'input, ' + 
				'textarea, ' + 
				'select {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	
	
	// Footer Font Options System Font
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_system_font]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_system_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_system_font"> ' + 
				'#footer, ' + 
				'#footer a {font-family:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Google Font
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_google_font]', function (value) { 
		value.bind(function (newval) { 
			var newvalArray = newval.split(':'), 
				newvalResult = newvalArray[0].replace(/\+/g, ' ');
			
			
			$('html > head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + newval + '" type="text/css" />');
			
			
			$('html > head').find('style#newgate_footer_font_google_font').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_google_font"> ' + 
				'#footer, ' + 
				'#footer a {font-family:' + newvalResult + ';} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Font Color
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_font_color]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_font_color').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_font_color"> ' + 
				'.bottom_inner a, ' + 
				'#footer, ' + 
				'#footer a {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Footer Links Font Hover Color
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_hover]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_hover').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_hover"> ' + 
				'.bottom_inner a:hover, ' + 
				'#footer a:hover, ' + 
				'#footer h1 a:hover, ' + 
				'#footer h2 a:hover, ' + 
				'#footer h3 a:hover, ' + 
				'#footer h4 a:hover, ' + 
				'#footer h5 a:hover, ' + 
				'#footer h6 a:hover {color:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Font Size
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_font_size]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_font_size').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_font_size"> ' + 
				'#footer, ' + 
				'#footer a {font-size:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Line Height
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_line_height]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_line_height').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_line_height"> ' + 
				'#footer, ' + 
				'#footer a {line-height:' + newval + 'px;} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Font Weight
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_font_weight]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_font_weight').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_font_weight"> ' + 
				'#footer, ' + 
				'#footer a {font-weight:' + newval + ';} ' + 
			'</style>');
		} );
	} );
	
	// Footer Font Options Font Style
	wp.customize('cmsms_options_newgate_font_other[newgate_footer_font_font_style]', function (value) { 
		value.bind(function (newval) { 
			$('html > head').find('style#newgate_footer_font_font_style').remove();
			
			
			$('html > head').append('<style type="text/css" id="newgate_footer_font_font_style"> ' + 
				'#footer, ' + 
				'#footer a {font-style:' + newval + ';} ' + 
			'</style>');
		} );
	} );
} ) (jQuery);

