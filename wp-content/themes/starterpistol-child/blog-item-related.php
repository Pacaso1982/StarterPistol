<div class="column-half blog-item blog-item--related">
	<div class="media">
		<?php if(has_post_thumbnail()) : ?>
		<a class="image" href="<?php the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail_url($post->ID, 'full') ?>"></a>
		<?php else : ?>
		<a class="image" href=""><img src="/wp-content/uploads/2022/05/image-coming-soon.jpg"></a>
		<?php endif; ?>
	</div>
	<div class="content">
		<div class="blog__meta">
			<span class="date"><?php the_time('F j, Y'); ?></span> | <span class="categories">Categories: <?php the_category(', '); ?></span>
		</div>	
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<a href="<?php the_permalink(); ?>" class="learn-more">Read More</a>
	</div>
</div>