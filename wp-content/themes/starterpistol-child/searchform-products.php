<form class="search-form" role="search" method="GET" action="<?php echo home_url('/') ?>">
    <div class="input-group">
		<input type="text" name="s" class="searchbox" maxlength="128" value="<?php echo get_search_query();?>" placeholder="<?php esc_attr_e('Search by product name or keyword', 'woocommerce');?>">
	</div>
	<div class="categories">
		<?php if (class_exists('WooCommerce')): ?>
		<?php
		if (isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat'])) {
		  $optsetlect = $_REQUEST['product_cat'];
		} else {
		  $optsetlect = 0;
		}
		$args = array(
		  'show_option_all' => esc_html__('All Categories', 'woocommerce'),
		  'hierarchical' => 1,
		  'class' => 'cat',
		  'echo' => 1,
		  'value_field' => 'slug',
		  'selected' => $optsetlect,
		);
		$args['taxonomy'] = 'product_cat';
		$args['name'] = 'product_cat';
		$args['class'] = 'cate-dropdown';
		wp_dropdown_categories($args);

		?>
		<input type="hidden" value="product" name="post_type">
		<?php endif;?>
	</div>
</form>