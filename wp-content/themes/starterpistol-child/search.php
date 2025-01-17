<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package StarterPistol
 */

get_header(); ?>
    
	<?php get_template_part('template-parts/hero-internal'); ?>

	<div class="page__content">
		<div class="l-constrain">
			<?php
			if ( have_posts() ) : ?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile; ?>
			
			<div class="pagination">
				<?php starterpistol_number_pagination(); ?>
			</div>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>
		</div>
	</div><!-- #primary -->

<?php
get_footer();
