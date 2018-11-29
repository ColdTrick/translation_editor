<?php
/**
 * Edit the translations of a plugin
 *
 * @uses $vars['current_language'] the language to edit
 * @uses $vars['plugin_id'] the plugin id for the plugin to edit
 */

// Get inputs
$current_language = elgg_extract('current_language', $vars);
$plugin = elgg_extract('plugin_id', $vars);

$translations = get_installed_translations();
if (!(array_key_exists($current_language, $translations))) {
	forward(elgg_generate_url('default:translation_editor', [
		'current_language' => get_current_language(),
	]));
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor', [
	'current_language' => get_current_language(),
]));

// add current language to breadcrumb
$translated_language = $current_language;
if (elgg_language_key_exists($current_language, $current_language)) {
	$translated_language = elgg_echo($current_language, [], $current_language);
} elseif (elgg_language_key_exists($current_language)) {
	$translated_language = elgg_echo($current_language);
}
elgg_push_breadcrumb($translated_language, elgg_generate_url('default:translation_editor', [
	'current_language' => $current_language,
]));
set_input('current_language', $current_language);

// build page elements
$languages = array_keys($translations);

$disabled_languages = translation_editor_get_disabled_languages();
$site_language = elgg_get_config('language', 'en');

$body = elgg_view('translation_editor/language_selector', [
	'current_language' => $current_language,
	'plugin' => $plugin,
	'languages' => $languages,
	'disabled_languages' => $disabled_languages,
	'site_language' => $site_language
]);

// show plugin keys
elgg_push_breadcrumb($plugin, elgg_generate_url('default:translation_editor:plugin', [
	'current_language' => $current_language,
	'plugin_id' => $plugin,
]));
set_input('plugin_id', $plugin);

// new title
$title_text = elgg_echo('translation_editor:menu:title:plugin', [$plugin, $translated_language]);

// page elements
$translation = translation_editor_get_plugin($current_language, $plugin);
if (($plugin == 'custom_keys') && elgg_is_admin_logged_in()) {
	$form = elgg_view_form('translation_editor/admin/add_custom_key');
	
	$body .= elgg_view_module('info', elgg_echo('translation_editor:custom_keys:title'), $form);
}

$body_vars = [
	'plugin' => $plugin,
	'current_language' => $current_language,
	'translation' => $translation,
];
$body .= elgg_view('translation_editor/plugin_edit', $body_vars);

// Build page
$page_data = elgg_view_layout('one_column', [
	'title' => $title_text,
	'content' => $body,
]);

echo elgg_view_page($title_text, $page_data);
