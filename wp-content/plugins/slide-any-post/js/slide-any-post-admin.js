// ##################################################################
// ### SLIDE ANY POST PLUGIN - JAVASCRIPT FOR WORDPRESS DASHBOARD ###
// ##################################################################

jQuery(function() {
	// ##### JQUERY-UI TOOLTIPS #####
	jQuery(".sap_tooltip").tooltip();

	// ##### PERFORM INITIAL AJAX CALLS TO POPULATE AJAX CONTAINERS #####
	var post_type = jQuery("#sap_post_type").val();
	ajax_post_type_dropdown_changed(post_type);

	// ##### CHANGE EVENT FOR 'POST TYPE' DROP-DOWN BOX #####
	jQuery("#sap_post_type").change(function () {
		var post_type = this.value;
		ajax_post_type_dropdown_changed(post_type);
	});

	// ##### 'POST TYPE'  DROPDOWN CHANGED - PERFORM AJAX CALLS PASSING THE 'post_type' PARAMETER #####
	function ajax_post_type_dropdown_changed(post_type) {
		var post_id = jQuery('#sap_post_id').val();

		// ##### PERFORM 'post_type_filters' AJAX CALL PASSING THE 'post_type' AND 'post_id' PARAMETERS #####
		jQuery.ajax({
			url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
			data: {
				'action' : 'post_type_filters',
				'post_type' : post_type,
				'post_id'   : post_id
			},
			beforeSend: function() {
				// AJAX container content set to loading message
				jQuery('#ajax_container_post_type_filters').html('<div class="ajax_loading">LOADING...</div>');
			},
			success:function(data) {
				// AJAX container content set to PHP ‘display_post_type_filters()’ function output
				jQuery('#ajax_container_post_type_filters').html(data);
			},
			error: function(errorThrown){
				// do nothing
			}
		});

		// ##### PERFORM 'post_type_sorting' AJAX CALL PASSING THE 'post_type' AND 'post_id' PARAMETERS #####
		jQuery.ajax({
			url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
			data: {
				'action' : 'post_type_sorting',
				'post_type' : post_type,
				'post_id'   : post_id
			},
			beforeSend: function() {
				// AJAX container content set to loading message
				jQuery('#ajax_container_post_type_sorting').html('<div class="ajax_loading">LOADING...</div>');
			},
			success:function(data) {
				// AJAX container content set to PHP ‘display_post_type_filters()’ function output
				jQuery('#ajax_container_post_type_sorting').html(data);
			},
			error: function(errorThrown){
				// do nothing
			}
		});

		// ##### PERFORM 'post_type_insert_fields' AJAX CALL PASSING THE 'post_type' AND 'post_id' PARAMETERS #####
		jQuery.ajax({
			url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
			data: {
				'action' : 'post_type_insert_fields',
				'post_type' : post_type,
				'post_id'   : post_id
			},
			beforeSend: function() {
				// AJAX container content set to loading message
				jQuery('#ajax_container_insert_fields').html('<div class="ajax_loading">LOADING...</div>');
			},
			success:function(data) {
				// AJAX container content set to PHP ‘display_post_type_filters()’ function output
				jQuery('#ajax_container_insert_fields').html(data);
			},
			error: function(errorThrown){
				// do nothing
			}
		});

		// ##### PERFORM 'slide_background_fields' AJAX CALL PASSING THE 'post_type' AND 'post_id' PARAMETERS #####
		jQuery.ajax({
			url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
			data: {
				'action' : 'slide_background_fields',
				'post_type' : post_type,
				'post_id'   : post_id
			},
			beforeSend: function() {
				// AJAX container content set to loading message
				jQuery('#ajax_container_slide_background').html('<div class="ajax_loading">LOADING...</div>');
			},
			success:function(data) {
				// AJAX container content set to PHP ‘display_post_type_filters()’ function output
				jQuery('#ajax_container_slide_background').html(data);
			},
			error: function(errorThrown){
				// do nothing
			}
		});
	}

	// ##### JQUERY-UI SPINNER - SLIDER BORDER WIDTH #####
	jQuery("#sap_num_slides").spinner({
		step: 1,
		min: 1,
		max: 100,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER - SLIDER BORDER WIDTH #####
	jQuery('#sap_num_slides').change(function() {
		var num_slides = document.getElementById('sap_num_slides').value;
		if (jQuery.isNumeric(num_slides)) {
			if ((num_slides >= 0) && (num_slides <= 100)) {
				// valid number
			} else {
				document.getElementById('sap_num_slides').value = '1'; // number out of range
			}
		} else {
			document.getElementById('sap_num_slides').value = '1'; // not a valid number
		}
	});

	// ##### JQUERY-UI - SLIDE DURATION SLIDER/INPUT #####
	var init_value = jQuery("#sap_slide_duration").val();
	jQuery("#jq_slider_duration").slider({
		range:"max",
		min:0,
		max:30,
		step:0.1,
		value:init_value,
		slide:function(event, ui) {
			jQuery("#sap_slide_duration").val(ui.value);
		}
	});
	jQuery("#sap_slide_duration").val(jQuery("#jq_slider_duration").slider("value"));

	// ##### JQUERY-UI - SLIDE BY SLIDER/INPUT #####
	var init_value = jQuery("#sap_slide_by").val();
	jQuery("#jq_slider_by").slider({
		range:"max",
		min:0,
		max:12,
		step:1,
		value:init_value,
		slide:function(event, ui) {
			jQuery("#sap_slide_by").val(ui.value);
		}
	});
	jQuery("#sap_slide_by").val(jQuery("#jq_slider_by").slider("value"));

	// ##### JQUERY-UI - SLIDE TRANSITION SLIDER/INPUT #####
	var init_value = jQuery("#sap_slide_transition").val();
	jQuery("#jq_slider_transition").slider({
		range:"max",
		min:0,
		max:3,
		step:0.1,
		value:init_value,
		slide:function(event, ui) {
			jQuery("#sap_slide_transition").val(ui.value);
		}
	});
	jQuery("#sap_slide_transition").val(jQuery("#jq_slider_transition").slider("value"));

	// ##### CLICK EVENT HANDLER FOR THE SHORTCODE 'Copy to Clipboard' BUTTON #####
	jQuery('#sap_shortcode_copy').click(function() {
		var shortcode = document.getElementById('sap_slider_shortcode').innerHTML;
		var aux = document.createElement("input"); // Create a "hidden" input
		aux.setAttribute("value", shortcode); // Assign it the value of the specified element
		document.body.appendChild(aux); // Append it to the body
		aux.select(); // Highlight its content
		document.execCommand("copy"); // Copy the highlighted text
		document.body.removeChild(aux); // Remove it from the body
		// DISPLAY 'Shortcode Copied' message 
		document.getElementById('sap_slider_shortcode').innerHTML = "Copied!";
		setTimeout(function(){ document.getElementById('sap_slider_shortcode').innerHTML = shortcode; }, 1000);
	});
	
	// ##### CHANGE EVENT HANDLER FOR CSS ID INPUT BOX #####
	jQuery('#sap_css_id').change(function() {
		var css_id = document.getElementById('sap_css_id').value;
		document.getElementById('css_note_value').innerHTML = '#' + css_id + ' .owl-item';
	});
	
	// ##### CLICK EVENT HANDLER FOR THE CSS SELECTOR 'Copy to Clipboard' BUTTON #####
	jQuery('#css_note_value').click(function() {
		var css_selector = document.getElementById('css_note_value').innerHTML;
		var aux = document.createElement("input"); // Create a "hidden" input
		aux.setAttribute("value", css_selector); // Assign it the value of the specified element
		document.body.appendChild(aux); // Append it to the body
		aux.select(); // Highlight its content
		document.execCommand("copy"); // Copy the highlighted text
		document.body.removeChild(aux); // Remove it from the body
		// DISPLAY 'Shortcode Copied' message
		document.getElementById('css_note_value').innerHTML = "Copied!";
		setTimeout(function(){ document.getElementById('css_note_value').innerHTML = css_selector; }, 1000);
	});

	// ##### SPECTRUM COLOR PICKER - SLIDE BACKGROUND COLOR #####
	if (document.getElementById('sap_slide_bg_color')) {
		var background_color = document.getElementById('sap_slide_bg_color').value;
		jQuery("#sap_slide_bg_color").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: background_color,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}

	// ##### SPECTRUM COLOR PICKER - SLIDER BACKGROUND COLOR #####
	if (document.getElementById('sap_background_color')) {
		var background_color = document.getElementById('sap_background_color').value;
		jQuery("#sap_background_color").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: background_color,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}
	
	// ##### JQUERY-UI SPINNER - SLIDER BORDER WIDTH #####
	jQuery("#sap_border_width").spinner({
		step: 1,
		min: 0,
		max: 10,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER - SLIDER BORDER WIDTH #####
	jQuery('#sap_border_width').change(function() {
		var border_width = document.getElementById('sap_border_width').value;
		if (jQuery.isNumeric(border_width)) {
			if ((border_width >= 0) && (border_width <= 10)) {
				// valid number
			} else {
				document.getElementById('sap_border_width').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_border_width').value = '0'; // not a valid number
		}
	});
	// ##### SPECTRUM COLOR PICKER - SLIDER BORDER COLOR #####
	if (document.getElementById('sap_border_color')) {
		var border_color = document.getElementById('sap_border_color').value;
		jQuery("#sap_border_color").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: border_color,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}
	
	// ##### JQUERY-UI SPINNER - SLIDER BORDER RADIUS #####
	jQuery("#sap_border_radius").spinner({
		step: 1,
		min: 0,
		max: 20,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER - SLIDER BORDER RADIUS #####
	jQuery('#sap_border_radius').change(function() {
		var border_radius = document.getElementById('sap_border_radius').value;
		if (jQuery.isNumeric(border_radius)) {
			if ((border_radius >= 0) && (border_radius <= 20)) {
				// valid number
			} else {
				document.getElementById('sap_border_radius').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_border_radius').value = '0'; // not a valid number
		}
	});
	
	// ##### JQUERY-UI SPINNERS - WRAPPER PADDING (TOP, RIGHT, BOTTOM, LEFT) #####
	jQuery("#sap_wrapper_padd_top").spinner({ step: 1, min: 0, max: 99, numberFormat: "n" });
	jQuery("#sap_wrapper_padd_right").spinner({ step: 1, min: 0, max: 99, numberFormat: "n" });
	jQuery("#sap_wrapper_padd_bottom").spinner({ step: 1, min: 0, max: 99, numberFormat: "n" });
	jQuery("#sap_wrapper_padd_left").spinner({ step: 1, min: 0, max: 99, numberFormat: "n" });
	// ##### CHANGE EVENT HANDLER - WRAPPER PADDING TOP #####
	jQuery('#sap_wrapper_padd_top').change(function() {
		var wrapper_padding = document.getElementById('sap_wrapper_padd_top').value;
		if (jQuery.isNumeric(wrapper_padding)) {
			if ((wrapper_padding >= 0) && (wrapper_padding <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_wrapper_padd_top').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_wrapper_padd_top').value = '0'; // not a valid number
		}
	});
	// ##### CHANGE EVENT HANDLER - WRAPPER PADDING RIGHT #####
	jQuery('#sap_wrapper_padd_right').change(function() {
		var wrapper_padding = document.getElementById('sap_wrapper_padd_right').value;
		if (jQuery.isNumeric(wrapper_padding)) {
			if ((wrapper_padding >= 0) && (wrapper_padding <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_wrapper_padd_right').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_wrapper_padd_right').value = '0'; // not a valid number
		}
	});
	// ##### CHANGE EVENT HANDLER - WRAPPER PADDING BOTTOM #####
	jQuery('#sap_wrapper_padd_bottom').change(function() {
		var wrapper_padding = document.getElementById('sap_wrapper_padd_bottom').value;
		if (jQuery.isNumeric(wrapper_padding)) {
			if ((wrapper_padding >= 0) && (wrapper_padding <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_wrapper_padd_bottom').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_wrapper_padd_bottom').value = '0'; // not a valid number
		}
	});
	// ##### CHANGE EVENT HANDLER - WRAPPER PADDING LEFT #####
	jQuery('#sap_wrapper_padd_left').change(function() {
		var wrapper_padding = document.getElementById('sap_wrapper_padd_left').value;
		if (jQuery.isNumeric(wrapper_padding)) {
			if ((wrapper_padding >= 0) && (wrapper_padding <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_wrapper_padd_left').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_wrapper_padd_left').value = '0'; // not a valid number
		}
	});

	// ##### CHANGE EVENT FOR SLIDE MINIMUM HEIGHT RADIO BUTTONS #####
	jQuery('.sap_slide_min_height_type').change(function() {
		var slide_min_height_type = this.value;
		if (slide_min_height_type == "percent") {
			document.getElementById('sap_slide_min_height').value = document.getElementById('sap_slide_min_height_hidden').value;
			document.getElementById('sap_slide_min_height_wrapper').style.display = 'block';
			document.getElementById('mh_suffix').innerHTML = '%';
		} else if (slide_min_height_type == 'px') {
			document.getElementById('sap_slide_min_height').value = document.getElementById('sap_slide_min_height_hidden').value;
			document.getElementById('sap_slide_min_height_wrapper').style.display = 'block';
			document.getElementById('mh_suffix').innerHTML = 'px';
		} else if (slide_min_height_type == '43') {
			document.getElementById('sap_slide_min_height').value = 'aspect43';
			document.getElementById('sap_slide_min_height_wrapper').style.display = 'none';
		} else if (slide_min_height_type == '169') {
			document.getElementById('sap_slide_min_height').value = 'aspect169';
			document.getElementById('sap_slide_min_height_wrapper').style.display = 'none';
		}
	});

	// ##### JQUERY-UI SPINNER FOR SLIDE MINIMUM HEIGHT (PIXELS) #####
	jQuery("#sap_slide_min_height").spinner({
		step: 1,
		min: 0,
		max: 999,
		numberFormat: "n"
	});
	jQuery('#sap_slide_min_height_wrapper .ui-spinner-button').click(function() {
		jQuery(this).siblings('input').change();
	});
	// ##### CHANGE EVENT HANDLER FOR SLIDE MINIMUM HEIGHT (PERCENT/PIXELS) #####
	jQuery('#sap_slide_min_height').change(function() {
		var slide_min_height = document.getElementById('sap_slide_min_height').value;
		var min_height_type = jQuery('input[name=sap_slide_min_height_type]:checked').val();
		if (jQuery.isNumeric(slide_min_height)) {
			if ((slide_min_height >= 0) && (slide_min_height <= 999)) {
				// valid number
				if (min_height_type == 'percent') {
					document.getElementById('sap_slide_min_height_hidden').value = slide_min_height;
				} else {
					document.getElementById('sap_slide_min_height_hidden').value = slide_min_height + 'px';
				}
			} else {
				document.getElementById('sap_slide_min_height').value = '0'; // number out of range
				document.getElementById('sap_slide_min_height_hidden').value = '0';
			}
		} else {
			document.getElementById('sap_slide_min_height').value = '0'; // not a valid number
			document.getElementById('sap_slide_min_height_hidden').value = '0';
		}
	});

	// ##### JQUERY-UI SPINNER FOR SLIDE PADDING TOP/BOTTOM #####
	jQuery("#sap_slide_padding_tb").spinner({
		step: 0.1,
		min: 0,
		max: 30,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR SLIDE PADDING TOP/BOTTOM #####
	jQuery('#sap_slide_padding_tb').change(function() {
		var slide_padding_tb = document.getElementById('sap_slide_padding_tb').value;
		if (jQuery.isNumeric(slide_padding_tb)) {
			if ((slide_padding_tb >= 0) && (slide_padding_tb <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_slide_padding_tb').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_slide_padding_tb').value = '0'; // not a valid number
		}
	});

	// ##### JQUERY-UI SPINNER FOR SLIDE PADDING LEFT/RIGHT #####
	jQuery("#sap_slide_padding_lr").spinner({
		step: 0.1,
		min: 0,
		max: 30,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR SLIDE PADDING LEFT/RIGHT #####
	jQuery('#sap_slide_padding_lr').change(function() {
		var slide_padding_lr = document.getElementById('sap_slide_padding_lr').value;
		if (jQuery.isNumeric(slide_padding_lr)) {
			if ((slide_padding_lr >= 0) && (slide_padding_lr <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_slide_padding_lr').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_slide_padding_lr').value = '0'; // not a valid number
		}
	});
	
	// ##### JQUERY-UI SPINNER FOR SLIDE MARGIN LEFT/RIGHT #####
	jQuery("#sap_slide_margin_lr").spinner({
		step: 0.1,
		min: 0,
		max: 20,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR SLIDE MARGIN LEFT/RIGHT #####
	jQuery('#sap_slide_margin_lr').change(function() {
		var slide_margin_lr = document.getElementById('sap_slide_margin_lr').value;
		if (jQuery.isNumeric(slide_margin_lr)) {
			if ((slide_margin_lr >= 0) && (slide_margin_lr <= 30)) {
				// valid number
			} else {
				document.getElementById('sap_slide_margin_lr').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_slide_margin_lr').value = '0'; // not a valid number
		}
	});

	// ##### JQUERY-UI SPINNER FOR NAVIGATION ARROWS WRAPPER WIDTH #####
	jQuery("#sap_arrows_wrapper_width").spinner({
		step: 1,
		min: 10,
		max: 200,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR NAVIGATION ARROWS WRAPPER WIDTH #####
	jQuery('#sap_arrows_wrapper_width').change(function() {
		var arrows_wrapper_width = document.getElementById('sap_arrows_wrapper_width').value;
		if (jQuery.isNumeric(arrows_wrapper_width)) {
			if ((arrows_wrapper_width >= 10) && (arrows_wrapper_width <= 200)) {
				// valid number
			} else {
				document.getElementById('sap_arrows_wrapper_width').value = '30'; // number out of range
			}
		} else {
			document.getElementById('sap_arrows_wrapper_width').value = '30'; // not a valid number
		}
	});

	// ##### JQUERY-UI SPINNER FOR NAVIGATION ARROWS WRAPPER HEIGHT #####
	jQuery("#sap_arrows_wrapper_height").spinner({
		step: 1,
		min: 10,
		max: 200,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR NAVIGATION ARROWS WRAPPER HEIGHT #####
	jQuery('#sap_arrows_wrapper_height').change(function() {
		var arrows_wrapper_height = document.getElementById('sap_arrows_wrapper_height').value;
		if (jQuery.isNumeric(arrows_wrapper_height)) {
			if ((arrows_wrapper_height >= 10) && (arrows_wrapper_height <= 200)) {
				// valid number
			} else {
				document.getElementById('sap_arrows_wrapper_height').value = '40'; // number out of range
			}
		} else {
			document.getElementById('sap_arrows_wrapper_height').value = '40'; // not a valid number
		}
	});

	// ##### JQUERY-UI SPINNER FOR NAVIGATION ARROWS WRAPPER BORDER RADIUS #####
	jQuery("#sap_arrows_wrapper_bordradius").spinner({
		step: 1,
		min: 0,
		max: 100,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR NAVIGATION ARROWS WRAPPER HEIGHT #####
	jQuery('#sap_arrows_wrapper_bordradius').change(function() {
		var arrows_wrapper_bordradius = document.getElementById('sap_arrows_wrapper_bordradius').value;
		if (jQuery.isNumeric(arrows_wrapper_bordradius)) {
			if ((arrows_wrapper_bordradius >= 10) && (arrows_wrapper_bordradius <= 100)) {
				// valid number
			} else {
				document.getElementById('sap_arrows_wrapper_bordradius').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_arrows_wrapper_bordradius').value = '0'; // not a valid number
		}
	});

	// ##### SPECTRUM COLOR PICKER - NAVIGATION ARROWS WRAPPER BACKGROUND COLOR #####
	if (document.getElementById('sap_arrows_wrapper_bgcol')) {
		var arrows_wrapper_bgcol = document.getElementById('sap_arrows_wrapper_bgcol').value;
		jQuery("#sap_arrows_wrapper_bgcol").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: arrows_wrapper_bgcol,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}

	// ##### SPECTRUM COLOR PICKER - NAVIGATION ARROWS HOVER BACKGROUND COLOR #####
	if (document.getElementById('sap_arrows_hover_bgcol')) {
		var arrows_hover_bgcol = document.getElementById('sap_arrows_hover_bgcol').value;
		jQuery("#sap_arrows_hover_bgcol").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: arrows_hover_bgcol,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}

	// ##### CHANGE EVENT HANDLER - ARROW IMAGES (NAVIGATION ARROWS) #####
	jQuery("#sap_arrow_images").change(function () {
		var arrow_images = this.value;
		var siteurl = wordpress_urls.siteurl;
		if (arrow_images == 'white') {
			document.getElementById('sap_custom_nav_arrows').style.maxHeight = '0px';
			jQuery('#sap_prev_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/white_icon_prev.png");
			jQuery('#sap_next_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/white_icon_next.png");
			jQuery('#sap_arrows_wrapper_width').val('30');
			jQuery('#sap_arrows_wrapper_height').val('40');
			jQuery('#sap_arrows_wrapper_bgcol').val('rgba(0,0,0,0.3)');
			jQuery('#sap_arrows_wrapper_bgcol').spectrum('set', 'rgba(0,0,0,0.3)');
			jQuery('#sap_arrows_hover_bgcol').val('rgba(0,0,0,0.8)');
			jQuery('#sap_arrows_hover_bgcol').spectrum('set', 'rgba(0,0,0,0.8)');
		} else if (arrow_images == 'grey') {
			document.getElementById('sap_custom_nav_arrows').style.maxHeight = '0px';
			jQuery('#sap_prev_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/grey_icon_prev.png");
			jQuery('#sap_next_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/grey_icon_next.png");
			jQuery('#sap_arrows_wrapper_width').val('30');
			jQuery('#sap_arrows_wrapper_height').val('40');
			jQuery('#sap_arrows_wrapper_bgcol').val('rgba(0,0,0,0.0)');
			jQuery('#sap_arrows_wrapper_bgcol').spectrum('set', 'rgba(0,0,0,0.0)');
			jQuery('#sap_arrows_hover_bgcol').val('rgba(0,0,0,0.05)');
			jQuery('#sap_arrows_hover_bgcol').spectrum('set', 'rgba(0,0,0,0.05)');
		} else if (arrow_images == 'black') {
			document.getElementById('sap_custom_nav_arrows').style.maxHeight = '0px';
			jQuery('#sap_prev_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/black_icon_prev.png");
			jQuery('#sap_next_arrow_url').val(siteurl + "/wp-content/plugins/slide-any-post/images/black_icon_next.png");
			jQuery('#sap_arrows_wrapper_width').val('30');
			jQuery('#sap_arrows_wrapper_height').val('40');
			jQuery('#sap_arrows_wrapper_bgcol').val('rgba(255,255,255,0.3)');
			jQuery('#sap_arrows_wrapper_bgcol').spectrum('set', 'rgba(255,255,255,0.3)');
			jQuery('#sap_arrows_hover_bgcol').val('rgba(255,255,255,0.8)');
			jQuery('#sap_arrows_hover_bgcol').spectrum('set', 'rgba(255,255,255,0.8)');
		} else {
			document.getElementById('sap_custom_nav_arrows').style.maxHeight = '400px';
		}
	});

	// ##### CHENGE EVENT HANDLER - 'USE A POST LINK' CHECKBOX #####
	jQuery('#sap_post_link_yn').change(function() {
		if (this.checked) {
			document.getElementById('sap_post_link_wrapper').style.maxHeight = '400px';
		} else {
			document.getElementById('sap_post_link_wrapper').style.maxHeight = '0px';
		}
	});

	// ##### CHENGE EVENT HANDLER - 'CUSTOMISE ICON' CHECKBOX #####
	jQuery('#sap_custom_link_yn').change(function() {
		if (this.checked) {
			document.getElementById('sap_custom_link_wrapper').style.maxHeight = '300px';
		} else {
			document.getElementById('sap_custom_link_wrapper').style.maxHeight = '0px';
		}
	});

	// ##### JQUERY-UI SPINNER FOR POST LINK ICON WRAPPER WIDTH #####
	jQuery("#sap_link_icon_width").spinner({
		step: 1,
		min: 10,
		max: 200,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR POST LINK ICON WRAPPER WIDTH #####
	jQuery('#sap_link_icon_width').change(function() {
		var link_icon_width = document.getElementById('sap_link_icon_width').value;
		if (jQuery.isNumeric(link_icon_width)) {
			if ((link_icon_width >= 10) && (link_icon_width <= 200)) {
				// valid number
			} else {
				document.getElementById('sap_link_icon_width').value = '40'; // number out of range
			}
		} else {
			document.getElementById('sap_link_icon_width').value = '40'; // not a valid number
		}
	});

	// ##### JQUERY-UI SPINNER FOR POST LINK ICON WRAPPER HEIGHT #####
	jQuery("#sap_link_icon_height").spinner({
		step: 1,
		min: 10,
		max: 200,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR POST LINK ICON WRAPPER HEIGHT #####
	jQuery('#sap_link_icon_height').change(function() {
		var link_icon_height = document.getElementById('sap_link_icon_height').value;
		if (jQuery.isNumeric(link_icon_height)) {
			if ((link_icon_height >= 10) && (link_icon_height <= 200)) {
				// valid number
			} else {
				document.getElementById('sap_link_icon_height').value = '40'; // number out of range
			}
		} else {
			document.getElementById('sap_link_icon_height').value = '40'; // not a valid number
		}
	});

	// ##### CHANGE EVENT HANDLER FOR POST LINK LOCATION DROPDOWN #####
	jQuery("#sap_slide_link_location").change(function () {
		var slide_link_location = this.value;
		if (slide_link_location == 'Entire Slide') {
			document.getElementById('sap_link_button_only_wrapper').style.maxHeight = '0px';
		} else {
			document.getElementById('sap_link_button_only_wrapper').style.maxHeight = '200px';
		}
	});

	// ##### JQUERY-UI SPINNER FOR POST LINK WRAPPER BORDER RADIUS #####
	jQuery("#sap_link_icon_bordradius").spinner({
		step: 1,
		min: 0,
		max: 100,
		numberFormat: "n"
	});
	// ##### CHANGE EVENT HANDLER FOR NAVIGATION ARROWS WRAPPER HEIGHT #####
	jQuery('#sap_link_icon_bordradius').change(function() {
		var link_icon_bordradius = document.getElementById('sap_link_icon_bordradius').value;
		if (jQuery.isNumeric(link_icon_bordradius)) {
			if ((link_icon_bordradius >= 10) && (link_icon_bordradius <= 100)) {
				// valid number
			} else {
				document.getElementById('sap_link_icon_bordradius').value = '0'; // number out of range
			}
		} else {
			document.getElementById('sap_link_icon_bordradius').value = '0'; // not a valid number
		}
	});

	// ##### SPECTRUM COLOR PICKER - POST LINK WRAPPER BACKGROUND COLOR #####
	if (document.getElementById('sap_link_wrapper_bgcol')) {
		var link_wrapper_bgcol = document.getElementById('sap_link_wrapper_bgcol').value;
		jQuery("#sap_link_wrapper_bgcol").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: link_wrapper_bgcol,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}

	// ##### SPECTRUM COLOR PICKER - POST LINK HOVER BACKGROUND COLOR #####
	if (document.getElementById('sap_link_hover_bgcol')) {
		var link_hover_bgcol = document.getElementById('sap_link_hover_bgcol').value;
		jQuery("#sap_link_hover_bgcol").spectrum({
			showPaletteOnly: true,
			togglePaletteOnly: true,
			togglePaletteMoreText: 'more',
			togglePaletteLessText: 'less',
			showInput: true,
			allowEmpty:true,
			preferredFormat: "rgb",
			showAlpha: true,
			color: link_hover_bgcol,
			palette: [
				["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
				["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
				["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
				["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
				["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
				["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
				["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
				["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			]
		});
	}
})



// ##### FUNCTION THAT IS CALLED WHEN A FILTER OPERATION DROP-DOWN IS CHANGED #####
function check_to_hide_filter_value(filter_oper_id, filter_value_id, lookup_button) {
	var oper = document.getElementById(filter_oper_id);
	var oper_val = oper.options[oper.selectedIndex].text;
	if (lookup_button != '') {
		document.getElementById(lookup_button).style.visibility = 'visible';
	}
	if ((oper_val == 'EXISTS') || (oper_val == 'NOT EXISTS')) {
		document.getElementById(filter_value_id).style.visibility = 'hidden';
		if (lookup_button != '') {
			document.getElementById(lookup_button).style.visibility = 'hidden';
		}
	} else if ((oper_val == 'IN') || (oper_val == 'NOT IN')) {
		document.getElementById(filter_value_id).style.visibility = 'visible';
		jQuery("#"+filter_value_id).attr("placeholder", "use '|' to separate values");
	} else if ((oper_val == 'BETWEEN') || (oper_val == 'NOT BETWEEN')) {
		document.getElementById(filter_value_id).style.visibility = 'visible';
		jQuery("#"+filter_value_id).attr("placeholder", "2 values separated by '|'");
	} else if ((oper_val == 'LIKE') || (oper_val == 'NOT LIKE')) {
		document.getElementById(filter_value_id).style.visibility = 'visible';
		jQuery("#"+filter_value_id).attr("placeholder", "enter search text");
	} else {
		document.getElementById(filter_value_id).style.visibility = 'visible';
		jQuery("#"+filter_value_id).attr("placeholder", "enter single value");
	}
}



// ##### FUNCTION THAT DISPLAYS A POPUP TO PERFORM A SPECIFIED TAXONOMY SLUG LOOKUP #####
// This function displays a list of all taxonomy slugs for the specified taxonomy parameter within
// a modal popup. The user can select a slug, which is then copied/pasted into the specified
// input field id.
function taxonomy_slug_lookup(tax_slug, input_id) {
	// DISPLAY THE HTML POPUP CONTAINER
	document.getElementById("tax_slug_lookup").style.display = "block";
	jQuery('body').css('overflow', 'hidden');

	// ##### PERFORM 'tax_slug_lookup' AJAX CALL PASSING THE 'tax_slug' AND 'input_id' PARAMETERS #####
	jQuery.ajax({
		url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
		data: {
			'action' : 'tax_slug_lookup',
			'tax_slug' : tax_slug,
			'input_id' : input_id
		},
		beforeSend: function() {
			// AJAX container content set to loading message
			jQuery('#ajax_container_tax_slug_lookup').html('<div class="popup_ajax_loading">LOADING...</div>');
		},
		success:function(data) {
			// AJAX container content set to PHP ‘display_post_type_filters()’ function output
			jQuery('#ajax_container_tax_slug_lookup').html(data);
		},
		error: function(errorThrown){
			// do nothing
		}
	});
}

// ### THIS FUNCTION APPENDS A SPECIFIED SLUG VALUE TO A TEXT INPUT FIELD ###
// This function is called from within the taxonomy slug lookup modal popup.
function append_tax_slug_to_text_input(slug, input_id) {
	// HIDE THE HTML POPUP CONTAINER
	document.getElementById("tax_slug_lookup").style.display = "none";
	jQuery('body').css('overflow', 'auto');

	// APPEND SLUG VALUE TO TEXT INPUT FIELD
	if (jQuery("#"+input_id).val() == '') {
		jQuery("#"+input_id).val(slug);
	} else {
		jQuery("#"+input_id).val(jQuery("#"+input_id).val() + "|" + slug);
	}

	// HIGHLIGHT TEXT INPUT FIELD FOR A SHORT DURATION
	document.getElementById(input_id).style.backgroundColor = "#ffffcc";
	setTimeout(function() {
		document.getElementById(input_id).style.backgroundColor = "#ffffff";
	}, 1000);
}



// ##### FUNCTION THAT DISPLAYS A POPUP TO PERFORM POST ID LOOKUP FOR A SPECIFIED POST TYPE #####
// This function displays a list of all the post titles (and corresponding post IDs) for the specified
// post type parameter within a modal popup. The user can select a post title/ID line, and the
// corresponding post ID is then copied/pasted into 'Post IDs' input field.
function post_id_lookup(post_type) {
	// DISPLAY THE HTML POPUP CONTAINER
	document.getElementById("post_id_lookup").style.display = "block";
	jQuery('body').css('overflow', 'hidden');

	// ##### PERFORM 'post_id_lookup' AJAX CALL PASSING THE 'post_type' PARAMETER #####
	jQuery.ajax({
		url: "../wp-admin/admin-ajax.php", // must be correct path to 'admin-ajax.php'
		data: {
			'action' : 'post_id_lookup',
			'post_type' : post_type
		},
		beforeSend: function() {
			// AJAX container content set to loading message
			jQuery('#ajax_container_post_id_lookup').html('<div class="popup_ajax_loading">LOADING...</div>');
		},
		success:function(data) {
			// AJAX container content set to PHP ‘display_post_type_filters()’ function output
			jQuery('#ajax_container_post_id_lookup').html(data);
		},
		error: function(errorThrown){
			// do nothing
		}
	});
}

// ### THIS FUNCTION APPENDS A SPECIFIED POST ID TO THE POST IDS FILTER TEXT INPUT FIELD ###
// This function is called from within the post id lookup modal popup.
function append_post_id_to_text_input(post_id) {
	// HIDE THE HTML POPUP CONTAINER
	document.getElementById("post_id_lookup").style.display = "none";
	jQuery('body').css('overflow', 'auto');

	// APPEND SLUG VALUE TO TEXT INPUT FIELD
	if (jQuery("#sap_post_id_value").val() == '') {
		jQuery("#sap_post_id_value").val(post_id);
	} else {
		jQuery("#sap_post_id_value").val(jQuery("#sap_post_id_value").val() + "|" + post_id);
	}

	// HIGHLIGHT TEXT INPUT FIELD FOR A SHORT DURATION
	document.getElementById('sap_post_id_value').style.backgroundColor = "#ffffcc";
	setTimeout(function() {
		document.getElementById('sap_post_id_value').style.backgroundColor = "#ffffff";
	}, 1000);
}



// ###### THIS FUNCTION IS CALLED WHEN THE POST TYPE 'SORT ORDER' DROPDOWN IS CHANGED ######
function sort_order_change() {
	var sort_order = jQuery('#sap_sort_order').val();
	if (sort_order.startsWith("meta~")) {
		document.getElementById('sap_sort_type_wrapper').style.display = 'inline-block';
	} else {
		document.getElementById('sap_sort_type_wrapper').style.display = 'none';
	}
}



// ##### FUNCTION THAT INSERTS TEXT SPECIFIED INTO THE WORDPRESS VISUAL/TEXT EDITOR (AT CURRENT CURSOR POSITION) #####
function addTextIntoEditor(insert_text){
	if (insert_text == "{FEATURED_IMAGE}") {
		// The 'Featured Image' button was clicked
		var image_size = jQuery('#sap_insert_image_size').val();
		insert_text = "{FEATURED_IMAGE~" + image_size + "}";
	}
	if (insert_text == "{FEATURED_IMAGE_LINK}") {
		// The 'Featured Image Link' button was clicked
		var image_size = jQuery('#sap_insert_image_size').val();
		insert_text = "{FEATURED_IMAGE_LINK~" + image_size + "}";
	}
	if (insert_text.startsWith("{TAX~")) {
		// Taxonomy Field insert - the insert text starts with 'TAX~'
		var tax_ins_option = jQuery('#sap_tax_ins_option').val();
		insert_text = insert_text.substring(0, insert_text.length - 1);
		insert_text = insert_text + "~" + tax_ins_option + "}";
	}
	if (insert_text.startsWith("{META~")) {
		// Meta Data Field insert - the insert text starts with 'META~'
		var meta_ins_option = jQuery('#sap_meta_ins_option').val();
		insert_text = insert_text.substring(0, insert_text.length - 1);
		insert_text = insert_text + "~" + meta_ins_option + "}";
	}
	if (insert_text.startsWith("{META_IMAGE~")) {
		// Meta Image Field insert - the insert text starts with 'META_IMAGE~'
		var image_size = jQuery('#sap_insert_image_size').val();
		insert_text = insert_text.substring(0, insert_text.length - 1);
		insert_text += "~" + image_size + "}";
	}

	if (!tinymce.activeEditor || tinymce.activeEditor.isHidden()) {
		// EDITOR IS CURRENTLY IN 'TEXT' EDITOR MODE
		var textarea = jQuery('#wp-content-editor-container .wp-editor-area');
		var caret_pos = textarea[0].selectionStart;
		var textarea_text = textarea.val();
		textarea.val(textarea_text.substring(0, caret_pos) + insert_text + textarea_text.substring(caret_pos) );
	} else {
		// EDITOR IS CURRENTLY IN 'VISUAL' EDITOR MODE
		tinymce.activeEditor.execCommand('mceInsertContent', false, insert_text);
	}
}



// ###### THIS FUNCTION IS CALLED WHEN THE POST TYPE 'USE FEATURED IMAGE AS BACKGROUND' CHECKBOX IS CHANGED ######
function bg_use_featured_changed() {
	if (jQuery('#sap_slide_bg_use_featured').is(":checked")) {
		var checkbox_val = '1';
	} else {
		var checkbox_val = '0';
	}
	// HIDE/SHOW THE 'use_featured_bg_container' ELEMENT
	if (checkbox_val == '1') {
		document.getElementById('use_featured_bg_container').style.display = 'block';
	} else {
		document.getElementById('use_featured_bg_container').style.display = 'none';
	}
}
