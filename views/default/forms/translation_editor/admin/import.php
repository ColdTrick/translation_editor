<?php
/**
 * Import a custom translations export file
 *
 * @uses $vars['current_language'] the language to import for
 */

$language = elgg_extract('current_language', $vars);
if (empty($language)) {
	return;
}

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('translation_editor:import:file'),
	'name' => 'import',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'language',
	'value' => $language,
]);

// footer
$fields = [];

$last_import = elgg_view('translation_editor/last_import', [
	'language' => $language,
]);
if (!empty($last_import)) {
	$fields[] = [
		'#html' => $last_import,
	];
}

$fields[] = [
	'#type' => 'submit',
	'icon' => 'file-import',
	'value' => elgg_echo('import'),
];

if (count($fields) > 1) {
	$footer = elgg_view_field([
		'#type' => 'fieldset',
		'fields' => $fields,
	]);
} else {
	$footer = elgg_view_field($fields[0]);
}

elgg_set_form_footer($footer);
