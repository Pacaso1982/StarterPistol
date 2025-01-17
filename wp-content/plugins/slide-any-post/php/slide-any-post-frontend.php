<?php
// #####################################################################
// ### SLIDE ANY POST PLUGIN - PHP FUNCTIONS FOR WORDPRESS FRONT-END ###
// #####################################################################

add_shortcode('slide-any-post', 'slide_any_post_shortcode');

/* ###### ROOT FUNCTION THAT IS CALLED TO BY THE 'slide-anything' SHORTCODE ###### */
function slide_any_post_shortcode($atts) {
	// ### VALIDATE 'SLIDE ANY POST' LICENSE KEY ###
	$valid_key = validate_slide_any_post_license_key();
	if ($valid_key != 1) {
		$invalid_license_text =  "<div style='padding:30px 0px 10px; font-size:20px; line-height:26px; color:red; text-align:center;'>";
		$invalid_license_text .= "Missing or invalid 'Slide Any Post' license key!";
		$invalid_license_text .= "</div>";
		$invalid_license_text .= "<div style='padding:0px 0px 30px; font-size:16px; line-height:22px; color:#808080; text-align:center;'>";
		$invalid_license_text .= "Go to 'Settings -> Slide Any Post' within the WordPress Dashboard to activate your 'Slide Any Post' license key.";
		$invalid_license_text .= "</div>";
		return $invalid_license_text;
	}
	wp_enqueue_script('jquery');
	wp_register_script('owl_carousel_js', SAP_PLUGIN_PATH.'owl-carousel/owl.carousel.min.js', array('jquery'), '2.3.4', true);
	wp_enqueue_script('owl_carousel_js');
	wp_register_style('owl_carousel_css', SAP_PLUGIN_PATH.'owl-carousel/owl.carousel.css', array(), '2.3.4', 'all');
	wp_enqueue_style('owl_carousel_css');
	wp_register_style('sap_owl_theme', SAP_PLUGIN_PATH.'owl-carousel/sap-owl-theme.css', array(), '2.3.4', 'all');
	wp_enqueue_style('sap_owl_theme');
	wp_register_style('owl_animate_css', SAP_PLUGIN_PATH.'owl-carousel/animate.min.css', array(), '3.6.2', 'all');
	wp_enqueue_style('owl_animate_css');

	// ### EXTRACT SHORTCODE ATTRIBUTES ###
	extract(shortcode_atts(array(
		'id' => 0,
	), $atts));
	$output = '';
	$valid_shortcode_param = 1;
	$valid_post_type = 1;
	$settings = array();
	$query_arr = array();

	// CHECK WHETHER SHORTCODE 'id' PARAMETER IS VALID
	if ($id == 0) {
		$valid_shortcode_param = 0;
	} else {
		$post_status = get_post_status($id);
		if ($post_status == 'publish') {
			$metadata = get_metadata('post', $id);
		}
		if (($post_status != 'publish') || (count($metadata) == 0)) {
			$valid_shortcode_param = 0;
		}
	}
	// INVALID SHORTCODE 'id' PARAMETER - DISPLAY ERROR MESSAGE
	if ($valid_shortcode_param == 0) {
		$output .= "<div id='sap_invalid_message'>SLIDE ANY POST ERROR: The ID specified within the shortcode is not valid</div>\n";
	}

	// CHECK IF THE SLIDER POST TYPE EXISTS
	if ($valid_shortcode_param == 1) {
		// GET SLIDER CONTENT TEMPLATE
		$slider_post = get_post($id);
		$slider_template = $slider_post->post_content;
		// GET SLIDER METADATA
		$query_arr['post_type'] = $metadata['sap_post_type'][0];
		if (!post_type_exists($query_arr['post_type'])) {
			$valid_post_type = 0;
			// THE SLIDER POST TYPE DOES NOT EXIST - DISPLAY ERROR MESSAGE
			$output .= "<div id='sap_invalid_message'>SLIDE ANY POST ERROR: The Post Type selected ('".$query_arr['post_type']."') for the slider does not exist</div>\n";
		}
	}

	// ### VALID SHORTCODE 'id' PARAMETER & VALID POST TYPE - EXECUTE SHORTCODE ###
	if (($valid_shortcode_param == 1) && ($valid_post_type == 1)) {
		// GET SLIDER FILTER/SORT CRITERIA AND SAVE IN ARRAY
		$query_arr['post_id_oper'] = $metadata['sap_post_id_oper'][0];
		$query_arr['post_id_value'] = $metadata['sap_post_id_value'][0];
		$query_arr['post_title_oper'] = $metadata['sap_post_title_oper'][0];
		$query_arr['post_title_value'] = $metadata['sap_post_title_value'][0];
		$query_arr['post_thumb_yn'] = $metadata['sap_post_thumb_yn'][0];
		// get taxonomy filter data
		$query_arr['total_taxonomies'] = $metadata['sap_total_taxonomies'][0];
		$total_tax = 0;
		for ($i = 1; $i <= $query_arr['total_taxonomies']; $i++) {
			$tax_name = $metadata["sap_filter_tax".$i."_name"][0];
			$tax_oper = $metadata["sap_filter_tax".$i."_oper"][0];
			$tax_value = $metadata["sap_filter_tax".$i."_value"][0];
			if (($tax_value != '') || ($tax_oper == 'EXISTS') || ($tax_oper == 'NOT EXISTS')) {
				$total_tax++;
				$query_arr["filter_tax".$total_tax."_name"] = $tax_name;
				$query_arr["filter_tax".$total_tax."_oper"] = $tax_oper;
				$query_arr["filter_tax".$total_tax."_value"] = $tax_value;
			}
		}
		$query_arr['total_taxonomies'] = $total_tax;
		// get meta filter data
		$query_arr['total_meta_keys'] = $metadata['sap_total_meta_keys'][0];
		$total_meta = 0;
		for ($i = 1; $i <= $query_arr['total_meta_keys']; $i++) {
			$meta_name = $metadata["sap_filter_meta".$i."_name"][0];
			$meta_oper = $metadata["sap_filter_meta".$i."_oper"][0];
			$meta_value = $metadata["sap_filter_meta".$i."_value"][0];
			if (($meta_value != '') || ($meta_oper == 'EXISTS') || ($meta_oper == 'NOT EXISTS')) {
				$total_meta++;
				$query_arr["filter_meta".$total_meta."_name"] = $meta_name;
				$query_arr["filter_meta".$total_meta."_oper"] = $meta_oper;
				$query_arr["filter_meta".$total_meta."_value"] = $meta_value;
			}
		}
		$query_arr['total_meta_keys'] = $total_meta;
		// get sorting data
		$query_arr['sort_order'] = $metadata['sap_sort_order'][0];
		$query_arr['sort_meta'] = $metadata['sap_sort_meta'][0];
		$query_arr['order_by'] = $metadata['sap_order_by'][0];
		$query_arr['sort_type'] = $metadata['sap_sort_type'][0];

		// SAVE SLIDER SETTINGS IN AN ARRAY
		// number of slides
		$settings['num_slides'] = $metadata['sap_num_slides'][0];
		// slide duration
		$settings['slide_duration'] = floatval($metadata['sap_slide_duration'][0]) * 1000;
		// slide transition
		$settings['slide_transition'] = floatval($metadata['sap_slide_transition'][0]) * 1000;
		// slide by
		$settings['slide_by'] = $metadata['sap_slide_by'][0];
		if ($settings['slide_by'] == '0') {
			$settings['slide_by'] = 'page';
		}
		// loop slider
		$settings['loop_slider'] = $metadata['sap_loop_slider'][0];
		if ($settings['loop_slider'] == '1')	{ $settings['loop_slider'] = 'true'; }
		else												{ $settings['loop_slider'] = 'false'; }
		// navigate arrows
		$settings['nav_arrows'] = $metadata['sap_nav_arrows'][0];
		if ($settings['nav_arrows'] == '1')	{ $settings['nav_arrows'] = 'true'; }
		else											{ $settings['nav_arrows'] = 'false'; }
		// show pagination
		$settings['pagination'] = $metadata['sap_pagination'][0];
		if ($settings['pagination'] == '1')	{ $settings['pagination'] = 'true'; }
		else											{ $settings['pagination'] = 'false'; }
		// stop on hover
		$settings['stop_hover'] = $metadata['sap_stop_hover'][0];
		if ($settings['stop_hover'] == '1')	{ $settings['stop_hover'] = 'true'; }
		else											{ $settings['stop_hover'] = 'false'; }
		// mouse drag
		$settings['mouse_drag'] = $metadata['sap_mouse_drag'][0];
		if ($settings['mouse_drag'] == '1')	{ $settings['mouse_drag'] = 'true'; }
		else											{ $settings['mouse_drag'] = 'false'; }
		// touch drag
		$settings['touch_drag'] = $metadata['sap_touch_drag'][0];
		if ($settings['touch_drag'] == '1')	{ $settings['touch_drag'] = 'true'; }
		else											{ $settings['touch_drag'] = 'false'; }

		// SAVE SLIDER SETTINGS IN AN ARRAY (SLIDE BACKGROUND)
		// background colour
		$settings['slide_bg_color'] = $metadata['sap_slide_bg_color'][0];
		// post type supports featured image (hidden input)
		$settings['supports_featured'] = $metadata['sap_supports_featured'][0];
		if ($settings['supports_featured'] == '1')	{ $settings['supports_featured'] = 'true'; }
		else { $settings['supports_featured'] = 'false'; }
		// use featured image as background image
		$settings['slide_bg_use_featured'] = $metadata['sap_slide_bg_use_featured'][0];
		if ($settings['slide_bg_use_featured'] == '1')	{ $settings['slide_bg_use_featured'] = 'true'; }
		else															{ $settings['slide_bg_use_featured'] = 'false'; }
		// background image position
		if (isset($metadata['sap_slide_bg_position'])) {
			$settings['slide_bg_position'] = $metadata['sap_slide_bg_position'][0];
		}
		// background image size
		if (isset($metadata['sap_slide_bg_size'])) {
			$settings['slide_bg_size'] = $metadata['sap_slide_bg_size'][0];
		}
		// background image repeat
		if (isset($metadata['sap_slide_bg_repeat'])) {
			$settings['slide_bg_repeat'] = $metadata['sap_slide_bg_repeat'][0];
		}
		// background image - wordpress image size
		if (isset($metadata['sap_slide_bg_wp_imagesize'])) {
			$settings['slide_bg_wp_imagesize'] = $metadata['sap_slide_bg_wp_imagesize'][0];
		}

		// SAVE SLIDER SETTINGS IN AN ARRAY (ITEMS DISPLAYED)
		$settings['items_width1'] = $metadata['sap_items_width1'][0];
		$settings['items_width2'] = $metadata['sap_items_width2'][0];
		$settings['items_width3'] = $metadata['sap_items_width3'][0];
		$settings['items_width4'] = $metadata['sap_items_width4'][0];
		$settings['items_width5'] = $metadata['sap_items_width5'][0];
		$settings['items_width6'] = $metadata['sap_items_width6'][0];
		// slide transition
		$settings['transition'] = $metadata['sap_transition'][0];

		// SAVE SLIDER SETTINGS IN AN ARRAY (SLIDER STYLE)
		// slider css id
		$settings['css_id'] = $metadata['sap_css_id'][0];
		// slider padding
		$settings['wrapper_padd_top'] = $metadata['sap_wrapper_padd_top'][0];
		$settings['wrapper_padd_right'] = $metadata['sap_wrapper_padd_right'][0];
		$settings['wrapper_padd_bottom'] = $metadata['sap_wrapper_padd_bottom'][0];
		$settings['wrapper_padd_left'] = $metadata['sap_wrapper_padd_left'][0];
		// slider background colour
		$settings['background_color'] = $metadata['sap_background_color'][0];
		// slider border
		$settings['border_width'] = $metadata['sap_border_width'][0];
		$settings['border_color'] = $metadata['sap_border_color'][0];
		$settings['border_radius'] = $metadata['sap_border_radius'][0];

		// SAVE SLIDER SETTINGS IN AN ARRAY (SLIDE STYLE)
		// slide min height settings
		$settings['slide_min_height_perc'] = $metadata['sap_slide_min_height_perc'][0];
		// slide padding
		$settings['slide_padding_tb'] = $metadata['sap_slide_padding_tb'][0];
		$settings['slide_padding_lr'] = $metadata['sap_slide_padding_lr'][0];
		// slide margin
		$settings['slide_margin_lr'] = $metadata['sap_slide_margin_lr'][0];

		// SAVE SLIDER SETTINGS IN AN ARRAY (NAVIGATION ARROWS)
		// prev/next arrows - scheme (white/grey/black/custom)
		$settings['arrow_images'] = $metadata['sap_arrow_images'][0];
		// autohide arrows
		$settings['autohide_arrows'] = $metadata['sap_autohide_arrows'][0];
		if ($settings['autohide_arrows'] == '1')	{ $settings['autohide_arrows'] = 'true'; }
		else													{ $settings['autohide_arrows'] = 'false'; }
		// prev/next arrows - image urls
		if (isset($metadata['sap_prev_arrow_url'])) {
			$settings['prev_arrow_url'] = $metadata['sap_prev_arrow_url'][0];
		}
		if (isset($metadata['sap_next_arrow_url'])) {
			$settings['next_arrow_url'] = $metadata['sap_next_arrow_url'][0];
		}
		// prev/next arrows - wrapper width and height
		if (isset($metadata['sap_arrows_wrapper_width'])) {
			$settings['arrows_wrapper_width'] = $metadata['sap_arrows_wrapper_width'][0];
		}
		if (isset($metadata['sap_arrows_wrapper_height'])) {
			$settings['arrows_wrapper_height'] = $metadata['sap_arrows_wrapper_height'][0];
		}
		// prev/next arrows - wrapper border radius
		if (isset($metadata['sap_arrows_wrapper_bordradius'])) {
			$settings['arrows_wrapper_bordradius'] = $metadata['sap_arrows_wrapper_bordradius'][0];
		}
		// prev/next arrows - wrapper background colour and hover background colour
		if (isset($metadata['sap_arrows_wrapper_bgcol'])) {
			$settings['arrows_wrapper_bgcol'] = $metadata['sap_arrows_wrapper_bgcol'][0];
		}
		if (isset($metadata['sap_arrows_hover_bgcol'])) {
			$settings['arrows_hover_bgcol'] = $metadata['sap_arrows_hover_bgcol'][0];
		}

		// SAVE SLIDER SETTINGS IN AN ARRAY (LINK TO SINGLE POST)
		// use a post link
		$settings['post_link_yn'] = $metadata['sap_post_link_yn'][0];
		if ($settings['post_link_yn'] == '1')	{ $settings['post_link_yn'] = 'true'; }
		else												{ $settings['post_link_yn'] = 'false'; }
		// use a post link - link location
		$settings['slide_link_location'] = $metadata['sap_slide_link_location'][0];
		// use a post link - link icon url
		if (isset($metadata['sap_link_icon_url'])) {
			$settings['link_icon_url'] = $metadata['sap_link_icon_url'][0];
		}
		// use a post link - wrapper width and height
		if (isset($metadata['sap_link_icon_width'])) {
			$settings['link_icon_width'] = $metadata['sap_link_icon_width'][0];
		}
		if (isset($metadata['sap_link_icon_height'])) {
			$settings['link_icon_height'] = $metadata['sap_link_icon_height'][0];
		}
		// use a post link - wrapper border radius
		if (isset($metadata['sap_link_icon_bordradius'])) {
			$settings['link_icon_bordradius'] = $metadata['sap_link_icon_bordradius'][0];
		}
		// use a post link - wrapper background colour and hover background colour
		if (isset($metadata['sap_link_wrapper_bgcol'])) {
			$settings['link_wrapper_bgcol'] = $metadata['sap_link_wrapper_bgcol'][0];
		}
		if (isset($metadata['sap_link_hover_bgcol'])) {
			$settings['link_hover_bgcol'] = $metadata['sap_link_hover_bgcol'][0];
		}

		// SAVE SLIDER SETTINGS IN AN ARRAY (ADVANCED SETTINGS)
		// allow shortcodes
		$settings['shortcodes'] = $metadata['sap_shortcodes'][0];
		if ($settings['shortcodes'] == '1')	{ $settings['shortcodes'] = 'true'; }
		else											{ $settings['shortcodes'] = 'false'; }
		// slider auto height
		$settings['auto_height'] = $metadata['sap_auto_height'][0];
		if ($settings['auto_height'] == '1')	{ $settings['auto_height'] = 'true'; }
		else												{ $settings['auto_height'] = 'false'; }
		// use window.onload event
		$settings['window_onload'] = $metadata['sap_window_onload'][0];
		if ($settings['window_onload'] == '1')	{ $settings['window_onload'] = 'true'; }
		else												{ $settings['window_onload'] = 'false'; }
		// remove javascript content
		$settings['strip_javascript'] = $metadata['sap_strip_javascript'][0];
		if ($settings['strip_javascript'] == '1')	{ $settings['strip_javascript'] = 'true'; }
		else													{ $settings['strip_javascript'] = 'false'; }
		// lazy load images
		$settings['lazy_load_images'] = '0';
		if (isset($metadata['sap_lazy_load_images'])) {
			$settings['lazy_load_images'] = $metadata['sap_lazy_load_images'][0];
		}

		// BUILD WORDPRESS QUERY FROM SLIDER FILTER/SORT DATA
		// initial query arguments
		$args = array('post_type' => $query_arr['post_type'], 'posts_per_page' => $settings['num_slides']);
		// filter for post ids
		if ($query_arr['post_id_value'] != '') {
			$value_arr = explode("|", $query_arr['post_id_value']);
			if ($query_arr['post_id_oper'] == 'IN') {
				$args['post__in'] = $value_arr;
			} elseif ($query_arr['post_id_oper'] == 'NOT IN') {
				$args['post__not_in'] = $value_arr;
			}
		}
		// filter for 'Post Title LIKE'
		if (($query_arr['post_title_oper'] == 'LIKE') && ($query_arr['post_title_value'] != '')) {
			$args['search_title_like'] = $query_arr['post_title_value'];
		}
		// filter for 'Post Title NOT LIKE'
		if (($query_arr['post_title_oper'] == 'NOT LIKE') && ($query_arr['post_title_value'] != '')) {
			$args['search_title_not_like'] = $query_arr['post_title_value'];
		}
		// filter for 'Has a Featured Image'
		if ($query_arr['post_thumb_yn'] == '1') {
			$args['meta_query'] = array(array('key' => '_thumbnail_id','compare' => 'EXISTS'));
		}
		// taxonomy filter (loop)
		$total_tax = intval($query_arr['total_taxonomies']);
		if ($total_tax != 0) {
			$tax_args = array();
			for ($i = 1; $i <= $total_tax; $i++) {
				$ftax_name = $query_arr['filter_tax'.$i.'_name'];
				$ftax_oper = $query_arr['filter_tax'.$i.'_oper'];
				$ftax_value = $query_arr['filter_tax'.$i.'_value'];
				if (($ftax_oper == 'IN') || ($ftax_oper == 'NOT IN')) {
					$value_arr = explode("|", $ftax_value);
					$tax_args[] = array('taxonomy' => $ftax_name, 'field' => 'slug', 'terms' => $value_arr, 'operator' => $ftax_oper);
				} elseif (($ftax_oper == 'EXISTS') || ($ftax_oper == 'NOT EXISTS')) {
					$tax_args[] = array('taxonomy' => $ftax_name, 'operator' => $ftax_oper);
				}
			}
			if ($total_tax == 1) {
				$args['tax_query'] = array($tax_args);
			} else {
				$args['tax_query'] = array('relation' => 'AND', $tax_args);
			}
		}
		// meta data filter (loop)
		$total_meta = intval($query_arr['total_meta_keys']);
		if ($total_meta != 0) {
			$meta_args = array();
			for ($i = 1; $i <= $total_meta; $i++) {
				$fmeta_name = $query_arr["filter_meta".$i."_name"];
				$fmeta_oper = $query_arr["filter_meta".$i."_oper"];
				$fmeta_value = $query_arr["filter_meta".$i."_value"];
				if (($fmeta_oper == '=') || ($fmeta_oper == '!=')) {
					$meta_args[] = array('key' => $fmeta_name, 'value' => $fmeta_value, 'compare' => $fmeta_oper);
				} elseif (($fmeta_oper == '&lt;') || ($fmeta_oper == '>')) {
					$fmeta_type = 'CHAR';
					if (is_numeric($fmeta_value)) {
						$fmeta_type = 'NUMERIC';
						if (is_float($fmeta_value)) {
							$fmeta_type = 'DECIMAL';
						}
					}
					if ($fmeta_oper == '&lt;') {
						$meta_args[] = array('key' => $fmeta_name, 'value' => $fmeta_value, 'type' => $fmeta_type, 'compare' => '<');
					} else {
						$meta_args[] = array('key' => $fmeta_name, 'value' => $fmeta_value, 'type' => $fmeta_type, 'compare' => '>');
					}
				} elseif (($fmeta_oper == "LIKE") || ($fmeta_oper == "NOT LIKE")) {
					$meta_args[] = array('key' => $fmeta_name, 'value' => $fmeta_value, 'compare' => $fmeta_oper);
				} elseif (($fmeta_oper == "IN") || ($fmeta_oper == "NOT IN")) {
					$value_arr = explode("|", $fmeta_value);
					$fmeta_type = 'CHAR';
					if (is_numeric($value_arr[0])) {
						$fmeta_type = 'NUMERIC';
						if (is_float($value_arr[0])) {
							$fmeta_type = 'DECIMAL';
						}
					}
					$meta_args[] = array('key' => $fmeta_name, 'value' => $value_arr, 'type' => $fmeta_type, 'compare' => $fmeta_oper);
				} elseif (($fmeta_oper == "BETWEEN") || ($fmeta_oper == "NOT BETWEEN")) {
					$value_arr = explode("|", $fmeta_value, 2);
					$fmeta_type = 'CHAR';
					if (is_numeric($value_arr[0])) {
						$fmeta_type = 'NUMERIC';
						if (is_float($value_arr[0])) {
							$fmeta_type = 'DECIMAL';
						}
					}
					$meta_args[] = array('key' => $fmeta_name, 'value' => $value_arr, 'type' => $fmeta_type, 'compare' => $fmeta_oper);
				} elseif (($fmeta_oper == 'EXISTS') || ($fmeta_oper == 'NOT EXISTS')) {
					$meta_args[] = array('key' => $fmeta_name, 'operator' => $fmeta_oper);
					if ($fmeta_oper == 'EXISTS') {
						$meta_args[] = array('key' => $fmeta_name, 'value' => '', 'type' => 'CHAR', 'compare' => '!=');
					} else {
						$meta_args[] = array('key' => $fmeta_name, 'value' => '', 'type' => 'CHAR', 'compare' => '=');
					}
				}
			}
			if ($total_meta == 1) {
				$args['meta_query'] = array($meta_args);
			} else {
				$args['meta_query'] = array('relation' => 'AND', $meta_args);
			}
		}
		// query sort parameters
		if (in_array($query_arr['sort_order'], array("ID", "author", "title", "name", "date", "modified", "rand"))) {
			$args['orderby'] = $query_arr['sort_order'];
			$args['order'] = $query_arr['order_by'];
		}
		if ($query_arr['sort_order'] == "meta_value") {
			if ($query_arr['sort_type'] == "num") {
				$args['orderby'] = 'meta_value_num';
			} else {
				$args['orderby'] = 'meta_value';
			}
			$args['meta_key'] = $query_arr['sort_meta'];
			$args['order'] = $query_arr['order_by'];
		}
		// ### EXECUTE WORDPRESS QUERY - SAVE QUERY POST IDS TO ARRAY ###
		$post_ids = array();
		$sap_query = new WP_Query($args);
		if ($sap_query->have_posts()) {
			while ($sap_query->have_posts()) {
				$sap_query->the_post();
				$post_ids[] = get_the_ID();
			}
		}
		wp_reset_postdata();

		if (count($post_ids) == 0) {
			// ### NO RESULTS WERE FOUND FOR THE QUERY - DISPLAY MESSAGE ###
			$output .= "<div id='sap_invalid_message'>No posts found for the specified query parameters.</div>\n";
		} else {
			// ### HTML CODE FOR OWL CAROUSEL SLIDER - OPEN WRAPPER ###
			$wrapper_style =  "background:".$settings['background_color']."; ";
			$wrapper_style .=  "border:solid ".$settings['border_width']."px ".$settings['border_color']."; ";
			$wrapper_style .=  "border-radius:".$settings['border_radius']."px; ";
			$wrapper_style .=  "padding:".$settings['wrapper_padd_top']."px ";
			$wrapper_style .= $settings['wrapper_padd_right']."px ";
			$wrapper_style .= $settings['wrapper_padd_bottom']."px ";
			$wrapper_style .= $settings['wrapper_padd_left']."px;";
			$output .= "<div style='".esc_attr($wrapper_style)."'>\n";
			$additional_classes = '';
			if ($settings['pagination'] == 'true') {
				if ($settings['autohide_arrows'] == 'true') {
					$additional_classes = "owl-pagination-true autohide-arrows";
				} else {
					$additional_classes = "owl-pagination-true";
				}
			} else {
				if ($settings['autohide_arrows'] == 'true') {
					$additional_classes = "autohide-arrows";
				}
			}
			if ($settings['lazy_load_images'] == '1') {
				$additional_classes .= " sap-lazy-load";
			}
			$output .= "<div id='".esc_attr($settings['css_id'])."' class='owl-carousel sap_owl_theme ".$additional_classes."' style='visibility:hidden;'>\n";

			for ($i = 0; $i < count($post_ids); $i++) {
				// ### HTML CODE FOR OWL CAROUSEL SLIDER - SINGLE SLIDE ###
				$post_id = $post_ids[$i];
				// build slide style
				$slide_style =  "padding:".$settings['slide_padding_tb']."% ".$settings['slide_padding_lr']."%; ";
				$slide_style .= "margin:0px ".$settings['slide_margin_lr']."%; ";
				$slide_style .= "background-color:".$settings['slide_bg_color']."; ";
				if (($settings['supports_featured'] == 'true') && ($settings['slide_bg_use_featured'] == 'true')) {
					// slide background image style
					$bg_src = get_the_post_thumbnail_url($post_id, $settings['slide_bg_wp_imagesize']);
					$slide_style .= "background-image:url(\"".$bg_src."\"); ";
					$slide_style .= "background-position:".$settings['slide_bg_position']."; ";
					$slide_style .= "background-size:".$settings['slide_bg_size']."; ";
					$slide_style .= "background-repeat:".$settings['slide_bg_repeat']."; ";
				}
				if (strpos($settings['slide_min_height_perc'], 'px') !== false) {
					$slide_style .= "min-height:".$settings['slide_min_height_perc']."; ";
				}
				$output .= "<div class='sap_slider_container' style='".esc_attr($slide_style)."'>";
				// post link icon
				if ($settings['post_link_yn'] == 'true') {
					$slide_link = get_permalink($post_id);
					if ($settings['slide_link_location'] == 'Entire Slide') {
						// no link icon - the entire slide is a clickable link
						$style = "background-color:".$settings['link_hover_bgcol']."; ";
						if ($settings['link_icon_url'] != '') {
							$style .= "background-image:url(&quot;".$settings['link_icon_url']."&quot;); ";
						}
						$output .= "<a class='sap_entire_slide_link' style='".$style."' href='".$slide_link."'></a>";
					} else {
						$mtop = intval(intval($settings['link_icon_height']) / 2);
						$mleft = intval(intval($settings['link_icon_width']) / 2);
						$style =  "width:".$settings['link_icon_width']."px; height:".$settings['link_icon_height']."px; ";
						$style .= "border-radius:".$settings['link_icon_bordradius']."px; ";
						$style .= "background-color:".$settings['link_wrapper_bgcol']."; ";
						if ($settings['link_icon_url'] != '') {
							$style .= "background-image:url(&quot;".$settings['link_icon_url']."&quot;); ";
						}
						if ($settings['slide_link_location'] == 'Top Left') {					// icons location - top left
							$style .= "top:0px; left:0px; margin:0px;";
						} elseif ($settings['slide_link_location'] == 'Top Center') {		// icons location - top center
							$style .= "top:0px; left:50%; margin-left:-".$mleft."px;";
						} elseif ($settings['slide_link_location'] == 'Top Right') {		// icons location - top right
							$style .= "top:0px; right:0px; margin:0px;";
						} elseif ($settings['slide_link_location'] == 'Bottom Left') {		// icons location - bottom left
							$style .= "bottom:0px; left:0px; margin:0px;";
						} elseif ($settings['slide_link_location'] == 'Bottom Center') {	// icons location - bottom center
							$style .= "bottom:0px; left:50%; margin-left:-".$mleft."px;";
						} elseif ($settings['slide_link_location'] == 'Bottom Right') {	// icons location - bottom right
							$style .= "bottom:0px; right:0px; margin:0px;";
						} else {																				// icons location - center center (default)
							$style .= "top:50%; left:50%; margin-top:-".$mtop."px; margin-left:-".$mleft."px;";
						}

						// onMouseOver and onMouseOut javascript for clickable link
						$link_mover = "this.style.backgroundColor = &quot;".$settings['link_hover_bgcol']."&quot;;";
						$link_mout  = "this.style.backgroundColor = &quot;".$settings['link_wrapper_bgcol']."&quot;;";
						$output .= "<div class='sap_hover_buttons' style='".$style."' onMouseOver='".$link_mover."' onMouseOut='".$link_mout."'>";
						$output .= "<a class='sap_slide_link_icon' href='".$slide_link."'></a>";
						$output .= "</div>\n"; // .sap_hover_buttons
					}
				}
				// get slide content and display it
				$slide_content = $slider_template;
				$slide_content = substitute_post_type_fields($slide_content, $post_id);
				if ($settings['shortcodes'] == 'true') {
					$slide_content = do_shortcode($slide_content);
				}
				if ($settings['strip_javascript'] == 'true') {
					// strip JavaScript code (<script> tags) from slide content
					$slide_content = sap_remove_javascript_from_content($slide_content);
				}
				if ($settings['lazy_load_images'] == '1') {
					// modify images (<img> tag) within slide content to enable owl carousel lazy load
					$slide_content = sap_set_slide_images_to_lazy_load($slide_content);
				}
				$output .= $slide_content;
				$output .= "</div>\n"; // .sap_slider_container
			}

			// ### GENERATE HTML DIVS FOR NAVIGATION ARROWS ###
			// css inline style for prev/next arrow divs
			$arr_margin_top = intval(intval($settings['arrows_wrapper_height']) / 2);
			$prev_arr_css =  "width:".$settings['arrows_wrapper_width']."px; height:".$settings['arrows_wrapper_height']."px; ";
			$prev_arr_css .= "background-color:".$settings['arrows_wrapper_bgcol']."; margin-top:-".$arr_margin_top."px; ";
			$prev_arr_css .= "border-radius:".$settings['arrows_wrapper_bordradius']."px; ";
			$next_arr_css = $prev_arr_css;
			$prev_arr_css .= "background-image:url(&quot;".$settings['prev_arrow_url']."&quot;); ";
			$next_arr_css .= "background-image:url(&quot;".$settings['next_arrow_url']."&quot;); ";
			// onMouseOver and onMouseOut javascript for prev/next arrow divs
			$arr_mover = "this.style.backgroundColor = &quot;".$settings['arrows_hover_bgcol']."&quot;;";
			$arr_mout  = "this.style.backgroundColor = &quot;".$settings['arrows_wrapper_bgcol']."&quot;;";
			// create divs for prev/next arrows
			$prev_arr_div = "<div style=\'".$prev_arr_css."\' onMouseOver='".$arr_mover."' onMouseOut='".$arr_mout."'></div>";
			$next_arr_div = "<div style=\'".$next_arr_css."\' onMouseOver='".$arr_mover."' onMouseOut='".$arr_mout."'></div>";

			// ### HTML CODE FOR OWL CAROUSEL SLIDER - CLOSE WRAPPER ###
			$output .= "</div>\n";
			$output .= "</div>\n";

			// ### ENQUEUE JQUERY SCRIPT IF IT HAS NOT ALREADY BEEN LOADED ###
			if (!wp_script_is('jquery', 'done')) {
				wp_enqueue_script('jquery');
			}

			// ### GENERATE JQUERY CODE FOR THE OWL CAROUSEL SLIDER ###
			if (wp_script_is('jquery', 'done')) { // Only generate JQuery code if JQuery has been loaded
				if (($settings['items_width1'] == 1) && ($settings['items_width2'] == 1) && ($settings['items_width3'] == 1) &&
					 ($settings['items_width4'] == 1) && ($settings['items_width5'] == 1) && ($settings['items_width6'] == 1)) {
					$single_item = 1;
				} else {
					$single_item = 0;
				}
				$output .= "<script type='text/javascript'>\n";
				if ($settings['window_onload'] == 'true') {
					$output .= "	document.addEventListener('DOMContentLoaded', function() {\n";
				} else {
					$output .= "	jQuery(document).ready(function() {\n";
				}

				// JQUERY CODE FOR OWN CAROUSEL
				$output .= "		jQuery('#".esc_attr($settings['css_id'])."').owlCarousel({\n";
				if ($single_item == 1) {
					$output .= "			items : 1,\n";
					if (($settings['transition'] == 'Fade') || ($settings['transition'] == 'fade')) {
						$output .= "			animateOut : 'fadeOut',\n";
					} elseif (($settings['transition'] == 'Slide Down') || ($settings['transition'] == 'goDown')) {
						$output .= "			animateOut : 'slideOutDown',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Zoom In') {
						$output .= "			animateOut : 'fadeOut',\n";
						$output .= "			animateIn : 'zoomIn',\n";
					} elseif ($settings['transition'] == 'Zoom Out') {
						$output .= "			animateOut : 'zoomOut',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Flip Out X') {
						$output .= "			animateOut : 'flipOutX',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Flip Out Y') {
						$output .= "			animateOut : 'flipOutY',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Rotate Left') {
						$output .= "			animateOut : 'rotateOutDownLeft',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Rotate Right') {
						$output .= "			animateOut : 'rotateOutDownRight',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Bounce Out') {
						$output .= "			animateOut : 'bounceOut',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					} elseif ($settings['transition'] == 'Roll Out') {
						$output .= "			animateOut : 'rollOut',\n";
						$output .= "			animateIn : 'fadeIn',\n";
					}
					$output .= "			smartSpeed : ".esc_attr($settings['slide_transition']).",\n";
				} else {
					$output .= "			responsive:{\n";
					$output .= "				0:{ items:".esc_attr($settings['items_width1'])." },\n";
					$output .= "				480:{ items:".esc_attr($settings['items_width2'])." },\n";
					$output .= "				768:{ items:".esc_attr($settings['items_width3'])." },\n";
					$output .= "				980:{ items:".esc_attr($settings['items_width4'])." },\n";
					$output .= "				1200:{ items:".esc_attr($settings['items_width5'])." },\n";
					$output .= "				1500:{ items:".esc_attr($settings['items_width6'])." }\n";
					$output .= "			},\n";
				}
				if ($settings['slide_duration'] == 0) {
					$output .= "			autoplay : false,\n";
					$output .= "			autoplayHoverPause : false,\n";
				} else {
					$output .= "			autoplay : true,\n";
					$output .= "			autoplayTimeout : ".esc_attr($settings['slide_duration']).",\n";
					$output .= "			autoplayHoverPause : ".esc_attr($settings['stop_hover']).",\n";
				}
				$output .= "			smartSpeed : ".esc_attr($settings['slide_transition']).",\n";
				$output .= "			fluidSpeed : ".esc_attr($settings['slide_transition']).",\n";
				$output .= "			autoplaySpeed : ".esc_attr($settings['slide_transition']).",\n";
				$output .= "			navSpeed : ".esc_attr($settings['slide_transition']).",\n";
				$output .= "			dotsSpeed : ".esc_attr($settings['slide_transition']).",\n";
				$output .= "			loop : ".esc_attr($settings['loop_slider']).",\n";
				$output .= "			nav : ".esc_attr($settings['nav_arrows']).",\n";
				$output .= "			navText : [\"".$prev_arr_div."\",\"".$next_arr_div."\"],\n";
				$output .= "			dots : ".esc_attr($settings['pagination']).",\n";
				$output .= "			responsiveRefreshRate : 200,\n";
				if ($settings['slide_by'] == 'page') {
					$output .= "			slideBy : 'page',\n";
				} else {
					$output .= "			slideBy : ".esc_attr($settings['slide_by']).",\n";
				}
				$output .= "			mergeFit : true,\n";
				//$output .= "			URLhashListener : true,\n";
				$output .= "			autoHeight : ".esc_attr($settings['auto_height']).",\n";
				if ($settings['lazy_load_images'] == '1') {
					$output .= "			lazyLoad : true,\n";
					$output .= "			lazyLoadEager: 1,\n";
				}
				$output .= "			mouseDrag : ".esc_attr($settings['mouse_drag']).",\n";
				$output .= "			touchDrag : ".esc_attr($settings['touch_drag'])."\n";
				$output .= "		});\n";

				// MAKE SLIDER VISIBLE (AFTER 'WINDOW ONLOAD' OR 'DOCUMENT READY' EVENT)
				$output .= "		jQuery('#".esc_attr($settings['css_id'])."').css('visibility', 'visible');\n";

				// JAVASCRIPT 'WINDOW RESIZE' EVENT TO SET CSS 'min-height' OF SLIDES WITHIN THIS SLIDER
				$slide_min_height = $settings['slide_min_height_perc'];
				if (strpos($slide_min_height, 'px') !== false) {
					$slide_min_height = 0;
				}
				if (($slide_min_height != '') && ($slide_min_height != '0')) {
					$output .= "		sap_resize_".esc_attr($settings['css_id'])."();\n";	// initial call of resize function
					$output .= "		window.addEventListener('resize', sap_resize_".esc_attr($settings['css_id']).");\n"; // create resize event
											// RESIZE EVENT FUNCTION (to set slide CSS 'min-heigh')
					$output .= "		function sap_resize_".esc_attr($settings['css_id'])."() {\n";
												// get slide min height setting
					$output .= "			var min_height = '".$slide_min_height."';\n";
												// get window width
					$output .= "			var win_width = jQuery(window).width();\n";
					$output .= "			var slider_width = jQuery('#".esc_attr($settings['css_id'])."').width();\n";
												// calculate slide width according to window width & number of slides
					$output .= "			if (win_width < 480) {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width1']).";\n";
					$output .= "			} else if (win_width < 768) {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width2']).";\n";
					$output .= "			} else if (win_width < 980) {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width3']).";\n";
					$output .= "			} else if (win_width < 1200) {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width4']).";\n";
					$output .= "			} else if (win_width < 1500) {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width5']).";\n";
					$output .= "			} else {\n";
					$output .= "				var slide_width = slider_width / ".esc_attr($settings['items_width6']).";\n";
					$output .= "			}\n";
					$output .= "			slide_width = Math.round(slide_width);\n";
												// calculate CSS 'min-height' using the captured 'min-height' data settings for this slider
					$output .= "			var slide_height = '0';\n";
					$output .= "			if (min_height == 'aspect43') {\n";
					$output .= "				slide_height = (slide_width / 4) * 3;";
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			} else if (min_height == 'aspect169') {\n";
					$output .= "				slide_height = (slide_width / 16) * 9;";
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			} else {\n";
					$output .= "				slide_height = (slide_width / 100) * min_height;";
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			}\n";
												// set the slide 'min-height' css value
					$output .= "			jQuery('#".esc_attr($settings['css_id'])." .owl-item .sap_slider_container').css('min-height', slide_height+'px');\n";
					$output .= "		}\n";
				}

				$output .= "	});\n";
				$output .= "</script>\n";
			}
		}
	}

	return $output;
}



