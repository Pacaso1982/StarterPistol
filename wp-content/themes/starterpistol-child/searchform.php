<form class="search-form" role="search" action="<?php echo home_url('/') ?>">
    <div class="input-group">
        <input type="text" name="s" value="<?php echo get_search_query() ?>" class="form-control bt-search-field" placeholder="Search the entire site" />
<!-- 		<input type="text" name="s" class="searchbox" maxlength="128" value="<?php // echo get_search_query();?>" placeholder="<?php // esc_attr_e('Search by product name or keyword', 'woocommerce');?>"> -->
		<input type="hidden" name="post_type" value="search" />
		
<!--         <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Search <i class="fas fa-search"></i></button>
        </span> -->
    </div>
</form>