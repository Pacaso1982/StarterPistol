<?php if (is_search()) : ?>
<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<div class="page__title">
					<h1>Results for: <?php echo get_search_query() ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif (is_archive('reviews')) : ?>
<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<div class="page__title">
					<h1>Reviews</h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif (is_singular('services')) : ?>
<?php if (has_post_thumbnail()) : ?>
	<div class="hero--internal" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'full') ?>);">
<?php else : ?>
	<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
<?php endif; ?>
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<div class="page__title">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif (is_single()) : ?>
<?php if (has_post_thumbnail()) : ?>
	<div class="hero--internal" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'full') ?>);">
<?php else : ?>
	<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
<?php endif; ?>
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<div class="page__title">
					<h1><?php the_title(); ?></h1>
				</div>
				<?php get_template_part('template-parts/breadcrumbs'); ?>
			</div>
		</div>
	</div>
</div>
<?php elseif (is_page_template() || basename(get_page_template()) === 'page.php') : ?>
<?php if (has_post_thumbnail()) : ?>
	<div class="hero--internal" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'full') ?>);">
<?php else : ?>
	<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
<?php endif; ?>
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<div class="page__title">
					<h1><?php the_title(); ?></h1>
				</div>
				<?php get_template_part('template-parts/breadcrumbs'); ?>
				<?php if (get_field('hero_description')) : ?>
					<?php the_field('hero_description'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php elseif (is_archive() || is_404()) : ?>
<div class="hero--internal" style="background-image: url(<?php the_field('default_internal_hero', 'options'); ?>);">
	<div class="l-constrain">
		<div class="inner">
			<div class="hero__content">
				<?php if (is_archive()) : ?>
					<div class="page__title">
						<h1><?php the_archive_title(); ?></h1>
					</div>
				<?php elseif (is_404()) : ?>
					<div class="page__title">
						<h1>Error</h1>
					</div>
					<?php get_template_part('template-parts/breadcrumbs'); ?>
				<?php else : ?>
					<div class="page__title">
						<h1><?php the_title(); ?></h1>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php else : ?>
<div class="page__title">
	<div class="l-constrain">
		<h1><?php the_title(); ?></h1>
	</div>
</div>
<?php endif; ?>
