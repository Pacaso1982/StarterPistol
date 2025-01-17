<?php if( have_rows('locations', 'options') ): ?>
<div class="locations">
	
  <?php while( have_rows('locations', 'options') ): the_row(); 
	// Get the address (replace this with your address)
	$address = get_sub_field('location_address');

	// Convert the address into a clickable Google Maps link
	$google_maps_link = address_to_google_maps_link($address);
  ?>
	
	<div class="location-item">
		<h2 class="location__name"><?php the_sub_field('location_name'); ?></h2>
		<?php if($address) : ?>
		<p class="location__address"><?php echo $address; ?></p>
		<?php echo $google_maps_link; ?>
		<?php endif; ?>
	</div>

  <?php endwhile; ?>

</div>
<?php endif; ?>