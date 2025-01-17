<?php 
	$request_info = do_shortcode( '[gravityform id="4" title="false" ajax="true"]' );
?>

<?php if(is_archive('reviews')) : ?>
<div id="popup-sp">
	<div class="inner">
		<h2>Submit Your Review</h2>
		<div class="popup-sp__content">
			<?php echo $request_info; ?>
		</div>
	</div>
</div>
<?php endif; ?>