<?php if(get_field('message')) : ?>
<div class="message-bar">
	<div class="l-constrain">
		<p><?php the_field('message'); ?></p>
	</div>
</div>
<?php endif; ?>