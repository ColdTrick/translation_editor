<?php
/**
 * Intermediate view to handle translation import
 *
 * @uses $vars['language'] the language to import for
 */

$language = elgg_extract('language', $vars);

$tabs = [];

// remote
$remote = elgg_view_form('translation_editor/admin/remote', [], [
	'language' => $language,
]);
if (!empty($remote)) {
	$tabs[] = [
		'text' => elgg_echo('translation_editor:import:remote:title'),
		'content' => $remote,
	];
}

// file import
$tabs[] = [
	'text' => elgg_echo('translation_editor:import:file:title'),
	'content' => elgg_view_form('translation_editor/admin/import', [], [
		'current_language' => $language,
	]),
];

if (count($tabs) === 1) {
	echo $tabs[0]['content'];
	return;
}

echo elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);
