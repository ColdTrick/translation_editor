<?php
/**
 * Add a custom key to the translations.
 */

$key = get_input('key');
$translation = get_input('translation');

if (empty($key) || empty($translation)) {
	return elgg_error_response(elgg_echo('translation_editor:action:add_custom_key:missing_input'));
}

if (is_numeric($key)) {
	return elgg_error_response(elgg_echo('translation_editor:action:add_custom_key:key_numeric'));
}

if (!preg_match('/^[a-zA-Z0-9_:]{1,}$/', $key)) {
	return elgg_error_response(elgg_echo('translation_editor:action:add_custom_key:invalid_chars'));
}

if (elgg_language_key_exists($key, 'en')) {
	return elgg_error_response(elgg_echo('translation_editor:action:add_custom_key:exists'));
}
	
// save
$custom_translations = translation_editor_get_plugin('en', 'custom_keys');
if (!empty($custom_translations)) {
	$custom_translations = $custom_translations['en'];
} else {
	$custom_translations = [];
}

$custom_translations[$key] = $translation;

$base_dir = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR;
if (!file_exists($base_dir)) {
	mkdir($base_dir, 0755, true);
}

$location = $base_dir . 'custom_keys' . DIRECTORY_SEPARATOR;
if (!file_exists($location)) {
	mkdir($location, 0755, true);
}

$file_contents = '<?php' . PHP_EOL;
$file_contents .= 'return ';
$file_contents .= var_export($custom_translations, true);
$file_contents .= ';' . PHP_EOL;

if (!file_put_contents($location . 'en.php', $file_contents)) {
	return elgg_error_response(elgg_echo('translation_editor:action:add_custom_key:file_error'));
}
	
// invalidate cache
elgg_flush_caches();

return elgg_ok_response('', elgg_echo('translation_editor:action:add_custom_key:success'));
