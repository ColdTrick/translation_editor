<?php
/**
 * Show the remote import option if all requirements are met
 *
 * @uses $vars['language'] the language to import
 */

$language = elgg_extract('language', $vars);
if (empty($language)) {
	return;
}

if (!elgg_is_active_plugin('web_services')) {
	return;
}

$plugin = elgg_get_plugin_from_id('translation_editor');
if (empty($plugin->remote_host) || empty($plugin->remote_public_key)) {
	return;
}

elgg_import_esm('forms/translation_editor/admin/remote');

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'language',
	'value' => $language,
]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('translation_editor:import:remote:description', [
		$plugin->remote_host,
	]),
]);

$trans_language = $language;
if (elgg_language_key_exists($language, $language)) {
	$trans_language = elgg_echo($language, [], $language);
} elseif (elgg_language_key_exists($language)) {
	$trans_language = elgg_echo($language);
}

echo elgg_view_message('warning', elgg_echo('translation_editor:import:remote:warning', [$trans_language]));

// add plugin selection
$plugins = elgg_get_plugins();
$value = [
	'core',
];
$options_values = [];
foreach ($plugins as $plugin) {
	$value[] = $plugin->getID();
	$options_values[$plugin->getID()] = $plugin->getID() . elgg_format_element('span', ['class' => ['elgg-subtext', 'mls']], $plugin->getDisplayName());
}

ksort($options_values);

$options_values = ['core' => 'core'] + $options_values;

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'button',
			'id' => 'translation-editor-import-filter',
			'icon' => 'filter',
			'text' => elgg_echo('translation_editor:import:remote:plugins:filter'),
		],
		[
			'#type' => 'button',
			'#class' => 'hidden',
			'id' => 'translation-editor-import-all',
			'icon' => 'check',
			'text' => elgg_echo('translation_editor:import:remote:plugins:all'),
		],
	],
	'align' => 'horizontal',
]);

echo elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('translation_editor:import:remote:plugins'),
	'#class' => 'translation-editor-import-plugin-selection hidden',
	'name' => 'plugins',
	'options_values' => $options_values,
	'value' => $value,
	'default' => false,
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
	'icon' => 'cloud-download-alt',
	'text' => elgg_echo('import'),
	'confirm' => true,
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
