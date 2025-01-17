<?php 
/**
 * The template for displaying Sitemap Menu.
 * Template Name: Sitemap Menu Page
 */

get_header(); ?>
	
<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	<div class="l-constrain">
		
		<?php while(have_posts()) : the_post(); ?>

		<?php get_template_part('template-parts/page-description'); ?>

		<div class="inner">
			<?php get_template_part('template-parts/navigation'); ?>

			<?php the_content(); ?>

		</div>
		
		<?php endwhile; ?>
		
	</div>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer(); 
