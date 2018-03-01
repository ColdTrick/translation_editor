<?php
/**
 * show a form to edit the language of a plugin
 */

$current_language = elgg_extract('current_language', $vars);
$plugin = elgg_extract('plugin', $vars);

$working_translation = elgg_extract('translation', $vars);
$english = elgg_extract('en', $working_translation);
$translated_language = elgg_extract('current_language', $working_translation);
$custom = elgg_extract('custom', $working_translation);

$missing_count = 0;
$equal_count = 0;
$params_count = 0;
$custom_count = 0;

$translation = [];
if (!empty($english)) {
	foreach ($english as $en_key => $en_value) {
		$en_params = translation_editor_get_string_parameters($en_value);
		$cur_params = 0;
		if (array_key_exists($en_key, $translated_language)) {
			$cur_params = translation_editor_get_string_parameters($translated_language[$en_key]);
		}
		
		$row_rel = null;
		if (!array_key_exists($en_key, $translated_language)) {
			$row_rel = 'missing';
			$missing_count++;
		} elseif ($en_value == $translated_language[$en_key]) {
			$row_rel = 'equal';
			$equal_count++;
		} elseif ($en_params != $cur_params) {
			$row_rel = 'params';
			$params_count++;
		} elseif (array_key_exists($en_key, $custom)) {
			$row_rel = 'custom';
			$custom_count++;
		}
		
		$translation[] = elgg_view('input/te_translation', [
			'english' => [
				'key' => $en_key,
				'value' => $en_value,
			],
			'translation' => [
				'key' => $en_key,
				'value' => elgg_extract($en_key, $translated_language),
			],
			'plugin' => $plugin,
			'language' => $current_language,
			'row_rel' => $row_rel,
		]);
	}
}
$table_content = elgg_format_element('tbody', [], implode(PHP_EOL, $translation));

$selected_view_mode = 'missing';
$table_class = [
	'elgg-table',
	'translation-editor-translation-table',
];
if (empty($missing_count)) {
	$selected_view_mode = 'all';
	$table_class[] = 'translation-editor-translation-table-no-missing';
}

// toggle between different filters
elgg_require_js('translation_editor/toggle_translations');

$menu_items = [];

$menu_items[] = [
	'name' => 'title',
	'text' => elgg_echo('translation_editor:plugin_edit:show'),
	'href' => false,
];

$menu_items[] = [
	'name' => 'missing',
	'text' => elgg_echo('translation_editor:plugin_edit:show:missing'),
	'href' => false,
	'rel' => 'missing',
	'badge' => $missing_count,
	'selected' => $selected_view_mode === 'missing',
];
$menu_items[] = [
	'name' => 'equal',
	'text' => elgg_echo('translation_editor:plugin_edit:show:equal'),
	'href' => false,
	'rel' => 'equal',
	'badge' => $equal_count,
	'selected' => $selected_view_mode === 'equal',
];
$menu_items[] = [
	'name' => 'params',
	'text' => elgg_echo('translation_editor:plugin_edit:show:params'),
	'href' => false,
	'rel' => 'params',
	'badge' => $params_count,
	'selected' => $selected_view_mode === 'params',
];
$menu_items[] = [
	'name' => 'custom',
	'text' => elgg_echo('translation_editor:plugin_edit:show:custom'),
	'href' => false,
	'rel' => 'custom',
	'badge' => $custom_count,
	'selected' => $selected_view_mode === 'custom',
];
$menu_items[] = [
	'name' => 'all',
	'text' => elgg_echo('translation_editor:plugin_edit:show:all'),
	'href' => false,
	'rel' => 'all',
	'badge' => $working_translation['total'],
	'selected' => $selected_view_mode === 'all',
];

// show all
$table = elgg_format_element('table', ['class' => $table_class], $table_content);

echo elgg_view_module('info', elgg_echo('translation_editor:plugin_edit:title') . ' ' . $plugin, $table, [
	'menu' => elgg_view_menu('translation-editor-plugin-edit', [
		'items' => $menu_items,
		'class' => 'elgg-menu-hz',
	]),
]);
