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

// build page elements
$title_text = elgg_echo('translation_editor:import');

// build search form
$form_vars = [
	'enctype' => 'multipart/form-data',
];

$body_vars = [
	'current_language' => $current_language,
];
$body = elgg_view_form('translation_editor/admin/import', $form_vars, $body_vars);

// draw page
echo elgg_view_page($title_text, [
	'content' => $body,
]);
