<?php if ($networks = get_field('social_networks', 'option')) : ?>
<div class="social-media social-media--footer">
	<div class="social-media">
		<ul>
			<?php foreach ($networks as $network) : ?>
				<li><a href="<?php echo($network['web_address']); ?>" target="_blank"><span class="socicon socicon-<?php echo($network['name']); ?>"></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>