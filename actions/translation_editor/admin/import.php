<?php
/**
 * Exports the custom translations for the selected plugins
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

$language = get_input('language');

$import = elgg_get_uploaded_file('import');
if (!$import instanceof UploadedFile) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$json = file_get_contents($import->getPathname());

$translated_language = $language;
if (elgg_language_key_exists($language, $language)) {
	$translated_language = elgg_echo($language, [], $language);
} elseif (elgg_language_key_exists($language)) {
	$translated_language = elgg_echo($language);
}

$data = json_decode($json, true);
if (!array_key_exists($language, $data)) {
	return elgg_error_response(elgg_echo('translation_editor:action:import:incorrect_language', [$translated_language]));
}

$plugins = elgg_extract($language, $data);
if (empty($data[$language]) || !is_array($plugins)) {
	return elgg_error_response(elgg_echo('translation_editor:action:import:no_plugins'));
}

foreach ($plugins as $plugin => $translations) {
	if (empty($translations) || !is_array($translations)) {
		continue;
	}
	
	if ($plugin !== 'core' && !elgg_get_plugin_from_id($plugin)) {
		continue;
	}
	
	translation_editor_write_translation($language, $plugin, $translations);
}

translation_editor_merge_translations($language);
translation_editor_log_last_import($language);

return elgg_ok_response('', elgg_echo('translation_editor:action:import:success'), elgg_generate_url('default:translation_editor', ['current_language' => $language]));
