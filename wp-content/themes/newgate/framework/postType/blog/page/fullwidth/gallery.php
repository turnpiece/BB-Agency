<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Blog Page Full Width Gallery Post Format Template
 * Created by CMSMasters
 * 
 */


$cmsms_post_featured_image_show = get_post_meta(get_the_ID(), 'cmsms_post_featured_image_show', true);

$cmsms_post_images = explode(',', str_replace(' ', '', str_replace('img_', '', get_post_meta(get_the_ID(), 'cmsms_post_images', true))));

?>

<!--_________________________ Start Gallery Article _________________________ -->
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
					if (sizeof($cmsms_post_images) > 1) {
			?>
			<div class="shortcode_slideshow" id="slideshow_<?php the_ID(); ?>">
				<div class="shortcode_slideshow_body">
					<script type="text/javascript">
						jQuery(document).ready(function () { 
							jQuery('#slideshow_<?php the_ID(); ?> .shortcode_slideshow_slides').cmsmsResponsiveContentSlider( { 
								sliderWidth : '100%', 
								sliderHeight : 'auto', 
								animationSpeed : 500, 
								animationEffect : 'slide', 
								animationEasing : 'easeInOutExpo', 
								pauseTime : 0, 
								activeSlide : 1, 
								touchControls : true, 
								pauseOnHover : false, 
								arrowNavigation : true, 
								slidesNavigation : true 
							} );
						} );
					</script>
					<div class="shortcode_slideshow_container">
						<ul class="shortcode_slideshow_slides responsiveContentSlider">
						<?php 
							foreach ($cmsms_post_images as $cmsms_post_image) {
								echo "\t\t\t\t\t\t" . 
								'<li>' . "\n\t\t\t\t\t\t\t" . 
									'<figure>' . "\n\t\t\t\t\t\t\t\t" . 
										wp_get_attachment_image($cmsms_post_image, 'full-slider-thumb', false, array( 
											'class' => 'fullwidth', 
											'alt' => cmsms_title(get_the_ID(), false), 
											'title' => cmsms_title(get_the_ID(), false) 
										)) . "\r\t\t\t\t\t\t\t" . 
									'</figure>' . "\r\t\t\t\t\t\t" . 
								'</li>' . "\r";
							}
						?>
						</ul>
					</div>
				</div>
			</div>
			<?php 
				} else if (sizeof($cmsms_post_images) == 1) {
					cmsms_thumb(get_the_ID(), 'full-slider-thumb', false, 'img_' . get_the_ID(), true, true, true, true, $cmsms_post_images[0]);
					} else if (sizeof($cmsms_post_images) < 1 && has_post_thumbnail()) && $cmsms_post_featured_image_show == 'true') {
						cmsms_thumb(get_the_ID(), 'full-slider-thumb', false, 'img_' . get_the_ID(), true, true, true, true, false);
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
<!--_________________________ Finish Gallery Article _________________________ -->

