<?php
/**
 * Template Name: Default
 * Description: The template for all default pages
 *
 * @package StarterPistol
 */

get_header(); ?>

<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">

	<div class="l-constrain">
		<?php while(have_posts()) : the_post(); ?>

		<!-- Content Begins Here -->
		<div class="inner">

			<?php the_content(); ?>

		</div>
		<!-- Content Ends Here -->
		
		<?php endwhile; ?>

	</div>
	
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();
