<?php
/**
 * The template for pages with a Gallery.
 * Template Name: Gallery
 */

get_header(); ?>
    
<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">

	<div class="l-constrain">
	<?php while(have_posts()) : the_post(); ?>

		<?php the_content(); ?>

		<?php get_template_part('template-parts/gallery-flex'); ?>

	<?php endwhile; ?>
	</div>

</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();
