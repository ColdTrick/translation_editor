<?php
/**
 * Display a row in a table to be translated
 *
 * @uses $vars['english'] as array('key' => $language_key, 'value' => $english_value)
 * @uses $vars['original'] as array('key' => $language_key, 'value' =>  => $translated_value)
 * @uses $vars['translation'] as array('key' => $language_key, 'value' =>  => $translated_value)
 * @uses $vars['language'] the language being translated
 * @uses $vars['row_rel'] a special rel to put on the rows
 * @uses $vars['plugin'] plugin id
 */

elgg_require_js('elgg/toggle');
elgg_require_js('translation_editor/edit_translation');

$current_language = elgg_extract('language', $vars);
$english = elgg_extract('english', $vars);

$original = elgg_extract('original', $vars);
$original_value = elgg_extract('value', $original);

$translation = elgg_extract('translation', $vars);
$translation_value = elgg_extract('value', $translation);
$key = elgg_extract('key', $translation);

$plugin = elgg_extract('plugin', $vars);
$row_rel = elgg_extract('row_rel', $vars);

$show_original_translation = false;
$toggle_id = elgg_get_friendly_title("{$plugin}-{$key}"); // friendly title replaces colons with underscores
if (!elgg_is_empty($original_value) && !elgg_is_empty($translation_value) && $original_value !== $translation_value && $current_language !== 'en') {
	$show_original_translation = true;
}

$rows = [];

// English information
$row = [];
$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', ['language' => 'en']));
$row[] = elgg_format_element('td', ['class' => 'translation-editor-translation-english'], elgg_format_element('pre', [], nl2br(htmlspecialchars($english['value']))));

$icons = '';
if ($show_original_translation) {
	$icons .= elgg_view_icon('eye', [
		'title' => elgg_echo('translation_editor:show_original'),
		'class' => 'elgg-toggle',
		'data-toggle-selector' => "#{$toggle_id}",
	]);
}
$icons .= elgg_view_icon('key', ['title' => $english['key']]);

$row[] = elgg_format_element('td', ['class' => 'translation-editor-translation-key'], $icons);

$rows[] = elgg_format_element('tr', ['rel' => $row_rel], implode(PHP_EOL, $row));

// original translation
if ($show_original_translation) {
	$row = [];
	$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', ['language' => $current_language]));
	$row[] = elgg_format_element('td', ['colspan' => 2], elgg_format_element('pre', [], nl2br(htmlspecialchars($original_value))));
	
	$rows[] = elgg_format_element('tr', ['id' => $toggle_id, 'class' => 'translation-editor-original', 'data-toggle-slide' => '0'], implode(PHP_EOL, $row));
}

// Custom language information
$row = [];
$row_count = max(2, count(explode('\n', $translation_value)));


$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', ['language' => $current_language]));
$row[] = elgg_format_element('td', ['colspan' => 2], elgg_view('input/plaintext', [
	'name' => "translation[{$current_language}][{$plugin}][{$key}]",
	'value' => $translation_value,
	'rows' => $row_count,
	'class' => 'translation-editor-input',
]));

$rows[] = elgg_format_element('tr', ['rel' => $row_rel], implode(PHP_EOL, $row));

echo implode(PHP_EOL, $rows);
