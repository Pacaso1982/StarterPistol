<?php
/**
 * The template for the Blog Landing Page.
 * Template Name: Blog Landing Page
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

			<div class="blog-items">
				<?php echo do_shortcode("[ic_add_posts showposts='8' category='blogs' paginate='yes' template='blog-item.php']"); ?>
			</div>

		</div>
		<!-- Content Ends Here -->
		<?php endwhile; ?>
	</div>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php
get_footer();
