<?php
/* 
 * Paginate Advanced Custom Field repeater
 */

if( isset($_GET['pg']) ) {
  $page = abs((int)$_GET['pg']);
} else {
  $page = 1;
}

// Variables
$image_count	  = get_field( 'videos_per_page' );
$row              = 0;
$images_per_page  = $image_count; // How many images to display on each page
$images           = get_field( 'video_grid' );
$total            = (!empty($images) && is_array($images))?count( $images ):0;
$pages            = ceil( $total / $images_per_page );
$min              = ( ( $page * $images_per_page ) - $images_per_page ) + 1;
$max              = ( $min + $images_per_page ) - 1;
$column_int       = get_field('video_column_choice');

switch ($column_int) {
    case 'thirds':
        $mod_int = 3;
        break;
    case 'half':
        $mod_int = 2;
        break;
    case 'fourths':
        $mod_int = 4;
        break;

    default:
        $mod_int = 3;
        break;
}

?>

<?php if( have_rows('video_grid') ): ?>
<div class="grid-items columns columns--grid-<?php the_field('video_column_choice'); ?>">

<?php $index = 0; while( have_rows('video_grid') ): the_row(); 
	$row++;

	// Ignore this image if $row is lower than $min
	if($row < $min) { continue; }

	// Stop loop completely if $row is higher than $max
	if($row > $max) { break; } 
        $index++;
?> 	

	<?php if(get_sub_field('choose_video_type') == 'youtube') : ?>
	<div class="grid-item--video column-<?php the_field('video_column_choice'); ?>">
		<div class="media">
			<div class="embed-responsive embed-responsive-16by9 youtube" data-embed="<?php the_sub_field('youtube_id'); ?>">
			
				<div class="play-button"></div>
				<img src="https://img.youtube.com/vi/<?php the_sub_field('youtube_id'); ?>/hqdefault.jpg">

			</div>
		</div>
		
		<div class="content">
			<p class="title"><?php the_sub_field('video_title'); ?></p>
		</div>
	</div>
	<?php elseif(get_sub_field('choose_video_type') == 'vimeo') : ?>
	<div class="grid-item--video column-<?php the_field('video_column_choice'); ?>">
		<div class="media">
			<div class="embed-responsive embed-responsive-16by9 youtube" data-embed="<?php the_sub_field('vimeo_id'); ?>">
			
				<div class="play-button"></div>
				<img src="https://vumbnail.com/<?php the_field('vimeo_id'); ?>.jpg">

			</div>
		</div>
		
		<div class="content">
			<p class="title"><?php the_sub_field('video_title'); ?></p>
		</div>
	</div>
	<?php endif; ?>
	
<?php endwhile; if( $index%$mod_int != 0 ){     echo '<div class="grid-item grid-item__'.$column_int.'"></div>';} ?>

</div>
<?php  endif ; ?>

<?php  if($pages > 1) : ?>	
<div class="grid__pagination">
	<?php // Pagination
	  echo paginate_links( array(
		'base' => get_permalink() . '?pg=%#%',
		'format' => '?pg=%#%',
		'current' => $page,
		'total' => $pages
	  ) );
	  ?>
</div>
<?php  endif ; ?>	
