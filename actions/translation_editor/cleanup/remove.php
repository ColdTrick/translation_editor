<?php

translation_editor_gatekeeper();

$language = get_input('language');
if (empty($language)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$base_path = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR;
$filename = $base_path . $language . DIRECTORY_SEPARATOR . 'translation_editor_cleanup.json';
$filename = sanitise_filepath($filename, false);
if (!file_exists($filename)) {
	return elgg_error_response(elgg_echo('translation_editor:action:cleanup:remove:error:no_file'));
}

if (!unlink($filename)) {
	return elgg_error_response(elgg_echo('translation_editor:action:cleanup:remove:error:remove'));
}

return elgg_ok_response('', elgg_echo('translation_editor:action:cleanup:remove:success'));
