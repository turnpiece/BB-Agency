<?php 
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Fonts & Colors Settings File
 * Created by CMSMasters
 * 
 */


header('Content-type: text/css');


require('../../../../wp-load.php');


$cmsms_option = cmsms_get_global_options();

?>

/* ===================> Fonts <================== */

/* ====> Content <==== */

body,
li p,
.user_name, 
.cmsms_post_full_date, 
.blog.opened-article .cmsms_comments_wrap, 
.widget_custom_posts_tabs_entries li {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_content_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_content_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_content_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_content_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_content_font_font_style']; ?>;
}

.cmsms_comments,
.comment-reply-link, 
#cancel-comment-reply-link, 
input[type="submit"] {
	font-family:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo (($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_content_font_system_font'];
	?>;
}

table.table th {
	font-family:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_content_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo (($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_content_font_system_font'];
	?>;
	font-weight:normal;
}

.cmsms_info .cmsms_comments_wrap,
.blog .post .published,
.cmsms_price_outer,
.more_button, 
.pj_sort a[name="pj_name"], 
.pj_sort a[name="pj_date"], 
a.pj_cat_filter {
	font-family:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h1_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h1_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h1_font_system_font'];
	?>;
}

/* ====> Links <==== */

a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_link_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_link_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_link_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_link_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_font_style']; ?>;
}

/* ====> Navigation <==== */

#navigation > li > a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_style']; ?>;
}

#navigation ul li a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_font_style']; ?>;
}


/* ====> Headings <==== */

h1,
h1 a,
.logo .title, 
.cmsms_page_month, 
.cmsms_page_day, 
.cmsms_post_month, 
.cmsms_post_day, 
.cmsms_info .cmsms_comments {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h1_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h1_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h1_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h1_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h1_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h1_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h1_font_font_style']; ?>;
}

h2,
h2 a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h2_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h2_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h2_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h2_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h2_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h2_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h2_font_font_style']; ?>;
}

h3,
h3 a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h3_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h3_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h3_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h3_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h3_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h3_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h3_font_font_style']; ?>;
}

h4,
h4 a,
.cmsms_sitemap > li > a, 
.cmsms_page_year, 
.cmsms_post_year {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h4_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h4_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h4_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h4_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h4_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h4_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h4_font_font_style']; ?>;
}

h5,
h5 a,
.button, 
.pricing_button, 
.button_small, 
.button_medium, 
.button_large, 
input[type="submit"], 
.cmsms_period {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h5_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h5_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h5_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h5_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h5_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h5_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h5_font_font_style']; ?>;
}

h6,
h6 a, 
.cmsms_date_title, 
.testimonial .tl_author, 
.cmsms_info .cmsms_comments_wrap {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_h6_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_h6_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_h6_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_h6_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_h6_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h6_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h6_font_font_style']; ?>;
}


/* ====> Other <==== */

blockquote, 
.post.format-aside .entry-header > .entry-header-inner, 
q {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_quote_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_quote_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_quote_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_quote_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_quote_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_quote_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_quote_font_font_style']; ?>;
}

q:before, 
blockquote:before, 
.tl-content:after {font-family:'Headland One Regular';}

span.dropcap {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_font_style']; ?>;
}

code {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_code_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_code_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_code_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_code_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_code_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_code_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_code_font_font_style']; ?>;
}

small,
small a, 
.cmsms_breadcrumbs, 
.cmsms_breadcrumbs a, 
.user_name, 
.cmsms_category, 
.cmsms_details li, 
.cmsms_tl_cat, 
.testimonial .published, 
.cmsms_portfolio_full_date abbr, 
.post_type_shortcode .entry-header .published, 
.widget_custom_posts_tabs_entries .tabs > li a span, 
.widget_custom_recent_testimonials_entries .tl_author, 
.portfolio_page .cmsms_portfolio_full_date, 
.portfolio_page .entry-meta .cmsms_category, 
.portfolio_page .entry-meta .cmsms_category a, 
.table td {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_small_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_small_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_small_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_small_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_small_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_small_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_small_font_font_style']; ?>;
}

input, 
textarea, 
select {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_input_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_input_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_input_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_input_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_input_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_input_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_input_font_font_style']; ?>;
}

