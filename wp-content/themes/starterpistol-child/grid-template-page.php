<?php
/**
 * The template for Multi-Column Grids.
 * Template Name: Grid Landing Page
 */

get_header(); ?>
    
<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	
	<div class="l-constrain">
		<?php while(have_posts()) : the_post(); ?>

		<?php get_template_part('template-parts/page-description'); ?>

		<!-- Content Begins Here -->
		<div class="inner">

			<?php the_content(); ?>

			<?php get_template_part('template-parts/grid'); ?>

		</div>
		<!-- Content Ends Here -->
		<?php endwhile; ?>

	</div>

</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();
