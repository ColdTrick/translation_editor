<?php
/**
 * Display a row in a table to be translated
 *
 * @uses $vars['english'] as array('key' => $language_key, 'value' => $english_value)
 * @uses $vars['translation'] as array('key' => $language_key, 'value' =>  => $translated_value)
 * @uses $vars['language'] the language being translated
 * @uses $vars['row_rel'] a special rel to put on the rows
 * @uses $vars['plugin'] plugin id
 */

elgg_require_js('translation_editor/edit_translation');

$current_language = elgg_extract('language', $vars);
$english = elgg_extract('english', $vars);
$translation = elgg_extract('translation', $vars);
$plugin = elgg_extract('plugin', $vars);
$row_rel = elgg_extract('row_rel', $vars);

$rows = [];

// English information
$row = [];
$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', ['language' => 'en']));
$row[] = elgg_format_element('td', [
	'class' => 'translation-editor-translation-english',
], elgg_format_element('pre', [], nl2br(htmlspecialchars($english['value']))));
$row[] = elgg_format_element('td', ['class' => 'translation-editor-translation-key'], elgg_view_icon('key', ['title' => $english['key']]));

$rows[] = elgg_format_element('tr', ['rel' => $row_rel], implode(PHP_EOL, $row));

// Custom language information
$row = [];
$translation_value = elgg_extract('value', $translation);
$row_count = max(2, count(explode('\n', $translation_value)));
$key = elgg_extract('key', $translation);

$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', ['language' => $current_language]));
$row[] = elgg_format_element('td', ['colspan' => 2], elgg_view('input/plaintext', [
	'name' => "translation[{$current_language}][{$plugin}][{$key}]",
	'value' => $translation_value,
	'rows' => $row_count,
	'class' => 'translation-editor-input',
]));

$rows[] = elgg_format_element('tr', ['rel' => $row_rel], implode(PHP_EOL, $row));

echo implode(PHP_EOL, $rows);
