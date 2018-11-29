<?php
/**
 * Add translations for the current plugin
 */

$translation = get_input('translation');

if (!translation_editor_is_translation_editor()) {
	return elgg_error_response(elgg_echo('translation_editor:action:translate:error:not_authorized'));
}

if (!is_array($translation)) {
	return elgg_error_response(elgg_echo('translation_editor:action:translate:error:input'));
}

$trans = get_installed_translations();

foreach ($translation as $language => $plugins) {
	
	if (!array_key_exists($language, $trans)) {
		continue;
	}
	
	if (!is_array($plugins)) {
		continue;
	}
	
	foreach ($plugins as $plugin_name => $translate_input) {
		
		if (!is_array($translate_input)) {
			continue;
		}
		
		// get plugin translation
		$plugin_translation = translation_editor_get_plugin($language, $plugin_name);
		
		// merge with existing custom translations
		$custom_translation = elgg_extract('custom', $plugin_translation);
		if (!empty($custom_translation)) {
			$translate_input = array_merge($custom_translation, $translate_input);
		}
		
		// get original plugin keys
		$original_keys = elgg_extract('en', $plugin_translation);
		// only keep keys which are present in the plugin
		$translate_input = array_intersect_key($translate_input, $original_keys);
		
		// check if translated
		$plugin_original = elgg_extract('original_language', $plugin_translation);
		
		$translated = translation_editor_compare_translations($translate_input, $plugin_original);
		if (!empty($translated)) {
			if (translation_editor_write_translation($language, $plugin_name, $translated)) {
				system_message(elgg_echo('translation_editor:action:translate:success'));
			} else {
				register_error(elgg_echo('translation_editor:action:translate:error:write'));
			}
		} else {
			translation_editor_delete_translation($language, $plugin_name);
			system_message(elgg_echo('translation_editor:action:translate:success'));
		}
	}
	
	// merge translations
	translation_editor_merge_translations($language);
}

return elgg_ok_response();
