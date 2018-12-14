<?php
/**
 * Download the custom trnslations for the provided plugin
 */
	
$current_language = get_input('current_language');
$plugin = get_input('plugin');

if (!translation_editor_is_translation_editor()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$translation = translation_editor_get_plugin($current_language, $plugin);
$translation = $translation['current_language'];

$content = [];
$content[] = '<?php';
$content[] = '/**';
$content[] = ' * This file was created by Translation Editor v' . elgg_get_plugin_from_id('translation_editor')->getManifest()->getVersion();
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
