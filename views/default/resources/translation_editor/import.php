<?php

$current_language = elgg_extract('current_language', $vars);

$translated_language = $current_language;
if (elgg_language_key_exists($current_language, $current_language)) {
	$translated_language = elgg_echo($current_language, [], $current_language);
} elseif (elgg_language_key_exists($current_language)) {
	$translated_language = elgg_echo($current_language);
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor'));
elgg_push_breadcrumb($translated_language, elgg_generate_url('default:translation_editor', [
	'current_language' => $translated_language,
]));

echo elgg_view_page(elgg_echo('translation_editor:import'), [
	'content' => elgg_view_form('translation_editor/admin/import', [], [
		'current_language' => $current_language,
	]),
]);
