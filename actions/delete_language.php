<?php
/**
 * Delete a custom added language
 */

$language = get_input("language");
if (!empty($language) && ($language != "en")) {
	
	// only remove untranslated languages
	$completeness = translation_editor_get_language_completeness($language);
	if ($completeness == 0) {
		// get all the custom languages
		$custom_languages = elgg_get_plugin_setting("custom_languages", "translation_editor");
		if (!empty($custom_languages)) {
			$custom_languages = explode(",", $custom_languages);
			
			$index = array_search($language, $custom_languages);
			if ($index !== false) {
				unset($custom_languages[$index]);
				
				$code = implode(",", array_unique($custom_languages));

				elgg_set_plugin_setting("custom_languages", $code, "translation_editor");
				system_message(elgg_echo("translation_editor:action:delete_language:success"));
			}
		}
	}
}

forward(REFERER);
