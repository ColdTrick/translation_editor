<?php 
	global $CONFIG;
	gatekeeper();
	
	if(isadminloggedin()){
		set_context("admin");
	}
	set_page_owner(get_loggedin_userid());
	
	// Get inputs
	$current_language = get_input("current_language");
	$translations = get_installed_translations();
	
	if(!(array_key_exists($current_language,$translations ))){
		forward($CONFIG->wwwroot . "pg/translation_editor");
	}
	
	$plugin = get_input("plugin");
	
	$languages = array_keys($CONFIG->translations);
	
	$disabled_languages = get_plugin_setting(TRANSLATION_EDITOR_DISABLED_LANGUAGE, "translation_editor");
	if(!empty($disabled_languages)){
		$disabled_languages = explode(",", $disabled_languages);
	} else {
		$disabled_languages = array();
	}
	
	if(!empty($CONFIG->language)){
		$site_language = $CONFIG->language;
	} else {
		$site_language = "en";
	}
	
	// Build elements
	$title_text = elgg_echo("translation_editor:menu:title");
	$title = elgg_view_title($title_text);
	
	$body .= elgg_view("translation_editor/language_selector", array("current_language" => $current_language, "plugin" => $plugin, "languages" => $languages, "disabled_languages" => $disabled_languages, "site_language" => $site_language));
	
	
	if(empty($plugin)){
		$plugins = translation_editor_get_plugins($current_language);
		
		$body .= elgg_view("translation_editor/search", array("current_language" => $current_language, "query" => get_input("q")));
		$body .= elgg_view("translation_editor/plugin_list", array("plugins" => $plugins, "current_language" => $current_language));
	} else {
		$translation = translation_editor_get_plugin($current_language, $plugin);
		if($plugin == "custom_keys" && isadminloggedin()){
			$body .= elgg_view("translation_editor/add_custom_key");
		}
		$body .= elgg_view("translation_editor/plugin_edit", array("plugin" => $plugin, "current_language" => $current_language, "translation" => $translation));
	}
	
	// Build page
	$page_data = $title . $body;
	
	page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));
?>