// ### CREATE CUSTOM FILTERS FOR THE WORDPRESS QUERY ('WP-Query') ###
add_filter('posts_where', 'sap_wp_query_custom_filters', 10, 2);
function sap_wp_query_custom_filters($where, $wp_query) {
	global $wpdb;

	// POST TITLE LIKE
	if ($search_term = $wp_query->get('search_title_like')) {
		$search_term = $wpdb->esc_like($search_term);
		$search_term = ' \'%'.$search_term.'%\'';
		$where .= ' AND '.$wpdb->posts.'.post_title LIKE '.$search_term;
	}
	// POST TITLE NOT LIKE
	if ($search_term = $wp_query->get('search_title_not_like')) {
		$search_term = $wpdb->esc_like($search_term);
		$search_term = ' \'%'.$search_term.'%\'';
		$where .= ' AND '.$wpdb->posts.'.post_title NOT LIKE '.$search_term;
	}
	return $where;
}



// ### PROCESS SLIDE CONTENT AND REPLACE 'Slide Any Post' {PLACEHOLDERS} WITH CORRESPONDING POST DATA ###
function substitute_post_type_fields($slide_content, $post_id) {
	// PLACEHOLDER: POST TITLE
	if (strpos($slide_content, "{POST_TITLE}") !== false) {
		$post_title = get_the_title($post_id);
		$slide_content = str_replace("{POST_TITLE}", $post_title, $slide_content);
	}
	// PLACEHOLDER: POST TITLE LINK
	if (strpos($slide_content, "{POST_TITLE_LINK}") !== false) {
		$post_title = get_the_title($post_id);
		$link_url = get_permalink($post_id);
		$replacement = "<a class='title_link' href='".$link_url."'>".$post_title."</a>";
		$slide_content = str_replace("{POST_TITLE_LINK}", $replacement, $slide_content);
	}
	// PLACEHOLDER: POST URL
	if (strpos($slide_content, "{POST_URL}") !== false) {
		$link_url = get_permalink($post_id);
		$slide_content = str_replace("{POST_URL}", $link_url, $slide_content);
	}
	// PLACEHOLDER: DESCRIPTION
	if (strpos($slide_content, "{DESCRIPTION}") !== false) {
		$content = get_post_field('post_content', $post_id);
		$slide_content = str_replace("{DESCRIPTION}", $content, $slide_content);
	}
	// PLACEHOLDER: EXCERPT
	if (strpos($slide_content, "{EXCERPT}") !== false) {
		$excerpt = get_the_excerpt($post_id);
		$slide_content = str_replace("{EXCERPT}", $excerpt, $slide_content);
	}
	// PLACEHOLDER: FEATURED IMAGE
	if (preg_match_all("/{FEATURED_IMAGE~.*?}/", $slide_content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$placeholder = $matches[0][$i];
			$ph_arr = explode("~", trim($placeholder, "{}"));
			$image_src = get_the_post_thumbnail_url($post_id, $ph_arr[1]);
			$replacement = "<img src='".$image_src."'/>";
			$slide_content = str_replace($placeholder, $replacement, $slide_content);
		}
	}
	// PLACEHOLDER: FEATURED IMAGE LINK
	if (preg_match_all("/{FEATURED_IMAGE_LINK~.*?}/", $slide_content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$placeholder = $matches[0][$i];
			$link_url = get_permalink($post_id);
			$ph_arr = explode("~", trim($placeholder, "{}"));
			$image_src = get_the_post_thumbnail_url($post_id, $ph_arr[1]);
			$replacement = "<a class='image_link' href='".$link_url."'><img src='".$image_src."'/></a>";
			$slide_content = str_replace($placeholder, $replacement, $slide_content);
		}
	}
	// PLACEHOLDER: POST LINK
	if (preg_match_all("/{POST_LINK~.*?}/", $slide_content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$placeholder = $matches[0][$i];
			$ph_arr = explode("~", trim($placeholder, "{}"));
			$link_url = get_permalink($post_id);
			$replacement = "<a class='post_link' href='".$link_url."'>".$ph_arr[1]."</a>";
			$slide_content = str_replace($placeholder, $replacement, $slide_content);
		}
	}
	// PLACEHOLDER: META DATA FIELDS
	if (preg_match_all("/{META~.*?}/", $slide_content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$placeholder = $matches[0][$i];
			$ph_arr = explode("~", trim($placeholder, "{}"));
			$meta_data = get_post_meta($post_id, $ph_arr[1], true);
			$format = $ph_arr[2];
			if ($format == 'string') {
				$replacement = $meta_data;
			} elseif ($format == 'integer') {
				$replacement = intval($meta_data);
			} elseif ($format == 'float') {
				$replacement = floatval($meta_data);
			} elseif ($format == 'currency1') {
				$replacement = number_format_i18n($meta_data, 2);
			} elseif ($format == 'currency2') {
				$replacement = number_format_i18n($meta_data, 0);
			}
			$slide_content = str_replace($placeholder, $replacement, $slide_content);
		}
	}
	// PLACEHOLDER: TAXONOMY FIELDS
	if (preg_match_all("/{TAX~.*?}/", $slide_content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$placeholder = $matches[0][$i];
			$ph_arr = explode("~", trim($placeholder, "{}"));
			$terms = get_the_terms($post_id , $ph_arr[1]);
			$tax_info = get_taxonomy($ph_arr[1]);
			$tax_folder = $tax_info->rewrite['slug'];
			$replacement = '';
			foreach ($terms as $term) {
				if ($ph_arr[2] == 'comma_list') {
					if ($replacement != '') { $replacement .= ", "; }
					$replacement .= $term->name;
				} elseif ($ph_arr[2] == 'comma_links') {
					$term_link = get_site_url()."/".$tax_folder."/".$term->slug;
					if ($replacement != '') { $replacement .= ", "; }
					$replacement .= "<a href='".$term_link."'>".$term->name."</a>";
				} elseif ($ph_arr[2] == 'space_list') {
					if ($replacement != '') { $replacement .= " "; }
					$replacement .= $term->name;
				} elseif ($ph_arr[2] == 'space_links') {
					$term_link = get_site_url()."/".$tax_folder."/".$term->slug;
					if ($replacement != '') { $replacement .= " "; }
					$replacement .= "<a href='".$term_link."'>".$term->name."</a>";
				}
			}
			$slide_content = str_replace($placeholder, $replacement, $slide_content);
		}
	}
	return $slide_content;
}



