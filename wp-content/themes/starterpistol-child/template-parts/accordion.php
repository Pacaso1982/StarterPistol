<?php if( have_rows('accordion') ): ?>
<div class="accordion">
	
  <?php while( have_rows('accordion') ): the_row(); ?>
	
  <div class="accordion-item accordion-item--<?php echo get_row_index(); ?>">
    <div class="accordion__title"><?php the_sub_field('accordion_title'); ?></div>
		<div class="accordion__content">
			<?php the_sub_field('accordion_content'); ?>
		</div>
	</div>

  <?php endwhile; ?>

</div>
<?php endif; ?>