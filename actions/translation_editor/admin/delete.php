<?php
/**
 * Delete the custom translations for the provided plugin
 */

$lang = get_input('current_language');
$plugin = get_input('plugin');

if (empty($lang) || empty($plugin)) {
	return elgg_error_response(elgg_echo('translation_editor:action:delete:error:input'));
}

if (!translation_editor_delete_translation($lang, $plugin)) {
	return elgg_error_response(elgg_echo('translation_editor:action:delete:error:delete'));
}

// merge translations
translation_editor_merge_translations($lang);

return elgg_ok_response('', elgg_echo('translation_editor:action:delete:success'), "translation_editor/{$lang}");
