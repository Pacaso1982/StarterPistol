<?php if(is_single()) : ?>
<div class="breadcrumbs">
	<div class="l-constrain">
		<p id=“breadcrumbs”>
			<span><span><a href="<?php echo home_url(); ?>">Home</a> » <span class="breadcrumb_parent"><a href="<?php echo home_url('/blog/'); ?>">Blog</a></span> » <span class="breadcrumb_last" aria-current="page"><?php the_title(); ?></span></span></span>
		</p>
	</div>
</div>
<?php else : ?>
<div class="breadcrumbs">
	<div class="l-constrain">
		<?php
		if ( function_exists('yoast_breadcrumb') ) {
		  yoast_breadcrumb( '<p class="breadcrumbs-inner">','</p>' );
		}
		?>
	</div>
</div>
<?php endif; ?>