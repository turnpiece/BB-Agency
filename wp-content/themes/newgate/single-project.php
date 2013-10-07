<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Single Project Template
 * Created by CMSMasters
 * 
 */


$cmsms_option = cmsms_get_global_options();


get_header();


$cmsms_project_format = get_post_meta(get_the_ID(), 'cmsms_project_format', true);

if (!$cmsms_project_format) { 
    $cmsms_project_format = 'slider'; 
}

$cmsms_project_sharing_box = get_post_meta(get_the_ID(), 'cmsms_project_sharing_box', true);
$cmsms_project_author_box = get_post_meta(get_the_ID(), 'cmsms_project_author_box', true);
$cmsms_project_more_posts = get_post_meta(get_the_ID(), 'cmsms_project_more_posts', true);

$project_tags = get_the_terms(get_the_ID(), 'pj-tags');


echo '<!--_________________________ Start Content _________________________ -->' . "\n" . 
'<section id="middle_content" role="main">' . "\n";

if (have_posts()) : the_post();
	echo "\t" . '<div class="entry">' . "\n\t\t" . 
		'<section class="opened-article">' . "\n";
	
	get_template_part('framework/postType/portfolio/post/' . $cmsms_project_format); 
	
	if ($cmsms_option[CMSMS_SHORTNAME . '_portfolio_project_nav_box']) {
		echo '<aside class="project_navi">' . "\n\t";
		
		next_post_link('%link');
		
		previous_post_link('%link');
		
		echo '</aside>' . "\n";
	}
	
	if ($cmsms_project_sharing_box == 'true') {
		echo '<aside class="share_posts">' . "\n\t" . 
			'<h5>' . __('Like this post?', 'cmsmasters') . '</h5>' . "\n";
?>
	<div class="fl">
		<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en"><?php _e('Tweet', 'cmsmasters'); ?></a>
		<script type="text/javascript">
			!function (d, s, id) { 
				var js = undefined, 
					fjs = d.getElementsByTagName(s)[0];
				
				if (d.getElementById(id)) { 
					d.getElementById(id).parentNode.removeChild(d.getElementById(id));
				}
				
				js = d.createElement(s);
				js.id = id;
				js.src = '//platform.twitter.com/widgets.js';
				
				fjs.parentNode.insertBefore(js, fjs);
			} (document, 'script', 'twitter-wjs');
		</script>
	</div>
	<div class="fl">
		<div class="g-plusone" data-size="medium"></div>
		<script type="text/javascript">
			(function () { 
				var po = document.createElement('script'), 
					s = document.getElementsByTagName('script')[0];
				
				po.type = 'text/javascript';
				po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				
				s.parentNode.insertBefore(po, s);
			} )();
		</script>
	</div>
	<div class="fl">
		<a href="http://pinterest.com/pin/create/button/?url=url_text&media=http%3A%2F%2Ftext&description=descr_text" class="pin-it-button" count-layout="horizontal">
			<img border="0" src="//assets.pinterest.com/images/PinExt.png" title="<?php _e('Pin It', 'cmsmasters'); ?>" />
		</a>
		<script type="text/javascript">
			(function (d, s, id) { 
				var js = undefined, 
					fjs = d.getElementsByTagName(s)[0];
				
				if (d.getElementById(id)) { 
					d.getElementById(id).parentNode.removeChild(d.getElementById(id));
				}
				
				js = d.createElement(s);
				js.id = id;
				js.src = '//assets.pinterest.com/js/pinit.js';
				
				fjs.parentNode.insertBefore(js, fjs);
			} (document, 'script', 'pinterest-wjs'));
		</script>
	</div>
	<div class="fl">
		<div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="false" data-font="arial"></div>
		<script type="text/javascript">
			(function (d, s, id) { 
				var js = undefined, 
					fjs = d.getElementsByTagName(s)[0];
				
				if (d.getElementById(id)) { 
					d.getElementById(id).parentNode.removeChild(d.getElementById(id));
				}
				
				js = d.createElement(s);
				js.id = id;
				js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
				
				fjs.parentNode.insertBefore(js, fjs);
			} (document, 'script', 'facebook-jssdk'));
		</script>
	</div>
	<div class="cl"></div>
	<a class="cmsms_share" href="#"><span><?php _e('More sharing options', 'cmsmasters'); ?></span></a>
	<div class="cmsms_social cl"></div>
<?php 
		echo '</aside>' . "\n";
	}
	
	if ($cmsms_project_author_box == 'true') {
		$user_email = get_the_author_meta('user_email') ? get_the_author_meta('user_email') : false;
		$user_nicename = get_the_author_meta('user_nicename') ? get_the_author_meta('user_nicename') : false;
		$user_first_name = get_the_author_meta('first_name') ? get_the_author_meta('first_name') : false;
		$user_last_name = get_the_author_meta('last_name') ? get_the_author_meta('last_name') : false;
		$user_description = get_the_author_meta('description') ? get_the_author_meta('description') : false;
		
		echo '<aside class="about_author">' . "\n\t" . 
			'<h5>' . __('About author', 'cmsmasters') . '</h5>' . "\n\t" . 
			'<div class="about_author_inner">' . "\n\t\t";
		
		$out = '';
		
		if ($user_first_name) {
			$out .= $user_first_name;
		}
		
		if ($user_first_name && $user_last_name) {
			$out .= ' ' . $user_last_name;
		} elseif ($user_last_name) {
			$out .= $user_last_name;
		}
		
		if (get_the_author() && ($user_first_name || $user_last_name)) {
			$out .= ' (';
		}
		
		if (get_the_author()) {
			$out .= get_the_author();
		}
		
		if (get_the_author() && ($user_first_name || $user_last_name)) {
			$out .= ')';
		}
		
		echo '<figure class="alignleft">' . "\n\t\t\t" . 
			get_avatar($user_email, 100, $default='<path_to_url>', $user_nicename) . "\r\t\t" . 
		'</figure>' . "\n\t\t";
		
		if ($out != '') {
			echo '<h6>' . $out . '</h6>' . "\n\t\t";
		}
		
		if ($user_description) {
			echo '<p>' . str_replace("\n", '<br />', $user_description) . '</p>' . "\r\t";
		}
		
		echo '</div>' . "\r" . 
		'</aside>' . "\n";
	}
	
	if ($project_tags) {
		$tgsarray = array();
		
		foreach ($project_tags as $tagone) {
			$tgsarray[] = $tagone->term_id;
		}  
	} else {
		$tgsarray = null;
	}
	
	if (is_array($cmsms_project_more_posts)) {
		cmsms_related( 
			in_array('related', $cmsms_project_more_posts), 
			$tgsarray, 
			in_array('popular', $cmsms_project_more_posts), 
			in_array('recent', $cmsms_project_more_posts), 
			$cmsms_option[CMSMS_SHORTNAME . '_portfolio_project_r_p_l_number'], 
			'project', 
			'pj-tags' 
		);
	}
	
	comments_template(); 
	
	echo '</section>' . "\r\t" . 
	'</div>' . "\n";
endif;

echo '</section>' . "\n" . 
'<!-- _________________________ Finish Content _________________________ -->' . "\n\n";


if ($cmsms_layout == 'r_sidebar') {
	echo "\n" . '<!-- _________________________ Start Sidebar _________________________ -->' . "\n" . 
	'<section id="sidebar" role="complementary">' . "\n";
	
	get_sidebar();
	
	echo "\n" . '</section>' . "\n" . 
	'<!-- _________________________ Finish Sidebar _________________________ -->' . "\n";
} elseif ($cmsms_layout == 'l_sidebar') {
	echo "\n" . '<!-- _________________________ Start Sidebar _________________________ -->' . "\n" . 
	'<section id="sidebar" class="fl" role="complementary">' . "\n";
	
	get_sidebar();
	
	echo "\n" . '</section>' . "\n" . 
	'<!-- _________________________ Finish Sidebar _________________________ -->' . "\n";
}


get_footer();

