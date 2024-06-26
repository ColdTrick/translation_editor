<?php
/**
 * Download the custom translations for the provided plugin
 */
	
$current_language = get_input('current_language');
$plugin = get_input('plugin');

if (empty($current_language) || empty($plugin)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$translation = translation_editor_get_plugin($current_language, $plugin);
if (empty($translation)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$translation = $translation['current_language'];

$content = [];
$content[] = '<?php';
$content[] = '/**';
$content[] = ' * This file was created by Translation Editor v' . elgg_get_plugin_from_id('translation_editor')->getVersion();
$content[] = ' * On ' . date('Y-m-d H:i');
$content[] = ' */' . PHP_EOL;
$content[] = 'return ' . var_export($translation, true) . ';' . PHP_EOL;

$content = implode(PHP_EOL, $content);

// start output
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: Attachment; filename="' . $current_language . '.php"');
header('Content-Length: ' . strlen($content));
header('Pragma: public');

echo $content;

exit();
