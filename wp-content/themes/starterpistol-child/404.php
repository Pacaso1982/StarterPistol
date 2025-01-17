<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package StarterPistol
 */

get_header(); ?>

<?php get_template_part('template-parts/hero-internal'); ?>

<div class="page__content">
	<div class="l-constrain">
		<section class="error-404 not-found">

			<p>It seems as if you have stumbled across something that doesn't exist. Please go back the way that you came, or simply follow the breadcrumb trail back to the homepage.</p>

		</section><!-- .error-404 -->
	</div>
</div><!-- #primary -->

<?php get_template_part('template-parts/pre-footer'); ?>

<?php
get_footer();
