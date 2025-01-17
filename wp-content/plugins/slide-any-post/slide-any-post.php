<?php
/**
* Plugin Name: Slide Any Post - Generate a Slider/Carousel for ANY Post Type
* Plugin URI: http://edgewebpages.com/slide-any-post/
* Description: SLIDE ANY POST allows you to generate a carousel/slider from ANY WordPress post type. You can generate a carousel/slider from standard WordPress posts/pages, using WooCommerce products, or you may use any other custom post types within your site. You slide content can include post titles, post descriptions, post links, featured images and any other meta data associated with a post. You can also use post taxonomies (categories and tags) to filter the posts included within the sliders you create.
* Author: Simon Edge
* Version: 1.0.0
* License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit; // EXIT IF ACCESSED DIRECTLY

// SET CONSTANT FOR PLUGIN PATH
define('SAP_PLUGIN_PATH', plugins_url('/', __FILE__));

require 'php/slide-any-post-admin.php';
require 'php/slide-any-post-frontend.php';

/* ##### PLUGIN ACTIVATION HOOK ##### */
register_activation_hook(__FILE__, 'sapa_slider_plugin_activation' );

/* ##### ADD ACTION HOOKS & FILTERS FOR PLUGIN ##### */
add_action('admin_enqueue_scripts', 'sapa_register_admin_scripts', 999999);
add_action('init', 'sapa_slider_register');
add_action('post_row_actions', 'sapa_slider_row_actions', 10, 2);
add_action('add_meta_boxes', 'sapa_slider_add_meta_boxes');
add_action('edit_form_after_title', 'sapa_move_advanced_meta_boxes_to_top');
add_action('save_post', 'sapa_slider_save_postdata');
add_filter('manage_sap_slider_posts_columns', 'sapa_slider_modify_columns');
add_filter('manage_sap_slider_posts_custom_column', 'sapa_slider_custom_column_content');
add_action('admin_head', 'add_tinymce_sap_button');
add_action('admin_footer', 'get_tinymce_sap_shortcode_array', 9999999);

// LINK THE AJAX ACTION 'post_type_filters' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_post_type_filters', 'display_post_type_filters');
add_action('wp_ajax_nopriv_post_type_filters', 'display_post_type_filters');

// LINK THE AJAX ACTION 'post_id_lookup' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_post_id_lookup', 'display_post_id_lookup_list');
add_action('wp_ajax_nopriv_post_id_lookup', 'display_post_id_lookup_list');

// LINK THE AJAX ACTION 'tax_slug_lookup' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_tax_slug_lookup', 'display_tax_slug_lookup_list');
add_action('wp_ajax_nopriv_tax_slug_lookup', 'display_tax_slug_lookup_list');

// LINK THE AJAX ACTION 'post_type_sorting' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_post_type_sorting', 'display_post_type_sorting');
add_action('wp_ajax_nopriv_post_type_sorting', 'display_post_type_sorting');

// LINK THE AJAX ACTION 'post_type_insert_fields' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_post_type_insert_fields', 'display_post_type_insert_fields');
add_action('wp_ajax_nopriv_post_type_insert_fields', 'display_post_type_insert_fields');

// LINK THE AJAX ACTION 'slide_background_fields' TO A SPECIFIC PHP FUNCTION
add_action('wp_ajax_slide_background_fields', 'display_slide_background_fields');
add_action('wp_ajax_nopriv_slide_background_fields', 'display_slide_background_fields');

/* ##### PLUGIN ACTION HOOKS FOR THE SETTINGS PAGE ##### */
add_action('admin_menu', 'sapost_register_options_page');
add_action('admin_init', 'sapost_register_settings_group');
?>