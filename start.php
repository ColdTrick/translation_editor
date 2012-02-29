<?php 

	define("TRANSLATION_EDITOR_DISABLED_LANGUAGE", "disabled_languages");

	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	
	function translation_editor_init(){
		global $CONFIG;
		
		elgg_extend_view("css", "translation_editor/css");
		elgg_extend_view("js/initialise_elgg", "translation_editor/js");

		// Extend context menu with admin links
		if (isadminloggedin()){
   			 elgg_extend_view('profile/menu/adminlinks','translation_editor/adminlinks');
		}
		
		if(translation_editor_is_translation_editor(get_loggedin_userid())){
			register_page_handler('translation_editor', 'translation_editor_page_handler');
			
			if(get_plugin_setting("show_in_tools", "translation_editor") != "no"){
				add_menu(elgg_echo("translation_editor:menu:title"), $CONFIG->wwwroot . "pg/translation_editor/");
			}
		}
		
		if(defined("upgrading") && (upgrading == "upgrading")){
			translation_editor_actions_hook();
		}
	}
	
	function translation_editor_pagesetup(){
		global $CONFIG;
		
		if(get_context() == "admin" && isadminloggedin()){
			add_submenu_item(elgg_echo("translation_editor:menu:title"), $CONFIG->wwwroot . "pg/translation_editor/");
		}
	}
	
	function translation_editor_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "search":
				$q = get_input("translation_editor_search");
				if(!empty($q)){
					include(dirname(__FILE__) . "/pages/search.php");
					break;
				}
			default:
				if(!empty($page[0])){
					set_input("current_language", $page[0]);
					if(!empty($page[1])){
						set_input("plugin", $page[1]);
					}
					
					include(dirname(__FILE__) . "/pages/index.php");
				} else {
					$current_language = get_current_language();
					forward($CONFIG->wwwroot . "pg/translation_editor/" . $current_language);
				}
				break;
		} 
	}
	
	function translation_editor_plugins_boot_event(){
		global $CONFIG;
		
		run_function_once("translation_editor_version_053");
		
		// add the custom_keys_locations to language paths
		$custom_keys_path = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR . "custom_keys" . DIRECTORY_SEPARATOR;
		if(is_dir($custom_keys_path)){
			$CONFIG->language_paths[$custom_keys_path] = true;
		}   
		
		// force creation of static to prevent reload of unwanted translations
		reload_all_translations(); 
		
		translation_editor_load_custom_languages();
		
		if(get_context() != "translation_editor"){
			// remove disabled languages
			translation_editor_unregister_translations(); 
		}
		
		// load custom translations
		$user_language = get_current_language();
		$elgg_default_language = "en";
		
		$load_languages = array($user_language, $elgg_default_language);
		$load_languages = array_unique($load_languages);
		
		$disabled_languages = translation_editor_get_disabled_languages();
		
		foreach($load_languages as $language){
			if(empty($disabled_languages) || !in_array($language, $disabled_languages)){
				// add custom translations
				translation_editor_load_translations($language);
			}
		}
	}
	
	function translation_editor_version_053(){
		if($languages = get_installed_translations()){
			foreach($languages as $lang => $name){
				translation_editor_merge_translations($lang);
			}
		}
	}
	
	// Plugin init
	register_elgg_event_handler('plugins_boot', 'system', 'translation_editor_plugins_boot_event', 50); // before normal execution to prevent conflicts with plugins like language_selector
	register_elgg_event_handler('init', 'system', 'translation_editor_init');
	register_elgg_event_handler('pagesetup','system','translation_editor_pagesetup');
	
	// register plugin hooks
	register_plugin_hook("action", "admin/plugins/reorder", "translation_editor_actions_hook");
	register_plugin_hook("action", "admin/plugins/enable", "translation_editor_actions_hook");
	register_plugin_hook("action", "admin/plugins/disable", "translation_editor_actions_hook");
	register_plugin_hook("action", "admin/plugins/enableall", "translation_editor_actions_hook");
	register_plugin_hook("action", "admin/plugins/disableall", "translation_editor_actions_hook");
	
	// Register actions
	register_action("translation_editor/translate", false, dirname(__FILE__) . "/actions/translate.php");
	register_action("translation_editor/translate_search", false, dirname(__FILE__) . "/actions/translate_search.php");
	register_action("translation_editor/merge", false, dirname(__FILE__) . "/actions/merge.php");
	
	// Admin only actions
	register_action("translation_editor/make_translation_editor", false, dirname(__FILE__) . "/actions/make_translation_editor.php", true);
	register_action("translation_editor/unmake_translation_editor", false, dirname(__FILE__) . "/actions/unmake_translation_editor.php", true);
	register_action("translation_editor/delete", false, dirname(__FILE__) . "//actions/delete.php", true);
	register_action("translation_editor/disable_languages", false, dirname(__FILE__) . "/actions/disable_languages.php", true);
	register_action("translation_editor/add_language", false, dirname(__FILE__) . "/actions/add_language.php", true);
	register_action("translation_editor/add_custom_key", false, dirname(__FILE__) . "/actions/add_custom_key.php", true);
	register_action("translation_editor/delete_language", false, dirname(__FILE__) . "/actions/delete_language.php", true);
	