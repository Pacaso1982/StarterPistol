<?php if( have_rows('gallery_flex') ): ?>
<div class="gallery--flex">
	
  <?php while( have_rows('gallery_flex') ): the_row(); ?>
	
	<div class="gallery-item" style="background-image: url(<?php the_sub_field('gf_image'); ?>);">
		<div class="gallery__content">
			<div class="gallery__title"><?php the_sub_field('gf_title'); ?></div>
			<?php the_sub_field('gf_content'); ?>
			<a class="btn btn--small" href="<?php the_sub_field('gf_link'); ?>">View</a>
		</div>
	</div>

  <?php endwhile; ?>

</div>
<?php endif; ?>