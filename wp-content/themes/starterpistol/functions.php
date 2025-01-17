<?php

// SVG Support
function upload_svg_files( $allowed ) {
    if ( !current_user_can( 'manage_options' ) )
        return $allowed;
    $allowed['svg'] = 'image/svg';
    return $allowed;
}

add_filter( 'upload_mimes', 'upload_svg_files');

// Enqueue scripts and styles.
function starterpistol_scripts() {
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/dynamic.css', false, STARTERV, 'all' );
    wp_enqueue_style('starterpistol-style', get_stylesheet_directory_uri() . '/style.css', STARTERV, 'all');
}

add_action('wp_enqueue_scripts', 'starterpistol_scripts');

// Add pagination with 1,2,3 numbers to the "Posts In Page" plugin.
class ICPagePosts_Paginate_With_Numbers {

	public function __construct( ) {
		add_filter('posts_in_page_results', array( &$this, 'get_wp_query' ));
		add_filter('posts_in_page_paginate', array( &$this, 'paginate_links' ));
	}

	public function get_wp_query($posts){
		$this->posts = $posts;
		return $posts;
	}

	public function paginate_links($html){
		$obj = get_queried_object();
		$posts = $this->posts;
		
		wp_reset_query();

		if (is_archive() || is_tax()) {
			if ($obj->taxonomy == 'tag'){ $obj->taxonomy = 'post_tag'; }
			$page_url = get_term_link($obj);
		} elseif(is_post_type_archive() ) {
			$page_url = get_post_type_archive_link( get_query_var('post_type') );
		} else {
			$page_url = get_permalink();
		}
		
		$page = isset( $_GET['page'] ) ? $_GET['page'] : 1;
		$total_pages = $posts->max_num_pages;
		$per_page = $posts->query_vars['posts_per_page'];
		$curr_page = ( isset( $posts->query_vars['paged'] ) && $posts->query_vars['paged'] > 0	) ? $posts->query_vars['paged'] : 1;
		$prev = ( $curr_page && $curr_page > 1 ) ? '<li><a href="'.$page_url.'page/'. ( $curr_page-1 ).'">Previous</a></li>' : '';
		$next = ( $curr_page && $curr_page < $total_pages ) ? '<li><a href="'.$page_url.'page/'. ( $curr_page+1 ).'">Next</a></li>' : '';
		$numbers = ''; 

		for ($i = ($curr_page - 2); $i < ($curr_page + 3); $i++){
			if ($i == $curr_page) {
				$numbers .= '<li class="current">' . $i . '</li>';
			} elseif ($i < 1) {
				//do nothing because there are no links before page 1
			} elseif ($i > $total_pages) {
				//do nothing. we don't want to paginate pages that don't exist.
			} else {
			 	$numbers .= '<li><a href="'.$page_url.'page/'. $i .'">' . $i . '</a></li>';
			}
		}
		return '<div class="pip-nav"><ul>' . $prev . $numbers . $next . '</ul></div>';
	}
}
new ICPagePosts_Paginate_With_Numbers();

// Dynamic CSS Functionality
function clear_advert_main_transient() {
    $screen = get_current_screen();

    if (strpos($screen->id, "acf-options-website-variables") == true) {
        $file = WP_CONTENT_DIR.'/themes/starterpistol/css.txt';        
        $css_raw = file_get_contents($file);
        
        $variables = array('base_font_color', 'background_color', 'link_color','link_hover_color',
                'primary_color', 'secondary_color', 'tertiary_color', 'quaternary_color', 'accent_color',
                'base_font_size', 'font_xs', 'font_sm', 'font_md', 'font_lg', 'font_xl', 'font_2x', 'font_3x', 'font_4x', 
                'base_font', 'secondary_font'
            );

        $search_array = array();
        $replace_array = array();

        foreach ($variables as $value) {

            $search_array[]  = '__'.$value.'__';
            $replace_array[] = get_field( $value, 'options');

        }
        
        $final_css = str_replace( $search_array, $replace_array, $css_raw );        
        unlink( WP_CONTENT_DIR.'/themes/starterpistol/dynamic.css' );
        file_put_contents(WP_CONTENT_DIR.'/themes/starterpistol/dynamic.css', $final_css);
    }
}

add_action('acf/save_post', 'clear_advert_main_transient', 20);
