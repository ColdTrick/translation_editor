<?php
/**
 * Show the search form
 */

$form_data = '<table><tr>';
$form_data .= '<td>';
$form_data .= elgg_view('input/text', [
	'name' => 'q',
	'value' => elgg_extract('query', $vars),
	'placeholder' => elgg_echo('translation_editor:forms:search:default'),
]);
$form_data .= '</td>';

$form_data .= '<td>';
$form_data .= elgg_view('input/hidden', [
	'name' => 'language',
	'value' => elgg_extract('current_language', $vars),
]);
$form_data .= elgg_view('input/submit', [
	'value' => elgg_echo('search'),
	'class' => 'elgg-button-submit mlm',
]);
$form_data .= '</td>';

$form_data .= '</tr></table>';

echo $form_data;
