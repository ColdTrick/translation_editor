<?php 

	global $CONFIG;
	
	gatekeeper();
	
	// set context and page owner
	set_context("admin");
	set_page_owner(get_loggedin_userid());
	
	// get inputs
	$q = get_input("translation_editor_search");
	$language = get_input("language", "en");
	
	$found = translation_editor_search_translation($q, $language);
	
	$trans = get_installed_translations();
	if(!array_key_exists($language, $trans)){
		forward($CONFIG->wwwroot . "pg/translation_editor");
	}
	
	// build page elements
	$title_text = elgg_echo("translation_editor:search");
	$title = elgg_view_title($title_text);
	
	$body .= elgg_view("translation_editor/search", array("current_language" => $language, "query" => $q, "in_search" => true));

	$body .= elgg_view("translation_editor/search_results", array("results" => $found, "current_language" => $language));
	
	// build page
	$page_data = $title . $body;
	
	// draw page
	page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));

?>