<?php
/**
 * Form to add a custom language
 */

$form_vars = [
	'id' => 'translation_editor_add_language_form',
	'class' => 'hidden',
];
$form = elgg_view_form('translation_editor/admin/add_language', $form_vars);

$toggle_link = elgg_view('output/url', [
	'text' => elgg_echo('translation_editor:language_selector:add_language'),
	'icon' => 'plus',
	'href' => '#translation_editor_add_language_form',
	'rel' => 'toggle',
]);

echo elgg_format_element('div', ['class' => 'mbm'], $toggle_link . $form);
