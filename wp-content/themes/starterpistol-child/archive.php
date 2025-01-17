<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package StarterPistol
 */

get_header(); ?>

<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	<div class="l-constrain">
		
	<?php echo do_shortcode("[ic_add_posts showposts='12' category='petcare' paginate='yes' template='blog-item-related.php']"); ?>

	</div>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php
get_footer();
