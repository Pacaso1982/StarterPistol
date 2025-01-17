<?php
/**
 * StarterPistol functions and definitions
 */

// include_once 'inc/woo-functions.php';
// include_once 'inc/acf-functions.php';
// include_once 'inc/gf-functions.php';

define( 'STARTERV', '1.0.0' );

if ( ! function_exists( 'starterpistol_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function starterpistol_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on StarterPistol, use a find and replace
	 * to change 'starterpistol' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'starterpistol', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'starterpistol' ),
		'mobile-menu' => esc_html__( 'Mobile Menu', 'starterpistol' ),
	) );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'starterpistol_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Custom Logo Size
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	add_theme_support('woocommerce'); 
}
endif;
add_action( 'after_setup_theme', 'starterpistol_setup' );

// Enqueue Parent Styles
add_action( 'wp_enqueue_scripts', 'starterpistol_styles' );
function starterpistol_styles() {
	$parenthandle = 'starterpistol';
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_uri(),
		array( $parenthandle ),
		$theme->get( 'Version' )
	);
}

// Enqueue scripts and styles.
function starterpistol_child_scripts() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
	wp_enqueue_style('glide-css', 'https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css', array(), '3.5.2');
	wp_enqueue_script('starterpistol-glide', get_stylesheet_directory_uri() . '/js/glide.min.js', [], '3.5.2', true);
    wp_enqueue_script('starterpistol-css-var-polyfix', get_template_directory_uri() . '/js/css-var-polyfix.js', array('jquery'), STARTERV, true);
    wp_enqueue_script('starterpistol-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array('jquery'), STARTERV, true);
    wp_enqueue_script('starterpistol-main-script', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery', 'starterpistol-css-var-polyfix', 'starterpistol-skip-link-focus-fix', 'starterpistol-glide'), rand(100, 9999), true);

    wp_localize_script('starterpistol-main-script', 'starterpistolobj', array('ajax_url' => admin_url('admin-ajax.php')));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'starterpistol_child_scripts');

// Register Widgets
function starterpistol_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'starterpistol' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'starterpistol' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'starterpistol_widgets_init' );

// Register our sidebars and widgetized areas.
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Header Widget',
		'id'            => 'header_widget',
		'before_widget' => '<div class="site-header--widget">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );

	register_sidebar( array(
		'name'          => 'Small Header Widget',
		'id'            => 'header_widget_small',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

	register_sidebar( array(
		'name'          => 'Footer Widget 1',
		'id'            => 'site_footer_widget_1',
		'before_widget' => '<div class="site-footer__widget-item">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Widget 2',
		'id'            => 'site_footer_widget_2',
		'before_widget' => '<div class="site-footer__widget-item">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Widget 3',
		'id'            => 'site_footer_widget_3',
		'before_widget' => '<div class="site-footer__widget-item">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

// Custom Post Types
function create_posttype() {
 
    register_post_type( 'Podcasts',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Podcasts' ),
                'singular_name' => __( 'Podcast' ),
				'menu_name' => __( 'Podcasts' ),
				'add_new' => __( 'Add New Podcast' ), 
				'add_new_item' => __( 'Add New Podcast' ), 
            ),
            'public' => true,
            'has_archive' => true,
			'show_ui' => true,
			'supports'=> array( 'title', 'editor', 'revisions', 'thumbnail', 'page-attributes' ),
			'capability_type' => 'post',
			'taxonomies' => array('podcast_category'),
			'menu_icon' => 'dashicons-controls-volumeon'
        )
    );
	
	$cat_labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'podcasts' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'podcasts' ),
		'search_items'      => __( 'Search Categories', 'podcasts' ),
		'all_items'         => __( 'All Categories', 'podcasts' ),
		'parent_item'       => __( 'Parent Category', 'podcasts' ),
		'parent_item_colon' => __( 'Parent Category:', 'podcasts' ),
		'edit_item'         => __( 'Edit Category', 'podcasts' ),
		'update_item'       => __( 'Update Category', 'podcasts' ),
		'add_new_item'      => __( 'Add New Category', 'podcasts' ),
		'new_item_name'     => __( 'New Category Name', 'podcasts' ),
		'menu_name'         => __( 'Category', 'podcasts' ),
        );
    $podcast_category = array(
        'hierarchical'      => true,
        'labels'            => $cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true
    );
	
    register_taxonomy( 'podcast_category', 'podcasts', $podcast_category );
	
}

// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

// Custom Excerpt Size
function custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Add Theme Options Support via ACF
add_action('init', function() {
    if (function_exists('acf_add_options_page')) {
		acf_add_options_sub_page(array(
			'page_title' => 'Website Variables',
			'menu_title' => 'Website Variables'
		));
		
		acf_add_options_sub_page(array(
			'page_title' => 'Footer',
			'menu_title' => 'Footer'
		));
		
		acf_add_options_sub_page(array(
			'page_title' => 'Global Settings',
			'menu_title' => 'Global Settings'
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Mobile Menu',
			'menu_title' => 'Mobile Menu'
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Social Media',
			'menu_title' => 'Social Media'
		));
    }
});

// Lazy Load Youtube Videos on Breeder Page
add_action( 'wp_footer', 'lazy_load_youtube' );

