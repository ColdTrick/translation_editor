<?php
/**
 * Show the search form
 */

$current_language = elgg_extract('current_language', $vars);
$q = elgg_extract('query', $vars);

// build form
$form_data = '<table><tr>';
$form_data .= '<td>';
$form_data .= elgg_view('input/text', [
	'name' => 'q',
	'value' => $q,
	'placeholder' => elgg_echo('translation_editor:forms:search:default'),
]);
$form_data .= '</td>';

$form_data .= '<td>';
$form_data .= elgg_view('input/hidden', [
	'name' => 'language',
	'value' => $current_language,
]);
$form_data .= elgg_view('input/submit', [
	'value' => elgg_echo('search'),
	'class' => 'elgg-button-submit mlm',
]);
$form_data .= '</td>';

$form_data .= '</tr></table>';

echo $form_data;
