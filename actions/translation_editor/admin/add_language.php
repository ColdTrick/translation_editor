<?php
/**
 * Add a language without the need for a plugin to provide a part
 */

$code = get_input('code');
if (empty($code)) {
	return elgg_error_response();
}

// check for existing custom languages
$custom_languages = elgg_get_plugin_setting('custom_languages', 'translation_editor');
if (!empty($custom_languages)) {
	$custom_languages = string_to_tag_array($custom_languages);
	$custom_languages[] = $code;
	
	$code = implode(',', array_unique($custom_languages));
}

elgg_set_plugin_setting('custom_languages', $code, 'translation_editor');

// invalidate cache
elgg_flush_caches();

return elgg_ok_response('', elgg_echo('translation_editor:action:add_language:success'));
