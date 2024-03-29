<?php
/**
 * Add a language without the need for a plugin to provide a part
 */

$code = get_input('code');
if (empty($code)) {
	return elgg_error_response();
}

// check for existing custom languages
$custom_languages = (string) elgg_get_plugin_setting('custom_languages', 'translation_editor');
if (!empty($custom_languages)) {
	$custom_languages = elgg_string_to_array($custom_languages);
	$custom_languages[] = $code;
	
	$code = implode(',', array_unique($custom_languages));
}

$plugin = elgg_get_plugin_from_id('translation_editor');
$plugin->setSetting('custom_languages', $code);

// invalidate cache
elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('translation_editor:action:add_language:success'));
