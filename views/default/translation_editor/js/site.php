<?php

?>
//<script>
elgg.provide("elgg.translation_editor");

elgg.translation_editor.disable_language = function() {
	
	var lan = new Array();
	$('#translation_editor_language_table input[name="disabled_languages[]"]:checked').each(function(index, elm){
		lan.push($(this).val());
	});

	elgg.action("translation_editor/disable_languages", {
		data: {
			disabled_languages: lan
		}
	});
}

elgg.translation_editor.toggle_view_mode = function(mode) {
	$("#translation_editor_plugin_toggle a").removeClass("view_mode_active");
	$("#view_mode_" + mode).addClass("view_mode_active");
	
	if (mode == "all") {
		$("#translation_editor_plugin_form tr").show();
	} else {
		$("#translation_editor_plugin_form tr").hide();
		$("#translation_editor_plugin_form tr[rel='" + mode + "']").show();
		$("#translation_editor_plugin_form tr:first").show();
	}
}

elgg.translation_editor.save = function() {
	var url = $('#translation_editor_plugin_form').attr("action");
	var formData = $('#translation_editor_plugin_form').serialize();

	elgg.action(url, {
		data: formData
	});
}

elgg.translation_editor.save_search = function() {
	var url = $('#translation_editor_search_result_form').attr("action");
	var formData = $('#translation_editor_search_result_form').serialize();

	elgg.action(url, {
		data: formData
	});
}

elgg.translation_editor.init = function() {
	// normal plugin edit form
	$('#translation_editor_plugin_form textarea').live("change", function() {
		elgg.translation_editor.save();
	});

	// search result edit form
	$('#translation_editor_search_result_form textarea').live("change", function() {
		elgg.translation_editor.save_search();
	});
	
	// search form
	$('#translation_editor_search_form input[name="translation_editor_search"]').live("focus", function() {
		if ($(this).val() == elgg.echo("translation_editor:forms:search:default")) {
			$(this).val("");
		}
	}).live("blur", function() {
		if ($(this).val() == "") {
			$(this).val(elgg.echo("translation_editor:forms:search:default"));
		}
	});
}

elgg.register_hook_handler("init", "system", elgg.translation_editor.init);
