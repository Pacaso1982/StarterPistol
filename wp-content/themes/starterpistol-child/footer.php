<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package StarterPistol
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
	<div class="backtotop"><a href="#backtotop"></a></div>
		
		<div class="l-constrain">
			
			<?php get_template_part('template-parts/social-media-footer'); ?>

			<div class="site-footer__widgets">
				<?php dynamic_sidebar( 'site_footer_widget_1' ); ?>
				<?php dynamic_sidebar( 'site_footer_widget_2' ); ?>
				<?php dynamic_sidebar( 'site_footer_widget_3' ); ?>
			</div>

			<div class="site-info">
				Copyright © <?php echo date("Y"); ?> <span><?php bloginfo( 'name' ); ?></span>. All Rights Reserved. Developed by <a href="<?php the_field('department_link', 'options'); ?>" target="_blank"><?php the_field('department', 'options'); ?></a>.
			</div><!-- .site-info -->

		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<script src="https://kit.fontawesome.com/86e74db3e3.js"></script>

<?php get_template_part('template-parts/popup'); ?>

</body>
</html>
