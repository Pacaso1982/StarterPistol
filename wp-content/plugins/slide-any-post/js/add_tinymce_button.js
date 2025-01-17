/*
###########################################################################
### SLIDE ANYTHING PLUGIN - JAVASCRIPT/JQUERY FOR TINYMCE EDITOR BUTTON ###
###########################################################################
*/
(function() {
	tinymce.PluginManager.add('tinymce_sap_button', function(editor, url) {
		// get a list of shortcode values from previously defined array 'sa_title_arr' and 'sa_id_arr'
		var shortcode_values = [];
		jQuery.each(sap_title_arr, function(i) {
			shortcode_values.push({text: sap_title_arr[i], value:sap_id_arr[i]});
		});

		// add TinyMCE editor button, which opens a popup containing a dropdown list of slider titles
		// when a slider title is selected the corresponing SA shortcode is generated and displayed within the editor content
		editor.addButton('tinymce_sap_button', {
			title: 'Slide Any Post Sliders',
			type: 'menubutton',
			icon: 'icon dashicons-images-alt2',
			image: '../wp-content/plugins/slide-any-post/images/wp_menu_icon.png',
			tooltip: "Insert a 'Slide Any Post' slider shortcode",
			onClick: function() {
				editor.windowManager.open({
					title: "Insert 'Slide Any Post' shortcode",
					body: [{
						type: 'listbox',
						name: 'sap_id',
						label: 'Select Slider',
						values: shortcode_values
					}],
					onsubmit: function(e) {
						editor.insertContent("[slide-any-post id='" + e.data.sap_id + "']");
					}
				});
			}
		});
	});
})();
