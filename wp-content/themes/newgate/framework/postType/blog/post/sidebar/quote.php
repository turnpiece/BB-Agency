<?php
/**
 * @package WordPress
 * @subpackage Newgate
 * @since Newgate 1.0
 * 
 * Blog Post with Sidebar Quote Post Format Template
 * Created by CMSMasters
 * 
 */


$cmsms_post_quote_text = get_post_meta(get_the_ID(), 'cmsms_post_quote_text', true);
$cmsms_post_quote_author = get_post_meta(get_the_ID(), 'cmsms_post_quote_author', true);

?>

<!--_________________________ Start Quote Article _________________________ -->
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
	<?php if (!post_password_required()) { ?>
		<blockquote>
		<?php 
			if ($cmsms_post_quote_text != '') {
				echo "\t" . '<p>' . str_replace("\n", '<br />', $cmsms_post_quote_text) . '</p>' . "\n";
			} else {
				echo "\t" . '<p>' . theme_excerpt(55, false) . '</p>' . "\n";
			}
		?>
		</blockquote>
	<?php 
			if ($cmsms_post_quote_author != '') {
				echo '<p class="cmsms_quote_author">' . $cmsms_post_quote_author . '</p>' . "\n";
			}
		} else {
			echo '<p>' . __('There is no excerpt because this is a protected post.', 'cmsmasters') . '</p>';
		}
	?>
	<div class="entry-content">
		<?php 
			the_content();

			wp_link_pages(array( 
				'before' => '<div class="subpage_nav" role="navigation">' . '<strong>' . __('Pages', 'cmsmasters') . ':</strong>', 
				'after' => '</div>' . "\n", 
				'link_before' => ' [ ', 
				'link_after' => ' ] ' 
			));
			
			cmsms_content_composer(get_the_ID());
		?>
	</div>
	<footer class="entry-meta">
		<?php cmsms_tags(get_the_ID(), 'post', 'post'); ?>
	</footer>
</article>
<!--_________________________ Finish Quote Article _________________________ -->