#footer,
#footer a {
	font:<?php 
		if ($cmsms_option[CMSMS_SHORTNAME . '_footer_font_google_font'] != '') {
			$google_font_array = explode(':', $cmsms_option[CMSMS_SHORTNAME . '_footer_font_google_font']);
			
			$google_font = str_replace('+', ' ', $google_font_array[0]);
		} else {
			$google_font = '';
		}
		
		echo $cmsms_option[CMSMS_SHORTNAME . '_footer_font_font_size'] . 
		'px/' . 
		$cmsms_option[CMSMS_SHORTNAME . '_footer_font_line_height'] . 
		'px ' . 
		(($google_font != '') ? "'" . $google_font . "', " : '') . 
		$cmsms_option[CMSMS_SHORTNAME . '_footer_font_system_font'];
	?>;
	font-weight:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_footer_font_font_weight']; ?>;
	font-style:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_footer_font_font_style']; ?>;
}

/* ===================> Colors <================== */

/* ====> Content <==== */
	
body, 
.tour > li a, 
.tog, 
.cmsms_comments,
.cmsms_tl_cat a, 
.tl_comments, 
.heading_subtitle, 
.cmsmsLike {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_content_font_font_color']; ?>;
}

/* ====> Links <==== */

a, 
.cmsmsLike.active {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_font_color']; ?>;
}

a:hover, 
.cmsms_tags a, 
.user_name a, 
.cmsms_category a, 
.jta-tweet-timestamp-link {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_hover']; ?>;
}

.entry-title a:hover, 
.cmsms_tl_cat a:hover, 
.tl_comments:hover, 
.post .entry-title a:hover,
.cmsms_tags a:hover, 
.user_name a:hover, 
.cmsms_category a:hover, 
.more_button:hover, 
.cmsms_comments:hover, 
.cmsms_info .cmsms_comments:hover, 
.related_posts_content h6 a:hover, 
.jp-playlist-item:hover, 
.testimonial .tl_author:hover, 
.jta-tweet-timestamp-link:hover, 
div.jp-playlist li a.jp-playlist-current {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_link_font_font_color']; ?>;
}

.color_3, 
.cmsms_price, 
a.logo:hover {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
}

.project_navi a:hover, 
.cmsms_share:hover, 
.comment-reply-link:hover,
#cancel-comment-reply-link:hover, 
.comment-edit-link:hover, 
#cmsms_latest_bottom_tweets a:hover {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h6_font_font_color']; ?>;
}

/* ====> Navigation <==== */

#navigation > li > a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_color']; ?>;
}

#navigation > li.current_page_item > a,
#navigation > li.current-menu-ancestor > a,
#navigation > li:hover > a,
#navigation > li > a:hover {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_hover']; ?>;
}

#navigation ul li > a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_font_color']; ?>;
}

#navigation ul li.current_page_item > a,
#navigation ul li.current-menu-ancestor > a,
#navigation ul li:hover > a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_dropdown_font_hover']; ?>;
}

/* ====> Headings <==== */

h1,
.logo, 
.cmsms_page_month, 
.cmsms_page_day, 
.cmsms_post_month, 
.cmsms_post_day, 
.cmsms_info .cmsms_comments {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h1_font_font_color']; ?>;
}

h2,
h2 a,
.post .entry-title a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h2_font_font_color']; ?>;
}

h3, 
h3 a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h3_font_font_color']; ?>;
}

h4,
h4 a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h4_font_font_color']; ?>;
}

h5, 
h5 a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h5_font_font_color']; ?>;
}

h6, 
h6 a, 
.cmsms_currency, 
.cmsms_coins, 
.cmsms_period, 
.person_subtitle, 
.testimonial .tl_author, 
.testimonial .tl_company, 
#cmsms_latest_bottom_tweets {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_h6_font_font_color']; ?>;
}

/* ====> Other <==== */

.color_2, 
blockquote, 
.post.format-aside .entry-header > .entry-header-inner, 
q {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_quote_font_font_color']; ?>;
}

span.dropcap {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_dropcap_font_font_color']; ?>;
}

code {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_code_font_font_color']; ?>;
}

small {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_small_font_font_color']; ?>;
}

input, 
textarea, 
select,
select option {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_input_font_font_color']; ?>;
}

/* ====> Footer Content <==== */

.bottom_inner a, 
#footer,
#footer a {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_footer_font_font_color']; ?>;
}


/* ====> Footer Links <==== */

.bottom_inner a:hover, 
#footer a:hover,
#footer h1 a:hover, 
#footer h2 a:hover, 
#footer h3 a:hover, 
#footer h4 a:hover, 
#footer h5 a:hover, 
#footer h6 a:hover {
	color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_footer_font_hover']; ?>;
}

/* ===================> Backgrounds and Borders <================== */

