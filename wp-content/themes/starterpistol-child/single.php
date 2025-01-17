<?php
/**
 * Template Name: Default Posts
 * Description: The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package StarterPistol
 */

get_header(); ?>

<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	<div class="l-constrain">
		
		<div class="blog__meta">
			<span class="date"><?php the_time('F j, Y'); ?></span> | <span class="categories">Categories: <?php the_category(', '); ?></span>
		</div>	
		

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );
			
			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
		
		<div class="blogs--related">
		<h2>Related Posts</h2>
			<div class="columns">
				<?php echo do_shortcode("[ic_add_posts showposts='2' category='petcare' orderby='rand' template='blog-item-related.php']"); ?>
			</div>
		</div>
	</div>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php
get_footer();
