<?php
/**
 * Show a notification to the editor that some custom translations were cleaned
 */

$current_translation = elgg_extract('current_language', $vars);

$file_name = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR . $current_translation . DIRECTORY_SEPARATOR . 'translation_editor_cleanup.json';
if (!file_exists($file_name)) {
	// nothing was cleaned up
	return;
}

$content = file_get_contents($file_name);
$cleaned = json_decode($content, true);
$count = 0;
foreach ($cleaned as $plugin_id => $removed_translations){
	$count += count($removed_translations);
}

$download = elgg_view('output/url', [
	'icon' => 'download',
	'text' => elgg_echo('download'),
	'href' => elgg_generate_action_url('translation_editor/cleanup/download', [
		'language' => $current_translation,
	]),
	'class' => 'elgg-button elgg-button-action',
]);

$remove = elgg_view('output/url', [
	'text' => elgg_echo('delete'),
	'icon' => 'trash-alt',
	'href' => elgg_generate_action_url('translation_editor/cleanup/remove', [
		'language' => $current_translation,
	]),
	'confirm' => elgg_echo('deleteconfirm'),
	'class' => 'elgg-button elgg-button-delete',
]);

$content = elgg_format_element('div', [
	'class' => 'elgg-output mtn',
], elgg_echo('translation_editor:cleanup:description', [$count]));

echo elgg_view_module('info', elgg_echo('translation_editor:cleanup:title'), $content, [
	'menu' => $download . $remove,
]);
