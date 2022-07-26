<?php
/**
 * Form to add a custom language
 */

$form_vars = [
	'id' => 'translation-editor-add-language-form',
	'class' => 'hidden',
];
$form = elgg_view_form('translation_editor/admin/add_language', $form_vars);

$toggle_link = elgg_view('output/url', [
	'icon' => 'plus',
	'text' => elgg_echo('translation_editor:language_selector:add_language'),
	'href' => '#translation-editor-add-language-form',
	'class' => 'elgg-toggle',
]);

echo elgg_format_element('div', ['class' => 'mbm'], $toggle_link . $form);
