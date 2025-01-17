<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link href="https://fonts.googleapis.com/css?family=Flamenco:300,400" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;600&family=Spartan:wght@300;400;600;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'starterpistol' ); ?></a>
	<div id="backtotop"></div>
	
	<?php get_template_part('template-parts/message-bar'); ?>
	
	<header id="masthead" class="site-header">
		<div class="site-logo">
			<?php 
				$custom_logo = get_custom_logo();
				$image_path_filename = get_template_directory_uri().'/images/logo.svg';
			?>

			<?php if ($custom_logo) : ?>
			<a href="<?php echo home_url(); ?>"><?php the_custom_logo(); ?></a>
			<?php elseif($image_path_filename && file_exists( get_stylesheet_directory().'/images/logo.svg') ) : ?>
			<a href="<?php echo home_url(); ?>"><img src="<?php echo $image_path_filename; ?>"></a>
			<?php else : ?>
			<a href="<?php echo home_url(); ?>"><?php echo get_option('blogname'); ?></a>
			<?php endif; ?>
		</div>

		<div class="site-contact">
			<p class="phone"><?php the_field('phone', 'options'); ?></p>
			<p class="address"><?php the_field('address', 'options'); ?></p>
		</div>

		<?php get_template_part('template-parts/navigation'); ?>
		
		<?php get_template_part('template-parts/mobile-menu'); ?>
		
		<div class="site-search">
			<?php get_search_form(); ?>
		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">