function lazy_load_youtube() {
?>
<script>
    // Lazy Load Youtube Videos
		( function() {

			var youtube = document.querySelectorAll( ".youtube" );

			for (var i = 0; i < youtube.length; i++) {

// 				var source = "https://img.youtube.com/vi/"+ youtube[i].dataset.embed +"/sddefault.jpg";

// 				var image = new Image();
// 						image.src = source;
// 						image.addEventListener( "load", function() {
// 							youtube[ i ].appendChild( image );
// 						}( i ) );

						youtube[i].addEventListener( "click", function() {

							var iframe = document.createElement( "iframe" );

									iframe.setAttribute( "frameborder", "0" );
									iframe.setAttribute( "allowfullscreen", "" );
									iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );

									this.innerHTML = "";
									this.appendChild( iframe );
						} );    
			};

		} )(); // End Youtube Videos
</script>
<?php
}

// Lazy Load Vimeo Videos
add_action( 'wp_footer', 'lazy_load_vimeo' );

function lazy_load_vimeo() {
?>
<script>
    // Lazy Load Vimeo Videos
		( function() {

			var vimeo = document.querySelectorAll( ".vimeo" );

			for (var i = 0; i < vimeo.length; i++) {

						vimeo[i].addEventListener( "click", function() {

							var iframe = document.createElement( "iframe" );

									iframe.setAttribute( "frameborder", "0" );
									iframe.setAttribute( "allowfullscreen", "" );
									iframe.setAttribute( "src", "https://player.vimeo.com/video/"+ this.dataset.embed +"?color&amp;autopause=0&amp;loop=0&amp;muted=0&amp;title=1&amp;portrait=1&amp;byline=1#t=" );

									this.innerHTML = "";
									this.appendChild( iframe );

                  // Adding the class "has-video" to the clicked element
                  this.classList.add('has-video');
						} );    
			};

		} )(); // End Vimeo Videos
</script>
<?php
}

// Add Category slug name to Body Classes
function pn_body_class_add_categories( $classes ) {
 
	// Only proceed if we're on a single post page
	if ( is_single() ) {
            // Get the categories that are assigned to this post
            $post_categories = get_the_category();
            // Loop over each category in the $categories array
            foreach( $post_categories as $current_category ) {
                    // Add the current category's slug to the $body_classes array
                    $classes[] = 'category-' . $current_category->slug;
            }
	}
	if( is_singular( 'product' ) ) {
            $custom_terms = get_the_terms(0, 'product_cat');
            if ($custom_terms) {
              foreach ($custom_terms as $custom_term) {
                    $classes[] = 'product_cat_' . $custom_term->slug;
              }
            }
        }
	
	if ( $theme_colors = sp_get_field( 'theme_colors' ) ){
            $theme_colors  = esc_attr( trim( $theme_colors ) );
            $classes[] = $theme_colors;
        } 
 
	// Finally, return the $body_classes array
	return $classes;
}

add_filter( 'body_class', 'pn_body_class_add_categories' );

function sp_get_field( $selector, $object = false ){
    if ( function_exists('get_field' ) ) {
        return get_field($selector, $object);
    }
}

// Add Custom Class to the Body Classes
add_filter( 'body_class', 'color_theme_body_class' );
 
function color_theme_body_class( $classes ){
  
  if ( $theme_colors = get_field( 'theme_colors' ) ) {

	  $theme_colors  = esc_attr( trim( $theme_colors ) );

	  $classes[] = $theme_colors;

  }  
  return $classes;
}

// Custom Pagination for Post Navigation
function wpbeginner_numeric_posts_nav() {
 
    if( is_singular() )
        return;
 
    global $wp_query;
 
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
 
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
 
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
 
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
 
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
 
    echo '<div class="navigation"><ul>' . "\n";
 
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link() );
 
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
 
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
 
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
 
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
 
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
 
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
 
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link() );
 
    echo '</ul></div>' . "\n";
 
}

// Number Pagination Function - See Search Results for placement
function starterpistol_number_pagination() {

global $wp_query;
$big = 9999999; // need an unlikely integer
  echo paginate_links( array(
   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
   'format' => '?paged=%#%',
   'current' => max( 1, get_query_var('paged') ),
   'total' => $wp_query->max_num_pages) );
}

// Disable Default Image Links
function wpb_imagelink_setup() {
    $image_set = get_option( 'image_default_link_type' );
      
    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

// Disable Gravity Forms Auto Updates
add_filter('gform_disable_auto_update', '__return_true', PHP_INT_MAX);
add_filter('option_gform_enable_background_updates', '__return_false', PHP_INT_MAX);

// Define a function to convert an address into a clickable Google Maps link
function address_to_google_maps_link($address) {
    // Encode the address for use in URL
    $encoded_address = urlencode($address);

    // Construct the Google Maps URL
    $google_maps_url = "https://www.google.com/maps/search/?api=1&query=" . $encoded_address;

    // Generate the HTML for the link
    $link_html = '<a class="directions" href="' . $google_maps_url . '" target="_blank">Get Directions</a>';

    // Return the HTML
    return $link_html;
}

// Adding Template Part Shortcode Recognition - to activate use [embed_template_part template="template-part-name"]
function embed_template_part_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'template' => '', // Default template part
        ), 
        $atts, 
        'embed_template_part'
    );

    if (!empty($atts['template'])) {
        ob_start();
        get_template_part('template-parts/' . $atts['template']);
        return ob_get_clean();
    }

    return '';
}
add_shortcode('embed_template_part', 'embed_template_part_shortcode');