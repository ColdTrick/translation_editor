<?php
/**
 * Delete a custom added language
 */

$language = get_input('language');
if (empty($language) || ($language === 'en')) {
	return elgg_error_response();
}

// only remove untranslated languages
$completeness = translation_editor_get_language_completeness($language);
if ($completeness !== (float) 0) {
	return elgg_error_response();
}

// get all the custom languages
$custom_languages = (string) elgg_get_plugin_setting('custom_languages', 'translation_editor');
if (empty($custom_languages)) {
	return elgg_ok_response();
}

$custom_languages = elgg_string_to_array($custom_languages);

$index = array_search($language, $custom_languages);
if ($index === false) {
	return elgg_ok_response();
}

unset($custom_languages[$index]);

$code = implode(',', array_unique($custom_languages));

$plugin = elgg_get_plugin_from_id('translation_editor');
$plugin->setSetting('custom_languages', $code);

// invalidate cache
elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('translation_editor:action:delete_language:success'));
