<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Portfolio Page Full Width Video Project Format Template
 * Created by CMSMasters
 * 
 */


global $cmsms_page_full_columns;

$cmsms_project_featured_image_show = get_post_meta(get_the_ID(), 'cmsms_project_featured_image_show', true);

$pj_sort_categs = get_the_terms(0, 'pj-sort-categs');

if ($pj_sort_categs != '') {
	$pj_categs = '';
	
	foreach ($pj_sort_categs as $pj_sort_categ) {
		$pj_categs .= ' ' . $pj_sort_categ->slug;
	}
	
	$pj_categs = ltrim($pj_categs, ' ');
}

$cmsms_project_video_type = get_post_meta(get_the_ID(), 'cmsms_project_video_type', true);
$cmsms_project_video_link = get_post_meta(get_the_ID(), 'cmsms_project_video_link', true);
$cmsms_project_video_links = get_post_meta(get_the_ID(), 'cmsms_project_video_links', true);

if (!$cmsms_page_full_columns) {
    $cmsms_page_full_columns = 'four_columns';
}

if ($cmsms_page_full_columns == 'four_columns' || $cmsms_page_full_columns == 'three_columns') {
    $project_thumb = 'project-thumb';
} elseif ($cmsms_page_full_columns == 'two_columns') {
    $project_thumb = 'slider-thumb';
} elseif ($cmsms_page_full_columns == 'one_column') {
    $project_thumb = 'full-thumb';
} 

?>

<!--_________________________ Start Video Project _________________________ -->
<article id="post-<?php the_ID(); ?>" <?php post_class('format-video'); ?> data-category="<?php echo $pj_categs; ?>">
<div class="wrap_project">
<?php 
	if (has_post_thumbnail() && $cmsms_project_featured_image_show == 'true') {
		echo '<div class="media_box">' . 
			'<figure class="preloader">' . 
				get_the_post_thumbnail(get_the_ID(), $project_thumb, array( 
					'class' => 'fullwidth', 
					'alt' => cmsms_title(get_the_ID(), false), 
					'title' => cmsms_title(get_the_ID(), false) 
				)) . 
			'</figure>' . 
		'</div>';
		
		$cmsms_imagelink = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
		
		echo '<div class="portfolio_rollover">' . 
			'<a href="' . $cmsms_imagelink[0] . '" data-group="img_' . get_the_ID() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_imagelink jackbox"></a>' . 
			'<a href="' . get_permalink() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_link"></a>' . 
		'</div>';
	} elseif ($cmsms_project_video_type == 'selfhosted' && sizeof($cmsms_project_video_links) > 0) {
		foreach ($cmsms_project_video_links as $cmsms_project_video_link_url) {
			$video_link[$cmsms_project_video_link_url[0]] = $cmsms_project_video_link_url[1];
		}
		if (has_post_thumbnail()) {
			echo '<div class="media_box">' . 
				'<figure class="preloader">' . 
					get_the_post_thumbnail(get_the_ID(), $project_thumb, array( 
						'class' => 'fullwidth', 
						'alt' => cmsms_title(get_the_ID(), false), 
						'title' => cmsms_title(get_the_ID(), false) 
					)) . 
				'</figure>' . 
			'</div>';
		} else {
			echo '<div class="media_box">' . 
				'<figure class="preloader">' . 
					'<img src="' . get_template_directory_uri() . '/img/PF-XL-video.jpg' . '" alt="' . cmsms_title(get_the_ID(), false) . '" title="' . cmsms_title(get_the_ID(), false) . '" class="fullwidth" />' . 
				'</figure>' . 
			'</div>';
		}
		echo '<div class="portfolio_rollover">' . 
			'<a href="' . $video_link[$cmsms_project_video_link_url[0]] . '" data-group="img_' . get_the_ID() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_imagelink jackbox"></a>' . 
			'<a href="' . get_permalink() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_link"></a>' . 
		'</div>';
	} elseif ($cmsms_project_video_type == 'embedded' && $cmsms_project_video_link != '') {
		if (has_post_thumbnail()) {
			echo '<div class="media_box">' . 
				'<figure class="preloader">' . 
					get_the_post_thumbnail(get_the_ID(), $project_thumb, array( 
						'class' => 'fullwidth', 
						'alt' => cmsms_title(get_the_ID(), false), 
						'title' => cmsms_title(get_the_ID(), false) 
					)) . 
				'</figure>' . 
			'</div>';
		} else {
			echo '<div class="media_box">' . 
				'<figure class="preloader">' . 
					'<img src="' . get_template_directory_uri() . '/img/PF-XL-video.jpg' . '" alt="' . cmsms_title(get_the_ID(), false) . '" title="' . cmsms_title(get_the_ID(), false) . '" class="fullwidth" />' . 
				'</figure>' . 
			'</div>';
		}
		echo '<div class="portfolio_rollover">' . 
			'<a href="' . $cmsms_project_video_link . '" data-group="img_' . get_the_ID() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_imagelink jackbox"></a>' . 
			'<a href="' . get_permalink() . '" title="' . cmsms_title(get_the_ID(), false) . '" class="cmsms_link"></a>' . 
		'</div>';
	}
?>
</div>
<?php 
	cmsms_heading(get_the_ID(), 'project', true, 'h4');
	
	cmsms_pj_date('page');
	
	cmsms_meta('project', 'page', get_the_ID(), 'pj-sort-categs', 'fullwidth');
	
	cmsms_exc_cont('project');
?>
</article>
<!--_________________________ Finish Video Project _________________________ -->

