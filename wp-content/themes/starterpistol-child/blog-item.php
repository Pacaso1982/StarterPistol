<div class="blog-item">
	<div class="columns columns--vertical-center">
		<div class="column-half">
			<?php if(has_post_thumbnail()) : ?>
			<a class="image" href="<?php the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail_url($post->ID, 'full') ?>"></a>
			<?php else : ?>
			<a class="image" href="<?php the_permalink(); ?>"><img src="/wp-content/uploads/2022/05/image-coming-soon.jpg"></a>
			<?php endif; ?>
		</div>
		<div class="column-half">
			<div class="blog__meta">
				<span class="date"><?php the_time('F j, Y'); ?></span> | <span class="categories">Categories: <?php the_category(', '); ?></span>
			</div>	
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<div class="content">
				<?php the_excerpt(); ?>
			</div>

			<a href="<?php the_permalink(); ?>" class="learn-more">Read More</a>
		</div>
	</div>
</div>