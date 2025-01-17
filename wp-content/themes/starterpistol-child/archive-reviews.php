<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package StarterPistol
 */

get_header(); ?>
 	
    <?php get_template_part('template-parts/breadcrumbs'); ?>

	<div class="page__content">
		<div class="l-constrain">

			<div class="columns">
				<div class="column-fourths">
					<?php csr_add_rating(); ?>
				</div>
				<div class="column-three-fourths">
					<?php
					if ( have_posts() ) : ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					the_posts_navigation();

					else :

					get_template_part( 'template-parts/content', 'none' );

					endif; ?>
				</div>
			</div>
			
	    </div>
	</div><!-- #primary -->

<?php
get_footer();