#slide_top:hover,
q:before, 
blockquote:before, 
.percent_item_colored,
.table thead th, 
span.dropcap2,
.post .cmsms_post_format_img, 
.project_navi a[rel="prev"]:hover:before, 
.project_navi a[rel="next"]:hover:before, 
.button, 
.pricing_button, 
.button_small, 
.button_medium, 
.button_large, 
input[type="submit"], 
.related_posts > ul li a:hover, 
.related_posts > ul li a.current, 
.tabs li a:hover, 
.tabs li.current a, 
.related_posts > ul li a.current, 
#navigation > li.current_page_item > a:before, 
#navigation > li.current-menu-ancestor > a:before, 
#navigation > li.dropdown:hover > a:before, 
#navigation > li.menu-item-type-custom:hover > a:before, 
.tour > li:hover a, 
.tour > li.current a, 
.tog:hover, 
.tog.current, 
.wrap_project .portfolio_rollover, 
.tl-content:before, 
.responsive_nav, 
.tparrows.default:hover, 
.ls-newgate .ls-nav-prev:hover, 
.ls-newgate .ls-nav-next:hover, 
.tp-bullets.round .bullet:hover, 
.tp-bullets.round .bullet.selected, 
.ls-newgate .ls-bottom-slidebuttons a.ls-nav-active,
.ls-newgate .ls-bottom-slidebuttons a:hover, 
.cmsms_content_prev_slide:hover span,
.cmsms_content_next_slide:hover span, 
.blog .post .cmsms_content_prev_slide:hover span,
.blog .post .cmsms_content_next_slide:hover span,
.cmsms_content_slider_parent ul.cmsms_slides_nav li.active a, 
.cmsms_content_slider_parent ul.cmsms_slides_nav li:hover a {
	background-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
}

.pj_sort a:hover:before, 
.pj_filter_container:hover a.pj_cat_filter:before, 
.pj_sort a.current:before {
	background-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
	border-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
}

code, 
.cmsms_pricing_table .title {
	border-top-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
}

.cmsmsLike.active:after, 
#bottom input:focus, 
#bottom textarea:focus, 
#bottom select:focus {
	border-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
}

/* ---------- Small Tablet & Mobile (Note: Design for a width less than 1024px) ---------- */

@media only screen and (max-width: 1024px) {
	
	#navigation > li.current_page_item > a,
	#navigation > li.current-menu-ancestor > a,
	#navigation li.current_page_item > a,
	#navigation li.current-menu-ancestor > a,
	#navigation > li:hover > a,
	#navigation > li > a:hover {
		font-size:<?php $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_size']; ?>;
	}
	
	#navigation li.current_page_item > a, 
	#navigation li.current-menu-ancestor > a, 
	#navigation li.current_page_item:hover > a, 
	#navigation li.current-menu-ancestor:hover > a, 
	#navigation ul li.current_page_item > a,
	#navigation ul li.current-menu-ancestor > a, 
	#navigation ul li.current_page_item:hover > a,
	#navigation ul li.current-menu-ancestor:hover > a	{
		color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_hover']; ?>;
	}
	
	#navigation ul li > a {
		color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_nav_title_font_font_color']; ?>;
	}
	
	#navigation ul li:hover > a {color:#ffffff;}
	
	#navigation ul li > a {background-color:#fbfbfa;}
	
	#navigation li:hover > a, 
	#navigation li > a:hover > span:after, 
	#navigation li > a:hover > span:before, 
	#navigation li.menu-item-type-custom:hover > a > span:after, 
	#navigation li.menu-item-type-custom:hover > a > span:before, 
	#navigation li.dropdown:hover > a > span:after, 
	#navigation li.dropdown:hover > a > span:before	{
		background-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
	}

	#navigation li:hover > a:before, 
	#navigation ul li a:hover, 
	#navigation ul li:hover > a, 
	#navigation li.menu-item-type-custom.current_page_item:hover > a:before, 
	#navigation li.menu-item-type-custom.current-menu-ancestor:hover > a:before, 
	#navigation li.menu-item-type-custom.current_page_item > a:before, 
	#navigation li.menu-item-type-custom.current-menu-ancestor > a:before, 
	#navigation li.dropdown.current_page_item:hover > a:before, 
	#navigation li.dropdown.current-menu-ancestor:hover > a:before, 
	#navigation li.dropdown.current_page_item > a:before, 
	#navigation li.dropdown.current-menu-ancestor > a:before {
		background-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
		border-color:<?php echo $cmsms_option[CMSMS_SHORTNAME . '_theme_color']; ?>;
	}

}

