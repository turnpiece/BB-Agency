<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Blog Page with Sidebar Audio Post Format Template
 * Created by CMSMasters
 * 
 */


$cmsms_post_featured_image_show = get_post_meta(get_the_ID(), 'cmsms_post_featured_image_show', true);

$cmsms_post_audio_links = get_post_meta(get_the_ID(), 'cmsms_post_audio_links', true);

?>

<!--_________________________ Start Audio Article _________________________ -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="cmsms_info">
		<?php 
			cmsms_post_date();
			
			if (!post_password_required()) {
				cmsms_comments();
			} 
		?>
	</div>
	<div class="ovh">
		<header class="entry-header">
			<?php 
				if (!post_password_required()) {
					if (has_post_thumbnail() && $cmsms_post_featured_image_show == 'true') {
						cmsms_thumb(get_the_ID(), 'post-thumbnail', true, false, true, false, true, true, false);
					}
					
					if (!empty($cmsms_post_audio_links) && sizeof($cmsms_post_audio_links) > 0) {
						foreach ($cmsms_post_audio_links as $cmsms_post_audio_link_url) {
							$audio_link[$cmsms_post_audio_link_url[0]] = $cmsms_post_audio_link_url[1];
						}
						
						echo '<div class="cmsms_blog_media">' . "\n" . 
							cmsmastersSingleAudioPlayer($audio_link) . "\r\t\t" . 
						'</div>' . "\r\t\t";
					}
				}
			?>
		</header>
		<?php cmsms_heading(get_the_ID()); ?>
		<footer class="entry-meta">
			<?php 
				cmsms_meta(); 
				
				cmsms_exc_cont();
				
				cmsms_more(get_the_ID());
				
				cmsms_tags(get_the_ID(), 'post', 'page'); 
			?>
		</footer>
	</div>
</article>
<!--_________________________ Finish Audio Article _________________________ -->

