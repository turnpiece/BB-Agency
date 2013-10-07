<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Blog Post Full Width Link Post Format Template
 * Created by CMSMasters
 * 
 */


$cmsms_post_link_text = get_post_meta(get_the_ID(), 'cmsms_post_link_text', true);
$cmsms_post_link_address = get_post_meta(get_the_ID(), 'cmsms_post_link_address', true);

if ($cmsms_post_link_text == '') {
	$cmsms_post_link_text = __('Enter link text', 'cmsmasters');
}

if ($cmsms_post_link_address == '') {
	$cmsms_post_link_address = '#';
}

?>

<!--_________________________ Start Link Article _________________________ -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php cmsms_post_date('post', 'post'); ?>
		<div class="ovh">
			<?php 
				cmsms_heading_nolink(get_the_ID(), 'post', true) . "\n";
				
				cmsms_meta('post', 'post');
				
				if (!post_password_required()) {
					cmsms_comments('post');
				} 
			?>
		</div>
	</header>
	<div class="cmsms_wrapper_content">
		<span class="cmsms_post_format_img"></span>
		<?php 
			if (!post_password_required()) {
				echo '<h3 class="entry-title">' . 
					'<a href="' . $cmsms_post_link_address . '" target="_blank">' . $cmsms_post_link_text . '</a>' . 
				'</h3>' . "\n\t" . 
				'<h6>- ' . $cmsms_post_link_address . ' -</h6>' . "\n";
			} else {
				echo '<h3 class="entry-title">' . $cmsms_post_link_text . '</h3>';
			}
		?>
	</div>
	<footer class="entry-meta">
		<?php 
			echo '<div class="entry-content">' . "\n";
			
			the_content();
			
			wp_link_pages(array( 
				'before' => '<div class="subpage_nav" role="navigation">' . '<strong>' . __('Pages', 'cmsmasters') . ':</strong>', 
				'after' => '</div>' . "\n", 
				'link_before' => ' [ ', 
				'link_after' => ' ] ' 
			));
			
			cmsms_content_composer(get_the_ID());
			
			echo "\t\t" . '</div>' . "\n";
			
			cmsms_tags(get_the_ID(), 'post', 'post');
		?>
	</footer>
</article>
<!--_________________________ Finish Link Article _________________________ -->

