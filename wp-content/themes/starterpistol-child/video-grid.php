<?php
/**
 * The template for Multi-Column Video Grids.
 * Template Name: Video Grid
 */

get_header(); ?>

<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	
	<div class="l-constrain">
		<?php while(have_posts()) : the_post(); ?>

		<!-- Content Begins Here -->
		<div class="inner">

			<?php the_content(); ?>

			<?php get_template_part('template-parts/grid-video'); ?>

		</div>
		<!-- Content Ends Here -->
		<?php endwhile; ?>

	</div>

</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();
