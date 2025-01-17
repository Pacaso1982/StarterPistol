<?php
/**
 * The template for pages with an Accordion.
 * Template Name: Accordion List
 */

get_header(); ?>
    
<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">

	<div class="l-constrain">
	<?php while(have_posts()) : the_post(); ?>

		<?php the_content(); ?>

		<?php get_template_part('template-parts/accordion'); ?>

	<?php endwhile; ?>
	</div>

	<?php get_template_part('template-parts/section-contact'); ?>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();
