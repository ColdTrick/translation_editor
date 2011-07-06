<?php 

?>

function translation_editor_disable_language(){
	var url = $('#translation_editor_language_table input[name="action"]').val();
	var ts = $('#translation_editor_language_table input[name="__elgg_ts"]').val();
	var token = $('#translation_editor_language_table input[name="__elgg_token"]').val();

	var lan = new Array();
	$('#translation_editor_language_table input[name="disabled_languages[]"]:checked').each(function(index, elm){
		lan.push($(this).val());
	});

	var values = {
		'disabled_languages[]': lan,
		__elgg_ts: ts,
		__elgg_token: token
	};

	$.post(url, values, function(data){
		// nothing yet
	});
}

function toggleViewMode(mode){
	$("#translation_editor_plugin_toggle a").removeClass("view_mode_active");
	$("#view_mode_" + mode).addClass("view_mode_active");
	
	if(mode == "all"){
		$("#translation_editor_plugin_form tr").css("display", "block");
	} else {
		$("#translation_editor_plugin_form tr").css("display", "none");
		$("#translation_editor_plugin_form .translation_editor_" + mode + "_translation").css("display", "block");
	}
}

function translationEditorJQuerySave(){
	var url = $('#translation_editor_plugin_form').attr("action") + "?jquery=yes";
	var formData = $('#translation_editor_plugin_form').serialize();

	$.post(url, formData, function(data){
		if(data != null){
			$('#translation_editor_plugin_form input[name="__elgg_ts"]').val(data.ts);
			$('#translation_editor_plugin_form input[name="__elgg_token"]').val(data.token);
		}
	}, "json");
}

function translationEditorJQuerySearchSave(){
	var url = $('#translation_editor_search_result_form').attr("action") + "?jquery=yes";
	var formData = $('#translation_editor_search_result_form').serialize();

	$.post(url, formData, function(data){
		if(data != null){
			$('#translation_editor_search_result_form input[name="__elgg_ts"]').val(data.ts);
			$('#translation_editor_search_result_form input[name="__elgg_token"]').val(data.token);
		}
	}, "json");
}