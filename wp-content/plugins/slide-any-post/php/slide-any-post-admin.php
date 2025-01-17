<?php
// #####################################################################
// ### SLIDE ANY POST PLUGIN - PHP FUNCTIONS FOR WORDPRESS DASHBOARD ###
// #####################################################################

// ###### PLUGIN REGISTRATION HOOK - RUN WHEN THE PLUGIN IS ACTIVATED ######
function sapa_slider_plugin_activation() {
}



// ###### ACTION HOOK - REGISTER SCRIPTS (JS AND CSS) FOR WORDPRESS DASHBOARD ONLY ######
function sapa_register_admin_scripts() {
	$screen = get_current_screen();
	if ($screen->post_type == 'sap_slider') {
		// ONLY LOAD SCRIPTS (JS & CSS) WITHIN 'Slide Any Post' SCREENS IN WORDPRESS DASHBOARD
		// load 'wordpress jquery-ui' scripts
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-resize');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-tooltip');
		wp_enqueue_script('jquery-ui-spinner');
		// load 'spectrum colorpicker' script and css
		wp_register_script('spectrum_js', SAP_PLUGIN_PATH.'spectrum/spectrum.js', array('jquery'));
		wp_enqueue_script('spectrum_js');
		wp_register_style('spectrum_css', SAP_PLUGIN_PATH.'spectrum/spectrum.css');
		wp_enqueue_style('spectrum_css');
		// load 'jquery-ui' css
		wp_register_style('admin_ui_css', SAP_PLUGIN_PATH.'css/admin-user-interface.min.css');
		wp_enqueue_style('admin_ui_css');
		// load 'font awesome' css
		wp_register_style('font-awesome_css', SAP_PLUGIN_PATH.'font-awesome/css/font-awesome.min.css');
		wp_enqueue_style('font-awesome_css');
		// load 'slide-anything' custom javasript and css for wordpress admin
		wp_register_script('sa-slider-admin-script', SAP_PLUGIN_PATH.'js/slide-any-post-admin.js', array( 'jquery' ));
		wp_enqueue_script('sa-slider-admin-script');
		wp_localize_script('sa-slider-admin-script', 'wordpress_urls', array('siteurl' => get_site_url()));
		wp_register_style('sa-slider-admin-css', SAP_PLUGIN_PATH.'css/slide-any-post-admin.css', array(), '1.0', 'all');
		wp_enqueue_style('sa-slider-admin-css');
		// DISABLE AUTOSAVE FOR THIS CUSTOM POST TYPE (causes issues with preview modal popup)
		wp_dequeue_script('autosave');
	}
}



// ###### ACTION HOOK - REGISTER THE 'Slide Any Post' CUSTOM POST TYPE ######
function sapa_slider_register() {
	// ### VALIDATE 'SLIDE ANY POST' LICENSE KEY ###
	$valid_key = validate_slide_any_post_license_key();
	if ($valid_key == 1) {
		$labels = array(
			'name' => _x('SA Post Sliders', 'post type general name', 'sap_slider_textdomain'),
			'singular_name' => _x('Post Slider', 'post type singular name', 'sap_slider_textdomain'),
			'menu_name' => __('SA Post Sliders', 'sap_slider_textdomain'),
			'add_new' => __('Add New Post Slider', 'sap_slider_textdomain'),
			'add_new_item' => __('Add New Post Slider', 'sap_slider_textdomain'),
			'edit_item' => __('Edit Post Slider', 'sap_slider_textdomain'),
			'new_item' => __('New Post Slider', 'sap_slider_textdomain'),
			'view_item' => __('View Post Slider', 'sap_slider_textdomain'),
			'not_found' => __('No post sliders found', 'sap_slider_textdomain'),
			'not_found_in_trash' => __('No post sliders found in Trash', 'sap_slider_textdomain'),
		);
		$args = array(
			'labels' => $labels,
			'description' => __('Slide Any Post carousel/slider', 'sap_slider_textdomain'),
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'menu_position' => 10,
			'menu_icon' => SAP_PLUGIN_PATH."/images/wp_menu_icon.png",
			'hierarchical' => false,
			'supports' => array('title', 'editor'),
			'has_archive' => false,
			'query_var' => false,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type('sap_slider', $args);
	}
}



// ##### SETTINGS PAGE - REGISTER OPTIONS PAGE #####
function sapost_register_options_page() {
	add_options_page('Slide Any Post', 'Slide Any Post', 'manage_options', 'slide-any-post', 'sapost_settings_page');
}

// ##### SETTINGS PAGE - REGISTER SETTINGS GROUP #####
function sapost_register_settings_group() {
	register_setting('sapost-plugin-settings', 'sapost_license_key');
	register_setting('sapost--plugin-settings', 'sapost_valid_license');
	register_setting('sapost--plugin-settings', 'sapost_activated_timestamp');
}

// ##### SETTINGS PAGE - HTML CODE FOR SETTINGS FORM #####
function sapost_settings_page() {
	echo "<div class='wrap'>\n";
	echo "<h2 style='margin:10px 0px 20px; padding:0px;'>Slide Any Post Settings</h2>\n";
	// GET CURRENT SETTINGS FOR PLUGIN
	$license_key = esc_attr(get_option('sapost_license_key'));
	$valid_license = esc_attr(get_option('sapost_valid_license'));
	$activated_timestamp = esc_attr(get_option('sapost_activated_timestamp'));
	if ($activated_timestamp == '') {
		$activated_timestamp = 0;
	}
	$error_message = '';

	// SETTINGS HAVE BEEN UPDATED - ATTEMPT TO ACTIVATE LICENSE KEY
	if (isset( $_GET['settings-updated'])) {
		// URL where the WooCommerce Software License plugin is installed
		define('SL_APP_API_URL', 'https://edgewebpages.com/sap/index.php');
		// Software Unique ID as defined within product admin page
		define('SL_PRODUCT_ID', 'SAPOST');
		// Get domain URL of this WordPress install (minus protocol prefix)
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		define('SL_INSTANCE', str_replace($protocol, "", get_bloginfo('wpurl')));
		// PERFORM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		$args = array(
			'woo_sl_action'		=> 'activate',
			'licence_key'			=> $license_key,
			'product_unique_id'	=> SL_PRODUCT_ID,
			'domain'					=> SL_INSTANCE
		);
		$request_uri = SL_APP_API_URL.'?'.http_build_query($args);
		$data = wp_remote_get($request_uri);
		// CHECK DATA RETURNED FROM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		if (is_wp_error($data) || $data['response']['code'] != 200) {
			// problem establishing connection to API server
			$error_message = 'There was a problem connecting to the License Server - please try again later.';
		} else {
			$data_body_arr = json_decode($data['body']);
			$data_body = $data_body_arr[0];
			$data_status	= $data_body->status;
			$data_code		= $data_body->status_code;
			$data_message	= $data_body->message;
			if (isset($data_status)) {
				if (($data_status == 'success') && ($data_code == 's100')) {
					// the license key has been successfully activated - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				} elseif (($data_status == 'error') && ($data_code == 'e113')) {
					// license key is already activated for this domain - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				} else {
					// the license key cannot be activated - set plugin license to invalid and deactivate license
					$error_message = 'The license key provided cannot be activated. Please check that you have entered the correct license key.';
					$valid_license = '0';
					$activated_timestamp = 0;
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				}
			} else {
				// problem establishing connection to API server
				$error_message = 'There was a problem connecting to the License Server - please try again later.';
			}
		}
	}

	// SETTINGS OPTION FORM
	echo "<form action='options.php' method='post'>\n";
	settings_fields('sapost-plugin-settings');
	do_settings_sections('sapost-plugin-settings');
	if ($error_message != '') {
		echo "<h4 style='margin:0px; padding:0px; color:crimson;'>".$error_message."</h4>";
	}
	echo "<table style='margin:30px 0px 0px'>\n";
	echo "<tr>\n";
	echo "<th align='left' style='min-width:80px;'>License Key</th>\n";
	echo "<td><input type='text' placeholder='Enter Slide Any Post License Key' name='sapost_license_key' ";
	echo "value='".$license_key."' size='40'/></td>\n";
	echo "</tr><tr>\n";
	echo "<td colspan='2'>".get_submit_button()."</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<input type='hidden' name='sapost_valid_license' value='".$valid_license."'/>\n";
	echo "<input type='hidden' name='sapost_activated_timestamp' value='".$activated_timestamp."'/>\n";
	echo "</form>\n";

	// DISPLAY ACTIVATION STATUS
	echo "<h3 style='margin:0px; padding:30px 0px 10px;'>Activation Status</h3>";
	if ($valid_license == '1') {
		echo "<h4 style='display:inline-block; margin:0px; padding:10px 15px; background:green; color:white; border-radius:5px;'>ACTIVE</h4>\n";
	} else {
		echo "<h4 style='display:inline-block; margin:0px; padding:10px 15px; background:crimson; color:white; border-radius:5px;'>INACTIVE</h4>\n";
		echo "<h3 style='margin:0px; padding:30px 0px 10px;'>Activation Instructions </h3>\n";
		echo "<ul style='list-style-type: disc; margin:0px 0px 0px 20px;'>\n";
		echo "<li>Get a your Slide Any Post License Key from the '<strong>Order Complete</strong>' email sent to you</li>\n";
		echo "<li>Alternatively, get a your License Key from your '<strong>My Account -> Orders</strong>' page on the '<em>Slide Any Post</em>' ";
		echo "website by clicking <strong><a href='https://edgewebpages.com/sap/my-account/orders/' target='_blank'>HERE</a></strong>, ";
		echo "and then clicking  the '<strong>License Manage</strong>' button for your order.</li>\n";
		echo "<li>Copy/Paste this License Key into the '<strong>License Key</strong>' text box above and click the ";
		echo "'<strong>Save Changes</strong>' button.</li>\n";
		echo "</ul>\n";
	}
	echo "</div>\n"; // .wrap
}

// ##### VALIDATE THAT LICENSE KEY FOR THIS PLUGIN EXISTS ON THE LICENSE SERVER #####
function validate_slide_any_post_license_key() {
	$valid = false;
	$current_timestamp = time();

	// GET CURRENT SETTINGS FOR PLUGIN
	$license_key = esc_attr(get_option('sapost_license_key'));
	$valid_license = esc_attr(get_option('sapost_valid_license'));
	$activated_timestamp = esc_attr(get_option('sapost_activated_timestamp'));
	if ($activated_timestamp == '') {
		$activated_timestamp = 0;
	}

	// ONE DAY HAS ELAPSED SINCE LAST ACTIVATION - REACTIVATE/CHECK CURRENT LICENSE KEY
	$seconds_elapsed = $current_timestamp - $activated_timestamp;
	if ($seconds_elapsed > 86400) {
		// URL where the WooCommerce Software License plugin is installed
		define('SL_APP_API_URL', 'https://edgewebpages.com/sap/index.php');
		// Software Unique ID as defined within product admin page
		define('SL_PRODUCT_ID', 'SAPOST');
		// Get domain URL of this WordPress install (minus protocol prefix)
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		define('SL_INSTANCE', str_replace($protocol, "", get_bloginfo('wpurl')));
		// PERFORM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		$args = array(
			'woo_sl_action'		=> 'activate',
			'licence_key'			=> $license_key,
			'product_unique_id'	=> SL_PRODUCT_ID,
			'domain'					=> SL_INSTANCE
		);
		$request_uri = SL_APP_API_URL.'?'.http_build_query($args);
		$data = wp_remote_get($request_uri);
		// CHECK DATA RETURNED FROM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		if (is_wp_error($data) || $data['response']['code'] != 200) {
			// problem establishing connection to API server - do nothing
		} else {
			$data_body_arr = json_decode($data['body']);
			$data_body = $data_body_arr[0];
			$data_status	= $data_body->status;
			$data_code		= $data_body->status_code;
			$data_message	= $data_body->message;
			if (isset($data_status)) {
				if (($data_status == 'success') && ($data_code == 's100')) {
					// the license key has been successfully activated - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				} elseif (($data_status == 'error') && ($data_code == 'e113')) {
					// license key is already activated for this domain - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				} else {
					// the license key cannot be activated - set plugin license to invalid and deactivate license
					$valid_license = '0';
					$activated_timestamp = 0;
					update_option('sapost_valid_license', $valid_license);
					update_option('sapost_activated_timestamp', $activated_timestamp);
				}
			} else {
				// problem establishing connection to API server - do nothing
			}
		}
	}

	if ($valid_license == '1') {
		$valid = true;
	}
	return $valid;
}



// ###### WP DASHBOARD - SLIDER LIST PAGE ######
// ACTION HOOK - ADD/REMOVE (HOVER-OVER) ROW ACTIONS WHEN THIS CUSTOM POST TYPE IS LISTED IN DASHBOARD
function sapa_slider_row_actions($actions, $post) {
	if ($post->post_type == 'sap_slider') {
		// REMOVE 'Quick Edit' ROW ACTION
		unset($actions['inline hide-if-no-js']);
	}
	return $actions;
}
// FILTER TO ADD/REMOVE COLUMNS DISPLAYED FOR THIS CUSTOM POST TYPE WITHIN THE DASHBOARD
function sapa_slider_modify_columns($columns) {
	// new columns to be added
	$new_columns = array(
		'shortcode' => 'Shortcode',
		'css-id' => 'CSS ID'
	);
	$columns = array_slice($columns, 0, 2, true) + $new_columns + array_slice($columns, 2, NULL, true);
	return $columns;
}
// DEFINE OUTPUT FOR EACH CUSTOM COLUMN DISPLAYED FOR THIS CUSTOM POST TYPE WITHIN THE DASHBOARD
function sapa_slider_custom_column_content($column) {
	// get post object for this row
	global $post;

	// output for the 'Shortcode' column
	if ($column == 'shortcode') {
		$shortcode = "[slide-any-post id='".$post->ID."']";
		echo esc_html($shortcode);
	}

	// output for the 'CSS ID' column
	if ($column == 'css-id') {
		$css_id = get_post_meta($post->ID, 'sap_css_id', true);
		if ($css_id == '') {
			$css_id = '-';
		} else {
			$css_id = "#".$css_id;
		}
		echo esc_html($css_id);
	}
}



// ###### ADD A CUSTOM BUTTON TO WORDPRESS TINYMCE EDITOR (ON PAGES AND POSTS ONLY) ######
function add_tinymce_sap_button() {
	global $typenow;
	// ### VALIDATE 'SLIDE ANY POST' LICENSE KEY ###
	$valid_key = validate_slide_any_post_license_key();
	if ($valid_key == 1) {
		// check user permissions
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
			return;
		}
		// verify the post type - only display button on posts and pages
		if (!in_array($typenow, array('post', 'page'))) {
			return;
		}
		// check if WYSIWYG is enabled
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', 'add_tinymce_sap_plugin');
			add_filter('mce_buttons', 'register_tinymce_sap_button');
		}
	}
}
function add_tinymce_sap_plugin($plugin_array) {
	$plugin_array['tinymce_sap_button'] = SAP_PLUGIN_PATH.'js/add_tinymce_button.js';
	return $plugin_array;
}
function register_tinymce_sap_button($buttons) {
	array_push($buttons, 'tinymce_sap_button');
	return $buttons;
}
function get_tinymce_sap_shortcode_array() {
	$screen = get_current_screen();
	if ($screen->post_type != 'envira') { // ### BUG FIX - CLASHING WITH ENVIRA GALLERY (VER 2.0.13) ###
		// display 2 javascript arrays (in footer) containing all the 'slide any post' post titles and post ids
		// these 2 arrays are used to display the shortcode options by the TinyMCE button
		echo "<script type='text/javascript'>\n";
		echo "var sap_title_arr = new Array();\n";
		echo "var sap_id_arr = new Array();\n";

		$args = array('post_type' => 'sap_slider', 'post_status' => 'publish', 'posts_per_page' => -1);
		$sap_slider_query = new WP_Query($args);
		$count = 0;
		while ($sap_slider_query->have_posts()) : $sap_slider_query->the_post();
			$title = get_the_title();
			echo "sap_title_arr[".$count."] = '".$title."';\n";
			echo "sap_id_arr[".$count."] = '".get_the_ID()."';\n";
			$count++;
		endwhile;
		echo "</script>\n";
	}
}



