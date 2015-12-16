<?php
/**
 * Form to add a custom language key
 */

$form_body = '<div>';
$form_body .= elgg_format_element('label', ['for' => 'translation-editor-add-key-key'], elgg_echo('translation_editor:custom_keys:key'));
$form_body .= elgg_view('input/text', [
	'name' => 'key',
	'id' => 'translation-editor-add-key-key',
]);

$form_body .= elgg_format_element('label', ['for' => 'translation-editor-add-key-value'], elgg_echo('translation_editor:custom_keys:translation'));
$form_body .= elgg_view('input/plaintext', [
	'name' => 'translation',
	'rows' => 3,
	'id' => 'translation-editor-add-key-value',
]);
$form_body .= elgg_format_element('span', ['class' => 'elgg-quiet'], elgg_echo('translation_editor:custom_keys:translation_info'));
$form_body .= '</div>';

$form_body .= elgg_view('input/submit', [
	'value' => elgg_echo('save'),
]);

echo elgg_view_module('info', elgg_echo('translation_editor:custom_keys:title'), $form_body);