// ### STRIP JAVASCRIPT ('<script>' tags) FROM SUPPLIED STRING ARGUMENT ###
function sap_remove_javascript_from_content($slide_content) {
	if ($slide_content != '') {
		$dom = new DOMDocument();
		$dom->loadHTML($slide_content);
		//$dom->loadHTML($slide_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$script = $dom->getElementsByTagName('script');
		$remove = array();
		foreach($script as $item) {
			$item->parentNode->removeChild($item);
		}
		//$slide_content = $dom->saveHTML();
		$slide_content = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
	}
	return $slide_content;
}



// ### MODIFY IMAGES (<img> tag) WITHIN STRING PASSED (SLIDE CONTENT) TO ENABLE OWL CAROUSEL LAZY LOAD ###
function sap_set_slide_images_to_lazy_load($slide_content) {
	// 1) REPLACE 'src=' WITH 'data-src=' WITHIN <IMG> TAGS
	$slide_content = preg_replace('~<img[^>]*\K(?=src)~i','data-', $slide_content);

	// 2) FOR EACH <IMG> TAG WITHIN THE SLIDE CONTENT, ADD THE 'owl-lazy' CLASS
	$dom = new DOMDocument();
	$dom->loadHTML(mb_convert_encoding($slide_content, 'HTML-ENTITIES', 'UTF-8'));
	$imgs = $dom->getElementsByTagName('img');
	foreach ($imgs as $img) {
		$curr_class = $img->getAttribute('class');
		if ($curr_class != '') {
			$img->setAttribute('class', $curr_class.' owl-lazy');
		} else {
			$img->setAttribute('class', 'owl-lazy');
		}
	}

	$slide_content = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
	return $slide_content;
}
?>