// ###### ACTION HOOK - ADD META BOXES TO THE 'Slide Any Post' CUSTOM POST TYPE ######
function sapa_slider_add_meta_boxes() {
	global $post;
	add_meta_box('sapa_slider_post_type', __('WordPress Post Type'), 'sapa_slider_post_type_content', 'sap_slider', 'advanced', 'high');
	add_meta_box('sapa_slider_insert', __('Insert Post Type Fields'), 'sapa_slider_insert_fields', 'sap_slider', 'advanced', 'high');
	add_meta_box('sapa_slider_settings', __('Slider Settings'), 'sapa_slider_settings_content', 'sap_slider', 'normal', 'high');
	add_meta_box('sapa_slider_shortcode', __('Shortcode / Preview'), 'sapa_slider_shortcode_content', 'sap_slider', 'side', 'high');
	add_meta_box('sapa_slide_background', __('Slide Background'), 'sapa_slide_background_content', 'sap_slider', 'side', 'default');
	add_meta_box('sapa_slider_items', __('Items Displayed'), 'sapa_slider_items_content', 'sap_slider', 'side', 'default');
	add_meta_box('sapa_slider_style', __('Slider Style'), 'sapa_slider_style_content', 'sap_slider', 'side', 'default');
	remove_meta_box( 'mymetabox_revslider_0', 'sap_slider', 'normal' ); // remove revolution slider meta box
}
// ###### ACTION HOOK - MOVE ALL META BOXES WITH AN 'advanced' CONTEXT TO THE TOP OF THE PAGE (above the editor) ######
function sapa_move_advanced_meta_boxes_to_top() {
	global $post, $wp_meta_boxes;
	do_meta_boxes(get_current_screen(), 'advanced', $post);
	unset($wp_meta_boxes[get_post_type($post)]['advanced']);
}



// ###### META BOX CONTENT - 'WordPress Post Type' BOX ######
function sapa_slider_post_type_content($post) {
	echo "<div id='sap_slider_post_type'>\n";
	// ### DISPLAY CURRENT SLIDER POST ID IN A HIDDEN INPUT FIELD ###
	echo "<input type='hidden' id='sap_post_id' name='sap_post_id' value='".esc_attr($post->ID)."'>\n";

	// ### GET POST TYPE DATA AND SAVE IN POST TYPE ARRAY ###
	$post_type_arr = array();
	$count = 0;
	$args = array('public' => true); // public post types only
	$post_types = get_post_types($args, 'objects');
	foreach ($post_types as $post_type) {
		if (($post_type->name == 'page') || ($post_type->name == 'attachment')) {
			// exclude these post type/s
		} else {
			$post_name = $post_type->name;
			// check that the post type contains at least 1 published post
			$count_posts = wp_count_posts($post_name)->publish;
			if ($count_posts > 0) {
				$post_type_arr[$count]['label'] = $post_type->label;
				$post_type_arr[$count]['name'] = $post_type->name;
				$count++;
			}
		}
	}

	// ### 1) DISPLAY DROPDOWN CONTAINING THE POST TYPES AVAILABLE ###
	echo "<h3>1) Select WordPress Post Type for slider content</h3>\n";

	$post_type = get_post_meta($post->ID, 'sap_post_type', true);
	echo "<div id='sap_post_type_line'><span>Select Post Type:</span>\n";
	echo "<select id='sap_post_type' name='sap_post_type'>";
	for ($i = 0; $i < count($post_type_arr); $i++) {
		if ($post_type == $post_type_arr[$i]['name']) {
			echo "<option value='".$post_type_arr[$i]['name']."' selected>".$post_type_arr[$i]['label']."</option>";
		} else {
			echo "<option value='".$post_type_arr[$i]['name']."'>".$post_type_arr[$i]['label']."</option>";
		}
	}
	echo "</select>";
	echo "</div>\n";
	echo "<hr/>\n";

	// ### 2) ADD SELECTION CRITERIA TO FILTER WHICH POSTS ARE DISPLAYED ###
	echo "<h3>2) Add selection criteria to filter posts</h3>\n";
	// AJAX CONTAINER - POST TYPE FILTERS
	echo "<div id='ajax_container_post_type_filters'><div class='ajax_loading'>LOADING...</div></div>";
	echo "<hr/>\n";

	// ### 3) SET THE SORT ORDER FOR THE SLIDES WITHIN THIS SLIDER ###
	echo "<h3>3) Set the sort order for the slides</h3>\n";
	// AJAX CONTAINER - POST TYPE SORTING
	echo "<div id='ajax_container_post_type_sorting'><div class='ajax_loading'>LOADING...</div></div>";

	// ### POST ID LOOKUP POPUP (WITH AJAX CONTAINER) ###
	echo "<div id='post_id_lookup'>\n";
	echo "<div id='post_id_lookup_container'>\n";
	echo "<span class='lookup_close' onClick='document.getElementById(\"post_id_lookup\").style.display=\"none\"; ";
	echo "document.body.style.overflow=\"auto\";'>X</span>\n";
	echo "<h3>POST ID LOOKUP</h3>\n";
	// AJAX CONTAINER - POST ID LOOKUP POPUP
	echo "<div id='ajax_container_post_id_lookup'><div class='popup_ajax_loading'>LOADING...</div></div>";
	echo "</div>\n"; // #slug_lookup_container
	echo "</div>\n"; // #post_id_lookup

	// ### TAXONOMY SLUG LOOKUP POPUP (WITH AJAX CONTAINER) ###
	echo "<div id='tax_slug_lookup'>\n";
	echo "<div id='slug_lookup_container'>\n";
	echo "<span class='lookup_close' onClick='document.getElementById(\"tax_slug_lookup\").style.display=\"none\"; ";
	echo "document.body.style.overflow=\"auto\";'>X</span>\n";
	echo "<h3>TAXONOMY SLUG LOOKUP</h3>\n";
	// AJAX CONTAINER - TAXONOMY SLUG LOOKUP POPUP
	echo "<div id='ajax_container_tax_slug_lookup'><div class='popup_ajax_loading'>LOADING...</div></div>";
	echo "</div>\n"; // #slug_lookup_container
	echo "</div>\n"; // #tax_slug_lookup

	echo "</div>\n"; // #sap_slider_post_type
}



// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'POST TYPE FILTERS' AJAX CONTAINER ######
function display_post_type_filters() {
	// GET THE 'post_type' PARAMETER PASSED
	$post_type = $_REQUEST['post_type'];
	// GET THE 'post_id' PARAMETER PASSED
	$post_id = $_REQUEST['post_id'];

	// GET THE POST TYPE THIS SLIDER IS CURRENTLY SET TO USE
	$curr_post_type = get_post_meta($post_id, 'sap_post_type', true);

	// ### 1) FIND OUT WHAT FEATURES THE POST TYPE SUPPORTS ###
	$title_yn = post_type_supports($post_type, 'title');
	$editor_yn = post_type_supports($post_type, 'editor');
	$excerpt_yn = post_type_supports($post_type, 'excerpt');
	$thumb_yn = post_type_supports($post_type, 'thumbnail');

	// ### 2) GET ALL TAXONOMIES FOR THIS POST TYPE ###
	$tax_arr = get_taxonomies_for_post_type($post_type);

	// ### 3) GET ALL META KEYS FOR THIS POST TYPE ###
	$meta_arr = get_meta_keys_for_post_type($post_type);
	$meta_arr2 = array();
	for ($i = 0; $i < count($meta_arr); $i++) {
		if (($post_type == 'product') &&
			 (($meta_arr[$i] == '_length') || ($meta_arr[$i] == '_width') || ($meta_arr[$i] == '_height') ||
			  ($meta_arr[$i] == '_weight'))) {
			// exclude specifc woocommerce meta keys as filters
		} elseif (strpos($meta_arr[$i], "IMAGE~") === 0) {
			// exclude meta keys marked as images
		} else {
			$meta_arr2[] = $meta_arr[$i];
		}
	}
	$meta_arr = $meta_arr2;

	// ### 4) GET CURRENT VALUES FOR TAXONOMY FILTERS FOR THIS SLIDER ###
	$curr_tax_arr = array();
	if ($post_type == $curr_post_type) {
		for ($i = 0; $i < count($tax_arr); $i++) {
			$curr_tax_no = $i + 1;
			$curr_tax_arr[$i]['name'] = get_post_meta($post_id, "sap_filter_tax".$curr_tax_no."_name", true);
			$curr_tax_arr[$i]['oper'] = get_post_meta($post_id, "sap_filter_tax".$curr_tax_no."_oper", true);
			$curr_tax_arr[$i]['value'] = get_post_meta($post_id, "sap_filter_tax".$curr_tax_no."_value", true);
		}
	}

	// ### 5) GET CURRENT VALUES FOR META KEY FILTERS FOR THIS SLIDER ###
	$curr_meta_arr = array();
	if ($post_type == $curr_post_type) {
		for ($i = 0; $i < count($meta_arr); $i++) {
			$curr_meta_no = $i + 1;
			$curr_meta_arr[$i]['name'] = get_post_meta($post_id, "sap_filter_meta".$curr_meta_no."_name", true);
			$curr_meta_arr[$i]['oper'] = get_post_meta($post_id, "sap_filter_meta".$curr_meta_no."_oper", true);
			$curr_meta_arr[$i]['value'] = get_post_meta($post_id, "sap_filter_meta".$curr_meta_no."_value", true);
		}
	}

	// ### 6) DISPLAY THE 'POST IDS' FILTER, 'POST TITLE' FILTER AND 'HAS A FEATURED IMAGE' INPUT FIELDS ###

	// POST IDS FILTER
	$curr_post_id_oper = 'IN';
	$curr_post_id_value = '';
	if ($post_type == $curr_post_type) {
		$curr_post_id_oper = get_post_meta($post_id, "sap_post_id_oper", true);
		$curr_post_id_value = get_post_meta($post_id, "sap_post_id_value", true);
	}
	echo "<div class='filter_input_line filter_post_id_title'><span>Post IDs</span>";
	// post id drop-down input - operator
	$operators = array('IN', 'NOT IN');
	echo "<select id='sap_post_id_oper' name='sap_post_id_oper' ";
	echo "onChange='check_to_hide_filter_value(\"sap_post_id_oper\", \"sap_post_id_value\", \"\");'>";
	for ($j = 0; $j < count($operators); $j++) {
		if ($operators[$j] == $curr_post_id_oper) {
			echo "<option value='".$operators[$j]."' selected>".$operators[$j]."</option>";
		} else {
			echo "<option value='".$operators[$j]."'>".$operators[$j]."</option>";
		}
	}
	echo "</select>";
	// post id text input field - value
	echo '<input type="text" id="sap_post_id_value" name="sap_post_id_value" value="'.$curr_post_id_value.'" ';
	$placeholder = set_placeholder_for_selected_operator($curr_post_id_oper, "ID");
	echo 'placeholder="'.$placeholder.'">';
	if ($title_yn) {
		// post id lookup button
		echo "<i class='fa fa-search' title='Lookup post IDs for \"".$post_type."\" post type' ";
		echo "onClick='post_id_lookup(\"".$post_type."\");'></i>";
	}
	echo "</div>\n"; // .filter_input_line
	echo "<div style='clear:both; float:none; width:100%; height:1px;'></div>";

	if ($title_yn == '1') {
		// POST TITLE FILTER
		$curr_post_title_oper = 'LIKE';
		$curr_post_title_value = '';
		if ($post_type == $curr_post_type) {
			$curr_post_title_oper = get_post_meta($post_id, "sap_post_title_oper", true);
			$curr_post_title_value = get_post_meta($post_id, "sap_post_title_value", true);
		}
		echo "<div class='filter_input_line filter_post_id_title'><span>Post Title</span>";
		// post title drop-down input - operator
		$operators = array('LIKE', 'NOT LIKE');
		echo "<select id='sap_post_title_oper' name='sap_post_title_oper' ";
		echo "onChange='check_to_hide_filter_value(\"sap_post_title_oper\", \"sap_post_title_value\", \"\");'>";
		for ($j = 0; $j < count($operators); $j++) {
			if ($operators[$j] == $curr_post_title_oper) {
				echo "<option value='".$operators[$j]."' selected>".$operators[$j]."</option>";
			} else {
				echo "<option value='".$operators[$j]."'>".$operators[$j]."</option>";
			}
		}
		echo "</select>";
		// post title text input field - value
		echo '<input type="text" id="sap_post_title_value" name="sap_post_title_value" value="'.$curr_post_title_value.'" ';
		$placeholder = set_placeholder_for_selected_operator($curr_post_title_oper, "title");
		echo 'placeholder="enter search text">';
		echo "</div>\n"; // .filter_input_line
		echo "<div style='clear:both; float:none; width:100%; height:1px;'></div>";

	}
	if ($thumb_yn == '1') {
		// 'HAS A FEATURED IMAGE' CHECKBOX
		$curr_post_thumb_yn = '1';
		if ($post_type == $curr_post_type) {
			$curr_post_thumb_yn = get_post_meta($post_id, "sap_post_thumb_yn", true);
		}
		echo "<div class='filter_checkbox_line'><span>Has a Featured Image</span>";
		if ($curr_post_thumb_yn == '1') {
			echo "<input type='checkbox' id='sap_post_thumb_yn' name='sap_post_thumb_yn' value='1' checked/>";
		} else {
			echo "<input type='checkbox' id='sap_post_thumb_yn' name='sap_post_thumb_yn' value='1' />";
		}
		echo "</div>\n"; // .filter_checkbox_line
		echo "<div style='clear:both; float:none; width:100%; height:1px;'></div>";
	}

	// ### 7) DISPLAY TAXONOMY FILTERS ###
	$operators = array('IN', 'NOT IN', 'EXISTS', 'NOT EXISTS');
	echo "<div class='filter_half_column'>\n";
	echo "<h4>Taxonomy Filters:</h4>";
	// HIDDEN INPUT FIELD (total number of taxonomies)
	echo "<input type='hidden' id='sap_total_taxonomies' name='sap_total_taxonomies' value='".esc_attr(count($tax_arr))."'>\n";

	// LOOP TO DISPLAY TAXONOMY INPUT FIELDS
	for ($i = 0; $i < count($tax_arr); $i++) {
		$curr_tax_no = $i + 1;
		// set input fields css ids/names
		$tax_name_id = 'sap_filter_tax'.$curr_tax_no."_name";
		$tax_oper_id = 'sap_filter_tax'.$curr_tax_no."_oper";
		$tax_value_id = 'sap_filter_tax'.$curr_tax_no."_value";
		// get default (current) values for the input fields
		$curr_tax_oper = 'IN';
		$curr_tax_value = '';
		if ($post_type == $curr_post_type) {
			for ($j = 0; $j < count($tax_arr); $j++) {
				if ($tax_arr[$i]['name'] == $curr_tax_arr[$j]['name']) {
					$curr_tax_oper = htmlspecialchars_decode($curr_tax_arr[$j]['oper']);
					$curr_tax_value = $curr_tax_arr[$j]['value'];
				}
			}
		}

		echo "<div class='filter_input_line'>";
		// display taxonomy field label
		echo "<span title='".$tax_arr[$i]['label']."'>".$tax_arr[$i]['label']."</span>";
		// taxonomy text input field - name (HIDDEN!)
		echo "<input type='hidden' id='".$tax_name_id."' name='".$tax_name_id."' value='".esc_attr($tax_arr[$i]['name'])."'>\n";
		// taxonomy drop-down input - operator
		echo "<select id='".$tax_oper_id."' name='".$tax_oper_id."' ";
		echo "onChange='check_to_hide_filter_value(\"".$tax_oper_id."\", \"".$tax_value_id."\", \"filter_tax".$curr_tax_no."_lookup\");'>";
		for ($j = 0; $j < count($operators); $j++) {
			if ($operators[$j] == $curr_tax_oper) {
				echo "<option value='".$operators[$j]."' selected>".$operators[$j]."</option>";
			} else {
				echo "<option value='".$operators[$j]."'>".$operators[$j]."</option>";
			}
		}
		echo "</select>";
		// taxonomy text input field - value
		echo '<input type="text" id="'.$tax_value_id.'" name="'.$tax_value_id.'" value="'.$curr_tax_value.'" ';
		if (($curr_tax_oper == 'EXISTS') || ($curr_tax_oper == 'NOT EXISTS')) {
			echo "style='visibility:hidden;' ";
		}
		$placeholder = set_placeholder_for_selected_operator($curr_tax_oper, "slug");
		echo 'placeholder="'.$placeholder.'">';
		// taxonomy slug lookup button
		echo "<i id='filter_tax".$curr_tax_no."_lookup' class='fa fa-search' title='Lookup slugs used within \"".$tax_arr[$i]['label']."\" taxonomy' ";
		echo "onClick='taxonomy_slug_lookup(\"".$tax_arr[$i]['name']."\", \"".$tax_value_id."\");'></i>";
		echo "</div>\n"; // .filter_input_line
	}
	if (count($tax_arr) == 0) {
		echo "<div class='no_filters_found'>No Taxonomies found</div>\n";
	}
	if (count($tax_arr) > 1) {
		echo "<div id='meta_filters_note'><strong>NOTE 1:</strong> Use taxonomy <em>slug/s</em> to create taxonomy filters.<br/>";
		echo "<strong>NOTE 2:</strong> Press <i class='fa fa-search'></i> button to perform taxonomy slug lookup.</div>";
	}
	echo "</div>\n"; // .filter_half_column

	// ### 8) DISPLAY META DATA FILTERS ###
	$operators = array('=', '!=', '<', '>', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS', 'NOT EXISTS');
	echo "<div class='filter_half_column fhc_col2'>\n";
	echo "<h4>Meta Data Filters:</h4>";
	// HIDDEN INPUT FIELD (total number of meta keys)
	echo "<input type='hidden' id='sap_total_meta_keys' name='sap_total_meta_keys' value='".esc_attr(count($meta_arr))."'>\n";

	// LOOP TO DISPLAY META DATA INPUT FIELDS
	for ($i = 0; $i < count($meta_arr); $i++) {
		$curr_meta_no = $i + 1;
		// set input fields css ids/names
		$meta_name_id = 'sap_filter_meta'.$curr_meta_no."_name";
		$meta_oper_id = 'sap_filter_meta'.$curr_meta_no."_oper";
		$meta_value_id = 'sap_filter_meta'.$curr_meta_no."_value";
		// get default (current) values for the input fields
		$curr_meta_oper = '=';
		$curr_meta_value = '';
		if ($post_type == $curr_post_type) {
			for ($j = 0; $j < count($meta_arr); $j++) {
				if ($meta_arr[$i] == $curr_meta_arr[$j]['name']) {
					$curr_meta_oper = htmlspecialchars_decode($curr_meta_arr[$j]['oper']);
					$curr_meta_value = $curr_meta_arr[$j]['value'];
				}
			}
		}

		echo "<div class='filter_input_line'>";
		// display meta key field name
		echo "<span>".$meta_arr[$i]."</span>";
		// meta key text input field for name (HIDDEN!)
		echo "<input type='hidden' id='".$meta_name_id."' name='".$meta_name_id."' value='".esc_attr($meta_arr[$i])."'>\n";
		// meta key drop-down input for operator
		echo "<select id='".$meta_oper_id."' name='".$meta_oper_id."' ";
		echo "onChange='check_to_hide_filter_value(\"".$meta_oper_id."\", \"".$meta_value_id."\", \"\");'>";
		for ($j = 0; $j < count($operators); $j++) {
			if ($operators[$j] == $curr_meta_oper) {
				echo "<option value='".$operators[$j]."' selected>".$operators[$j]."</option>";
			} else {
				echo "<option value='".$operators[$j]."'>".$operators[$j]."</option>";
			}
		}
		echo "</select>";
		// meta key text input field for value
		echo '<input type="text" id="'.$meta_value_id.'" name="'.$meta_value_id.'" value="'.$curr_meta_value.'" ';
		if (($curr_meta_oper == 'EXISTS') || ($curr_meta_oper == 'NOT EXISTS')) {
			echo "style='visibility:hidden;' ";
		}
		$placeholder = set_placeholder_for_selected_operator($curr_meta_oper, "value");
		echo 'placeholder="'.$placeholder.'">';
		echo "</div>\n"; // .filter_input_line
	}
	if (count($meta_arr) == 0) {
		echo "<div class='no_filters_found'>No Meta Data found</div>\n";
	}
	echo "</div>\n"; // .filter_half_column

	echo "<div style='clear:both; float:none; with:100%; height:1px;'></div>\n";
	die();
}



// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'POST ID LOOKUP' AJAX CONTAINER ######
function display_post_id_lookup_list() {
	global $wpdb;
	// GET THE 'post_type' PARAMETER PASSED
	$post_type = $_REQUEST['post_type'];

	echo "<div id='post_id_lookup_list'>\n";
	// DISPLAY POST TYPE HEADING
	$post_type_data = get_post_type_object($post_type);
	echo "<h2>".$post_type_data->label."</h2>";

	// SQL QUERY TO FETTCH POST DATA FOR SPECIFIED POST TYPE
	$post_arr = array();
	$count = 0;
	$query = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE post_type = '".$post_type."' AND post_status = 'publish' ORDER BY post_title";
	$result = $wpdb->get_results($query, OBJECT);
	foreach ($result as $row) {
		$post_arr[$count]['id'] = $row->ID;
		$post_arr[$count]['title'] = $row->post_title;
		$count++;
	}

	// START OF POST ID/TITLE LIST TABLE
	echo "<table>\n";
	echo "<tr><th>ID</th><th>Post Title</th><th>&nbsp;</th></tr>\n";

	// LOOP TO DISPLAY ALL POST ID/TITLE ROWS
	for ($i = 0; $i < count($post_arr); $i++) {
		echo "<tr>";
		echo "<td>".$post_arr[$i]['id']."</td>";
		echo "<td>".$post_arr[$i]['title']."</td>";
		echo "<td style='width:40px;'><span class='add_id_button' title='Add \"".$post_arr[$i]['id']."\" to Post IDs filter' ";
		echo "onClick='append_post_id_to_text_input(".$post_arr[$i]['id'].")'>ADD&nbsp;ID</span></td>";
		echo "</tr>\n";
	}
	if (count($post_arr) == 0) {
		echo "<tr><td colspan='3' class='no_posts_found'>NO POSTS FOUND FOR THIS POST TYPE</td></tr>";
	}

	// END OF POST ID/TITLE LIST TABLE
	echo "</table>\n";

	echo "</div>\n"; // #post_id_lookup_list
	die();
}



// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'TAXONOMY SLUG LOOKUP' AJAX CONTAINER () ######
function display_tax_slug_lookup_list() {
	// GET THE 'tax_slug' PARAMETER PASSED
	$tax_slug = $_REQUEST['tax_slug'];
	// GET THE 'input_id' PARAMETER PASSED
	$input_id = $_REQUEST['input_id'];

	echo "<div id='tax_lookup_list'>\n";

	// DISPLAY TAXONOMY LABEL HEADING
	$tax_data = get_taxonomy($tax_slug);
	$tax_label = $tax_data->label;
	echo "<h2>".$tax_label."</h2>";

	// START OF TAXONOMY LIST TABLE
	echo "<table>\n";
	echo "<tr><th>Term Name</th><th>Term Slug</th><th>&nbsp;</th></tr>\n";

	// DISPLAY TAXONOMY TERM ROWS (LOOP)
	$terms = display_taxonomy_hierarchy($tax_slug, $input_id);
	if (count($terms) == 0) {
		echo "<tr><td colspan='3' class='no_terms_found'>NO POSTS FOUND CONTAINING TERMS FOR THIS TAXONOMY</td></tr>";
	}

	// END OF TAXONOMY LIST TABLE
	echo "</table>\n";

	echo "</div>\n"; // #tax_lookup_list
	die();
}

// ### THIS FUNCTION IS RECURSIVELY CALLED - IT IS CALLED ONCE PER 'LEVEL' WITHIN A TAXONOMY HEIRACHY ###
// (This is called by the display_tax_slug_lookup_list()' Ajax function)
function display_taxonomy_hierarchy($taxonomy, $input_id, $parent = 0, $level = 0) {
	$level++;
	$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
	// get all direct decendants of the $parent
	$terms = get_terms( $taxonomy, array( 'parent' => $parent, 'orderby' => 'title' ) );
	// new array into which we copy the children of the parent (but only after they find their own children)
	$children = array();

	// go through all direct decendants of parent, and gather their children
	foreach ($terms as $term){
		//  DISPLAY CONTENTS OF PARENT TERM AS A TABLE ROW
		echo "<tr>";
		echo "<td>";
		for ($i = 1; $i <= $level; $i++) {
			if ($i > 1) { echo "<em></em>"; } // indent
		}
		echo $term->name."<span>(".$term->count.")</span></td>";
		echo "<td>".$term->slug."</td>";
		echo "<td style='width:50px;'><span class='add_slug_button' title='Add slug \"".$term->slug."\" to taxonomy filter' ";
		echo "onClick='append_tax_slug_to_text_input(\"".$term->slug."\", \"".$input_id."\")'>ADD&nbsp;SLUG</span></td>";
		echo "</tr>\n";

		// recursively call this function to get the direct decendants of 'this' term
		$term->children = display_taxonomy_hierarchy($taxonomy, $input_id, $term->term_id, $level);
		// add the term to our new array
		$children[ $term->term_id ] = $term;
	}
	// send the results back to the caller
	return $children;
}



// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'POST TYPE SORTING' AJAX CONTAINER ######
function display_post_type_sorting() {
	// GET THE 'post_type' PARAMETER PASSED
	$post_type = $_REQUEST['post_type'];
	// GET THE 'post_id' PARAMETER PASSED
	$post_id = $_REQUEST['post_id'];

	// GET THE POST TYPE THIS SLIDER IS CURRENTLY SET TO USE
	$curr_post_type = get_post_meta($post_id, 'sap_post_type', true);

	// CHECK IF THIS POST TYPE HAS TITLES
	$title_yn = post_type_supports($post_type, 'title');

	// GET ALL META KEYS FOR THIS POST TYPE
	$meta_arr = get_meta_keys_for_post_type($post_type);
	$meta_arr2 = array();
	for ($i = 0; $i < count($meta_arr); $i++) {
		if (($post_type == 'product') &&
			 (($meta_arr[$i] == '_length') || ($meta_arr[$i] == '_width') || ($meta_arr[$i] == '_height') ||
			  ($meta_arr[$i] == '_weight'))) {
			// exclude specifc woocommerce meta keys as filters
		} elseif (strpos($meta_arr[$i], "IMAGE~") === 0) {
			// exclude meta keys marked as images
		} else {
			$meta_arr2[] = $meta_arr[$i];
		}
	}
	$meta_arr = $meta_arr2;

	// GET CURRENT VALUES FOR THE SORT OPTIONS FOR THIS SLIDER ###
	$sort_order = $order_by = $sort_meta = '';
	if ($post_type == $curr_post_type) {
		$sort_order = get_post_meta($post_id, "sap_sort_order", true);
		$order_by = get_post_meta($post_id, "sap_order_by", true);
		$sort_type = get_post_meta($post_id, "sap_sort_type", true);
		$sort_meta = get_post_meta($post_id, "sap_sort_meta", true);
		if ($sort_order == 'meta_value' ) {
			$sort_order = "meta~".$sort_meta;
		}
	}

	// CREATE ARRAY CONTAINING ALL THE 'SORT ORDER' OPTIONS
	$sort_order_arr = array();
	array_push($sort_order_arr, array('label' => 'None', 'value' => 'none'));
	array_push($sort_order_arr, array('label' => 'Post ID', 'value' => 'ID'));
	array_push($sort_order_arr, array('label' => 'Post Author', 'value' => 'author'));
	if ($title_yn) {
		array_push($sort_order_arr, array('label' => 'Post Title', 'value' => 'title'));
	}
	array_push($sort_order_arr, array('label' => 'Post Slug', 'value' => 'name'));
	array_push($sort_order_arr, array('label' => 'Published Date', 'value' => 'date'));
	array_push($sort_order_arr, array('label' => 'Modified Date', 'value' => 'modified'));
	array_push($sort_order_arr, array('label' => 'Random', 'value' => 'rand'));
	if ($post_type == 'post') {
		array_push($sort_order_arr, array('label' => 'Comment Count', 'value' => 'comment_count'));
	}
	if ($post_type == 'page') {
		array_push($sort_order_arr, array('label' => 'Menu Order', 'value' => 'menu_order'));
	}
	// create sort options for the meta keys of this post
	if (count($meta_arr) != 0) {
		for ($i = 0; $i < count($meta_arr); $i++) {
			array_push($sort_order_arr, array('label' => 'META: '.$meta_arr[$i], 'value' => 'meta~'.$meta_arr[$i]));
		}
	}

	// CREATE ARRAY CONTAINING ALL THE 'ORDER BY' OPTIONS
	$order_by_arr = array();
	array_push($order_by_arr, array('label' => 'Ascending', 'value' => 'ASC'));
	array_push($order_by_arr, array('label' => 'Descending', 'value' => 'DESC'));

	// CREATE ARRAY CONTAINING ALL THE 'SORT TYPE' OPTIONS
	$sort_type_arr = array();
	array_push($sort_type_arr, array('label' => 'Alphabetical', 'value' => 'alpha'));
	array_push($sort_type_arr, array('label' => 'Numerical', 'value' => 'num'));

	// DISPLAY 'SORT ORDER' DROP-DOWN (SELECT) INPUT BOX
	echo "<div class='sap_sort_order_line'><span>Sort Order:</span>";
	echo "<select id='sap_sort_order' name='sap_sort_order' onChange='sort_order_change();'>";
	for ($i = 0; $i < count($sort_order_arr); $i++) {
		if ($sort_order == $sort_order_arr[$i]['value']) {
			echo "<option value='".$sort_order_arr[$i]['value']."' selected>".$sort_order_arr[$i]['label']."</option>";
		} else {
			echo "<option value='".$sort_order_arr[$i]['value']."'>".$sort_order_arr[$i]['label']."</option>";
		}
	}
	echo "</select>";
	echo "</div>\n"; // .sap_sort_order_line

	// DISPLAY 'ORDER BY' DROP-DOWN (SELECT) INPUT BOX
	echo "<div class='sap_sort_order_line'><span>Order By:</span>";
	echo "<select id='sap_order_by' name='sap_order_by'>";
	for ($i = 0; $i < count($order_by_arr); $i++) {
		if ($order_by == $order_by_arr[$i]['value']) {
			echo "<option value='".$order_by_arr[$i]['value']."' selected>".$order_by_arr[$i]['label']."</option>";
		} else {
			echo "<option value='".$order_by_arr[$i]['value']."'>".$order_by_arr[$i]['label']."</option>";
		}
	}
	echo "</select>";
	echo "</div>\n"; // .sap_sort_order_line

	// DISPLAY 'SORT TYPE' DROP-DOWN (SELECT) INPUT BOX
	if (substr($sort_order, 0, 5) === "meta~") {
		echo "<div id='sap_sort_type_wrapper' class='sap_sort_order_line' style='display:inline-block;'><span>Sort Type:</span>";
	} else {
		echo "<div id='sap_sort_type_wrapper' class='sap_sort_order_line' style='display:none;'><span>Sort Type:</span>";
	}
	echo "<select id='sap_sort_type' name='sap_sort_type'>";
	for ($i = 0; $i < count($sort_type_arr); $i++) {
		if ($sort_type == $sort_type_arr[$i]['value']) {
			echo "<option value='".$sort_type_arr[$i]['value']."' selected>".$sort_type_arr[$i]['label']."</option>";
		} else {
			echo "<option value='".$sort_type_arr[$i]['value']."'>".$sort_type_arr[$i]['label']."</option>";
		}
	}
	echo "</select>";
	echo "</div>\n"; // .sap_sort_order_line

	die();
}



// ###### META BOX CONTENT - 'Insert Post Type Fields' BOX ######
function sapa_slider_insert_fields($post) {
	$post_id = $post->ID;

	echo "<div id='sap_insert_dropdown_options'>\n";
	// ### DISPLAY WORDPRESS IMAGE SIZE DROPDOWN (SELECT) BOX ###
	global $_wp_additional_image_sizes;
	$image_size_arr = array();
	// get available wordpress image sizes and save to array
	$image_size_arr[0]['value'] = 'full';
	$image_size_arr[0]['desc'] = 'Set WP Image Size for Image Fields';
	$count = 1;
	foreach (get_intermediate_image_sizes() as $image_size) {
		if (in_array($image_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
			$width = get_option("{$image_size}_size_w");
			$height = get_option("{$image_size}_size_h");
		} elseif (isset($_wp_additional_image_sizes[$image_size])) {
			$width = $_wp_additional_image_sizes[$image_size]['width'];
			$height = $_wp_additional_image_sizes[$image_size]['height'];
		}
		if (($width == 0) && ($height == 0)) {
			// width & height are both 0, so skip this one
		} else {
			$image_size_arr[$count]['value'] = $image_size;
			$image_size_arr[$count]['desc'] = $image_size." (".$width."&times;".$height.")";
			$count++;
		}
	}
	// display the image size dropdown (select) box
	echo "<div><select id='sap_insert_image_size' name='sap_insert_image_size'>";
	for ($i = 0; $i < count($image_size_arr); $i++) {
		echo "<option value='".$image_size_arr[$i]['value']."'>".$image_size_arr[$i]['desc']."</option>";
	}
	echo "</select>";
	$tooltip =  "The WordPress Image Size to use when inserting image fields into your slide content. ";
	$tooltip .= "Please note that the default image size is \"full\".";
	echo "<i class='sap_tooltip' title='".$tooltip."'></i></div>\n";

	// ### DISPLAY META DATA FORMAT OPTIONS DROPDOWN (SELECT) BOX ###
	$meta_options_arr = array();
	array_push($meta_options_arr, array('value' => 'string', 'desc' => 'Meta Data Format: string'));
	array_push($meta_options_arr, array('value' => 'integer', 'desc' => 'Meta Data Format: integer'));
	array_push($meta_options_arr, array('value' => 'float', 'desc' => 'Meta Data Format: float'));
	array_push($meta_options_arr, array('value' => 'currency1', 'desc' => 'Meta Data Format: currency (2 decimals)'));
	array_push($meta_options_arr, array('value' => 'currency2', 'desc' => 'Meta Data Format: currency (no decimals)'));
	echo "<div><select id='sap_meta_ins_option' name='sap_meta_ins_option'>";
	for ($i = 0; $i < count($meta_options_arr); $i++) {
		echo "<option value='".$meta_options_arr[$i]['value']."'>".$meta_options_arr[$i]['desc']."</option>";
	}
	echo "</select>";
	$tooltip =  "Insert Meta Data Fields formated as a string, integer value, float value or as a currency value ";
	$tooltip .= "(1,234.56).";
	echo "<i class='sap_tooltip' title='".$tooltip."'></i></div>\n";

	// ### DISPLAY TAXONOMIES OPTIONS DROPDOWN (SELECT) BOX ###
	$tax_options_arr = array();
	array_push($tax_options_arr, array('value' => 'comma_list', 'desc' => 'Taxonomies: comma separated list'));
	array_push($tax_options_arr, array('value' => 'comma_links', 'desc' => 'Taxonomies: comma separated links'));
	array_push($tax_options_arr, array('value' => 'space_list', 'desc' => 'Taxonomies: space separated list'));
	array_push($tax_options_arr, array('value' => 'space_links', 'desc' => 'Taxonomies: space separated links'));
	echo "<div><select id='sap_tax_ins_option' name='sap_tax_ins_option'>";
	for ($i = 0; $i < count($tax_options_arr); $i++) {
		echo "<option value='".$tax_options_arr[$i]['value']."'>".$tax_options_arr[$i]['desc']."</option>";
	}
	echo "</select>";
	$tooltip =  "Insert Taxonomy Fields as a comma-separated or a space-separated list? Should taxonomy ";
	$tooltip .= "terms link to taxonomy archive pages?";
	echo "<i class='sap_tooltip' title='".$tooltip."'></i></div>\n";

	echo "</div>\n"; // #sap_insert_dropdown_options

	// ### INTRODUCTION TEXT ###
	echo "<h4>Insert Post Type fields into your slide content by clicking these buttons,<br/> ";
	echo "which insert <em>Slide Any Post</em> <strong>{PLACEHOLDERS}</strong> into the slide editor below.</h4>";

	// AJAX CONTAINER - POST TYPE SORTING
	echo "<div id='ajax_container_insert_fields'><div class='ajax_loading'>LOADING...</div></div>";
}

// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'Insert Post Type Fields' AJAX CONTAINER ######
function display_post_type_insert_fields() {
	// GET THE 'post_type' PARAMETER PASSED
	$post_type = $_REQUEST['post_type'];
	// GET THE 'post_id' PARAMETER PASSED
	$post_id = $_REQUEST['post_id'];

	// ### FIND OUT WHAT FEATURES THE POST TYPE SUPPORTS ###
	$title_yn = post_type_supports($post_type, 'title');
	$editor_yn = post_type_supports($post_type, 'editor');
	$excerpt_yn = post_type_supports($post_type, 'excerpt');
	$thumb_yn = post_type_supports($post_type, 'thumbnail');

	// ### GET ALL TAXONOMIES FOR THIS POST TYPE ###
	$tax_arr = get_taxonomies_for_post_type($post_type);

	// ### GET ALL META KEYS FOR THIS POST TYPE ###
	$meta_arr = get_meta_keys_for_post_type($post_type);

	// ### DISPLAY GENERAL POST TYPE INSERT FIELDS (title, description, excerpt, featured image) ###
	echo "<div class='sap_insert_buttons'>\n";
	if ($title_yn) {
		echo "<span class='sap_ins_button' title='Insert post title' ";
		echo "onClick='addTextIntoEditor(\"{POST_TITLE}\");'>Post Title</span>";
		echo "<span class='sap_ins_button' title='Insert post title which links to post page' ";
		echo "onClick='addTextIntoEditor(\"{POST_TITLE_LINK}\");'>Post Title Link</span>";
	}
	if ($editor_yn) {
		echo "<span class='sap_ins_button' title='Insert post desctiption' ";
		echo "onClick='addTextIntoEditor(\"{DESCRIPTION}\");'>Description</span>";
	}
	if ($excerpt_yn) {
		echo "<span class='sap_ins_button' title='Insert post excerpt' ";
		echo "onClick='addTextIntoEditor(\"{EXCERPT}\");'>Excerpt</span>";
	}
	if ($thumb_yn) {
		echo "<span class='sap_ins_button' title='Insert featured image' ";
		echo "onClick='addTextIntoEditor(\"{FEATURED_IMAGE}\");'>Featured Image</span>";
		echo "<span class='sap_ins_button' title='Insert featured image which links to post page' ";
		echo "onClick='addTextIntoEditor(\"{FEATURED_IMAGE_LINK}\");'>Featured Image Link</span>";
	}
	echo "<span class='sap_ins_button' title='Insert a link to the single post page (change &apos;Click to View&apos; to your desired link text)' ";
	echo "onClick='addTextIntoEditor(\"{POST_LINK~Click to View}\");'>Post Link</span>";
	echo "<span class='sap_ins_button' title='Insert the URL to the single post page' ";
	echo "onClick='addTextIntoEditor(\"{POST_URL}\");'>Post URL</span>";
	echo "</div>\n";

	// ### DISPLAY POST TYPE 'META DATA' INSERT FIELDS ###
	echo "<div class='sap_insert_buttons'>\n";
	echo "<h3>META DATA FIELDS:</h3>";
	for ($i = 0; $i < count($meta_arr); $i++) {
		$meta_key = $meta_arr[$i];
		if (strpos($meta_key, "IMAGE~") === 0) {
			$explode_arr = explode("~", $meta_key);
			$meta_key = $explode_arr[1];
			// META DATA - IMAGE
			echo "<span class='sap_ins_button' title='Insert Meta Data Image: ".$meta_key."' ";
			echo "onClick='addTextIntoEditor(\"{META_IMAGE~".$meta_key."}\");'><i class='fa fa-picture-o'></i>".$meta_key."</span>";
		} else {
			// META DATA - TEXT
			echo "<span class='sap_ins_button' title='Insert Meta Data: ".$meta_key."' ";
			echo "onClick='addTextIntoEditor(\"{META~".$meta_key."}\");'>".$meta_key."</span>";
		}
	}
	if (count($meta_arr) == 0) {
		echo "<div style='padding:5px 3px; font-size:16px; line-height:22px; color:#a0a0a0;'>No Meta Data found</div>";
	}
	echo "</div>\n";

	// ### DISPLAY POST TYPE 'TAXONOMY' INSERT FIELDS ###
	echo "<div class='sap_insert_buttons'>\n";
	echo "<h3>TAXONOMY FIELDS:</h3>";
	for ($i = 0; $i < count($tax_arr); $i++) {
		$tax_name = $tax_arr[$i]['name'];
		$tax_label = $tax_arr[$i]['label'];
		echo "<span class='sap_ins_button' title='Insert Taxonomy: ".$tax_label."' ";
		echo "onClick='addTextIntoEditor(\"{TAX~".$tax_name."}\");'>".$tax_label."</span>";
	}
	if (count($tax_arr) == 0) {
		echo "<div style='padding:5px 3px; font-size:16px; line-height:22px; color:#a0a0a0;'>No Taxonomies found</div>";
	}
	echo "</div>\n";

	die();
}



// ###### META BOX CONTENT - 'Slider Settings' BOX ######
function sapa_slider_settings_content($post) {
	echo "<div id='sap_slider_settings'>\n";
	// NONCE TO PREVENT CSRF SECURITY ATTACKS
	wp_nonce_field(basename(__FILE__), 'nonce_save_slider');

	// NUMBER OF SLIDES
	$sap_num_slides = get_post_meta($post->ID, 'sap_num_slides', true);
	if ($sap_num_slides == '') {
		$sap_num_slides = '10';
	}
	echo "<div id='sap_num_slides_line' class='sap_slider_value'><span id='nos_label'>Number of Slides:</span>";
	echo "<input type='text' id='sap_num_slides' name='sap_num_slides' value='".esc_attr($sap_num_slides)."'>";
	echo "<em class='sap_tooltip' title='The maximum number of slides for this Slider'></em>\n";
	echo "</div><hr/>\n";

	// SLIDE DURATION
	$slide_duration = get_post_meta($post->ID, 'sap_slide_duration', true);
	if ($slide_duration == '') {
		$slide_duration = 5;
	}
	echo "<div class='sap_slider_value'><span>Slide Duration:</span>";
	echo "<input type='text' id='sap_slide_duration' name='sap_slide_duration' readonly value='".esc_attr($slide_duration)."'><em>seconds (0 = manual navigation)</em>";
	echo "<em class='sap_tooltip' title='Set to 0 to disable slider autoplay (manual slider navigation only)'></em></div>\n";
	echo "<div class='jquery_ui_slider' id='jq_slider_duration'></div><hr/>\n";

	// SLIDE TRANSITION
	$slide_transition = get_post_meta($post->ID, 'sap_slide_transition', true);
	if ($slide_transition == '') {
		$slide_transition = 0.2;
	}
	echo "<div class='sap_slider_value'><span>Slide Transition:</span>";
	echo "<input type='text' id='sap_slide_transition' name='sap_slide_transition' readonly value='".esc_attr($slide_transition)."'><em>seconds</em>\n";
	echo "<em class='sap_tooltip' title='The time it takes to change from one slide to the next slide'></em></div>\n";
	echo "<div class='jquery_ui_slider' id='jq_slider_transition'></div><hr/>\n";

	// SLIDE BY
	$slide_by = get_post_meta($post->ID, 'sap_slide_by', true);
	if ($slide_by == '') {
		$slide_by = 1;
	}
	echo "<div class='sap_slider_value'><span>Slide By:</span>";
	echo "<input type='text' id='sap_slide_by' name='sap_slide_by' readonly value='".esc_attr($slide_by)."'><em>slides (0 = slide by page)</em>";
	echo "<em class='sap_tooltip' title='The number of slides to slide per transition. Set to 0 to enable the Slide by Page option.'></em></div>\n";
	echo "<div class='jquery_ui_slider' id='jq_slider_by'></div><hr/>\n";

	echo "<div class='half_width_column'>\n";

	// LOOP SLIDER
	$loop_slider = get_post_meta($post->ID, 'sap_loop_slider', true);
	if ($loop_slider == '') {
		$loop_slider = '1';
	}
	echo "<div class='sap_setting_checkbox'><span>Loop Slider:</span>";
	if ($loop_slider == '1') {
		echo "<input type='checkbox' id='sap_loop_slider' name='sap_loop_slider' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_loop_slider' name='sap_loop_slider' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Only applies when slide duration is NOT zero (loops back to first slide after last slide is displayed)'></em>";
	echo "</div>\n";

	// NAVIGATE ARROWS
	$nav_arrows = get_post_meta($post->ID, 'sap_nav_arrows', true);
	if ($nav_arrows == '') {
		$nav_arrows = '1';
	}
	echo "<div class='sap_setting_checkbox'><span>Navigate Arrows:</span>";
	if ($nav_arrows == '1') {
		echo "<input type='checkbox' id='sap_nav_arrows' name='sap_nav_arrows' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_nav_arrows' name='sap_nav_arrows' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Display the \"next slide\" amd \"previous slide\" buttons'></em>\n";
	echo "</div>\n";

	// SHOW PAGINATION
	$pagination = get_post_meta($post->ID, 'sap_pagination', true);
	if ($pagination == '') {
		$pagination = '1';
	}
	echo "<div class='sap_setting_checkbox'><span>Show Pagination:</span>";
	if ($pagination == '1') {
		echo "<input type='checkbox' id='sap_pagination' name='sap_pagination' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_pagination' name='sap_pagination' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Display slider pagination below the slider'></em>\n";
	echo "</div>\n";

	echo "</div>\n";
	echo "<div class='half_width_column'>\n";

	// STOP ON HOVER
	$stop_hover = get_post_meta($post->ID, 'sap_stop_hover', true);
	if ($stop_hover == '') {
		$stop_hover = '1';
	}
	echo "<div class='sap_setting_checkbox'><span>Stop on Hover:</span>";
	if ($stop_hover == '1') {
		echo "<input type='checkbox' id='sap_stop_hover' name='sap_stop_hover' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_stop_hover' name='sap_stop_hover' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Only applies when slide duration is NOT zero (slideshow is paused when hovering over a slide)'></em>";
	echo "</div>\n";

	// MOUSE DRAG
	$mouse_drag = get_post_meta($post->ID, 'sap_mouse_drag', true);
	if ($mouse_drag == '') {
		$mouse_drag = '0';
	}
	echo "<div class='sap_setting_checkbox'><span>Mouse Drag:</span>";
	if ($mouse_drag == '1') {
		echo "<input type='checkbox' id='sap_mouse_drag' name='sap_mouse_drag' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_mouse_drag' name='sap_mouse_drag' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Allow navigation to previous/next slides by holding down left mouse button and dragging left/right'></em>\n";
	echo "</div>\n";

	// TOUCH DRAG
	$touch_drag = get_post_meta($post->ID, 'sap_touch_drag', true);
	if ($touch_drag == '') {
		$touch_drag = '1';
	}
	echo "<div class='sap_setting_checkbox'><span>Touch Drag:</span>";
	if ($touch_drag == '1') {
		echo "<input type='checkbox' id='sap_touch_drag' name='sap_touch_drag' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_touch_drag' name='sap_touch_drag' value='1'/>";
	}
	echo "<em class='sap_tooltip' title='Allow navigation to previous/next slides on mobile devices by touching screen and dragging left/right'></em>\n";
	echo "</div>\n";

	echo "</div>\n";
	echo "<div style='clear:both; float:none; width:100%; height:1px;'></div>\n";
	echo "</div>\n";
}



// ###### META BOX CONTENT - 'Slider Preview/Shortcode' BOX ######
function sapa_slider_shortcode_content($post) {
	$post_status = get_post_status($post->ID);
	$allow_shortcodes = get_post_meta($post->ID, 'sap_shortcodes', true);
	$shortcode = '[slide-any-post id="'.$post->ID.'"]';
	echo "<div id='sap_slider_shortcode'>".esc_html($shortcode)."</div>\n";
	echo "<div id='sap_shortcode_copy' class='button button-secondary'>Copy to Clipboard</div>\n";
	if (($post_status == 'publish') && ($allow_shortcodes != '1')) {
		echo "<div id='sap_preview_slider' class='button button-secondary' ";
		echo "onClick='document.getElementById(\"sap_preview_popup\").style.display = \"block\";'>Preview Slider</div>\n";
	}

	if (($post_status == 'publish') && ($allow_shortcodes != '1')) {
		// DISPLAY SLIDER PREVIEW POPUP
		echo "<div id='sap_preview_popup' style='display:none;'>\n";
		echo "<div id='sap_preview_wrapper'>\n";
		echo "<div id='sap_preview_close' title='Close Slider Preview' ";
		echo "onClick='document.getElementById(\"sap_preview_popup\").style.display = \"none\";'>X</div>\n";
		//echo do_shortcode("[slide-anything id='".$post->ID."']");
		echo "</div>\n";
		echo "</div>\n";
	}
}



// ###### META BOX CONTENT - 'Slide Background' BOX ######
function sapa_slide_background_content($post) {
	// GET CURRENTLY SAVED SLIDE BACKGROUND COLOR FOR THIS SLIDER
	$sap_bg_color = get_post_meta($post->ID, 'sap_slide_bg_color', true);
	if ($sap_bg_color == '') {
		$sap_bg_color = "rgba(0,0,0,0)";
	}

	// SLIDE BACKGROUND - BACKGROUND COLOR (color picker)
	echo "<div class='slide_bg_line'><span>Background Color</span>";
	echo "<input type='text' id='sap_slide_bg_color' name='sap_slide_bg_color' value='".esc_attr($sap_bg_color)."'>";
	echo "</div>\n";

	// AJAX CONTAINER - SLIDER BACKGROUND
	echo "<div id='ajax_container_slide_background'><div class='ajax_loading'>LOADING...</div></div>";
}

// ###### AJAX FUNCTION - DISPLAYS CONTENT WITHIN THE 'Slide Background' AJAX CONTAINER ######
function display_slide_background_fields() {
	// GET THE 'post_type' PARAMETER PASSED
	$post_type = $_REQUEST['post_type'];
	// GET THE 'post_id' PARAMETER PASSED
	$post_id = $_REQUEST['post_id'];
	// FIND OUT IF POST TYPE SUPPORTS A FEATURED IMAGE
	$feat_image_yn = post_type_supports($post_type, 'thumbnail');
	if ($feat_image_yn != '1') {
		$feat_image_yn = '0';
	}

	// SUPPORTS FEATURED (HIDDEN FIELD)
	echo "<input type='hidden' id='sap_supports_featured' name='sap_supports_featured' value='".$feat_image_yn."'>";

	// ### THIS POST TYPE SUPPORTS A FEATURED IMAGE ###
	if ($feat_image_yn == "1") {
		// GET CURRENTLY SAVED SLIDE BACKGROUND FIELDS FOR THIS SLIDER
		$sap_bg_use_featured = get_post_meta($post_id, 'sap_slide_bg_use_featured', true);
		if ($sap_bg_use_featured != '1') {
			$sap_bg_use_featured = '0';
		}
		$sap_bg_position = get_post_meta($post_id, 'sap_slide_bg_position', true);
		if ($sap_bg_position == '') {
			$sap_bg_position = 'left top';
		}
		$sap_bg_size = get_post_meta($post_id, 'sap_slide_bg_size', true);
		if ($sap_bg_size == '') {
			$sap_bg_size = 'contain';
		}
		$sap_bg_repeat = get_post_meta($post_id, 'sap_slide_bg_repeat', true);
		if ($sap_bg_repeat == '') {
			$sap_bg_repeat = 'no-repeat';
		}
		$sap_bg_wp_imagesize = get_post_meta($post_id, 'sap_slide_bg_wp_imagesize', true);
		if ($sap_bg_wp_imagesize == '') {
			$sap_bg_wp_imagesize = 'full';
		}

		// SLIDE BACKGROUND - USE FEATURED IMAGE AS BACKGROUND (checkbox)
		echo "<div class='slide_bg_line' style='padding:5px 0px 10px !important;'><span>Use Featured Image as Background</span>";
		if ($sap_bg_use_featured == '1') {
	 		echo "<input type='checkbox' id='sap_slide_bg_use_featured' name='sap_slide_bg_use_featured' value='1' checked ";
		} else {
			echo "<input type='checkbox' id='sap_slide_bg_use_featured' name='sap_slide_bg_use_featured' value='1' ";
		}
		echo "onChange='bg_use_featured_changed();' />";
		echo "</div>\n";

		// DISPLAY CONTAINER FOR BACKGROUND POSITION, BACKGROUND SIZE, BACKGROUND REPEAT & WORDPRESS IMAGE SIZE OPTIONS
		if ($sap_bg_use_featured == '1') {
			echo "<div id='use_featured_bg_container' style='display:block;'>\n";
		} else {
			echo "<div id='use_featured_bg_container' style='display:none;'>\n";
		}

		// SLIDE BACKGROUND - BACKGROUND POSITION (dropdown box)
		$option_arr = array();
		$option_arr[0]['desc'] = 'Top Left'; $option_arr[0]['value'] = 'left top';
		$option_arr[1]['desc'] = 'Top Center'; $option_arr[1]['value'] = 'center top';
		$option_arr[2]['desc'] = 'Top Right'; $option_arr[2]['value'] = 'right top';
		$option_arr[3]['desc'] = 'Center Left'; $option_arr[3]['value'] = 'left center';
		$option_arr[4]['desc'] = 'Center'; $option_arr[4]['value'] = 'center center';
		$option_arr[5]['desc'] = 'Center Right'; $option_arr[5]['value'] = 'right center';
		$option_arr[6]['desc'] = 'Bottom Left'; $option_arr[6]['value'] = 'left bottom';
		$option_arr[7]['desc'] = 'Bottom Center'; $option_arr[7]['value'] = 'center bottom';
		$option_arr[8]['desc'] = 'Bottom Right'; $option_arr[8]['value'] = 'right bottom';
		echo "<div class='slide_bg_line'><span>Background Position</span>";
		echo "<select id='sap_slide_bg_position' name='sap_slide_bg_position'>";
		for ($i = 0; $i < count($option_arr); $i++) {
			if ($sap_bg_position == $option_arr[$i]['value']) {
				echo "<option value='".esc_attr($option_arr[$i]['value'])."' selected>".esc_html($option_arr[$i]['desc'])."</option>";
			} else {
				echo "<option value='".esc_attr($option_arr[$i]['value'])."'>".esc_html($option_arr[$i]['desc'])."</option>";
			}
		}
		echo "</select>";
		echo "</div>\n";

		// SLIDE BACKGROUND - BACKGROUND SIZE (dropdown box)
		$option_arr = array();
		$option_arr[0]['value'] = 'auto'; $option_arr[0]['desc'] = 'no resize';
		$option_arr[1]['value'] = 'contain'; $option_arr[1]['desc'] = 'contain';
		$option_arr[2]['value'] = 'cover'; $option_arr[2]['desc'] = 'cover';
		$option_arr[3]['value'] = '100% 100%'; $option_arr[3]['desc'] = '100%';
		$option_arr[4]['value'] = '100% auto'; $option_arr[4]['desc'] = '100% width';
		$option_arr[5]['value'] = 'auto 100%'; $option_arr[5]['desc'] = '100% height';
		echo "<div class='slide_bg_line'><span>Background Size</span>";
		echo "<select id='sap_slide_bg_size' name='sap_slide_bg_size'>";
		for ($i = 0; $i < count($option_arr); $i++) {
			if ($sap_bg_size == $option_arr[$i]['value']) {
				echo "<option value='".esc_attr($option_arr[$i]['value'])."' selected>".esc_html($option_arr[$i]['desc'])."</option>";
			} else {
				echo "<option value='".esc_attr($option_arr[$i]['value'])."'>".esc_html($option_arr[$i]['desc'])."</option>";
			}
		}
		echo "</select>";
		echo "</div>\n";

		// SLIDE BACKGROUND - BACKGROUND REPEAT (dropdown box)
		$option_arr = array();
		$option_arr[0] = 'no-repeat';
		$option_arr[1] = 'repeat';
		$option_arr[2] = 'repeat-x';
		$option_arr[3] = 'repeat-y';
		echo "<div class='slide_bg_line'><span>Background Repeat</span>";
		echo "<select id='sap_slide_bg_repeat' name='sap_slide_bg_repeat'>";
		for ($i = 0; $i < count($option_arr); $i++) {
			if ($sap_bg_repeat == $option_arr[$i]) {
				echo "<option value='".esc_attr($option_arr[$i])."' selected>".esc_html($option_arr[$i])."</option>";
			} else {
				echo "<option value='".esc_attr($option_arr[$i])."'>".esc_html($option_arr[$i])."</option>";
			}
		}
		echo "</select>";
		echo "</div>\n";

		// SLIDE BACKGROUND - WORDPRESS IMAGE SIZE
		global $_wp_additional_image_sizes;
		$image_size_arr = array();
		// get available wordpress image sizes and save to array
		$image_size_arr[0]['value'] = 'full';
		$image_size_arr[0]['desc'] = 'Full Size';
		$count = 1;
		foreach (get_intermediate_image_sizes() as $image_size) {
			if (in_array($image_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
				$width = get_option("{$image_size}_size_w");
				$height = get_option("{$image_size}_size_h");
			} elseif (isset($_wp_additional_image_sizes[$image_size])) {
				$width = $_wp_additional_image_sizes[$image_size]['width'];
				$height = $_wp_additional_image_sizes[$image_size]['height'];
			}
			if (($width == 0) && ($height == 0)) {
				// width & height are both 0, so skip this one
			} else {
				$image_size_arr[$count]['value'] = $image_size;
				$image_size_arr[$count]['desc'] = $image_size." (".$width."&times;".$height.")";
				$count++;
			}
		}
		// display the image size dropdown (select) box
		echo "<div id='slide_bg_wp_imagesize_line'><span>WordPress Image Size for Background:</span>";
		echo "<select id='sap_slide_bg_wp_imagesize' name='sap_slide_bg_wp_imagesize'>";
		for ($i = 0; $i < count($image_size_arr); $i++) {
			if ($sap_bg_wp_imagesize == $image_size_arr[$i]['value']) {
				echo "<option value='".$image_size_arr[$i]['value']."' selected>".$image_size_arr[$i]['desc']."</option>";
			} else {
				echo "<option value='".$image_size_arr[$i]['value']."'>".$image_size_arr[$i]['desc']."</option>";
			}
		}
		echo "</select>";
		echo "</div>\n";

		echo "</div>\n"; // #use_featured_bg_container
	}

	die();
}



// ###### META BOX CONTENT - 'Items Displayed' BOX ######
function sapa_slider_items_content($post) {
	$items_width1 = intval(get_post_meta($post->ID, 'sap_items_width1', true));
	$items_width2 = intval(get_post_meta($post->ID, 'sap_items_width2', true));
	$items_width3 = intval(get_post_meta($post->ID, 'sap_items_width3', true));
	$items_width4 = intval(get_post_meta($post->ID, 'sap_items_width4', true));
	$items_width5 = intval(get_post_meta($post->ID, 'sap_items_width5', true));
	$items_width6 = intval(get_post_meta($post->ID, 'sap_items_width6', true));
	if ($items_width1 == 0) { $items_width1 = 1; }
	if ($items_width2 == 0) { $items_width2 = 1; }
	if ($items_width3 == 0) { $items_width3 = 1; }
	if ($items_width4 == 0) { $items_width4 = 1; }
	if ($items_width5 == 0) { $items_width5 = 1; }
	if ($items_width6 == 0) { $items_width6 = $items_width5; }

	echo "<div id='items_displayed_metabox'>\n";
	echo "<h4>Browser/Device Width:</h4>\n";
	// items for browser width category 1
	echo "<div><em class='sap_tooltip' href='' title='Up to 479 pixels'></em><span>Mobile Portrait</span><select name='sap_items_width1'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width1) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// items for browser width category 2
	echo "<div><em class='sap_tooltip' href='' title='480 to 767 pixels'></em><span>Mobile Landscape</span><select name='sap_items_width2'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width2) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// items for browser width category 3
	echo "<div><em class='sap_tooltip' href='' title='768 to 979 pixels'></em><span>Tablet Portrait</span><select name='sap_items_width3'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width3) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// items for browser width category 4
	echo "<div><em class='sap_tooltip' href='' title='980 to 1199 pixels'></em><span>Desktop Small</span><select name='sap_items_width4'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width4) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// items for browser width category 5
	echo "<div><em class='sap_tooltip' href='' title='1200 to 1499 pixels'></em><span>Desktop Large</span><select name='sap_items_width5'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width5) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// items for browser width category 6
	echo "<div><em class='sap_tooltip' href='' title='Over 1500 pixels'></em><span>Desktop X-Large</span><select name='sap_items_width6'>";
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $items_width6) {
			echo "<option value='".$i."' selected>".$i."</option>";
		} else {
			echo "<option value='".$i."'>".$i."</option>";
		}
	}
	echo "</select></div>\n";
	// slide transition effect
	$transition = get_post_meta($post->ID, 'sap_transition', true);
	if ($transition == '') {
		$transition = 'fade';
	}
	$option_arr = array();
	$option_arr[0] = 'Slide';
	$option_arr[1] = 'Fade';
	$option_arr[2] = 'Zoom In';
	$option_arr[3] = 'Zoom Out';
	$option_arr[4] = 'Flip Out X';
	$option_arr[5] = 'Flip Out Y';
	$option_arr[6] = 'Rotate Left';
	$option_arr[7] = 'Rotate Right';
	$option_arr[8] = 'Bounce Out';
	$option_arr[9] = 'Roll Out';
	$option_arr[10] = 'Slide Down';
	echo "<div><em class='sap_tooltip' href='' title='NOTE: Slide transitions only work when the above items displayed are ALL SET TO 1'></em>";
	echo "<span style='color:firebrick !important;'>Slide Transition</span><select style='max-width:100px !important;' name='sap_transition'>";
	for ($i = 0; $i < count($option_arr); $i++) {
		if ($transition == $option_arr[$i]) {
			echo "<option value='".$option_arr[$i]."' selected>".$option_arr[$i]."</option>";
		} else {
			echo "<option value='".$option_arr[$i]."'>".$option_arr[$i]."</option>";
		}
	}
	echo "</select></div>\n";

	echo "</div>\n";
}



// ###### META BOX CONTENT - 'Slider Style' BOX ######
function sapa_slider_style_content($post) {
	// CSS ID
	$css_id = get_post_meta($post->ID, 'sap_css_id', true);
	if ($css_id == '') {
		$css_id = "slider_".$post->ID;
	}
	echo "<div id='slider_style_metabox'>\n";
	echo "<h4>CSS <span>#id</span> for Slider:</h4>\n";
	echo "<div style='padding-bottom:10px; color:#909090;'>Must consist of letters (upper/lowercase) or Underscore '_' characters <span style='color:firebrick;'>ONLY!</span></div>\n";
	echo "<input type='text' id='sap_css_id' name='sap_css_id' value='".esc_attr($css_id)."'/>\n";
	echo "<div id='css_note_text'>To style slides use CSS selector:</div>";
	echo "<div id='css_note_value'>#".esc_html($css_id)." .owl-item</div>";
	echo "<div class='ca_style_hr'></div>\n";

	// SLIDER PADDING (TOP, RIGHT, BOTTOM, LEFT)
	$wrapper_padd_top = get_post_meta($post->ID, 'sap_wrapper_padd_top', true);
	if ($wrapper_padd_top == '') { $wrapper_padd_top = '0'; }
	$wrapper_padd_right = get_post_meta($post->ID, 'sap_wrapper_padd_right', true);
	if ($wrapper_padd_right == '') { $wrapper_padd_right = '0'; }
	$wrapper_padd_bottom = get_post_meta($post->ID, 'sap_wrapper_padd_bottom', true);
	if ($wrapper_padd_bottom == '') { $wrapper_padd_bottom = '0'; }
	$wrapper_padd_left = get_post_meta($post->ID, 'sap_wrapper_padd_left', true);
	if ($wrapper_padd_left == '') { $wrapper_padd_left = '0'; }
	$tooltip = "Padding space around the entire carousel/slider";
	echo "<h4>Padding <span>(pixels)</span>:<em class='sap_tooltip' title='".$tooltip."'></em></h4>";
	echo "<div class='ca_style_padding'>";
	echo "<div id='padd_top'>";
	echo "<input type='text' id='sap_wrapper_padd_top' name='sap_wrapper_padd_top' value='".esc_attr($wrapper_padd_top)."'></div>";
	echo "<div id='padd_right'>";
	echo "<input type='text' id='sap_wrapper_padd_right' name='sap_wrapper_padd_right' value='".esc_attr($wrapper_padd_right)."'></div>";
	echo "<div type='text' id='padd_bottom'>";
	echo "<input type='text' id='sap_wrapper_padd_bottom' name='sap_wrapper_padd_bottom' value='".esc_attr($wrapper_padd_bottom)."'></div>";
	echo "<div id='padd_left'>";
	echo "<input type='text' id='sap_wrapper_padd_left' name='sap_wrapper_padd_left' value='".esc_attr($wrapper_padd_left)."'></div>";
	echo "</div>\n";
	echo "<div style='clear:both; float:none; width:100%; height:10px;'></div>";

	$tooltip = "The background color and border around the entire carousel/slider";
	echo "<h4>Background/Border:<em class='sap_tooltip' title='".$tooltip."'></em></h4>";

	// SLIDER BACKGROUND COLOR
	$background_color = get_post_meta($post->ID, 'sap_background_color', true);
	if ($background_color == '') {
		$background_color = 'rgba(0,0,0,0)';
	}
	echo "<div class='ca_style_setting_line'><span>Background:</span>";
	echo "<input type='text' id='sap_background_color' name='sap_background_color' value='".esc_attr($background_color)."'></div>\n";

	// SLIDER BORDER (WIDTH & COLOR)
	$border_width = get_post_meta($post->ID, 'sap_border_width', true);
	if ($border_width == '') {
		$border_width = '0';
	}
	$border_color = get_post_meta($post->ID, 'sap_border_color', true);
	if ($border_color == '') {
		$border_color = 'rgba(0,0,0,0)';
	}
	echo "<div class='ca_style_setting_line'><span>Border Settings:</span>";
	echo "<input type='text' id='sap_border_width' name='sap_border_width' value='".esc_attr($border_width)."'><em>px</em>";
	echo "<input type='text' id='sap_border_color' name='sap_border_color' value='".esc_attr($border_color)."'></div>\n";

	// SLIDER BORDER RADIUS
	$border_radius = get_post_meta($post->ID, 'sap_border_radius', true);
	if ($border_radius == '') {
		$border_radius = '0';
	}
	echo "<div class='ca_style_setting_line'><span>Border Radius:</span>";
	echo "<input type='text' id='sap_border_radius' name='sap_border_radius' value='".esc_attr($border_radius)."'></div>\n";

	echo "<div class='ca_style_hr' style='margin-top:10px;'></div>\n";

	$tooltip = "The style settings for all slides (within the slider/carousel)";
	echo "<h4>Slide Style:<em class='sap_tooltip' title='".$tooltip."'></em></h4>";

	// SLIDE - MINIMUM HEIGHT
	$slide_min_height = get_post_meta($post->ID, 'sap_slide_min_height_perc', true);
	if ($slide_min_height == '') {
		$slide_min_height = '50';
	}
	echo "<div style='padding:5px 0px 10px;'>\n";
	$tooltip = "The minimum height of each slide. Can be set to a percentage of the slide width, or for image sliders set to a 4:3 or 16:9 aspect ratio.";
	echo "<div class='ca_style_setting_line' id='ca_style_min_height' style='padding-bottom:7px !important;'>";
	echo "<span class='sap_tooltip' title='".$tooltip."'>Min Height:</span><br/>";
	if ($slide_min_height == 'aspect43') {
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='percent' style='margin-left:20px !important;'/><em>%</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='px'/><em>px</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='43' checked/><em>4:3</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='169'/><em>16:9</em>";
	} elseif ($slide_min_height == 'aspect169') {
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='percent' style='margin-left:20px !important;'/><em>%</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='px'/><em>px</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='43'/><em>4:3</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='169' checked/><em>16:9</em>";
	} elseif (strpos($slide_min_height, 'px') !== false) {
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='percent' style='margin-left:20px !important;'/><em>%</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='px' checked/><em>px</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='43'/><em>4:3</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='169'/><em>16:9</em>";
	} else {
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='percent' style='margin-left:20px !important;' checked/><em>%</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='px'/><em>px</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='43'/><em>4:3</em>";
		echo "<input type='radio' name='sap_slide_min_height_type' class='sap_slide_min_height_type' value='169'/><em>16:9</em>";
	}
	echo "</div>\n";
	if (($slide_min_height == 'aspect43') || ($slide_min_height == 'aspect169')) {
		echo "<div class='ca_style_setting_line' id='sap_slide_min_height_wrapper' style='display:none;'>";
		echo "<input type='text' id='sap_slide_min_height' name='sap_slide_min_height' value='".esc_attr($slide_min_height)."'/>";
		echo "<em id='mh_suffix'>".$mh_suffix."</em></div>\n";
		echo "<input type='hidden' id='sap_slide_min_height_hidden' name='sap_slide_min_height_hidden' value='0'/>\n";
	} else {
		if (strpos($slide_min_height, 'px') !== false) {
			$mh_value = str_replace('px', '', $slide_min_height);
			$mh_suffix = 'px';
		} else {
			$mh_value = $slide_min_height;
			$mh_suffix = '%';
		}
		echo "<div class='ca_style_setting_line' id='sap_slide_min_height_wrapper'><span style='width:20px;'>&nbsp;</span>";
		echo "<input type='text' id='sap_slide_min_height' name='sap_slide_min_height' value='".esc_attr($mh_value)."'/>";
		echo "<em id='mh_suffix'>".$mh_suffix."</em></div>\n";
		echo "<input type='hidden' id='sap_slide_min_height_hidden' name='sap_slide_min_height_hidden' value='".esc_attr($mh_value)."'/>\n";
	}
	echo "</div>\n";

	// SLIDE - PADDING TOP/BOTTOM
	$slide_padding_tb = get_post_meta($post->ID, 'sap_slide_padding_tb', true);
	if ($slide_padding_tb == '') {
		$slide_padding_tb = '5';
	}
	$tooltip = "Padding space top/bottom for each individual slide";
	echo "<div class='ca_style_setting_line' id='ca_style_padding_top_bottom'><span class='sap_tooltip' title='".$tooltip."'>Padding:</span>";
	echo "<input type='text' id='sap_slide_padding_tb' name='sap_slide_padding_tb' value='".esc_attr($slide_padding_tb)."'><em>%</em></div>\n";

	// SLIDE - PADDING LEFT/RIGHT
	$slide_padding_lr = get_post_meta($post->ID, 'sap_slide_padding_lr', true);
	if ($slide_padding_lr == '') {
		$slide_padding_lr = '5';
	}
	$tooltip = "Padding space left/right for each individual slide";
	echo "<div class='ca_style_setting_line' id='ca_style_padding_left_right'><span class='sap_tooltip' title='".$tooltip."'>Padding:</span>";
	echo "<input type='text' id='sap_slide_padding_lr' name='sap_slide_padding_lr' value='".esc_attr($slide_padding_lr)."'><em>%</em></div>\n";

	// SLIDE - MARGIN LEFT/RIGHT
	$slide_margin_lr = get_post_meta($post->ID, 'sap_slide_margin_lr', true);
	if ($slide_margin_lr == '') {
		$slide_margin_lr = '0';
	}
	$tooltip = "Margin space left and right of each slide";
	echo "<div class='ca_style_setting_line' id='ca_style_margin_left_right'><span class='sap_tooltip' title='".$tooltip."'>Margin:</span>";
	echo "<input type='text' id='sap_slide_margin_lr' name='sap_slide_margin_lr' value='".esc_attr($slide_margin_lr)."'><em>%</em></div>\n";

	echo "<div class='ca_style_hr' style='margin-top:10px;'></div>\n";
	$tooltip = "The slider next/previous navigation arrow icons";
	echo "<h4>Navigation Arrows:<em class='sap_tooltip' title='".$tooltip."'></em></h4>";

	// NAVIGATION ARROWS - ARROW IMAGES (DROPDOWN - WHITE/GREY/BLACK/CUSTOM)
	$arrow_images = get_post_meta($post->ID, 'sap_arrow_images', true);
	if ($arrow_images == '') {
		$arrow_images = 'white';
	}
	echo "<div class='ca_style_setting_line' style='padding-bottom:5px;'><span>Arrow Images</span>";
	echo "<select id='sap_arrow_images' name='sap_arrow_images'>";
	if ($arrow_images == 'white') {
		echo "<option value='white' selected>White</option>";
		echo "<option value='grey'>Grey</option>";
		echo "<option value='black'>Black</option>";
		echo "<option value='custom'>Custom</option>";
	} elseif ($arrow_images == 'grey') {
		echo "<option value='white'>White</option>";
		echo "<option value='grey' selected>Grey</option>";
		echo "<option value='black'>Black</option>";
		echo "<option value='custom'>Custom</option>";
	} elseif ($arrow_images == 'black') {
		echo "<option value='white'>White</option>";
		echo "<option value='grey'>Grey</option>";
		echo "<option value='black' selected>Black</option>";
		echo "<option value='custom'>Custom</option>";
	} else {
		echo "<option value='white'>White</option>";
		echo "<option value='grey'>Grey</option>";
		echo "<option value='black'>Black</option>";
		echo "<option value='custom' selected>Custom</option>";
	}
	echo "</select></div>\n";

	// AUTOHIDE ARROWS
	$autohide_arrows = get_post_meta($post->ID, 'sap_autohide_arrows', true);
	if ($autohide_arrows == '') {
		$autohide_arrows = '1';
	}
	echo "<div class='ca_style_setting_line' style='padding-top:5px !important;'><span>Autohide Arrows</span>";
	if ($autohide_arrows == '1') {
		echo "<input type='checkbox' id='sap_autohide_arrows' name='sap_autohide_arrows' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_autohide_arrows' name='sap_autohide_arrows' value='1'/>";
	}
	echo "</div>\n";

	if ($arrow_images == 'custom') {
		echo "<div id='sap_custom_nav_arrows'>\n";
	} else {
		echo "<div id='sap_custom_nav_arrows' style='max-height:0px;'>\n";
	}
	// NAVIGATION ARROWS - PREVIOUS ARROW IMAGE URL
	$site_url = get_site_url();
	$prev_arrow_url = get_post_meta($post->ID, 'sap_prev_arrow_url', true);
	if ($prev_arrow_url == '') {
		$prev_arrow_url = $site_url."/wp-content/plugins/slide-any-post/images/white_icon_prev.png";
	}
	echo "<div class='ca_style_setting_line_url' style='padding-top:10px;'><span>Previous Arrow Image URL</span>";
	echo "<input type='text' id='sap_prev_arrow_url' name='sap_prev_arrow_url' value='".esc_attr($prev_arrow_url)."'/>";
	echo "</div>\n";

	// NAVIGATION ARROWS - NEXT ARROW IMAGE URL
	$next_arrow_url = get_post_meta($post->ID, 'sap_next_arrow_url', true);
	if ($next_arrow_url == '') {
		$next_arrow_url = $site_url."/wp-content/plugins/slide-any-post/images/white_icon_next.png";
	}
	echo "<div class='ca_style_setting_line_url'><span>Next Arrow Image URL</span>";
	echo "<input type='text' id='sap_next_arrow_url' name='sap_next_arrow_url' value='".esc_attr($next_arrow_url)."'/>";
	echo "</div>\n";

	// NAVIGATION ARROWS - WRAPPER WIDTH
	$arrows_wrapper_width = get_post_meta($post->ID, 'sap_arrows_wrapper_width', true);
	if ($arrows_wrapper_width == '') {
		$arrows_wrapper_width = '30';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Width:</span>";
	echo "<input type='text' id='sap_arrows_wrapper_width' name='sap_arrows_wrapper_width' value='".esc_attr($arrows_wrapper_width)."'><em>px</em>";
	echo "</div>\n";

	// NAVIGATION ARROWS - WRAPPER HEIGHT
	$arrows_wrapper_height = get_post_meta($post->ID, 'sap_arrows_wrapper_height', true);
	if ($arrows_wrapper_height == '') {
		$arrows_wrapper_height = '40';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Height:</span>";
	echo "<input type='text' id='sap_arrows_wrapper_height' name='sap_arrows_wrapper_height' value='".esc_attr($arrows_wrapper_height)."'><em>px</em>";
	echo "</div>\n";

	// NAVIGATION ARROWS - WRAPPER BORDER RADIUS
	$arrows_wrapper_bordradius = get_post_meta($post->ID, 'sap_arrows_wrapper_bordradius', true);
	if ($arrows_wrapper_bordradius == '') {
		$arrows_wrapper_bordradius = '0';
	}
	echo "<div class='ca_style_setting_line'><span>Border Radius:</span>";
	echo "<input type='text' id='sap_arrows_wrapper_bordradius' name='sap_arrows_wrapper_bordradius' value='".esc_attr($arrows_wrapper_bordradius)."'><em>px</em>";
	echo "</div>\n";

	// NAVIGATION ARROWS - WRAPPER BACKGROUND
	$arrows_wrapper_bgcol = get_post_meta($post->ID, 'sap_arrows_wrapper_bgcol', true);
	if ($arrows_wrapper_bgcol == '') {
		$arrows_wrapper_bgcol = 'rgba(0,0,0,0.3)';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Background:</span>";
	echo "<input type='text' id='sap_arrows_wrapper_bgcol' name='sap_arrows_wrapper_bgcol' value='".esc_attr($arrows_wrapper_bgcol)."'>";
	echo "</div>\n";

	// NAVIGATION ARROWS - WRAPPER HOVER BACKGROUND
	$arrows_hover_bgcol = get_post_meta($post->ID, 'sap_arrows_hover_bgcol', true);
	if ($arrows_hover_bgcol == '') {
		$arrows_hover_bgcol = 'rgba(0,0,0,0.8)';
	}
	echo "<div class='ca_style_setting_line'><span>Hover Background:</span>";
	echo "<input type='text' id='sap_arrows_hover_bgcol' name='sap_arrows_hover_bgcol' value='".esc_attr($arrows_hover_bgcol)."'>";
	echo "</div>\n";

	echo "</div>\n"; #sap_custom_nav_arrows

	echo "<div class='ca_style_hr' style='margin-top:15px;'></div>\n";

	// USE A POST LINK (CHECKBOX)
	$tooltip = "For each slide, add a link button pointing to the single post page (select &apos;Entire Slide&apos; as an &apos;Icon Location&apos; to make the entire slide content a clickable link)";
	echo "<h4>Link to Single Post:<em class='sap_tooltip' title='".$tooltip."'></em></h4>";
	$post_link_yn = get_post_meta($post->ID, 'sap_post_link_yn', true);
	if ($post_link_yn != '1') {
		$post_link_yn = '0';
	}
	echo "<div class='ca_style_setting_line' style='padding-top:5px;'><span>Use a Post Link</span>";
	if ($post_link_yn == '1') {
		echo "<input type='checkbox' id='sap_post_link_yn' name='sap_post_link_yn' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_post_link_yn' name='sap_post_link_yn' value='1'/>";
	}
	echo "</div>\n";

	// WRAPPER - SHOW IF 'USE A POST LINK' IS CHECKED
	if ($post_link_yn == '1') {
		echo "<div id='sap_post_link_wrapper'>\n";
	} else {
		echo "<div id='sap_post_link_wrapper' style='max-height:0px;'>\n";
	}

	// USE A POST LINK - LINK LOCATION (DROPDOWN)
	$slide_link_location = get_post_meta($post->ID, 'sap_slide_link_location', true);
	if ($slide_link_location == '') {
		$slide_link_location = 'Entire Slide';
	}
	echo "<div class='ca_style_setting_line'><span>Link Location</span>";
	echo "<select id='sap_slide_link_location' name='sap_slide_link_location'>";
	$option_arr = array();
	$option_arr[0] = 'Entire Slide';
	$option_arr[1] = 'Center Center';
	$option_arr[2] = 'Top Left';
	$option_arr[3] = 'Top Center';
	$option_arr[4] = 'Top Right';
	$option_arr[5] = 'Bottom Left';
	$option_arr[6] = 'Bottom Center';
	$option_arr[7] = 'Bottom Right';
	for ($i = 0; $i < count($option_arr); $i++) {
		if ($option_arr[$i] == $slide_link_location) {
			echo "<option value='".$option_arr[$i]."' selected>".$option_arr[$i]."</option>";
		} else {
			echo "<option value='".$option_arr[$i]."'>".$option_arr[$i]."</option>";
		}
	}
	echo "</select></div>\n";

	// USE A POST LINK - CUSTOMISE ICON (CHECKBOX)
	$custom_link_yn = get_post_meta($post->ID, 'sap_custom_link_yn', true);
	if ($custom_link_yn != '1') {
		$custom_link_yn = '0';
	}
	echo "<div class='ca_style_setting_line'><span>Customise Icon</span>";
	if ($custom_link_yn == '1') {
		echo "<input type='checkbox' id='sap_custom_link_yn' name='sap_custom_link_yn' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_custom_link_yn' name='sap_custom_link_yn' value='1'/>";
	}
	echo "</div>\n";

	// WRAPPER - SHOW IF 'CUSTOMISE ICON' IS CHECKED
	if ($custom_link_yn == '1') {
		echo "<div id='sap_custom_link_wrapper'>\n";
	} else {
		echo "<div id='sap_custom_link_wrapper' style='max-height:0px;'>\n";
	}

	// USE A POST LINK - LINK ICON URL (TEXT INPUT)
	$link_icon_url = get_post_meta($post->ID, 'sap_link_icon_url', true);
	if ($link_icon_url == '') {
		if ($slide_link_location != 'Entire Slide') {
			$link_icon_url = $site_url."/wp-content/plugins/slide-any-post/images/slide_link.png";
		}
	}
	echo "<div class='ca_style_setting_line_url' style='padding-top:10px;'><span>Link Icon URL</span>";
	echo "<input type='text' id='sap_link_icon_url' name='sap_link_icon_url' value='".esc_attr($link_icon_url)."'/>";
	echo "</div>\n";


	// WRAPPER - SHOW IF 'LINK LOCATION' IS NOT SET TO 'Entire Slide'
	if ($slide_link_location != 'Entire Slide') {
		echo "<div id='sap_link_button_only_wrapper'>\n";
	} else {
		echo "<div id='sap_link_button_only_wrapper' style='max-height:0px;'>\n";
	}

	// USE A POST LINK - WRAPPER WIDTH (JQUERY-UI SPINNER)
	$link_icon_width = get_post_meta($post->ID, 'sap_link_icon_width', true);
	if ($link_icon_width == '') {
		$link_icon_width = '40';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Width:</span>";
	echo "<input type='text' id='sap_link_icon_width' name='sap_link_icon_width' value='".esc_attr($link_icon_width)."'><em>px</em>";
	echo "</div>\n";

	// USE A POST LINK - WRAPPER HEIGHT (JQUERY-UI SPINNER)
	$link_icon_height = get_post_meta($post->ID, 'sap_link_icon_height', true);
	if ($link_icon_height == '') {
		$link_icon_height = '40';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Height:</span>";
	echo "<input type='text' id='sap_link_icon_height' name='sap_link_icon_height' value='".esc_attr($link_icon_height)."'><em>px</em>";
	echo "</div>\n";

	// USE A POST LINK - WRAPPER BORDER RADIUS
	$link_icon_bordradius = get_post_meta($post->ID, 'sap_link_icon_bordradius', true);
	if ($link_icon_bordradius == '') {
		$link_icon_bordradius = '0';
	}
	echo "<div class='ca_style_setting_line'><span>Border Radius:</span>";
	echo "<input type='text' id='sap_link_icon_bordradius' name='sap_link_icon_bordradius' value='".esc_attr($link_icon_bordradius)."'><em>px</em>";
	echo "</div>\n";

	// USE A POST LINK - WRAPPER BACKGROUND
	$link_wrapper_bgcol = get_post_meta($post->ID, 'sap_link_wrapper_bgcol', true);
	if ($link_wrapper_bgcol == '') {
		$link_wrapper_bgcol = 'rgba(0,0,0,0.3)';
	}
	echo "<div class='ca_style_setting_line'><span>Wrapper Background:</span>";
	echo "<input type='text' id='sap_link_wrapper_bgcol' name='sap_link_wrapper_bgcol' value='".esc_attr($link_wrapper_bgcol)."'>";
	echo "</div>\n";

	echo "</div>\n"; #sap_link_button_only_wrapper

	// USE A POST LINK - HOVER BACKGROUND
	$link_hover_bgcol = get_post_meta($post->ID, 'sap_link_hover_bgcol', true);
	if ($link_hover_bgcol == '') {
		$link_hover_bgcol = 'rgba(0,0,0,0.8)';
	}
	echo "<div class='ca_style_setting_line'><span>Hover Background:</span>";
	echo "<input type='text' id='sap_link_hover_bgcol' name='sap_link_hover_bgcol' value='".esc_attr($link_hover_bgcol)."'>";
	echo "</div>\n";

	echo "</div>\n"; #sap_custom_link_wrapper
	echo "</div>\n"; #sap_post_link_wrapper

	// ### ADVANCED SETTINGS ###
	echo "<div class='ca_style_hr' style='margin-top:15px;'></div>\n";
	echo "<h4 style='margin:15px 0px 10px !important;'>Advanced Settings:</h4>";

	// allow shortcodes
	$shortcodes = get_post_meta($post->ID, 'sap_shortcodes', true);
	if ($shortcodes == '') {
		$shortcodes = '0';
	}
	$tooltip =  'Allow WordPress shorcodes within slide content. NOTE: Running shortcodes in Slide Any Post may ';
	$tooltip .= 'cause issues with some Wordpress Page Builders';
	echo "<div id='sap_advanced_settings_line'>";
	echo "<em class='sap_tooltip' title='".$tooltip."'></em><span>Allow Shortcodes:</span>";
	if ($shortcodes == '1') {
		echo "<input type='checkbox' id='sap_shortcodes' name='sap_shortcodes' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_shortcodes' name='sap_shortcodes' value='1'/>";
	}
	echo "</div>\n";

	// slider auto height
	$auto_height = get_post_meta($post->ID, 'sap_auto_height', true);
	if ($auto_height == '') {
		$auto_height = '0';
	}
	$tooltip =  'When checked the height of slider is automatically ajusted to match the height for each slide displayed. ';
	$tooltip .= 'NOTE: Only works when Items Displayed is 1';
	echo "<div id='sap_advanced_settings_line'>";
	echo "<em class='sap_tooltip' title='".$tooltip."'></em><span>Slider Auto Height:</span>";
	if ($auto_height == '1') {
		echo "<input type='checkbox' id='sap_auto_height' name='sap_auto_height' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_auto_height' name='sap_auto_height' value='1'/>";
	}
	echo "</div>\n";

	// Use 'window.onload' EVENT (checkbox)
	$window_onload = get_post_meta($post->ID, 'sap_window_onload', true);
	if ($window_onload == '') {
		$window_onload = '0';
	}
	$tooltip =  'Load the Slide Anything JavaScript during the DOMContentLoaded event. Use this option if jQuery ';
	$tooltip .= 'is loading in your theme footer and you are getting the JavaScript error message ';
	$tooltip .= '&quot;Uncaught ReferenceError: jQuery is not defined&quot;.';
	echo "<div id='sap_advanced_settings_line'>";
	echo "<em class='sap_tooltip' title='".$tooltip."'></em><span>DOMContentLoaded event:</span>";
	if ($window_onload == '1') {
		echo "<input type='checkbox' id='sap_window_onload' name='sap_window_onload' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_window_onload' name='sap_window_onload' value='1'/>";
	}
	echo "</div>\n";

	// Strip JavaScript from Content
	$strip_javascript = get_post_meta($post->ID, 'sap_strip_javascript', true);
	if ($strip_javascript == '') {
		$strip_javascript = '0';
	}
	$tooltip = 'Remove JavaScript (<script> tags) from slide content for extra security.';
	echo "<div id='sap_advanced_settings_line'>";
	echo "<em class='sap_tooltip' title='".$tooltip."'></em><span>Remove JavaScript Content:</span>";
	if ($strip_javascript == '1') {
		echo "<input type='checkbox' id='sap_strip_javascript' name='sap_strip_javascript' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_strip_javascript' name='sap_strip_javascript' value='1'/>";
	}
	echo "</div>\n";

	// Enable Lazy Load Images
	$lazy_load_images = get_post_meta($post->ID, 'sap_lazy_load_images', true);
	if ($lazy_load_images == '') {
		$lazy_load_images = '0';
	}
	$tooltip = "Enable &quot;Lazy Load&quot; for images added to your slide content (note: does not apply to slide backgrounds).";
	echo "<div id='sap_advanced_settings_line'>";
	echo "<em class='sap_tooltip' title='".$tooltip."'></em><span>Enable 'Lazy Load' Images:</span>";
	if ($lazy_load_images == '1') {
		echo "<input type='checkbox' id='sap_lazy_load_images' name='sap_lazy_load_images' value='1' checked/>";
	} else {
		echo "<input type='checkbox' id='sap_lazy_load_images' name='sap_lazy_load_images' value='1'/>";
	}
	echo "</div>\n";

	echo "</div>\n";
}



// ###### ACTION HOOK - SAVE CUSTOM POST TYPE ('Slide Any Post') DATA ######
function sapa_slider_save_postdata() {
	global $post;

	// ### VERIFY 1) LOGGED-IN USER IS ADMINISTRATOR AND 2) VALID NONCE TO PREVENT CSRF HACKER ATTACKS ###
	if (current_user_can('edit_pages') &&
		 isset($_POST['nonce_save_slider']) && wp_verify_nonce($_POST['nonce_save_slider'], basename(__FILE__))) {

		// UPDATE WORDPRESS POST TYPE SETTINGS
		update_post_meta($post->ID, 'sap_post_type', sanitize_text_field($_POST['sap_post_type']));						// SANATIZE
		// 'post title' filter and 'has a featured image' checkbox
		update_post_meta($post->ID, 'sap_post_id_oper', sanitize_text_field($_POST['sap_post_id_oper']));				// SANATIZE
		update_post_meta($post->ID, 'sap_post_id_value', sanitize_text_field($_POST['sap_post_id_value']));			// SANATIZE
		update_post_meta($post->ID, 'sap_post_title_oper', sanitize_text_field($_POST['sap_post_title_oper']));		// SANATIZE
		update_post_meta($post->ID, 'sap_post_title_value', sanitize_text_field($_POST['sap_post_title_value']));	// SANATIZE
		if (isset($_POST['sap_post_thumb_yn']) && ($_POST['sap_post_thumb_yn'] == '1')) {
			update_post_meta($post->ID, 'sap_post_thumb_yn', '1');
		} else {
			update_post_meta($post->ID, 'sap_post_thumb_yn', '0');
		}
		// update taxonomy filters
		$total_tax = intval($_POST['sap_total_taxonomies']);
		update_post_meta($post->ID, 'sap_total_taxonomies', abs(floatval($_POST['sap_total_taxonomies'])));	// SANATIZE
		for ($i = 1; $i <= $total_tax; $i++) {
			$filter_tax_name = "sap_filter_tax".$i."_name";
			$filter_tax_oper = "sap_filter_tax".$i."_oper";
			$filter_tax_value = "sap_filter_tax".$i."_value";
			$filter_tax_name_data = sanitize_text_field($_POST[$filter_tax_name]);		// SANATIZE
			$filter_tax_oper_data = sanitize_text_field($_POST[$filter_tax_oper]); 		// SANATIZE
			$filter_tax_value_data = sanitize_text_field($_POST[$filter_tax_value]);	// SANATIZE
			update_post_meta($post->ID, $filter_tax_name, $filter_tax_name_data);
			update_post_meta($post->ID, $filter_tax_oper, $filter_tax_oper_data);
			update_post_meta($post->ID, $filter_tax_value, $filter_tax_value_data);
		}
		// update meta data filters
		$total_meta = intval($_POST['sap_total_meta_keys']);
		update_post_meta($post->ID, 'sap_total_meta_keys', abs(floatval($_POST['sap_total_meta_keys'])));		// SANATIZE
		for ($i = 1; $i <= $total_meta; $i++) {
			$filter_meta_name = "sap_filter_meta".$i."_name";
			$filter_meta_oper = "sap_filter_meta".$i."_oper";
			$filter_meta_value = "sap_filter_meta".$i."_value";
			$filter_meta_name_data = sanitize_text_field($_POST[$filter_meta_name]);  	// SANATIZE
			$filter_meta_oper_data = sanitize_text_field($_POST[$filter_meta_oper]);  	// SANATIZE
			$filter_meta_value_data = sanitize_text_field($_POST[$filter_meta_value]);	// SANATIZE
			update_post_meta($post->ID, $filter_meta_name, $filter_meta_name_data);
			update_post_meta($post->ID, $filter_meta_oper, $filter_meta_oper_data);
			update_post_meta($post->ID, $filter_meta_value, $filter_meta_value_data);
		}
		// update sort order settings
		$sap_sort_order = 'none';
		$sap_order_by = 'ASC';
		$sap_sort_meta = '';
		if (isset($_POST['sap_sort_order']) && ($_POST['sap_sort_order'] != '')) {
			$sap_sort_order = sanitize_text_field($_POST['sap_sort_order']);	// SANATIZE
			if (strpos($sap_sort_order, "meta~") === 0) {
				$explode_arr = explode("~", $sap_sort_order);
				$sap_sort_order = 'meta_value';
				$sap_sort_meta = $explode_arr[1];
			}
		}
		if (isset($_POST['sap_order_by']) && ($_POST['sap_order_by'] != '')) {
			$sap_order_by = sanitize_text_field($_POST['sap_order_by']);	// SANATIZE
		}
		if (isset($_POST['sap_sort_type']) && ($_POST['sap_sort_type'] != '')) {
			$sap_sort_type = sanitize_text_field($_POST['sap_sort_type']);	// SANATIZE
		}
		update_post_meta($post->ID, 'sap_sort_order', $sap_sort_order);
		update_post_meta($post->ID, 'sap_order_by', $sap_order_by);
		update_post_meta($post->ID, 'sap_sort_meta', $sap_sort_meta);
		update_post_meta($post->ID, 'sap_sort_type', $sap_sort_type);

		// UPDATE SLIDER SETTINGS
		update_post_meta($post->ID, 'sap_num_slides', abs($_POST['sap_num_slides']));									// SANATIZE
		update_post_meta($post->ID, 'sap_slide_duration', abs(floatval($_POST['sap_slide_duration'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_slide_transition', abs(floatval($_POST['sap_slide_transition'])));	// SANATIZE
		update_post_meta($post->ID, 'sap_slide_by', abs($_POST['sap_slide_by']));										// SANATIZE
		if (isset($_POST['sap_loop_slider']) && ($_POST['sap_loop_slider'] == '1')) {
			update_post_meta($post->ID, 'sap_loop_slider', '1');
		} else {
			update_post_meta($post->ID, 'sap_loop_slider', '0');
		}
		if (isset($_POST['sap_nav_arrows']) && ($_POST['sap_nav_arrows'] == '1')) {
			update_post_meta($post->ID, 'sap_nav_arrows', '1');
		} else {
			update_post_meta($post->ID, 'sap_nav_arrows', '0');
		}
		if (isset($_POST['sap_pagination']) && ($_POST['sap_pagination'] == '1')) {
			update_post_meta($post->ID, 'sap_pagination', '1');
		} else {
			update_post_meta($post->ID, 'sap_pagination', '0');
		}
		if (isset($_POST['sap_stop_hover']) && ($_POST['sap_stop_hover'] == '1')) {
			update_post_meta($post->ID, 'sap_stop_hover', '1');
		} else {
			update_post_meta($post->ID, 'sap_stop_hover', '0');
		}
		if (isset($_POST['sap_mouse_drag']) && ($_POST['sap_mouse_drag'] == '1')) {
			update_post_meta($post->ID, 'sap_mouse_drag', '1');
		} else {
			update_post_meta($post->ID, 'sap_mouse_drag', '0');
		}
		if (isset($_POST['sap_touch_drag']) && ($_POST['sap_touch_drag'] == '1')) {
			update_post_meta($post->ID, 'sap_touch_drag', '1');
		} else {
			update_post_meta($post->ID, 'sap_touch_drag', '0');
		}

		// UPDATE SLIDER ITEMS DISPLAYED SETTINGS
		update_post_meta($post->ID, 'sap_items_width1', abs(intval($_POST['sap_items_width1'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_items_width2', abs(intval($_POST['sap_items_width2'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_items_width3', abs(intval($_POST['sap_items_width3'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_items_width4', abs(intval($_POST['sap_items_width4'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_items_width5', abs(intval($_POST['sap_items_width5'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_items_width6', abs(intval($_POST['sap_items_width6'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_transition', sanitize_text_field($_POST['sap_transition']));	// SANATIZE

		// UPDATE SLIDE BACKGROUND SETTINGS
		update_post_meta($post->ID, 'sap_slide_bg_color', sanitize_text_field($_POST['sap_slide_bg_color'])); 		// SANATIZE
		$supports_featured = 0;
		$bg_use_featured = 0;
		if (isset($_POST['sap_supports_featured']) && ($_POST['sap_supports_featured'] == '1')) {
			update_post_meta($post->ID, 'sap_supports_featured', '1');
			$supports_featured = 1;
		} else {
			update_post_meta($post->ID, 'sap_supports_featured', '0');
		}
		if ($supports_featured == 1) {
			if (isset($_POST['sap_slide_bg_use_featured']) && ($_POST['sap_slide_bg_use_featured'] == '1')) {
				update_post_meta($post->ID, 'sap_slide_bg_use_featured', '1');
				$bg_use_featured = 1;
			} else {
				update_post_meta($post->ID, 'sap_slide_bg_use_featured', '0');
			}
			if ($bg_use_featured == 1) {
				update_post_meta($post->ID, 'sap_slide_bg_position', sanitize_text_field($_POST['sap_slide_bg_position']));				// SANATIZE
				update_post_meta($post->ID, 'sap_slide_bg_size', sanitize_text_field($_POST['sap_slide_bg_size']));						// SANATIZE
				update_post_meta($post->ID, 'sap_slide_bg_repeat', sanitize_text_field($_POST['sap_slide_bg_repeat']));					// SANATIZE
				update_post_meta($post->ID, 'sap_slide_bg_wp_imagesize', sanitize_text_field($_POST['sap_slide_bg_wp_imagesize']));	// SANATIZE
			}
		}

		// UPDATE SLIDER STYLE SETTINGS
		$post_css_id = str_replace("-", "_", $_POST['sap_css_id']);
		update_post_meta($post->ID, 'sap_css_id', sanitize_text_field($post_css_id));											// SANATIZE
		update_post_meta($post->ID, 'sap_background_color', sanitize_text_field($_POST['sap_background_color'])); 	// SANATIZE
		update_post_meta($post->ID, 'sap_border_width', abs(intval($_POST['sap_border_width'])));							// SANATIZE
		update_post_meta($post->ID, 'sap_border_color', sanitize_text_field($_POST['sap_border_color']));				// SANATIZE
		update_post_meta($post->ID, 'sap_border_radius', abs(intval($_POST['sap_border_radius'])));						// SANATIZE
		update_post_meta($post->ID, 'sap_wrapper_padd_top', abs(intval($_POST['sap_wrapper_padd_top'])));				// SANATIZE
		update_post_meta($post->ID, 'sap_wrapper_padd_right', abs(intval($_POST['sap_wrapper_padd_right'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_wrapper_padd_bottom', abs(intval($_POST['sap_wrapper_padd_bottom'])));		// SANATIZE
		update_post_meta($post->ID, 'sap_wrapper_padd_left', abs(intval($_POST['sap_wrapper_padd_left'])));			// SANATIZE
		if ($_POST['sap_slide_min_height_type'] == 'px') {
			update_post_meta($post->ID, 'sap_slide_min_height_perc', sanitize_text_field($_POST['sap_slide_min_height']).'px');	// SANATIZE
		} else {
			update_post_meta($post->ID, 'sap_slide_min_height_perc', sanitize_text_field($_POST['sap_slide_min_height']));			// SANATIZE
		}
		update_post_meta($post->ID, 'sap_slide_padding_tb', abs(floatval($_POST['sap_slide_padding_tb'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_slide_padding_lr', abs(floatval($_POST['sap_slide_padding_lr'])));			// SANATIZE
		update_post_meta($post->ID, 'sap_slide_margin_lr', abs(floatval($_POST['sap_slide_margin_lr'])));				// SANATIZE

		// UPDATE SLIDER STYLE SETTINGS (NAVIGATION ARROWS)
		update_post_meta($post->ID, 'sap_arrow_images', sanitize_text_field($_POST['sap_arrow_images']));										// SANATIZE
		update_post_meta($post->ID, 'sap_prev_arrow_url', sanitize_text_field($_POST['sap_prev_arrow_url']));									// SANATIZE
		update_post_meta($post->ID, 'sap_next_arrow_url', sanitize_text_field($_POST['sap_next_arrow_url']));									// SANATIZE
		update_post_meta($post->ID, 'sap_arrows_wrapper_width', sanitize_text_field($_POST['sap_arrows_wrapper_width']));					// SANATIZE
		update_post_meta($post->ID, 'sap_arrows_wrapper_height', sanitize_text_field($_POST['sap_arrows_wrapper_height']));				// SANATIZE
		update_post_meta($post->ID, 'sap_arrows_wrapper_bordradius', sanitize_text_field($_POST['sap_arrows_wrapper_bordradius']));	// SANATIZE
		update_post_meta($post->ID, 'sap_arrows_wrapper_bgcol', sanitize_text_field($_POST['sap_arrows_wrapper_bgcol']));					// SANATIZE
		update_post_meta($post->ID, 'sap_arrows_hover_bgcol', sanitize_text_field($_POST['sap_arrows_hover_bgcol']));						// SANATIZE
		if (isset($_POST['sap_autohide_arrows']) && ($_POST['sap_autohide_arrows'] == '1')) {
			update_post_meta($post->ID, 'sap_autohide_arrows', '1');
		} else {
			update_post_meta($post->ID, 'sap_autohide_arrows', '0');
		}

		// UPDATE SLIDER STYLE SETTINGS (LINK TO SINGLE POST)
		if (isset($_POST['sap_post_link_yn']) && ($_POST['sap_post_link_yn'] == '1')) {
			update_post_meta($post->ID, 'sap_post_link_yn', '1');
		} else {
			update_post_meta($post->ID, 'sap_post_link_yn', '0');
		}
		update_post_meta($post->ID, 'sap_slide_link_location', sanitize_text_field($_POST['sap_slide_link_location']));	// SANATIZE
		if (isset($_POST['sap_custom_link_yn']) && ($_POST['sap_custom_link_yn'] == '1')) {
			update_post_meta($post->ID, 'sap_custom_link_yn', '1');
		} else {
			update_post_meta($post->ID, 'sap_custom_link_yn', '0');
		}
		update_post_meta($post->ID, 'sap_link_icon_url', sanitize_text_field($_POST['sap_link_icon_url']));		 			// SANATIZE
		update_post_meta($post->ID, 'sap_link_icon_width', sanitize_text_field($_POST['sap_link_icon_width']));				// SANATIZE
		update_post_meta($post->ID, 'sap_link_icon_height', sanitize_text_field($_POST['sap_link_icon_height']));			// SANATIZE
		update_post_meta($post->ID, 'sap_link_icon_bordradius', sanitize_text_field($_POST['sap_link_icon_bordradius']));	// SANATIZE
		update_post_meta($post->ID, 'sap_link_wrapper_bgcol', sanitize_text_field($_POST['sap_link_wrapper_bgcol']));		// SANATIZE
		update_post_meta($post->ID, 'sap_link_hover_bgcol', sanitize_text_field($_POST['sap_link_hover_bgcol']));			// SANATIZE

		// UPDATE SLIDER STYLE SETTINGS (ADVANCED SETTINGS)
		if (isset($_POST['sap_shortcodes']) && ($_POST['sap_shortcodes'] == '1')) {
			update_post_meta($post->ID, 'sap_shortcodes', '1');
		} else {
			update_post_meta($post->ID, 'sap_shortcodes', '0');
		}
		if (isset($_POST['sap_auto_height']) && ($_POST['sap_auto_height'] == '1')) {
			update_post_meta($post->ID, 'sap_auto_height', '1');
		} else {
			update_post_meta($post->ID, 'sap_auto_height', '0');
		}
		if (isset($_POST['sap_window_onload']) && ($_POST['sap_window_onload'] == '1')) {
			update_post_meta($post->ID, 'sap_window_onload', '1');
		} else {
			update_post_meta($post->ID, 'sap_window_onload', '0');
		}
		if (isset($_POST['sap_strip_javascript']) && ($_POST['sap_strip_javascript'] == '1')) {
			update_post_meta($post->ID, 'sap_strip_javascript', '1');
		} else {
			update_post_meta($post->ID, 'sap_strip_javascript', '0');
		}
		if (isset($_POST['sap_lazy_load_images']) && ($_POST['sap_lazy_load_images'] == '1')) {
			update_post_meta($post->ID, 'sap_lazy_load_images', '1');
		} else {
			update_post_meta($post->ID, 'sap_lazy_load_images', '0');
		}
	}
}



// ###### GET ALL TAXONOMIES FOR A SUPPLIED POST TYPE NAME ######
function get_taxonomies_for_post_type($post_type) {
	$tax_arr = array();
	$count = 0;
	$tax_items = get_object_taxonomies($post_type, 'objects');
	foreach ($tax_items as $tax_item) {
		if ($tax_item->public == '1') {
			if ($tax_item->name == 'product_shipping_class') {
				// exclude specific txonomies
			} else {
				$tax_arr[$count]['name'] = $tax_item->name;
				$tax_arr[$count]['label'] = $tax_item->label;
				$count++;
			}
		}
	}
	// DEBUG OUTPUT (post taxonomies)
	// echo "<div><strong>DEBUG - Post Taxonomies</strong></div>";
	// for ($i = 0; $i < count($tax_arr); $i++) {
	// 	echo "<div>|".$tax_arr[$i]['name']."|".$tax_arr[$i]['label']."|</div>";
	// }

	return $tax_arr;
}



// ###### GET ALL POST META KEYS FOR A SUPPLIED POST TYPE NAME ###
function get_meta_keys_for_post_type($post_type) {
	global $wpdb;
	$meta_arr = array();
	$number_posts = 10; // MAX NUMBER OF POSTS TO SEARCH FOR META KEYS

	// GET THE HIGHEST POST ID USED ON THE SITE
	$query = "SELECT ID FROM ".$wpdb->posts." ORDER BY ID DESC LIMIT 1";
	$result = $wpdb->get_results($query);
	$row = $result[0];
	$max_id = $row->ID;

	// ### SQL QUERY TO FETTCH THE MOST RECENT POST IDS FOR THIS POST TYPE (WITH A 'publish' OR 'draft' STATUS) ###
	$post_ids = array();
	$query =  "SELECT ID FROM ".$wpdb->posts." WHERE post_type = '".$post_type."' AND post_status IN('publish', 'draft') ";
	$query .= "ORDER BY post_date DESC LIMIT ".$number_posts;
	$result = $wpdb->get_results($query, OBJECT);
	foreach ($result as $row) {
		$post_ids[] = $row->ID;
	}

	// ### LOOP EACH POST ID (FETCHED IN ABOVE QUERY) AND FETCH ALL THE META KEYS FOR EACH POST ###
	for ($i = 0; $i < count($post_ids); $i++) {
		$query =  "SELECT * FROM ".$wpdb->postmeta." WHERE post_id = ".$post_ids[$i];
		$result = $wpdb->get_results($query, OBJECT);
		foreach ($result as $row) {
			$meta_key = $row->meta_key;
			$meta_value = $row->meta_value;
			// check if this meta key already exists in meta array
			$exists = 0;
			for ($j = 0; $j < count($meta_arr); $j++) {
				if ($meta_key == $meta_arr[$j]) {
					$exists = 1;
				}
			}
			// ### IF META KEY DOES NOT EXIST IN META ARRAY, THEN ADD IT ###
			if ($exists == 0) {
				// REMOVE WORDPRESS INTERNAL META KEYS AND OTHER UNWANTED EXCEPTION META KEYS
				$include = 0;
				if (($meta_key == 'slide_template') || ($meta_key == 'et_enqueued_post_fonts')) {
					// exclude these meta keys (exceptions)
					$include = 0;
				} elseif (($post_type == 'product') &&
							 (($meta_key == '_sku') || ($meta_key == '_regular_price') || ($meta_key == '_sale_price') ||
		  	 		 		  ($meta_key == '_weight') || ($meta_key == '_stock_status') || ($meta_key == '_stock') ||
		  	 				  ($meta_key == '_wc_average_rating') || ($meta_key == '_length') || ($meta_key == '_width') ||
							  ($meta_key == '_height') || ($meta_key == '_price'))) {
		  			// include woocommerce meta keys (starting with underscore)
					$include = 1;
				} elseif (!preg_match("(^[_0-9].+$)", $meta_key)) {
					// include all meta keys that do not start with underscore
					$include = 1;
				}
				if ($include == 1) {
					// CHECK IF THE META VALUE IS A WORDPRESS IMAGE (ATTACHMENT) ID
					$meta_value_int = intval($meta_value);
					if (($meta_value_int > 0) && ($meta_value_int <= $max_id)) {
						$query2 = "SELECT COUNT(*) FROM ".$wpdb->posts." WHERE ID = ".$meta_value_int." AND post_type = 'attachment'";
						$rec_count = $wpdb->get_var($query2);
						if ($rec_count > 0) {
							// add 'IMAGE~' prefix to meta key if it is a wordpress attachment id
							$meta_key = "IMAGE~".$meta_key;
						}
					}
					// ADD META KEY TO META ARRAY
					$meta_arr[] = $meta_key;
				}
			}
		}
	}

	return $meta_arr;
}



// ##### RETURNS A FILTER TEXT INPUT BOX PLACEHOLDER FOR A SPECIFIED FILTER OPERATOR #####
function set_placeholder_for_selected_operator($filter_oper, $value_label) {
	$placeholder = '';
	if (($filter_oper == 'IN') || ($filter_oper == 'NOT IN')) {
 		$placeholder = "use '|' to seperate ".$value_label."s";
 	} elseif (($filter_oper == 'BETWEEN') || ($filter_oper == 'NOT BETWEEN')) {
 		$placeholder = "2 ".$value_label."s separated by '|'";
 	} elseif (($filter_oper == 'LIKE') || ($filter_oper == 'NOT LIKE')) {
 		$placeholder = "enter search text";
 	} else {
 		$placeholder = "enter single ".$value_label;
 	}
	return $placeholder;
}
?>